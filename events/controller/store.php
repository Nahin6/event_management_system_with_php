<?php
session_start();

include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $description = htmlspecialchars($_POST['description']);
    $max_capacity = intval($_POST['max_capacity']);
    $image = null;

    if (empty($name) || empty($location) || empty($description) || empty($max_capacity)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../add.php"); 
        exit();
    }

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        if ($image_size > 5242880) {
            $_SESSION['error'] = "Image size should not exceed 5MB!";
            header("Location: ../add.php");
            exit();
        }

        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_ext, $allowed_exts)) {
            $_SESSION['error'] = "Invalid image format! Allowed formats: jpg, jpeg, png, gif.";
            header("Location: ../add.php");
            exit();
        }
        $new_image_name = basename($image_name);
        $uploadDir = "../uploads/events/"; 
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); 
        }
        $image_path =$uploadDir . $new_image_name;

        if (!move_uploaded_file($image_tmp, $image_path)) {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: ../add.php");
            exit();
        }

        $image = $new_image_name;
    }

    try {
        $conn->beginTransaction();
    
        if (!$conn->inTransaction()) {
            throw new Exception("Transaction did not start!");
        }
        $user_id = $_SESSION['user']['id'] ?? null; 
        if (!$user_id) {
            $_SESSION['error'] = "User is not logged in!";
            header("Location: ../add.php");
            exit();
        }
        
        $sql = "INSERT INTO events (name, location, description, max_capacity, image, created_by) 
        VALUES (:name, :location, :description, :max_capacity, :image, :created_by)";

        
        $stmt = $conn->prepare($sql);
    
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':max_capacity', $max_capacity, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $conn->commit();
            $_SESSION['success'] = "Event created successfully!";
            header("Location: ../list.php");
        } else {
            throw new Exception("Query execution failed!");
        }
    } catch (Exception $e) {
        $conn->rollBack();
        error_log($e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../add.php");
    }
    exit();
}
?>
