<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/send_email.php';

if (isset($_GET['id'])) {
    $landlord_id = $_GET['id'];
    $approve_query = "UPDATE users set status = 'Approved' WHERE id = '$landlord_id'";
    $email_query = "SELECT * FROM users where id = '$landlord_id'";
    $result = mysqli_query($conn, $email_query);
    if (mysqli_query($conn, $approve_query)) {
        $_SESSION['success_message'] = "Application approved successfully.";
        while ($row = mysqli_fetch_assoc($result)) {
            $subject = "Application Approval";
            $name = $row['name'];
            $email = $row['email'];
            $link = "<a href = 'http://192.168.137.1/HouseSeeker/pages/login.php'>Login</a>";
            $message = "Hello: $name your application have been approved, please login again here '$link'  to access the services";
            sendEmail($email,$subject,$message);
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