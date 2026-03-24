<?php
session_start();

// If a user is logged in, save their cart under their user_id
if (isset($_SESSION['user_id']) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
	$_SESSION['saved_carts'][$_SESSION['user_id']] = $_SESSION['cart'];
}

// Clear auth info and current cart so guests don't see previous data
unset($_SESSION['user_id'], $_SESSION['full_name'], $_SESSION['profile_picture'], $_SESSION['role'], $_SESSION['cart']);

header("Location: login.php");
exit;
?>