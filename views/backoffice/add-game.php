<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le controller
require_once __DIR__ . '/../../controllers/GameController.php';
$gameController = new GameController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $game = new Game();
        $game->setTitle($_POST['title'] ?? '');
        $game->setCategory($_POST['category'] ?? '');
        $game->setDescription($_POST['description'] ?? '');
        $game->setStoryline($_POST['storyline'] ?? '');
        $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
        $game->setIsFree($isFree);
        $game->setPrice($isFree ? 0.00 : (float)($_POST['price'] ?? 0));
        
        $game->setRating(0.0);
        $game->setDateAdded(date('Y-m-d'));
        $game->setDownloads(0);
        $game->setLikes(0);
        $game->setDownloads7days(0);
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                throw new Exception('Type de fichier image non autorisé. Utilisez JPG, PNG ou WEBP.');
            }
            
            $uploadDir = __DIR__ . '/../assets/images/games/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'game_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $game->setImagePath('assets/images/games/' . $filename);
            } else {
                throw new Exception('Erreur lors de l\'upload de l\'image.');
            }
        } else {
            throw new Exception('Image de couverture requise.');
        }
        
        // Trailer YouTube link (optional) -- store the YouTube URL
        $trailerLink = trim($_POST['trailer'] ?? '');
        if ($trailerLink !== '') {
            // Validate it's a YouTube URL
            $isValidYouTube = preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/|v\/)|youtu\.be\/)[\w-]{11}/', $trailerLink);
            if (!$isValidYouTube) {
                throw new Exception('Le lien de la bande-annonce doit être une URL YouTube valide.');
            }
            $game->setTrailerPath($trailerLink);
        }
        
        // Download link (optional) -- store a URL to an external download/page
        $downloadLink = trim($_POST['download_link'] ?? '');
        if ($downloadLink !== '') {
          if (!filter_var($downloadLink, FILTER_VALIDATE_URL)) {
            throw new Exception('Le lien de téléchargement n\'est pas une URL valide.');
          }
          $game->setDownloadLink($downloadLink);
        }
        
        $success = $gameController->addGame($game);
        
        if ($success) {
            $_SESSION['success_message'] = 'Jeu "' . $game->getTitle() . '" ajouté avec succès !';
            header('Location: games-list.php');
            exit();
        } else {
            throw new Exception('Erreur lors de l\'ajout du jeu dans la base de données.');
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Erreur : ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Add New Game</title>
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
    .step { 
      display: none;
      animation: slideIn 0.4s ease-out;
    }
    .step.active { 
      display: block; 
    }
    .progress-bar { 
      height: 8px; 
      background: #ec6090;
      transition: width 0.4s ease;
      border-radius: 4px;
    }
    .form-label {
      color: #ffffff !important;
      font-weight: 600 !important;
      font-size: 1rem !important;
      margin-bottom: 8px;
    }
    .form-control, .form-select {
      background: #16213e !important;
      border: 1px solid #2a2a3e !important;
      color: #e0e0e0 !important;
      border-radius: 8px;
      padding: 10px 12px;
      transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
      background: #1a1a2e !important;
      color: #ffffff !important;
      border-color: #ec6090 !important;
      box-shadow: 0 0 0 0.2rem rgba(236, 96, 144, 0.25);
      transform: translateY(-2px);
    }
    .form-check-input {
      background-color: #16213e;
      border: 1px solid #ec6090;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .form-check-input:checked {
      background-color: #ec6090;
      border-color: #ec6090;
      transform: scale(1.1);
    }
    .form-check-label {
      color: #e0e0e0 !important;
      font-weight: 500;
      cursor: pointer;
    }
    .btn-primary, .btn-success, .btn-secondary {
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 20px;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-primary:hover, .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(236, 96, 144, 0.3);
    }
    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(100, 100, 120, 0.3);
    }
    .preview-img {
      max-width: 220px;
      max-height: 220px;
      border-radius: 12px;
      border: 2px solid #ec6090;
      margin-top: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      animation: fadeIn 0.5s ease-in;
    }
    .step h4 {
      color: #ec6090 !important;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 1.3rem;
    }
    h2 {
      color: #ffffff !important;
      font-weight: 700;
    }
    .error-msg {
      color: #ff6b6b;
      font-size: 0.9rem;
      margin-top: 5px;
      display: none;
      animation: shake 0.5s ease-in-out;
    }
    .error-msg:not(:empty) {
      display: block;
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
    .file-info {
      background: #1a1a2e;
      padding: 10px;
      border-radius: 8px;
      margin-top: 10px;
      color: #aaa;
      font-size: 0.85rem;
    }
    .card-form {
      background: #16213e;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .next-btn:disabled, .prev-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    @media (max-width: 768px) {
      .admin-sidebar {
        width: 200px;
        padding: 15px;
      }
      .admin-content {
        margin-left: 200px;
        padding: 20px;
      }
      .card-form {
        padding: 20px;
      }
      .step h4 {
        font-size: 1.1rem;
      }
      .preview-img {
        max-width: 150px;
        max-height: 150px;
      }
    }

    @media (max-width: 576px) {
      .admin-sidebar {
        width: 150px;
      }
      .admin-content {
        margin-left: 150px;
        padding: 15px;
      }
      .btn-primary, .btn-success, .btn-secondary {
        padding: 8px 15px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

  <div class="admin-sidebar">
    <div class="text-center mb-4">
      <img src="../assets/images/logo.png" alt="GameAct" class="logo-admin">
    </div>
    <h5 class="text-white mb-3">Admin Panel</h5>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="dashboard_shop.php" class="nav-link"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="nav-item"><a href="games-list.php" class="nav-link"><i class="fa fa-list"></i> Games List</a></li>
      <li class="nav-item"><a href="add-game.php" class="nav-link active"><i class="fa fa-plus"></i> Add Game</a></li>
      <li class="nav-item"><a href="../frontoffice/shop.php" class="nav-link"><i class="fa fa-eye"></i> View Site</a></li>
      <li class="nav-item"><hr style="border-color: #2a2a3e;"></li>
      <li class="nav-item"><a href="../../index.html" class="nav-link text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>

  <div class="admin-content">
    <h2 class="mb-4">➕ Add New Game</h2>
    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle"></i> <?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="progress mb-5" style="height: 8px; background: #2a2a3e; border-radius: 4px;">
      <div class="progress-bar" id="progress" style="width: 33%; border-radius: 4px;"></div>
    </div>
    <div id="formContainer"></div>
  </div>
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
  <script src="js/add-game-form.js"></script>

</body>
</html>