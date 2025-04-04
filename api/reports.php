<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_id = intval($data['house_id']);
    $user_id = intval($data['user_id']);
    $report = mysqli_real_escape_string($conn, $data['report']);

    $query = "INSERT INTO reports (house_id, user_id, report) VALUES ($house_id, $user_id, '$report')";
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Report submission failed.";
    }
}

echo json_encode($response);
?>
