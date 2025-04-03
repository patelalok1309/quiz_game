<?php
header("Content-Type: application/json");

include_once("./config/Database.php");

// Validate incoming request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["quiz_id"], $data["user_id"], $data["attempt_id"], $data["answers"]) || !is_array($data["answers"])) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

$quizId = $data["quiz_id"];
$userId = $data["user_id"];
$answers = $data["answers"]; // This is an array of objects

$database = new Database();
$conn = $database->connect();

try {
    // Fetch correct answers from `options`
    $query = "SELECT q.id AS question_id, o.answer 
              FROM questions q 
              JOIN options o ON q.id = o.question_id
              WHERE q.quiz_id = :quiz_id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":quiz_id", $quizId, PDO::PARAM_INT);
    $stmt->execute();

    $correctAnswers = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $correctAnswers[$row['question_id']] = $row['answer'];
    }

    $correctCount = 0;
    $wrongCount = 0;
    $unattemptedCount = count($correctAnswers) - count($answers);

    foreach ($answers as $index => $answerObj) {
        if (!isset($answerObj['answer'])) {
            continue;
        }

        $questionId = array_keys($correctAnswers)[$index];
        $selectedAnswer = $answerObj['answer'];

        if (empty($selectedAnswer)) {
            $unattemptedCount++;
            continue;
        }

        $isCorrect = isset($correctAnswers[$questionId]) && $correctAnswers[$questionId] === $selectedAnswer;
        if ($isCorrect) {
            $correctCount++;
        } else {
            $wrongCount++;
        }
    }

    $total = $correctCount + $wrongCount + $unattemptedCount;   

    $insertQuery = "INSERT INTO  user_attermpts (user_id, quiz_id, correct, wrong, total) 
                    VALUES (:user_id, :quiz_id, :correct, :wrong, :total)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":quiz_id", $quizId, PDO::PARAM_INT);
    $stmt->bindParam(":correct", $correctCount, PDO::PARAM_INT);
    $stmt->bindParam(":wrong", $wrongCount, PDO::PARAM_INT);
    $stmt->bindParam(":total", $total, PDO::PARAM_INT);
    $stmt->execute();
    $attemptId = $conn->lastInsertId();

    $_SESSION['result_id'] = $attemptId;

    echo json_encode([
        "success" => true,
        "correct_answers" => $correctCount,
        "wrong_answers" => $wrongCount,
        "unattempted_questions" => $unattemptedCount,
        "total_questions" => $total,
        "result_id" => $attemptId
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
}
?>

