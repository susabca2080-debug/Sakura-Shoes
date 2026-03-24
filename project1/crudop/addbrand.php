<?php
session_start();
include 'databaseconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = $conn->real_escape_string($_POST['brand_name']);
    $brand_status = isset($_POST['status']) ? 1 : 0;

    $sql = "INSERT INTO brands (brand_name, status) VALUES ('$brand_name', $brand_status)";

     if ($conn->query($sql) === TRUE) {
        $_SESSION['tost'] = ['text' => 'Brand added successfully', 'type' => 'success'];
        header('Location: dashbord.php');
        exit;
    } else {
        $_SESSION['tost'] = ['text' => 'Failed to add brand: ' . $conn->error, 'type' => 'error'];
        header('Location: dashbord.php');
        exit;
    }
    } 
    else{
        $_SESSION['tost'] = ['text' => 'Brand name is required', 'type' => 'error'];
        header('Location: dashbord.php');
        exit;
    }      
    
?>