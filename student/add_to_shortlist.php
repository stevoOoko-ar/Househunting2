<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../includes/config.php';
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header("Location: /HouseSeeker/pages/login.php");
    exit();
}
if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $approve_query = "INSERT INTO shortlist (user_id,house_id) VALUES ('$user_id','$house_id')";
    
    
    if (mysqli_query($conn, $approve_query)) {
        $_SESSION['success_message'] = "House has been shortlisted.";
        header("Location: shortlist.php");
        exit();
    } else {
        echo "Error shortlisting house: " . mysqli_error($conn);
    }
} else {
    header("Location: shortlist.php");
    exit();
}

?>