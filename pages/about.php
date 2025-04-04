<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php'; // Ensure database connection if needed

// Check if the user is logged in and determine their role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'landlord') {
    include '../includes/header_landlord.php';
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    include '../includes/header.php';
}else{
    include '../includes/admin_header.php';
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
        <h2 class="text-center mb-4">About University House Seeker</h2>
        <p class="text-center lead">
            Welcome to <strong>University House Seeker</strong>, your trusted platform for finding and listing student accommodations near MUST.
        </p>

        <div class="row mt-5">
            <div class="col-md-6">
                <h3>For Students</h3>
                <ul>
                    <li>Verified house listings</li>
                    <li>Advanced search with filters</li>
                    <li>Interactive map for location-based searches</li>
                    <li>Instant messaging with landlords</li>
                    <li>Online booking and applications</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3>For Landlords</h3>
                <ul>
                    <li>Manage property listings</li>
                    <li>Direct student inquiries</li>
                    <li>Rental payment integration</li>
                    <li>Tenant screening and verification</li>
                    <li>Automated vacancy updates</li>
                </ul>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="contact.php" class="btn btn-primary">Contact Us</a>
        </div>
    </div>


</body>
</html>

<?php
include '../includes/footer.php';
?>
