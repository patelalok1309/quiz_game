<?php
include("./controllers/QuizController.php");

$urlPath = $_SERVER['REQUEST_URI']; // Get full request URI
$segments = explode('/', rtrim($urlPath, '/')); // Split by "/"
$quizId = end($segments); // Get last segment

$quiz = new QuizController();
$questions = $quiz->getQuestionsByQuizId($quizId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="bi bi-plus-circle"></i> Add Question
        </button>

        <table id="questionsTable" class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question Text</th>
                    <th>Question Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($questions as $index => $question) {
                    echo "<tr>
                            <td>" . ($index + 1) . "</td>
                            <td>{$question['question_text']}</td>
                            <td>{$question['question_type']}</td>
                            <td>
                                <button class='btn btn-danger btn-sm delete-question' data-id='{$question['question_id']}'>Delete</button>
                                <a href='/quiz-api/question_details.php/{$question['question_id']}' class='btn btn-secondary btn-sm'>Manage</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Question Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuestionModalLabel">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addQuestionForm">
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <input type="text" class="form-control" id="question_text" name="question_text" required>
                        </div>
                        <div class="mb-3">
                            <label for="question_type" class="form-label">Question Type</label>
                            <select class="form-control" id="question_type" name="question_type" required>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="true_false">True/False</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="option1" class="form-label">Option 1</label>
                            <input type="text" class="form-control" id="option1" name="option1" required>
                        </div>
                        <div class="mb-3">
                            <label for="option2" class="form-label">Option 2</label>
                            <input type="text" class="form-control" id="option2" name="option2" required>
                        </div>
                        <div class="mb-3">
                            <label for="option3" class="form-label">Option 3</label>
                            <input type="text" class="form-control" id="option3" name="option3">
                        </div>
                        <div class="mb-3">
                            <label for="option4" class="form-label">Option 4</label>
                            <input type="text" class="form-control" id="option4" name="option4">
                        </div>
                        <div class="mb-3">
                            <label for="answer" class="form-label">Answer</label>
                            <input type="text" class="form-control" id="answer" name="answer" required>
                        </div>
                        <input type="hidden" id="quiz_id" name="quiz_id" value="1"> <!-- Dynamic Quiz ID -->
                        <button type="submit" class="btn btn-success">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $("#questionsTable").DataTable();

            // Handle Add Question form submission
            $("#addQuestionForm").submit(function(e) {
                e.preventDefault();

                let formData = {
                    quiz_id: $("#quiz_id").val(),
                    question_text: $("#question_text").val(),
                    question_type: $("#question_type").val(),
                    options: JSON.stringify([
                        $("#option1").val(),
                        $("#option2").val(),
                        $("#option3").val(),
                        $("#option4").val()
                    ]),
                    answer: $("#answer").val()
                };

                $.ajax({
                    url: "../add_questions.php",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        console.log("response", response);
                        let res = response;
                        if (res.success) {
                            alert("Question Added Successfully!");
                            location.reload();
                        } else {
                            alert("Error: " + es.message);
                        }
                    },
                    error: function() {
                        alert("Failed to add question.");
                    }
                });
            });
        });
    </script>

</body>

</html>
