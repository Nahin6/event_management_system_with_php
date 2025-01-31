<?php
session_start();
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];
    $username = trim($_POST['username']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username)) {
        $_SESSION['error'] = "Username cannot be empty.";
        header("Location: ../profile.php");
        exit();
    }

    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters long.";
            header("Location: ../profile.php");
            exit();
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: ../profile.php");
            exit();
        }
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->execute(['username' => $username, 'id' => $userId]);

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
        }

        $conn->commit();

        // Update session with new username
        $_SESSION['user']['username'] = $username;
        $_SESSION['success'] = "Profile updated successfully!";
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Failed to update profile. Please try again.";
    }

    header("Location: ../profile.php");
    exit();
}
