<?php

require_once __DIR__ . "/../models/Feedback.php";
require_once __DIR__ . "/../models/Tutorial.php";
require_once __DIR__ . "/../config/database.php";

class FeedbackController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function store()
    {
        session_start();

        $errors = [];
        $username = trim($_POST["username"] ?? "");
        $message  = trim($_POST["message"] ?? "");
        $tutorial = $_POST["tutorial_id"] ?? null;

        if (!$tutorial || !is_numeric($tutorial)) {
            $errors[] = "Tutoriel invalide.";
        }

        if (strlen($username) < 3) {
            $errors[] = "Le nom doit contenir au moins 3 caractères.";
        }

        if (strlen($message) < 5) {
            $errors[] = "Le message doit contenir au moins 5 caractères.";
        }

        if (strlen($username) > 50) {
            $errors[] = "Le nom ne doit pas dépasser 50 caractères.";
        }

        if (strlen($message) > 500) {
            $errors[] = "Le message est trop long (maximum 500 caractères).";
        }

        if (!empty($errors)) {
            $_SESSION["feedback_errors"] = $errors;
            $_SESSION["feedback_old"] = [
                "username" => $username,
                "message" => $message
            ];

            header("Location: router.php?action=show&id=" . $tutorial);
            exit;
        }

        $fb = new Feedback($this->db);
        $fb->create([
            "tutorial_id" => $tutorial,
            "username" => $username,
            "message" => $message
        ]);

        header("Location: router.php?action=show&id=" . $tutorial);
        exit;
    }

    public function adminList()
    {
        $fb = new Feedback($this->db);
        $data = $fb->getAllWithTutorial();

        include "./views/back/feedbackList.php";
    }

    public function delete($id)
    {
        $fb = new Feedback($this->db);
        $fb->delete($id);

        header("Location: router.php?action=adminFeedback");
        exit;
    }

   public function like($id)
{
    $fb = new Feedback($this->db);

    // Récupérer le feedback pour connaître le tutoriel
    $feedback = $fb->getById($id);

    if ($feedback) {
        $tutorialId = $feedback["tutorial_id"];
        $fb->addLike($id);

        // Retourner sur la page du tutoriel (FRONT)
        header("Location: router.php?action=show&id=" . $tutorialId);
        exit;
    }

    // Si erreur → retour admin
    header("Location: router.php?action=adminFeedback");
    exit;
}


public function dislike($id)
{
    $fb = new Feedback($this->db);

    $feedback = $fb->getById($id);

    if ($feedback) {
        $tutorialId = $feedback["tutorial_id"];
        $fb->addDislike($id);

        header("Location: router.php?action=show&id=" . $tutorialId);
        exit;
    }

    header("Location: router.php?action=adminFeedback");
    exit;
}

}

