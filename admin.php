<?php
/**
 * Admin Panel Entry Point - admin.php
 * Back-Office for Gaming Quiz Platform
 * OPEN ACCESS - No authentication required (as per user request)
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once __DIR__ . '/config/db.php';

// Include controller
require_once __DIR__ . '/controller/QuizController.php';

// NO AUTHENTICATION BLOCKING - Everyone can access

// Get page and action from URL
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Initialize controller
$controller = new QuizController();

try {
    // Route to appropriate controller method (ADMIN ROUTES ONLY)
    switch($page) {
        // Admin Dashboard
        case 'dashboard':
            $controller->adminDashboard();
            break;
            
        // Manage Quiz
        case 'quiz_manage':
            $controller->adminManageQuiz();
            break;
            
        // Edit Quiz
        case 'quiz_edit':
            if ($id) {
                $controller->adminEditQuiz($id);
            } else {
                header('Location: admin.php?page=quiz_manage');
                exit;
            }
            break;
            
        // Update Quiz
        case 'quiz_update':
            $controller->adminUpdateQuiz();
            break;
            
        // Delete Quiz
        case 'quiz_delete':
            if ($id) {
                $controller->adminDeleteQuiz($id);
            } else {
                header('Location: admin.php?page=quiz_manage');
                exit;
            }
            break;
            
        // Update Quiz Status (AJAX)
        case 'update_quiz_status':
            $controller->updateQuizStatus();
            break;
            
        // Default: Redirect to dashboard
        default:
            header('Location: admin.php?page=dashboard');
            exit;
            break;
    }
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    
    // Display error in development (remove in production)
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='admin.php'>Back to Dashboard</a>";
}
?>