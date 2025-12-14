<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

try {
    // Vérifier que la requête est POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }

    // Supprimer le code promo de la session
    unset($_SESSION['promo_code']);

    // Récupérer le nouveau résumé sans réduction
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    $summary = $gameController->getCartSummary($userId, null);

    echo json_encode([
        'success' => true,
        'message' => 'Promo code removed',
        'summary' => $summary
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>