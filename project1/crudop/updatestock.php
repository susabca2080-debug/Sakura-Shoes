<?php include 'databaseconn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_size_id = isset($_POST['product_size_id']) ? (int)$_POST['product_size_id'] : 0;
    $new_stock = isset($_POST['new_stock']) ? (int)$_POST['new_stock'] : 0;


    if( $product_size_id > 0 ) {
        $sql = "UPDATE product_sizes SET size_stock = $new_stock WHERE product_size_id = $product_size_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['tost'] = ['text' => "Stock updated successfully.", 'type' => "success"];
            header("Location: dashbord.php");
            exit;
        } else {
            $_SESSION['tost'] = ['text' => "Error updating stock: " . $conn->error, 'type' => "error"];
            header("Location: dashbord.php");
            exit;
        }
    } else {
        $_SESSION['tost'] = ['text' => "Invalid product size id.", 'type' => "error"];
        header("Location: dashbord.php");
        exit;
    }
} else {
    echo "Invalid request method.";
}
?>