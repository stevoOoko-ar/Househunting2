<?php
if (!isset($_SESSION)) {
    session_start();
}

// Initialize shortlisted houses count if not already set
if (!isset($_SESSION['shortlisted_count'])) {
    $_SESSION['shortlisted_count'] = 0;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .card:hover {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
    </style>
    <style>
        .btn-primary:hover {
            background-color: #2575fc;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }
    </style>
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
                <div class="card shadow-sm rounded h-100">
                    <img src="/HouseSeeker/assets/images/<?php echo $row['images']; ?>" class="card-img-top rounded-top" alt="House Image" style="height: 250px; object-fit: cover;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-primary"><?php echo $row['title']; ?></h5>
                            <p class="card-text text-muted mb-1"><i class="bi bi-geo-alt"></i> <?php echo $row['location']; ?>, Meru</p>
                            <p class="card-text text-muted mb-1"><i class="bi bi-door-open"></i> <?php echo $row['vacant_rooms']; ?> Vacant Rooms</p>
                            <p class="card-text text-dark"><strong>Rent:</strong> KES <?php echo $row['price']; ?>/month</p>
                        </div>
                        <div>
                            <a href="/HouseSeeker/student/view_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mt-2 w-100">View Details</a>
                            <form method="POST" action="/HouseSeeker/student/shortlist.php" class="mt-2">
                                <input type="hidden" name="house_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-outline-success w-100">Shortlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php }; ?>
    </div>
    <?php endif; ?>
    </div>
    
    <div class="position-fixed bottom-0 end-0 m-4">
        <a href="/HouseSeeker/student/shortlist.php" class="btn btn-primary position-relative rounded-circle p-3 shadow" style="width: 60px; height: 60px;">
            <i class="bi bi-basket" style="font-size: 1.5rem;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo isset($_SESSION['shortlisted_count']) ? $_SESSION['shortlisted_count'] : 0; ?>
            </span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include '../includes/footer.php'; // Footer content
?>
