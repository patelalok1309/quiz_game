<?php 
include("./controllers/QuizController.php");
$quiz = new QuizController();
$quizzes = $quiz->getQuizzes();

if(!$_SESSION['user_name'] || !$_SESSION['role'] || !$_SESSION['user_id'] ) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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

   <!-- Navbar -->
   <?php include('./components/navbar.php'); ?> 

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

