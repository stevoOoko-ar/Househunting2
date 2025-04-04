<?php
include '../includes/config.php';
header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT * FROM houses WHERE availability = 'available'");

$houses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $houses[] = $row;
}

echo json_encode($houses);
?>
