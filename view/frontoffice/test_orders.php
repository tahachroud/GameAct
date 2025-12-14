<?php
// Test script to verify orders table and insert test data
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config.php';

$pdo = config::getConnexion();

echo "<h2>Database Test</h2>";

// Check if orders table exists
try {
    $result = $pdo->query("DESCRIBE orders");
    $columns = $result->fetchAll();
    echo "<h3>Orders table structure:</h3>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Error describing orders table: " . $e->getMessage() . "</p>";
}

// Check current cart items
echo "<h3>Current cart items (user_id = 1):</h3>";
try {
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = 1");
    $stmt->execute();
    $cartItems = $stmt->fetchAll();
    echo "<pre>";
    print_r($cartItems);
    echo "</pre>";
    echo "<p>Total cart items: " . count($cartItems) . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Error fetching cart: " . $e->getMessage() . "</p>";
}

// Check current orders
echo "<h3>Current orders:</h3>";
try {
    $stmt = $pdo->prepare("SELECT * FROM orders ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $orders = $stmt->fetchAll();
    echo "<pre>";
    print_r($orders);
    echo "</pre>";
    echo "<p>Total orders: " . count($orders) . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Error fetching orders: " . $e->getMessage() . "</p>";
}

// Test insert order
echo "<h3>Test Insert Order:</h3>";
try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, cart_id, created_at) VALUES (:user_id, :cart_id, NOW())");
    $result = $stmt->execute([
        ':user_id' => 1,
        ':cart_id' => 1
    ]);
    
    if ($result) {
        echo "<p style='color:green;'>Test order inserted successfully! ID: " . $pdo->lastInsertId() . "</p>";
        
        // Verify it was inserted
        $verify = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $verify->execute([$pdo->lastInsertId()]);
        $testOrder = $verify->fetch();
        echo "<pre>";
        print_r($testOrder);
        echo "</pre>";
    } else {
        echo "<p style='color:red;'>Failed to insert test order</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error inserting test order: " . $e->getMessage() . "</p>";
}

?>
    