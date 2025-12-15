<?php
session_start();
require_once __DIR__ . '/../../controller/UserController.php';

$userController = new userController();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = $userController->getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: ../backoffice/dashboard.php');
        } else {
            header('Location: profile.php');
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Login page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header-area header-sticky">
        <nav class="main-nav">
            <a href="../../index.php" class="logo">
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

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Login</h1>
            </div>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="email">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" name="email" placeholder="Email"  required>
                </div>

                <div class="form-group">
                    <label for="password">
                        Password <span class="required">*</span>
                    </label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="checkbox-label">Remember me</label>
                </div>

                <button type="submit" class="button">Login</button>

                <div class="forgot-password">
                    <a href="#">Lost your password?</a>
                </div>
            </form>
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

<script src="java.js"></script>

</body>
</html>