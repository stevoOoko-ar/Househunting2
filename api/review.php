<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_id = intval($data['house_id']);
    $user_id = intval($data['user_id']);
    $rating = intval($data['rating']);
    $comment = mysqli_real_escape_string($conn, $data['comment']);

    $query = "INSERT INTO reviews (house_id, user_id, rating, comment) VALUES ($house_id, $user_id, $rating, '$comment')";
    
    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Review submission failed.";
    }
}

echo json_encode($response);
?>
