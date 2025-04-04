<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/admin_header.php';

$query = "SELECT u.*, p.transaction_id as transaction_id FROM landlord_payments p join users u on u.id = p.landlord_id  WHERE u.role = 'landlord'";
$result = mysqli_query($conn, $query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Landlords</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Manage Landlords</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Transaction Id</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <?php if($row['status'] != 'Approved'): ?> {
                    <td>
                        <a href="approve_landlord.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                        <a href="reject_landlord.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Reject</a>
                    </td>
                    
                    }
                    <?php endif ?>
                    <td>
                    <a href="delete_landlord.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
                </body>
                </html>
<?php include '../includes/admin_footer.php'; ?>
