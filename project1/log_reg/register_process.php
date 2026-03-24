<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../crudop/databaseconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        echo "❌ Passwords do not match";
        exit;
    }

    // 1️⃣ Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "❌ Email already registered";
        exit;
    }

    // 2️⃣ Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3️⃣ Upload profile picture (optional)
    $profile_picture = "default.png";

    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = "../crudop/images/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $profile_picture = time() . '_' . $_FILES['profile_picture']['name'];
        if (!move_uploaded_file(
            $_FILES['profile_picture']['tmp_name'],
            $upload_dir . $profile_picture
        )) {
            echo "❌ Failed to upload profile picture";
            exit;
        }
    }
    

    // 4️⃣ Insert user (role default = user)
    $sql = "INSERT INTO users (full_name, email, password, profile_picture)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $profile_picture);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "❌ Registration failed";
    }
}
?>