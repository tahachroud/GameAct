<?php

require "./config/database.php";
require "./controllers/TutorialController.php";
require "./controllers/FeedbackController.php";
require "./controllers/AdminController.php";

$db = (new Database())->connect();
$controller = new TutorialController($db);

$action = $_GET["action"] ?? "index";

// 🔥 ROUTES PRINCIPALES
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
}

// 🔥 ROUTES FEEDBACK
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

// 🔥 ROUTE ADMIN
if ($action == "dashboard") {
    (new AdminController())->dashboard();
    exit;
}

?>
