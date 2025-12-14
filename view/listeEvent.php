<?php
// Include the necessary eventC.php file (chemin relatif optimisé)
require_once(__DIR__ . '/../controller/eventC.php');

// Create an instance of EventC class
$event = new EventC();

// Check if search is active
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchActive = !empty($search);

// Fetch the list of events
if ($searchActive) {
    $tab = $event->searchEvents($search);
} else {
    $tab = $event->listEvents();
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Événements · GameAct Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="backoffice/css/style-admin.css">
  <link rel="stylesheet" href="../public/assets/css/moving-bg.css">
  <style>
    body {
      background: transparent !important;
    }
    /* Action buttons grid layout */
    .action-buttons {
      display: grid !important;
      grid-template-columns: 1fr 1fr;
      gap: 0.5rem;
      padding: 0.5rem !important;
      min-width: 280px;
    }
    
    .action-buttons .btn {
      width: 100%;
      white-space: nowrap;
      font-size: 0.75rem;
      padding: 0.4rem 0.6rem;
    }
    
    @media (max-width: 992px) {
      .action-buttons {
        grid-template-columns: 1fr;
        min-width: 150px;
      }
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
        <h1 class="h3">Événements<?= $searchActive ? ' - Résultats de recherche' : '' ?></h1>
        <div class="d-flex gap-3">
          <a href="calendar.php" class="btn btn-primary">
            <i class="fas fa-calendar-alt"></i> Calendrier
          </a>
          <a href="addEventForm.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un Événement
          </a>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="card mb-3">
        <div class="card-body">
          <form method="GET" action="listeEvent.php" class="row g-3">
            <div class="col-md-10">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Rechercher par titre, description, lieu ou statut..." value="<?= htmlspecialchars($search); ?>">
              </div>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">Rechercher</button>
              <?php if ($searchActive): ?>
                <a href="listeEvent.php" class="btn btn-outline-light w-100 mt-2">Réinitialiser</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <?php if ($searchActive && empty($tab)): ?>
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>Aucun événement trouvé pour "<strong><?= htmlspecialchars($search); ?></strong>"
        </div>
      <?php endif; ?>

      <div class="card">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Lieu</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tab as $event) {
              ?>
              <tr>
                <td><?= htmlspecialchars($event['id']); ?></td>
                <td><?= htmlspecialchars($event['titre']); ?></td>
                <td><?= htmlspecialchars($event['description']); ?></td>
                <td><?= htmlspecialchars($event['lieu']); ?></td>
                <td><?= htmlspecialchars($event['date']); ?></td>
                <td><span class="btn btn-success btn-sm"><?= htmlspecialchars($event['statut']); ?></span></td>
                <td><?= htmlspecialchars($event['heure_deb']); ?></td>
                <td><?= htmlspecialchars($event['heure_fin']); ?></td>
                <td class="text-end action-buttons">
                  <a href="listeParticipation.php?id_event=<?= $event['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> Participations </a>
                  <a href="showEvent.php?id=<?= $event['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> detail </a>
                  <a href="updateEvent.php?id=<?= $event['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>Update </a>
                  <a href="deleteEvent.php?id=<?= $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');"><i class="fas fa-trash"></i> delete </a>
                </td>
              </tr>
            <?php
            }
            ?>
            </tbody>
          </table>
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
