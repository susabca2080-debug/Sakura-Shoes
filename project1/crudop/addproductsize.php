<?php
include "databaseconn.php";
if($_SERVER['REQUEST_METHOD'] == 'POST')
{   
// Check if product id and at least one size is selected
if(!empty($_POST['product_id']) && !empty($_POST['size_id']))
{
    $product_id = (int)$_POST['product_id'];
    $sizes = $_POST['size_id'];       
    $stocks = $_POST['stock_quantity']; 

    foreach($sizes as $size_id)
    {
        $size_id = (int)$size_id;
        $stock_count = isset($stocks[$size_id]) ? (int)$stocks[$size_id] : 0;

        $sql = "INSERT INTO product_sizes (product_id, size_id, size_stock)
                VALUES ($product_id, $size_id, $stock_count)";

        if(!$conn->query($sql))
        {
            echo "Insert failed for size $size_id : " . $conn->error;
            exit;
        }
    }

    // -------------------------
    // SUCCESS ONLY AFTER LOOP
    // -------------------------
    $conn->close();
    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=Stock added successfully");
    exit;
}
}
else
{
    echo "Please select at least one size.";
     $_SESSION['tost']=['text'=>'size added successfully.','type'=>'success'];
    header("location:dashbord.php");
   
    
}
?>
<!-- this is the code where i was confused 
 mainproblem
 both if and else is executed
 it is because one time it take data from form and if executes and again when it reaches to that form there is no 
 data so taking that data in this file cause else to execute 
 
 now dont khow hou to solve-->