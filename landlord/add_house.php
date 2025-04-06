<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');
$landlord_id = $_SESSION['user_id'] ?? 1; 
$query2 = "SELECT * FROM users WHERE id = '$landlord_id'";
$status = mysqli_query($conn, $query2);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $vacant_rooms = $_POST['vacant_rooms'];
    $descriptions = $_POST['descriptions'];
    $landlord_id = $_SESSION['user_id'] ?? 1;
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "../assets/images/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $query = "INSERT INTO houses (title, location, price, type,vacant_rooms,descriptions, landlord_id, images, availability) 
              VALUES ('$title', '$location', '$price', '$type','$vacant_rooms','$descriptions', '$landlord_id', '$image', 'available')";
    
    if (mysqli_query($conn, $query)) {
        $success_message = "House added successfully!";
    } else {
        error_log("House Insert Error: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add House</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5" style="padding-top: 70px;">
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success animate__animated animate__fadeInDown" role="alert">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php while ($data = mysqli_fetch_assoc($status)) { ?>
        <?php if ($data['status'] == 'Rejected'): ?>
            <div class="alert alert-danger">
                Sorry, you have been Rejected.
            </div>
        <?php elseif ($data['status'] == "Pending"): ?>
            <div class="alert alert-info">
                Please your registration is not yet confirmed, if no payments made <a href="landlord_payments.php">make payments</a> to start posting your houses.
            </div>
        <?php else : ?>
            <h2>Add a New House</h2>
            <form action="add_house.php" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Rent Price (KES)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Type</label>
                    <select name="type" class="form-select">
                        <option value="single">Single Room</option>
                        <option value="bedsitter">Bed-sitter</option>
                        <option value="self-contained">Self-Contained</option>
                        <option value="shared">Shared</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Vacant Rooms</label>
                    <input type="number" name="vacant_rooms" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Available Services</label>
                    <input type="text" name="descriptions" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Upload Image </label>
                    <input type="file" accept="image/*" name="image" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add House</button>
                </div>
            </form>
        <?php endif; ?>
    <?php } ?>
</div>
</body>
</html>
<script>
    // Optional: Automatically hide the success message after a few seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.classList.add('animate__fadeOutUp');
            setTimeout(() => alert.remove(), 1000);
        }
    }, 3000);
</script>
<?php include '../includes/footer.php'; ?>
