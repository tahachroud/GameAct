<?php
// Define base URL for assets - IMPORTANT!
$base_url = '/GameAct/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title><?php echo $pageTitle ?? 'Gaming Quiz'; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    
    <!-- Fix for sticky header -->
    <style>
        /* Force header to stay fixed at top */
        header.header-area,
        .header-area.header-sticky {
            position: fixed !important;
            top: 0;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            height: 150px;
            z-index: 999999 !important;
            background: #1f2122 !important;
            transform: translateY(0) !important;
            transition: none !important;
        }
        
        /* Prevent any JavaScript from hiding it */
        header.header-area.hide,
        .header-area.header-sticky.hide {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        body{
  padding-top: 60px; /* same value */
}
        
        .page-content {
            position: relative;
            z-index: 1;
        }
        .main-nav {
            display: flex;
            align-items: center; /* aligns ALL children on same horizontal line */
        }

        .main-nav .nav {
            display: flex;
            align-items: center;
            gap: 20px;              /* spacing between items */
            flex-wrap: nowrap;      /* 🔴 prevents going under */
        }

        .main-nav .nav li {
            display: flex;
            align-items: center;
            white-space: nowrap;    /* prevents text wrapping */
        }

        .main-nav .nav li a {
            display: flex;
            align-items: center;
            gap: 8px;               /* space between text & image */
        }

        .search-input {
            display: flex;
            align-items: center;
            height: 42px;              /* match nav links height */
        }

        .search-input form {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .search-input input {
            height: 100%;
            line-height: 42px;
            padding: 0 40px 0 15px;    /* keep space for icon */
        }
        /* Fix profile avatar escaping the pill */
.main-nav .nav li:last-child a{
  display: inline-flex;
  align-items: center;
  gap: 10px;
  position: relative;      /* anchor for any absolute children */
  padding-right: 14px;     /* space so the image stays inside */
  overflow: hidden;        /* keeps everything inside the pill */
}

/* Force the image to behave like a normal inline element */
.main-nav .nav li:last-child a img{
  position: static !important;  /* cancels absolute positioning from template */
  margin: 0 !important;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  object-fit: cover;
  flex: 0 0 auto;
}


    </style>
    
    <!-- JavaScript to force header to stay visible -->
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header-area');
            if (header) {
                // Force header to stay at top
                header.style.position = 'fixed';
                header.style.top = '0';
                header.style.left = '0';
                header.style.right = '0';
                header.style.width = '100%';
                header.style.zIndex = '999999';
                header.style.background = '#1f2122';
                
                // Prevent template JavaScript from hiding it
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (header.style.display === 'none' || 
                            header.style.visibility === 'hidden' ||
                            header.style.transform !== 'translateY(0px)') {
                            header.style.display = 'block';
                            header.style.visibility = 'visible';
                            header.style.transform = 'translateY(0px)';
                        }
                    });
                });
                
                observer.observe(header, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }
        });
    </script>
    
    <?php if(isset($customCSS)): ?>
        <style><?php echo $customCSS; ?></style>
    <?php endif; ?>
</head>

<body>


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo.png?v=<?php echo time(); ?>" alt="">
                        </a>
                        <div class="search-input">
                            <form id="search" action="quiz_list.php" method="GET">
                                <input type="hidden" name="page" value="quiz_list">
                                <input type="text" placeholder="Search Quiz..." id='searchText' name="search" value="<?php echo $_GET['search'] ?? ''; ?>" />
                                <i class="fa fa-search"></i>
                            </form>
                        </div>
                        <ul class="nav">
                            <li><a href="quiz_list.php?page=quiz_list" class="<?php echo ($page ?? '') == 'quiz_list' ? 'active' : ''; ?>">Quiz</a></li>
                            <li><a href="quiz_list.php?page=quiz_create" class="<?php echo ($page ?? '') == 'quiz_create' ? 'active' : ''; ?>">Create Quiz</a></li>
                            <li><a href="quiz_list.php?page=user_my_quizzes" class="<?php echo ($page ?? '') == 'user_my_quizzes' ? 'active' : ''; ?>">My Quizzes</a></li>
                            <li><a href="view/frontoffice/profile.php">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
                        </ul>   
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->