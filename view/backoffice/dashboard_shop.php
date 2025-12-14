<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controller/GameController.php';

// Créer une instance du controller
$gameController = new GameController();

// Récupérer les statistiques
$stats = $gameController->getGlobalStats();

// Récupérer les top jeux
$topDownloads = $gameController->getTopDownloadedGames(5);
$topRated = $gameController->getTopRatedGames(5);

// Calculer la note moyenne
$avgRating = isset($stats['avg_rating']) ? round($stats['avg_rating'], 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Admin Dashboard</title>
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
    .stat-card { 
      background: #16213e; 
      border-radius: 12px; 
      padding: 25px; 
      color: #fff; 
      text-align: center; 
      transition: transform 0.3s, box-shadow 0.3s;
      height: 100%;
    }
    .stat-card:hover { 
      transform: translateY(-5px); 
      box-shadow: 0 10px 20px rgba(236,96,144,0.2); 
    }
    .stat-icon { 
      font-size: 3rem; 
      color: #ec6090; 
      margin-bottom: 15px; 
    }
    .stat-number { 
      font-size: 2.5rem; 
      font-weight: 700; 
      color: #ec6090; 
      margin-bottom: 10px; 
    }
    .stat-label { 
      color: #aaa; 
      font-size: 1rem; 
    }
    .table-dark { 
      background: #16213e; 
      border-radius: 12px; 
      overflow: hidden;
    }
    .table-dark th { 
      background: #ec6090; 
      color: #fff;
      border: none;
      padding: 15px;
    }
    .table-dark td { 
      color: #e0e0e0;
      border-color: #2a2a3e;
      padding: 12px 15px;
    }
    .table-dark tbody tr:hover {
      background: #1a1a2e;
    }
    .badge-success { 
      background: #28a745;
      padding: 5px 10px;
      border-radius: 5px;
    }
    .badge-warning {
      background: #ffc107;
      color: #000;
      padding: 5px 10px;
      border-radius: 5px;
    }
    .badge-danger {
      background: #dc3545;
      padding: 5px 10px;
      border-radius: 5px;
    }
    h2, h4 {
      color: #fff !important;
      font-weight: 700;
    }
    .section-title {
      color: #ec6090;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 1.3rem;
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
  </style>
</head>
<body>
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <ul class="nav"> 
              <li><a href="../../index.php">Home</a></li>
              <li><a href="../backoffice/dashboard_shop.php">Admin</a></li>
            </ul>   
            <a class='menu-trigger'>
              <span>Menu</span>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <div class="admin-sidebar">
    <div class="text-center mb-4">
      <img src="../assets/images/logo.png" alt="GameAct" class="logo-admin">
    </div>
    <h5 class="text-white mb-3">Admin Panel</h5>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="nav-item"><a href="games-list.php" class="nav-link"><i class="fa fa-list"></i> Games List</a></li>
      <li class="nav-item"><a href="add-game.php" class="nav-link"><i class="fa fa-plus"></i> Add Game</a></li>
      <li class="nav-item"><a href="../frontoffice/shop.php" class="nav-link"><i class="fa fa-eye"></i> View Site</a></li>
      <li class="nav-item"><hr style="border-color: #2a2a3e;"></li>
      <li class="nav-item"><a href="../../index.html" class="nav-link text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="admin-content">
    <h2 class="mb-4">📊 Dashboard</h2>

    <!-- Statistiques -->
    <div class="row mb-5">
      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <i class="fa fa-gamepad stat-icon"></i>
          <div class="stat-number"><?= $stats['total_games'] ?? 0 ?></div>
          <div class="stat-label">Total Games</div>
        </div>
      </div>
      
      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <i class="fa fa-download stat-icon"></i>
          <div class="stat-number"><?= number_format($stats['total_downloads'] ?? 0) ?></div>
          <div class="stat-label">Total Downloads</div>
        </div>
      </div>
      
      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <i class="fa fa-heart stat-icon"></i>
          <div class="stat-number"><?= number_format($stats['total_likes'] ?? 0) ?></div>
          <div class="stat-label">Total Likes</div>
        </div>
      </div>
      
      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <i class="fa fa-star stat-icon"></i>
          <div class="stat-number"><?= $avgRating ?></div>
          <div class="stat-label">Avg Rating</div>
        </div>
      </div>
    </div>

    <!-- Top Downloaded (Last 7 Days) -->
    <div class="row mb-5">
      <div class="col-12">
        <h4 class="section-title">🔥 Top Downloaded (Last 7 Days)</h4>
        <div class="table-responsive">
          <table class="table table-dark">
            <thead>
              <tr>
                <th>#</th>
                <th>Game</th>
                <th>Category</th>
                <th>Downloads (7 days)</th>
                <th>Total Downloads</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($topDownloads)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">Aucun jeu trouvé</td>
                </tr>
              <?php else: ?>
                <?php foreach ($topDownloads as $index => $game): ?>
                  <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= htmlspecialchars($game['title']) ?></strong></td>
                    <td><?= htmlspecialchars($game['category']) ?></td>
                    <td><span class="badge-success"><?= number_format($game['downloads_7days']) ?></span></td>
                    <td><?= number_format($game['downloads']) ?></td>
                    <td>
                      <a href="../frontoffice/details.php?id=<?= $game['id'] ?>" class="btn btn-sm btn-outline-light" target="_blank">
                        <i class="fa fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Top Rated Games -->
    <div class="row">
      <div class="col-12">
        <h4 class="section-title">⭐ Top Rated Games (All Time)</h4>
        <div class="table-responsive">
          <table class="table table-dark">
            <thead>
              <tr>
                <th>#</th>
                <th>Game</th>
                <th>Category</th>
                <th>Rating</th>
                <th>Likes</th>
                <th>Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($topRated)): ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">Aucun jeu trouvé</td>
                </tr>
              <?php else: ?>
                <?php foreach ($topRated as $index => $game): ?>
                  <tr>
                    <td><?= $index + 1 ?></td>
                    <td><strong><?= htmlspecialchars($game['title']) ?></strong></td>
                    <td><?= htmlspecialchars($game['category']) ?></td>
                    <td>
                      <span class="badge-warning">
                        <i class="fa fa-star"></i> <?= $game['rating'] ?>
                      </span>
                    </td>
                    <td><span class="badge-danger"><?= number_format($game['likes']) ?></span></td>
                    <td>
                      <?php if ($game['is_free']): ?>
                        <span class="badge-success">FREE</span>
                      <?php else: ?>
                        $<?= number_format($game['price'], 2) ?>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="../frontoffice/details.php?id=<?= $game['id'] ?>" class="btn btn-sm btn-outline-light" target="_blank">
                        <i class="fa fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
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

</body>
</html>