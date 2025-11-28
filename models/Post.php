<?php

class Post {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ===================================================
    // GET ALL POSTS (normal only)
    // ===================================================
    public function getAll()
    {
        $sql = "SELECT posts.*, users.username 
                FROM posts 
                JOIN users ON users.id = posts.user_id
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===================================================
    // FIND ONE POST
    // ===================================================
    public function find($id)
    {
        $sql = "SELECT posts.*, users.username
                FROM posts
                JOIN users ON users.id = posts.user_id
                WHERE posts.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===================================================
    // CREATE NORMAL POST
    // ===================================================
    public function create($user_id, $content, $image)
    {
        $sql = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $content, $image]);
    }

    // ===================================================
    // CREATE SHARED POST (FACEBOOK STYLE)
    // ===================================================
    public function createShare($user_id, $original_post_id, $message = "")
    {
        // shared post has message = content
        // link to original post through parent_id

        $sql = "INSERT INTO posts (user_id, content, image, parent_id)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $user_id,
            $message,
            null,               // shared post has no image
            $original_post_id   // link to original
        ]);

        return $this->db->lastInsertId();
    }

    // ===================================================
    // UNIFIED FEED: posts + shared posts sorted by date
    // ===================================================
    public function getUnifiedFeed()
    {
        $sql = "
            SELECT posts.*, users.username
            FROM posts
            JOIN users ON users.id = posts.user_id
            ORDER BY created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===================================================
    // UPDATE
    // ===================================================
    public function update($id, $user_id, $content, $image)
    {
        $sql = "UPDATE posts SET user_id=?, content=?, image=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $content, $image, $id]);
    }

    // ===================================================
    // DELETE
    // ===================================================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id=?");
        return $stmt->execute([$id]);
    }

    // ===================================================
    // COUNT posts
    // ===================================================
    public function countPosts()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM posts");
        return $stmt->fetchColumn();
    }
    // ===================================================
// ADVANCED SEARCH (multi-critère SQL + JSON)
// ===================================================
public function search($criteria)
{
    $query = "SELECT posts.*, users.username 
              FROM posts
              JOIN users ON users.id = posts.user_id
              WHERE 1=1";

    $params = [];

    /* ========== KEYWORD (content) ========== */
    if (!empty($criteria['keyword'])) {
        $query .= " AND posts.content LIKE :keyword";
        $params[':keyword'] = "%".$criteria['keyword']."%";
    }

    /* ========== AUTHOR ========== */
    if (!empty($criteria['author'])) {
        $query .= " AND users.username = :author";
        $params[':author'] = $criteria['author'];
    }

    /* ========== DATE RANGE ========== */
    if (!empty($criteria['date_from'])) {
        $query .= " AND DATE(posts.created_at) >= :date_from";
        $params[':date_from'] = $criteria['date_from'];
    }

    if (!empty($criteria['date_to'])) {
        $query .= " AND DATE(posts.created_at) <= :date_to";
        $params[':date_to'] = $criteria['date_to'];
    }

    /* ========== HAS IMAGE ========== */
    if (!empty($criteria['has_image']) && $criteria['has_image'] === "1") {
        $query .= " AND posts.image IS NOT NULL";
    }

    /* ========== EXECUTE SQL FIRST ========== */
    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ========== FILTER BY MINIMUM LIKES (JSON) ========== */
    if (!empty($criteria['min_likes'])) {

        $likesJson = json_decode(file_get_contents(__DIR__ . '/../public/admin_likes.json'), true);
        $likesList = $likesJson['posts'];

        $posts = array_filter($posts, function ($p) use ($criteria, $likesList) {
            $count = isset($likesList[$p['id']]) ? $likesList[$p['id']] : 0;
            return $count >= $criteria['min_likes'];
        });
    }

    /* ========== FILTER BY MOST SHARED (JSON) ========== */
    if (!empty($criteria['most_shared']) && $criteria['most_shared'] == 1) {

        $sharesJson = json_decode(file_get_contents(__DIR__ . '/../public/admin_shares.json'), true);
        $sharesList = $sharesJson['posts'];

        // Sort by highest shares
        usort($posts, function($a, $b) use ($sharesList) {
            $a_s = isset($sharesList[$a['id']]) ? $sharesList[$a['id']] : 0;
            $b_s = isset($sharesList[$b['id']]) ? $sharesList[$b['id']] : 0;
            return $b_s - $a_s;
        });
    }

    return $posts;
}

}

