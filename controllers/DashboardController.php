<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Like.php';   // JSON Like Model
require_once __DIR__ . '/../models/Share.php';  // JSON Share Model (NEW)

class DashboardController {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ==========================================================
    // DASHBOARD PAGE
    // ==========================================================
    public function index()
    {
        // ===========================
        // TOTAL POSTS
        // ===========================
        $stmt = $this->db->prepare("SELECT COUNT(*) AS c FROM posts");
        $stmt->execute();
        $totalPosts = $stmt->fetch(PDO::FETCH_ASSOC)['c'];

        // ===========================
        // TOTAL COMMENTS
        // ===========================
        $stmt = $this->db->prepare("SELECT COUNT(*) AS c FROM comments");
        $stmt->execute();
        $totalComments = $stmt->fetch(PDO::FETCH_ASSOC)['c'];

        // ===========================
        // TOTAL LIKES (JSON MODEL)
        // ===========================
        $likeModel = new Like();
        $totalLikes = $likeModel->getTotal();

        // ===========================
        // TOTAL SHARES (JSON MODEL)
        // ===========================
        $shareModel = new Share();
        $totalShares = $shareModel->getTotal();

        // ===========================
        // LAST 10 POSTS
        // ===========================
        $sql = "
            SELECT 
                posts.*,
                users.username,
                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count
            FROM posts
            JOIN users ON users.id = posts.user_id
            ORDER BY posts.id DESC
            LIMIT 10
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $latestPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ======================================================
        // LOAD VIEW
        // ======================================================
        ob_start();
        include __DIR__ . '/../views/dashboard/index.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }
}
