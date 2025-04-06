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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<div class="container mt-5" style="padding-top: 70px;">
    <div class="card shadow-sm animate__animated animate__fadeIn" style="max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 10px;">
        <h2 class="text-center mb-4 animate__animated animate__fadeInDown">Update House</h2>
        <form method="POST" enctype="multipart/form-data" class="animate__animated animate__zoomIn">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $house['title']; ?>" required>
                </div>
                <div class="col-md-12">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo $house['location']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label">Price (KES)</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $house['price']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?php echo $house['type']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="vacant_rooms" class="form-label">Vacant Rooms</label>
                    <input type="text" class="form-control" id="vacant_rooms" name="vacant_rooms" value="<?php echo $house['vacant_rooms']; ?>" required>
                </div>
                <div class="col-md-12">
                    <label for="image" class="form-label">Current Image</label>
                    <div class="text-center mb-3">
                        <img src="/HouseSeeker/assets/images/<?php echo $house['images']; ?>" 
                             alt="House Image" 
                             class="img-thumbnail animate__animated animate__fadeIn" 
                             style="max-width: 100%; height: auto; object-fit: cover; border: 2px solid #ddd;">
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="image" class="form-label">Upload New Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="col-12 text-center">
                    <button type="submit" name="update" class="btn btn-primary w-50 animate__animated animate__pulse animate__infinite">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php';?>
