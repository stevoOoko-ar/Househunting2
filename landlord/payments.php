<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';
include '../includes/send_email.php';
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

$landlord_id = $_SESSION['user_id'] ?? 1;

$query = "SELECT payments.*, users.name,users.email as email, houses.title FROM payments 
          JOIN houses ON payments.house_id = houses.id 
          JOIN users ON payments.user_id = users.id 
          WHERE houses.landlord_id = '$landlord_id'";
$result = mysqli_query($conn, $query);
$message = "Hello you payments was recievied so you can visit and choose the house of your choice";
$from = "danielmaishy@gmail.com";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5" style="padding-top: 70px;">
    <h2>Payment Transactions</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student</th>
                <th>House</th>
                <th>Amount (KES)</th>
                <th>Transaction Id</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['paid_at']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php } else { ?>
                            <span class="badge bg-success">Confirmed</span>
            
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <a href="confirm_payments.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Confirm</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
<script>
    function confirmPayments(id) {
        message = prompt();
            window.location.href = `confirm_payments.php?id=${id},?message = ${message}`;
    }

</script>

<?php include '../includes/footer.php'; ?>

