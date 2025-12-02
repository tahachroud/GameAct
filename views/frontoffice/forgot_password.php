<?php
session_start();
require_once '../../controllers/userController.php';

$userController = new userController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $user = $userController->getUserByEmail($email);

    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(25));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save to user row
        $pdo = config::getConnexion();
        $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?")
            ->execute([$token, $expires, $email]);

        // FULL RESET LINK
        $link = "http://localhost/projet/views/frontoffice/reset_password.php?token=" . $token;

        // TEST MODE — SHOW LINK DIRECTLY (no email, no error)
        $message = "
        <div style='background:#2a2c2d; padding:25px; border-radius:16px; margin:25px 0; text-align:center; border:2px solid #e75e8d;'>
            <h3 style='color:#e75e8d; margin:0 0 15px;'>Reset Link Ready!</h3>
            <p style='color:#ccc; margin:10px 0;'>Click the button below or copy the link</p>
            
            <a href='$link' style='background:linear-gradient(135deg,#e75e8d,#f54f89); color:white; padding:16px 36px; text-decoration:none; border-radius:50px; font-weight:bold; display:inline-block; margin:15px 0; box-shadow:0 8px 20px rgba(231,94,141,0.4);'>
                Reset My Password Now
            </a>
            
            <p style='font-size:13px; color:#999; word-break:break-all; background:#1e1e1e; padding:12px; border-radius:8px; margin:15px 0;'>
                $link
            </p>
            <small style='color:#666;'>Valid for 1 hour • For testing only</small>
        </div>";
    } else {
        $message = "<p style='color:#e75e8d; text-align:center; padding:15px; background:#2a2c2d; border-radius:12px;'>
            If that email exists in our system, a reset link has been sent.
        </p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - GameAct</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header-area header-sticky">
        <nav class="main-nav">
            <a href="index.html" class="logo">
                <img src="assets/images/logo.png" alt="">
            </a>
            <div class="search-input">
                <form id="search" action="#">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Rechercher" id='searchText' name="searchKeyword" onkeypress="handle" />
                </form>
            </div>
            <ul class="nav">
                <li><a href="index.html" class="active">Home</a></li>
                <li><a href="browse.html">Evénements</a></li>
                <li><a href="details.html">Boutique</a></li>
                <li><a href="streams.html">Communauté</a></li>
                <li><a href="streams.html">Tutoriels</a></li>
                <li><a href="streams.html">Leaderboard</a></li>
                <li><a href="index.html">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>

<div class="login-container" style="margin-top: 120px;">
    <div class="login-card">
        <h1 style="color:#e75e8d; text-align:center; margin-bottom:8px;">Forgot Password?</h1>
        <p style="text-align:center; color:#aaa; margin-bottom:30px;">Enter your email below</p>

        <?php if($message) echo $message; ?>

        <?php if (!$message || strpos($message, 'exists') !== false): ?>
        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="your@email.com" required style="text-align:center;">
            </div>
            <button type="submit" class="button" style="width:100%; margin-top:20px;">
                Send Reset Link
            </button>
        </form>
        <?php endif; ?>

        <div style="text-align:center; margin-top:30px;">
            <a href="login_client.php" style="color:#ec6090; font-size:15px;">
                Back to Login
            </a>
        </div>
    </div>
</div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2036 <a href="#">GameAct</a> Company. All rights reserved. 
          
          <br>Design: <a href="#">Taha Chroud</a>  Distributed By <a href="#">APEX   </a></p>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>