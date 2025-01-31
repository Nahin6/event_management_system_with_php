<?php
include '../config/database.php';

$pageTitle = "Dashboard";
ob_start();
session_start();
$user_id = $_SESSION['user']['id'] ?? null;

if (!$user_id) {
  die("User not logged in.");
}

// Fetch total events created by the user
$stmt = $conn->prepare("SELECT COUNT(*) AS total_events FROM events WHERE created_by = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$total_events = $stmt->fetch(PDO::FETCH_ASSOC)['total_events'] ?? 0;

// Fetch total attendees for all events created by the user
$stmt = $conn->prepare("SELECT COUNT(*) AS total_attendees FROM attendees 
                        WHERE event_id IN (SELECT id FROM events WHERE created_by = :user_id)");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$total_attendees = $stmt->fetch(PDO::FETCH_ASSOC)['total_attendees'] ?? 0;

$stmt = $conn->prepare("SELECT SUM(max_capacity) AS total_capacity, SUM(current_capacity) AS current_capacity 
                        FROM events WHERE created_by = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$event_capacity = $stmt->fetch(PDO::FETCH_ASSOC);
$total_capacity = $event_capacity['total_capacity'] ?? 0;
$current_capacity = $event_capacity['current_capacity'] ?? 0;
$capacity_percentage = ($total_capacity > 0) ? round(($current_capacity / $total_capacity) * 100, 2) : 0;
?>
<!-- Dashboard -->
<div class="container mt-5">
  <h2 class="text-center fw-bold text-primary mb-4">Dashboard</h2>
  <div class="row g-4">
    <!-- Total Events -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <div class="icon mb-3">
            <i class="bi bi-calendar-event text-primary fs-1"></i>
          </div>
          <h5 class="card-title fw-bold">Total Events</h5>
          <p class="card-text display-6 text-secondary"><?= $total_events ?></p>
        </div>
      </div>
    </div>
    <!-- Total Attendees -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <div class="icon mb-3">
            <i class="bi bi-people text-success fs-1"></i>
          </div>
          <h5 class="card-title fw-bold">Total Attendees</h5>
          <p class="card-text display-6 text-secondary"><?= $total_attendees ?></p>
        </div>
      </div>
    </div>
    <!-- Events Capacity -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <div class="icon mb-3">
            <i class="bi bi-bar-chart-line text-warning fs-1"></i>
          </div>
          <h5 class="card-title fw-bold">Events Capacity</h5>
          <p class="card-text display-6 text-secondary"><?= $capacity_percentage ?>%</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../includes/layout.php';
?>