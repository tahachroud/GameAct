<?php
/**
 * Contrôleur Quiz - MVC Pattern
 * controllers/QuizController.php
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/QuizQuestion.php';

class QuizController {
    
    private $quizModel;
    private $questionModel;
    private $quizQuestionModel;

    public function __construct() {
        $this->quizModel = new Quiz();
        $this->questionModel = new Question();
        $this->quizQuestionModel = new QuizQuestion();
    }

    /**
     * Afficher la liste des quiz (Front Office)
     */
    public function listQuiz() {
        $categoryFilter = isset($_GET['category']) ? $_GET['category'] : null;
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

        if ($searchTerm) {
            $quizzes = $this->quizModel->search($searchTerm);
        } else {
            $quizzes = $this->quizModel->readAll($categoryFilter);
        }

        // Get user stats (hardcoded to user 1 for now)
        $stats = $this->getUserStatsData(1);

        require __DIR__ . '/../views/quiz/list.php';
    }

    /**
     * Créer un quiz (Front Office)
     */
    public function createQuiz() {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect questions from POST data
            $questions = [];
            $questionCount = 0;
            
            // Count questions
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'question-text-') === 0) {
                    $questionCount++;
                }
            }
            
            // Build questions array
            for ($i = 1; $i <= $questionCount; $i++) {
                if (isset($_POST["question-text-$i"])) {
                    $questions[] = [
                        'texte_question' => $_POST["question-text-$i"],
                        'option_a' => $_POST["option-a-$i"],
                        'option_b' => $_POST["option-b-$i"],
                        'option_c' => $_POST["option-c-$i"],
                        'option_d' => $_POST["option-d-$i"],
                        'reponse_correcte' => $_POST["correct-answer-$i"],
                        'explication' => $_POST["explanation-$i"] ?? '',
                        'points' => 100
                    ];
                }
            }
            
            // Build quiz data
            // Handle image upload
            $image_url = 'popular-01.jpg'; // Default image
            if (isset($_FILES['quiz-image']) && $_FILES['quiz-image']['error'] === UPLOAD_ERR_OK) {
                $uploadedImage = $this->handleImageUpload($_FILES['quiz-image']);
                if ($uploadedImage) {
                    $image_url = $uploadedImage;
                }
            }
            
            $quizData = [
                'titre' => $_POST['quiz-title'] ?? '',
                'description' => $_POST['quiz-description'] ?? '',
                'categorie' => $_POST['quiz-category'] ?? '',
                'difficulte' => $_POST['quiz-difficulty'] ?? 'medium',
                'image_url' => $image_url,
                'id_createur' => $_SESSION['user_id'] ?? 1, // Default to 1 for testing
                'questions' => $questions
            ];
            
            // Store quiz
            $result = $this->store($quizData);
            
            if ($result['success']) {
                header('Location: index.php?page=quiz_list&success=1');
                exit;
            } else {
                $errors = $result['errors'] ?? [$result['message']];
            }
        }

        require __DIR__ . '/../views/quiz/create.php';
    }

    /**
     * Jouer un quiz (Front Office)
     */
    public function playQuiz($id) {
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();

        if (!$quiz) {
            header('Location: index.php?page=quiz_list');
            exit;
        }

        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);

        require __DIR__ . '/../views/quiz/play.php';
    }

    /**
     * Soumettre les résultats d'un quiz
     */
    public function submitQuizResults() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quiz = $_POST['id_quiz'];
            $id_user = $_SESSION['user_id'] ?? 1; // Default to 1 for testing
            
            // Get questions
            $questions = $this->quizQuestionModel->getQuestionsByQuiz($id_quiz);
            
            $score_total = 0;
            $reponses_correctes = 0;
            $total_questions = count($questions);

            // Calculate score
            foreach ($questions as $question) {
                $id_question = $question['id_question'];
                $reponse_user = strtoupper($_POST["answer_$id_question"] ?? '');
                
                if ($reponse_user === strtoupper($question['reponse_correcte'])) {
                    $reponses_correctes++;
                    $score_total += $question['points'];
                }
            }

            $pourcentage = ($reponses_correctes / $total_questions) * 100;
            $temps_ecoule = $_POST['temps_ecoule'] ?? 0;

            // Save results to database
            try {
                $db = new Database();
                $conn = $db->getConnection();

                // Insert quiz result
                $query = "INSERT INTO quiz_result 
                          (id_quiz, id_user, score_total, reponses_correctes, temps_ecoule, pourcentage) 
                          VALUES (:id_quiz, :id_user, :score_total, :reponses_correctes, :temps_ecoule, :pourcentage)";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_quiz', $id_quiz);
                $stmt->bindParam(':id_user', $id_user);
                $stmt->bindParam(':score_total', $score_total);
                $stmt->bindParam(':reponses_correctes', $reponses_correctes);
                $stmt->bindParam(':temps_ecoule', $temps_ecoule);
                $stmt->bindParam(':pourcentage', $pourcentage);
                
                if ($stmt->execute()) {
                    // DIRECT UPDATE - Increment quiz completion count
                    $updateQuery = "UPDATE quiz SET nombre_completions = nombre_completions + 1 WHERE id_quiz = :id_quiz";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bindParam(':id_quiz', $id_quiz);
                    $updateStmt->execute();
                    
                    // Return success (form submitted to iframe, so this won't show to user)
                    echo "SUCCESS";
                    exit;
                } else {
                    throw new Exception("Failed to save results");
                }

            } catch (Exception $e) {
                error_log("Erreur submitResults: " . $e->getMessage());
                echo "ERROR";
                exit;
            }
        }
    }

    /**
     * Dashboard Admin
     */
    public function adminDashboard() {
        $allQuizzes = $this->quizModel->readAll();
        
        $totalQuiz = count($allQuizzes);
        $totalQuestions = 0;
        $totalCompletions = 0;
        
        foreach ($allQuizzes as $quiz) {
            $totalQuestions += $quiz['nombre_questions'];
            $totalCompletions += $quiz['nombre_completions'];
        }
        
        $stats = $this->getUserStatsData(1);
        $avgScore = $stats['pourcentage_moyen'] ?? 0;
        
        $recentQuizzes = array_slice($allQuizzes, 0, 5);
        
        // Category distribution
        $categoryCount = [];
        foreach ($allQuizzes as $quiz) {
            $cat = $quiz['categorie'];
            $categoryCount[$cat] = ($categoryCount[$cat] ?? 0) + 1;
        }

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Gérer les quiz (Admin)
     */
    public function adminManageQuiz() {
        $quizzes = $this->quizModel->readAll();
        require __DIR__ . '/../views/admin/quiz_manage.php';
    }

    /**
     * Modifier un quiz (Admin)
     */
    public function adminEditQuiz($id) {
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();

        if (!$quiz) {
            header('Location: index.php?page=admin_quiz_manage');
            exit;
        }
        
        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);

        require __DIR__ . '/../views/admin/quiz_edit.php';
    }

    /**
     * Mettre à jour un quiz (Admin)
     */
    public function adminUpdateQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quiz = $_POST['quiz-id'];
            
            $this->quizModel->id_quiz = $id_quiz;
            $this->quizModel->titre = $_POST['quiz-title'];
            $this->quizModel->description = $_POST['quiz-description'];
            $this->quizModel->categorie = $_POST['quiz-category'];
            $this->quizModel->difficulte = $_POST['quiz-difficulty'];
            $this->quizModel->statut = $_POST['quiz-status'];
            
            // Get existing quiz data for image
            $existingQuiz = $this->quizModel->readOne();
            
            // Handle image upload if provided
            if (isset($_FILES['quiz-image']) && $_FILES['quiz-image']['error'] === UPLOAD_ERR_OK) {
                $uploadedImage = $this->handleImageUpload($_FILES['quiz-image']);
                if ($uploadedImage) {
                    $this->quizModel->image_url = $uploadedImage;
                } else {
                    $this->quizModel->image_url = $existingQuiz['image_url'];
                }
            } else {
                $this->quizModel->image_url = $existingQuiz['image_url'];
            }
            
            // Handle questions
            $questions = [];
            $questionCount = 0;
            
            // Count questions
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'question-text-') === 0) {
                    $questionCount++;
                }
            }
            
            // Validate question count
            if ($questionCount < 8 || $questionCount > 12) {
                header('Location: index.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=question_count');
                exit;
            }
            
            // Build questions array
            $existingQuestionIds = [];
            for ($i = 1; $i <= $questionCount; $i++) {
                if (isset($_POST["question-text-$i"])) {
                    $questionData = [
                        'id_question' => $_POST["question-id-$i"] ?? null,
                        'texte_question' => $_POST["question-text-$i"],
                        'option_a' => $_POST["option-a-$i"],
                        'option_b' => $_POST["option-b-$i"],
                        'option_c' => $_POST["option-c-$i"],
                        'option_d' => $_POST["option-d-$i"],
                        'reponse_correcte' => $_POST["correct-answer-$i"],
                        'explication' => $_POST["explanation-$i"] ?? '',
                        'points' => 100
                    ];
                    $questions[] = $questionData;
                    if ($questionData['id_question']) {
                        $existingQuestionIds[] = $questionData['id_question'];
                    }
                }
            }
            
            // Update quiz nombre_questions
            $this->quizModel->nombre_questions = $questionCount;
            
            try {
                // Update quiz
                if (!$this->quizModel->update()) {
                    header('Location: index.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=update_failed');
                    exit;
                }
                
                // Get current questions for this quiz
                $currentQuestions = $this->quizQuestionModel->getQuestionsByQuiz($id_quiz);
                $currentQuestionIds = array_column($currentQuestions, 'id_question');
                
                // Delete questions that were removed
                foreach ($currentQuestionIds as $currentId) {
                    if (!in_array($currentId, $existingQuestionIds)) {
                        // Check if question is used in other quizzes
                        $usageCount = $this->quizQuestionModel->getQuizCountForQuestion($currentId);
                        if ($usageCount <= 1) {
                            // Only used in this quiz, safe to delete
                            $this->questionModel->id_question = $currentId;
                            $this->questionModel->delete();
                        }
                    }
                }
                
                // Remove all question associations for this quiz
                $this->quizQuestionModel->removeAllQuestions($id_quiz);
                
                // Update or create questions
                $newQuestionIds = [];
                foreach ($questions as $questionData) {
                    if ($questionData['id_question']) {
                        // Update existing question
                        $this->questionModel->id_question = $questionData['id_question'];
                        $this->questionModel->texte_question = $questionData['texte_question'];
                        $this->questionModel->option_a = $questionData['option_a'];
                        $this->questionModel->option_b = $questionData['option_b'];
                        $this->questionModel->option_c = $questionData['option_c'];
                        $this->questionModel->option_d = $questionData['option_d'];
                        $this->questionModel->reponse_correcte = strtoupper($questionData['reponse_correcte']);
                        $this->questionModel->explication = $questionData['explication'];
                        $this->questionModel->points = $questionData['points'];
                        $this->questionModel->update();
                        
                        $newQuestionIds[] = $questionData['id_question'];
                    } else {
                        // Create new question
                        $this->questionModel->texte_question = $questionData['texte_question'];
                        $this->questionModel->option_a = $questionData['option_a'];
                        $this->questionModel->option_b = $questionData['option_b'];
                        $this->questionModel->option_c = $questionData['option_c'];
                        $this->questionModel->option_d = $questionData['option_d'];
                        $this->questionModel->reponse_correcte = strtoupper($questionData['reponse_correcte']);
                        $this->questionModel->explication = $questionData['explication'];
                        $this->questionModel->points = $questionData['points'];
                        
                        $question_id = $this->questionModel->create();
                        if ($question_id) {
                            $newQuestionIds[] = $question_id;
                        }
                    }
                }
                
                // Re-associate questions with quiz
                $this->quizQuestionModel->associateQuestions($id_quiz, $newQuestionIds);
                
                header('Location: index.php?page=admin_quiz_manage&updated=1');
                exit;
                
            } catch (Exception $e) {
                error_log("Error updating quiz: " . $e->getMessage());
                header('Location: index.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=exception');
                exit;
            }
        }
    }

    /**
     * Supprimer un quiz (Admin)
     */
    public function adminDeleteQuiz($id) {
        $this->quizModel->id_quiz = $id;
        $this->quizModel->delete();
        
        header('Location: index.php?page=admin_quiz_manage&deleted=1');
        exit;
    }

    /**
     * USER QUIZ MANAGEMENT (NEW FEATURE)
     */
    
    /**
     * Display user's own quizzes
     */
    public function userMyQuizzes() {
        $id_user = $_SESSION['user_id'] ?? 1;
        
        // Get only user's quizzes
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT q.*, u.username as createur
                  FROM quiz q
                  LEFT JOIN users u ON q.id_createur = u.id_user
                  WHERE q.id_createur = :id_user
                  ORDER BY q.date_creation DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $myQuizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../views/user/my_quizzes.php';
    }
    
    /**
     * Edit user's own quiz
     */
    public function userEditQuiz($id) {
        $id_user = $_SESSION['user_id'] ?? 1;
        
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();
        
        // Check if user owns this quiz
        if (!$quiz || $quiz['id_createur'] != $id_user) {
            header('Location: index.php?page=user_my_quizzes&error=not_owner');
            exit;
        }
        
        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);
        
        require __DIR__ . '/../views/user/edit_quiz.php';
    }
    
    /**
     * Update user's own quiz
     */
    public function userUpdateQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quiz = $_POST['quiz-id'];
            $id_user = $_SESSION['user_id'] ?? 1;
            
            $this->quizModel->id_quiz = $id_quiz;
            $quiz = $this->quizModel->readOne();
            
            // Check ownership
            if (!$quiz || $quiz['id_createur'] != $id_user) {
                header('Location: index.php?page=user_my_quizzes&error=not_owner');
                exit;
            }
            
            // Update quiz metadata
            $this->quizModel->titre = $_POST['quiz-title'];
            $this->quizModel->description = $_POST['quiz-description'];
            $this->quizModel->categorie = $_POST['quiz-category'];
            $this->quizModel->difficulte = $_POST['quiz-difficulty'];
            $this->quizModel->statut = $quiz['statut']; // Keep existing status
            
            // Handle image upload if provided
            if (isset($_FILES['quiz-image']) && $_FILES['quiz-image']['error'] === UPLOAD_ERR_OK) {
                $uploadedImage = $this->handleImageUpload($_FILES['quiz-image']);
                if ($uploadedImage) {
                    $this->quizModel->image_url = $uploadedImage;
                } else {
                    $this->quizModel->image_url = $quiz['image_url']; // Keep existing image
                }
            } else {
                $this->quizModel->image_url = $quiz['image_url']; // Keep existing image
            }
            
            // Handle questions
            $questions = [];
            $questionCount = 0;
            
            // Count questions
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'question-text-') === 0) {
                    $questionCount++;
                }
            }
            
            // Validate question count
            if ($questionCount < 8 || $questionCount > 12) {
                header('Location: index.php?page=user_edit_quiz&id=' . $id_quiz . '&error=question_count');
                exit;
            }
            
            // Build questions array
            $existingQuestionIds = [];
            for ($i = 1; $i <= $questionCount; $i++) {
                if (isset($_POST["question-text-$i"])) {
                    $questionData = [
                        'id_question' => $_POST["question-id-$i"] ?? null,
                        'texte_question' => $_POST["question-text-$i"],
                        'option_a' => $_POST["option-a-$i"],
                        'option_b' => $_POST["option-b-$i"],
                        'option_c' => $_POST["option-c-$i"],
                        'option_d' => $_POST["option-d-$i"],
                        'reponse_correcte' => $_POST["correct-answer-$i"],
                        'explication' => $_POST["explanation-$i"] ?? '',
                        'points' => 100
                    ];
                    $questions[] = $questionData;
                    if ($questionData['id_question']) {
                        $existingQuestionIds[] = $questionData['id_question'];
                    }
                }
            }
            
            // Update quiz nombre_questions
            $this->quizModel->nombre_questions = $questionCount;
            
            try {
                // Update quiz
                if (!$this->quizModel->update()) {
                    header('Location: index.php?page=user_edit_quiz&id=' . $id_quiz . '&error=update_failed');
                    exit;
                }
                
                // Get current questions for this quiz
                $currentQuestions = $this->quizQuestionModel->getQuestionsByQuiz($id_quiz);
                $currentQuestionIds = array_column($currentQuestions, 'id_question');
                
                // Delete questions that were removed
                foreach ($currentQuestionIds as $currentId) {
                    if (!in_array($currentId, $existingQuestionIds)) {
                        // Check if question is used in other quizzes
                        $usageCount = $this->quizQuestionModel->getQuizCountForQuestion($currentId);
                        if ($usageCount <= 1) {
                            // Only used in this quiz, safe to delete
                            $this->questionModel->id_question = $currentId;
                            $this->questionModel->delete();
                        }
                    }
                }
                
                // Remove all question associations for this quiz
                $this->quizQuestionModel->removeAllQuestions($id_quiz);
                
                // Update or create questions
                $newQuestionIds = [];
                foreach ($questions as $questionData) {
                    if ($questionData['id_question']) {
                        // Update existing question
                        $this->questionModel->id_question = $questionData['id_question'];
                        $this->questionModel->texte_question = $questionData['texte_question'];
                        $this->questionModel->option_a = $questionData['option_a'];
                        $this->questionModel->option_b = $questionData['option_b'];
                        $this->questionModel->option_c = $questionData['option_c'];
                        $this->questionModel->option_d = $questionData['option_d'];
                        $this->questionModel->reponse_correcte = strtoupper($questionData['reponse_correcte']);
                        $this->questionModel->explication = $questionData['explication'];
                        $this->questionModel->points = $questionData['points'];
                        $this->questionModel->update();
                        
                        $newQuestionIds[] = $questionData['id_question'];
                    } else {
                        // Create new question
                        $this->questionModel->texte_question = $questionData['texte_question'];
                        $this->questionModel->option_a = $questionData['option_a'];
                        $this->questionModel->option_b = $questionData['option_b'];
                        $this->questionModel->option_c = $questionData['option_c'];
                        $this->questionModel->option_d = $questionData['option_d'];
                        $this->questionModel->reponse_correcte = strtoupper($questionData['reponse_correcte']);
                        $this->questionModel->explication = $questionData['explication'];
                        $this->questionModel->points = $questionData['points'];
                        
                        $question_id = $this->questionModel->create();
                        if ($question_id) {
                            $newQuestionIds[] = $question_id;
                        }
                    }
                }
                
                // Re-associate questions with quiz
                $this->quizQuestionModel->associateQuestions($id_quiz, $newQuestionIds);
                
                header('Location: index.php?page=user_my_quizzes&updated=1');
                exit;
                
            } catch (Exception $e) {
                error_log("Error updating quiz: " . $e->getMessage());
                header('Location: index.php?page=user_edit_quiz&id=' . $id_quiz . '&error=exception');
                exit;
            }
        }
    }
    
    /**
     * Delete user's own quiz
     */
    public function userDeleteQuiz($id) {
        $id_user = $_SESSION['user_id'] ?? 1;
        
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();
        
        // Check ownership
        if ($quiz && $quiz['id_createur'] == $id_user) {
            $this->quizModel->delete();
            header('Location: index.php?page=user_my_quizzes&deleted=1');
        } else {
            header('Location: index.php?page=user_my_quizzes&error=not_owner');
        }
        exit;
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Check file type
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'quiz_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        
        // Upload directory
        $uploadDir = __DIR__ . '/../../assets/images/quiz/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return 'quiz/' . $filename;
        }
        
        return false;
    }

    /**
     * CRUD Methods (Private Helper Functions)
     */
    private function store($data) {
        $this->quizModel->titre = $data['titre'];
        $this->quizModel->description = $data['description'];
        $this->quizModel->categorie = $data['categorie'];
        $this->quizModel->image_url = $data['image_url'];
        $this->quizModel->id_createur = $data['id_createur'];
        $this->quizModel->difficulte = $data['difficulte'];
        $this->quizModel->nombre_questions = count($data['questions']);
        $this->quizModel->statut = 'active';

        // Validate quiz data
        $errors = $this->quizModel->validate();
        if (!empty($errors)) {
            return ['success' => false, 'message' => 'Validation error', 'errors' => $errors];
        }

        // Validate question count
        if ($this->quizModel->nombre_questions < 8 || $this->quizModel->nombre_questions > 12) {
            return ['success' => false, 'message' => 'Le quiz doit contenir entre 8 et 12 questions'];
        }

        try {
            // Create quiz
            $quiz_id = $this->quizModel->create();
            
            if (!$quiz_id) {
                return ['success' => false, 'message' => 'Erreur lors de la création du quiz'];
            }

            $questions_ids = [];

            // Create questions
            foreach ($data['questions'] as $question_data) {
                $this->questionModel->texte_question = $question_data['texte_question'];
                $this->questionModel->option_a = $question_data['option_a'];
                $this->questionModel->option_b = $question_data['option_b'];
                $this->questionModel->option_c = $question_data['option_c'];
                $this->questionModel->option_d = $question_data['option_d'];
                $this->questionModel->reponse_correcte = strtoupper($question_data['reponse_correcte']);
                $this->questionModel->explication = $question_data['explication'];
                $this->questionModel->points = $question_data['points'];

                $question_id = $this->questionModel->create();
                if ($question_id) {
                    $questions_ids[] = $question_id;
                }
            }

            // Associate questions with quiz
            $this->quizQuestionModel->associateQuestions($quiz_id, $questions_ids);

            return ['success' => true, 'message' => 'Quiz créé avec succès', 'data' => ['id_quiz' => $quiz_id]];

        } catch (Exception $e) {
            error_log("Erreur store quiz: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la création du quiz'];
        }
    }

    /**
     * Get user statistics
     */
    /**
     * Get user statistics as JSON (for AJAX updates)
     */
    public function getUserStats() {
        $id_user = $_SESSION['user_id'] ?? 1;
        $stats = $this->getUserStatsData($id_user);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    private function getUserStatsData($id_user) {
        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Get quiz completions count and average score
            $query1 = "SELECT COUNT(DISTINCT id_quiz) as quiz_completes,
                             AVG(pourcentage) as pourcentage_moyen,
                             MAX(pourcentage) as meilleur_score
                      FROM quiz_result
                      WHERE id_user = :id_user";
            
            $stmt1 = $conn->prepare($query1);
            $stmt1->bindParam(':id_user', $id_user);
            $stmt1->execute();
            $resultData = $stmt1->fetch(PDO::FETCH_ASSOC);
            
            // Get quiz created count
            $query2 = "SELECT COUNT(*) as quiz_crees
                      FROM quiz
                      WHERE id_createur = :id_user AND statut = 'active'";
            
            $stmt2 = $conn->prepare($query2);
            $stmt2->bindParam(':id_user', $id_user);
            $stmt2->execute();
            $createdData = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            return [
                'quiz_completes' => $resultData['quiz_completes'] ?? 0,
                'meilleur_score' => $resultData['meilleur_score'] ?? 0,
                'quiz_crees' => $createdData['quiz_crees'] ?? 0,
                'pourcentage_moyen' => $resultData['pourcentage_moyen'] ?? 0
            ];

        } catch (Exception $e) {
            error_log("Erreur getUserStats: " . $e->getMessage());
            return ['quiz_completes' => 0, 'meilleur_score' => 0, 'quiz_crees' => 0, 'pourcentage_moyen' => 0];
        }
    }
}
?>