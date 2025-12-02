<?php
require_once "../config/database.php";
require_once "../models/Feedback.php";

header("Content-Type: application/json");

if (!isset($_POST["id"]) || !isset($_POST["type"])) {
    echo json_encode(["status" => "error", "message" => "Paramètres manquants"]);
    exit;
}

$id = intval($_POST["id"]);
$type = $_POST["type"];

$db = (new Database())->connect();
$fb = new Feedback($db);

if ($type === "like") {
    $fb->addLike($id);
} elseif ($type === "dislike") {
    $fb->addDislike($id);
} else {
    echo json_encode(["status" => "error", "message" => "Type invalide"]);
    exit;
}

echo json_encode(["status" => "success"]);
exit;
