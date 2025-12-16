<?php

class Feedback
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CRÉATION D'UN FEEDBACK (COMMENTAIRE)
    ====================================================== */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO feedbacks (tutorial_id, username, message, created_at)
            VALUES (:tutorial_id, :username, :message, NOW())
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'tutorial_id' => $data['tutorial_id'],
            'username'    => $data['username'],
            'message'     => $data['message']
        ]);
    }

    /* =====================================================
       VOTES (LIKE / DISLIKE)
    ====================================================== */

    public function getUserVote(int $feedbackId, int $userId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT vote 
             FROM feedback_votes 
             WHERE feedback_id = ? AND user_id = ?"
        );
        $stmt->execute([$feedbackId, $userId]);
        $vote = $stmt->fetchColumn();

        return $vote !== false ? (int) $vote : 0;
    }

    public function saveVote(int $feedbackId, int $userId, int $vote): void
    {
        $stmt = $this->pdo->prepare(
            "REPLACE INTO feedback_votes (feedback_id, user_id, vote)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([$feedbackId, $userId, $vote]);
    }

    public function deleteVote(int $feedbackId, int $userId): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM feedback_votes 
             WHERE feedback_id = ? AND user_id = ?"
        );
        $stmt->execute([$feedbackId, $userId]);
    }

    public function countVotes(int $feedbackId, int $vote): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) 
             FROM feedback_votes 
             WHERE feedback_id = ? AND vote = ?"
        );
        $stmt->execute([$feedbackId, $vote]);

        return (int) $stmt->fetchColumn();
    }

    /* =====================================================
       FEEDBACKS PAR TUTORIEL
    ====================================================== */

    public function getByTutorial(int $tutorialId): array
    {
        $sql = "
            SELECT f.*,
                   COALESCE(SUM(CASE WHEN v.vote = 1 THEN 1 ELSE 0 END), 0) AS likes,
                   COALESCE(SUM(CASE WHEN v.vote = -1 THEN 1 ELSE 0 END), 0) AS dislikes
            FROM feedbacks f
            LEFT JOIN feedback_votes v ON v.feedback_id = f.id
            WHERE f.tutorial_id = :tutorial_id
            GROUP BY f.id
            ORDER BY f.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tutorial_id' => $tutorialId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
