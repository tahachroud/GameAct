<?php
/**
 * Quiz-Question Junction Model
 * model/QuizQuestion.php
 */

require_once __DIR__ . '/../config.php';

class QuizQuestion {
    
    private $conn;
    private $table_name = "quiz_question";

    // Properties
    public $id_quiz;
    public $id_question;
    public $ordre_question;

    /**
     * Constructor
     */
    public function __construct() {
        $this->conn = config::getConnexion();
        
    }

    /**
     * Associate multiple questions with a quiz
     * @param int $quiz_id
     * @param array $question_ids
     * @return bool
     */
    public function associateQuestions($quiz_id, $question_ids) {
        try {
            $ordre = 1;
            
            foreach ($question_ids as $question_id) {
                $query = "INSERT INTO " . $this->table_name . " 
                          (id_quiz, id_question, ordre_question) 
                          VALUES 
                          (:id_quiz, :id_question, :ordre_question)";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_quiz', $quiz_id);
                $stmt->bindParam(':id_question', $question_id);
                $stmt->bindParam(':ordre_question', $ordre);
                
                $stmt->execute();
                $ordre++;
            }

            return true;

        } catch (Exception $e) {
            error_log("Error associateQuestions: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all questions for a specific quiz
     * @param int $quiz_id
     * @return array
     */
    public function getQuestionsByQuiz($quiz_id) {
        $query = "SELECT 
                    q.*,
                    qq.ordre_question
                  FROM question q
                  INNER JOIN " . $this->table_name . " qq ON q.id_question = qq.id_question
                  WHERE qq.id_quiz = :id_quiz
                  ORDER BY qq.ordre_question ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_quiz', $quiz_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Remove all questions from a quiz
     * @param int $quiz_id
     * @return bool
     */
    public function removeAllQuestions($quiz_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id_quiz = :id_quiz";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_quiz', $quiz_id);

        return $stmt->execute();
    }

    /**
     * Check if a question is used in any quiz
     * @param int $question_id
     * @return bool
     */
    public function isQuestionUsed($question_id) {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE id_question = :id_question";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_question', $question_id);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row['count'] > 0;
    }

    /**
     * Get quiz count for a question
     * @param int $question_id
     * @return int
     */
    public function getQuizCountForQuestion($question_id) {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE id_question = :id_question";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_question', $question_id);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row['count'];
    }
}
?>