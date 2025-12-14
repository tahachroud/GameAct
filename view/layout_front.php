<!doctype html> 
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct — Community</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <?php 
  $base = dirname($_SERVER['PHP_SELF']);
  if ($base === '/' || $base === '\\') $base = '';
  $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/gameact_shop';
  ?>

  <!-- FRONTEND CSS -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/fontawesome.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/animate.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/moving-bg.css">
  
  <!-- Feed CSS should load LAST to override template styles -->
  <link rel="stylesheet" href="<?= $baseUrl ?>/public/assets/css/feed.css">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    .header-area .main-nav .logo img {
      max-height: 70px !important;
      width: auto !important;
      position: relative !important;
      top: 0 !important;
      transform: translateY(0) !important;
    }
    .header-area .main-nav {
      display: flex !important;
      align-items: center !important;
    }
  </style>

</head>

<body> 
  <div class="moving-bg"></div>


  <!-- HEADER -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">

            <a href="<?= $baseUrl ?>/index.php" class="logo">
              <img src="<?= $baseUrl ?>/view/front-office/assets/images/logo.png" alt="GameAct" style="max-height: 80px; width: auto; vertical-align: middle; margin-top: 0;">
            </a>

            
              


            <ul class="nav">
               <li><a href="<?= $baseUrl ?>/index-community.php?action=search_form" id="search-link">🔍 Advanced Search</a></li>
              <li><a href="<?= $baseUrl ?>/index.php">Home</a></li>
              <li><a href="<?= $baseUrl ?>/shop-home.php">Shop</a></li>
<li>
    <a href="<?= $baseUrl ?>/view/front-office/events/front/index.php">Events</a>
</li>


              <li><a href="#">Tutorials</a></li>
              <li class="active"><a href="<?= $baseUrl ?>/index-community.php?action=community">Community</a></li>
              <li><a href="#">Profile <img src="<?= $baseUrl ?>/public/assets/images/profile-header.jpg"></a></li>

            </ul>

            <a class="menu-trigger"><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <?= $content ?>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <p>Copyright © 2025 GameAct Company. All rights reserved.</p>
    </div>
  </footer>

  <!-- JS -->
  <script src="<?= $baseUrl ?>/public/vendor/jquery/jquery.min.js"></script>
  <script src="<?= $baseUrl ?>/public/vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Template JS -->
  <script src="<?= $baseUrl ?>/public/assets/js/isotope.min.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/custom.js"></script>

  <!-- ORDER FIXED -->
  <script src="<?= $baseUrl ?>/public/assets/js/feed.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/like.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/share.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/poll.js"></script>

  <!-- MUST BE LAST (validation attaches after DOM + feed build) -->
  <script src="<?= $baseUrl ?>/public/assets/js/community_validation.js"></script>
  <script src="<?= $baseUrl ?>/public/assets/js/tts.js"></script>

</body>
</html>
