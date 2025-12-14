<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un tutoriel</title>

  
  <link rel="stylesheet" href="../../public/assets/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="../../public/assets/owl.css">
  <link rel="stylesheet" href="../../public/assets/animate.css">
  <link rel="stylesheet" href="../../public/assets/fontawesome.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="background:#1f2122;" class="text-light p-4">

<div class="container">
  <h2 class="mb-4">Ajouter un tutoriel</h2>

  <form method="POST" onsubmit="return validate()">

    <div class="mb-3">
      <label>Titre</label>
      <input type="text" id="title" name="title" class="form-control">
    </div>

    <div class="mb-3">
      <label>URL vidéo (YouTube)</label>
      <input type="text" id="videoUrl" name="videoUrl" class="form-control">
    </div>

    <div class="mb-3">
      <label>Catégorie</label>
      <input type="text" id="category" name="category" class="form-control">
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea id="content" name="content" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Ajouter</button>
  </form>
</div>



<script>
function validate() {

    const title = document.getElementById("title");
    const videoUrl = document.getElementById("videoUrl");
    const category = document.getElementById("category");
    const content = document.getElementById("content");

    
    if (title.value.trim().length < 3) {
        alert("Le titre doit contenir au moins 3 caractères.");
        title.focus();
        return false;
    }

    
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

    
    if (category.value.trim() === "") {
        alert("Veuillez entrer une catégorie.");
        category.focus();
        return false;
    }

    
    if (content.value.trim().length < 10) {
        alert("La description doit contenir au moins 10 caractères.");
        content.focus();
        return false;
    }

    return true; 
}
</script>

</body>
</html>
