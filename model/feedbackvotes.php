<?php

class FeedbackVote {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserVote($feedbackId, $userId) {
        $sql = "SELECT vote FROM feedback_votes WHERE feedback_id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$feedbackId, $userId]);
        $row = $stmt->fetch();

        return $row ? (int)$row['vote'] : 0; 
    }

    public function getCounts($feedbackId) {
        $sql = "SELECT 
                 SUM(CASE WHEN vote = 1 THEN 1 ELSE 0 END) AS likes,
                 SUM(CASE WHEN vote = -1 THEN 1 ELSE 0 END) AS dislikes
                FROM feedback_votes WHERE feedback_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$feedbackId]);
        return $stmt->fetch();
    }

    public function applyVote($feedbackId, $userId, $vote) {
        $this->pdo->beginTransaction();

        $current = $this->getUserVote($feedbackId, $userId);

        if ($current == 0) {
            
            $sql = "INSERT INTO feedback_votes(feedback_id, user_id, vote) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$feedbackId, $userId, $vote]);
            $status = "added";
        } elseif ($current == $vote) {
           
            $sql = "DELETE FROM feedback_votes WHERE feedback_id = ? AND user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$feedbackId, $userId]);
            $status = "removed";
        } else {
            
            $sql = "UPDATE feedback_votes SET vote = ? WHERE feedback_id = ? AND user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$vote, $feedbackId, $userId]);
            $status = "updated";
        }

        $this->pdo->commit();

        return [
            "status" => $status,
            "counts" => $this->getCounts($feedbackId),
            "userVote" => ($status === "removed" ? 0 : $vote)
        ];
    }
}
