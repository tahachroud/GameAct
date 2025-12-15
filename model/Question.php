<?php
/**
 * Question Model
 * model/Question.php
 */

require_once __DIR__ . '/../config.php';

class Question {
    
    private $conn;
    private $table_name = "question";

    // Question properties
    public $id_question;
    public $texte_question;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $reponse_correcte;
    public $explication;
    public $points;
    public $date_creation;

    /**
     * Constructor
     */
    public function __construct() {
        $this->conn = config::getConnexion();
        
    }

    /**
     * Create new question
     * @return int|false Question ID or false
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (texte_question, option_a, option_b, option_c, option_d, reponse_correcte, explication, points) 
                  VALUES 
                  (:texte_question, :option_a, :option_b, :option_c, :option_d, :reponse_correcte, :explication, :points)";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->texte_question = htmlspecialchars(strip_tags($this->texte_question));
        $this->option_a = htmlspecialchars(strip_tags($this->option_a));
        $this->option_b = htmlspecialchars(strip_tags($this->option_b));
        $this->option_c = htmlspecialchars(strip_tags($this->option_c));
        $this->option_d = htmlspecialchars(strip_tags($this->option_d));
        $this->reponse_correcte = strtoupper(htmlspecialchars(strip_tags($this->reponse_correcte)));
        $this->explication = htmlspecialchars(strip_tags($this->explication));

        // Bind values
        $stmt->bindParam(':texte_question', $this->texte_question);
        $stmt->bindParam(':option_a', $this->option_a);
        $stmt->bindParam(':option_b', $this->option_b);
        $stmt->bindParam(':option_c', $this->option_c);
        $stmt->bindParam(':option_d', $this->option_d);
        $stmt->bindParam(':reponse_correcte', $this->reponse_correcte);
        $stmt->bindParam(':explication', $this->explication);
        $stmt->bindParam(':points', $this->points);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Read single question by ID
     * @return array|false
     */
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_question = :id_question 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_question', $this->id_question);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Update question
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                    texte_question = :texte_question,
                    option_a = :option_a,
                    option_b = :option_b,
                    option_c = :option_c,
                    option_d = :option_d,
                    reponse_correcte = :reponse_correcte,
                    explication = :explication,
                    points = :points
                  WHERE id_question = :id_question";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->texte_question = htmlspecialchars(strip_tags($this->texte_question));
        $this->option_a = htmlspecialchars(strip_tags($this->option_a));
        $this->option_b = htmlspecialchars(strip_tags($this->option_b));
        $this->option_c = htmlspecialchars(strip_tags($this->option_c));
        $this->option_d = htmlspecialchars(strip_tags($this->option_d));
        $this->reponse_correcte = strtoupper(htmlspecialchars(strip_tags($this->reponse_correcte)));

        // Bind
        $stmt->bindParam(':texte_question', $this->texte_question);
        $stmt->bindParam(':option_a', $this->option_a);
        $stmt->bindParam(':option_b', $this->option_b);
        $stmt->bindParam(':option_c', $this->option_c);
        $stmt->bindParam(':option_d', $this->option_d);
        $stmt->bindParam(':reponse_correcte', $this->reponse_correcte);
        $stmt->bindParam(':explication', $this->explication);
        $stmt->bindParam(':points', $this->points);
        $stmt->bindParam(':id_question', $this->id_question);

        return $stmt->execute();
    }

    /**
     * Delete question
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id_question = :id_question";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_question', $this->id_question);

        return $stmt->execute();
    }

    /**
     * Validate question data
     * @return array Validation errors
     */
    public function validate() {
        $errors = [];

        if (empty($this->texte_question) || strlen($this->texte_question) < 10) {
            $errors[] = "La question doit contenir au moins 10 caractères";
        }

        if (empty($this->option_a) || strlen($this->option_a) < 2) {
            $errors[] = "L'option A est requise";
        }

        if (empty($this->option_b) || strlen($this->option_b) < 2) {
            $errors[] = "L'option B est requise";
        }

        if (empty($this->option_c) || strlen($this->option_c) < 2) {
            $errors[] = "L'option C est requise";
        }

        if (empty($this->option_d) || strlen($this->option_d) < 2) {
            $errors[] = "L'option D est requise";
        }

        $valid_answers = ['A', 'B', 'C', 'D'];
        if (!in_array(strtoupper($this->reponse_correcte), $valid_answers)) {
            $errors[] = "Réponse correcte invalide";
        }

        return $errors;
    }
}
?>