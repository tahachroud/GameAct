<?php
/**
 * Routeur principal - index.php
 * Point d'entrée de l'application Gaming Quiz
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once __DIR__ . '/config/db.php';

// Include controller
require_once __DIR__ . '/controllers/QuizController.php';

// Get page and action from URL
$page = $_GET['page'] ?? 'quiz_list';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Initialize controller
$controller = new QuizController();

try {
    // Route to appropriate controller method
    switch($page) {
        // Front Office Routes
        case 'quiz_list':
            $controller->listQuiz();
            break;
            
        case 'quiz_create':
            $controller->createQuiz();
            break;
            
        case 'quiz_play':
            if ($id) {
                $controller->playQuiz($id);
            } else {
                header('Location: index.php?page=quiz_list');
                exit;
            }
            break;
            
        case 'quiz_submit':
            $controller->submitQuizResults();
            break;
            
        case 'get_user_stats':
            $controller->getUserStats();
            break;
            
        // Admin Routes
        case 'admin_dashboard':
            $controller->adminDashboard();
            break;
            
        case 'admin_quiz_manage':
            $controller->adminManageQuiz();
            break;
            
        case 'admin_quiz_edit':
            if ($id) {
                $controller->adminEditQuiz($id);
            } else {
                header('Location: index.php?page=admin_quiz_manage');
                exit;
            }
            break;
            
        case 'admin_quiz_update':
            $controller->adminUpdateQuiz();
            break;
            
        case 'admin_quiz_delete':
            if ($id) {
                $controller->adminDeleteQuiz($id);
            } else {
                header('Location: index.php?page=admin_quiz_manage');
                exit;
            }
            break;
            
        // User Quiz Management Routes
        case 'user_my_quizzes':
            $controller->userMyQuizzes();
            break;
            
        case 'user_edit_quiz':
            if ($id) {
                $controller->userEditQuiz($id);
            } else {
                header('Location: index.php?page=user_my_quizzes');
                exit;
            }
            break;
            
        case 'user_update_quiz':
            $controller->userUpdateQuiz();
            break;
            
        case 'user_delete_quiz':
            if ($id) {
                $controller->userDeleteQuiz($id);
            } else {
                header('Location: index.php?page=user_my_quizzes');
                exit;
            }
            break;
            
        // Default: Redirect to quiz list
        default:
            $controller->listQuiz();
            break;
    }
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    
    // Display error in development (remove in production)
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='index.php'>Back to Home</a>";
}
?>