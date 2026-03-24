<?php
include '../includes/connection.php';

if (isset($_POST['email'])) {

    $email = trim($_POST['email']);

    // Check if email exists
    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Email exists → redirect to reset page
        header("Location: reset_password.php?email=" . urlencode($email));
        exit;
    } else {
        echo "❌ Email not registered";
    }
}
?>