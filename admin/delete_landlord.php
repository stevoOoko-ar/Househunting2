<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';

if (isset($_GET['id'])) {
    
    $landlord_id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id = '$landlord_id'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "Landlord deleted successfully.";
        header("Location: manage_landlords.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_landlords.php");

}
?>