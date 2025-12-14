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
    
    // Vérifier que tous les champs requis sont remplis
    if (!empty($titre) && !empty($description) && !empty($lieu) && !empty($date) && !empty($statut) && !empty($heure_deb) && !empty($heure_fin)) {
        // Création de l'objet Event
        $event = new Event(
            $id,
            $titre,
            $description,
            $lieu,
            $date,
            $statut,
            $heure_deb,
            $heure_fin
        );

        // Mettre à jour l'événement
        try {
            $result = $eventC->updateEvent($event, $id);
            if ($result !== false) {
                ?>
                <script>
                    alert("Événement modifié avec succès !");
                    window.location.href = 'listeEvent.php';
                </script>
                <?php
                exit;
            } else {
                echo "<br><strong>Erreur : La mise à jour a échoué.</strong><br>";
            }
        } catch (Exception $e) {
            echo "<br><strong>Erreur : " . htmlspecialchars($e->getMessage()) . "</strong><br>";
        }
    } else {
        echo "<br><strong>Erreur : Un ou plusieurs champs sont vides.</strong><br>";
        if (empty($titre)) echo "Champ titre est vide.<br>";
        if (empty($description)) echo "Champ description est vide.<br>";
        if (empty($lieu)) echo "Champ lieu est vide.<br>";
        if (empty($date)) echo "Champ date est vide.<br>";
        if (empty($statut)) echo "Champ statut est vide.<br>";
        if (empty($heure_deb)) echo "Champ heure de début est vide.<br>";
        if (empty($heure_fin)) echo "Champ heure de fin est vide.<br>";
    }
}

// Récupérer l'événement à modifier
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listeEvent.php');
    exit;
}

$event = $eventC->showEvent($_GET['id']);

if (!$event) {
    ?>
    <script>
        alert("Événement non trouvé !");
        window.location.href = 'listeEvent.php';
    </script>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier Événement · GameAct Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="backoffice/css/style-admin.css">
  <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebar = document.getElementById('sidebar');
      
      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('show');
        });
      }
    });
  </script>
</head>
<body>

  <!-- Header -->
  <header class="app-header">
    <div class="header-content">
      <div class="d-flex align-items-center">
        <button class="btn btn-link text-light d-lg-none me-3" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        <a href="#" class="text-decoration-none">
          <span class="brand-text">GameAct Admin</span>
        </a>
      </div>
      <div>
        <button class="btn btn-outline-light btn-sm me-2"><i class="fas fa-bell"></i></button>
        <a href="#" class="btn btn-outline-danger btn-sm">Déconnexion</a>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <aside class="app-sidebar" id="sidebar">
    <div class="sidebar-brand">
      <h4 class="text-gradient">GameAct</h4>
    </div>
    <nav class="nav-sidebar">
      <a href="#" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="listeEvent.php" class="nav-link active"><i class="fas fa-calendar-check"></i> Événements</a>
      <a href="#" class="nav-link"><i class="fas fa-users"></i> Participants</a>
      <a href="#" class="nav-link"><i class="fas fa-user"></i> Utilisateurs</a>
      <a href="#" class="nav-link"><i class="fas fa-comments"></i> Feed</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="app-main">
    <div class="container-fluid">
      <div class="d-flex justify-between align-center mb-4">
        <h1 class="h3">Modifier l'événement</h1>
        <a href="listeEvent.php" class="btn btn-outline-light">
          <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
      </div>

      <div class="card">
        <form method="POST" action="updateEvent.php?id=<?= $event['id']; ?>">
          <input type="hidden" name="id" value="<?= $event['id']; ?>">
          
          <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($event['titre']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($event['description']); ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Lieu</label>
            <input type="text" class="form-control" name="lieu" value="<?= htmlspecialchars($event['lieu']); ?>" required>
          </div>

          <div class="d-flex gap-3 mb-3">
            <div class="flex-fill">
              <label class="form-label">Date</label>
              <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($event['date']); ?>" required>
            </div>
            <div class="flex-fill">
              <label class="form-label">Statut</label>
              <select class="form-select" name="statut" required>
                <option value="">Sélectionnez un statut</option>
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

  <!-- Footer -->
  <footer class="app-footer">
    <strong>© 2025 GameAct Admin.</strong> Tous droits réservés.
    <span class="float-end d-none d-sm-inline">Version 1.0</span>
  </footer>

</body>
</html>
