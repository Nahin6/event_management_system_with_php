<?php
session_start(); 
$pageTitle = "Add Event";
ob_start();

?>
<div class="container mt-5">
  <h2 class="text-center">Create Event</h2>
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
    <form action="controller/store.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Event Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="event name" required>
      </div>
      <div class="mb-3">
        <label for="name" class="form-label">Event Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="location" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Event Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="event description"
          required></textarea>
      </div>
      <div class="mb-3">
        <label for="max_capacity" class="form-label">Max Capacity</label>
        <input type="number" class="form-control" id="max_capacity" name="max_capacity" placeholder="maximum capacity"
          required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image">
      </div>
      <div class="text-center">
          <button type="submit" class="btn btn-primary w-50 text-center">Create Event</button>
      </div>
    </form>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../includes/layout.php';
?>