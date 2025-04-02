<?php
include("./controllers/QuizController.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $quiz = new QuizController();

    $title = $_POST['title'] ?? '';
    $topic = $_POST['topic'] ?? '';
    $description = $_POST['description'] ?? '';
    $created_by = $_POST['user_id'] ?? '';

    if (empty($title) || empty($topic) || empty($created_by)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $result = $quiz->addQuiz($title, $topic, $description, $created_by);
    echo json_encode($result);
}
