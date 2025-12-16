<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/Feedback.php';
require_once __DIR__ . '/../model/Tutorial.php';

class FeedbackController
{
    private PDO $db;
    private Feedback $feedbackModel;

    public function __construct()
    {
        $this->db = config::getconnexion();
        $this->feedbackModel = new Feedback($this->db);
    }

    /* =====================================================
       AJOUT D'UN FEEDBACK (COMMENTAIRE)
    ====================================================== */
    public function store()
    {
        session_start();

        $errors = [];

        $username = trim($_POST['username'] ?? '');
        $message  = trim($_POST['message'] ?? '');
        $tutorial = $_POST['tutorial_id'] ?? null;

        if (!$tutorial || !is_numeric($tutorial)) {
            $errors[] = "Tutoriel invalide.";
        }

        if (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Le nom doit contenir entre 3 et 50 caractères.";
        }

        if (strlen($message) < 5 || strlen($message) > 500) {
            $errors[] = "Le message doit contenir entre 5 et 500 caractères.";
        }

        if (!empty($errors)) {
            $_SESSION['feedback_errors'] = $errors;
            $_SESSION['feedback_old'] = [
                'username' => $username,
                'message'  => $message
            ];

            header("Location: router.php?action=show&id=" . $tutorial);
            exit;
        }

        $this->feedbackModel->create([
            'tutorial_id' => $tutorial,
            'username'    => $username,
            'message'     => $message
        ]);

        header("Location: router.php?action=show&id=" . $tutorial);
        exit;
    }

    /* =====================================================
       AJAX : LIKE / DISLIKE (1 VOTE PAR UTILISATEUR)
    ====================================================== */
    public function vote()
    {
        header('Content-Type: application/json; charset=utf-8');
        session_start();

        // utilisateur simulé si pas de login
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = 1;
        }

        try {
            if (!isset($_POST['id'], $_POST['type'])) {
                throw new Exception('Paramètres manquants');
            }

            $feedbackId = (int) $_POST['id'];
            $type = $_POST['type']; // like | dislike

            if (!in_array($type, ['like', 'dislike'], true)) {
                throw new Exception('Type invalide');
            }

            $userId = $_SESSION['user_id'];
            $newVote = ($type === 'like') ? 1 : -1;

            $currentVote = $this->feedbackModel->getUserVote($feedbackId, $userId);

            if ($currentVote === $newVote) {
                // même vote → on annule
                $this->feedbackModel->deleteVote($feedbackId, $userId);
                $userVote = 0;
            } else {
                // nouveau vote ou changement
                $this->feedbackModel->saveVote($feedbackId, $userId, $newVote);
                $userVote = $newVote;
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'counts' => [
                        'likes'    => $this->feedbackModel->countVotes($feedbackId, 1),
                        'dislikes' => $this->feedbackModel->countVotes($feedbackId, -1)
                    ],
                    'userVote' => $userVote
                ]
            ]);
            exit;

        } catch (Throwable $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }

    /* =====================================================
       ADMIN
    ====================================================== */
    public function adminList()
    {
        $data = $this->feedbackModel->getAllWithTutorial();
        include __DIR__ . '/../view/back/feedbackList.php';
    }

    public function delete($id)
    {
        $this->feedbackModel->delete($id);
        header("Location: router.php?action=adminFeedback");
        exit;
    }
}
