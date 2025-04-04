<?php

if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/header_landlord.php';

$landlord_id = $_SESSION['user_id'] ?? 1;
$query = "SELECT applications.*, users.name, houses.title FROM applications 
          JOIN houses ON applications.house_id = houses.id 
          JOIN users ON applications.user_id = users.id 
          WHERE houses.landlord_id = '$landlord_id'";
$result = mysqli_query($conn, $query);
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
    <h2>Student Applications</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>House</th>
                <th>Message</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><a href="tel:<?php echo $row['phone_number']; ?>" class="btn btn-primary">Contact</a></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php } else { ?>
                            <span class="badge bg-success">Confirmed</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <a href="approve_application.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
                        </body>
                        </html>
<?php include '../includes/footer.php'; ?>
