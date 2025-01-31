<?php   
ob_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
$user = $_SESSION['user'];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="../user/dashboard.php">Event Manager</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link fw-semibold" href="../events/add.php">Create Event</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold" href="../events/list.php">Event List</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold" href="../attendee/register.php">Register Attendee</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="../user/profile.php"><?php echo $user['username']; ?> </a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="../auth/logout.php">Logout</a></li>
            <?php else: ?>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="../auth/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link fw-semibold text-danger" href="../auth/register.php">Registration</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>