<?php

class Tutorial
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM tutorials ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM tutorials WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO tutorials (title, videoUrl, content, category) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['videoUrl'],
            $data['content'],
            $data['category']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE tutorials SET title=?, videoUrl=?, content=?, category=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['videoUrl'],
            $data['content'],
            $data['category'],
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM tutorials WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM tutorials";
        return $this->conn->query($sql)->fetch();
    }
}

