<?php

require_once __DIR__ . "/../config/Database.php";

class AuthController
{

    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($username, $email, $password)
    {
        if (empty($username) || empty($email) || empty($password)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        $username = htmlspecialchars($username);
        $email = htmlspecialchars($email);
        $password = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        try {
            $stmt->execute();
            return ["status" => "success", "message" => "Registration successful! <a href='login.php'>Login here</a>"];
        } catch (\Throwable $th) {
            $error_code = $th->getCode();

            // Integrity Error code
            if ($error_code == 23000) {
                return ["status" => "error", "message" => "Email already exists! <a href='login.php'>Login here</a>"];
            }
            return ["status" => "error", "message" => "Something went wrong!"];
        }
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return ["status" => "error", "message" => "Email and password are required."];
        }

        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);

        $stmt = $this->conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bindParam(1, $email);
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && password_verify($password, $result['password'])) {
                $_SESSION["user_id"] = $result['id'];
                $_SESSION["user_name"] = $result['username'];
                $_SESSION["role"] = $result['role'];
                return ["status" => "success", "message" => "Login successful!"];
            } else {
                return ["status" => "error", "message" => "Invalid email or password."];
            }
        } catch (\Throwable $th) {
            return ["status" => "error", "message" => "Invalid email or password."];
        }
    }

    public function logout()
    {
        session_destroy();
        return ["status" => "success", "message" => "Logout successful!"];
    }

    public function isAuthenticated()
    {
        return isset($_SESSION["user_id"]);
    }

    public function changePasssword($email, $oldPassword, $newPassword)
    {
        if (empty($email) || empty($oldPassword) || empty($newPassword)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        $email = htmlspecialchars($email);
        $oldPassword = htmlspecialchars($oldPassword);
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($oldPassword, $result['password'])) {
            $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            $stmt->bindParam(":password", $newPassword);
            $stmt->bindParam(":email", $email);

            if ($stmt->execute()) {
                return ["status" => "success", "message" => "Password changed successfully!"];
            } else {
                return ["status" => "error", "message" => "Error changing password."];
            }
        } else {
            return ["status" => "error", "message" => "Invalid email or old password."];
        }
    }

    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role FROM users");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["status" => "success", "data" => $result];
    }
}

