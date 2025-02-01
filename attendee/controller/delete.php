<?php 
session_start();
include '../../config/database.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

if (isset($_GET['id']) && isset($_GET['event_id'])) {
    $attendee_id = (int)$_GET['id'];
    $event_id = (int)$_GET['event_id']; 

    try {
        $conn->beginTransaction();

        // Delete the attendee
        $deleteSql = "DELETE FROM attendees WHERE id = :attendee_id";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bindParam(':attendee_id', $attendee_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        $updateCapacitySql = "
            UPDATE events 
            SET current_capacity = GREATEST(0, current_capacity - 1) 
            WHERE id = :event_id
        ";
        $updateCapacityStmt = $conn->prepare($updateCapacitySql);
        $updateCapacityStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $updateCapacityStmt->execute();

        $conn->commit();

        $_SESSION['success'] = "Attendee deleted successfully!";
        header("Location: ../attendeeList.php?id=" . $event_id); 
        exit;
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<p class='text-danger'>Error deleting attendee: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request.</p>";
}
?>
