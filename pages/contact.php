<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php'; // Ensure database connection if needed
include '../includes/send_email.php';

// Check if the user is logged in and determine their role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'landlord') {
    include '../includes/header_landlord.php';
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    include '../includes/header.php';
}else{
    include '../includes/admin_header.php';
}

$sender_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $query = "INSERT INTO messages (sender_id,name, email, message) VALUES ('$sender_id','$name', '$email', '$message')";
    if (mysqli_query($conn, $query)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Something went wrong. Try again!";
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
<body>
    <div class="container mt-5" style = "padding-top: 50px;">
        <h2 class="text-center mb-4">Contact Us</h2>
        <p class="text-center">Have questions? Reach out to us and we’ll get back to you.</p>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Message</button>
        </form>
    </div>
</body>
</html>

<?php
include '../includes/footer.php';
?>
