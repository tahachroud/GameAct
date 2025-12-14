<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once __DIR__ . '/../../controller/GameController.php';
    $gameController = new GameController();
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    $cartItems = $gameController->getCartByUserId($userId);
    $promoCode = isset($_SESSION['promo_code']) ? $_SESSION['promo_code'] : null;
    $summary = $gameController->getCartSummary($userId, $promoCode);

} catch (Exception $e) {
    die('<div style="background:#16213e; color:#fff; padding:40px; font-family:Arial; border-radius:10px; margin:50px auto; max-width:800px;">
        <h2 style="color:#ec6090;">❌ Erreur de chargement du panier</h2>
        <p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>
        <p><strong>Fichier:</strong> ' . htmlspecialchars($e->getFile()) . '</p>
        <p><strong>Ligne:</strong> ' . $e->getLine() . '</p>
        <hr>
        <p><a href="shop.php" style="color:#ec6090;">← Retour au Shop</a></p>
    </div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <title>GameAct - My Cart</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <style>
    body {
      background: #0f0f1e;
      font-family: 'Poppins', sans-serif;
    }
    .cart-container {
      background: #16213e;
      border-radius: 15px;
      padding: 30px;
      margin-top: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .cart-title {
      color: #ec6090;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 30px;
    }
    .cart-item {
      background: #1a1a2e;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
      transition: 0.3s;
    }
    .cart-item:hover {
      box-shadow: 0 5px 15px rgba(236, 96, 144, 0.2);
    }
    .cart-item-img {
      width: 120px;
      height: 120px;
      border-radius: 10px;
      object-fit: cover;
      margin-right: 20px;
    }
    .cart-item-title {
      color: #fff;
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 8px;
    }
    .cart-item-category {
      color: #aaa;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
    .cart-item-price {
      color: #ec6090;
      font-size: 1.3rem;
      font-weight: 700;
    }
    .quantity-control {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .quantity-btn {
      background: #ec6090;
      color: #fff;
      border: none;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      font-weight: 700;
      cursor: pointer;
      transition: 0.3s;
    }
    .quantity-btn:hover {
      background: #d14e7a;
      transform: scale(1.1);
    }
    .quantity-input {
      width: 60px;
      text-align: center;
      background: #16213e;
      border: 2px solid #2a2a3e;
      color: #fff;
      border-radius: 8px;
      padding: 5px;
      font-weight: 600;
    }
    .btn-remove {
      background: transparent;
      color: #ff4444;
      border: 2px solid #ff4444;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 600;
      transition: 0.3s;
    }
    .btn-remove:hover {
      background: #ff4444;
      color: #fff;
    }
    .summary-box {
      background: #1a1a2e;
      border-radius: 12px;
      padding: 25px;
      position: sticky;
      top: 100px;
    }
    .summary-title {
      color: #ec6090;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      border-bottom: 2px solid #2a2a3e;
      padding-bottom: 10px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      color: #aaa;
      font-size: 1rem;
    }
    .summary-row strong {
      color: #fff;
    }
    .summary-total {
      display: flex;
      justify-content: space-between;
      padding: 15px 0;
      margin-top: 15px;
      border-top: 2px solid #ec6090;
      font-size: 1.5rem;
      font-weight: 700;
      color: #ec6090;
    }
    .promo-input {
      background: #16213e;
      border: 2px solid #2a2a3e;
      color: #fff;
      border-radius: 8px;
      padding: 10px;
      width: 100%;
      margin-bottom: 10px;
    }
    .promo-input:focus {
      border-color: #ec6090;
      outline: none;
    }
    .btn-promo {
      background: #ec6090;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      width: 100%;
      transition: 0.3s;
    }
    .btn-promo:hover {
      background: #d14e7a;
    }
    .btn-checkout {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 15px 30px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 1.2rem;
      width: 100%;
      transition: 0.3s;
      margin-top: 20px;
    }
    .btn-checkout:hover {
      background: #218838;
      transform: translateY(-2px);
    }
    .empty-cart {
      text-align: center;
      padding: 80px 20px;
      background: #16213e;
      border-radius: 15px;
      margin-top: 30px;
    }
    .empty-cart i {
      font-size: 5rem;
      color: #ec6090;
      margin-bottom: 20px;
    }
    .promo-badge {
      background: #28a745;
      color: #fff;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 15px;
    }
    .promo-remove {
      background: none;
      border: none;
      color: #fff;
      cursor: pointer;
      font-weight: 700;
    }
    .alert-custom {
      background: #1a1a2e;
      border: 2px solid #ec6090;
      color: #fff;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
    }
    .profile-img {
      width: 32px !important;
      height: 32px !important;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 8px;
      border: 2px solid #ec6090;
    }
    .cart-badge {
      background: #ec6090;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div id="js-preloader" class="js-preloader" style="display: none;">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.php" class="logo">
              <img src="../assets/images/logo.png" alt="GameAct">
            </a>
            <div class="search-input">
              <form id="search" action="#">
                <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <ul class="nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="all-games.php">Browse</a></li>
              <li>
                <a href="cart.php" class="active">
                  <i class="fa fa-shopping-cart" style="color: #ec6090;"></i> 
                  My Cart
                  <span id="cartCount" class="cart-badge"><?= $summary['item_count'] ?></span>
                </a>
              </li>
              <li>
                <a href="favorites.php">
                  <i class="fa fa-heart" style="color: #ec6090;"></i> 
                  Favorites
                </a>
              </li>
            </ul>   
            <a class='menu-trigger'>
              <span>Menu</span>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>
  <div class="container">
    <div class="cart-container">
      <h1 class="cart-title"><i class="fa fa-shopping-cart"></i> My Cart</h1>
      <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
          <i class="fa fa-shopping-cart"></i>
          <h3 style="color: #fff; font-weight: 700;">Your cart is empty</h3>
          <p style="color: #aaa; font-size: 1.1rem; margin: 20px 0;">Add games to your cart to start shopping!</p>
          <a href="shop.php" class="btn btn-outline-light px-4">
            <i class="fa fa-shopping-bag"></i> Browse Games
          </a>
        </div>
      <?php else: ?>
        <div class="row">
          <div class="col-lg-8">
            <div id="cartItemsList">
              <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-cart-id="<?= $item['id'] ?>">
                  <div class="d-flex align-items-center">
                    <img src="../<?= htmlspecialchars($item['image_path']) ?>" 
                         alt="<?= htmlspecialchars($item['title']) ?>" 
                         class="cart-item-img">
                    <div class="flex-grow-1">
                      <h4 class="cart-item-title"><?= htmlspecialchars($item['title']) ?></h4>
                      <p class="cart-item-category">
                        <i class="fa fa-tag"></i> <?= htmlspecialchars($item['category']) ?>
                      </p>
                      <p class="cart-item-price">$<?= number_format($item['price'], 2) ?></p>                      
                      <div class="quantity-control mt-3">
                        <button class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, -1)">
                          <i class="fa fa-minus"></i>
                        </button>
                        <input type="number" 
                               class="quantity-input" 
                               value="<?= $item['quantity'] ?>" 
                               min="1" 
                               readonly>
                        <button class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, 1)">
                          <i class="fa fa-plus"></i>
                        </button>
                        <span style="color: #aaa; margin-left: 15px;">
                          Subtotal: <strong style="color: #ec6090;">$<?= number_format($item['subtotal'], 2) ?></strong>
                        </span>
                      </div>
                    </div>
                    <button class="btn-remove" onclick="removeFromCart(<?= $item['id'] ?>, '<?= htmlspecialchars($item['title']) ?>')">
                      <i class="fa fa-trash"></i> Remove
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="summary-box">
              <h3 class="summary-title">Order Summary</h3>
              <?php if ($promoCode): ?>
                <div class="promo-badge">
                  <i class="fa fa-tag"></i>
                  <?= htmlspecialchars($summary['promo_description']) ?>
                  <button class="promo-remove" onclick="removePromo()">✕</button>
                </div>
              <?php endif; ?>
              <div class="mb-3">
                <label style="color: #aaa; font-size: 0.9rem; margin-bottom: 8px;">Have a promo code?</label>
                <input type="text" 
                       class="promo-input" 
                       id="promoCodeInput" 
                       placeholder="Enter promo code"
                       <?= $promoCode ? 'disabled' : '' ?>>
                <button class="btn-promo" 
                        id="applyPromoBtn"
                        <?= $promoCode ? 'disabled' : '' ?>
                        onclick="applyPromo()">
                  <i class="fa fa-check"></i> Apply Code
                </button>
              </div>
              <div class="summary-row">
                <span>Subtotal:</span>
                <strong id="subtotalAmount">$<?= number_format($summary['subtotal'], 2) ?></strong>
              </div>
              <?php if ($summary['discount'] > 0): ?>
              <div class="summary-row" style="color: #28a745;">
                <span>Discount:</span>
                <strong id="discountAmount">-$<?= number_format($summary['discount'], 2) ?></strong>
              </div>
              <?php endif; ?>
              <div class="summary-total">
                <span>Total:</span>
                <span id="totalAmount">$<?= number_format($summary['total'], 2) ?></span>
              </div>
              <button class="btn-checkout" onclick="proceedToCheckout()">
                <i class="fa fa-credit-card"></i> Proceed to Checkout
              </button>
              <div class="text-center mt-3">
                <a href="shop.php" style="color: #aaa; text-decoration: none; font-size: 0.9rem;">
                  <i class="fa fa-arrow-left"></i> Continue Shopping
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2025 <a href="#">GameAct</a> Company. All rights reserved. 
          <br>Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a></p>
        </div>
      </div>
    </div>
  </footer>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    function updateQuantity(cartId, change) {
      const input = $(`.cart-item[data-cart-id="${cartId}"] .quantity-input`);
      let newQuantity = parseInt(input.val()) + change;      
      if (newQuantity < 1) {
        removeFromCart(cartId, '');
        return;
      }
      $.ajax({
        url: 'update-cart-quantity.php',
        method: 'POST',
        data: {
          cart_id: cartId,
          quantity: newQuantity
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            location.reload();
          } else {
            alert('Error: ' + response.message);
          }
        },
        error: function() {
          alert('Error updating quantity');
        }
      });
    }
    function removeFromCart(cartId, gameTitle) {
      if (confirm(`Remove "${gameTitle}" from your cart?`)) {
        $.ajax({
          url: 'remove-from-cart.php',
          method: 'POST',
          data: { cart_id: cartId },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              location.reload();
            } else {
              alert('Error: ' + response.message);
            }
          },
          error: function() {
            alert('Error removing item');
          }
        });
      }
    }
    function applyPromo() {
      const promoCode = $('#promoCodeInput').val().trim();     
      if (!promoCode) {
        alert('Please enter a promo code');
        return;
      }
      $.ajax({
        url: 'apply-promo.php',
        method: 'POST',
        data: { promo_code: promoCode },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            location.reload();
          } else {
            alert(response.message || 'Invalid promo code');
          }
        },
        error: function() {
          alert('Error applying promo code');
        }
      });
    }
    function removePromo() {
      $.ajax({
        url: 'remove-promo.php',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            location.reload();
          }
        }
      });
    }
    function proceedToCheckout() {
      // Navigate to the checkout page where the payment form is generated by JavaScript
      window.location.href = 'checkout.php';
    }
  </script>
</body>
</html>