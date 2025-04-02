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
            $stmt->execute();

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
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

    public function getQuestionsByQuizId($quiz_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT q.id, q.question_text, q.question_type
                FROM questions q
                WHERE q.quiz_id = :quiz_id
            ");
            $stmt->bindParam(":quiz_id", $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }
}
