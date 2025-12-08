<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check user is logged in (use default user_id 1 if not logged in for testing)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

try {
    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../../controllers/GameController.php';
    
    $gameController = new GameController();
    $userId = (int)$_SESSION['user_id'];
    
    // Get cart items before clearing
    $cartItems = $gameController->getCartByUserId($userId);
    error_log("Cart items count: " . count($cartItems));
    
    if (empty($cartItems)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }
    
    // Validate input - READ ONLY ONCE
    $rawInput = file_get_contents('php://input');
    error_log("Raw input: " . $rawInput);
    $data = json_decode($rawInput, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit;
    }
    
    if (!isset($data['name'], $data['email'], $data['country'], $data['phone'], $data['method'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $name = trim($data['name']);
    $email = trim($data['email']);
    $country = trim($data['country']);
    $phone = trim($data['phone']);
    $address = isset($data['address']) ? trim($data['address']) : '';
    $method = trim($data['method']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($country)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid or empty required fields']);
        exit;
    }
    
    // Get database connection
    $pdo = config::getConnexion();
    error_log("Database connection OK");
    
    // Begin transaction
    $pdo->beginTransaction();
    error_log("Transaction started");
    
    try {
        $orderIds = [];
        
        // FIRST: Clear the cart BEFORE inserting orders to avoid foreign key constraint issues
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
        $result = $stmt->execute([':user_id' => $userId]);
        
        if (!$result) {
            throw new Exception('Failed to clear cart');
        }
        
        error_log("Cart cleared for user_id: " . $userId);
        
        // SECOND: Create orders for each cart item - save all the details
        foreach ($cartItems as $item) {
            error_log("Processing cart_id: " . $item['id']);
            
            // Insert order with all cart item details (but cart_id can be NULL after cart delete)
            $stmt = $pdo->prepare('
                INSERT INTO orders (user_id, game_id, game_title, quantity, price, created_at)
                VALUES (:user_id, :game_id, :game_title, :quantity, :price, NOW())
            ');
            
            $result = $stmt->execute([
                ':user_id' => $userId,
                ':game_id' => $item['game_id'],
                ':game_title' => $item['title'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ]);
            
            if (!$result) {
                throw new Exception('Failed to insert order for cart_id: ' . $item['id']);
            }
            
            $lastId = $pdo->lastInsertId();
            $orderIds[] = $lastId;
            error_log("Order created with ID: " . $lastId . " for game: " . $item['title']);
        }
        
        error_log("All orders created. Total: " . count($orderIds));
        
        // Commit transaction
        $pdo->commit();
        error_log("Transaction committed successfully");
        
        // Clear session promo code if any
        if (isset($_SESSION['promo_code'])) {
            unset($_SESSION['promo_code']);
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Order created successfully',
            'order_count' => count($cartItems),
            'order_ids' => $orderIds
        ]);
        exit;
        
    } catch (Exception $e) {
        error_log("Exception during transaction: " . $e->getMessage());
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Catch Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    exit;
}
?>

