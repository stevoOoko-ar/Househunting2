<?php
$host = "localhost";
$user = "root"; // Change if needed
$password = ""; // Change if needed
$database = "househunt";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error_log.txt');
?>