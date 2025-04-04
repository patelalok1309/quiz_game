<?php
include('./config/Database.php');

$db = new Database();
$conn = $db->connect();

// Fetch result data
$resultId = isset($_GET['result_id']) ? intval($_GET['result_id']) : 0;
$sql = "SELECT * FROM user_attermpts WHERE id = :result_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':result_id', $resultId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// print_r($_SESSION);
// die();

// $quizSql = "SELECT * FROM quizzes WHERE id = :quiz_id";
// $quizStmt = $conn->prepare($quizSql);   
// $quizStmt->bindParam(':quiz_id', $result['quiz_id'], PDO::PARAM_INT);
// $quizStmt->execute();
// $quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);
// echo "<pre>";
// print_r($quiz);
// echo "</pre>";
// die();

// User Session Data
$userName = ucwords($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
        }

        .user-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .card {
            border-radius: 10px;
            background-color: #eef2f3;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include('./components/navbar.php'); ?>

    <!-- Main Container -->
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h2 class="fw-bold">Congratulations</h2>
                <p>You've completed the Quiz with the score of <?= $result['correct']; ?> out of <?= $result['total']; ?></p>
                <p><strong>Correct Answers:</strong> <?= $result['correct']; ?></p>
                <p><strong>Wrong Answers:</strong> <?= $result['wrong']; ?></p>
                <p><strong>Total:</strong> <?= $result['total']; ?></p>
                <p class="text-muted">You can review your submission below. Keep quizzing!</p>
            </div>
        </div>

        <!-- Summary Section -->
        <h3 class="text-center mt-4">Summary</h3>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <canvas id="quizChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var ctx = document.getElementById('quizChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [<?= $result['correct']; ?>, <?= $result['wrong']; ?>, <?= $result['total'] - ($result['correct'] + $result['wrong']); ?>],
                        backgroundColor: ['#1E90FF', '#FF5733', '#4CAF50']
                    }],
                    labels: ['Correct Answers', 'Wrong Answers', 'Un-attempted']
                },
                options: {
                    plugins: {
                        labels: {
                            render: 'percentage',
                            fontSize: 14,
                            fontColor: '#fff',
                            precision: 0
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>