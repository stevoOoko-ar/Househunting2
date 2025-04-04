<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = intval($data['sender_id']);
    $receiver_id = intval($data['receiver_id']);
    $message = mysqli_real_escape_string($conn, $data['message']);

    $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ($sender_id, $receiver_id, '$message')";
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Message not sent.";
    }
}

echo json_encode($response);
?>
