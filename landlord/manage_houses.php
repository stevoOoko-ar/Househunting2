<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');
$landlord_id = $_SESSION['user_id'] ?? 1;

$query = "SELECT * FROM houses WHERE landlord_id = $landlord_id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Houses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5" style="padding-top: 70px;">
    <h2>Manage Houses</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Location</th>
                <th>Price (KES)</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo ucfirst($row['type']); ?></td>
                    <td>
                        <a href="update_house.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <!-- <a onclick="deleteHouse("$row['id']");" href="delete_house.php?id=<?php echo $row['id']; ?>"class="btn btn-sm btn-danger">Delete</a> -->
                        <a onclick="deleteHouse(<?php echo $row['id']; ?>);" href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
            </body>
            </html>
<script>
    function deleteHouse(id) {
        if (confirm('Are you sure you want to delete?')) {
            window.location.href = `delete_house.php?id=${id}`;
        };
    }

</script>

<?php include '../includes/footer.php'; ?>
