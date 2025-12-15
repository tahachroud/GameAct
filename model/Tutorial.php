<?php

class Tutorial
{
    private $conn;
    private $table_name = "tutorials";
    private $feedback_table = "feedbacks"; // Ajout du nom de la table de feedbacks

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        // REMARQUE : Pour la cohérence, vous devriez probablement utiliser fetchAll(PDO::FETCH_OBJ)
        // ou similaire ici, car getById et getAllRanked utilisent fetch/fetchAll.
        return $this->conn->query($sql);
    }
    
    /**
     * MODIFICATION : Récupère tous les tutoriels triés par le nombre total de feedbacks (interactions).
     * Nécessite que la table 'feedbacks' ait une colonne 'tutorial_id'.
     */
    public function getAllRanked()
    {
        // Jointure pour compter le nombre de feedbacks pour chaque tutoriel.
        // Utilisation de LEFT JOIN pour inclure les tutoriels qui n'ont AUCUN feedback (compte = 0).
        $sql = "SELECT 
                    t.*, 
                    COUNT(f.id) AS interactions_count
                FROM 
                    " . $this->table_name . " AS t
                LEFT JOIN
                    " . $this->feedback_table . " AS f ON t.id = f.tutorial_id
                GROUP BY 
                    t.id, t.title, t.videoUrl, t.content, t.category, t.likes_count, t.created_at /* Ajoutez toutes les colonnes de 'tutorials' (t) ici pour MySQL 5.7+ */
                ORDER BY 
                    interactions_count DESC, 
                    t.id DESC"; 
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        // On retourne les résultats sous forme d'objets. Chaque objet aura la propriété 'interactions_count'.
        return $stmt->fetchAll(PDO::FETCH_OBJ); 
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO " . $this->table_name . " (title, videoUrl, content, category) VALUES (?, ?, ?, ?)";
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
        $sql = "UPDATE " . $this->table_name . " SET title=?, videoUrl=?, content=?, category=? WHERE id=?";
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
        $sql = "DELETE FROM " . $this->table_name . " WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table_name;
        return $this->conn->query($sql)->fetch();
    }
}