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
    $query = "DELETE FROM shortlist WHERE user_id = '$user_id' AND house_id = '$house_id'";
    mysqli_query($conn, $query);
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
<?php if (!isset ($_SESSION['user_id'])): ?>
    <div class="alert alert-danger" role="alert">
        You need to be logged in to view this page.
    </div>
<?php else: ?>
<div class="container mt-5" style="padding-top: 70px;">
    <h2>Shortlisted Houses</h2>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="../assets/images/<?php echo $row['images']; ?>" class="card-img-top" alt="House Image" style = "height: 250px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['location']; ?></p>
                        <p class="card-text"><strong>Rent:</strong> KES <?php echo $row['price']; ?> /month</p>
                        <a href="view_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                        <a href="?remove=<?php echo $row['id']; ?>" class="btn btn-danger">Remove</a>
                        <a href = "apply.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Apply</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
        </body>
        </html>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

