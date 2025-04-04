<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$house_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and validate form data
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, trim($_POST['payment_method'])) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $transaction_id = isset($_POST['transaction_id']) ? mysqli_real_escape_string($conn, trim($_POST['transaction_id'])) : '';

    if ($user_id <= 0 || empty($amount) || empty($transaction_id)) {
        echo "<div class='alert alert-danger'>All fields are required</div>";
    } else {
        // Prepare and execute SQL statement securely
        $query = "INSERT INTO landlord_payments (landlord_id, payment_method, amount, transaction_id) 
                  VALUES ('$user_id', '$payment_method', '$amount', '$transaction_id')";
        $stmt = mysqli_prepare($conn, $query);

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("SQL Error: " . mysqli_error($conn));  // Debugging output
        }


        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Payment submitted successfully! Wait for further instructions.</div>";
        } else {
            error_log("MySQL Error: " . mysqli_error($conn));
            echo "<div class='alert alert-danger'>Error submitting payment.</div>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<div class="container mt-5" style="padding-top: 70px;">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Make Payment</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="landlord_payments.php" id="paymentForm">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <div class="form-group mb-3">
                            <label for="payment_method">Payment Mode</label>
                            <input type="text" class="form-control" id="payment_method" name="payment_method"
                                   value="M-Pesa" readonly required>
                            <small class="form-text text-muted">Payments are only accepted through M-Pesa.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">Amount Paid</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                            <small class="form-text text-muted">Enter the Mpesa transaction code.</small>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I confirm that all payment details are correct.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            const terms = document.getElementById('terms').checked;
            
            if (!terms) {
                event.preventDefault();
                alert('Please confirm that all payment details are correct.');
            }
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
