<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';


if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $query = "SELECT * FROM houses WHERE id = '$house_id'";
    $result = mysqli_query($conn, $query);
    $house = mysqli_fetch_assoc($result);
    if (isset($_POST['update'])) {
        $title = $_POST['title'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $type = $_POST['type'];

        $update_query = "UPDATE houses SET title = '$title', location = '$location', price = '$price', type = '$type' WHERE id = '$house_id'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_message'] = "House updated successfully.";
            header("Location: manage_houses.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: manage_houses.php");
    exit();
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<div class="container mt-5" style="padding-top: 70px;">
    <div class="card" style="padding: 10px;">
        <h2>Update House</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $house['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $house['location']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (KES)</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $house['price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo $house['type']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Vacant Rooms</label>
                <input type="text" class="form-control" id="type" name="vacant_rooms" value="<?php echo $house['vacant_rooms']; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
        </div>
</div>
<?php include '../includes/footer.php';?>
