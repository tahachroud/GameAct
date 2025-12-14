<?php

require_once __DIR__ . '/../models/Post.php';

class CommunityController {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ======================================================
    // FEED PAGE (POSTS + SHARES)
    // ======================================================
    public function index()
    {
        $postModel = new Post($this->db);

        // unified feed sorted by date
$selectedTag = $_GET['tag'] ?? null;

if ($selectedTag) {
    $feed = $postModel->getByHashtag($selectedTag);
} else {
    $feed = $postModel->getUnifiedFeed();
}

        // compute trending posts (based on likes + comments)
        $likesJsonPath = __DIR__ . '/../public/admin_likes.json';
        $likesData = [];
        if (file_exists($likesJsonPath)) {
            $likesContent = @file_get_contents($likesJsonPath);
            $likesArr = json_decode($likesContent, true);
            if (is_array($likesArr) && isset($likesArr['posts'])) $likesData = $likesArr['posts'];
        }

        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment($this->db);

        $trendingCandidates = [];
        foreach ($feed as $p) {
            $pid = $p['id'];
            $likesCount = isset($likesData[$pid]) ? (int)$likesData[$pid] : 0;
            $commentsCount = (int)$commentModel->countByPost($pid);
            $score = $likesCount + $commentsCount; // simple scoring: likes + comments
            $trendingCandidates[] = array_merge($p, [
                'likes_count' => $likesCount,
                'comments_count' => $commentsCount,
                'trend_score' => $score
            ]);
        }

        usort($trendingCandidates, function ($a, $b) {
            return $b['trend_score'] <=> $a['trend_score'];
        });

        $trending = array_slice($trendingCandidates, 0, 5);

        // compute top contributors for this month
        $topContributors = $this->getTopContributors($commentModel, $likesData);

        // fetch active poll
        require_once __DIR__ . '/../models/Poll.php';
        $pollModel = new Poll();
        $activePoll = $pollModel->getActive();
        $hashtagsFile = __DIR__ . '/../public/hashtags.json';
$topHashtags = [];

if (file_exists($hashtagsFile)) {
    $hashtags = json_decode(file_get_contents($hashtagsFile), true) ?? [];
    arsort($hashtags);
    $topHashtags = array_slice($hashtags, 0, 3, true);
}

        // Make variables accessible in the view
        $GLOBALS['feed'] = $feed;
        $GLOBALS['postModel'] = $postModel;
        $GLOBALS['trending'] = $trending;
        $GLOBALS['topContributors'] = $topContributors;
        $GLOBALS['activePoll'] = $activePoll;
        $GLOBALS['topHashtags'] = $topHashtags;
        $GLOBALS['selectedTag'] = $selectedTag;

        ob_start();
        include __DIR__ . '/../views/community/index-community.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout_front.php';
    }

    // ======================================================
    // AJAX LIKE UPDATE
    // ======================================================
    public function updateLikesAjax()
    {
        require_once __DIR__ . '/../models/Like.php';

        $postId = $_POST['post_id'] ?? null;
        $liked = $_POST['liked'] === "true";

        if (!$postId) {
            echo json_encode(["error" => "Missing post id"]);
            return;
        }

        $like = new Like();
        $data = $like->updateLike($postId, $liked);

        echo json_encode([
            "success" => true,
            "total_likes" => $data['total_likes'],
            "post_likes" => $data['posts'][$postId]
        ]);
    }

    // ======================================================
    // SHARE COUNTER ONLY
    // ======================================================
    public function updateShareAjax()
    {
        require_once __DIR__ . '/../models/Share.php';

        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            echo json_encode(["error" => "Missing post id"]);
            return;
        }

        $share = new Share();
        $data = $share->updateShare($postId);

        echo json_encode([
            "success" => true,
            "total_shares" => $data['total_shares'],
            "post_shares" => $data['posts'][$postId]
        ]);
    }

    // ======================================================
    // COMPUTE TOP CONTRIBUTORS (posts + comments + likes received this month)
    // ======================================================
    private function getTopContributors($commentModel, $likesData)
    {
        // get all posts from this month
        $monthStart = date('Y-m-01');
        $sql = "SELECT id, user_id, created_at FROM posts WHERE DATE(created_at) >= ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$monthStart]);
        $monthPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // group by user and calculate score
        $userScores = [];
        foreach ($monthPosts as $post) {
            $uid = $post['user_id'];
            if (!isset($userScores[$uid])) {
                $userScores[$uid] = ['posts' => 0, 'comments' => 0, 'likes' => 0, 'last_post' => $post['created_at']];
            }
            $userScores[$uid]['posts']++;
            $userScores[$uid]['likes'] += isset($likesData[$post['id']]) ? (int)$likesData[$post['id']] : 0;
        }

        // count comments by user this month
        $sqlComments = "SELECT user_id, COUNT(*) as cnt FROM comments WHERE DATE(created_at) >= ? GROUP BY user_id";
        $stmtComments = $this->db->prepare($sqlComments);
        $stmtComments->execute([$monthStart]);
        $commentCounts = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
        foreach ($commentCounts as $cc) {
            $uid = $cc['user_id'];
            if (isset($userScores[$uid])) {
                $userScores[$uid]['comments'] = $cc['cnt'];
            }
        }

        // compute engagement score and fetch user info
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User($this->db);

        $contributors = [];
        foreach ($userScores as $uid => $scores) {
            $user = $userModel->find($uid);
            if ($user) {
                $engagementScore = $scores['posts'] * 3 + $scores['comments'] * 2 + $scores['likes'] * 1;
                $contributors[] = array_merge($user, $scores, ['engagement_score' => $engagementScore]);
            }
        }

        // sort by engagement score descending
        usort($contributors, function ($a, $b) {
            return $b['engagement_score'] <=> $a['engagement_score'];
        });

        // return top 3
        return array_slice($contributors, 0, 3);
    }

    // ======================================================
    // SHARE POST (FACEBOOK STYLE)
    // ======================================================
   public function sharePost()
{
    $postId = $_POST['post_id'] ?? null;
    $message = $_POST['message'] ?? "";

    if (!$postId) {
        echo json_encode(["error" => "missing post id"]);
        return;
    }

    // default user
    $userId = 5;

    $postModel = new Post($this->db);
    $shareId = $postModel->createShare($userId, $postId, $message);

    echo json_encode([
        "success" => true,
        "share_id" => $shareId
    ]);
}

    // ======================================================
    // POLL VOTE AJAX
    // ======================================================
    public function voteOnPollAjax()
    {
        require_once __DIR__ . '/../models/Poll.php';

        $pollId = $_POST['poll_id'] ?? null;
        $option = $_POST['option'] ?? null;

        if (!$pollId || !$option) {
            echo json_encode(["error" => "Missing poll_id or option"]);
            return;
        }

        $pollModel = new Poll();
        $updatedPoll = $pollModel->vote($pollId, $option);

        if (!$updatedPoll) {
            echo json_encode(["error" => "Poll not found or inactive"]);
            return;
        }

        $totalVotes = $pollModel->getTotalVotes($pollId);

        echo json_encode([
            "success" => true,
            "poll" => $updatedPoll,
            "total_votes" => $totalVotes
        ]);
    }


}
