<?php
session_start();
$pageTitle = "Edit Event";
ob_start();
include '../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

$user_id = $_SESSION['user']['id'];

if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id'];

    try {
        $sql = "SELECT * FROM events WHERE id = :event_id AND created_by = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            echo "<p class='text-danger'>Event not found.</p>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error fetching event: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
}
?>

<div class="container mt-5">
  <h2 class="text-center">Update Event</h2>
  <div class="card p-4">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success">
        <?php echo $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger text-center">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
    <form action="controller/update.php?id=<?= htmlspecialchars($event_id) ?>" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Event Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Event name" value="<?= htmlspecialchars($event['name']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="location" class="form-label">Event Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Location" value="<?= htmlspecialchars($event['location']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Event Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Event description" required><?= htmlspecialchars($event['description']) ?></textarea>
      </div>
      <div class="mb-3">
        <label for="max_capacity" class="form-label">Max Capacity</label>
        <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="<?= htmlspecialchars($event['max_capacity']) ?>" placeholder="Maximum capacity" required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image">
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Previous Image</label>
        <img src='./uploads/events/<?= htmlspecialchars($event['image']) ?>' alt='Event Image' width='200'>
      </div>
      <div class="text-center">
          <button type="submit" class="btn btn-primary w-50 text-center">Update</button>
      </div>
    </form>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../includes/layout.php';
?>
