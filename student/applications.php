<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access!");
}
$user_id = $_SESSION['user_id'];

// Fetch applications with house details
$query = "SELECT applications.*, houses.title, houses.type, houses.price
          FROM applications
          JOIN houses ON applications.house_id = houses.id
          WHERE applications.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5" style="padding-top: 70px;">
    <h2>My Applications</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>House Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $house_id = $row['house_id'];
                $rent_amount = $row['price'];

                // Fetch total amount paid for the house
                $payment_query = "SELECT SUM(amount) AS total_paid FROM payments WHERE user_id = ? AND house_id = ?";
                $payment_stmt = mysqli_prepare($conn, $payment_query);
                mysqli_stmt_bind_param($payment_stmt, "ii", $user_id, $house_id);
                mysqli_stmt_execute($payment_stmt);
                $payment_result = mysqli_stmt_get_result($payment_stmt);
                $payment_data = mysqli_fetch_assoc($payment_result);
                $total_paid = $payment_data['total_paid'] ?? 0;

                $balance = $rent_amount - $total_paid;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php } else { ?>
                            <span class="badge bg-success">Approved</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php 
                        if ($balance > 0) { 
                            echo "Ksh " . number_format($balance, 2);
                        } else { 
                            echo "<span class='text-success'>Fully Paid</span>"; 
                        } 
                        ?>
                    </td>
                    <td>
                        <?php if ($balance > 0) { ?>
                            <a href="payments.php?id=<?php echo $row['house_id']; ?>" class="btn btn-sm btn-success">Make Payments</a>
                        <?php } else { ?>
                            <span class="badge bg-primary">Payments Complete</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php 
                mysqli_stmt_close($payment_stmt);
            } ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php 
mysqli_stmt_close($stmt);
include '../includes/footer.php'; 
?>
