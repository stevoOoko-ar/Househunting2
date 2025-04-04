<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
include '../includes/send_email.php';

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $approve_query = "UPDATE payments SET status = 'Confirmed' WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $approve_query)) {
        mysqli_stmt_bind_param($stmt, "i", $payment_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Payments confirmed successfully.";
            header("Location: compose_email.php?id=$payment_id");
            exit();
        } else {
            echo "Error confirming the payments: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    header("Location: payments.php");
    exit();
}
?>
