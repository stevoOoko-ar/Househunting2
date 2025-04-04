<?php
// session_start();
include '../includes/config.php';
include '../includes/header.php';

$query = "SELECT reports.*, students.name FROM reports JOIN students ON reports.student_id = students.id";
$result = mysqli_query($conn, $query);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<div class="container mt-5">
    <h2>Reported Issues</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Issue</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['issue']; ?></td>
                    <td><?php echo $row['date_reported']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>
