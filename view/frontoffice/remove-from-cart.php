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
    if (!isset($_POST['cart_id']) || empty($_POST['cart_id'])) {
        echo json_encode(['success' => false, 'message' => 'Cart ID is required']);
        exit();
    }

    $cartId = (int)$_POST['cart_id'];

    // Supprimer l'article
    $success = $gameController->removeFromCart($cartId);

    if ($success) {
        // Récupérer le nouveau résumé
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
        $itemCount = $gameController->getCartItemCount($userId);
        $summary = $gameController->getCartSummary($userId, $_SESSION['promo_code'] ?? null);

        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $itemCount,
            'summary' => $summary
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>