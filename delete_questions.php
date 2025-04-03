<?php 
include("./controllers/QuizController.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $quiz = new QuizController();

    // Get question ID from request
    $question_id = $_POST["question_id"];

    // Validate input
    if (empty($question_id)) {
        echo json_encode(["success" => false, "message" => "Question ID is required."]);
        exit;
    }

    // Call the delete method
    $response = $quiz->deleteQuestion($question_id);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
