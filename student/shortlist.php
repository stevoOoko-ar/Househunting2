<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual student session when authentication is implemented

// Handle removing a house from shortlist
if (isset($_GET['remove'])) {
    $house_id = $_GET['remove'];

    // Remove the house from the database shortlist table
    $query = "DELETE FROM shortlist WHERE user_id = '$user_id' AND house_id = '$house_id'";
    mysqli_query($conn, $query);

    // Update the session variables
    if (isset($_SESSION['shortlisted_houses']) && in_array($house_id, $_SESSION['shortlisted_houses'])) {
        // Remove the house ID from the session array
        $_SESSION['shortlisted_houses'] = array_filter($_SESSION['shortlisted_houses'], function ($id) use ($house_id) {
            return $id != $house_id;
        });

        // Decrement the shortlisted count
        $_SESSION['shortlisted_count'] = count($_SESSION['shortlisted_houses']);
    }

    // Redirect back to the shortlist page
    header('Location: /HouseSeeker/student/shortlist.php');
    exit();
}

// Fetch shortlisted houses
$query = "SELECT houses.* FROM shortlist 
          JOIN houses ON shortlist.house_id = houses.id 
          WHERE shortlist.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php if (!isset($_SESSION['user_id'])): ?>
    <div class="alert alert-danger" role="alert">
        You need to be logged in to view this page.
    </div>
<?php else: ?>
<div class="container mt-5" style="padding-top: 70px;">
    <h2 class="mb-4">Your Shortlisted Houses</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm rounded h-100">
                        <img src="../assets/images/<?php echo $row['images']; ?>" class="card-img-top rounded-top" alt="House Image" style="height: 250px; object-fit: cover;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-primary"><?php echo $row['title']; ?></h5>
                                <p class="card-text text-muted mb-1"><i class="bi bi-geo-alt"></i> <?php echo $row['location']; ?>, Meru</p>
                                <p class="card-text text-muted mb-1"><i class="bi bi-door-open"></i> <?php echo $row['vacant_rooms']; ?> Vacant Rooms</p>
                                <p class="card-text text-dark"><i class="bi bi-currency-dollar"></i> <strong>Rent:</strong> KES <?php echo $row['price']; ?>/month</p>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="view_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary me-2">View Details</a>
                                <a href="?remove=<?php echo $row['id']; ?>" class="btn btn-danger me-2">Remove</a>
                                <a href="apply.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You have not shortlisted any houses yet.
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body>
</html>

