<?php

include 'config/database.php';

try {
  $sql = "SELECT id, name FROM events ";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "<p class='text-danger'>Error fetching events: " . htmlspecialchars($e->getMessage()) . "</p>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Registration Landing Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      color: #333;
      background-color: #f9f9f9;
    }
    .btn-primary {
  background-color: #00b894;
  border-color: #00b894;
  font-size: 1.1rem;
  font-weight: 600;
}
.btn-primary:hover {
  background-color: #4CAF50;
  border-color: #4CAF50;
}
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="../user/dashboard.php">Event Management</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="auth/register.php">Create an
              Account</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="auth/login.php">Login</a></li>

        </ul>
      </div>
    </div>
  </nav>
  <section class="hero text-center">
    <div class="container">
      <h1>Welcome to the Ultimate Event Experience</h1>
      <p>Join us to explore incredible events and register with ease.</p>
      <a href="#registration" class="btn btn-primary mt-3 px-4 py-2">Register Now</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features text-center">
    <div class="container">
      <h2>Why Choose Us?</h2>
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="feature-box p-4">
            <i class="fas fa-calendar-check mb-3"></i>
            <h4>Seamless Registration</h4>
            <p>Effortlessly register for events with our user-friendly interface.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-box p-4">
            <i class="fas fa-users mb-3"></i>
            <h4>Wide Variety of Events</h4>
            <p>From tech conferences to art festivals, we have something for everyone.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-box p-4">
            <i class="fas fa-chart-line mb-3"></i>
            <h4>Detailed Analytics</h4>
            <p>Get insights and reports about event attendees and statistics.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Registration Section -->
  <section id="registration" class="py-5">
    <div class="container">
      <h2 class="text-center mb-4">Register for an Event</h2>
      <form id="registerUserAttendeeForm" class="bg-white p-4 shadow rounded">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" >
          </div>
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter your phone number"
              required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="event_id" class="form-label">Select Event</label>
            <select id="event_id" name="event_id" class="form-select" required>
              <option value="" disabled>Choose an event...</option>
              <?php foreach ($events as $event): ?>
                <option value="<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">Submit Registration</button>
      </form>
    </div>
  </section>
  <section class="create-account-section text-center">
    <div class="container">
      <h2>Want to manage your own Events?</h2>
      <div class="row mt-2">
        <div class="col-md-12">
          <div class="feature-box p-4">
            <i class="fas fa-user-check mb-3"></i>
            <h4>Create an Account <a class="create-account-btn" href="auth/register.php">Now</a> </h4>
            <p>Or if you have an Account already you can login from <a class="create-account-btn"
                href="auth/login.php">Here</a>.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- jQuery (Toastr requires jQuery) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    document.getElementById('registerUserAttendeeForm').addEventListener('submit', function (e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch('userEventRegistration.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(resp => {
          if (resp.success) {
            console.log(resp.message);
            
            toastr.success(resp.message);
            document.getElementById('registerUserAttendeeForm').reset();
          } else {
            toastr.error('Error: ' + resp.message);
          }
        })
        .catch(error => {
          toastr.error('Error: ' + error);
          console.log('Error: ' + error);

        }
        );
    });
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-center",
      timeOut: 3000
    };
  </script>
</body>

</html>