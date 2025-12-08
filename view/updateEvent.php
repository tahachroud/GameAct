<?php
// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listeEvent.php');
    exit;
}

// Inclure les fichiers nécessaires
require_once(__DIR__ . '/../controller/eventC.php');
require_once(__DIR__ . '/../model/event.php');

$eventC = new EventC();

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Récupérer les champs
    $titre = isset($_POST["titre"]) ? trim($_POST["titre"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $lieu = isset($_POST["lieu"]) ? trim($_POST["lieu"]) : "";
    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    $statut = isset($_POST["statut"]) ? $_POST["statut"] : "";
    $heure_deb = isset($_POST["heure_deb"]) ? $_POST["heure_deb"] : "";
    $heure_fin = isset($_POST["heure_fin"]) ? $_POST["heure_fin"] : "";
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        ?>
        <script>
            alert("Erreur : ID événement invalide !");
            window.location.href = 'listeEvent.php';
        </script>
        <?php
        exit;
    }

    // Vérifier les champs vides (similaire à ajoutEvent.php)
    if (empty($titre) || empty($description) || empty($lieu) || empty($date) || empty($statut) || empty($heure_deb) || empty($heure_fin)) {
        ?>
        <script>
            alert("Erreur : Veuillez remplir tous les champs !");
            window.location.href = 'updateEvent.php?id=<?= $id ?>';
        </script>
        <?php
        exit;
    }

    // Création de l'objet Event
    $updatedEvent = new Event(
        $id,
        $titre,
        $description,
        $lieu,
        $date,
        $statut,
        $heure_deb,
        $heure_fin
    );

    // Mettre à jour l'événement via le contrôleur
    try {
        $eventC->updateEvent($updatedEvent, $id);
        ?>
        <script>
            alert("Événement mis à jour avec succès !");
            window.location.href = 'listeEvent.php';
        </script>
        <?php
        exit;
    } catch (Exception $e) {
        // En cas d'erreur de base de données
        ?>
        <script>
            alert("Erreur lors de la mise à jour : <?= $e->getMessage(); ?>");
            window.location.href = 'updateEvent.php?id=<?= $id ?>';
        </script>
        <?php
        exit;
    }
} else {
    // Affichage du formulaire de mise à jour
    $eventId = intval($_GET['id']);
    try {
        $event = $eventC->showEvent($eventId);
        if (!$event) {
            header('Location: listeEvent.php');
            exit;
        }
    } catch (Exception $e) {
        // En cas d'erreur lors de la récupération
        header('Location: listeEvent.php');
        exit;
    }
}

// Fonction pour sécuriser les données si nécessaire (déjà fait par htmlspecialchars dans le HTML)
// function secure_output($data) {
//     return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
// }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Modifier Événement</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../assets/css/fontawesome.css">

  <link rel="stylesheet" href="style-admin.css"> 
  <link rel="stylesheet" href="events-custom.css"> 
</head>
<body style="background: #1f2122;">

  <main class="container py-5">
    <div class="card p-4 mx-auto" style="max-width: 800px; background: #2a2a2a; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
      <h2 class="card-title text-center mb-4" style="color: #e94560; font-weight: 700;">
        <i class="fas fa-edit me-2"></i> Modifier l'événement : <?= htmlspecialchars($event['titre']); ?>
      </h2>
      <div class="card-body">
        <form action="updateEvent.php?id=<?= $eventId ?>" method="POST">
          <input type="hidden" name="id" value="<?= $eventId ?>">
          
          <div class="mb-4">
            <label class="form-label">Titre</label>
            <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($event['titre']); ?>" required>
          </div>

          <div class="mb-4">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($event['description']); ?></textarea>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Lieu</label>
              <input type="text" class="form-control" name="lieu" value="<?= htmlspecialchars($event['lieu']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date</label>
              <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($event['date']); ?>" required>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Statut</label>
              <select class="form-select" name="statut" required>
                <option value="à venir" <?= $event['statut'] == 'à venir' ? 'selected' : ''; ?>>À venir</option>
                <option value="en cours" <?= $event['statut'] == 'en cours' ? 'selected' : ''; ?>>En cours</option>
                <option value="terminé" <?= $event['statut'] == 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                <option value="annulé" <?= $event['statut'] == 'annulé' ? 'selected' : ''; ?>>Annulé</option>
              </select>
            </div>
          </div>

          <div class="d-flex gap-3 mb-4">
            <div class="flex-fill">
              <label class="form-label">Heure de début</label>
              <input type="time" class="form-control" name="heure_deb" value="<?= htmlspecialchars($event['heure_deb']); ?>" required>
            </div>
            <div class="flex-fill">
              <label class="form-label">Heure de fin</label>
              <input type="time" class="form-control" name="heure_fin" value="<?= htmlspecialchars($event['heure_fin']); ?>" required>
            </div>
          </div>

          <div class="action-buttons">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Enregistrer
            </button>
            <a href="listeEvent.php" class="btn btn-warning">
              <i class="fas fa-times me-1"></i> Annuler
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="mt-5 py-4" style="background:#0f0f1e; border-top:1px solid #333;">
    </footer>
  
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/custom.js"></script>
</body>
</html>