<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';

if (isset($_GET['id'])) {
    
    $house_id = $_GET['id'];
    $delete_query = "DELETE FROM houses WHERE id = '$house_id'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "House deleted successfully.";
        header("Location: manage_houses.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_houses.php");

}
?>