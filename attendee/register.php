<?php
session_start(); 
$pageTitle = "Add Attendee";
ob_start();

include '../config/database.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

$user_id = $_SESSION['user']['id'];

try {
    $sql = "SELECT id, name FROM events WHERE created_by = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='text-danger'>Error fetching events: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
<div class="container mt-5">
  <h2 class="text-center">Register new attendee</h2>
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
    <form action="controller/store.php" method="POST" id="attendee-reg-form" onsubmit="return validateAttendeeRegForm(event)">

      <div class="mb-3">
        <label for="name" class="form-label">Attendee Name <b class="text-danger">*</b></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="name">
          <div id="name-error" class="error-text"></div>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Attendee Phone <b class="text-danger">*</b></label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="phone">
          <div id="phone-error" class="error-text"></div>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Attendee Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="email" >
      </div>
      <div class="mb-3">
        <label for="event" class="form-label">Event <b class="text-danger">*</b></label>
        <select id="event" class="form-select form-control" name="event_id">
          <option value="" selected disabled>Select an event</option>
          <?php foreach ($events as $event): ?>
            <option value="<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['name']) ?></option>
          <?php endforeach; ?>
        </select>
          <div id="event-error" class="error-text"></div>
      </div>
      <div class="text-center">
          <button type="submit" class="btn btn-success w-50 text-center">Submit</button>
      </div>
    </form>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../includes/layout.php';
?>
