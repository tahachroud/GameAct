<!doctype html> 
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct — Community</title>

  <!-- FRONTEND CSS -->
  <link rel="stylesheet" href="public/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="public/assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="public/assets/css/feed.css">
  <link rel="stylesheet" href="public/assets/css/fontawesome.css">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

  <!-- HEADER -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">

            <a href="#" class="logo">
              <img src="public/assets/images/logo.png" alt="GameAct">
            </a>

            
              


            <ul class="nav">
               <li><a href="index.php?action=search_form">Advanced Search</a></li>
              <li><a href="#">Home</a></li>
              <li><a href="#">Games</a></li>
              <li><a href="#">Events</a></li>
              <li><a href="#">Tutorials</a></li>
              <li><a href="#">Shop</a></li>
              <li class="active"><a href="index.php?action=community">Community</a></li>
              <li><a href="#">Profile <img src="public/assets/images/profile-header.jpg"></a></li>

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
      <p>Copyright © 2036 Cyborg Gaming Company. All rights reserved.</p>
    </div>
  </footer>

  <!-- JS -->
  <script src="public/vendor/jquery/jquery.min.js"></script>
  <script src="public/vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- ORDER FIXED -->
  <script src="public/assets/js/feed.js"></script>
  <script src="public/assets/js/like.js"></script>
  <script src="public/assets/js/share.js"></script>

  <!-- MUST BE LAST (validation attaches after DOM + feed build) -->
  <script src="public/assets/js/community_validation.js"></script>

</body>
</html>
