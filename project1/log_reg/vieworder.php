<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../header.php';
include '../crudop/databaseconn.php';

$user_id = (int) $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id = " . $user_id;
$result = $conn->query($query);

echo "<h2>Your Orders</h2>";

if ($result && $result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        echo "<div class='order' style='border: 1px solid #ccc; padding: 15px; margin: 15px 0; border-radius: 5px;'>";
        echo "<strong>Order ID:</strong> " . htmlspecialchars($order['order_id']) . "<br>";
        echo "<strong>Date:</strong> " . htmlspecialchars($order['created_at']) . "<br>";
        echo "<strong>Total:</strong> Nrs" . htmlspecialchars($order['total_amount']) . "<br>";
        echo "<strong>Status:</strong> " . htmlspecialchars($order['order_status']) . "<br>";
        echo "</div>";
    }
} else {
    echo "<p>No orders found.</p>";
}

include '../footer.php'; 
?>