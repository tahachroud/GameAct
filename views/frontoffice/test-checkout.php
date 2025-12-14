<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Checkout</h1>";

// Test 1 : Vérifier le controller
try {
    require_once __DIR__ . '/../../controllers/GameController.php';
    echo "✅ Controller chargé<br>";
} catch (Exception $e) {
    die("❌ Erreur Controller : " . $e->getMessage());
}

// Test 2 : Créer une instance
try {
    $gameController = new GameController();
    echo "✅ GameController instancié<br>";
} catch (Exception $e) {
    die("❌ Erreur instanciation : " . $e->getMessage());
}

// Test 3 : Vérifier les méthodes
if (method_exists($gameController, 'getCartByUserId')) {
    echo "✅ Méthode getCartByUserId existe<br>";
} else {
    echo "❌ Méthode getCartByUserId manquante<br>";
}

if (method_exists($gameController, 'getCartSummary')) {
    echo "✅ Méthode getCartSummary existe<br>";
} else {
    echo "❌ Méthode getCartSummary manquante<br>";
}

// Test 4 : Tester l'exécution
try {
    $cartItems = $gameController->getCartByUserId(1);
    echo "✅ getCartByUserId fonctionne : " . count($cartItems) . " articles<br>";
} catch (Exception $e) {
    echo "❌ Erreur getCartByUserId : " . $e->getMessage() . "<br>";
}

try {
    $summary = $gameController->getCartSummary(1);
    echo "✅ getCartSummary fonctionne : Total = $" . $summary['total'] . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur getCartSummary : " . $e->getMessage() . "<br>";
}

echo "<hr><a href='checkout.php'>Tester checkout.php</a>";
?>