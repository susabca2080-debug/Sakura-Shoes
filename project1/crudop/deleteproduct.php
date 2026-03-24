<?php
require_once 'databaseconn.php';
session_start();

$id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
if ($id === null || $id === false) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
}

if ($id && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['tost'] = ['text' => 'Product deleted successfully', 'type' => 'success'];
    } else {
        $_SESSION['tost'] = ['text' => 'Failed to delete product: ' . $stmt->error, 'type' => 'error'];
    }
    $stmt->close();
} else {
    $_SESSION['tost'] = ['text' => 'Invalid product id', 'type' => 'error'];
}

header('Location: dashbord.php');
exit;
