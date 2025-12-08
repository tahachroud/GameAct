<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/GameController.php';

$gameController = new GameController();

// Gérer la suppression d'un jeu
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $gameId = (int)$_GET['id'];
    
    // Récupérer le jeu pour supprimer les fichiers
    $game = $gameController->getGameById($gameId);
    
    if ($game) {
        // Supprimer les fichiers physiques
        if (!empty($game['image_path']) && file_exists(__DIR__ . '/../' . $game['image_path'])) {
            unlink(__DIR__ . '/../' . $game['image_path']);
        }
        if (!empty($game['trailer_path']) && file_exists(__DIR__ . '/../' . $game['trailer_path'])) {
            unlink(__DIR__ . '/../' . $game['trailer_path']);
        }
        if (!empty($game['zip_file_path']) && file_exists(__DIR__ . '/../' . $game['zip_file_path'])) {
            unlink(__DIR__ . '/../' . $game['zip_file_path']);
        }
        
        // Supprimer de la base de données
        if ($gameController->deleteGame($gameId)) {
            $_SESSION['success_message'] = 'Jeu supprimé avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la suppression du jeu.';
        }
    }
    
    header('Location: games-list.php');
    exit();
}

// Récupérer tous les jeux
$games = $gameController->getAllGames();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Games List</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      background: #0f0f1e;
      font-family: 'Poppins', sans-serif;
    }
    .admin-sidebar { 
      position: fixed; 
      top: 0; 
      left: 0; 
      width: 250px; 
      height: 100vh; 
      background: #1a1a2e; 
      padding: 20px; 
      color: #fff; 
      z-index: 1000;
      overflow-y: auto;
    }
    .admin-content { 
      margin-left: 250px; 
      padding: 40px; 
      background: #0f0f1e; 
      min-height: 100vh;
    }
    .game-card { 
      background: #16213e; 
      border-radius: 12px; 
      padding: 18px; 
      color: #fff; 
      transition: 0.3s;
      margin-bottom: 20px;
    }
    .game-card:hover { 
      transform: translateY(-5px); 
      box-shadow: 0 10px 20px rgba(236,96,144,0.2); 
    }
    .price-badge { 
      background: #ec6090; 
      color: #fff; 
      padding: 6px 12px; 
      border-radius: 6px; 
      font-weight: 600; 
    }
    .free-badge {
      background: #28a745;
      color: #fff;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 600;
    }
    .game-image {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
      margin-right: 15px;
    }
    h2, h4, h5 {
      color: #fff !important;
      font-weight: 700;
    }
    .nav-link {
      color: #aaa !important;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 5px;
      transition: 0.3s;
    }
    .nav-link:hover, .nav-link.active {
      background: #ec6090;
      color: #fff !important;
    }
    .logo-admin {
      max-width: 150px;
      margin-bottom: 20px;
    }
    .alert {
      border-radius: 10px;
      border: none;
    }
    .btn-action {
      padding: 8px 15px;
      border-radius: 8px;
      font-weight: 600;
      transition: 0.3s;
      margin: 0 5px;
    }
    .search-box {
      background: #16213e;
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
      background: #1a1a2e;
    }
    .game-info p {
      margin: 5px 0;
      color: #aaa;
      font-size: 0.9rem;
    }
    .game-info h5 {
      color: #ec6090 !important;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="admin-sidebar">
    <div class="text-center mb-4">
      <img src="../assets/images/logo.png" alt="GameAct" class="logo-admin">
    </div>
    <h5 class="text-white mb-3">Admin Panel</h5>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="dashboard_shop.php" class="nav-link"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="nav-item"><a href="games-list.php" class="nav-link active"><i class="fa fa-list"></i> Games List</a></li>
      <li class="nav-item"><a href="add-game.php" class="nav-link"><i class="fa fa-plus"></i> Add Game</a></li>
      <li class="nav-item"><a href="../frontoffice/shop.php" class="nav-link"><i class="fa fa-eye"></i> View Site</a></li>
      <li class="nav-item"><hr style="border-color: #2a2a3e;"></li>
      <li class="nav-item"><a href="../../index.html" class="nav-link text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>📋 Games List</h2>
      <a href="add-game.php" class="btn btn-success px-4">
        <i class="fa fa-plus"></i> Add New Game
      </a>
    </div>

    <!-- Messages de succès/erreur -->
    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle"></i> <?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Barre de recherche -->
    <div class="mb-4">
      <input type="text" id="searchInput" class="search-box" placeholder="🔍 Rechercher un jeu...">
    </div>

    <!-- Liste des jeux -->
    <div class="row" id="gamesList">
      <?php if (empty($games)): ?>
        <div class="col-12 text-center py-5">
          <i class="fa fa-gamepad" style="font-size: 4rem; color: #ec6090;"></i>
          <h4 class="text-white mt-3">Aucun jeu disponible</h4>
          <p class="text-muted">Commencez par ajouter votre premier jeu !</p>
          <a href="add-game.php" class="btn btn-success mt-3">
            <i class="fa fa-plus"></i> Ajouter un jeu
          </a>
        </div>
      <?php else: ?>
        <?php foreach ($games as $game): ?>
          <div class="col-md-6 mb-4 game-item" 
               data-title="<?= strtolower(htmlspecialchars($game['title'])) ?>"
               data-category="<?= strtolower(htmlspecialchars($game['category'])) ?>">
            <div class="game-card">
              <div class="d-flex align-items-center">
                <img src="../<?= htmlspecialchars($game['image_path']) ?>" 
                     alt="<?= htmlspecialchars($game['title']) ?>" 
                     class="game-image">
                
                <div class="game-info flex-grow-1">
                  <h5><?= htmlspecialchars($game['title']) ?></h5>
                  <p><i class="fa fa-tag"></i> <?= htmlspecialchars($game['category']) ?></p>
                  <p><i class="fa fa-star text-warning"></i> <?= $game['rating'] ?> | 
                     <i class="fa fa-download"></i> <?= number_format($game['downloads']) ?> | 
                     <i class="fa fa-heart text-danger"></i> <?= number_format($game['likes']) ?></p>
                  <p><i class="fa fa-calendar"></i> <?= date('d/m/Y', strtotime($game['date_added'])) ?></p>
                  <?php if ($game['is_free']): ?>
                    <span class="free-badge">GRATUIT</span>
                  <?php else: ?>
                    <span class="price-badge">$<?= number_format($game['price'], 2) ?></span>
                  <?php endif; ?>
                </div>

                <div class="d-flex flex-column">
                  <a href="../frontoffice/details.php?id=<?= $game['id'] ?>" 
                     class="btn btn-sm btn-info btn-action mb-2" 
                     target="_blank">
                    <i class="fa fa-eye"></i> View
                  </a>
                  <a href="edit-game.php?id=<?= $game['id'] ?>" 
                     class="btn btn-sm btn-warning btn-action mb-2">
                    <i class="fa fa-edit"></i> Edit
                  </a>
                  <button onclick="confirmDelete(<?= $game['id'] ?>, '<?= htmlspecialchars($game['title']) ?>')" 
                          class="btn btn-sm btn-danger btn-action">
                    <i class="fa fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Compteur de jeux -->
    <div class="text-center mt-4">
      <p class="text-white">
        <strong id="gamesCount"><?= count($games) ?></strong> jeu<?= count($games) > 1 ? 'x' : '' ?> au total
      </p>
    </div>

  </div>

  <!-- Footer -->
  <footer style="margin-left: 250px; padding: 20px 40px; background: #0f0f1e;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 text-center">
          <p style="color: #aaa; margin: 0;">Copyright © 2025 <a href="#" style="color: #ec6090;">GameAct</a> Company. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

  <script>
    // Fonction de confirmation de suppression
    function confirmDelete(gameId, gameTitle) {
      if (confirm(`Êtes-vous sûr de vouloir supprimer "${gameTitle}" ?\n\nCette action est irréversible et supprimera également tous les fichiers associés.`)) {
        window.location.href = `games-list.php?action=delete&id=${gameId}`;
      }
    }

    // Fonction de recherche en temps réel
    $('#searchInput').on('keyup', function() {
      const searchTerm = $(this).val().toLowerCase();
      let visibleCount = 0;

      $('.game-item').each(function() {
        const title = $(this).data('title');
        const category = $(this).data('category');
        
        if (title.includes(searchTerm) || category.includes(searchTerm)) {
          $(this).show();
          visibleCount++;
        } else {
          $(this).hide();
        }
      });

      // Mettre à jour le compteur
      $('#gamesCount').text(visibleCount);
      
      // Afficher un message si aucun résultat
      if (visibleCount === 0 && searchTerm !== '') {
        if ($('#noResults').length === 0) {
          $('#gamesList').append(`
            <div id="noResults" class="col-12 text-center py-5">
              <i class="fa fa-search" style="font-size: 3rem; color: #ec6090;"></i>
              <h5 class="text-white mt-3">Aucun résultat trouvé</h5>
              <p class="text-muted">Essayez un autre terme de recherche</p>
            </div>
          `);
        }
      } else {
        $('#noResults').remove();
      }
    });
  </script>

</body>
</html>