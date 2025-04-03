<?php
include_once("./controllers/QuizController.php");


$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($quizId > 0) {
    $quizController = new QuizController();
    $questions = $quizController->getQuestionsByQuizIdWithoutAns($quizId);
    echo json_encode($questions);
} else {
    echo json_encode(["error" => "Invalid quiz ID"]);
}


