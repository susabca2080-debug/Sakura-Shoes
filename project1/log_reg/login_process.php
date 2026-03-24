<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../crudop/databaseconn.php';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1️⃣ Check user by email
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 2️⃣ If user exists
    if ($user = $result->fetch_assoc()) {

        // 3️⃣ Verify password
        if (password_verify($password, $user['password'])) {

            // 4️⃣ Create session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            $_SESSION['last_login'] = time();


            // Restore cart for this user if we saved one earlier
            /**
             * Restores the shopping cart for the authenticated user from the session.
             *
             * Checks if a saved cart exists in the `$_SESSION['saved_carts']` array
             * for the current user's `user_id` and verifies that it is an array.
             * If a saved cart is found, it is assigned to `$_SESSION['cart']`,
             * effectively loading the user's previously stored cart items after login.
             */
            if (isset($_SESSION['saved_carts'][$user['user_id']]) && is_array($_SESSION['saved_carts'][$user['user_id']])) {
                $_SESSION['cart'] = $_SESSION['saved_carts'][$user['user_id']];
            }

            // 5️⃣ Redirect based on role
            ob_clean(); // Clear any previous output
            if ($user['role'] === 'admin') {
                header("Location: /sakurashoes/project1/crudop/dashbord.php");
            } else {
                header("Location: /sakurashoes/project1/index.php");
            }
            exit;

        } else {
            $_SESSION['error'] = "❌ Incorrect password";
            header("Location: login.php");
            exit;
        }

    } else {
        $_SESSION['error'] = "❌ Email not registered";
        header("Location: login.php");
        exit;
    }
}
?>