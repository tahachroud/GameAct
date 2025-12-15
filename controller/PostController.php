<?php 
require_once __DIR__ . '/../model/Post.php';
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../helpers/HashtagHelper.php';

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
        include __DIR__ . '/../view/posts/list.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
    }

    /* CREATE FORM (ADMIN) */
    public function createForm()
    {
        $userModel = new UserModel($this->db);
        $users = $userModel->getAll();

        ob_start();
        include __DIR__ . '/../view/posts/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../view/layout.php';
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
            header("Location: index-community.php?action=posts_create");
            exit();
        }

        // IMAGE
        $imagesArray = [];

if (!empty($_FILES['images']['name'][0])) {

    $folder = __DIR__ . '/../public/uploads/posts/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    foreach ($_FILES['images']['name'] as $i => $name) {
        $newName = time() . "_" . basename($name);
        move_uploaded_file($_FILES['images']['tmp_name'][$i], $folder . $newName);
        $imagesArray[] = $newName;
    }
}

// EXTRACT & STORE HASHTAGS
$hashtags = HashtagHelper::extract($content);
HashtagHelper::store($hashtags);
$postModel = new Post($this->db);

// SAVE POST (hashtags remain in content only)
$postModel->create(
    $user_id,
    htmlspecialchars($content),
    json_encode($imagesArray)
);


        header("Location: index-community.php?action=posts");
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
            header("Location: index-community.php?action=posts_edit&id=" . $id);
            exit();
        }

        // IMAGE
        $postModel = new Post($this->db);
$old = $postModel->find($id);

// OLD IMAGES
$existingImages = json_decode($old['images'], true) ?? [];

// NEW IMAGES
$newImages = [];

if (!empty($_FILES['images']['name'][0])) {

    $folder = __DIR__ . '/../public/uploads/posts/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    foreach ($_FILES['images']['name'] as $i => $name) {
        $newName = time() . "_" . basename($name);
        move_uploaded_file($_FILES['images']['tmp_name'][$i], $folder . $newName);
        $newImages[] = $newName;
    }
}

// FINAL IMAGE LIST
$finalImages = array_merge($existingImages, $newImages);

// UPDATE
$postModel->update(
    $id,
    $user_id,
    htmlspecialchars($content),
    json_encode($finalImages)
);


        header("Location: index-community.php?action=posts");
        exit();
    }

    /* DELETE (ADMIN) */
    public function delete()
    {
        $postModel = new Post($this->db);
        $postModel->delete($_GET['id']);

        header("Location: index-community.php?action=posts");
        exit();
    }

    /* ===============================
       FRONTEND POST CREATION (FIXED)
    ================================= */
    public function createFromFront()
    {
        // Use logged-in user when available, otherwise pick an existing user from DB
        // require a logged-in user
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        if ($user_id === null) {
            $_SESSION['errors'] = ['Please log in to create a post.'];
            header("Location: index-community.php?action=community");
            exit();
        }

        $content = trim($_POST['content']);

        /* ---------------------------
           VALIDATION FRONTEND BACKEND
           (same rules as backoffice)
        ---------------------------- */

        if (empty($content)) {
            $_SESSION['errors'] = ["Le contenu ne peut pas être vide."];
            header("Location: index-community.php?action=community");
            exit();
        }

        if (strlen($content) < 3) {
            $_SESSION['errors'] = ["Le contenu doit contenir au moins 3 caractères."];
            header("Location: index-community.php?action=community");
            exit();
        }

        if (strlen($content) > 500) {
            $_SESSION['errors'] = ["Le contenu ne doit pas dépasser 500 caractères."];
            header("Location: index-community.php?action=community");
            exit();
        }

$pdf = null;
$link = trim($_POST['link'] ?? null);

// IMAGE
$imagesArray = [];

if (!empty($_FILES['images']['name'][0])) {

    $folder = __DIR__ . '/../public/uploads/posts/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    foreach ($_FILES['images']['name'] as $index => $filename) {
        $newName = time() . "_" . basename($filename);
        move_uploaded_file($_FILES['images']['tmp_name'][$index], $folder . $newName);
        $imagesArray[] = $newName;
    }
}


// PDF
if (!empty($_FILES['pdf']['name'])) {
    $folder = __DIR__ . '/../public/uploads/posts/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $pdf = time() . "_" . basename($_FILES['pdf']['name']);
    move_uploaded_file($_FILES['pdf']['tmp_name'], $folder . $pdf);
}


        // INSERT POST
        $postModel = new Post($this->db);
// EXTRACT & STORE HASHTAGS
$hashtags = HashtagHelper::extract($content);
HashtagHelper::store($hashtags);

// INSERT POST
$postModel->create(
    $user_id,
    htmlspecialchars($content),
    json_encode($imagesArray),
    $pdf,
    $link
);

        header("Location: index-community.php?action=community");
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
    include __DIR__ . '/../view/posts/search_results.php';
    $content = ob_get_clean();

    include __DIR__ . '/../view/layout_front.php';
}
/* ===============================
   READ POST OUT LOUD (TTS)
================================= */
public function readPostAudio()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(["error" => "Missing post ID"]);
        exit;
    }

    $postModel = new Post($this->db);
    $post = $postModel->find($id);

    if (!$post) {
        echo json_encode(["error" => "Post not found"]);
        exit;
    }

    require_once __DIR__ . '/../services/TTSService.php';
    $tts = new TTSService();

    $audioBase64 = $tts->generateAudio($post['content']);

    echo json_encode([
        "audio" => $audioBase64
    ]);
    exit;
}
/* EDIT FORM (ADMIN) */
public function editForm()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo "Missing post ID";
        exit;
    }

    $postModel = new Post($this->db);
    $post = $postModel->find($id);

    if (!$post) {
        echo "Post not found";
        exit;
    }

    // Fetch users for dropdown
    $userModel = new UserModel($this->db);
    $users = $userModel->getAll();

    // Load edit view
    ob_start();
    include __DIR__ . '/../view/posts/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../view/layout.php';
}


}
