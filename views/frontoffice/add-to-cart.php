<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Inclure le controller
require_once __DIR__ . '/../../controllers/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

try {
    // Vérifier que la requête est POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }

    // Vérifier les données
    if (!isset($_POST['game_id']) || empty($_POST['game_id'])) {
        echo json_encode(['success' => false, 'message' => 'Game ID is required']);
        exit();
    }

    $gameId = (int)$_POST['game_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    // Vérifier que le jeu existe
    $game = $gameController->getGameById($gameId);
    if (!$game) {
        echo json_encode(['success' => false, 'message' => 'Game not found']);
        exit();
    }

    // Vérifier que le jeu n'est pas gratuit
    if ($game['is_free']) {
        echo json_encode([
            'success' => false,
            'message' => 'This game is free, you can download it directly!'
        ]);
        exit();
    }

    // Ajouter au panier
    $success = $gameController->addToCart($userId, $gameId, $quantity);

    if ($success) {
        // Récupérer le nouveau nombre d'articles
        $itemCount = $gameController->getCartItemCount($userId);

        echo json_encode([
            'success' => true,
            'message' => '"' . $game['title'] . '" added to cart!',
            'cart_count' => $itemCount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>