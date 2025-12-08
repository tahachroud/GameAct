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
    if (!isset($_POST['promo_code']) || empty($_POST['promo_code'])) {
        echo json_encode(['success' => false, 'message' => 'Promo code is required']);
        exit();
    }

    $promoCode = strtoupper(trim($_POST['promo_code']));
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    // Valider le code promo
    $promoData = $gameController->validatePromoCode($promoCode);

    if ($promoData) {
        // Stocker le code promo dans la session
        $_SESSION['promo_code'] = $promoData['code'];

        // Récupérer le nouveau résumé avec la réduction
        $summary = $gameController->getCartSummary($userId, $promoData['code']);

        echo json_encode([
            'success' => true,
            'message' => 'Promo code applied: ' . $promoData['description'],
            'promo_data' => $promoData,
            'summary' => $summary
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid promo code. Try: WELCOME10, SUMMER20, SAVE5, or GAMEACT50'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>