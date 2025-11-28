<?php

class User {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // GET ALL USERS
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FIND ONE
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function create($data)
    {
        $sql = "INSERT INTO users (username, avatar, level, xp, badges)
                VALUES (:username, :avatar, :level, :xp, :badges)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":username" => $data['username'],
            ":avatar"   => $data['avatar'],
            ":level"    => $data['level'],
            ":xp"       => $data['xp'],
            ":badges"   => $data['badges']
        ]);
    }

    // UPDATE
    public function update($data)
    {
        $sql = "UPDATE users SET 
                    username = :username, 
                    avatar = :avatar,
                    level = :level,
                    xp = :xp,
                    badges = :badges
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ":id"       => $data['id'],
            ":username" => $data['username'],
            ":avatar"   => $data['avatar'],
            ":level"    => $data['level'],
            ":xp"       => $data['xp'],
            ":badges"   => $data['badges']
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }

    // COUNT (dashboard)
    public function countUsers()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }
}
