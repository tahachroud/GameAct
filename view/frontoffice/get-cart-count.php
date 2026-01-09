<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../controller/GameController.php';
$gameController = new GameController();
try {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    $count = $gameController->getCartItemCount($userId);
    echo json_encode(['success' => true, 'count' => $count]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'count' => 0]);
}
?>