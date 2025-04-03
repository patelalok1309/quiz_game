<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .list-group {
            margin-top: 2rem;
        }

        .quiz-container {
            display: flex;
            width: 100%;
            height: 100vh;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 25%;
            background: #d3d3d3;
            padding: 20px;
            position: relative;
        }

        .main-content {
            width: 75%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .question-box {
            background: #eee;
            padding: 20px;
            border-radius: 10px;
        }

        .quiz-option {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border: 1px solid #ccc;
        }

        .quiz-option:hover {
            background: #ddd;
        }

        .quiz-option.selected {
            background: #007bff;
            color: white;
        }

        .question-link {
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            background: #bbb;
            margin-bottom: 5px;
            display: block;
            transition: 0.3s;
        }

        .question-link.active {
            background: #007bff;
            color: white;
        }

        .bottom-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 70%;
                position: fixed;
                left: -100%;
                top: 0;
                height: 100vh;
                background: #d3d3d3;
                box-shadow: 3px 0px 10px rgba(0, 0, 0, 0.2);
                transition: 0.3s;
                z-index: 1000;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                width: 100%;
            }

            .hamburger {
                font-size: 24px;
                background: none;
                border: none;
                cursor: pointer;
                top: 10px;
                left: 10px;
                z-index: 999;
            }

            .close-btn {
                font-size: 24px;
                background: none;
                border: none;
                cursor: pointer;
                position: absolute;
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="quiz-container">
        <div class="sidebar">
            <button class="close-btn d-md-none">✖</button> <!-- Close Button -->
            <ul id="questionList" class="list-group"></ul>
        </div>

        <div class="main-content">
            <div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-center gap-x-1 align-items-center">
                        <button class="hamburger d-md-none">☰</button> <!-- Hamburger Button -->
                        <h4 style="margin-left: 1rem;" class="ml-2">
                            Question <span id="questionNumber">1</span>
                        </h4>
                    </div>
                    <p> 👤 <?php echo ucwords($_SESSION["user_name"]) ?></p>
                </div>
                <div id="questionBox" class="question-box">
                    <h5 id="questionText">Loading...</h5>
                </div>
                <div id="optionsContainer" class="mt-3"></div>
            </div>

            <div class="bottom-controls">
                <div>
                    <button id="prevBtn" class="btn btn-secondary" disabled>Previous</button>
                    <button id="nextBtn" class="btn btn-secondary">Next</button>
                </div>
                <button id="submitBtn" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const url = window.location.href;
            const quizId = url.substring(url.lastIndexOf('/') + 1);
            let questions = [];
            let currentQuestionIndex = 0;
            let selectedAnswers = {};

            $.ajax({
                url: `../fetch_questions.php?quiz_id=${quizId}`,
                type: 'GET',
                success: function(res) {
                    const data = JSON.parse(res);
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    console.log("data", data);
                    questions = data;
                    loadSidebar();
                    loadQuestion(0);
                },
                error: function() {
                    alert("Error loading quiz data.");
                }
            });

            function loadSidebar() {
                let listHTML = "";
                questions.forEach((q, index) => {
                    listHTML += `<li class="list-group-item question-link" data-index="${index}">${index + 1}. ${q.question_text.substring(0, 20)}...</li>`;
                });
                $("#questionList").html(listHTML);

                $(".question-link").click(function() {
                    loadQuestion($(this).data("index"));
                    $(".sidebar").removeClass("open"); // Close sidebar on mobile after selecting a question
                });
            }

            function loadQuestion(index) {
                if (index < 0 || index >= questions.length) return;

                currentQuestionIndex = index;
                $("#questionNumber").text(index + 1);
                $("#questionText").text(questions[index].question_text);

                let optionsHTML = "";
                questions[index].options.forEach((option, i) => {
                    const isSelected = selectedAnswers[index] === i;
                    optionsHTML += `<div class="quiz-option ${isSelected ? 'selected' : ''}" data-index="${i}">(${String.fromCharCode(97 + i)}) ${option}</div>`;
                });
                $("#optionsContainer").html(optionsHTML);

                $(".quiz-option").click(function() {
                    selectedAnswers[currentQuestionIndex] = $(this).data("index");
                    loadQuestion(currentQuestionIndex);
                });

                $(".question-link").removeClass("active");
                $(".question-link").eq(index).addClass("active");
                $("#prevBtn").prop("disabled", currentQuestionIndex === 0);
                $("#nextBtn").prop("disabled", currentQuestionIndex === questions.length - 1);
            }

            $("#prevBtn").click(function() {
                loadQuestion(currentQuestionIndex - 1);
            });

            $("#nextBtn").click(function() {
                loadQuestion(currentQuestionIndex + 1);
            });

            $("#submitBtn").click(function() {
                let userId = <?php echo json_encode($_SESSION['user_id']); ?>;
                let attemptId = Math.floor(Math.random() * 100000); // Generate a unique attempt ID (Change this as needed)

                let answers = [];
                questions.forEach((q, index) => {
                    if (selectedAnswers[index] !== undefined) {
                        answers.push({
                            question_id: q.id,
                            answer: q.options[selectedAnswers[index]] // Store selected answer
                        });
                    }
                });

                console.log("quiz_id:", quizId);
                console.log("user_id:", userId);
                console.log("attempt_id:", attemptId);
                console.log("answers:", answers);

                // Send AJAX request
                $.ajax({
                    url: "../calculate_score.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        quiz_id: quizId,
                        user_id: userId,
                        attempt_id: attemptId,
                        answers: answers
                    }),
                    success: function(response) {
                    console.log(response);
                        if (response.success) {
                            window.location.href = `/quiz-api/result.php?result_id=${response.result_id}`;
                        } else {
                            alert(response.message || "Failed to calculate score.");
                        }
                    },
                    error: function() {
                        alert("Error submitting quiz.");
                    }
                });
            });


            // Sidebar Toggle Logic
            $(".hamburger").click(function() {
                $(".sidebar").addClass("open");
            });

            $(".close-btn").click(function() {
                $(".sidebar").removeClass("open");
            });
        });
    </script>

</body>

</html>