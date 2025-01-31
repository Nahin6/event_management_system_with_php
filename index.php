<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Management System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Event Manager</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link fw-semibold" href="events/create.php">Create Event</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="auth/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="auth/register.php">Registration</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="auth/logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

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
            <p class="card-text display-6 text-secondary">12</p>
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
            <p class="card-text display-6 text-secondary">54</p>
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
            <p class="card-text display-6 text-secondary">86%</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; 2025 Event Management System. All rights reserved.
  </footer>


</body>
</html>
