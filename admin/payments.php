<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/admin_header.php';

$query = "SELECT landlord_payments.*, users.name, users.status as status FROM landlord_payments 
          JOIN users ON landlord_payments.landlord_id = users.id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Payments Overview</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Landlord Name</th>
                <th>Amount (KES)</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['paid_at']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($row['status'] == 'confirmed') ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
            </body>
            </html>

<?php include '../includes/admin_footer.php'; ?>
