<?php

require "./config/database.php";
require "./controller/TutorialController.php";
require "./controller/FeedbackController.php";
require "./controller/AdminController.php";

$db = (new Database())->connect();
$controller = new TutorialController($db);

$action = $_GET["action"] ?? "index";

switch ($action) {

    case "index":
        $controller->index();
        exit;

    case "show":
        $controller->show($_GET["id"]);
        exit;

    case "adminList":
        $controller->adminList();
        exit;

    case "add":
        $controller->create();
        exit;

    case "edit":
        $controller->edit($_GET["id"]);
        exit;

    case "delete":
        $controller->delete($_GET["id"]);
        exit;

    // MODIFICATION ICI : Remplacement de l'action 'categories' par 'rank'
    case "rank":
        // IMPORTANT : La méthode getRankedTutorials() doit exister et trier par likes.
        case "rank":
        // Cette ligne va maintenant fonctionner :
        $tutorials = $controller->getRankedTutorials(); 
        require "./view/front/rank.php"; // Charge la nouvelle vue
        exit;
        require "./view/front/rank.php"; // Charge la nouvelle vue
        exit;
    
    case "about":
        require "./view/front/about.php";
        exit;
}

// ... Reste du code du FeedbackController ...

$fb = new FeedbackController();

if ($action == "feedbackStore") {
    $fb->store();
    exit;
}

if ($action == "adminFeedback") {
    $fb->adminList();
    exit;
}

if ($action == "deleteFeedback") {
    $fb->delete($_GET["id"]);
    exit;
}

if ($action == "likeFeedback") {
    $fb->like($_GET["id"]);
    exit;
}

if ($action == "dislikeFeedback") {
    $fb->dislike($_GET["id"]);
    exit;
}


if ($action == "dashboard") {
    (new AdminController())->dashboard();
    exit;
}

?>