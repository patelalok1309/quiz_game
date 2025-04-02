<?php
include("./controllers/QuizController.php");

$quiz = new QuizController();
$quizzes = $quiz->getQuizzes();
?>

<div class="tab-content" id="quizzes">
    <div class="container mt-4">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuizModal">
            <i class="bi bi-plus-circle"></i> Add Quiz
        </button>
        <table id="quizTable" class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Topic</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = array_map(fn($q, $index) => "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>{$q['title']}</td>
                    <td>{$q['topic']}</td>
                    <td>{$q['username']}</td>
                    <td>
                        <button class='btn btn-danger btn-sm delete-quiz' data-id='{$q['id']}'>Delete</button>
                        <a href='/quiz-api/quiz_questions.php/{$q['id']}' class='btn btn-secondary btn-sm'>Manage</a>
                    </td>
                </tr>", $quizzes, array_keys($quizzes));
                echo implode("\n", $rows);
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuizModalLabel">Add New Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quizForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="topic" class="form-label"> Topic</label>
                        <select class="form-control" id="topic" name="topic" required>
                            <option value="">-- Select Topic --</option>
                            <option value="maths">Maths</option>
                            <option value="science">Science</option>
                            <option value="sports">Sports</option>
                            <option value="history">History</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <button type="submit" class="btn btn-success">Save Quiz</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#quizForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/quiz-api/routes/api.php?action=add_quiz",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    let res = JSON.parse(response);
                    console.log(res);
                    if (res["success"]) {
                        alert("Quiz Added Successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + res.message);
                    }
                },
                error: function() {
                    alert("Failed to add quiz.");
                }
            });
        });

        $(".delete-quiz").click(function() {
            let quizId = $(this).data("id");
            if (!confirm("Are you sure you want to delete this quiz?")) return;

            $.ajax({
                url: "delete_quiz.php",
                type: "POST",
                data: {
                    quiz_id: quizId
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alert("Quiz Deleted Successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + res.message);
                    }
                },
                error: function() {
                    alert("Failed to delete quiz.");
                }
            });
        });
    });
</script>

