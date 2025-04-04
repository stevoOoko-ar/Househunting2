<?php
include '../includes/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $data['action'] ?? '';

    if ($action === 'register') {
        $username = mysqli_real_escape_string($conn, $data['username']);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            $response['success'] = true;
        } else {
            $response['error'] = "Registration failed.";
        }
    }

    if ($action === 'login') {
        $username = mysqli_real_escape_string($conn, $data['username']);
        $password = $data['password'];

        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $response['success'] = true;
        } else {
            $response['error'] = "Invalid credentials.";
        }
    }
}

echo json_encode($response);
?>
