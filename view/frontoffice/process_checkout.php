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
    require_once __DIR__ . '/../../controller/GameController.php';
    
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
    
    // Determine if this is FormData (with file) or JSON
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $isFormData = (strpos($contentType, 'multipart/form-data') !== false);
    
    if ($isFormData) {
        // Form data with file upload (bank transfer)
        error_log("Processing FormData request (with PDF upload)");
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $method = trim($_POST['method'] ?? '');
        
    } else {
        // JSON data (card or paypal)
        error_log("Processing JSON request");
        
        $rawInput = file_get_contents('php://input');
        error_log("Raw input: " . $rawInput);
        $data = json_decode($rawInput, true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            exit;
        }
        
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $country = trim($data['country'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');
        $method = trim($data['method'] ?? '');
    }
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($country) || empty($method)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Handle Bank Transfer PDF verification
    if ($method === 'pm_bank') {
        error_log("Processing bank transfer payment");
        
        // Check if PDF was uploaded
        if (!isset($_FILES['bank_receipt']) || $_FILES['bank_receipt']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bank transfer receipt (PDF) is required']);
            exit;
        }
        
        $file = $_FILES['bank_receipt'];
        error_log("Uploaded file: " . $file['name'] . ", size: " . $file['size'] . ", type: " . $file['type']);
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if ($mimeType !== 'application/pdf' && $file['type'] !== 'application/pdf') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Only PDF files are accepted for bank receipts']);
            exit;
        }
        
        // Validate file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'PDF file size must be less than 10MB']);
            exit;
        }
        
        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../uploads/bank_receipts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            error_log("Created upload directory: " . $uploadDir);
        }
        
        // Generate unique filename
        $filename = 'receipt_' . $userId . '_' . time() . '_' . uniqid() . '.pdf';
        $destination = $uploadDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to save bank receipt PDF']);
            exit;
        }
        
        error_log("PDF saved to: " . $destination);
        
        // Verify the PDF (basic verification)
        $isValidReceipt = verifyBankReceipt($destination);
        
        if (!$isValidReceipt) {
            // Delete invalid file
            unlink($destination);
            error_log("Invalid bank receipt, file deleted");
            
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => 'Le reçu bancaire n\'est pas valide ou ne contient pas les informations nécessaires. Veuillez uploader un reçu valide et réessayer.'
            ]);
            exit;
        }
        
        error_log("Bank receipt verified successfully");
    }
    
    // Get database connection
    $pdo = config::getConnexion();
    error_log("Database connection OK");
    
    // Begin transaction
    $pdo->beginTransaction();
    error_log("Transaction started");
    
    try {
        $orderIds = [];
        
        // FIRST: Create orders for each cart item BEFORE clearing cart
        foreach ($cartItems as $item) {
            error_log("Processing cart_id: " . $item['id']);
            
            // Insert order with payment method and receipt path if bank transfer
            $stmt = $pdo->prepare('
                INSERT INTO orders (user_id, game_id, game_title, quantity, price, payment_method, receipt_path, created_at)
                VALUES (:user_id, :game_id, :game_title, :quantity, :price, :payment_method, :receipt_path, NOW())
            ');
            
            $receiptPath = null;
            if ($method === 'pm_bank' && isset($filename)) {
                $receiptPath = 'uploads/bank_receipts/' . $filename;
            }
            
            $result = $stmt->execute([
                ':user_id' => $userId,
                ':game_id' => $item['game_id'],
                ':game_title' => $item['title'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price'],
                ':payment_method' => $method,
                ':receipt_path' => $receiptPath
            ]);
            
            if (!$result) {
                throw new Exception('Failed to insert order for cart_id: ' . $item['id']);
            }
            
            $lastId = $pdo->lastInsertId();
            $orderIds[] = $lastId;
            error_log("Order created with ID: " . $lastId . " for game: " . $item['title']);
        }
        
        error_log("All orders created. Total: " . count($orderIds));
        
        // SECOND: Clear the cart after orders are created
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
        $result = $stmt->execute([':user_id' => $userId]);
        
        if (!$result) {
            throw new Exception('Failed to clear cart');
        }
        
        error_log("Cart cleared for user_id: " . $userId);
        
        // Commit transaction
        // Remplacez la section de réponse JSON finale par ceci :

// Commit transaction
$pdo->commit();
error_log("Transaction committed successfully");

// Clear session promo code if any
if (isset($_SESSION['promo_code'])) {
    unset($_SESSION['promo_code']);
}

// IMPORTANT: Stocker un flag indiquant qu'une commande vient d'être créée
$_SESSION['order_just_created'] = true;

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Order created successfully',
    'order_count' => count($cartItems),
    'order_ids' => $orderIds,
    'payment_method' => $method,
    'redirect_to_confirmation' => true  // Signal pour la redirection
]);
exit;
        
    } catch (Exception $e) {
        error_log("Exception during transaction: " . $e->getMessage());
        $pdo->rollBack();
        
        // Delete uploaded PDF if transaction failed
        if (isset($destination) && file_exists($destination)) {
            unlink($destination);
            error_log("Deleted PDF due to transaction failure");
        }
        
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

/**
 * Verify if the uploaded PDF is a valid bank receipt
 * This is a basic implementation - you can enhance it with OCR or more advanced checks
 */
function verifyBankReceipt($pdfPath) {
    error_log("Verifying bank receipt: " . $pdfPath);
    
    // Basic verification: Check if file exists and is readable
    if (!file_exists($pdfPath) || !is_readable($pdfPath)) {
        error_log("PDF file not found or not readable");
        return false;
    }
    
    // Check file size (should be at least 1KB for a valid PDF)
    $fileSize = filesize($pdfPath);
    if ($fileSize < 1024) {
        error_log("PDF file too small: " . $fileSize . " bytes");
        return false;
    }
    
    // Verify PDF header (should start with %PDF)
    $handle = fopen($pdfPath, 'rb');
    $header = fread($handle, 5);
    fclose($handle);
    
    if (substr($header, 0, 4) !== '%PDF') {
        error_log("Invalid PDF header");
        return false;
    }
    
    // ADVANCED VERIFICATION (Optional - uncomment if you want more checks)
    /*
    // You can add more sophisticated checks here:
    
    // 1. Extract text from PDF using a library like TCPDF or pdftotext
    // 2. Check for keywords like "bank", "transfer", "amount", "receipt"
    // 3. Validate account numbers format
    // 4. Check if amount matches cart total
    // 5. Use OCR API for text extraction and validation
    
    // Example with exec and pdftotext (requires pdftotext installed on server):
    $textOutput = shell_exec("pdftotext '$pdfPath' -");
    if ($textOutput) {
        $requiredKeywords = ['bank', 'transfer', 'amount', 'date'];
        $foundKeywords = 0;
        foreach ($requiredKeywords as $keyword) {
            if (stripos($textOutput, $keyword) !== false) {
                $foundKeywords++;
            }
        }
        
        // Require at least 3 out of 4 keywords
        if ($foundKeywords < 3) {
            error_log("Bank receipt missing required keywords");
            return false;
        }
    }
    */
    
    error_log("Bank receipt passed basic verification");
    return true;
}
?>