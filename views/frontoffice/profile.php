<?php
session_start();

require_once __DIR__ . '/../../controllers/userController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_client.php');
    exit;
}

$userController = new UserController();
$userId = $_SESSION['user_id'];

$user = $userController->getUserById($userId);
if (!$user) {
    session_destroy();
    header('Location: login_client.php');
    exit;
}

function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Profile page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css">
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
                <li><a href="index.html" >Home</a></li>
                <li><a href="browse.html">Evénements</a></li>
                <li><a href="details.html">Boutique</a></li>
                <li><a href="streams.html">Communauté</a></li>
                <li><a href="streams.html">Tutoriels</a></li>
                <li><a href="streams.html">Leaderboard</a></li>
                <li><a href="profile.php" class="active">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>
    
    <div class="big-card">

        <div class="right-card">
            <img class="avatar" src="assets/images/profile-header.jpg" alt="">
            <div class="name-section">
                <h4><?= e($user['name'] . ' ' . $user['lastname']) ?></h4>
                <p class="title">Your role</p>
            </div>
            <div class="quote-section">
                <svg class="quote-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                    <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/>
                </svg>
                <p>Short Description</p>
            </div>
            <div class="middle-card">
                <h4>About Me</h4>
                <ul class="about-list">
                    <li><strong>Full Name:</strong>
                        <span class="editable">
                            <?= e($user['name'] . ' ' . $user['lastname']) ?>
                        </span>
                    </li>
                    <li><strong>Email:</strong>
                        <span class="editable"><?= e($user['email']) ?></span>
                    </li>
                    <li><strong>Gender:</strong>
                        <span class="editable"><?= e($user['gender']) ?></span>
                    </li>
                    <li><strong>Address:</strong>
                        <span class="editable">
                            <?php
                            echo isset($user['location']) ? e($user['location']) : 'Not set yet';
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="left-card">
            <div class="card">
                <h4>Bio</h4>
                <p class="editable">
                    Welcome back, <?= e($user['name']) ?>! This is your bio area.
                </p>
            </div>
            <div class="card">
                <h4>Genre Interests</h4>
                <ul class="genre-list">
                    <li class="button">Sci-Fi</li>
                    <li class="button">Horror</li>
                    <li class="button">Action</li>
                    <li class="button">Drama</li>
                </ul>
            </div>            
            <div class="card">
                <h4>Recent Activity</h4>
                <ul>
                    <li>Logged in as <?= e($user['email']) ?></li>
                    <li>Role: <?= e($user['role']) ?></li>
                </ul>
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
  
<script src="java.js"></script>

</body>
</html>