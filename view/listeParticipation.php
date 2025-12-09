<?php
// Include the necessary participationC.php file
require_once(__DIR__ . '/../controller/participationC.php');

// Create an instance of ParticipationC class
$participationC = new ParticipationC();

// Fetch the list of participations
$idEvent = isset($_GET['id_event']) ? (int)$_GET['id_event'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchActive = !empty($search);
$filterTitle = "";

if ($idEvent > 0) {
    $tab = $participationC->getParticipationsByEvent($idEvent);
    $filterTitle = " (Événement #$idEvent)";
} elseif ($searchActive) {
    $tab = $participationC->searchParticipations($search);
} else {
    $tab = $participationC->listParticipations();
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Participations · GameAct Admin</title>
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
      <a href="listeEvent.php" class="nav-link"><i class="fas fa-calendar-check"></i> Événements</a>
      <a href="listeParticipation.php" class="nav-link active"><i class="fas fa-users"></i> Participations</a>
      <a href="#" class="nav-link"><i class="fas fa-user"></i> Utilisateurs</a>
      <a href="#" class="nav-link"><i class="fas fa-comments"></i> Feed</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="app-main">
    <div class="container-fluid">
      <div class="d-flex justify-between align-center mb-4">
        <h1 class="h3">Participations<?= htmlspecialchars($filterTitle); ?><?= $searchActive ? ' - Résultats de recherche' : '' ?></h1>
        <?php if ($idEvent > 0): ?>
            <a href="listeEvent.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-1"></i> Retour aux événements
            </a>
        <?php endif; ?>
      </div>

      <!-- Search Bar -->
      <div class="card mb-3">
        <div class="card-body">
          <form method="GET" action="listeParticipation.php" class="row g-3">
            <?php if ($idEvent > 0): ?>
              <input type="hidden" name="id_event" value="<?= $idEvent ?>">
            <?php endif; ?>
            <div class="col-md-10">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, email, statut ou remarque..." value="<?= htmlspecialchars($search); ?>">
              </div>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">Rechercher</button>
              <?php if ($searchActive): ?>
                <a href="listeParticipation.php<?= $idEvent > 0 ? '?id_event=' . $idEvent : '' ?>" class="btn btn-outline-light w-100 mt-2">Réinitialiser</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <?php if ($searchActive && empty($tab)): ?>
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>Aucune participation trouvée pour "<strong><?= htmlspecialchars($search); ?></strong>"
        </div>
      <?php endif; ?>

      <div class="card">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Remarque</th>
                <th>ID Event</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tab as $participation) {
              ?>
              <tr>
                <td><?= htmlspecialchars($participation['idP']); ?></td>
                <td><?= htmlspecialchars($participation['nomP']); ?></td>
                <td><?= htmlspecialchars($participation['emailP']); ?></td>
                <td><span class="btn btn-info btn-sm"><?= htmlspecialchars($participation['statutP']); ?></span></td>
                <td><?= htmlspecialchars($participation['remarqueP']); ?></td>
                <td><?= htmlspecialchars($participation['id']); ?></td>
                <td class="text-end action-buttons">
                  <a href="showParticipation.php?idP=<?= $participation['idP']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> detail </a>
                  <a href="updateParticipation.php?idP=<?= $participation['idP']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>Update </a>
                  <a href="deleteParticipation.php?idP=<?= $participation['idP']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette participation ?');"><i class="fas fa-trash"></i> delete </a>
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
