<?php

class Feedback
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $sql = "INSERT INTO feedbacks (tutorial_id, username, message) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['tutorial_id'], $data['username'], $data['message']]);
    }

    public function getAll()
    {
        return $this->conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
    }

    public function getAllWithTutorial()
    {
        $sql = "
            SELECT f.*, t.title 
            FROM feedbacks f
            JOIN tutorials t ON t.id = f.tutorial_id
            ORDER BY f.created_at DESC
        ";
        return $this->conn->query($sql);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM feedbacks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function addLike($id)
    {
        $sql = "UPDATE feedbacks SET likes = likes + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function addDislike($id)
    {
        $sql = "UPDATE feedbacks SET dislikes = dislikes + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getByTutorial($tutorial_id)
    {
        $sql = "SELECT * FROM feedbacks WHERE tutorial_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tutorial_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM feedbacks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
