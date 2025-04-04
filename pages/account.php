<?php
include '../includes/config.php';
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to access this page.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$details = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Check if the email already exists (excluding current user)
    $checkEmail = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $checkEmail);
    mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error'] = "Email is already registered!";
        header("Location: account.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    // Update user details
    if ($password) {
        $query = "UPDATE users SET name = ?, email = ?, phone = ?, role = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone, $role, $password, $user_id);
    } else {
        $query = "UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $role, $user_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Details updated successfully!";
        header("Location: account.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Try again!";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="container d-flex justify-content-center align-items-center" style="height: 100vh;">

    <div class="card p-4" style="width: 400px;">
        <h2 class="text-center">Update Account</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required value="<?= htmlspecialchars($details['name']); ?>">
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required value="<?= htmlspecialchars($details['email']); ?>">
            <input type="text" name="phone" class="form-control mb-2" placeholder="Phone Number" required value="<?= htmlspecialchars($details['phone']); ?>">
            <input type="password" name="password" class="form-control mb-2" placeholder="New Password (leave blank to keep current)">
            <select name="role" class="form-select mb-2" required>
                <option value="student" <?= ($details['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                <option value="landlord" <?= ($details['role'] == 'landlord') ? 'selected' : ''; ?>>Landlord</option>
            </select>
            <button type="submit" name="update" class="btn btn-primary w-100">Update</button>
        </form>
    </div>

</body>
</html>
