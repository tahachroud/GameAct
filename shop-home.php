<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controller/GameController.php';

$gameController = new GameController();

$featuredGames = $gameController->getTopRatedGames(6);
$freeGames = $gameController->getFreeGames();
$stats = $gameController->getGlobalStats();

$avgRating = isset($stats['avg_rating']) ? round($stats['avg_rating'], 1) : 0;

$popularCategories = [
    ['name' => 'Action Shooter', 'icon' => 'fa-crosshairs', 'color' => '#ec6090'],
    ['name' => 'Battle Royale', 'icon' => 'fa-trophy', 'color' => '#ffc107'],
    ['name' => 'Sports', 'icon' => 'fa-futbol', 'color' => '#28a745'],
    ['name' => 'RPG', 'icon' => 'fa-dragon', 'color' => '#17a2b8'],
    ['name' => 'FPS', 'icon' => 'fa-bullseye', 'color' => '#dc3545'],
    ['name' => 'Sandbox', 'icon' => 'fa-cube', 'color' => '#6f42c1'],
    ['name' => 'Racing', 'icon' => 'fa-car', 'color' => '#b4cee7ff'],
    ['name' => 'Adventure', 'icon' => 'fa-tree', 'color' => '#04f353ff'],
    ['name' => 'Hero Shooter', 'icon' => 'fa-gun', 'color' => '#000000ff'],
    ['name' => 'MOBA', 'icon' => 'fa-horse', 'color' => '#935300ff']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>GameAct - Your Ultimate Gaming Platform</title>

  <?php
  $base = dirname($_SERVER['PHP_SELF']);
  if ($base === '/' || $base === '\\') $base = '';
  $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/gameact_shop';
  ?>

  <!-- FRONTEND CSS -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/fontawesome.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/animate.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/moving-bg.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/owl.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

  <!-- Feed CSS should load LAST to override template styles -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/feed.css">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      padding: 100px 0;
      position: relative;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('view/assets/images/banner-bg.jpg') center/cover;
      opacity: 0.1;
    }
    .hero-content {
      position: relative;
      z-index: 2;
    }
    .hero-title {
      font-size: 4rem;
      font-weight: 900;
      color: #fff;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .hero-title span {
      color: #ec6090;
    }
    .hero-subtitle {
      font-size: 1.5rem;
      color: #aaa;
      margin-bottom: 40px;
    }
    .btn-hero {
      background: #ec6090;
      color: #fff;
      padding: 15px 40px;
      font-size: 1.2rem;
      font-weight: 700;
      border-radius: 50px;
      border: none;
      transition: 0.3s;
      box-shadow: 0 5px 15px rgba(236, 96, 144, 0.4);
    }
    .btn-hero:hover {
      background: #d14e7a;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(236, 96, 144, 0.6);
      color: #fff;
    }
    .btn-hero-secondary {
      background: transparent;
      color: #fff;
      padding: 15px 40px;
      font-size: 1.2rem;
      font-weight: 700;
      border-radius: 50px;
      border: 2px solid #ec6090;
      transition: 0.3s;
      margin-left: 20px;
    }
    .btn-hero-secondary:hover {
      background: #ec6090;
      color: #fff;
    }

    /* Stats Section */
    .stats-section {
      background: #16213e;
      padding: 60px 0;
      margin: -50px 0 60px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .stat-item {
      text-align: center;
      padding: 20px;
    }
    .stat-number {
      font-size: 3rem;
      font-weight: 900;
      color: #ec6090;
      margin-bottom: 10px;
    }
    .stat-label {
      font-size: 1.1rem;
      color: #aaa;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Categories Section */
    .category-card {
      background: #16213e;
      border-radius: 15px;
      padding: 30px;
      text-align: center;
      transition: 0.3s;
      cursor: pointer;
      height: 100%;
      border: 2px solid transparent;
    }
    .category-card:hover {
      transform: translateY(-10px);
      border-color: #ec6090;
      box-shadow: 0 10px 30px rgba(236, 96, 144, 0.3);
    }
    .category-icon {
      font-size: 3rem;
      margin-bottom: 15px;
    }
    .category-name {
      font-size: 1.3rem;
      font-weight: 600;
      color: #fff;
      margin-bottom: 10px;
    }

    /* Featured Games */
    .game-card {
      background: #16213e;
      border-radius: 15px;
      overflow: hidden;
      transition: 0.3s;
      height: 100%;
      cursor: pointer;
    }
    .game-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(236, 96, 144, 0.4);
    }
    .game-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .game-card-body {
      padding: 20px;
    }
    .game-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 10px;
    }
    .game-category {
      color: #ec6090;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
    .game-price {
      font-size: 1.5rem;
      font-weight: 700;
      color: #ec6090;
    }
    .free-badge {
      background: #28a745;
      color: #fff;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 1rem;
      font-weight: 600;
    }
    .game-rating {
      color: #ffc107;
      font-size: 0.9rem;
      margin-top: 10px;
    }

    /* Section Titles */
    .section-title {
      font-size: 2.5rem;
      font-weight: 900;
      color: #fff;
      text-align: center;
      margin-bottom: 50px;
    }
    .section-title span {
      color: #ec6090;
    }

    /* CTA Section */
    .cta-section {
      background: linear-gradient(135deg, #ec6090 0%, #d14e7a 100%);
      padding: 80px 0;
      border-radius: 20px;
      text-align: center;
      margin: 60px 0;
    }
    .cta-title {
      font-size: 3rem;
      font-weight: 900;
      color: #fff;
      margin-bottom: 20px;
    }
    .cta-text {
      font-size: 1.3rem;
      color: #fff;
      margin-bottom: 40px;
      opacity: 0.9;
    }

    .profile-img {
      width: 32px !important;
      height: 32px !important;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 8px;
      border: 2px solid #ec6090;
    }
    .favorites-badge {
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
  <div class="moving-bg"></div>

  <!-- Preloader -->
  <div id="js-preloader" class="js-preloader">
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
            <a href="index.php" class="logo">
              <img src="view/assets/images/logo.png" alt="GameAct">
            </a>
            <div class="search-input">
              <form id="search" action="view/frontoffice/all-games.php" method="GET">
                <input type="text" placeholder="Search for games..." name="search" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <ul class="nav">
              <li><a href="index.php" class="active">Home</a></li>
              <li><a href="view/frontoffice/shop-home.php">Shop</a></li>
              <li><a href="view/frontoffice/all-games.php">Browse Games</a></li>
              <li><a href="dashboard-home.php">Admin</a></li>
              <li>
                <a href="view/frontoffice/favorites.php">
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

  <!-- Hero Section -->
  <div class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="hero-content">
            <h1 class="hero-title">Welcome to <span>GameAct</span></h1>
            <p class="hero-subtitle">Your ultimate gaming platform with thousands of games to explore</p>
            <a href="view/frontoffice/shop.php" class="btn-hero">
              <i class="fa fa-shopping-bag"></i> Explore Shop
            </a>
            <a href="view/frontoffice/all-games.php" class="btn-hero-secondary">
              <i class="fa fa-gamepad"></i> Browse Games
            </a>
          </div>
        </div>
        <div class="col-lg-5 text-center d-none d-lg-block">
          <img src="view/assets/images/banner-right-image.png" alt="Gaming" style="max-width: 100%; filter: drop-shadow(0 10px 30px rgba(236,96,144,0.5));">
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Section -->
  <div class="container">
    <div class="stats-section">
      <div class="row">
        <div class="col-md-3 col-6">
          <div class="stat-item">
            <div class="stat-number"><i class="fa fa-gamepad"></i> <?= $stats['total_games'] ?? 0 ?></div>
            <div class="stat-label">Games Available</div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-item">
            <div class="stat-number"><i class="fa fa-download"></i> <?= number_format($stats['total_downloads'] ?? 0) ?></div>
            <div class="stat-label">Total Downloads</div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-item">
            <div class="stat-number"><i class="fa fa-heart"></i> <?= number_format($stats['total_likes'] ?? 0) ?></div>
            <div class="stat-label">Community Likes</div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="stat-item">
            <div class="stat-number"><i class="fa fa-star"></i> <?= $avgRating ?></div>
            <div class="stat-label">Average Rating</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Popular Categories -->
  <div class="container mt-5">
    <h2 class="section-title">Popular <span>Categories</span></h2>
    <div class="row">
      <?php foreach ($popularCategories as $category): ?>
        <div class="col-lg-2 col-md-4 col-6 mb-4">
          <a href="view/frontoffice/all-games.php?category=<?= urlencode($category['name']) ?>" style="text-decoration: none;">
            <div class="category-card">
              <div class="category-icon" style="color: <?= $category['color'] ?>">
                <i class="fa <?= $category['icon'] ?>"></i>
              </div>
              <div class="category-name"><?= htmlspecialchars($category['name']) ?></div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
      <a href="view/frontoffice/all-games.php" class="btn btn-outline-light btn-lg px-5">
        <i class="fa fa-th"></i> Explore More
      </a>
    </div>
  </div>

  <!-- Featured Games -->
  <div class="container mt-5">
    <h2 class="section-title">Featured <span>Games</span></h2>
    <div class="row">
      <?php foreach ($featuredGames as $game): ?>
        <div class="col-lg-4 col-md-6 mb-4">
          <a href="view/frontoffice/details.php?id=<?= $game['id'] ?>" style="text-decoration: none;">
            <div class="game-card">
              <img src="view/<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
              <div class="game-card-body">
                <h5 class="game-title"><?= htmlspecialchars($game['title']) ?></h5>
                <p class="game-category"><i class="fa fa-tag"></i> <?= htmlspecialchars($game['category']) ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <?php if ($game['is_free']): ?>
                    <span class="free-badge">FREE</span>
                  <?php else: ?>
                    <span class="game-price">$<?= number_format($game['price'], 2) ?></span>
                  <?php endif; ?>
                  <span class="game-rating">
                    <i class="fa fa-star"></i> <?= $game['rating'] ?>/5
                  </span>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
      <a href="view/frontoffice/all-games.php" class="btn btn-outline-light btn-lg px-5">
        <i class="fa fa-th"></i> View All Games
      </a>
    </div>
  </div>

  <!-- Free Games Section -->
  <?php if (!empty($freeGames)): ?>
  <div class="container mt-5">
    <h2 class="section-title">Free to <span>Play</span></h2>
    <div class="row">
      <?php foreach (array_slice($freeGames, 0, 3) as $game): ?>
        <div class="col-lg-4 col-md-6 mb-4">
          <a href="view/frontoffice/details.php?id=<?= $game['id'] ?>" style="text-decoration: none;">
            <div class="game-card">
              <img src="view/<?= htmlspecialchars($game['image_path']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
              <div class="game-card-body">
                <h5 class="game-title"><?= htmlspecialchars($game['title']) ?></h5>
                <p class="game-category"><i class="fa fa-tag"></i> <?= htmlspecialchars($game['category']) ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="free-badge"><i class="fa fa-gift"></i> FREE</span>
                  <span class="game-rating">
                    <i class="fa fa-star"></i> <?= $game['rating'] ?>/5
                  </span>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- CTA Section -->
  <div class="container">
    <div class="cta-section">
      <h2 class="cta-title">Ready to Start Gaming?</h2>
      <p class="cta-text">Join thousands of gamers and explore our massive collection</p>
      <a href="view/frontoffice/shop-home.php" class="btn btn-light btn-lg px-5" style="font-weight: 700;">
        <i class="fa fa-rocket"></i> Get Started Now
      </a>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2025 <a href="#">GameAct</a> Company. All rights reserved. 
          <br>Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a> | Developed with ❤️</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="<?= $baseUrl ?>/public/vendor/jquery/jquery.min.js"></script>
  <script src="<?= $baseUrl ?>/public/vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Template JS -->
  <script src="<?= $baseUrl ?>/public/assets/js/isotope.min.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/custom.js"></script>

  <!-- ORDER FIXED -->
  <script src="<?= $baseUrl ?>/public/assets/js/feed.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/like.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/share.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/poll.js"></script>

  <!-- MUST BE LAST (validation attaches after DOM + feed build) -->
  <script src="<?= $baseUrl ?>/public/assets/js/community_validation.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/tts.js"></script>

  <!-- Additional shop scripts -->
  <script src="<?= $baseUrl ?>/public/assets/js/owl-carousel.js"></script>

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