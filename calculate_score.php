<?php
session_start();
header("Content-Type: application/json");
include_once("./controllers/QuizController.php");

// Validate incoming request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["quiz_id"]) || !isset($data["user_id"]) || !isset($data["attempt_id"]) || !isset($data["answers"])) {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$quizId = $data["quiz_id"];
$userId = $data["user_id"];
$attemptId = $data["attempt_id"];
$responses = $data["answers"];

try {

    $quizModel = new $quizController();

    $result = $quizModel->calculateQuizResult($attemptId, $userId, $quizId, $responses);

    echo json_encode($result);
} catch (Exception $e) {
    error_log("Error calculating quiz result: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "An error occurred while processing your request."]);
}
?>
