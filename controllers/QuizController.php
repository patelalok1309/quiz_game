<?php

require_once __DIR__ . "/../config/Database.php";

class QuizController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getQuizzes()
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT quizzes.id, quizzes.title, quizzes.topic, quizzes.created_at, 
                       users.id as user_id, users.username 
                FROM quizzes 
                LEFT JOIN users ON quizzes.created_by = users.id
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }

    public function addQuiz($title, $topic, $description, $created_by)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO quizzes (title, topic, description, created_by) 
                VALUES (:title, :topic, :description, :created_by)
            ");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':topic', $topic);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':created_by', $created_by);
            $result = $stmt->execute();

            return json_encode(["success" => true]);
        } catch (PDOException $e) {
            return json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    public function deleteQuiz($quiz_id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM quizzes WHERE id = :quiz_id");
            $stmt->bindParam(':quiz_id', $quiz_id);
            $stmt->execute();
            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getQuestionsByQuizId($quiz_id)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT q.id AS question_id, q.question_text, q.question_type, 
                       o.options, o.answer
                FROM questions q
                LEFT JOIN options o ON q.id = o.question_id
                WHERE q.quiz_id = :quiz_id
            ");

            $stmt->bindParam(":quiz_id", $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process options JSON
            foreach ($result as &$row) {
                $row['options'] = json_decode($row['options'], true); // Decode JSON options
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }

    public function addQuestionWithOptions($quiz_id, $question_text, $question_type, $options, $answer) {
        try {
            // Start Transaction
            $this->conn->beginTransaction();
    
            // Insert Question
            $stmt = $this->conn->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (:quiz_id, :question_text, :question_type)");
            $stmt->bindParam(":quiz_id", $quiz_id, PDO::PARAM_INT);
            $stmt->bindParam(":question_text", $question_text, PDO::PARAM_STR);
            $stmt->bindParam(":question_type", $question_type, PDO::PARAM_STR);
            $stmt->execute();
    
            // Get the last inserted question ID
            $question_id = $this->conn->lastInsertId();
    
            // Insert Options
            $optionsJson = json_encode($options);
            $stmt = $this->conn->prepare("INSERT INTO options (question_id, options, answer) VALUES (:question_id, :options, :answer)");
            $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
            $stmt->bindParam(":options", $optionsJson, PDO::PARAM_STR);
            $stmt->bindParam(":answer", $answer, PDO::PARAM_STR);
            $stmt->execute();
    
            // Commit transaction
            $this->conn->commit();
            
            return ["success" => true, "message" => "Question and options added successfully!"];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Database Error: " . $e->getMessage());
            return ["success" => false, "message" => "Failed to add question."];
        }
    }

    public function deleteQuestion($question_id) {
        try {
            // Start Transaction
            $this->conn->beginTransaction();
    
            // Delete Options first (to maintain foreign key constraints)
            $stmt = $this->conn->prepare("DELETE FROM options WHERE question_id = :question_id");
            $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Delete Question
            $stmt = $this->conn->prepare("DELETE FROM questions WHERE id = :question_id");
            $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Commit transaction
            $this->conn->commit();
    
            return ["success" => true, "message" => "Question deleted successfully!"];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Database Error: " . $e->getMessage());
            return ["success" => false, "message" => "Failed to delete question."];
        }
    }

    public function getQuestionsByQuizIdWithoutAns($quiz_id)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT q.id AS question_id, q.question_text, q.question_type, 
                       o.options
                FROM questions q
                LEFT JOIN options o ON q.id = o.question_id
                WHERE q.quiz_id = :quiz_id
            ");

            $stmt->bindParam(":quiz_id", $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process options JSON
            foreach ($result as &$row) {
                $row['options'] = json_decode($row['options'], true); // Decode JSON options
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }
}
