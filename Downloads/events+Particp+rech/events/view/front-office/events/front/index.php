<?php
// Include the necessary eventC.php file
require_once(__DIR__ . '/../../../../controller/eventC.php');

// Create an instance of EventC class
$eventController = new EventC();

// Fetch the list of events
$events = $eventController->listEvents();

// Function to format date in French
function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;
    
    $months = [
        1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
    ];
    
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    
    return $day . ' ' . $month . ' ' . $year;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Événements</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome + TemplateMo -->
  <link rel="stylesheet" href="../../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../../assets/css/templatemo-cyborg-gaming.css">

  <!-- Events Design -->
  <link rel="stylesheet" href="events-custom.css">
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

          <!-- Titre -->
          <div class="text-center mb-5">
            <h1 style="color:#e94560; font-size:2.5rem;">Événements Gaming</h1>
          </div>

          <!-- Liste des événements -->
          <div class="row g-4">
            <?php if (empty($events)): ?>
              <div class="col-12">
                <div class="text-center" style="color:#ccc; padding:3rem;">
                  <p style="font-size:1.2rem;">Aucun événement disponible pour le moment.</p>
                </div>
              </div>
            <?php else: ?>
              <?php foreach ($events as $event): ?>
                <div class="col-lg-4 col-md-6">
                  <div class="event-card">
                    <div class="event-body">
                      <h4><?= htmlspecialchars($event['titre']); ?><br><span><?= formatDate($event['date']); ?></span></h4>
                      <p class="event-info">
                        <i class="fa fa-map-marker"></i> <?= htmlspecialchars($event['lieu']); ?><br>
                        <?php if (!empty($event['heure_deb']) && !empty($event['heure_fin'])): ?>
                          <i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($event['heure_deb'])); ?> - <?= date('H:i', strtotime($event['heure_fin'])); ?><br>
                        <?php endif; ?>
                        <i class="fa fa-info-circle"></i> <?= htmlspecialchars($event['statut']); ?>
                      </p>
                      <a href="detail.php?id=<?= $event['id']; ?>" class="btn-primary">Voir Détail</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Ajouter un événement -->
          <div class="text-center mt-5">
            <a href="add-event.html" class="btn-primary" style="padding:1rem 2.5rem; font-size:1.2rem;">
              + Ajouter un événement
            </a>
          </div>

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
