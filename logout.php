<?php
session_start();
header("Location: /quiz-api/login.php", true);
session_destroy();
?>