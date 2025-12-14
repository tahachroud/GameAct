<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Récupérer tous les jeux
$games = $gameController->getAllGames();

// Filtrer par catégorie si nécessaire
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
if (!empty($selectedCategory)) {
    $games = $gameController->getGamesByCategory($selectedCategory);
}

// Recherche si nécessaire
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($searchTerm)) {
    $games = $gameController->searchGames($searchTerm);
}

// Récupérer toutes les catégories uniques pour le filtre
$allGames = $gameController->getAllGames();
$categories = array_unique(array_column($allGames, 'category'));
sort($categories);

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

  <title>GameAct - All Games</title>

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="../assets/css/flex-slider.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

  <style>
    .hover-effect .content ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .hover-effect .content ul li {
      display: inline-block;
      margin-right: 15px;
      color: #ddd;
      font-size: 14px;
    }
    .hover-effect .content ul li i {
      color: #fff;
      margin-right: 5px;
    }
    .hover-effect .content ul li:last-child {
      margin-right: 0;
    }
    .item {
      transition: transform 0.3s ease;
      text-decoration: none;
    }
    .item:hover {
      transform: translateY(-5px);
    }
    .thumb img {
      transition: transform 0.3s ease;
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }
    .thumb:hover img {
      transform: scale(1.05);
    }
    .price-display {
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
    .down-content h4 {
      color: #fff;
      font-size: 1.2rem;
      margin: 10px 0 5px;
    }
    .down-content p {
      margin: 0;
      font-size: 0.9rem;
    }
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
    .filter-section {
      background: #16213e;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 30px;
    }
    .filter-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }
    .filter-btn {
      background: #1a1a2e;
      color: #fff;
      border: 2px solid #2a2a3e;
      padding: 8px 20px;
      border-radius: 25px;
      transition: 0.15s ease;
      display: inline-flex;
      align-items: center;
      text-decoration: none;
    }
    .filter-buttons .filter-btn {
      white-space: nowrap;
    }
    .filter-btn:hover, .filter-btn.active {
      background: #ec6090;
      border-color: #ec6090;
      color: #fff;
    }
    .search-box {
      background: #1a1a2e;
      border: 2px solid #2a2a3e;
      color: #fff;
      padding: 10px 15px;
      border-radius: 25px;
      width: 100%;
      max-width: 400px;
    }
    .search-box:focus {
      border-color: #ec6090;
      outline: none;
      background: #1f1f2e;
    }
    .games-count {
      color: #ec6090;
      font-size: 1.1rem;
      font-weight: 600;
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

          <div class="heading-section mb-4">
            <h4><em>All</em> Available Games</h4>
          </div>

          <!-- Section Filtres -->
          <div class="filter-section">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h6 class="text-white mb-3">Filter by category:</h6>
                <div class="filter-buttons">
                  <a href="all-games.php" class="filter-btn <?= empty($selectedCategory) ? 'active' : '' ?>">
                    <i class="fa fa-th" style="margin-right:8px;"></i> All
                  </a>
                  <?php foreach ($categories as $category): ?>
                    <a href="all-games.php?category=<?= urlencode($category) ?>" class="filter-btn <?= $selectedCategory === $category ? 'active' : '' ?>">
                      <?= htmlspecialchars($category) ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="col-md-4 text-end">
                <p class="games-count mb-0">
                  <i class="fa fa-gamepad"></i> <?= count($games) ?> game<?= count($games) > 1 ? 's' : '' ?> found
                </p>
              </div>
            </div>
          </div>

          <!-- Affichage du terme de recherche -->
          <?php if (!empty($searchTerm)): ?>
          <div class="alert alert-info">
            <i class="fa fa-search"></i> Results for: <strong>"<?= htmlspecialchars($searchTerm) ?>"</strong>
            <a href="all-games.php" class="float-end text-danger"><i class="fa fa-times"></i> Clear</a>
          </div>
          <?php endif; ?>

          <!-- Grille des jeux -->
          <div class="row" id="gamesGrid">
            <?php if (empty($games)): ?>
              <div class="col-12 text-center py-5">
                <i class="fa fa-exclamation-triangle" style="font-size: 4rem; color: #ec6090;"></i>
                <h4 class="text-white mt-3">No games found</h4>
                <p class="text-muted">Try changing your search or filter criteria</p>
                <a href="all-games.php" class="btn btn-outline-light mt-3">View all games</a>
              </div>
            <?php else: ?>
              <?php foreach ($games as $game): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                  <a href="details.php?id=<?= $game['id'] ?>" class="text-decoration-none">
                    <div class="item">
                      <div class="thumb">
                        <img src="../<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
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
                          <span class="price-display">$<?= number_format($game['price'], 2) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </a>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <div class="col-lg-12 text-center mt-5">
            <div class="main-button">
              <a href="../../shop-home.php">Back to Shop</a>
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
  <script src="../assets/js/flex-slider.js"></script>
  <script src="../assets/js/tabs.js"></script>
  <script src="../assets/js/popup.js"></script>
  <script src="../assets/js/custom.js"></script>

  <script>
    // Charger le compteur de favoris
    function loadFavoritesCount() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      $('#favoritesCount').text(favorites.length);
    }

    $(document).ready(function() {
      loadFavoritesCount();
    });
  </script>

</body>
</html>