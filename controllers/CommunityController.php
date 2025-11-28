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
        $feed = $postModel->getUnifiedFeed();

        // Make variables accessible in the view
        $GLOBALS['feed'] = $feed;
        $GLOBALS['postModel'] = $postModel;

        ob_start();
        include __DIR__ . '/../views/community/index.php';
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


}
