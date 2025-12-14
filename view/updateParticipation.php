<?php
// Vérifier si l'ID est fourni
if (!isset($_GET['idP']) || empty($_GET['idP'])) {
    header('Location: listeParticipation.php');
    exit;
}

// Inclure les fichiers nécessaires
require_once(__DIR__ . '/../controller/participationC.php');
require_once(__DIR__ . '/../model/participation.php');

$participationC = new ParticipationC();

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idP'])) {
    // Récupérer les champs
    $nomP = isset($_POST["nomP"]) ? trim($_POST["nomP"]) : "";
    $emailP = isset($_POST["emailP"]) ? trim($_POST["emailP"]) : "";
    $statutP = isset($_POST["statutP"]) ? $_POST["statutP"] : "";
    
    // Recombine remarqueP from separate fields
    $telephone = isset($_POST["telephone"]) ? trim($_POST["telephone"]) : "";
    $discord = isset($_POST["discord"]) ? trim($_POST["discord"]) : "";
    $age = isset($_POST["age"]) ? trim($_POST["age"]) : "";
    $niveau = isset($_POST["niveau"]) ? trim($_POST["niveau"]) : "";
    
    $remarqueP = "Tel: $telephone, Discord: $discord, Age: $age, Niveau: $niveau";

    $idEvent = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $idP = isset($_POST['idP']) ? intval($_POST['idP']) : 0;
    
    if ($idP <= 0) {
        ?>
        <script>
            alert("Erreur : ID participation invalide !");
            window.location.href = 'listeParticipation.php';
        </script>
        <?php
        exit;
    }
    
    // Vérifier que tous les champs requis sont remplis
    if (!empty($nomP) && !empty($emailP) && !empty($statutP) && $idEvent > 0) {
        // Création de l'objet Participation
        $participation = new Participation(
            $idP,
            $nomP,
            $emailP,
            $statutP,
            $remarqueP,
            $idEvent
        );

        // Mettre à jour la participation
        try {
            $result = $participationC->updateParticipation($participation, $idP);
            if ($result !== false) {
                ?>
                <script>
                    alert("Participation modifiée avec succès !");
                    window.location.href = 'listeParticipation.php';
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
    }
}

// Récupérer la participation à modifier
if (!isset($_GET['idP']) || empty($_GET['idP'])) {
    header('Location: listeParticipation.php');
    exit;
}

$participation = $participationC->showParticipation($_GET['idP']);

if (!$participation) {
    ?>
    <script>
        alert("Participation non trouvée !");
        window.location.href = 'listeParticipation.php';
    </script>
    <?php
    exit;
}

// Parse remarqueP to extract fields
$remarqueP = $participation['remarqueP'];
$telephone = '';
$discord = '';
$age = '';
$niveau = '';

// Try to parse "Tel: ..., Discord: ..., Age: ..., Niveau: ..."
if (preg_match('/Tel:\s*(.*?),/', $remarqueP, $matches)) $telephone = trim($matches[1]);
if (preg_match('/Discord:\s*(.*?),/', $remarqueP, $matches)) $discord = trim($matches[1]);
if (preg_match('/Age:\s*(.*?),/', $remarqueP, $matches)) $age = trim($matches[1]);
if (preg_match('/Niveau:\s*(.*)$/', $remarqueP, $matches)) $niveau = trim($matches[1]);

// Fallback if regex fails (e.g. if format is slightly different or empty) but try to be robust
// If simple parsing fails, we might just show empty or raw text, but here we assume standard format from addParticipation
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier Participation · GameAct Admin</title>
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
        <h1 class="h3">Modifier la participation</h1>
        <a href="listeParticipation.php" class="btn btn-outline-light">
          <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
      </div>

      <div class="card">
        <form method="POST" action="updateParticipation.php?idP=<?= $participation['idP']; ?>">
          <input type="hidden" name="idP" value="<?= $participation['idP']; ?>">
          
          <div class="mb-3">
            <label class="form-label">Nom Participant</label>
            <input type="text" class="form-control" name="nomP" value="<?= htmlspecialchars($participation['nomP']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="emailP" value="<?= htmlspecialchars($participation['emailP']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Statut</label>
            <select class="form-select" name="statutP" required>
              <option value="En attente" <?= $participation['statutP'] == 'En attente' ? 'selected' : ''; ?>>En attente</option>
              <option value="Confirmé" <?= $participation['statutP'] == 'Confirmé' ? 'selected' : ''; ?>>Confirmé</option>
              <option value="Refusé" <?= $participation['statutP'] == 'Refusé' ? 'selected' : ''; ?>>Refusé</option>
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($telephone); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Discord ID</label>
                <input type="text" class="form-control" name="discord" value="<?= htmlspecialchars($discord); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Âge</label>
                <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($age); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Niveau</label>
                <select class="form-select" name="niveau">
                    <option value="Débutant" <?= $niveau == 'Débutant' ? 'selected' : ''; ?>>Débutant</option>
                    <option value="Intermédiaire" <?= $niveau == 'Intermédiaire' ? 'selected' : ''; ?>>Intermédiaire</option>
                    <option value="Pro" <?= $niveau == 'Pro' ? 'selected' : ''; ?>>Pro</option>
                </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">ID Événement</label>
            <input type="number" class="form-control" name="id" value="<?= htmlspecialchars($participation['id']); ?>" required>
          </div>

          <div class="action-buttons">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Enregistrer
            </button>
            <a href="listeParticipation.php" class="btn btn-warning">
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
