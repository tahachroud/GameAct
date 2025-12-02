<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier tutoriel</title>

  <!-- CHEMINS CSS CORRECTS -->
  <link rel="stylesheet" href="../../public/assets/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../../public/assets/animate.css">
  <link rel="stylesheet" href="../../public/assets/owl.css">
  <link rel="stylesheet" href="../../public/assets/fontawesome.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="background:#1f2122;" class="p-4 text-light">

  <h2 class="mb-4">Modifier le tutoriel</h2>

  <form method="POST" onsubmit="return validate()">

    <label>Titre</label>
    <input type="text" name="title" id="title" class="form-control" value="<?= $item['title'] ?>">

    <label class="mt-3">URL vidéo (YouTube)</label>
    <input type="text" name="videoUrl" id="videoUrl" class="form-control" value="<?= $item['videoUrl'] ?>">

    <label class="mt-3">Catégorie</label>
    <input type="text" name="category" id="category" class="form-control" value="<?= $item['category'] ?>">

    <label class="mt-3">Description</label>
    <textarea name="content" id="content" class="form-control"><?= $item['content'] ?></textarea>

    <button class="btn btn-success mt-4">Sauvegarder</button>
    <a href="router.php?action=adminList" class="btn btn-secondary mt-4">Annuler</a>

  </form>


<!-- VALIDATION JS -->
<script>
function validate() {

    const title = document.getElementById("title");
    const videoUrl = document.getElementById("videoUrl");
    const category = document.getElementById("category");
    const content = document.getElementById("content");

    // Vérification titre
    if (title.value.trim().length < 3) {
        alert("Le titre doit contenir au moins 3 caractères.");
        title.focus();
        return false;
    }

    // Vérification URL vidéo
    if (videoUrl.value.trim() === "") {
        alert("Veuillez entrer une URL vidéo.");
        videoUrl.focus();
        return false;
    }

    if (
        !videoUrl.value.includes("youtube.com") &&
        !videoUrl.value.includes("youtu.be")
    ) {
        alert("L’URL doit être une vidéo YouTube.");
        videoUrl.focus();
        return false;
    }

    // Vérification catégorie
    if (category.value.trim() === "") {
        alert("Veuillez entrer une catégorie.");
        category.focus();
        return false;
    }

    // Vérification description
    if (content.value.trim().length < 10) {
        alert("La description doit contenir au moins 10 caractères.");
        content.focus();
        return false;
    }

    return true; // Si tout est bon → envoi du formulaire
}
</script>

</body>
</html>
