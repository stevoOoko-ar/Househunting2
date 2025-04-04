<?php
include '../includes/config.php';
include '../includes/header.php';
if (!isset($_SESSION)) {
    session_start();
}
// Get house details if house_id is provided in URL
$house_id = 0;
$house_details = null;

if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $query = "SELECT h.*, u.name as owner_name, u.email as owner_email 
              FROM houses h 
              JOIN users u ON h.landlord_id = u.id 
              WHERE h.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $house_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $house_details = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Get current user details
$user_id = 0;
$user_email = '';
$user_phone = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT email,phone FROM users WHERE id = ?";
    $user_stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);
    $user_data = mysqli_fetch_assoc($user_result);
    $user_email = $user_data['email'] ?? '';
    $phone_number = $user_data['phone'] ?? '';
    mysqli_stmt_close($user_stmt);
}

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and validate form data
    $house_id = isset($_POST['house_id']) ? intval($_POST['house_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $student_email = isset($_POST['student_email']) ? trim($_POST['student_email']) : '';
    $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $status = 'Pending';
    
    if ($house_id <= 0 || $user_id <= 0 || empty($student_email) || empty($message)) {
        echo "<div class='alert alert-danger'>All fields are required</div>";
    } else {
        // Prepare SQL statement
        $query = "INSERT INTO applications (house_id, user_id, student_email,phone_number, message, status) VALUES ('$house_id','$user_id','$student_email','$phone_number','$message','$status')";
        $stmt = mysqli_prepare($conn, $query);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Application submitted successfully!</div>";
            header("Location: applications.php");
        } else {
            error_log("MySQL Error: " . mysqli_error($conn));
            echo "<div class='alert alert-danger'>Error submitting application.</div>";
        }
        mysqli_stmt_close($stmt);
    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5" style="padding-top: 70px;">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Apply for Housing</h3>
                </div>
                <div class="card-body">
                    <?php if (!$house_details && $house_id > 0): ?>
                        <div class="alert alert-danger">House not found!</div>
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-warning">Please <a href="/HouseSeeker/pages/login.php">login</a> to apply for housing.</div>
                    <?php else: ?>
                        <?php if ($house_details): ?>
                            <div class="house-details mb-4">
                                <h4><?php echo htmlspecialchars($house_details['title']); ?></h4>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($house_details['location']); ?></p>
                                <p><strong>Rent:</strong> $<?php echo htmlspecialchars($house_details['price']); ?> per month</p>
                                <p><strong>Owner:</strong> <?php echo htmlspecialchars($house_details['owner_name']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="apply.php" id="applicationForm">
                            <input type = "hidden"  name="house_id" value="<?php echo $house_id; ?>">
                            <input type = "hidden"  name="user_id" value="<?php echo $user_id; ?>">
                            
                            <div class="form-group mb-3">
                                <label for="student_email">Your Email</label>
                                <input type="email" class="form-control" id="student_email" name="student_email" 
                                       value="<?php echo htmlspecialchars($user_email); ?>" required>
                                <small class="form-text text-muted">The landlord will contact you at this email.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($phone_number); ?>" required>
                                <small class="form-text text-muted">The landlord will contact you at this phone number.</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="message">Message to Landlord</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required
                                          placeholder="Introduce yourself and explain why you're interested in this housing option..."></textarea>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the terms and conditions
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                            <a href="/HouseSeeker/dashboard.php" class="btn btn-secondary">Back to Listings</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
                        </body>
                        </html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('applicationForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            const email = document.getElementById('student_email').value;
            const message = document.getElementById('message').value;
            const terms = document.getElementById('terms').checked;
            
            if (!email || !message || !terms) {
                event.preventDefault();
                alert('Please fill out all required fields and accept the terms.');
            }
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>

