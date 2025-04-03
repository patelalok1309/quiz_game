<?php 
include("./controllers/QuizController.php");
$quiz = new QuizController();
$quizzes = $quiz->getQuizzes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #e3e3e3;
            padding: 15px;
        }
        .quiz-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .quiz-card {
            width: 250px;
            padding: 15px;
            background: #f8d7da;
            border-radius: 10px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .quiz-card h5 {
            font-weight: bold;
        }
        .rounded-icon {
            border-radius: 50%;
            background-color: #fff;
            width: 2rem;
            height: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <span class="fw-bold">Quiz</span>
        <span class="float-end d-flex align-items-center justify-content-center gap-2"><i class="bi bi-person rounded-icon"></i> <?php echo ucwords($_SESSION['user_name']); ?></span>
    </div>

    <div class="container">
        <div class="quiz-container" id="quizContainer">
            <?php foreach ($quizzes as $quiz) { ?>
                <div class="quiz-card" onclick="window.location.href='/quiz-api/quiz_playground.php/<?php echo $quiz['id']; ?>'">
                    <h5><?php echo $quiz['title']; ?></h5>
                    <p>Questions: 30</p>
                    <p>Topic: <?php echo $quiz['topic']; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>

