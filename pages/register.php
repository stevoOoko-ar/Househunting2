<?php
include '../includes/config.php';
if (!isset($_SESSION)) {
    session_start();
}

// Debugging on (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, trim($_POST['role']));

    // Check for email duplication
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: register.php");
        exit();
    } else {
        // INSERT without status
        $query = "INSERT INTO users (name, email, phone, password, role)
                  VALUES ('$name', '$email', '$phone', '$password', '$role')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Try again! Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- FontAwesome for eye icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="container" style="margin-top: 150px;">

<div class="card mt-4" style="padding: 20px; width: 400px; margin: 50px auto;">
    <h2 class="text-center">Register</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php elseif (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="return validateForm()">
        <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Full Name" required>
        <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Email" required>
        <input type="text" name="phone" id="phone" class="form-control mb-2" placeholder="Phone Number" required>

        <div class="input-group mb-2">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>

        <select name="role" class="form-select mb-2" id="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="landlord">Landlord</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
</div>

<!--  JavaScript Logic -->
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function validateForm() {
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const role = document.getElementById('role').value;

    const emailRegex = /^[^@]+@[^@]+\.[a-z]{2,}$/i;
    const phoneRegex = /^[0-9]{10,15}$/;

    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (!phoneRegex.test(phone)) {
        alert("Phone number must be digits only (10–15 digits).");
        return false;
    }

    if (role === "") {
        alert("Please select a role.");
        return false;
    }

    return true;
}
</script>

</body>
</html>
