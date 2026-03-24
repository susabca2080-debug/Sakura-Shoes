<?php include 'databaseconn.php'; 
session_start();
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['size_id'])) {
        $size_id = $_POST['size_id'];  
        $sql="DELETE FROM sizes WHERE size_id = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $size_id);
        if ($stmt->execute()) {
            $_SESSION['tost'] = ['text' => 'Size deleted successfully', 'type' => 'success'];
            header("Location: dashbord.php#viewsize");
            exit();;
        } else {
            $_SESSION['tost'] = ['text' => 'Failed to delete size: ' . $stmt->error, 'type' => 'error'];
            header("Location: dashbord.php#viewsize");
            exit();     
        }
        $stmt->close();
    } else {
        echo "Size ID not provided.";
    }
} 
else {
    echo "Invalid request method.";     
}
$conn->close();
?>