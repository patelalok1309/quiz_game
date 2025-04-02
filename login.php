<?php
include("./controllers/AuthController.php");
include("./components/alert.php");

$login_error = false;
unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $auth = new AuthController();
    $success = $auth->login($email, $password);


    if ($success['status'] == 'success') {
        $_SESSION['alert'] = [
            'type' => 'success', // success, danger, warning, info
            'message' => $success['message']
        ];

        print_r($success);
        print_r($_SESSION);
        $role = $_SESSION['role'];
         
        if($role == 'admin') {
            header("Location:/quiz-api/dashboard.php", true);
        }else{
            header("Location:/quiz-api/home.php", true);
        }
    } else {
        $login_error = true;
        $_SESSION['alert'] = [
            'type' => 'danger', // success, danger, warning, info
            'message' => $success['message']
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizVerse | Login</title>
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
                    <h3>Sign in to your account</h3>
                </div>
                <div class="auth-box p-4 bg-white rounded-md shadow">
                    <form action="./login.php" method="post">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control custom-input" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control custom-input" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                    </form>
                    <hr>
                    <div class="text-center mt-3">
                        New to QuizVerse ? <a href="register.php">Create an account</a>
                    </div>
                    <div class="text-center mt-1">
                        <a href="forgot-password.php">Forgot password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>