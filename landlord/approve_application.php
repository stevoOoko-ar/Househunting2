<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/send_email.php';
if (isset($_GET['id'])) {
    $application_id = $_GET['id'];
    $approve_query = "UPDATE applications set status = 'Approved' WHERE id = '$application_id'";
    
    if (mysqli_query($conn, $approve_query)) {
        $_SESSION['success_message'] = "Application approved successfully.";
        header("Location: applications.php");
        $query = "SELECT * FROM applications where id = '$application_id'";
        $user_data = mysqli_query($conn, $query);
        while ($user_data = mysqli_fetch_assoc($user_data)){
            $user_id = $user_data["user_id"];
            $email_query = "SELECT * from users where id = $user_id";
            $email_data = mysqli_query($conn, $email_query);
            while ($email_data = mysqli_fetch_assoc($email_data)){
                $email = $email_data['email'];
                $name = $email_data['name'];
                $subject = "Application Approval";
                $link = "<a class = 'btn btn-danger' href = 'http://192.168.137.1/HouseSeeker/pages/login.php'>Login</a>";
                $message = "Hello $name your application have been approved please login here '$link' and continue to payments";
                sendEmail($email,$subject,$message);
            }
        }
        exit();
    } else {
        echo "Error approving the application: " . mysqli_error($conn);
    }
} else {
    header("Location: applications.php");
    exit();
}
?>