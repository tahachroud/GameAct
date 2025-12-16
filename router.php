<?php

// ==========================================
// UPDATED: Changed from database.php to db.php
// Uses team's standard config class
// ==========================================
require "./config/db.php";  // ✅ CHANGED: was database.php
require "./controller/TutorialController.php";
require "./controller/FeedbackController.php";
require "./controller/AdminController.php";

// ==========================================
// UPDATED: Changed from Database class to config class
// ==========================================
$db = config::getConnexion();  // ✅ CHANGED: was (new Database())->connect()
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
        $tutorials = $controller->getRankedTutorials();
        require "./view/front/rank.php"; // Charge la nouvelle vue
        exit;
   
    case "about":
        require "./view/front/about.php";
        exit;
       
    // ==========================================
    // NEW: AJAX Search Endpoint (if using server-side search)
    // ==========================================
    case "searchTutorials":
        $controller->searchTutorials();
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