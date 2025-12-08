<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controllers/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Récupérer tous les jeux pour pouvoir afficher les favoris
$allGames = $gameController->getAllGames();

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
  
  <title>GameAct - Mes Favoris</title>

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
    .favorites-badge {
      background: #ec6090;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
      font-weight: 600;
    }
    .favorite-card {
      background: #16213e;
      border-radius: 12px;
      padding: 20px;
      color: #fff;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      position: relative;
    }
    .favorite-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(236, 96, 144, 0.3);
    }
    .favorite-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .favorite-card h5 {
      color: #ec6090;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .favorite-card p {
      color: #aaa;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }
    .btn-remove {
      background: transparent;
      color: #ff4444;
      border: 2px solid #ff4444;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 600;
      transition: 0.3s;
      width: 100%;
    }
    .btn-remove:hover {
      background: #ff4444;
      color: #fff;
    }
    .btn-view {
      background: #ec6090;
      color: #fff;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 600;
      transition: 0.3s;
      width: 100%;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      margin-bottom: 10px;
    }
    .btn-view:hover {
      background: #d14e7a;
      color: #fff;
      transform: translateY(-2px);
    }
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      background: #16213e;
      border-radius: 15px;
      margin-top: 30px;
    }
    .empty-state i {
      font-size: 5rem;
      color: #ec6090;
      margin-bottom: 20px;
    }
    .empty-state h3 {
      color: #fff;
      font-weight: 700;
      margin-bottom: 15px;
    }
    .empty-state p {
      color: #aaa;
      font-size: 1.1rem;
      margin-bottom: 30px;
    }
    .price-badge {
      background: #ec6090;
      color: #fff;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }
    .free-badge {
      background: #28a745;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }
    .rating-badge {
      background: #1a1a2e;
      color: #ffd700;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
      margin-right: 10px;
    }
  </style>
</head>

<body>
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
            <a href="../../index.html" class="logo">
              <img src="../assets/images/logo.png" alt="GameAct">
            </a>
            <div class="search-input">
              <form id="search" action="#">
                <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <ul class="nav">
              <li><a href="../../index.html">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="all-games.php">Tous les Jeux</a></li>
              <li><a href="../../profile.html">Profile <img src="../assets/images/profile-header.png" alt="Profile" class="profile-img"></a></li>
              <li>
                <a href="favorites.php" class="active">
                  <i class="fa fa-heart" style="color: #ec6090;"></i> 
                  Favoris
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
            <h4><i class="fa fa-heart text-danger"></i> <em>Mes</em> Jeux Favoris</h4>
          </div>

          <!-- Grille des favoris -->
          <div class="row" id="favoritesList">
            <!-- Les favoris seront chargés ici par JavaScript -->
          </div>

          <!-- Message si vide -->
          <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fa fa-heart-o"></i>
            <h3>Aucun jeu dans vos favoris</h3>
            <p>Ajoutez des jeux à vos favoris pour les retrouver facilement ici !</p>
            <a href="shop.php" class="btn btn-outline-light px-4">
              <i class="fa fa-shopping-bag"></i> Découvrir les jeux
            </a>
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
    // Base de données des jeux (depuis PHP)
    const allGamesData = <?= json_encode($allGames) ?>;

    // Charger et afficher les favoris
    function loadFavorites() {
      const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      const favoritesList = $('#favoritesList');
      const emptyState = $('#emptyState');
      
      // Mettre à jour le compteur
      $('#favoritesCount').text(favorites.length);

      if (favorites.length === 0) {
        favoritesList.empty();
        emptyState.show();
        return;
      }

      emptyState.hide();
      favoritesList.empty();

      favorites.forEach(fav => {
        // Trouver le jeu complet dans la base de données
        const game = allGamesData.find(g => g.id == fav.id);
        
        if (game) {
          const isFree = game.is_free == 1;
          const priceHtml = isFree 
            ? `<span class="free-badge">GRATUIT</span>` 
            : `<span class="price-badge">$${parseFloat(game.price).toFixed(2)}</span>`;

          const cardHtml = `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="favorite-card">
                <img src="../${game.image_path}" alt="${game.title}">
                <h5>${game.title}</h5>
                <p><i class="fa fa-tag"></i> ${game.category}</p>
                <div class="mb-3">
                  <span class="rating-badge">
                    <i class="fa fa-star"></i> ${game.rating}
                  </span>
                  ${priceHtml}
                </div>
                <a href="details.php?id=${game.id}" class="btn-view">
                  <i class="fa fa-eye"></i> Voir les détails
                </a>
                <button class="btn-remove" onclick="removeFromFavorites(${game.id}, '${game.title}')">
                  <i class="fa fa-trash"></i> Retirer des favoris
                </button>
              </div>
            </div>
          `;
          
          favoritesList.append(cardHtml);
        }
      });
    }

    // Retirer un jeu des favoris
    function removeFromFavorites(gameId, gameTitle) {
      if (confirm(`Êtes-vous sûr de vouloir retirer "${gameTitle}" de vos favoris ?`)) {
        let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        favorites = favorites.filter(f => f.id != gameId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        
        // Recharger l'affichage
        loadFavorites();
        
        // Notification
        alert(`"${gameTitle}" a été retiré de vos favoris !`);
      }
    }

    // Charger les favoris au chargement de la page
    $(document).ready(function() {
      loadFavorites();
    });
  </script>

</body>
</html>