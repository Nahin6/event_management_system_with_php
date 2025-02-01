  <?php
  include '../config/database.php';

  class Dashboard
  {
    private $conn;
    private $user_id;

    public function __construct($db, $user_id)
    {
      $this->conn = $db;
      $this->user_id = $user_id;
    }

    // Get total events created by the user
    private function getTotalEvents()
    {
      $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_events FROM events WHERE created_by = :user_id");
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC)['total_events'] ?? 0;
    }

    // Get total attendees for all events created by the user
    private function getTotalAttendees()
    {
      $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_attendees FROM attendees 
                                        WHERE event_id IN (SELECT id FROM events WHERE created_by = :user_id)");
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC)['total_attendees'] ?? 0;
    }

    // Get event capacity details
    private function getEventCapacity()
    {
      $stmt = $this->conn->prepare("SELECT SUM(max_capacity) AS total_capacity, SUM(current_capacity) AS current_capacity 
                                        FROM events WHERE created_by = :user_id");
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();
      $event_capacity = $stmt->fetch(PDO::FETCH_ASSOC);

      $total_capacity = $event_capacity['total_capacity'] ?? 0;
      $current_capacity = $event_capacity['current_capacity'] ?? 0;
      $capacity_percentage = ($total_capacity > 0) ? round(($current_capacity / $total_capacity) * 100, 2) : 0;

      return ['total_capacity' => $total_capacity, 'current_capacity' => $current_capacity, 'capacity_percentage' => $capacity_percentage];
    }

    // Get top event with most attendees
    private function getTopEvent()
    {
      $stmt = $this->conn->prepare("SELECT events.name, COUNT(attendees.id) AS attendee_count 
                                        FROM attendees 
                                        JOIN events ON attendees.event_id = events.id
                                        WHERE events.created_by = :user_id
                                        GROUP BY attendees.event_id 
                                        ORDER BY attendee_count DESC 
                                        LIMIT 1");
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();
      $top_event = $stmt->fetch(PDO::FETCH_ASSOC);

      return [
        'name' => $top_event['name'] ?? 'No Event',
        'attendee_count' => $top_event['attendee_count'] ?? 0
      ];
    }

    // Calculate average attendees per event
    private function getAverageAttendees($total_events, $total_attendees)
    {
      return ($total_events > 0) ? round($total_attendees / $total_events, 2) : 0;
    }

    // Fetch recent registrations
    private function getRecentRegistrations()
    {
      $stmt = $this->conn->prepare("SELECT attendees.name, events.name AS event_name, attendees.registered_at 
                                        FROM attendees 
                                        JOIN events ON attendees.event_id = events.id
                                        WHERE events.created_by = :user_id
                                        ORDER BY attendees.registered_at DESC
                                        LIMIT 5");
      $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all dashboard data
    public function getDashboardData()
    {
      $total_events = $this->getTotalEvents();
      $total_attendees = $this->getTotalAttendees();
      $event_capacity = $this->getEventCapacity();
      $top_event = $this->getTopEvent();
      $average_attendees = $this->getAverageAttendees($total_events, $total_attendees);
      $recent_registrations = $this->getRecentRegistrations();

      return [
        'total_events' => $total_events,
        'total_attendees' => $total_attendees,
        'capacity_percentage' => $event_capacity['capacity_percentage'],
        'top_event' => $top_event,
        'average_attendees' => $average_attendees,
        'recent_registrations' => $recent_registrations
      ];
    }
  }

  session_start();
  $user_id = $_SESSION['user']['id'] ?? null;

  if (!$user_id) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
  }

  // Instantiate the Dashboard class
  $dashboard = new Dashboard($conn, $user_id);
  $data = $dashboard->getDashboardData();

  // storing data
  $total_events = $data['total_events'];
  $total_attendees = $data['total_attendees'];
  $capacity_percentage = $data['capacity_percentage'];
  $top_event_name = $data['top_event']['name'] ;
  $top_event_attendees = $data['top_event']['attendee_count'];
  $average_attendees = $data['average_attendees'];
  $recent_registrations = $data['recent_registrations'];

  ?>

<div class="container mt-5">
  <h2 class="text-center fw-bold text-primary mb-4">Dashboard</h2>
  <div class="row g-4">
    <!-- Total Events -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center">
          <i class="bi bi-calendar-event text-primary fs-1 mb-3"></i>
          <h5 class="card-title fw-bold">Total Events</h5>
          <p class="card-text display-6 text-secondary"><?= $total_events ?></p>
        </div>
      </div>
    </div>
    <!-- Total Attendees -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center">
          <i class="bi bi-people text-success fs-1 mb-3"></i>
          <h5 class="card-title fw-bold">Total Attendees</h5>
          <p class="card-text display-6 text-secondary"><?= $total_attendees ?></p>
        </div>
      </div>
    </div>
    <!-- Events Capacity -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center">
          <i class="bi bi-bar-chart-line text-warning fs-1 mb-3"></i>
          <h5 class="card-title fw-bold">Event Capacity Utilization</h5>
          <p class="card-text display-6 text-secondary"><?= $capacity_percentage ?>%</p>
        </div>
      </div>
    </div>
  </div>


  <div class="row g-4 mt-4">
    <!-- Average Attendees Per Event -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center">
          <i class="bi bi-bar-chart-line text-warning fs-1 mb-3"></i>
          <h5 class="card-title fw-bold">Avg. Attendees per Event</h5>
          <p class="card-text display-6 text-secondary"><?= $average_attendees ?></p>
        </div>
      </div>
    </div>

    <!-- Top Event -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <i class="bi bi-trophy text-info fs-1"></i>
          <h5 class="card-title fw-bold">Most Attended Event</h5>
          <p class="card-text display-6 text-secondary"><?= $top_event_name  ?></p>
          <p class="text-muted">Total Attendees: <?= $top_event_attendees ?></p>
        </div>
      </div>
    </div>

  </div>

  <!-- Recent Registrations -->
  <div class="mt-5">
    <h4 class="fw-bold text-primary">Recent Registrations</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Attendee</th>
          <th>Event</th>
          <th>Registered On</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recent_registrations)): ?>
          <?php foreach ($recent_registrations as $registration): ?>
            <tr>
              <td><?= htmlspecialchars($registration['name']) ?></td>
              <td><?= htmlspecialchars($registration['event_name']) ?></td>
              <td><?= date('M d, Y', strtotime($registration['registered_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center text-muted">No recent registrations.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../includes/layout.php';
?>