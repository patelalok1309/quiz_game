<?php 
session_start();
include("./config/Database.php");

$db = new Database();
$conn = $db->connect();

// Fetch user's quiz history
$sql = "SELECT 
            ua.id AS attempt_id,
            q.id AS quiz_id,
            q.title AS quiz_title,
            q.topic,
            u.username AS user_name,
            ua.correct,
            ua.wrong,
            ua.total,
            ua.attempted_at
        FROM user_attermpts ua
        JOIN quizzes q ON ua.quiz_id = q.id
        JOIN users u ON ua.user_id = u.id
        WHERE ua.user_id = :user_id
        ORDER BY ua.attempted_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include('./components/navbar.php') ?>

<div class="container mt-2">
    <h2 class="mb-4">Quiz History</h2>

    <table id="quizHistory" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Quiz Title</th>
                <th>Topic</th>
                <th>Correct</th>
                <th>Wrong</th>
                <th>Total</th>
                <th>Attempted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['quiz_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['topic']); ?></td>
                    <td><?php echo $row['correct']; ?></td>
                    <td><?php echo $row['wrong']; ?></td>
                    <td><?php echo $row['total']; ?></td>
                    <td class="timestamp"><?php echo $row['attempted_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#quizHistory').DataTable();

    // Convert timestamps to human-readable format
    $(".timestamp").each(function() {
        let timestamp = $(this).text();
        let date = new Date(timestamp);
        let formattedDate = date.toLocaleString("en-US", {
            weekday: "short",
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit"
        });
        $(this).text(formattedDate);
    });
});
</script>

</body>
</html>
