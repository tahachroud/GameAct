<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/DashboardController.php';

// AUTOLOAD
spl_autoload_register(function ($class) {
    foreach (['controllers/', 'models/'] as $path) {
        $file = __DIR__ . '/' . $path . $class . '.php';
        if (file_exists($file)) require_once $file;
    }
});

// CONNECT DB
$db = (new Database())->getConnection();

// ACTION
$action = $_GET['action'] ?? 'dashboard';

// ROUTING
switch ($action) {

    /* ------------------ DASHBOARD ------------------ */
    case 'dashboard':
        (new DashboardController($db))->index();
        break;


    /* ------------------ POSTS CRUD ------------------ */
    case 'posts':
        (new PostController($db))->index();
        break;

    case 'posts_create':
        (new PostController($db))->createForm();
        break;

    case 'posts_store': // BACKOFFICE post create
        (new PostController($db))->create();
        break;

    case 'posts_edit':
        (new PostController($db))->editForm();
        break;

    case 'posts_update':
        (new PostController($db))->update();
        break;

    case 'posts_delete':
        (new PostController($db))->delete();
        break;

    /* 🚀 FRONT OFFICE POST */
    case 'posts_store_front':
        (new PostController($db))->createFromFront();
        break;


    /* ------------------ COMMENTS CRUD ------------------ */
    case 'comments':
        (new CommentController($db))->index();
        break;

    case 'comments_create':
        (new CommentController($db))->createForm();
        break;

    case 'comments_store': // BACKOFFICE comment create
        (new CommentController($db))->store();
        break;

    case 'comments_edit':
        (new CommentController($db))->editForm();
        break;

    case 'comments_update':
        (new CommentController($db))->update();
        break;

    case 'comments_delete':
        (new CommentController($db))->delete();
        break;

    /* 🚀 FRONT OFFICE COMMENT */
    case 'comments_store_front':
        (new CommentController($db))->createFromFront();
        break;


    /* ------------------ USERS CRUD ------------------ */
    case 'users':
        (new UserController($db))->index();
        break;

    case 'users_create':
        (new UserController($db))->createForm();
        break;

    case 'users_store':
        (new UserController($db))->create();
        break;

    case 'users_edit':
        (new UserController($db))->editForm();
        break;

    case 'users_update':
        (new UserController($db))->update();
        break;

    case 'users_delete':
        (new UserController($db))->delete();
        break;


    /* ------------------ COMMUNITY FRONTEND ------------------ */
    case 'community':
        (new CommunityController($db))->index();
        break;
    /* ------------------ AJAX COMMENT ROUTE ------------------ */
    case 'comments_store_ajax':
        (new CommentController($db))->createFromAjax();
        exit();
    case 'likes_update_ajax':
        (new CommunityController($db))->updateLikesAjax();
        exit;
        
    case 'share_update_ajax':
        (new CommunityController($db))->updateShareAjax();
        exit;
    case 'share_post':
        (new CommunityController($db))->sharePost();
        exit();
    case 'search':
        require_once 'controllers/PostController.php';
        $controller = new PostController($db);
        $controller->search();
        break;
    case 'search_form':
        ob_start();
        include 'views/posts/search_form.php';
        $content = ob_get_clean();
        include 'views/layout_front.php';
        break;










    /* ------------------ 404 ------------------ */
    default:
        echo "<h1>404 - Page not found</h1>";
}
