<?php 
include("./controllers/QuizController.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $quiz = new QuizController();

    // Get data from the form
    $quiz_id = $_POST["quiz_id"];
    $question_text = $_POST["question_text"];
    $question_type = $_POST["question_type"];
    $options = $_POST["options"]; // Expecting an array
    $answer = $_POST["answer"];

    // Convert options to an array if needed
    if (!is_array($options)) {
        $options = json_decode($options, true);
    }

    $response = $quiz->addQuestionWithOptions($quiz_id, $question_text, $question_type, $options, $answer);

    header('Content-Type: application/json');
    echo json_encode($response);
}

