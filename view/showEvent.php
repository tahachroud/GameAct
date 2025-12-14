<?php
// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listeEvent.php');
    exit;
}

// Inclure les fichiers nécessaires
require_once(__DIR__ . '/../controller/eventC.php');

// Créer une instance de EventC
$eventC = new EventC();

// Récupérer l'événement
$event = $eventC->showEvent($_GET['id']);

if (!$event) {
    header('Location: listeEvent.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Détails Événement · GameAct Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="backoffice/css/style-admin.css">
  <link rel="stylesheet" href="../public/assets/css/moving-bg.css">
  <style>
    body {
      background: transparent !important;
    }
  </style>
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

  <div class="moving-bg"></div>

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
        <h1 class="h3">Détails de l'événement</h1>
        <a href="listeEvent.php" class="btn btn-outline-light">
          <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
      </div>

      <div class="card">
        <div class="mb-4">
          <h3><?= htmlspecialchars($event['titre']); ?></h3>
          <span class="btn btn-success btn-sm"><?= htmlspecialchars($event['statut']); ?></span>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">ID</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['id']); ?></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Titre</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['titre']); ?></div>
            </div>
          </div>

          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Description</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border); min-height: 100px;"><?= htmlspecialchars($event['description']); ?></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Lieu</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['lieu']); ?></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Date</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['date']); ?></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Heure de début</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['heure_deb']); ?></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Heure de fin</label>
              <div class="form-control" style="background: var(--gray); border: 1px solid var(--border);"><?= htmlspecialchars($event['heure_fin']); ?></div>
            </div>
          </div>
        </div>

        <div class="action-buttons">
          <a href="updateEvent.php?id=<?= $event['id']; ?>" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Modifier
          </a>
          <a href="listeEvent.php" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-1"></i> Retour
          </a>
        </div>
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
