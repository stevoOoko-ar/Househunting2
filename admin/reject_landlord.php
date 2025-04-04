<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/send_email.php';

if (isset($_GET['id'])) {
    $landlord_id = $_GET['id'];
    $approve_query = "UPDATE users set status = 'Rejected' WHERE id = '$landlord_id'";
    $email_query = "SELECT * FROM users where id = '$landlord_id'";
    if (mysqli_query($conn, $approve_query)) {
        $_SESSION['success_message'] = "Application approved successfully.";

        while ($row = mysqli_fetch_assoc($result)) {
            $subject = "Application Approval";
            $name = $row['name'];
            $message = "Hello: $name We are really sorry that  your application have been rejected, if you already made the payments the money \ will be refunded in the next 24 hours from day of payments Thankyou";
            sendEmail($row['email'],$subject,$message);
         }
        header("Location: manage_landlords.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_landlords.php");
    exit();
}
?>