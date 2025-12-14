<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>GameAct - Gaming Platform</title>

  <link href="view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="view/assets/css/fontawesome.css">
  <link rel="stylesheet" href="view/assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="view/assets/css/animate.css">

  <style>
    body {
      background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: 'Poppins', sans-serif;
    }

    /* Hero Section */
    .hero-section {
      text-align: center;
      padding: 80px 20px 60px;
      position: relative;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('view/assets/images/banner-bg.jpg') center/cover;
      opacity: 0.1;
      z-index: 0;
    }
    .hero-content {
      position: relative;
      z-index: 2;
    }
    .hero-logo {
      max-width: 200px;
      margin-bottom: 30px;
      filter: drop-shadow(0 10px 30px rgba(236, 96, 144, 0.5));
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }
    .hero-title {
      font-size: 4rem;
      font-weight: 900;
      color: #fff;
      margin-bottom: 15px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
      animation: fadeInDown 1s ease-out;
    }
    .hero-title span {
      color: #ec6090;
    }
    .hero-subtitle {
      font-size: 1.5rem;
      color: #aaa;
      margin-bottom: 50px;
      animation: fadeInUp 1s ease-out;
    }

    /* Modules Grid */
    .modules-section {
      flex: 1;
      padding: 40px 0;
    }
    .modules-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Module Card */
    .module-card {
      background: linear-gradient(135deg, #16213e 0%, #1a1a2e 100%);
      border-radius: 20px;
      padding: 40px 30px;
      text-align: center;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      position: relative;
      overflow: hidden;
      border: 2px solid transparent;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      text-decoration: none;
      display: block;
    }
    .module-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(236, 96, 144, 0.1) 0%, transparent 100%);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 0;
    }
    .module-card:hover::before {
      opacity: 1;
    }
    .module-card:hover {
      transform: translateY(-15px) scale(1.05);
      border-color: #ec6090;
      box-shadow: 0 20px 50px rgba(236, 96, 144, 0.4);
    }
    
    /* Module Card Content */
    .module-icon {
      font-size: 4rem;
      margin-bottom: 20px;
      transition: all 0.4s ease;
      position: relative;
      z-index: 1;
    }
    .module-card:hover .module-icon {
      transform: scale(1.2) rotateY(360deg);
    }
    .module-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 10px;
      position: relative;
      z-index: 1;
    }
    .module-desc {
      color: #aaa;
      font-size: 0.95rem;
      line-height: 1.6;
      position: relative;
      z-index: 1;
    }
    
    /* Module Specific Colors */
    .module-shop .module-icon { color: #ec6090; }
    .module-shop:hover { border-color: #ec6090; }

    .module-feed .module-icon { color: #17a2b8; }
    .module-feed:hover { border-color: #17a2b8; }

    .module-tutorials .module-icon { color: #ffc107; }
    .module-tutorials:hover { border-color: #ffc107; }

    .module-events .module-icon { color: #28a745; }
    .module-events:hover { border-color: #28a745; }

    .module-quizs .module-icon { color: #6f42c1; }
    .module-quizs:hover { border-color: #6f42c1; }

    .module-login .module-icon { color: #fd7e14; }
    .module-login:hover { border-color: #fd7e14; }

    /* Status Badge */
    .status-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      z-index: 2;
    }
    .status-active {
      background: #28a745;
      color: #fff;
    }
    .status-soon {
      background: #ffc107;
      color: #000;
    }

    /* Footer */
    footer {
      background: #0f0f1e;
      padding: 30px 0;
      text-align: center;
      color: #aaa;
      margin-top: auto;
    }
    footer a {
      color: #ec6090;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
    }

    /* Animations */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.5rem;
      }
      .hero-subtitle {
        font-size: 1.2rem;
      }
      .modules-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Hero Section -->
  <div class="hero-section">
    <div class="hero-content">
      <img src="view/assets/images/logo.png" alt="GameAct Logo" class="hero-logo">
      <h1 class="hero-title">Welcome to <span>GameAct</span></h1>
      <p class="hero-subtitle">Your Ultimate Gaming Platform - Choose Your Module</p>
    </div>
  </div>

  <!-- Modules Section -->
  <div class="modules-section">
    <div class="container">
      <div class="modules-grid">

        <!-- Module 1: SHOP -->
        <a href="shop-home.php" class="module-card module-shop">
          <span class="status-badge status-active">
            <i class="fa fa-check-circle"></i> Active
          </span>
          <div class="module-icon">
            <i class="fa fa-shopping-bag"></i>
          </div>
          <h3 class="module-title">Shop</h3>
          <p class="module-desc">Browse and purchase your favorite games from our extensive collection</p>
        </a>

        <!-- Module 2: FEED -->
        <a href="index-community.php?action=community" class="module-card module-feed">
          <span class="status-badge status-active">
            <i class="fa fa-check-circle"></i> Active
          </span>
          <div class="module-icon">
            <i class="fa fa-rss"></i>
          </div>
          <h3 class="module-title">Feed</h3>
          <p class="module-desc">Stay updated with the latest gaming news, updates, and community posts</p>
        </a>

        <!-- Module 3: TUTORIALS -->
        <a href="#" class="module-card module-tutorials" onclick="showComingSoon('Tutorials'); return false;">
          <span class="status-badge status-soon">
            <i class="fa fa-clock"></i> Coming Soon
          </span>
          <div class="module-icon">
            <i class="fa fa-graduation-cap"></i>
          </div>
          <h3 class="module-title">Tutorials</h3>
          <p class="module-desc">Learn gaming tips, tricks, and strategies from expert players</p>
        </a>

        <!-- Module 4: EVENTS -->
        <a href="view/front-office/events/front/index.php" class="module-card module-events">
          <span class="status-badge status-active">
            <i class="fa fa-check-circle"></i> Active
          </span>
          <div class="module-icon">
            <i class="fa fa-calendar"></i>
          </div>
          <h3 class="module-title">Events</h3>
          <p class="module-desc">Participate in gaming tournaments, competitions, and community events</p>
        </a>

        <!-- Module 5: QUIZS -->
        <a href="#" class="module-card module-quizs" onclick="showComingSoon('Quizs'); return false;">
          <span class="status-badge status-soon">
            <i class="fa fa-clock"></i> Coming Soon
          </span>
          <div class="module-icon">
            <i class="fa fa-question-circle"></i>
          </div>
          <h3 class="module-title">Quizs</h3>
          <p class="module-desc">Test your gaming knowledge with fun and challenging quizzes</p>
        </a>

        <!-- Module 6: LOGIN -->
        <a href="view/frontoffice/login_client.php" class="module-card module-login">
          <span class="status-badge status-active">
            <i class="fa fa-check-circle"></i> Active
          </span>
          <div class="module-icon">
            <i class="fa fa-user-circle"></i>
          </div>
          <h3 class="module-title">Login</h3>
          <p class="module-desc">Access your account, manage profile, and track your gaming journey</p>
        </a>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p style="margin: 0;">
        Copyright © 2025 <a href="#">GameAct</a> Company. All rights reserved.
        <br>
        <small>Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a> | Developed with ❤️</small>
      </p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="view/vendor/jquery/jquery.min.js"></script>
  <script src="view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // Fonction pour afficher "Coming Soon" pour les modules non disponibles
    function showComingSoon(moduleName) {
      alert('🚀 ' + moduleName + ' module is coming soon!\n\nStay tuned for updates!');
    }

    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.module-card');
      
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(30px)';
          
          setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 50);
        }, index * 100);
      });
    });
  </script>

</body>
</html>