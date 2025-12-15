<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * Front-Office Entry Point - quiz_list.php
 * Gaming Quiz Platform - User Interface
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
// Disable display_errors for AJAX requests to prevent corrupting JSON responses
$isAjaxRequest = isset($_GET['page']) && in_array($_GET['page'], ['get_quizzes_by_category', 'get_user_stats']);
ini_set('display_errors', $isAjaxRequest ? 0 : 1);

// Include database configuration
require_once __DIR__ . '/config.php';

// Include controller
require_once __DIR__ . '/controller/QuizController.php';

// Get page and action from URL
$page = $_GET['page'] ?? 'quiz_list';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Initialize controller
$controller = new QuizController();

try {
    // Route to appropriate controller method (FRONT-OFFICE ROUTES ONLY)
    switch($page) {
        // ========================================
        // FRONT OFFICE ROUTES
        // ========================================
        
        // Quiz List
        case 'quiz_list':
            $controller->listQuiz();
            break;
            
        // Create Quiz
        case 'quiz_create':
            $controller->createQuiz();
            break;
            
        // Play Quiz
        case 'quiz_play':
            if ($id) {
                $controller->playQuiz($id);
            } else {
                header('Location: quiz_list.php?page=quiz_list');
                exit;
            }
            break;
            
        // Submit Quiz Results
        case 'quiz_submit':
        case 'submit_quiz_results':
            $controller->submitQuizResults();
            break;
            
        // AJAX: Get Quizzes by Category
        case 'get_quizzes_by_category':
            $controller->getQuizzesByCategory();
            break;
            
        // AJAX: Get User Stats
        case 'get_user_stats':
            $controller->getUserStats();
            break;
            
        // Certificate Generation
        case 'generate_certificate':
            require_once __DIR__ . '/controller/generate_certificate.php';
            $certController = new CertificateController();
            $certController->generate();
            break;
            
        // ========================================
        // USER QUIZ MANAGEMENT
        // ========================================
        
        // My Quizzes (User's Own)
        case 'user_my_quizzes':
            $controller->userMyQuizzes();
            break;
            
        // Edit User's Own Quiz
        case 'user_edit_quiz':
            if ($id) {
                $controller->userEditQuiz($id);
            } else {
                header('Location: quiz_list.php?page=user_my_quizzes');
                exit;
            }
            break;
            
        // Update User's Own Quiz
        case 'user_update_quiz':
            $controller->userUpdateQuiz();
            break;
            
        // Delete User's Own Quiz
        case 'user_delete_quiz':
            if ($id) {
                $controller->userDeleteQuiz($id);
            } else {
                header('Location: quiz_list.php?page=user_my_quizzes');
                exit;
            }
            break;
            
        // ========================================
        // DEFAULT
        // ========================================
        
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
    echo "<a href='quiz_list.php'>Back to Home</a>";
}
?>