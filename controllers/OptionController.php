<?php
include("./config/Database.php");

class OptionController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getOptionsByQuestionId($question_id) {
        $stmt = $this->conn->prepare("
            SELECT id, options, answer 
            FROM options 
            WHERE question_id = :question_id
        ");
        $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addOptions($question_id, $options, $answer) {
        try {
            $optionsJson = json_encode($options);
            $stmt = $this->conn->prepare("
                INSERT INTO options (question_id, options, answer) 
                VALUES (:question_id, :options, :answer)
            ");
            $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
            $stmt->bindParam(":options", $optionsJson, PDO::PARAM_STR);
            $stmt->bindParam(":answer", $answer, PDO::PARAM_STR);
            $stmt->execute();
            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>
