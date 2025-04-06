<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

$landlord_id = $_SESSION['user_id'] ?? 1; // Replace with actual landlord session

// Fetch landlord houses


$landlord_name = $_SESSION['name'] ??'';


$query = "SELECT * FROM houses WHERE landlord_id = '$landlord_id'";
$query2 = "SELECT * FROM users WHERE id = '$landlord_id'";
$status = mysqli_query($conn, $query2);
$result = mysqli_query($conn, $query);
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
<div class="container mt-5" style="padding-top: 70px;">
<?php while ($data = mysqli_fetch_assoc($status)) { ?>
        <?php 
        $user_status = $data['status'] ?? null; // Safely access the 'status' key
        if ($user_status === 'Rejected'): ?>
                <div class="alert alert-danger">
                    Sorry, you have been Rejected.
                </div>
        <?php elseif ($user_status === "Pending"): ?>
            <div class="alert alert-info">
                Application status Pending. Wait for approval. If payment not made <a href="landlord_payments.php">make payments</a> to start posting your houses.
            </div>
        <?php else : ?>
            <h2>Welcome, <?php echo htmlspecialchars($landlord_name); ?></h2>
            <p class="lead">Manage your property listings and applications.</p>

            <div class="row">
                <div class="col-md-4">
                    <a href="add_house.php" class="btn btn-success w-100" style = "margin-top: 20px;">Add New House</a>
                </div>
                <div class="col-md-4">
                    <a href="manage_houses.php" class="btn btn-primary w-100" style = "margin-top: 20px;">Manage Houses</a>
                </div>
                <div class="col-md-4">
                    <a href="applications.php" class="btn btn-warning w-100" style = "margin-top: 20px;">View Applications</a>
                </div>
            </div>

            <?php if (!$result): ?>
                <div class="alert alert-danger">
                    Sorry, there was an error processing your request. Please try again later.
                </div>
            <?php elseif (mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info">
                    Please add some house.
                </div>
            <?php else: ?>
                <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <img src="/HouseSeeker/assets/images/<?php echo $row['images']; ?>" 
                                 class="card-img-top" 
                                 alt="House Image" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body" style="padding: 15px;">
                                <h5 class="card-title text-truncate" style="font-weight: bold;"><?php echo $row['title']; ?></h5>
                                <p class="card-text text-muted mb-2">
                                    <i class="bi bi-geo-alt-fill"></i> <?php echo $row['location']; ?>
                                </p>
                                <p class="card-text mb-2">
                                    <strong>Vacant Rooms:</strong> <?php echo $row['vacant_rooms']; ?>
                                </p>
                                <p class="card-text mb-3">
                                    <strong>Rent:</strong> KES <?php echo number_format($row['price']); ?> /month
                                </p>
                                <a href="update_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
<?php } ?>


</div>
                </body>
                </html>
<?php include '../includes/footer.php'; ?>

