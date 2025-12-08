<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

$pdo = config::getConnexion();

// Check table structure
echo "=== ORDERS TABLE STRUCTURE ===\n";
$result = $pdo->query("DESCRIBE orders");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['Field'] . " - " . $col['Type'] . " - " . $col['Null'] . " - " . $col['Key'] . "\n";
}

echo "\n=== TOTAL ORDERS IN TABLE ===\n";
$result = $pdo->query("SELECT COUNT(*) as count FROM orders");
$count = $result->fetch(PDO::FETCH_ASSOC);
echo "Total orders: " . $count['count'] . "\n";

echo "\n=== LAST 5 ORDERS ===\n";
$result = $pdo->query("SELECT id, user_id, game_id, game_title, quantity, price, created_at FROM orders ORDER BY id DESC LIMIT 5");
$orders = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($orders as $order) {
    echo "ID: " . $order['id'] . " | User: " . $order['user_id'] . " | Game: " . $order['game_title'] . " | Price: " . $order['price'] . "\n";
}

// Test INSERT
echo "\n=== TEST INSERT ===\n";
try {
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, game_id, game_title, quantity, price, created_at) VALUES (:user_id, :game_id, :game_title, :quantity, :price, NOW())');
    $result = $stmt->execute([
        ':user_id' => 999,
        ':game_id' => 999,
        ':game_title' => 'TEST GAME',
        ':quantity' => 1,
        ':price' => 49.99
    ]);
    echo "Test insert result: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    echo "Last insert ID: " . $pdo->lastInsertId() . "\n";
} catch (Exception $e) {
    echo "Test insert error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFY TEST RECORD ===\n";
$result = $pdo->query("SELECT * FROM orders WHERE game_title = 'TEST GAME'");
$testRecord = $result->fetchAll(PDO::FETCH_ASSOC);
echo "Test records found: " . count($testRecord) . "\n";
if (count($testRecord) > 0) {
    foreach ($testRecord as $rec) {
        echo "  ID: " . $rec['id'] . " - Game: " . $rec['game_title'] . "\n";
    }
}
?>
