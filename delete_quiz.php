<?php
include("./controllers/QuizController.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!isset($_POST['quiz_id']) || empty($_POST['quiz_id'])) {
        echo json_encode(["success" => false, "message" => "Quiz ID is required."]);
        exit;
    }

    $quiz_id = intval($_POST['quiz_id']); // Ensure it's an integer
    $quiz = new QuizController();
    $result = $quiz->deleteQuiz($quiz_id);

    echo json_encode($result);
}
?>

