<?php
session_start();

// Only allow logged-in users to add to cart
if (!isset($_SESSION['user_id'])) {
    header('Location: log_reg/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id   = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price        = $_POST['price'];
    $size         = $_POST['size'];
    $image        = $_POST['image'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $key = $product_id . '_' . $size;

    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id'   => $product_id,
            'product_name' => $product_name,
            'price'        => $price,
            'size'         => $size,
            'quantity'     => 1,
            'image'        => $image,
        ];
    }

    header('Location: cart.php');
    exit;
}