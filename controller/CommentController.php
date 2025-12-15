 <?php 
require_once __DIR__ . '/../model/Comment.php';
require_once __DIR__ . '/../model/Post.php';
require_once __DIR__ . '/../model/UserModel.php';

class CommentController {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // LIST
    public function index()
    {
        $commentModel = new Comment($this->db);
        $comments = $commentModel->getAll();

        ob_start();
        include __DIR__ . '/../view/comments/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    // CREATE FORM
    public function createForm($errors = [])
    {
        $postModel = new Post($this->db);
        $posts = $postModel->getAll();

        $userModel = new UserModel($this->db);
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../view/comments/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    // STORE
    public function store()
    {
        $errors = [];

        // Nettoyage
        $post_id   = trim($_POST['post_id']);
        $user_id   = trim($_POST['user_id']);
        $content   = trim($_POST['content']);

        // VALIDATION BACKEND
        if (empty($content)) {
            $errors[] = "Le commentaire ne peut pas être vide.";
        }

        if (strlen($content) < 3) {
            $errors[] = "Le commentaire doit contenir au moins 3 caractères.";
        }

        if (strlen($content) > 500) {
            $errors[] = "Le commentaire ne doit pas dépasser 500 caractères.";
        }

        // SI ERREUR → RETOUR VUE
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index-community.php?action=comments_create");
            exit();
        }

        // INSERTION
        $commentModel = new Comment($this->db);
        // Prevent accidental duplicate submission (same content by same user on same post within short time)
        if (!$commentModel->existsDuplicate($post_id, $user_id, htmlspecialchars($content), 5)) {
            $commentModel->create([
                "post_id" => $post_id,
                "user_id" => $user_id,
                "content" => htmlspecialchars($content)
            ]);
        }

        header("Location: index-community.php?action=comments");
        exit();
    }

    // EDIT FORM
    public function editForm()
    {
        $commentModel = new Comment($this->db);
        $comment = $commentModel->find($_GET['id']);

        $postModel = new Post($this->db);
        $posts = $postModel->getAll();

        $userModel = new UserModel($this->db);
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../view/comments/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    // UPDATE
    public function update()
    {
        $errors = [];

        $id       = trim($_POST['id']);
        $post_id  = trim($_POST['post_id']);
        $user_id  = trim($_POST['user_id']);
        $content  = trim($_POST['content']);

        // VALIDATION BACKEND
        if (empty($content)) {
            $errors[] = "Le commentaire ne peut pas être vide.";
        }

        if (strlen($content) < 3) {
            $errors[] = "Le commentaire doit contenir au moins 3 caractères.";
        }

        if (strlen($content) > 500) {
            $errors[] = "Le commentaire ne doit pas dépasser 500 caractères.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index-community.php?action=comments_edit&id=" . $id);
            exit();
        }

        // UPDATE
        $commentModel = new Comment($this->db);
        $commentModel->update($id, $post_id, $user_id, htmlspecialchars($content));

        header("Location: index-community.php?action=comments");
        exit();
    }

    // DELETE
    public function delete()
    {
        $commentModel = new Comment($this->db);
        $commentModel->delete($_GET['id']);

        header("Location: index-community.php?action=comments");
        exit();
    }
    // =============================================
// FRONT OFFICE COMMENT CREATION
// =============================================
    public function createFromFront()
    {
    // require logged-in user
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    if ($user_id === null) {
        $_SESSION['errors'] = ["Please log in to comment."];
        header("Location: index-community.php?action=community");
        exit();
    }
    $post_id = trim($_POST['post_id']);
    $content = trim($_POST['content']);

    if ($post_id == "" || !is_numeric($post_id)) {
        $_SESSION['errors'] = ["Post ID is missing."];
        header("Location: index-community.php?action=community");
        exit();
    }

    if (strlen($content) < 3) {
        $_SESSION['errors'] = ["Le commentaire doit contenir au moins 3 caractères."];
        header("Location: index-community.php?action=community");
        exit();
    }

    $commentModel = new Comment($this->db);
    if ($commentModel->existsDuplicate($post_id, $user_id, htmlspecialchars($content), 5)) {
        $_SESSION['errors'] = ["Duplicate comment detected."];
        header("Location: index-community.php?action=community");
        exit();
    }

    $commentModel->create([
        "post_id" => $post_id,
        "user_id" => $user_id,
        "content" => htmlspecialchars($content)
    ]);

    header("Location: index-community.php?action=community");
    exit();
}

public function createFromAjax()
{
    // require logged-in user for ajax
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    if ($user_id === null) {
        echo json_encode(["error" => "Please log in to comment"]);
        return;
    }
    $post_id = $_POST['post_id'];
    $content = trim($_POST['content']);

    if (strlen($content) < 3) {
        echo json_encode(["error" => "Comment too short"]);
        return;
    }

    $commentModel = new Comment($this->db);
    if ($commentModel->existsDuplicate($post_id, $user_id, htmlspecialchars($content), 5)) {
        echo json_encode(["error" => "Duplicate comment detected"]);
        return;
    }

    $commentModel->create([
        "post_id" => $post_id,
        "user_id" => $user_id,
        "content" => htmlspecialchars($content)
    ]);

    // RETURN NEW COUNT
    $newCount = $commentModel->countByPost($post_id);

    echo json_encode([
        "success" => true,
        "count" => $newCount
    ]);
}


}
