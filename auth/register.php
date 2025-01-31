<?php require_once '../config/database.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <h2 class="text-center">Create an Account</h2>

            <div id="error-messages" class="text-center"></div>
            <form action="controller/authHandler.php" method="POST" class="mt-4" id="registration-form"
                onsubmit="validateRegistrationForm(event)">
                <input type="hidden" name="action" value="register">

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="username" placeholder="John Doe"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>">
                        <div id="name-error" class="error-text"></div>
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
                        <div id="email-error" class="error-text"></div>
                    <?php if (isset($_SESSION['email_error'])): ?>
                        <div class="error-text"><?php echo $_SESSION['email_error']; ?></div>
                        <?php unset($_SESSION['email_error']); ?>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********">
                    <div id="password-error" class="error-text"></div>
                </div>

                <!-- General Error -->
                <?php if (isset($_SESSION['general_error'])): ?>
                    <div class="text-danger text-center"><?php echo $_SESSION['general_error']; ?></div>
                    <?php unset($_SESSION['general_error']); ?>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-block auth-btn">Register</button>
                <p class="text-center mt-3">Already have an account? <a href="login.php" class="form-link">Login</a></p>
            </form>
        </div>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>