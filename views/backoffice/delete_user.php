<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontoffice/login_client.php");
    exit;
}

require_once '../../controllers/userController.php';
$userController = new userController();

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

$currentUserId = $_SESSION['user_id'] ?? 0;
if ($id === $currentUserId) {
    $_SESSION['error'] = "You cannot delete yourself!";
    header("Location: dashboard.php");
    exit;
}

$user = $userController->getUserById($id);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->deleteUser($id);
    $_SESSION['success'] = "User '{$user['name']} {$user['lastname']}' has been deleted.";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - GameAct Admin</title>
    <link rel="stylesheet" href="../frontoffice/assets/css/style.css">
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
                <li><a href="dashboard.php">Back to Dashboard</a></li>
                <li><a href="index.html">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>

<div class="delete-container">
    <div class="delete-card">
        <h1>Delete User?</h1>
        <p style="color:#ff6b6b; font-size:18px;">This action <strong>cannot be undone</strong>.</p>

        <div class="user-info">
            <strong><?= htmlspecialchars($user['name'] . " " . $user['lastname']) ?></strong><br>
            <?= htmlspecialchars($user['email']) ?><br>
            Role: <strong style="color:#e75e8d;"><?= ucfirst($user['role']) ?></strong>
        </div>

        <form method="POST" style="display:inline;">
            <button type="submit" class="btn btn-delete">Yes, Delete Forever</button>
        </form>
        <a href="dashboard.php" class="btn btn-cancel">Cancel</a>
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