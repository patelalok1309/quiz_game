<?php
require_once __DIR__ . "/controllers/AuthController.php";
include("./components/alert.php");

$auth = new AuthController();
$success = "";
$signup_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $success = $auth->register($_POST['username'], $_POST['email'], $_POST['password']);

        if ($success['status'] == 'success') {
            header("Location: login.php", true);
            exit();
        } else {
            $signup_error = $success['message'];
            $_SESSION['alert'] = [
                'type' => 'danger', // success, danger, warning, info
                'message' => $success['message']
            ];
        }
    }
}


$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizVerse | Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>
    <?php echo showAlert(); ?>
    <div class="auth-container">
        <div class="row justify-content-center flex-column auth-box-wrapper">
            <div class="col-md-12 col-lg-12">
                <div class="d-flex flex-column justify-content-center align-items-center auth-header">
                    <img src="Images/Logo.png" alt="Logo" class="logo" width="64" height="64">
                    <h3>Create an account</h3>
                </div>
                <div class="auth-box p-4 bg-white rounded-md shadow">
                    <form action="./register.php" method="post">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                    </form>
                    <hr>
                    <div class="text-center mt-3">
                        Already have an account ? <a href="login.php">Login</a>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <small class="text-center text-secondary">
                            &copy; 2025 QuizVerse
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>