<?php
/**
 * Contrôleur Quiz - MVC Pattern
 * controller/QuizController.php
 * UPDATED: Compatible with friend's authentication (uses $_SESSION['user_id'])
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/Quiz.php';
require_once __DIR__ . '/../model/Question.php';
require_once __DIR__ . '/../model/QuizQuestion.php';

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

        // Get user stats (use session user_id)
        $id_user = $_SESSION['user_id'] ?? 1;
        $stats = $this->getUserStatsData($id_user);

        require __DIR__ . '/../view/quiz/list.php';
    }

    /**
     * Get quizzes by category via AJAX
     */
    public function getQuizzesByCategory() {
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $category = isset($_GET['category']) ? htmlspecialchars(strip_tags($_GET['category'])) : null;
            
            error_log("Getting quizzes for category: " . ($category ?? 'all'));
            
            // Get quizzes filtered by category
            $quizzes = $this->quizModel->readAll($category);
            
            if (empty($quizzes)) {
                echo json_encode([
                    'success' => true,
                    'html' => '<div class="col-12"><p style="text-align: center; color: #666; padding: 50px;">No quiz found in this category.</p></div>',
                    'count' => 0
                ]);
                exit;
            }
            
            // Build HTML for quiz cards
            $html = '';
            foreach ($quizzes as $quiz) {
                $html .= '
                    <div class="col-lg-3 col-sm-6">
                        <div class="quiz-card" onclick="window.location.href=\'quiz_list.php?page=quiz_play&id=' . $quiz['id_quiz'] . '\'">
                            <img src="assets/images/' . htmlspecialchars($quiz['image_url']) . '" 
                                 alt="' . htmlspecialchars($quiz['titre']) . '" 
                                 onerror="this.src=\'assets/images/popular-01.jpg\'">
                            <div class="quiz-info">
                                <span class="quiz-category">' . strtoupper(htmlspecialchars($quiz['categorie'])) . '</span>
                                <h4>' . htmlspecialchars($quiz['titre']) . '</h4>
                                <p class="quiz-creator">
                                    <i class="fa fa-user"></i> Created by: ' . htmlspecialchars($quiz['createur'] ?? 'Anonymous') . '
                                </p>
                                <div class="quiz-details">
                                    <div class="quiz-detail-item">
                                        <span>Questions:</span>
                                        <span class="number">' . $quiz['nombre_questions'] . '</span>
                                    </div>
                                    <div class="quiz-detail-item">
                                        <span>Plays:</span>
                                        <span class="number">' . $quiz['nombre_completions'] . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
            
            echo json_encode([
                'success' => true,
                'html' => $html,
                'count' => count($quizzes)
            ]);
            
        } catch (Exception $e) {
            error_log("Error getting quizzes by category: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        
        exit;
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
                'id_createur' => $_SESSION['user_id'] ?? 1, // FIXED: Use 'user_id' from friend's auth
                'questions' => $questions
            ];
            
            // Store quiz
            $result = $this->store($quizData);
            
            if ($result['success']) {
                header('Location: quiz_list.php?page=quiz_list&success=1');
                exit;
            } else {
                $errors = $result['errors'] ?? [$result['message']];
            }
        }

        require __DIR__ . '/../view/quiz/create.php';
    }

    /**
     * Jouer un quiz (Front Office)
     */
    public function playQuiz($id) {
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();

        if (!$quiz) {
            header('Location: quiz_list.php?page=quiz_list');
            exit;
        }

        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);

        require __DIR__ . '/../view/quiz/play.php';
    }

    /**
     * Soumettre les résultats d'un quiz
     */
    public function submitQuizResults() {
        error_log("=== submitQuizResults called ===");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . json_encode($_POST));
        error_log("SESSION: " . json_encode($_SESSION));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id_quiz = isset($_POST['id_quiz']) ? intval($_POST['id_quiz']) : null;
                // Use id_user from POST first (sent from JavaScript), then fall back to session
                $id_user = isset($_POST['id_user']) ? intval($_POST['id_user']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1);
                
                error_log("Parsed - Quiz ID: $id_quiz, User ID: $id_user");
                
                // Validate quiz ID
                if (!$id_quiz) {
                    error_log("submitQuizResults ERROR: Missing id_quiz");
                    echo "ERROR: Missing quiz ID";
                    exit;
                }
                
                error_log("submitQuizResults: Processing quiz $id_quiz for user $id_user");
                
                // Get questions
                $questions = $this->quizQuestionModel->getQuestionsByQuiz($id_quiz);
                error_log("Found " . count($questions) . " questions for quiz $id_quiz");
                
                if (empty($questions)) {
                    error_log("submitQuizResults ERROR: No questions found for quiz $id_quiz");
                    echo "ERROR: No questions found";
                    exit;
                }
                
                $score_total = 0;
                $reponses_correctes = 0;
                $total_questions = count($questions);

                // Calculate score
                foreach ($questions as $question) {
                    $id_question = $question['id_question'];
                    $reponse_user = strtoupper($_POST["answer_$id_question"] ?? '');
                    $reponse_correcte = strtoupper($question['reponse_correcte']);
                    
                    error_log("Question $id_question: User='$reponse_user' vs Correct='$reponse_correcte'");
                    
                    if ($reponse_user === $reponse_correcte) {
                        $reponses_correctes++;
                        $score_total += $question['points'];
                    }
                }

                $pourcentage = ($reponses_correctes / $total_questions) * 100;
                $temps_ecoule = isset($_POST['temps_ecoule']) ? intval($_POST['temps_ecoule']) : 0;
                
                error_log("Score calculated - Correct: $reponses_correctes/$total_questions, Score: $score_total, Percentage: $pourcentage%, Time: $temps_ecoule");

                // Save results to database
                $conn = config::getConnexion();
                error_log("Database connection established");
                
                // Insert quiz result
                $query = "INSERT INTO quiz_result 
                          (id_quiz, id_user, score_total, reponses_correctes, temps_ecoule, pourcentage, date_completion) 
                          VALUES (:id_quiz, :id_user, :score_total, :reponses_correctes, :temps_ecoule, :pourcentage, NOW())";

                error_log("Executing query: $query");
                
                $stmt = $conn->prepare($query);
                error_log("Statement prepared");
                
                $stmt->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
                $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmt->bindParam(':score_total', $score_total, PDO::PARAM_INT);
                $stmt->bindParam(':reponses_correctes', $reponses_correctes, PDO::PARAM_INT);
                $stmt->bindParam(':temps_ecoule', $temps_ecoule, PDO::PARAM_INT);
                $stmt->bindParam(':pourcentage', $pourcentage);
                error_log("Parameters bound");
                
                if ($stmt->execute()) {
                    error_log("✓ Quiz result saved successfully for user $id_user, quiz $id_quiz");
                    
                    // Return success
                    echo "SUCCESS";
                    exit;
                } else {
                    error_log("submitQuizResults ERROR: Failed to execute INSERT statement");
                    error_log("SQL Error: " . implode(" | ", $stmt->errorInfo()));
                    echo "ERROR: Database insert failed";
                    exit;
                }

            } catch (Exception $e) {
                error_log("submitQuizResults EXCEPTION: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                echo "ERROR: " . $e->getMessage();
                exit;
            }
        }
    }

    /**
     * Dashboard Admin
     */
    public function adminDashboard() {
        $allQuizzes = $this->quizModel->readAll(null, true);
        
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

        require __DIR__ . '/../view/admin/dashboard_quiz.php';
    }

    /**
     * Gérer les quiz (Admin)
     */
    public function adminManageQuiz() {
        $quizzes = $this->quizModel->readAll(null, true);
        require __DIR__ . '/../view/admin/quiz_manage.php';
    }

    /**
     * Modifier un quiz (Admin)
     */
    public function adminEditQuiz($id) {
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();

        if (!$quiz) {
            header('Location: quiz_list.php?page=admin_quiz_manage');
            exit;
        }
        
        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);

        require __DIR__ . '/../view/admin/quiz_edit.php';
    }

    /**
     * Mettre à jour un quiz (Admin)
     */
    public function adminUpdateQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quiz = $_POST['quiz-id'];
            error_log("=== ADMIN UPDATE QUIZ START ===");
            error_log("Quiz ID: $id_quiz");
            
            $this->quizModel->id_quiz = $id_quiz;
            $this->quizModel->titre = $_POST['quiz-title'] ?? '';
            $this->quizModel->description = $_POST['quiz-description'] ?? '';
            $this->quizModel->categorie = $_POST['quiz-category'] ?? '';
            $this->quizModel->difficulte = $_POST['quiz-difficulty'] ?? 'medium';
            $this->quizModel->statut = $_POST['quiz-status'] ?? 'active';
            
            error_log("Titre: " . $this->quizModel->titre);
            error_log("Description: " . $this->quizModel->description);
            error_log("Categorie: " . $this->quizModel->categorie);
            error_log("Difficulte: " . $this->quizModel->difficulte);
            error_log("Statut: " . $this->quizModel->statut);
            
            // Get existing quiz data for image
            $existingQuiz = $this->quizModel->readOne();
            
            if (!$existingQuiz) {
                error_log("ERROR: Quiz not found with ID: $id_quiz");
                header('Location: quiz_list.php?page=admin_quiz_manage&error=quiz_not_found');
                exit;
            }
            
            // Handle image upload if provided
            error_log("FILES array: " . json_encode($_FILES));
            if (isset($_FILES['quiz-image'])) {
                error_log("quiz-image file info: " . json_encode($_FILES['quiz-image']));
            }
            
            if (isset($_FILES['quiz-image']) && $_FILES['quiz-image']['error'] === UPLOAD_ERR_OK) {
                error_log("File upload successful, processing image...");
                $uploadedImage = $this->handleImageUpload($_FILES['quiz-image']);
                if ($uploadedImage) {
                    $this->quizModel->image_url = $uploadedImage;
                    error_log("Image uploaded successfully: $uploadedImage");
                } else {
                    $this->quizModel->image_url = $existingQuiz['image_url'];
                    error_log("Image upload failed, using existing: " . $existingQuiz['image_url']);
                }
            } else {
                if (isset($_FILES['quiz-image'])) {
                    error_log("File upload error code: " . $_FILES['quiz-image']['error']);
                } else {
                    error_log("No file uploaded in quiz-image field");
                }
                $this->quizModel->image_url = $existingQuiz['image_url'];
                error_log("No image uploaded, using existing: " . $existingQuiz['image_url']);
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
            
            error_log("Question count: $questionCount");
            
            // Validate question count
            if ($questionCount < 8 || $questionCount > 12) {
                error_log("ERROR: Invalid question count: $questionCount");
                header('Location: quiz_list.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=question_count');
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
            
            error_log("About to update quiz with ID: $id_quiz, Titre: " . $this->quizModel->titre);
            
            try {
                // Update quiz
                $updateResult = $this->quizModel->update();
                error_log("Update result: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
                
                if (!$updateResult) {
                    error_log("ERROR: Failed to update quiz");
                    header('Location: quiz_list.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=update_failed');
                    exit;
                }
                
                error_log("Quiz updated successfully. Now processing questions...");
                
                // Get current questions for this quiz
                $currentQuestions = $this->quizQuestionModel->getQuestionsByQuiz($id_quiz);
                $currentQuestionIds = array_column($currentQuestions, 'id_question');
                
                error_log("Current question IDs: " . json_encode($currentQuestionIds));
                error_log("New question IDs: " . json_encode($existingQuestionIds));
                
                // Delete questions that were removed
                foreach ($currentQuestionIds as $currentId) {
                    if (!in_array($currentId, $existingQuestionIds)) {
                        // Check if question is used in other quizzes
                        $usageCount = $this->quizQuestionModel->getQuizCountForQuestion($currentId);
                        if ($usageCount <= 1) {
                            // Only used in this quiz, safe to delete
                            $this->questionModel->id_question = $currentId;
                            $this->questionModel->delete();
                            error_log("Deleted question: $currentId");
                        }
                    }
                }
                
                // Remove all question associations for this quiz
                $this->quizQuestionModel->removeAllQuestions($id_quiz);
                error_log("Removed all question associations");
                
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
                        error_log("Updated question: " . $questionData['id_question']);
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
                            error_log("Created new question: $question_id");
                        }
                    }
                }
                
                // Re-associate questions with quiz
                $this->quizQuestionModel->associateQuestions($id_quiz, $newQuestionIds);
                error_log("Associated " . count($newQuestionIds) . " questions with quiz");
                error_log("=== ADMIN UPDATE QUIZ END - SUCCESS ===");
                
                header('Location: quiz_list.php?page=admin_quiz_manage&updated=1');
                exit;
                
            } catch (Exception $e) {
                error_log("=== ADMIN UPDATE QUIZ END - ERROR ===");
                error_log("Error updating quiz: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                header('Location: quiz_list.php?page=admin_quiz_edit&id=' . $id_quiz . '&error=exception');
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
        
        header('Location: quiz_list.php?page=admin_quiz_manage&deleted=1');
        exit;
    }

    /**
     * Update quiz status via AJAX (Admin)
     */
    public function updateQuizStatus() {
        // Clear any output buffers
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Set JSON header
        header('Content-Type: application/json; charset=utf-8');
        
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        try {
            // Get quiz ID and new status
            $quizId = isset($_POST['id_quiz']) ? intval($_POST['id_quiz']) : null;
            $newStatus = isset($_POST['status']) ? htmlspecialchars(strip_tags($_POST['status'])) : null;
            
            error_log("Update Status Called - ID: $quizId, Status: $newStatus");
            
            // Validate inputs
            if (!$quizId || !$newStatus) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
                exit;
            }
            
            // Validate status value
            if (!in_array($newStatus, ['active', 'pending', 'deleted'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid status value']);
                exit;
            }
            
            // Get quiz and update status
            $this->quizModel->id_quiz = $quizId;
            $quiz = $this->quizModel->readOne();
            
            if (!$quiz) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Quiz not found']);
                exit;
            }
            
            error_log("Quiz found: " . json_encode($quiz));
            
            // Update quiz properties
            $this->quizModel->titre = $quiz['titre'];
            $this->quizModel->description = $quiz['description'];
            $this->quizModel->categorie = $quiz['categorie'];
            $this->quizModel->image_url = $quiz['image_url'];
            $this->quizModel->difficulte = $quiz['difficulte'];
            $this->quizModel->statut = $newStatus;
            
            // Execute update
            if ($this->quizModel->update()) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } catch (Exception $e) {
            error_log("Update Status Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        
        exit;
    }

    /**
     * USER QUIZ MANAGEMENT (NEW FEATURE)
     */
    
    /**
     * Display user's own quizzes
     */
    public function userMyQuizzes() {
        $id_user = $_SESSION['user_id'] ?? 1; // FIXED: Use 'user_id' from friend's auth
        
        // Get only user's quizzes
        $conn = config::getConnexion();
        
        $query = "SELECT q.*, CONCAT(u.name, ' ', u.lastname) as createur
                  FROM quiz q
                  LEFT JOIN users u ON q.id_createur = u.id
                  WHERE q.id_createur = :id_user
                  ORDER BY q.date_creation DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $myQuizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../view/user/my_quizzes.php';
    }
    
    /**
     * Edit user's own quiz
     */
    public function userEditQuiz($id) {
        $id_user = $_SESSION['user_id'] ?? 1; // FIXED: Use 'user_id' from friend's auth
        
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();
        
        // Check if user owns this quiz
        if (!$quiz || $quiz['id_createur'] != $id_user) {
            header('Location: quiz_list.php?page=user_my_quizzes&error=not_owner');
            exit;
        }
        
        // Get questions for this quiz
        $questions = $this->quizQuestionModel->getQuestionsByQuiz($id);
        
        require __DIR__ . '/../view/user/edit_quiz.php';
    }
    
    /**
     * Update user's own quiz
     */
    public function userUpdateQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quiz = $_POST['quiz-id'];
            $id_user = $_SESSION['user_id'] ?? 1; // FIXED: Use 'user_id' from friend's auth
            
            $this->quizModel->id_quiz = $id_quiz;
            $quiz = $this->quizModel->readOne();
            
            // Check ownership
            if (!$quiz || $quiz['id_createur'] != $id_user) {
                header('Location: quiz_list.php?page=user_my_quizzes&error=not_owner');
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
                header('Location: quiz_list.php?page=user_edit_quiz&id=' . $id_quiz . '&error=question_count');
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
                    header('Location: quiz_list.php?page=user_edit_quiz&id=' . $id_quiz . '&error=update_failed');
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
                
                header('Location: quiz_list.php?page=user_my_quizzes&updated=1');
                exit;
                
            } catch (Exception $e) {
                error_log("Error updating quiz: " . $e->getMessage());
                header('Location: quiz_list.php?page=user_edit_quiz&id=' . $id_quiz . '&error=exception');
                exit;
            }
        }
    }
    
    /**
     * Delete user's own quiz
     */
    public function userDeleteQuiz($id) {
        $id_user = $_SESSION['user_id'] ?? 1; // FIXED: Use 'user_id' from friend's auth
        
        $this->quizModel->id_quiz = $id;
        $quiz = $this->quizModel->readOne();
        
        // Check ownership
        if ($quiz && $quiz['id_createur'] == $id_user) {
            $this->quizModel->delete();
            header('Location: quiz_list.php?page=user_my_quizzes&deleted=1');
        } else {
            header('Location: quiz_list.php?page=user_my_quizzes&error=not_owner');
        }
        exit;
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        error_log("=== IMAGE UPLOAD START ===");
        error_log("File name: " . $file['name']);
        error_log("File type: " . $file['type']);
        error_log("File size: " . $file['size']);
        
        // Check file type
        if (!in_array($file['type'], $allowedTypes)) {
            error_log("ERROR: File type not allowed: " . $file['type']);
            return false;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            error_log("ERROR: File size exceeds max: " . $file['size']);
            return false;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'quiz_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        
        // Upload directory - corrected path
        $uploadDir = __DIR__ . '/../assets/images/quiz/';
        error_log("Upload directory: " . $uploadDir);
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            error_log("Directory doesn't exist, creating...");
            if (!mkdir($uploadDir, 0777, true)) {
                error_log("ERROR: Failed to create directory");
                return false;
            }
            error_log("Directory created successfully");
        } else {
            error_log("Directory already exists");
        }
        
        $uploadPath = $uploadDir . $filename;
        error_log("Upload path: " . $uploadPath);
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            error_log("File moved successfully");
            error_log("=== IMAGE UPLOAD END - SUCCESS ===");
            return 'quiz/' . $filename;
        }
        
        error_log("ERROR: Failed to move uploaded file");
        error_log("=== IMAGE UPLOAD END - FAILED ===");
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
        // Get user_id from AJAX parameter first, then session, then default
        $id_user = $_GET['user_id'] ?? $_SESSION['user_id'] ?? 13;
        $id_user = intval($id_user);
        
        $stats = $this->getUserStatsData($id_user);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    private function getUserStatsData($id_user) {
        try {
            $conn = config::getConnexion();

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