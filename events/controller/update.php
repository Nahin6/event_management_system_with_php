<?php
session_start();
include '../../config/database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id'];

    try {
        // Fetch the event
        $sql = "SELECT * FROM events WHERE id = :event_id AND created_by = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['error'] = "Event not found.";
            header("Location: ../list.php");
            exit;
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $location = $_POST['location'];
            $max_capacity = $_POST['max_capacity'];
            $image = $_FILES['image'] ?? null;

            // Image upload handling
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = basename($image['name']);
                $targetPath = "../uploads/events/" . $imageName;

                if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                    // Delete the previous image if a new one is uploaded
                    if ($event['image'] && file_exists("../uploads/events/" . $event['image'])) {
                        unlink("../uploads/events/" . $event['image']);
                    }
                } else {
                    $_SESSION['error'] = "Failed to upload image.";
                    header("Location: ../edit_event.php?id=" . $event_id);
                    exit;
                }
            } else {
                // If no new image, keep the old one
                $imageName = $event['image'];
            }

            try {
                // Update the event details
                $updateSql = "UPDATE events SET name = :name, location = :location, max_capacity = :max_capacity, image = :image WHERE id = :event_id AND created_by = :user_id";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':name', $name, PDO::PARAM_STR);
                $updateStmt->bindParam(':location', $location, PDO::PARAM_STR);
                $updateStmt->bindParam(':max_capacity', $max_capacity, PDO::PARAM_INT);
                $updateStmt->bindParam(':image', $imageName, PDO::PARAM_STR);
                $updateStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $updateStmt->execute();

                $_SESSION['success'] = "Event updated successfully!";
                header("Location: ../list.php");
                exit;

            } catch (PDOException $e) {
                $_SESSION['error'] = "Error updating event: " . htmlspecialchars($e->getMessage());
                header("Location: ../edit.php?id=" . $event_id);
                exit;
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error fetching event: " . htmlspecialchars($e->getMessage());
        header("Location: ../list.php");
        exit;
    }
} else {
    $_SESSION['error'] = "No event ID provided.";
    header("Location: ../list.php");
    exit;
}
?>
