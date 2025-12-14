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

    // Vérifier les données
    if (!isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        exit();
    }

    $cartId = (int)$_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];

    // Validation
    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
        exit();
    }

    // Mettre à jour la quantité
    $success = $gameController->updateCartQuantity($cartId, $quantity);

    if ($success) {
        // Récupérer le nouveau résumé
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
        $summary = $gameController->getCartSummary($userId, $_SESSION['promo_code'] ?? null);

        echo json_encode([
            'success' => true,
            'message' => 'Quantity updated successfully',
            'summary' => $summary
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>