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
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
</head>
<body style = "padding: 50px 0 0 0">

<!-- Hero Section -->
<div class="hero text-center text-white" style="background: url('/HouseSeeker/assets/images/background.jpg') center/cover; padding: 100px;" data-aos="fade-up">
    <h1>Find Your Perfect Home</h1>
    <p>Search from thousands of listings and find your next rental today.</p>
    <a href="#search" class="btn btn-primary btn-lg">Start Searching</a>
</div>

<div class="container mt-5" id="search" data-aos="fade-right">
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Find Your Perfect Accommodation</h4>
        </div>
        <div class="card-body">
            <form action="dashboard.php" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="Enter location">
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Property Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Any Type</option>
                        <option value="single" <?php echo ($type == 'single') ? 'selected' : ''; ?>>Single Room</option>
                        <option value="bedsitter" <?php echo ($type == 'bedsitter') ? 'selected' : ''; ?>>Bed-sitter</option>
                        <option value="self-contained" <?php echo ($type == 'self-contained') ? 'selected' : ''; ?>>Self-Contained</option>
                        <option value="shared" <?php echo ($type == 'shared') ? 'selected' : ''; ?>>Shared</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="max_rent" class="form-label">Maximum Rent</label>
                    <input type="number" class="form-control" id="max_rent" name="max_rent" value="<?php echo htmlspecialchars($max_rent > 0 ? $max_rent : ''); ?>" placeholder="KES">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Featured Listings -->
<h2 class="text-center my-4" data-aos="fade-up">Featured Listings</h2>
<div class="container">
    <div class="row">
        <?php if ($result && mysqli_num_rows($result) > 0) { while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4 mb-4" data-aos="zoom-in">
                <div class="card shadow-sm">
                    <img src="/HouseSeeker/assets/images/<?php echo $row['images']; ?>" class="card-img-top" alt="House Image" style="width: 100%; height: 250px;">
                    <div class="card-body">
                        <h5 class="card-title"> <?php echo $row['title']; ?> </h5>
                        <p class="card-text"> <?php echo $row['location']; ?> </p>
                        <p class="card-text"> <?php echo $row['descriptions']; ?> </p>
                        <p class="card-text"><strong>Rent:</strong> KES <?php echo number_format($row['price']); ?>/month</p>
                        <a href="/HouseSeeker/student/view_house.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php }} else { ?>
            <p class="text-center">No properties available at the moment.</p>
        <?php } ?>
    </div>
</div>
<!-- How It Works Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">How It Works</h2>
    <div class="row text-center">
        <!-- Step 1: Search Properties -->
        <div class="col-12 col-md-4 mb-3">
            <div class="card shadow-sm p-4">
                <i class="bi bi-search fs-1 text-primary"></i>
                <div class="card-body">
                    <h4 class="card-title">Search for Properties</h4>
                    <p class="card-text">Use our search filters to find properties that suit your needs and budget.</p>
                </div>
            </div>
        </div>

        <!-- Step 2: Contact Landlords -->
        <div class="col-12 col-md-4 mb-3">
            <div class="card shadow-sm p-4">
                <i class="bi bi-chat-dots fs-1 text-primary"></i>
                <div class="card-body">
                    <h4 class="card-title">Contact Landlords</h4>
                    <p class="card-text">Reach out to property owners for more information and to arrange viewings.</p>
                </div>
            </div>
        </div>

        <!-- Step 3: Move In -->
        <div class="col-12 col-md-4 mb-3">
            <div class="card shadow-sm p-4">
                <i class="bi bi-house-door fs-1 text-primary"></i>
                <div class="card-body">
                    <h4 class="card-title">Move In with Ease</h4>
                    <p class="card-text">Finalize your deal and move into your new home, hassle-free!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="text-center bg-primary text-white py-5">
    <h3 class="mb-3">Ready to Find Your Next Home?</h3>
    <p class="lead mb-4">Start your search now and find the perfect property for you.</p>
    <a href="#search" class="btn btn-light btn-lg">Search Now</a>
</div>


<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>

<?php include '../includes/footer.php'; ?>
