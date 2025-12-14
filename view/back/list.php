<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tutoriels</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body { background:#0f0f13; color:white; }
    h2 { text-shadow:0 0 10px #ff1177; }
    .table thead { background:#1b1c22; }
    .table tbody tr { background:#141419; transition:0.2s; }
    .table tbody tr:hover { background:#1f1f26; }
    .btn-edit { border-color:#4bb3ff; color:#4bb3ff; }
    .btn-edit:hover { background:#4bb3ff; color:black; }
    .btn-del { border-color:#ff1177; color:#ff1177; }
    .btn-del:hover { background:#ff1177; color:black; }
    .btn-add { background:#ff1177; border:none; color:white; }
    .btn-add:hover { background:#ff398f; }
  </style>
</head>

<body class="p-4">

<div class="container">

  <h2 class="mb-4">Gestion des Tutoriels</h2>

  <a href="router.php?action=add" class="btn btn-add mb-3">Ajouter un tutoriel</a>

  <table class="table table-dark table-striped align-middle">
      <thead>
          <tr>
              <th>ID</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
      <?php while($t = $data->fetch()): ?>
          <tr>
              <td><?= $t['id'] ?></td>
              <td><?= htmlspecialchars($t['title']) ?></td>
              <td><span class="badge bg-info"><?= htmlspecialchars($t['category']) ?></span></td>
              <td>
                  <a href="router.php?action=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-edit">Modifier</a>
                  <a href="router.php?action=delete&id=<?= $t['id'] ?>" class="btn btn-sm btn-del"
                     onclick="return confirm('Supprimer ce tutoriel ?')">Supprimer</a>
              </td>
          </tr>
      <?php endwhile; ?>
      </tbody>
  </table>

</div>

</body>
</html>
