<?php
session_start();
include 'crudop/databaseconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'], $_POST['rating'], $_POST['review'])) {
    header("Location: index.php");
    exit;
}
$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

$sql = "INSERT INTO product_reviews (product_id, user_id, rating, review)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $product_id, $user_id, $rating, $review);
$stmt->execute();

header("Location: productdetail.php?id=$product_id");
exit;