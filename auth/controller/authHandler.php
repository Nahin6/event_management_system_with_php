<?php

include $_SERVER['DOCUMENT_ROOT'] . '/event_management_system/config/database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        if (empty($username) ) {
            $_SESSION['username_error'] = 'Name is required.';
            header("Location: ../register.php");
            exit();
        }
        try {
            $query ="INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
            header("Location: ../login.php");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'username') !== false) {
                    $_SESSION['username_error'] = 'Username already exists. Please choose another one.';
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    $_SESSION['email_error'] = 'Email already registered. Please use another email.';
                }
            } else {
                $_SESSION['general_error'] = 'Registration failed. Please try again.';
            }
            header("Location: ../register.php");
            exit();
        }
    } elseif ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $query ="SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /event_management_system/user/dashboard.php");
        } else {
            $_SESSION['error'] = 'Invalid email or password. Please try again.';
            header("Location: ../login.php");
        }
    }
}
?>
