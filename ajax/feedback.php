<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

session_start();
ini_set('display_errors', '0');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/Feedback.php';
require_once __DIR__ . '/../controller/FeedbackController.php';

// utilisateur simulé si pas de login (DEV)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request');
    }

    if (!isset($_POST['id'], $_POST['type'])) {
        throw new Exception('Missing parameters');
    }

    $feedbackId = (int) $_POST['id'];
    $type = $_POST['type']; // like | dislike

    if (!in_array($type, ['like', 'dislike'], true)) {
        throw new Exception('Invalid vote type');
    }

    $controller = new FeedbackController($pdo);
    $data = $controller->handleVote(
        $feedbackId,
        $_SESSION['user_id'],
        $type
    );

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
