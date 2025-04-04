<?php
include("./controllers/AuthController.php");

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $auth = new AuthController();
    $users = $auth->getAllUsers();

    echo json_encode($users);
}
