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
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand fw-bold fs-4 text-light">Quiz</a>
        <div class="d-flex align-items-center gap-2">
            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAMAAzAMBIgACEQEDEQH/xAAbAAEAAwEBAQEAAAAAAAAAAAAAAwQFAgEGB//EADMQAQACAQIEBQIEBAcAAAAAAAABAgMEEQUSITEyQVFhcRMiQlKBsWKRwdEUFSNDU5Kh/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AP3EAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAcWyRHbrKK2WZ7dPgE8zEebmclfWFaf1AWPq19Z/kfUrPn/ADVwFqLRPaYdKbqt5r2mQWhDXN6xsli0TG8SD0AAAAAAAAAAAAHlpiI6gTMRG8oL5JnpHSHl780+zj5B7u8AAAAAAAB1W01no5AWKZIt8pFON47J8WTfuCUAAAAAAAAABXy35p6dkma20bQrgAAAdu/SAIV82tw4pmJmbTHlVS1ustktOPFbbH5+6nMbg0Z4pXfpinb3l3TiWKfFS9fdlmwN/HkplrvjtFo9nTBxZL4b82O3LLX0mojUU37WjxQCcAB7HR4As47b193arjty2WYnfYHoAAAAAAOck8tJkFe881plyAAACnxLNyYYx1na1+/wuMjic76nafw1gFXz3AAAAS6bLOHPW+/Tzj1hEee4PoYmJjePMRaS3Npsc+yUAABYwzvXb0V0mGdrbeoLAAAAAACLP4f1Soc/4QQgAAAMnileXVc09prDWVOJYefDF6+Kvf4BkgAAAE9pEumwznz1p5d7T6QDX0leTTY4/hSkRERtHaOkAAADqs/dE+7l7HeAWx5D0AAAABDn8kyLP4N/cEAAAAAAMvWaK1N8mLrTfeY84Ut+u3m+hQZtJhy9Zrtb1gGKNKeF0memW0fpDunDsUT902sDNx4r5bbY67z+zY0umrp6cve0x1skpjpjrtjrERDoD4AAAAex3h46pG9o+QWYegAAAAA5yRzUmHQCmOskbXlyAAACjquIRT7MG02/N6Au3vWlea9orHurZNfgr0ibW+IZV7XyW5sl7Wn3c9QaU8Tr2jFbb5dV4li86Wj33ZYDbxarBl6UyRv6T0TR1nZ89Mb91nT6zLh6TPPT8sg2BHgz481ObHP6eiUHgACTDG9unk4T4Y2rv6gkAAAAAAABHmrvG/orrcoMtNp3gEYINbn+hgmY8U9Kgq8R1c9cOKdo/FaGf8djr59wAAAAAAHeLLbDk56T1jy37trBmpnxxek9P2lhTEStaDP9LPET4LdJBrgdZ7dwdUrzW2WnGOvLHvLsAAAAAAAAB5aN4egK16cssjil+bNFN/BH7t+YiY2licR0OWua+bH99bd9u8AzggAAAAAAABJhw5M1+XFSbT/5ANnTXnJgx27zMdflbx49p3lFoNN/hsEVtO9u60AAAAAAAAAAAAAACnquH4NRvMRyX/NX+rLz8Mz4u0fUr61fQAPk7VtSfurNfl4+qvSto2tWJifWEF9Bpb+LDWPjp+wPnBv/AOV6T/jn/tL2OGaSP9rf5tIPn5mIS4dLqMs/6eOZifOekPoqaXBj60xUj4hLEAydPwiN99Rff+Gv92pixY8VIrjrFa+zsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB/9k=" alt="User Icon" class="user-icon">
            <span class="fw-semibold text-light"><?= htmlspecialchars($userName); ?></span>
        </div>
    </nav>

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