<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/send_email.php';

$user_email = '';
$house_title = '';
$payment_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($payment_id) {
    $query = "SELECT users.email, houses.title FROM users 
              INNER JOIN payments ON users.id = payments.user_id 
              INNER JOIN houses ON payments.house_id = houses.id 
              WHERE payments.id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $payment_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user_data = mysqli_fetch_assoc($result);
        $user_email = $user_data['email'] ?? '';
        $house_title = $user_data['title'] ?? '';
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $room_number = $_POST["room_number"];
    $subject = $_POST["subject"];
    $message = "House Name: " . $_POST["house_title"] . "\nRoom Number: " . $room_number . "\n\n" . $_POST["message"];
    
    $result = sendEmail($email, $subject, $message);
    $_SESSION['email_status'] = $result;
    header("Location: compose_email.php?id=$payment_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Compose Email</h3>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['email_status'])): ?>
                            <div class="alert alert-info">
                                <?php echo $_SESSION['email_status']; unset($_SESSION['email_status']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Recipient Email:</label>
                                <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">House Name:</label>
                                <input type="text" class="form-control" name="house_title" value="<?php echo htmlspecialchars($house_title); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Room Number:</label>
                                <input type="text" class="form-control" name="room_number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject:</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message:</label>
                                <textarea class="form-control" name="message" rows="4" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Send Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
