<?php
session_start();
require_once '../../controller/userController.php';
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';
require_once '../../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$userController = new userController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $user = $userController->getUserByEmail($email);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo = config::getConnexion();
        $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?")
            ->execute([$token, $expires, $email]);

        $reset_link = "http://localhost/projet/view/frontoffice/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tahachroud06@gmail.com';
            $mail->Password   = 'zacf nhgl nldq sgtu';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('no-reply@gameact.com', 'GameAct');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset - GameAct';
            $mail->Body    = "
                <h2>Reset Your Password</h2>
                <p>Click the button below to reset your password:</p>
                <br>
                <a href='$reset_link' style='background:#e75e8d; color:white; padding:15px 30px; text-decoration:none; border-radius:50px; font-weight:bold;'>
                    Reset Password
                </a>
                <br><br>
                <small>This link expires in 1 hour.</small>
            ";

            $mail->send();
            $message = "<p style='color:#4caf50; text-align:center;'>Check your email! We sent you a reset link.</p>";
        } catch (Exception $e) {
            $message = "<p style='color:red; text-align:center;'>Email failed. Try again later.</p>";
        }
    } else {
        $message = "<p style='color:#4caf50; text-align:center;'>If your email exists, a reset link was sent.</p>";
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
                <li><a href="../../index.php" class="active">Home</a></li>
                <li><a href="../front-office/events/front/index.php">Evénements</a></li>
                <li><a href="../../shop-home.php">Boutique</a></li>
                <li><a href="../../index-community.php?action=community">Communauté</a></li>
                <li><a href="../../module-card module-tutorials">Tutoriels</a></li>
                <li><a href="../../quiz_list.php">Quiz</a></li>
                <li><a href="profile.php">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>

<div class="login-container" style="margin-top:150px;">
    <div class="login-card">
        <h1 style="color:#e75e8d; text-align:center;">Forgot Password?</h1>
        <p style="text-align:center; color:#aaa;">Enter your email to reset password</p>

        <?php if($message) echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="your@email.com" required style="text-align:center;">
            </div>
            <button type="submit" class="button" style="width:100%; margin-top:20px;">
                Send Reset Link
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <a href="login_client.php" style="color:#ec6090;">Back to Login</a>
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