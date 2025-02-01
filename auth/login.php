<?php require_once '../config/database.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
   
   body {
       display: flex;
       justify-content: center;
       align-items: center;
       height: 100vh;
       background-color: #eef2f3;
       font-family: "Arial", sans-serif;
   }

</style>
</head>

<body>
  <div class="form-container">
    <div class="card form-card">
      <h2 class="text-center">Login to Your Account</h2>

      <div id="error-messages" class="text-center"></div>
      <form action="controller/authHandler.php" method="POST" class="mt-4" id="login-form" onsubmit="validateLoginForm(event)">
        <input type="hidden" name="action" value="login">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com">
          <div id="email-error" class="error-text"></div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="********">
          <div id="password-error" class="error-text"></div>
        </div>
       
        <?php if (isset($_SESSION['error'])): ?>
          <div class="login-error-text"><?php echo $_SESSION['error']; ?></div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary btn-block auth-btn">Login</button>
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
      </form>
    </div>
  </div>
  <script src="../assets/js/scripts.js"></script>
</body>

</html>