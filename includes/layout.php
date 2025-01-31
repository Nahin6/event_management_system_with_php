<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?php echo $pageTitle ?? 'Event Management system'; ?></title>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <?php echo $content ?? ''; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>