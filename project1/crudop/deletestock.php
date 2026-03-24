<?php include 'databaseconn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_size_id']) ? (int)$_POST['product_size_id'] : 0;
    

    if ($product_id > 0) {
        $sql = "DELETE FROM product_sizes WHERE product_size_id = $product_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['tost'] = ['text' => "Stock deleted successfully.", 'type' => "success"];
            header("Location: dashbord.php");
            exit;
        } else {
            $_SESSION['tost'] = ['text' => "Error deleting stock: " . $conn->error, 'type' => "error"];
            header("Location: dashbord.php");
            exit;
        }
    } else {
        echo "Invalid brand id.";
    }
} else {
    echo "Invalid request method.";
}
?>