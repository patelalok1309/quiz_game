<div class="container mt-4">

USRES
            <table id="quizTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sample PHP code for fetching questions (Replace with actual database logic)
                    $questions = [
                        ["id" => 1, "question" => "What is PHP?", "category" => "Programming"],
                        ["id" => 2, "question" => "What is HTML?", "category" => "Markup"],
                    ];
                    foreach ($questions as $q) {
                        echo "<tr>
                                <td>{$q['id']}</td>
                                <td>{$q['question']}</td>
                                <td>{$q['category']}</td>
                                <td><button class='btn btn-danger btn-sm'>Delete</button></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>