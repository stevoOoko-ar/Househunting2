<?php
include '../includes/config.php';
include '../includes/send_email.php';
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique reset code
        $reset_code = rand(100000, 999999); // 6-digit code

        // Store reset code in DB
        $update_query = "UPDATE users SET reset_code = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $reset_code, $email);
        mysqli_stmt_execute($stmt);

        // Send Email
        $subject = "Password Reset Code";
        $message = "Your password reset code is: " . $reset_code;
        $headers = "From: no-reply@yourwebsite.com";

        if (sendEmail($email, $subject, $message)) {
            $_SESSION['success'] = "A reset code has been sent to your email.";
            header("Location: reset_password.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to send reset code. Try again!";
        }
    } else {
        $_SESSION['error'] = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University House Seeker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4" style="width: 400px;">
        <h2 class="text-center">Forgot Password</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" class="form-control mb-2" placeholder="Enter your email" required>
            <button type="submit" class="btn btn-primary w-100">Send Reset Code</button>
        </form>
    </div>
</body>
</html>
