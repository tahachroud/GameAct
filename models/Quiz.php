<?php
/**
 * Quiz Model
 * models/Quiz.php
 */

require_once __DIR__ . '/../config/db.php';

class Quiz {
    private $conn;
    private $table_name = "quiz";

    // Quiz properties
    public $id_quiz;
    public $titre;
    public $description;
    public $categorie;
    public $image_url;
    public $id_createur;
    public $difficulte;
    public $nombre_questions;
    public $nombre_completions;
    public $statut;
    public $date_creation;
    public $date_modification;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Create new quiz
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET titre = :titre,
                    description = :description,
                    categorie = :categorie,
                    image_url = :image_url,
                    id_createur = :id_createur,
                    difficulte = :difficulte,
                    nombre_questions = :nombre_questions,
                    nombre_completions = 0,
                    statut = :statut";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->categorie = htmlspecialchars(strip_tags($this->categorie));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->statut = htmlspecialchars(strip_tags($this->statut));

        // Bind values
        $stmt->bindParam(":titre", $this->titre);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":categorie", $this->categorie);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":id_createur", $this->id_createur);
        $stmt->bindParam(":difficulte", $this->difficulte);
        $stmt->bindParam(":nombre_questions", $this->nombre_questions);
        $stmt->bindParam(":statut", $this->statut);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Read all quizzes
     */
    public function readAll($categoryFilter = null) {
        $query = "SELECT 
                    q.*,
                    u.username as createur
                  FROM " . $this->table_name . " q
                  LEFT JOIN users u ON q.id_createur = u.id_user
                  WHERE q.statut = 'active'";

        if ($categoryFilter && $categoryFilter !== 'all') {
            $query .= " AND q.categorie = :categorie";
        }

        $query .= " ORDER BY q.date_creation DESC";

        $stmt = $this->conn->prepare($query);

        if ($categoryFilter && $categoryFilter !== 'all') {
            $stmt->bindParam(':categorie', $categoryFilter);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Read one quiz
     */
    public function readOne() {
        $query = "SELECT 
                    q.*,
                    u.username as createur
                  FROM " . $this->table_name . " q
                  LEFT JOIN users u ON q.id_createur = u.id_user
                  WHERE q.id_quiz = :id_quiz
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_quiz", $this->id_quiz);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->titre = $row['titre'];
            $this->description = $row['description'];
            $this->categorie = $row['categorie'];
            $this->image_url = $row['image_url'];
            $this->id_createur = $row['id_createur'];
            $this->difficulte = $row['difficulte'];
            $this->nombre_questions = $row['nombre_questions'];
            $this->nombre_completions = $row['nombre_completions'];
            $this->statut = $row['statut'];
            $this->date_creation = $row['date_creation'];
            
            return $row;
        }

        return false;
    }

    /**
     * Update quiz
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET titre = :titre,
                    description = :description,
                    categorie = :categorie,
                    image_url = :image_url,
                    difficulte = :difficulte,
                    statut = :statut
                WHERE id_quiz = :id_quiz";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->categorie = htmlspecialchars(strip_tags($this->categorie));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->statut = htmlspecialchars(strip_tags($this->statut));

        // Bind
        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':categorie', $this->categorie);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':difficulte', $this->difficulte);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':id_quiz', $this->id_quiz);

        return $stmt->execute();
    }

    /**
     * Delete quiz (soft delete)
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . "
                SET statut = 'deleted'
                WHERE id_quiz = :id_quiz";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_quiz', $this->id_quiz);

        return $stmt->execute();
    }

    /**
     * Search quizzes
     */
    public function search($searchTerm) {
        $query = "SELECT 
                    q.*,
                    u.username as createur
                  FROM " . $this->table_name . " q
                  LEFT JOIN users u ON q.id_createur = u.id_user
                  WHERE q.statut = 'active'
                  AND (q.titre LIKE :search OR q.description LIKE :search OR u.username LIKE :search)
                  ORDER BY q.date_creation DESC";

        $stmt = $this->conn->prepare($query);
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Increment completions counter
     */
    public function incrementCompletions() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre_completions = nombre_completions + 1
                WHERE id_quiz = :id_quiz";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_quiz', $this->id_quiz);

        return $stmt->execute();
    }

    /**
     * Validate quiz data
     */
    public function validate() {
        $errors = [];

        if (empty($this->titre) || strlen($this->titre) < 5) {
            $errors[] = "Title must be at least 5 characters";
        }

        if (empty($this->description) || strlen($this->description) < 20) {
            $errors[] = "Description must be at least 20 characters";
        }

        if (empty($this->categorie)) {
            $errors[] = "Category is required";
        }

        return $errors;
    }
}
?>