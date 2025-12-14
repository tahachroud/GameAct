<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur vient d'une commande valide
if (!isset($_SESSION['order_just_created']) || $_SESSION['order_just_created'] !== true) {
    header('Location: shop.php');
    exit;
}

try {
    require_once __DIR__ . '/../../controller/GameController.php';
    $gameController = new GameController();
    
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    
    // Récupérer les détails de la commande
    $orderDetails = $gameController->getOrderDetails($userId);
    
    // Nettoyer le flag de session
    unset($_SESSION['order_just_created']);
    
} catch (Exception $e) {
    die('<div style="background:#16213e; color:#fff; padding:40px; font-family:Arial; border-radius:10px; margin:50px auto; max-width:800px;">
        <h2 style="color:#ec6090;">❌ Erreur</h2>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
        <p><a href="shop.php" style="color:#ec6090;">← Retour au Shop</a></p>
    </div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Order Confirmation</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../../public/assets/css/moving-bg.css">
  <style>
    body { background: #0f0f1e; font-family: 'Poppins', sans-serif; min-height: 100vh; }
    .confirmation-container { 
      background: linear-gradient(135deg, #16213e 0%, #1a1a2e 100%);
      border-radius: 15px; 
      padding: 40px; 
      margin: 50px auto; 
      max-width: 900px;
      color: #fff;
      box-shadow: 0 10px 40px rgba(236, 96, 144, 0.2);
    }
    .success-icon { 
      font-size: 5rem; 
      color: #28a745; 
      text-align: center;
      margin-bottom: 20px;
      animation: scaleIn 0.5s ease-out;
    }
    @keyframes scaleIn {
      from { transform: scale(0); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .confirmation-title { 
      color: #28a745; 
      font-size: 2.5rem; 
      font-weight: 700; 
      text-align: center;
      margin-bottom: 10px;
    }
    .order-date { 
      text-align: center; 
      color: #aaa; 
      font-size: 1rem;
      margin-bottom: 30px;
    }
    .game-card {
      background: #1a1a2e;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      border-left: 4px solid #ec6090;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .game-card:hover {
      transform: translateX(5px);
      box-shadow: 0 5px 20px rgba(236, 96, 144, 0.3);
    }
    .game-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
    }
    .game-details {
      flex: 1;
    }
    .game-title {
      color: #ec6090;
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .game-info {
      color: #aaa;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
    .download-btn {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: #fff;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
    }
    .download-btn:hover {
      background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
      color: #fff;
    }
    .download-btn i {
      margin-right: 8px;
    }
    .download-btn:disabled {
      background: #666;
      cursor: not-allowed;
      opacity: 0.6;
    }
    .order-summary {
      background: #16213e;
      border-radius: 12px;
      padding: 25px;
      margin-top: 30px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .summary-row:last-child {
      border-bottom: none;
      font-size: 1.2rem;
      font-weight: 700;
      color: #ec6090;
      padding-top: 15px;
      margin-top: 10px;
    }
    .back-btn {
      background: #ec6090;
      color: #fff;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-block;
      margin-top: 20px;
      transition: all 0.3s ease;
    }
    .back-btn:hover {
      background: #d14e7a;
      color: #fff;
      transform: translateY(-2px);
    }
    .email-notice {
      background: rgba(236, 96, 144, 0.1);
      border: 1px solid #ec6090;
      border-radius: 8px;
      padding: 15px;
      margin: 20px 0;
      text-align: center;
      color: #ec6090;
    }
    .no-games {
      text-align: center;
      padding: 40px;
      color: #aaa;
    }
    .pending-notice {
      background: rgba(255, 193, 7, 0.1);
      border: 1px solid #ffc107;
      border-radius: 8px;
      padding: 10px 15px;
      color: #ffc107;
      font-size: 0.9rem;
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="moving-bg"></div>

  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.php" class="logo">
              <img src="../assets/images/logo.png" alt="GameAct">
            </a>
            <ul class="nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="all-games.php">Browse</a></li>
            </ul>
            <a class='menu-trigger'><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="confirmation-container">
      <div class="success-icon">
        <i class="fa fa-check-circle"></i>
      </div>
      
      <h1 class="confirmation-title">Payment Successful!</h1>
      <div class="order-date">
        <i class="fa fa-calendar"></i> 
        <?= date('F d, Y - H:i', strtotime($orderDetails['created_at'])) ?>
      </div>
      
      <div class="email-notice">
        <i class="fa fa-envelope"></i> A confirmation email will be sent to your email address shortly.
      </div>

      <h3 style="color: #ec6090; margin-top: 30px; margin-bottom: 20px;">
        <i class="fa fa-download"></i> Your Games
      </h3>

      <?php if (!empty($orderDetails['games'])): ?>
        <?php foreach ($orderDetails['games'] as $game): ?>
          <div class="game-card">
            <?php if (!empty($game['image_path'])): ?>
              <img src="../<?= htmlspecialchars($game['image_path']) ?>" 
                   alt="<?= htmlspecialchars($game['title']) ?>" 
                   class="game-image">
            <?php else: ?>
              <div class="game-image" style="background: #2a2a3e; display: flex; align-items: center; justify-content: center;">
                <i class="fa fa-gamepad" style="font-size: 2rem; color: #ec6090;"></i>
              </div>
            <?php endif; ?>
            
            <div class="game-details">
              <div class="game-title">
                <i class="fa fa-gamepad"></i> <?= htmlspecialchars($game['title']) ?>
              </div>
              <div class="game-info">
                Quantity: <?= (int)$game['quantity'] ?> | 
                Price: $<?= number_format($game['price'], 2) ?> | 
                Subtotal: $<?= number_format($game['subtotal'], 2) ?>
              </div>
              
              <?php if (!empty($game['download_link'])): ?>
                <a href="<?= htmlspecialchars($game['download_link']) ?>" 
                   class="download-btn" 
                   target="_blank"
                   rel="noopener noreferrer">
                  <i class="fa fa-download"></i> Download Now
                </a>
              <?php else: ?>
                <div class="pending-notice">
                  <i class="fa fa-clock-o"></i> Download link will be available soon
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-games">
          <i class="fa fa-info-circle" style="font-size: 3rem; color: #aaa;"></i>
          <p>No downloadable items in this order.</p>
        </div>
      <?php endif; ?>

      <div class="order-summary">
        <h4 style="color: #ec6090; margin-bottom: 20px;">
          <i class="fa fa-receipt"></i> Order Summary
        </h4>
        <div class="summary-row">
          <span>Subtotal</span>
          <span>$<?= number_format($orderDetails['subtotal'], 2) ?></span>
        </div>
        <?php if ($orderDetails['discount'] > 0): ?>
          <div class="summary-row" style="color: #28a745;">
            <span>Discount</span>
            <span>-$<?= number_format($orderDetails['discount'], 2) ?></span>
          </div>
        <?php endif; ?>
        <div class="summary-row">
          <span><strong>Total Paid</strong></span>
          <span><strong>$<?= number_format($orderDetails['total'], 2) ?></strong></span>
        </div>
      </div>

      <div style="text-align: center; margin-top: 30px;">
        <a href="shop.php" class="back-btn">
          <i class="fa fa-shopping-cart"></i> Continue Shopping
        </a>
      </div>
    </div>
  </div>

  <footer style="margin-top: 50px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p style="text-align: center; color: #aaa;">
            Copyright © 2025 <a href="#" style="color: #ec6090;">GameAct</a>. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script>
    // Empêcher le retour en arrière après confirmation
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
</html>