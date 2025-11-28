<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GameAct BackOffice</title>

    <!-- FRONTEND CSS -->
    <link rel="stylesheet" href="public/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/assets/css/templatemo-cyborg-gaming.css">
    <link rel="stylesheet" href="public/assets/css/feed.css">
    <link rel="stylesheet" href="public/assets/css/fontawesome.css">

    <!-- ADMIN BACKEND CSS -->
    <link rel="stylesheet" href="public/assets/css/back.css">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            background: #1f2122 !important; /* match frontend */
            padding-top: 0 !important;
        }

        /* Sidebar top alignment fix since header is height 90px */
        .app-wrapper {
            padding-top: 120px !important;
        }

        .app-main {
            margin-left: 260px; /* keep your sidebar space */
        }

        aside.app-sidebar {
            position: fixed;
            top: 82px; /* under the frontend header */
            left: 0;
            width: 260px;
            height: calc(100% - 120px);
            overflow-y: auto;
            z-index: 999;
        }
    </style>
</head>

<body>

<!-- ========================== FRONTEND HEADER ========================== -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">

                    <!-- Logo -->
                    <a href="index.php?action=dashboard" class="logo">
                        <img src="public/assets/images/logo.png" alt="GameAct">
                    </a>

                    <!-- Search -->
                    <div class="search-input">
                        <form id="search" action="#">
                            <input type="text" placeholder="Search Something...">
                            <i class="fa fa-search"></i>
                        </form>
                    </div>

                    <!-- Navigation Menu -->
                    <ul class="nav">
                        <li><a href="index.php" target="_blank">Home</a></li>
                        <li><a href="#" target="_blank">Games</a></li>
                        <li><a href="#" target="_blank">Events</a></li>
                        <li><a href="#" target="_blank">Tutorials</a></li>
                        <li><a href="#" target="_blank">Shop</a></li>

                        <!-- Highlight Community since it's in dashboard -->
                        <li><a href="index.php?action=community" class="active">Community</a></li>

                        <!-- Admin profile -->
                        <li>
                            <a href="#">
                                Admin <img src="public/assets/images/profile-header.jpg">
                            </a>
                        </li>
                    </ul>

                    <a class="menu-trigger"><span>Menu</span></a>
                </nav>
            </div>
        </div>
    </div>
</header>


<!-- ========================== WRAPPER ========================== -->
<div class="app-wrapper">

    <!-- ========================== SIDEBAR (UNCHANGED) ========================== -->
    <?php include __DIR__ . "/partials/sidebar.php"; ?>


    <!-- ========================== MAIN CONTENT ========================== -->
    <main class="app-main">
        <div class="app-content">
            <div class="container-fluid py-3">

                <!-- Dynamic content here -->
                <?= $content ?>

            </div>
        </div>
    </main>


    <!-- ========================== FOOTER ========================== -->
    <footer class="app-footer text-center py-3" style="color:white;">
        <strong>&copy; 2025 GameAct.</strong> All rights reserved.
    </footer>

</div>


<!-- JS Scripts -->
<script src="public/vendor/jquery/jquery.min.js"></script>
<script src="public/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Frontend Scripts -->
<script src="public/assets/js/feed.js"></script>
<script src="public/assets/js/custom.js"></script>

</body>
</html>
