<?php
require_once "./models/Tutorial.php";
require_once "./models/Feedback.php";
require_once "./config/database.php";

$db = (new Database())->connect();
$tutorial = new Tutorial($db);
$feedback = new Feedback();

$countTutorials = $tutorial->countAll();
$countFeedbacks = $feedback->countAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Administration</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#121212; color:white; font-family:system-ui; }
.sidebar { position:fixed; left:0; top:0; width:250px; height:100vh; background:#1c1c1c; padding:20px; }
.sidebar a { display:block; padding:12px; color:#ddd; text-decoration:none; margin-bottom:10px; border-radius:6px; }
.sidebar a:hover { background:#ff0066; color:white; }
.main { margin-left:270px; padding:30px; }
.card-box { background:#1e1e1e; padding:25px; border-radius:12px; box-shadow:0 0 15px #000; }
.card-box h3 { font-size:45px; margin:0; color:#ff0066; }
.card-title { font-size:18px; color:#ccc; }
.header { font-size:35px; font-weight:700; margin-bottom:20px; }
.footer { text-align:center; margin-top:50px; color:#777; }
</style>

</head>
<body>

<div class="sidebar">
    <h2 style="color:#ff0066; font-weight:bold;">Admin Panel</h2>
    <a href="router.php?action=dashboard"><i class="fa fa-chart-line"></i> Dashboard</a>
    <a href="router.php?action=adminList"><i class="fa fa-video"></i> Tutoriels</a>
    <a href="router.php?action=adminFeedback"><i class="fa fa-comments"></i> Feedbacks</a>
</div>

<div class="main">

    <div class="header">Dashboard Admin</div>

    <div class="row">
        <div class="col-md-4">
            <div class="card-box">
                <div class="card-title">Tutoriels</div>
                <h3><?= $countTutorials ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-title">Feedbacks</div>
                <h3><?= $countFeedbacks ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-title">Administrateur</div>
                <h3>Connecté</h3>
            </div>
        </div>
    </div>

    <div class="footer">Administration © 2025</div>

</div>

</body>
</html>
