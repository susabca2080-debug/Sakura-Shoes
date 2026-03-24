<?php include 'databaseconn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;

    if ($brand_id > 0) {
        $sql = "DELETE FROM brands WHERE brand_id = $brand_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['tost'] = ['text' => "Brand deleted successfully.", 'type' => "success"];
            header("Location: dashbord.php");
            exit;
        } else {
            $_SESSION['tost'] = ['text' => "Error deleting brand . ", 'type' => "error"];
            header("Location: dashbord.php");
            exit;
        }
    } else {
        echo "Invalid brand id.";
    }
} else {
    echo "Invalid request method.";
}