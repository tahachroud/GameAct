<?php

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure les fichiers nécessaires
    require_once(__DIR__ . '/../controller/eventC.php');
    require_once(__DIR__ . '/../model/event.php');
    $eventC = new EventC();

    // Récupérer les champs du formulaire
    $titre = isset($_POST["titre"]) ? trim($_POST["titre"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $lieu = isset($_POST["lieu"]) ? trim($_POST["lieu"]) : "";
    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    $statut = isset($_POST["statut"]) ? $_POST["statut"] : "";
    $heure_deb = isset($_POST["heure_deb"]) ? $_POST["heure_deb"] : "";
    $heure_fin = isset($_POST["heure_fin"]) ? $_POST["heure_fin"] : "";
    // NOUVEAU: Coordonnées
    $latitude = isset($_POST["latitude"]) && is_numeric($_POST["latitude"]) ? (float)$_POST["latitude"] : null;
    $longitude = isset($_POST["longitude"]) && is_numeric($_POST["longitude"]) ? (float)$_POST["longitude"] : null;


    // Vérifier que tous les champs requis sont définis et non vides
    if (
        !empty($titre) &&
        !empty($description) &&
        !empty($lieu) &&
        !empty($date) &&
        !empty($statut) &&
        !empty($heure_deb) &&
        !empty($heure_fin)
    ) {
        // Création de l'objet Event avec les données du formulaire
        $event = new Event(
            null, 
            $titre,
            $description,
            $lieu,
            $date,
            $statut,
            $heure_deb,
            $heure_fin,
            $latitude, // NOUVEAU
            $longitude // NOUVEAU
        );

        // Ajouter l'événement via le contrôleur
        $result = $eventC->addEvent($event);

        ?>
        <script>
             alert("Événement ajouté avec succès !");
             window.location.href = 'listeEvent.php';
        </script>
        <?php
    } else {
        echo "<br><strong>Erreur : Un ou plusieurs champs sont vides.</strong><br>";
        if (empty($titre)) echo "Champ titre est vide.<br>";
        if (empty($description)) echo "Champ description est vide.<br>";
        if (empty($lieu)) echo "Champ lieu est vide.<br>";
        if (empty($date)) echo "Champ date est vide.<br>";
        if (empty($statut)) echo "Champ statut est vide.<br>";
        if (empty($heure_deb)) echo "Champ heure de début est vide.<br>";
        if (empty($heure_fin)) echo "Champ heure de fin est vide.<br>";

        ?>
        <script>
            setTimeout(function() {
                window.location.href = 'ajoutEvent.php';
            }, 3000);
        </script>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter Événement</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../../assets/css/templatemo-cyborg-gaming.css">
    <link rel="stylesheet" href="events-custom.css">

    <style>
      .btn-primary {
        /* Style du bouton "Ajouter" pour correspondre à votre design. */
        background: #e94560;
        color: white;
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        display: inline-block;
        transition: background-color 0.3s;
      }
      .btn-primary:hover {
        background: #d63384; 
      }
    </style>
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

<header class="header-area header-sticky">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="main-nav">
          <a href="../../index.html" class="logo">
            <img src="../../assets/images/logo.png" alt="">
          </a>
          <ul class="nav">
            <li><a href="../../index.html">Accueil</a></li>
            <li><a href="index.php" class="active">Événements</a></li>
            <li><a href="../../profile.html">Profile <img src="../../assets/images/profile-header.jpg" alt=""></a></li>
          </ul>   
          <a class='menu-trigger'>
            <span>Menu</span>
          </a>
        </nav>
      </div>
    </div>
  </div>
</header>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="heading-section">
                    <h4>Ajouter un nouvel Événement</h4>
                </div>
            </div>
            <div class="col-lg-8">
                <form method="POST" action="">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" class="form-control" name="titre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lieu *</label>
                        <input type="text" class="form-control" name="lieu" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut *</label>
                        <select class="form-control" name="statut" required>
                            <option value="à venir">À venir</option>
                            <option value="en cours">En cours</option>
                            <option value="terminé">Terminé</option>
                            <option value="annulé">Annulé</option>
                        </select>
                    </div>

                    <div class="d-flex gap-3 mb-3">
                        <div class="flex-fill">
                            <label class="form-label">Heure de début *</label>
                            <input type="time" class="form-control" name="heure_deb" required>
                        </div>
                        <div class="flex-fill">
                            <label class="form-label">Heure de fin *</label>
                            <input type="time" class="form-control" name="heure_fin" required>
                        </div>
                    </div>
                    
                    <h5 class="mt-4">Localisation (Optionnel pour la carte)</h5>

                    <div class="d-flex gap-3 mb-4">
                        <div class="flex-fill">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" 
                                   value="" placeholder="Ex: 36.8065">
                        </div>
                        <div class="flex-fill">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" 
                                   value="" placeholder="Ex: 10.1815">
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn-primary">
                          <i class="fas fa-plus me-1"></i> Ajouter l'événement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<footer class="mt-5 py-4">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <p>
            Copyright © 2025 <a href="#">GameAct</a>. Tous droits réservés.
          </p>
        </div>
      </div>
    </div>
</footer>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/isotope.min.js"></script>
<script src="../../assets/js/owl-carousel.js"></script>
<script src="../../assets/js/tabs.js"></script>
<script src="../../assets/js/popup.js"></script>
<script src="../../assets/js/custom.js"></script>

</body>
</html>