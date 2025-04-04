<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/admin_header.php';

// Ensure only admin can access
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');
$username = $_SESSION['name'];
// Fetch counts for dashboard
$student_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'"))['total'];
$landlord_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'landlord'"))['total'];
$payments_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM landlord_payments"))['total'];
$reports_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reports"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Welcome, <?php echo $username; ?> </h2>
    <div class="row" ">
        <div class="col-md-3" style="margin-top: 20px;">
            <div class="card bg-primary text-white text-center p-3">
                <h4>Students</h4>
                <p><?php echo $student_count; ?></p>
            </div>
        </div>
        <div class="col-md-3" style="margin-top: 20px;">
            <div class="card bg-warning text-white text-center p-3">
                <h4>Landlords</h4>
                <p><?php echo $landlord_count; ?></p>
            </div>
        </div>
        <div class="col-md-3" style="margin-top: 20px;">
            <div class="card bg-success text-white text-center p-3">
                <h4>Payments</h4>
                <p><?php echo $payments_count; ?></p>
            </div>
        </div>
        <div class="col-md-3" style="margin-top: 20px;">
            <div class="card bg-danger text-white text-center p-3">
                <h4>Reports</h4>
                <p><?php echo $reports_count; ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php include '../includes/admin_footer.php'; ?>
