<?php
// Démarrer la session
session_start();

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Vérifier si l'ID est passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit();
}

$gameId = (int)$_GET['id'];

// Incrémenter les téléchargements
$success = $gameController->incrementDownloads($gameId);

// Retourner le résultat
header('Content-Type: application/json');
echo json_encode(['success' => $success]);
?>