<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($data['user_id']);
    $house_id = intval($data['house_id']);
    $amount = floatval($data['amount']);

    $query = "INSERT INTO payments (user_id, house_id, amount, status) VALUES ($user_id, $house_id, $amount, 'pending')";
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Payment failed.";
    }
}

echo json_encode($response);
?>
