<?php 
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/User.php';

class PostController {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /* LIST POSTS (ADMIN) */
    public function index()
    {
        $postModel = new Post($this->db);
        $posts = $postModel->getAll();

        ob_start();
        include __DIR__ . '/../views/posts/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    /* CREATE FORM (ADMIN) */
    public function createForm()
    {
        $userModel = new User($this->db);
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../views/posts/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    /* STORE NEW POST (ADMIN) */
    public function create()
    {
        $errors = [];
        $user_id = trim($_POST['user_id']);
        $content = trim($_POST['content']);

        // VALIDATION
        if (empty($content)) $errors[] = "Le contenu ne peut pas être vide.";
        if (strlen($content) < 3) $errors[] = "Minimum 3 caractères.";
        if (strlen($content) > 500) $errors[] = "Maximum 500 caractères.";

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=posts_create");
            exit();
        }

        // IMAGE
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $folder = __DIR__ . '/../public/uploads/posts/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);
            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $folder . $image);
        }

        // SAVE
        $postModel = new Post($this->db);
        $postModel->create($user_id, htmlspecialchars($content), $image);

        header("Location: index.php?action=posts");
        exit();
    }

    /* UPDATE (ADMIN) */
    public function update()
    {
        $errors = [];
        $id      = trim($_POST['id']);
        $user_id = trim($_POST['user_id']);
        $content = trim($_POST['content']);

        if (empty($content)) $errors[] = "Le contenu ne peut pas être vide.";
        if (strlen($content) < 3) $errors[] = "Minimum 3 caractères.";
        if (strlen($content) > 500) $errors[] = "Maximum 500 caractères.";

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=posts_edit&id=" . $id);
            exit();
        }

        // IMAGE
        $postModel = new Post($this->db);
        $old = $postModel->find($id);
        $image = $old['image'];

        if (!empty($_FILES['image']['name'])) {
            $folder = __DIR__ . '/../public/uploads/posts/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);
            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $folder . $image);
        }

        // UPDATE
        $postModel->update($id, $user_id, htmlspecialchars($content), $image);

        header("Location: index.php?action=posts");
        exit();
    }

    /* DELETE (ADMIN) */
    public function delete()
    {
        $postModel = new Post($this->db);
        $postModel->delete($_GET['id']);

        header("Location: index.php?action=posts");
        exit();
    }

    /* ===============================
       FRONTEND POST CREATION (FIXED)
    ================================= */
    public function createFromFront()
    {
        // DEFAULT USER ID (must exist in users table)
        $user_id = 5;  

        $content = trim($_POST['content']);

        /* ---------------------------
           VALIDATION FRONTEND BACKEND
           (same rules as backoffice)
        ---------------------------- */

        if (empty($content)) {
            $_SESSION['errors'] = ["Le contenu ne peut pas être vide."];
            header("Location: index.php?action=community");
            exit();
        }

        if (strlen($content) < 3) {
            $_SESSION['errors'] = ["Le contenu doit contenir au moins 3 caractères."];
            header("Location: index.php?action=community");
            exit();
        }

        if (strlen($content) > 500) {
            $_SESSION['errors'] = ["Le contenu ne doit pas dépasser 500 caractères."];
            header("Location: index.php?action=community");
            exit();
        }

        // IMAGE UPLOAD
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $folder = __DIR__ . '/../public/uploads/posts/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);

            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $folder . $image);
        }

        // INSERT POST
        $postModel = new Post($this->db);
        $postModel->create($user_id, htmlspecialchars($content), $image);

        header("Location: index.php?action=community");
        exit();
    }
    public function search()
{
    $criteria = [
        'keyword'     => $_GET['keyword'] ?? "",
        'author'      => $_GET['author'] ?? "",
        'date_from'   => $_GET['date_from'] ?? "",
        'date_to'     => $_GET['date_to'] ?? "",
        'min_likes'   => $_GET['min_likes'] ?? "",
        'most_shared' => $_GET['most_shared'] ?? "",
        'has_image'   => $_GET['has_image'] ?? ""
    ];

    $postModel = new Post($this->db);
    $posts = $postModel->search($criteria);

    ob_start();
    include __DIR__ . '/../views/posts/search_results.php';
    $content = ob_get_clean();

    include __DIR__ . '/../views/layout_front.php';
}

}
