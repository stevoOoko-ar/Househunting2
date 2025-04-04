<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/admin_header.php';
include '../includes/send_email.php';

ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');

$landlord_id = $_SESSION['user_id'] ?? 1;

$query = "SELECT messages.*, users.name,users.role FROM messages 
          JOIN users ON messages.sender_id = users.id ";
$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $subject = 'Reply';
    $message = $_POST['reply'];
    
    $result = sendEmail($email, $subject, $message);
    $_SESSION['email_status'] = $result;
    header("Location: messages.php");
    exit();
}



?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<div class="container mt-5">
    <h2>Messages from Users</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Message</th>
                <th>Date</th>
                <th>Reply</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td><?php echo $row['sent_at']; ?></td>
                    <td>
                        <form action="messages.php" method="POST" class="d-flex">
                            <input type="hidden" name="student_id" value="<?php echo $row['sender_id']; ?>">
                            <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                            <input type="text" name="reply" class="form-control" placeholder="Type a reply..." required>
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Send</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include '../includes/admin_footer.php'; ?>
