<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/DashboardController.php';


spl_autoload_register(function ($class) {
    foreach (['controllers/', 'models/'] as $path) {
        $file = __DIR__ . '/' . $path . $class . '.php';
        if (file_exists($file)) require_once $file;
    }
});

$db = (new Database())->getConnection();

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        (new DashboardController($db))->index();
        break;


    case 'posts':
        (new PostController($db))->index();
        break;

    case 'posts_create':
        (new PostController($db))->createForm();
        break;

    case 'posts_store': 
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

    case 'posts_store_front':
        (new PostController($db))->createFromFront();
        break;


    case 'comments':
        (new CommentController($db))->index();
        break;

    case 'comments_create':
        (new CommentController($db))->createForm();
        break;

    case 'comments_store': 
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

    case 'comments_store_front':
        (new CommentController($db))->createFromFront();
        break;


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


    case 'community':
        (new CommunityController($db))->index();
        break;
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
    case 'tts_read':
        require_once __DIR__ . '/services/TTSService.php';
        $controller = new PostController($db);
        $controller->readPostAudio();
        break;











    default:
        echo "<h1>404 - Page not found</h1>";
}
