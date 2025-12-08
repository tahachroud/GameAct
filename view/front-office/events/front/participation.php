<?php

require_once(__DIR__ . '/../../../../controller/eventC.php');

// Get event ID from URL
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Create instances
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Participation</title>

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
            <a href="detail.php?id=<?= $eventId ?>" class="btn-back">
              <i class="fa fa-arrow-left"></i> Retour aux détails
            </a>
          </div>

          <?php if (!$event): ?>
            <!-- Event not found -->
            <div class="text-center" style="color:#ccc; padding:3rem;">
              <h2 style="color:#e94560; margin-bottom:1rem;">Événement introuvable</h2>
              <p style="font-size:1.2rem;">L'événement pour lequel vous essayez de vous inscrire n'existe pas.</p>
              <a href="index.php" class="btn-primary" style="display:inline-block; margin-top:2rem;">Retour à la liste</a>
            </div>
          <?php else: ?>
            
            <div class="text-center mb-5">
              <h1 style="color:#e94560; font-size:2.5rem;">Inscription à : <?= htmlspecialchars($event['titre']); ?></h1>
            </div>

            <div id="inscription" class="form-card" style="display:block;">
              <div class="text-center mb-4">
                <h3 style="color:#e94560;">Formulaire de participation</h3>
                <p style="color:#ccc;">Veuillez remplir les informations ci-dessous pour valider votre inscription.</p>
              </div>

              <form id="formParticipation" action="../../../ajoutparticipation.php" method="POST" novalidate>
                <input type="hidden" name="id" value="<?= $eventId ?>">
                <div class="form-grid">
                  <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="nomP" value="ProGamerX">
                    <span id="error-pseudo" class="error-message"></span>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="emailP" placeholder="vous@exemple.com">
                    <span id="error-email" class="error-message"></span>
                  </div>
                  <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="12345678">
                    <span id="error-telephone" class="error-message"></span>
                  </div>
                  <div class="form-group">
                    <label for="discord">Discord ID</label>
                    <input type="text" id="discord" name="discord" placeholder="User#1234">
                    <span id="error-discord" class="error-message"></span>
                  </div>
                  <div class="form-group">
                    <label for="age">Âge</label>
                    <input type="number" id="age" name="age" value="18">
                    <span id="error-age" class="error-message"></span>
                  </div>
                  <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau">
                      <option>Débutant</option>
                      <option>Intermédiaire</option>
                      <option selected>Pro</option>
                    </select>
                  </div>

                  <div class="checkbox-group">
                    <input type="checkbox" id="conditions">
                    <label for="conditions" style="color:#ccc; margin:0;">J'accepte les conditions du tournoi</label>
                    <span id="error-conditions" class="error-message" style="display:block; width:100%;"></span>
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="btn-confirm">Confirmer l'inscription</button>
                    <a href="detail.php?id=<?= $eventId ?>" class="btn-cancel" style="text-decoration:none; display:inline-block; text-align:center; line-height:45px;">Annuler</a>
                  </div>
                </div>
              </form>
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
  <script src="participation_validation.js?v=<?= time(); ?>"></script>
</body>
</html>
