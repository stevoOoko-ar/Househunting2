<?php
include '../includes/config.php';
include '../includes/header.php';
if (!isset($_SESSION)) {
    session_start();
}
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT * FROM houses WHERE id = $id";
$result = mysqli_query($conn, $query);
$house = mysqli_fetch_assoc($result);

if (!$house) {
    die("House not found.");
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
<div class="container" style="display: flex;align-items: center; justify-content: center; padding-top: 70px;">
    
        <div class = "card" style="padding: 20px; margin: 10px; box-shadow: 5px;">
            
            <h2><?php echo $house['title']; ?></h2>
            <img src="../assets/images/<?php echo $house['images']; ?>" class="img-fluid" alt="House Image">
            <h1 class="text-center">House Details</h1>
            <p><strong>Location:</strong> <?php echo $house['location']; ?></p>
            <p><strong>Rent:</strong> KES <?php echo $house['price']; ?>/month</p>
            <p><strong>Date Created:</strong> <?php echo $house['created_at']; ?></p>
            <p><strong>Available Services:</strong> <?php echo $house['descriptions']; ?></p>
            <div class="buttons" style="display: flex; flex-direction: row; justify-content: space-evenly;">
                <a href="/HouseSeeker/pages/dashboard.php" class="btn btn-secondary">Back to Listings</a>
                <a href="add_to_shortlist.php?id=<?php echo $house['id']; ?>" class="btn btn-primary">Shortlist</a>
                
            </div>
        </div>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
