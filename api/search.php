<?php
include '../includes/config.php';
header('Content-Type: application/json');

$location = isset($_GET['location']) ? mysqli_real_escape_string($conn, $_GET['location']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
$max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : 0;

$query = "SELECT * FROM houses WHERE availability = 'available'";

if ($location !== '') {
    $query .= " AND location LIKE '%$location%'";
}
if ($type !== '') {
    $query .= " AND type = '$type'";
}
if ($max_price > 0) {
    $query .= " AND price <= $max_price";
}

$result = mysqli_query($conn, $query);
$houses = [];

while ($row = mysqli_fetch_assoc($result)) {
    $houses[] = $row;
}

echo json_encode($houses);
?>
