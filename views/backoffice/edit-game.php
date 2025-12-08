<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/GameController.php';

$gameController = new GameController();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: games-list.php');
    exit();
}

$gameId = (int)$_GET['id'];
$game = $gameController->getGameById($gameId);

if (!$game) {
    $_SESSION['error_message'] = 'Jeu introuvable.';
    header('Location: games-list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updatedGame = new Game();
        $updatedGame->setId($gameId);
        $updatedGame->setTitle($_POST['title'] ?? $game['title']);
        $updatedGame->setCategory($_POST['category'] ?? $game['category']);
        $updatedGame->setDescription($_POST['description'] ?? $game['description']);
        $updatedGame->setStoryline($_POST['storyline'] ?? $game['storyline']);

        $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
        $updatedGame->setIsFree($isFree);
        $updatedGame->setPrice($isFree ? 0.00 : (float)($_POST['price'] ?? $game['price']));
        
        $updatedGame->setRating((float)$game['rating']);
        
        $imagePath = $game['image_path'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                throw new Exception('Type de fichier image non autorisé. Utilisez JPG, PNG ou WEBP.');
            }
            
            $uploadDir = __DIR__ . '/../assets/images/games/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if (!empty($game['image_path']) && file_exists(__DIR__ . '/../' . $game['image_path'])) {
                unlink(__DIR__ . '/../' . $game['image_path']);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'game_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $imagePath = 'assets/images/games/' . $filename;
            }
        }
        $updatedGame->setImagePath($imagePath);

        $trailerPath = $game['trailer_path'];
        if (isset($_FILES['trailer']) && $_FILES['trailer']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['video/mp4', 'video/webm'];
            if (!in_array($_FILES['trailer']['type'], $allowedTypes)) {
                throw new Exception('Type de fichier vidéo non autorisé. Utilisez MP4 ou WEBM.');
            }
            
            $uploadDir = __DIR__ . '/../assets/videos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($game['trailer_path']) && file_exists(__DIR__ . '/../' . $game['trailer_path'])) {
                unlink(__DIR__ . '/../' . $game['trailer_path']);
            }
            
            $extension = pathinfo($_FILES['trailer']['name'], PATHINFO_EXTENSION);
            $filename = 'trailer_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['trailer']['tmp_name'], $destination)) {
                $trailerPath = 'assets/videos/' . $filename;
            }
        }
        $updatedGame->setTrailerPath($trailerPath);

        $zipPath = $game['zip_file_path'];
        if (isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['zip_file']['type'] !== 'application/zip' && 
                $_FILES['zip_file']['type'] !== 'application/x-zip-compressed') {
                throw new Exception('Type de fichier non autorisé. Utilisez un fichier ZIP.');
            }
            
            $uploadDir = __DIR__ . '/../assets/games/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($game['zip_file_path']) && file_exists(__DIR__ . '/../' . $game['zip_file_path'])) {
                unlink(__DIR__ . '/../' . $game['zip_file_path']);
            }
            
            $filename = 'game_' . uniqid() . '.zip';
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $destination)) {
                $zipPath = 'assets/games/' . $filename;
            }
        }
        $updatedGame->setZipFilePath($zipPath);

        $success = $gameController->updateGame($updatedGame);
        
        if ($success) {
            $_SESSION['success_message'] = 'Jeu "' . $updatedGame->getTitle() . '" modifié avec succès !';
            header('Location: games-list.php');
            exit();
        } else {
            throw new Exception('Erreur lors de la modification du jeu dans la base de données.');
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Erreur : ' . $e->getMessage();
    }
}

$categories = [
    'Action Shooter',
    'Adventure',
    'Battle Royale',
    'FPS',
    'Hero Shooter',
    'MOBA',
    'Open World',
    'RPG',
    'Sandbox',
    'Sports',
    'Strategy'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Edit Game</title>
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
    }
    .form-control:focus, .form-select:focus {
      background: #1a1a2e !important;
      color: #ffffff !important;
      border-color: #ec6090 !important;
      box-shadow: 0 0 0 0.2rem rgba(236, 96, 144, 0.25);
    }
    .form-check-input {
      background-color: #16213e;
      border: 1px solid #ec6090;
    }
    .form-check-input:checked {
      background-color: #ec6090;
      border-color: #ec6090;
    }
    .form-check-label {
      color: #e0e0e0 !important;
      font-weight: 500;
    }
    .btn-primary, .btn-success, .btn-secondary {
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 20px;
    }
    .preview-img {
      max-width: 220px;
      max-height: 220px;
      border-radius: 12px;
      border: 2px solid #ec6090;
      margin-top: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    h2, h4 {
      color: #ffffff !important;
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
    .section-title {
      color: #ec6090 !important;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 1.3rem;
      border-bottom: 2px solid #2a2a3e;
      padding-bottom: 10px;
    }
    .current-file {
      background: #1a1a2e;
      padding: 12px;
      border-radius: 8px;
      margin-top: 8px;
      color: #28a745;
      font-size: 0.9rem;
    }
    .current-file i {
      color: #28a745;
      margin-right: 5px;
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

  <div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>✏️ Edit Game: <?= htmlspecialchars($game['title']) ?></h2>
      <a href="games-list.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to List
      </a>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle"></i> <?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="card-form">
      <form method="POST" enctype="multipart/form-data">

        <h4 class="section-title">📝 General Information</h4>
        <div class="row g-3 mb-4">  ""ed
          <div class="col-md-6">
            <label class="form-label">Game Name *</label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($game['title']) ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Genre *</label>
            <select class="form-select" name="category" required>
              <option value="">Select Genre</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= $game['category'] === $cat ? 'selected' : '' ?>>
                  <?= $cat ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Short Description *</label>
          <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($game['description']) ?></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label">Full Storyline</label>
          <textarea class="form-control" name="storyline" rows="5"><?= htmlspecialchars($game['storyline']) ?></textarea>
        </div>

        <hr style="border-color: #2a2a3e; margin: 30px 0;">

        <!-- Section 2 : Fichiers -->
        <h4 class="section-title">📁 Game Files</h4>
        
        <!-- Image -->
        <div class="mb-4">
          <label class="form-label">Game Cover Image</label>
          <div class="current-file">
            <i class="fa fa-check-circle"></i> Current image: <?= basename($game['image_path']) ?>
          </div>
          <input type="file" class="form-control mt-2" id="gameImage" name="image" accept="image/jpeg,image/png,image/jpg,image/webp">
          <div class="file-info">
            <i class="fa fa-info-circle"></i> Leave empty to keep current image | Formats: JPG, PNG, WEBP
          </div>
          <div class="text-center">
            <img src="../<?= htmlspecialchars($game['image_path']) ?>" 
                 alt="Current" 
                 class="preview-img" 
                 id="imagePreview">
          </div>
        </div>

        <!-- Vidéo -->
        <div class="mb-4">
          <label class="form-label">Game Trailer Video (Optional)</label>
          <?php if (!empty($game['trailer_path'])): ?>
            <div class="current-file">
              <i class="fa fa-check-circle"></i> Current video: <?= basename($game['trailer_path']) ?>
            </div>
          <?php else: ?>
            <div class="current-file" style="color: #aaa;">
              <i class="fa fa-info-circle"></i> No video uploaded yet
            </div>
          <?php endif; ?>
          <input type="file" class="form-control mt-2" name="trailer" accept="video/mp4,video/webm">
          <div class="file-info">
            <i class="fa fa-info-circle"></i> Leave empty to keep current video | Formats: MP4, WEBM | Max: 100MB
          </div>
        </div>

        <!-- ZIP -->
        <div class="mb-4">
          <label class="form-label">Installation Folder (ZIP) - Optional</label>
          <?php if (!empty($game['zip_file_path'])): ?>
            <div class="current-file">
              <i class="fa fa-check-circle"></i> Current ZIP: <?= basename($game['zip_file_path']) ?>
            </div>
          <?php else: ?>
            <div class="current-file" style="color: #aaa;">
              <i class="fa fa-info-circle"></i> No ZIP file uploaded yet
            </div>
          <?php endif; ?>
          <input type="file" class="form-control mt-2" name="zip_file" accept=".zip">
          <div class="file-info">
            <i class="fa fa-info-circle"></i> Leave empty to keep current ZIP | Format: ZIP | Max: 500MB
          </div>
        </div>

        <hr style="border-color: #2a2a3e; margin: 30px 0;">

        <!-- Section 3 : Prix -->
        <h4 class="section-title">💰 Pricing</h4>
        <div class="mb-4">
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="is_free" id="free" value="1" 
                   <?= $game['is_free'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="free">
              <strong>Free Game</strong> - Le jeu est gratuit
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_free" id="paid" value="0" 
                   <?= !$game['is_free'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="paid">
              <strong>Paid Game</strong> - Le jeu est payant
            </label>
          </div>
        </div>

        <div id="paidOptions" style="<?= $game['is_free'] ? 'display:none;' : '' ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Price ($) *</label>
              <input type="number" step="0.01" class="form-control" id="gamePrice" name="price" 
                     value="<?= number_format($game['price'], 2, '.', '') ?>" min="0.99">
            </div>
          </div>
        </div>

        <hr style="border-color: #2a2a3e; margin: 30px 0;">

        <!-- Section 4 : Statistiques (Read-only) -->
        <h4 class="section-title">📊 Statistics (Read-only)</h4>
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <label class="form-label">Rating</label>
            <input type="text" class="form-control" value="⭐ <?= $game['rating'] ?>/5" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">Total Downloads</label>
            <input type="text" class="form-control" value="<?= number_format($game['downloads']) ?>" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">Total Likes</label>
            <input type="text" class="form-control" value="❤️ <?= number_format($game['likes']) ?>" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">Date Added</label>
            <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($game['date_added'])) ?>" readonly>
          </div>
        </div>

        <!-- Boutons -->
        <div class="d-flex justify-content-between mt-5">
          <a href="games-list.php" class="btn btn-secondary px-4">
            <i class="fa fa-times"></i> Cancel
          </a>
          <button type="submit" class="btn btn-success px-5">
            <i class="fa fa-save"></i> Save Changes
          </button>
        </div>

      </form>
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
    // Aperçu de la nouvelle image
    $('#gameImage').change(function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#imagePreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });

    // Gestion prix gratuit/payant
    $('#free').change(function() {
      $('#paidOptions').hide();
      $('#gamePrice').prop('required', false);
    });
    
    $('#paid').change(function() {
      $('#paidOptions').show();
      $('#gamePrice').prop('required', true);
    });
  </script>

</body>
</html>