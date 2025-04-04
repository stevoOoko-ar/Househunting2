<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header.php';
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

// Get and sanitize filter parameters
$location = isset($_GET['location']) ? mysqli_real_escape_string($conn, $_GET['location']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
$max_rent = isset($_GET['max_rent']) ? (int)$_GET['max_rent'] : 0;

// Build query with prepared statement
$query = "SELECT h.*, u.name as owner_name FROM houses h 
          JOIN users u ON h.landlord_id = u.id 
          WHERE h.availability = 'available'";
$params = [];
$types = "";

if (!empty($location)) {
    $query .= " AND h.location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}
if (!empty($type)) {
    $query .= " AND h.type = ?";
    $params[] = $type;
    $types .= "s";
}
if ($max_rent > 0) {
    $query .= " AND h.rent <= ?";
    $params[] = $max_rent;
    $types .= "i";
}

// Add sorting
$query .= " ORDER BY h.created_at DESC";

// Prepare and execute the statement
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    if (!mysqli_stmt_execute($stmt)) {
        error_log("MySQL Execute Error: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);
} else {
    error_log("MySQL Prepare Error: " . mysqli_error($conn));
    $result = false;
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
    <div class="container mt-5 text-center" style = "padding-top: 50px;">
        <h1 class="mb-4">Find Your Ideal Student Accommodation</h1>
        <form action="/HouseSeeker/dashboard.php" method="GET" class="row g-3 justify-content-center">
            <div class="col-md-3">
                <input type="text" name="location" class="form-control" placeholder="Enter location (e.g., MUST)">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Any Type</option>
                    <option value="single">Single Room</option>
                    <option value="bedsitter">Bed-sitter</option>
                    <option value="self-contained">Self-Contained</option>
                    <option value="shared">Shared</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Rent (KES)">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    <div class="container mt-5 " style = "display: flex; flex-direction: column; align-items: center; justify-content: space-evenly">
        <h2 class="text-center">Available Accommodations</h2>
        <?php if (!$result): ?>
        <div class="alert alert-danger">
            Sorry, there was an error processing your request. Please try again later.
        </div>
    <?php elseif (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info">
            No properties found matching your criteria. Try adjusting your search filters.
        </div>
    <?php else: ?>
        <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="/HouseSeeker/assets/images/<?php echo $row['images']; ?>" class="card-img-top" alt="House Image" style = "height: 250px;">
                    <div class="card-body" style = "width: 100%; height: 200px; border: 1px solid black; padding: 5px; display: flex; flex-direction: column; justify-content:space-between;">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['location']; ?> Meru</p>
                        <p class="card-text"><?php echo $row['vacant_rooms']; ?> Vacant Rooms</p>
                        <p class="card-text"><strong>Rent:</strong> KES <?php echo $row['price']; ?>/month</p>
                        <a href="/HouseSeeker/student/view_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php };?>
    </div>
    <?php endif; ?>
    </div>
    
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include '../includes/footer.php'; // Footer content
?>
