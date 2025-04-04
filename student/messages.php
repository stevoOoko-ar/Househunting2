<?php
include '../includes/config.php';
include '../includes/header.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');
if (!isset($_SESSION)) {
    session_start();
}
$student_email = 'student@example.com'; // Get student email from session when authentication is implemented

$query = "SELECT * FROM messages WHERE recipient_email = '$student_email' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
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
<div class="container mt-5" style="padding-top: 70px;">
    <h2>Messages</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sender</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['sender_email']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
