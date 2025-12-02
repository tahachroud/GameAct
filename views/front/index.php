<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tutoriels Gaming</title>

  <!-- RÉPERTOIRES CORRIGÉS -->
  <link rel="stylesheet" href="public/assets/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>

    body {
        background:#0f0f13;
    }

    h1 {
        text-align:center;
        margin-top:40px;
        margin-bottom:40px;
        color:#ff1177;
        font-size:45px;
        font-weight:900;
        text-shadow:0 0 20px #ff1177;
    }

    .card-gaming {
        background:#1b1c22;
        border-radius:12px;
        padding:18px;
        transition:0.35s;
        border:1px solid #2a2b32;
        box-shadow:0 0 10px rgba(255,0,90,0.1);
    }

    .card-gaming:hover {
        transform:scale(1.03);
        border-color:#ff1177;
        box-shadow:0 0 18px #ff1177;
    }

    .thumb iframe {
        width:100%;
        height:200px;
        border-radius:10px;
    }

    .tuto-title {
        color:#ff4c4c;
        margin-top:10px;
        font-size:20px;
        font-weight:bold;
        text-transform:capitalize;
    }

    .tuto-desc {
        color:#6aff6a;
        margin-top:6px;
        font-size:15px;
    }

    .btn-gaming {
        width:100%;
        border:1px solid #ff1177;
        color:#ff1177;
        margin-top:10px;
        transition:0.3s;
    }

    .btn-gaming:hover {
        background:#ff1177;
        color:white;
        box-shadow:0 0 12px #ff1177;
    }

  </style>
</head>

<body>
<?php include "./views/front/header.php"; ?>   <!-- HEADER -->

<h1>Tutoriels Gaming – Explore & Apprends</h1>

<div class="container mt-4">
    <div class="row">

        <?php while($t = $data->fetch()): ?>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card-gaming">
                
                <div class="thumb">
                    <iframe src="<?= $t['videoUrl'] ?>" allowfullscreen></iframe>
                </div>

                <h4 class="tuto-title"><?= htmlspecialchars($t['title']) ?></h4>

                <p class="tuto-desc">
                    <?= htmlspecialchars(substr($t['content'], 0, 50)) ?>...
                </p>

                <a href="router.php?action=show&id=<?= $t['id'] ?>" 
                   class="btn btn-gaming">
                    Voir plus
                </a>

            </div>
        </div>

        <?php endwhile; ?>

    </div>
</div>

<?php include "./views/front/footer.php"; ?>   <!-- FOOTER -->

</body>
</html>
