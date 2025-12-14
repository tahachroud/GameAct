<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Récupérer les jeux pour le shop
$games = $gameController->getAllGames();
$featuredGames = $gameController->getTopRatedGames(3);
$topGames = $gameController->getTopDownloadedGames(3);

// Récupérer le nombre d'articles dans le panier
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
$cartCount = $gameController->getCartItemCount($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>GameAct - Shop</title>

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

  <style>
    .profile-img {
      width: 32px !important;
      height: 32px !important;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 8px;
      border: 2px solid #ec6090;
    }
    .cart-badge, .favorites-badge {
      background: #ec6090;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
      font-weight: 600;
    }
    .price-badge {
      color: #ec6090;
      font-weight: bold;
      font-size: 1.1em;
    }
    .free-badge {
      background: #28a745;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.9em;
      font-weight: 500;
    }
  </style>
</head>

<body>
  <!-- Preloader -->
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

  <!-- Header -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.php" class="logo">
              <img src="../assets/images/logo.png" alt="GameAct">
            </a>
            <div class="search-input">
              <form id="search" action="all-games.php" method="GET">
                <input type="text" placeholder="Search games..." id='searchText' name="search" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <ul class="nav"> 
              <li><a href="../../index.php">Home</a></li>
              <li><a href="shop.php" class="active">Shop</a></li>
              <li><a href="all-games.php">Browse Games</a></li>
              <li><a href="../backoffice/dashboard_shop.php">Admin</a></li>
              <li>
                <a href="cart.php">
                  <i class="fa fa-shopping-cart" style="color: #ec6090;"></i> 
                  Cart
                  <span id="cartCount" class="cart-badge"><?= $cartCount ?></span>
                </a>
              </li>
              <li>
                <a href="favorites.php">
                  <i class="fa fa-heart" style="color: #ec6090;"></i> 
                  Favorites
                  <span id="favoritesCount" class="favorites-badge">0</span>
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

  <!-- Main Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          <!-- Featured Games -->
          <div class="row">
            <div class="col-lg-8">
              <div class="featured-games header-text">
                <div class="heading-section">
                  <h4><em>Featured</em> Shop Games</h4>
                </div>
                <div class="owl-features owl-carousel">
                  <?php foreach ($featuredGames as $game): ?>
                    <div class="item">
                      <a href="details.php?id=<?= $game['id'] ?>" class="text-decoration-none">
                        <div class="thumb">
                          <img src="../<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                          <div class="hover-effect">
                            <h6><?= $game['is_free'] ? 'FREE' : '$' . number_format($game['price'], 2) ?></h6>
                          </div>
                        </div>
                        <h4><?= htmlspecialchars($game['title']) ?><br><span><?= htmlspecialchars($game['category']) ?></span></h4>
                        <ul>
                          <li><i class="fa fa-star"></i> <?= $game['rating'] ?></li>
                          <li><i class="fa fa-shopping-cart"></i> <?= $game['is_free'] ? 'Download' : 'Buy Now' ?></li>
                        </ul>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <!-- Top Selling Games -->
            <div class="col-lg-4">
              <div class="top-downloaded">
                <div class="heading-section">
                  <h4><em>Top</em> Selling Games</h4>
                </div>
                <ul>
                  <?php foreach ($topGames as $game): ?>
                    <li>
                      <a href="details.php?id=<?= $game['id'] ?>" class="text-decoration-none">
                        <img src="../<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="templatemo-item">
                        <h4><?= htmlspecialchars($game['title']) ?></h4>
                        <h6><?= htmlspecialchars($game['category']) ?></h6>
                        <span><i class="fa fa-star" style="color: yellow;"></i> <?= $game['rating'] ?></span>
                        <span><i class="fa fa-download" style="color: #ec6090;"></i> <?= $game['downloads_7days'] ?></span>
                        <div class="download">
                          <i class="fa fa-shopping-cart"></i>
                        </div>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <div class="text-button">
                  <a href="all-games.php">View All Games</a>
                </div>
              </div>
            </div>
          </div>

          <!-- All Available Games -->
          <div class="live-stream">
            <div class="col-lg-12">
              <div class="heading-section">
                <h4><em>All Available</em> Games</h4>
              </div>
            </div>
            <div class="row">
              <?php foreach (array_slice($games, 0, 8) as $game): ?>
                <div class="col-lg-3 col-sm-6 mb-4">
                  <div class="item">
                    <a href="details.php?id=<?= $game['id'] ?>" class="text-decoration-none">
                      <div class="thumb">
                        <img src="../<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
                        <div class="hover-effect">
                          <div class="content">
                            <ul>
                              <li><i class="fa fa-star"></i> <?= $game['rating'] ?></li>
                              <li><i class="fa fa-shopping-cart"></i> <?= $game['is_free'] ? 'Download' : 'Buy' ?></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="down-content text-center mt-3">
                        <h4><?= htmlspecialchars($game['title']) ?></h4>
                        <p class="text-muted small"><?= htmlspecialchars($game['category']) ?></p>
                        <?php if ($game['is_free']): ?>
                          <span class="free-badge">FREE</span>
                        <?php else: ?>
                          <span class="price-badge">$<?= number_format($game['price'], 2) ?></span>
                        <?php endif; ?>
                      </div> 
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>

              <div class="col-lg-12">
                <div class="main-button">
                  <a href="all-games.php">Load More Games</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
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

  <!-- Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/isotope.min.js"></script>
  <script src="../assets/js/owl-carousel.js"></script>
  <script src="../assets/js/custom.js"></script>

  <script>
    // Charger le compteur de favoris depuis localStorage
    function loadFavoritesCount() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      $('#favoritesCount').text(favorites.length);
    }

    // Mettre à jour le compteur du panier
    function updateCartCount() {
      $.ajax({
        url: 'get-cart-count.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            $('#cartCount').text(response.count);
          }
        }
      });
    }

    $(document).ready(function() {
      loadFavoritesCount();
      updateCartCount();
    });
  </script>

</body>
</html>