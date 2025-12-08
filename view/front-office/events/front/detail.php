<?php
// Include the necessary eventC.php file
require_once(__DIR__ . '/../../../../controller/eventC.php');

// Get event ID from URL
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Create an instance of EventC class
$eventController = new EventC();

// Fetch the event details
$event = null;
if ($eventId > 0) {
    try {
        $event = $eventController->showEvent($eventId);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Function to format date in French
function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;
    
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    
    return $day . ' ' . $month . ' ' . $year;
}

// Function to format date range
function formatDateRange($date, $heure_deb, $heure_fin) {
    $formatted = formatDate($date);
    if (!empty($heure_deb) && strtotime($heure_deb) !== false) {
        $formatted .= ' de ' . date('H:i', strtotime($heure_deb));
        if (!empty($heure_fin) && strtotime($heure_fin) !== false) {
            $formatted .= ' à ' . date('H:i', strtotime($heure_fin));
        }
    }
    return $formatted;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Détail Événement</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="events-custom.css">
</head>
<body>

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

  <!-- HEADER -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.html" class="logo">
              <img src="../../assets/images/logo.png" alt="GameAct" style="height:60px;">
            </a>
            <ul class="nav">
              <li><a href="../../index.html">Accueil</a></li>
              <li><a href="../../browse.html">Parcourir</a></li>
              <li><a href="index.php" class="active">Events</a></li>
              <li><a href="../../streams.html">Tutorials</a></li>
              <li><a href="../../profile.html">Profil</a></li>
              <li><a href="../../leaderboard.html">Leaderboard</a></li>
            </ul>
            <a class='menu-trigger'><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- CONTENU -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          <div class="mb-4">
            <a href="index.php" class="btn-back">
              <i class="fa fa-arrow-left"></i> Retour aux événements
            </a>
          </div>

          <?php if (!$event): ?>
            <!-- Event not found -->
            <div class="text-center" style="color:#ccc; padding:3rem;">
              <h2 style="color:#e94560; margin-bottom:1rem;">Événement introuvable</h2>
              <p style="font-size:1.2rem;">L'événement que vous recherchez n'existe pas ou a été supprimé.</p>
              <a href="index.php" class="btn-primary" style="display:inline-block; margin-top:2rem;">Retour à la liste</a>
            </div>
          <?php else: ?>
            <!-- Event details -->
            <div class="text-center mb-5">
              <h1 style="color:#e94560; font-size:2.5rem;"><?= htmlspecialchars($event['titre']); ?></h1>
            </div>

            <div class="event-info text-center mb-4" style="background:rgba(255,255,255,0.05); padding:2rem; border-radius:20px; border:2px solid rgba(233,69,96,0.3);">
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-calendar" style="color:#e94560;"></i> 
                <strong><?= formatDateRange($event['date'], $event['heure_deb'], $event['heure_fin']); ?></strong>
              </p>
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-map-marker" style="color:#e94560;"></i> 
                <strong><?= htmlspecialchars($event['lieu']); ?></strong>
              </p>
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-info-circle" style="color:#e94560;"></i> 
                <strong>Statut : <?= htmlspecialchars($event['statut']); ?></strong>
              </p>
            </div>

            <div class="text-center mb-4" style="background:rgba(255,255,255,0.05); padding:2rem; border-radius:20px; border:2px solid rgba(233,69,96,0.3);">
              <h3 style="color:#e94560; margin-bottom:1rem;">Description</h3>
              <p style="font-size:1.1rem; color:#ccc; max-width:800px; margin:0 auto; line-height:1.8;">
                <?= nl2br(htmlspecialchars($event['description'])); ?>
              </p>
            </div>

            <div class="text-center mb-5">
              <a href="participation.php?id=<?= $eventId ?>" class="btn-primary">S'inscrire maintenant</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="mt-5 py-4" style="background:#0f0f1e; border-top:1px solid #333;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <p style="color:#aaa; font-size:0.9rem; margin:0;">
            Copyright © 2025 <a href="#" style="color:#e94560; text-decoration:none;">GameAct</a>. Tous droits réservés.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/custom.js"></script>
</body>
</html>

