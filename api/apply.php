<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_id = intval($data['house_id']);
    $user_id = intval($data['user_id']);
    $visit_date = mysqli_real_escape_string($conn, $data['visit_date']);

    $query = "INSERT INTO applications (house_id, user_id, visit_date, status) VALUES ($house_id, $user_id, '$visit_date', 'pending')";

    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Application failed.";
    }
}

echo json_encode($response);
?>
