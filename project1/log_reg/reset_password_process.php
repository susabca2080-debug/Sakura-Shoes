<?php
include '../includes/connection.php';

if (isset($_POST['email'], $_POST['password'], $_POST['confirm_password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "❌ Passwords do not match";
        exit;
    }

    // Hash new password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed, $email);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "❌ Failed to reset password";
    }
}
?>