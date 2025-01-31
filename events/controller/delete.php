<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id'];
    $user_id = $_SESSION['user']['id'];

    try {
        $deleteSql = "DELETE FROM events WHERE id = :event_id AND created_by = :user_id";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        $_SESSION['success'] = "Event deleted successfully!";
        header("Location: ../list.php");
        exit;
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error deleting event: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request.</p>";
}
?>
