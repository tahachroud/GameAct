<?php

class Comment {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // GET ALL COMMENTS (used in dashboard)
    public function getAll()
    {
        $sql = "SELECT comments.*, posts.content AS post_content, users.username
                FROM comments
                JOIN posts ON comments.post_id = posts.id
                JOIN users ON comments.user_id = users.id
                ORDER BY comments.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FIND ONE
    public function find($id)
    {
        $sql = "SELECT * FROM comments WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE COMMENT
    public function create($data)
    {
        $sql = "INSERT INTO comments (post_id, user_id, content)
                VALUES (:post_id, :user_id, :content)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":post_id" => $data['post_id'],
            ":user_id" => $data['user_id'],
            ":content" => $data['content']
        ]);
    }

    // UPDATE COMMENT
    public function update($id, $post_id, $user_id, $content)
    {
        $sql = "UPDATE comments SET post_id=?, user_id=?, content=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$post_id, $user_id, $content, $id]);
    }

    // DELETE COMMENT
    public function delete($id)
    {
        $sql = "DELETE FROM comments WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // GET COMMENTS FOR ONE POST
    public function getByPost($post_id)
    {
        $sql = "SELECT comments.*, users.username 
                FROM comments
                JOIN users ON users.id = comments.user_id
                WHERE post_id = ?
                ORDER BY comments.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // COUNT COMMENTS FOR ONE POST
    public function countByPost($post_id)
    {
        $sql = "SELECT COUNT(*) FROM comments WHERE post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchColumn();
    }

}
