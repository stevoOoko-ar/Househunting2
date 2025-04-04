<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style = "position: fixed; width: 100%; z-index: 1; top: 0;">
    <div class="container">
        <a class="navbar-brand" href="/HouseSeeker/landlord/dashboard.php">Landlord Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/HouseSeeker/landlord/dashboard.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/HouseSeeker/landlord/payments.php">Payments</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger btn-sm text-white" href="/HouseSeeker/pages/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link btn btn-primary btn-sm text-white" href="/HouseSeeker/pages/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-secondary btn-sm text-white" href="/HouseSeeker/pages/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
