<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Vérifier si l'ID est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: shop.php');
    exit();
}

$gameId = (int)$_GET['id'];
$game = $gameController->getGameById($gameId);

// Si le jeu n'existe pas, rediriger
if (!$game) {
    header('Location: shop.php');
    exit();
}

// Récupérer le nombre d'articles dans le panier
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
$cartCount = $gameController->getCartItemCount($userId);

// Normalize file paths for image and download link
function normalize_path($path) {
  if (empty($path)) return '';
  $path = trim($path);
  // If absolute URL, return as-is
  if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
    return $path;
  }
  // If root-relative (starts with /) or already parent-relative, return as-is
  if (strpos($path, '/') === 0 || strpos($path, '../') === 0) {
    return $path;
  }
  // Otherwise prefix with ../ to reach the assets folder from this view
  return '../' . ltrim($path, './');
}

// Function to extract YouTube video ID from various URL formats
function getYouTubeEmbedUrl($url) {
  if (empty($url)) return '';
  
  $videoId = '';
  
  // Check for different YouTube URL formats
  if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $matches)) {
    $videoId = $matches[1];
  } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $matches)) {
    $videoId = $matches[1];
  } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $matches)) {
    $videoId = $matches[1];
  } elseif (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $matches)) {
    $videoId = $matches[1];
  }
  
  if (!empty($videoId)) {
    return "https://www.youtube.com/embed/{$videoId}";
  }
  
  return '';
}

$imgSrc = normalize_path($game['image_path'] ?? '');
$trailerUrl = $game['trailer_path'] ?? '';
$youtubeEmbedUrl = getYouTubeEmbedUrl($trailerUrl);
$downloadLink = normalize_path($game['download_link'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - <?= htmlspecialchars($game['title']) ?></title>

  <!-- CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="../../public/assets/css/moving-bg.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

  <style>
    .game-details {
      background: #16213e;
      border-radius: 15px;
      padding: 30px;
      color: #fff;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      margin-top: 20px;
    }
    .game-title {
      color: #ec6090;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
    }
    .game-price {
      font-size: 2.2rem;
      color: #ec6090;
      font-weight: 700;
    }
    .free-badge {
      background: #28a745;
      color: white;
      padding: 10px 25px;
      border-radius: 50px;
      font-size: 1.5rem;
      font-weight: 600;
      display: inline-block;
    }
    .btn-cart {
      background: #ec6090;
      color: #fff;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 50px;
      border: none;
      transition: 0.3s;
      cursor: pointer;
    }
    .btn-cart:hover {
      background: #d14e7a;
      transform: translateY(-2px);
      color: #fff;
    }
    .btn-cart:disabled {
      background: #666;
      cursor: not-allowed;
      transform: none;
    }
    .btn-fav {
      background: transparent;
      color: #ec6090;
      border: 2px solid #ec6090;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 50px;
      transition: 0.3s;
    }
    .btn-fav:hover {
      background: #ec6090;
      color: #fff;
    }
    .btn-fav.active {
      background: #ec6090;
      color: #fff;
    }
    .trailer-container {
      position: relative;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
      border-radius: 12px;
      margin: 20px 0;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      background: #000;
    }
    .trailer-container iframe {
      position: absolute;
      top: 0; 
      left: 0;
      width: 100%; 
      height: 100%;
      border: 0;
      border-radius: 12px;
    }
    .meta-info {
      background: #1a1a2e;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .meta-info i {
      color: #ec6090;
      margin-right: 8px;
    }
    .back-btn {
      color: #ec6090;
      text-decoration: none;
      font-weight: 600;
    }
    .back-btn:hover {
      text-decoration: underline;
    }
    .profile-img {
      width: 32px !important;
      height: 32px !important;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 8px;
      border: 2px solid #ec6090;
    }
    .favorites-badge, .cart-badge {
      background: #ec6090;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
      font-weight: 600;
    }
    .quantity-selector {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-right: 15px;
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
      background: #1a1a2e;
      border: 2px solid #2a2a3e;
      color: #fff;
      border-radius: 8px;
      padding: 5px;
      font-weight: 600;
    }
    .alert-cart {
      position: fixed;
      top: 100px;
      right: 20px;
      z-index: 9999;
      min-width: 300px;
      animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>
</head>
<body>

  <div class="moving-bg"></div>

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
            <a class="menu-trigger"><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <div class="container mt-5">
    <div class="row mb-3">
      <div class="col-12">
        <a href="shop.php" class="back-btn"><i class="fa fa-arrow-left"></i> Retour au Shop</a>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-12">
        <div class="game-details">

          <div class="row">
            <!-- Image + Vidéo -->
            <div class="col-md-6">
              <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="img-fluid rounded mb-4" style="width: 100%; border-radius: 12px;">
              
              <?php if (!empty($youtubeEmbedUrl)): ?>
              <div class="trailer-container">
                <iframe 
                  src="<?= htmlspecialchars($youtubeEmbedUrl) ?>" 
                  title="YouTube video player" 
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                  allowfullscreen>
                </iframe>
              </div>
              <?php endif; ?>
            </div>

            <!-- Détails -->
            <div class="col-md-6">
              <h1 class="game-title"><?= htmlspecialchars($game['title']) ?></h1>

              <div class="meta-info">
                <p><i class="fa fa-tag"></i> <strong>Catégorie:</strong> <?= htmlspecialchars($game['category']) ?></p>
                <p><i class="fa fa-calendar"></i> <strong>Date d'ajout:</strong> <?= date('d/m/Y', strtotime($game['date_added'])) ?></p>
                <p><i class="fa fa-star"></i> <strong>Note:</strong> <?= $game['rating'] ?>/5</p>
                <p><i class="fa fa-download"></i> <strong>Téléchargements:</strong> <?= number_format($game['downloads']) ?></p>
                <p><i class="fa fa-heart"></i> <strong>Likes:</strong> <?= number_format($game['likes']) ?></p>
              </div>

              <?php if ($game['is_free']): ?>
                <p class="free-badge">GRATUIT</p>
              <?php else: ?>
                <p class="game-price">$<?= number_format($game['price'], 2) ?></p>
              <?php endif; ?>

              <div class="mt-4">
                <?php if (!$game['is_free']): ?>
                  <!-- Sélecteur de quantité -->
                  <div class="quantity-selector">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                      <i class="fa fa-minus"></i>
                    </button>
                    <input type="number" id="quantityInput" class="quantity-input" value="1" min="1" max="10" readonly>
                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>

                  <!-- Bouton Ajouter au panier -->
                  <button class="btn btn-cart me-3" id="addToCartBtn" onclick="addToCart(<?= $game['id'] ?>, '<?= htmlspecialchars(addslashes($game['title'])) ?>')">
                    <i class="fa fa-shopping-cart"></i> Ajouter au panier
                  </button>
                <?php else: ?>
                  <!-- Si gratuit, bouton de téléchargement direct -->
                  <?php if (!empty($downloadLink)): ?>
                    <a href="<?= htmlspecialchars($downloadLink) ?>" class="btn btn-success me-3" target="_blank" onclick="incrementDownload(<?= $game['id'] ?>)">
                      <i class="fa fa-download"></i> Télécharger gratuitement
                    </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Bouton Favoris -->
                <button class="btn btn-fav" id="addToFavBtn" onclick="addToFavorites(<?= $game['id'] ?>, '<?= htmlspecialchars(addslashes($game['title'])) ?>')">
                  <i class="fa fa-heart"></i> Ajouter aux favoris
                </button>
              </div>

              <hr style="border-color: #2a2a3e; margin: 30px 0;">

              <h5><i class="fa fa-book"></i> Description</h5>
              <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($game['description'])) ?></p>

              <?php if (!empty($game['storyline'])): ?>
              <h5><i class="fa fa-book"></i> Histoire du jeu</h5>
              <p class="text-muted"><?= nl2br(htmlspecialchars($game['storyline'])) ?></p>
              <?php endif; ?>
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
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

  <script>
    // Charger le compteur de favoris
    function loadFavoritesCount() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      $('#favoritesCount').text(favorites.length);
      
      // Vérifier si le jeu est déjà dans les favoris
      const gameId = <?= $game['id'] ?>;
      const exists = favorites.find(f => f.id == gameId);
      if (exists) {
        $('#addToFavBtn').addClass('active').html('<i class="fa fa-check"></i> Dans les favoris');
      }
    }

    // Ajouter aux favoris
    function addToFavorites(gameId, gameTitle) {
      let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const exists = favorites.find(f => f.id == gameId);

      if (!exists) {
        favorites.push({ id: gameId, title: gameTitle });
        localStorage.setItem('favorites', JSON.stringify(favorites));
        $('#addToFavBtn').addClass('active').html('<i class="fa fa-check"></i> Dans les favoris');
        showAlert('success', '"' + gameTitle + '" ajouté aux favoris !');
        loadFavoritesCount();
      } else {
        showAlert('info', '"' + gameTitle + '" est déjà dans vos favoris !');
      }
    }

    // Changer la quantité
    function changeQuantity(change) {
      const input = $('#quantityInput');
      let newValue = parseInt(input.val()) + change;
      
      if (newValue < 1) newValue = 1;
      if (newValue > 10) newValue = 10;
      
      input.val(newValue);
    }

    // Ajouter au panier
    function addToCart(gameId, gameTitle) {
      const quantity = parseInt($('#quantityInput').val());
      const btn = $('#addToCartBtn');
      
      // Désactiver le bouton pendant la requête
      btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adding...');

      $.ajax({
        url: 'add-to-cart.php',
        method: 'POST',
        data: {
          game_id: gameId,
          quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Mettre à jour le compteur
            $('#cartCount').text(response.cart_count);
            
            // Afficher un message de succès
            showAlert('success', response.message);
            
            // Réinitialiser le bouton
            btn.prop('disabled', false).html('<i class="fa fa-check"></i> Added to Cart!');
            
            // Remettre le texte original après 2 secondes
            setTimeout(function() {
              btn.html('<i class="fa fa-shopping-cart"></i> Ajouter au panier');
            }, 2000);
            
            // Réinitialiser la quantité
            $('#quantityInput').val(1);
          } else {
            showAlert('error', response.message);
            btn.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Ajouter au panier');
          }
        },
        error: function() {
          showAlert('error', 'Erreur lors de l\'ajout au panier');
          btn.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Ajouter au panier');
        }
      });
    }

    // Incrémenter le compteur de téléchargements
    function incrementDownload(gameId) {
      fetch('increment_download.php?id=' + gameId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log('Téléchargement comptabilisé');
          }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Afficher une alerte personnalisée
    function showAlert(type, message) {
      const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
      const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle');
      
      const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show alert-cart" role="alert">
          <i class="fa ${icon}"></i> ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `);
      
      $('body').append(alert);
      
      // Auto-fermer après 3 secondes
      setTimeout(function() {
        alert.alert('close');
      }, 3000);
    }

    // Charger au démarrage
    $(document).ready(function() {
      loadFavoritesCount();
    });
  </script>

</body>
</html>