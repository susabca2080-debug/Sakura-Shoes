<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'databaseconn.php';
session_start();
if(isset($_POST['product_name']) && isset($_POST['category']) && isset($_POST['purchase_price']) && isset($_POST['selling_price']) && isset($_POST['discount_price']) && isset($_POST['brand']) && isset($_POST['description']) && isset($_POST['tags']) && isset($_POST['status']) && isset($_FILES['image']))
{    
     $product_name = $_POST['product_name'];
     $category= $_POST['category'];
     $purchase_price = $_POST['purchase_price'];
     $selling_price = $_POST['selling_price'];
     $discount_price = $_POST['discount_price'];
     $brand = $_POST['brand'];
     $description = $_POST['description'];
     $tags = $_POST['tags'];
     $status = $_POST['status'];

    //  image upload
    $image= $_FILES['image'];
    $imgname=time().'-'.$image['name'];
    $imagetmp=$image['tmp_name'];
    $imgpath='images/'.$imgname;
    //image1 upload
    $image1 = isset($_FILES['image1']) ? $_FILES['image1'] : null;
    $imgname1=time().'-'.$image1['name'];
    $imagetmp1=$image1['tmp_name'];
    $imgpath1='images/'.$imgname1;
    //image2 upload
    $image2 = isset($_FILES['image2']) ? $_FILES['image2'] : null;
    $imgname2=time().'-'.$image2['name'];
    $imagetmp2=$image2['tmp_name'];
    $imgpath2='images/'.$imgname2;
    if(move_uploaded_file($imagetmp, $imgpath)){
      // Image1 and Image2 are optional
      if(isset($imagetmp1) && $imagetmp1){
        move_uploaded_file($imagetmp1, $imgpath1);
      }
      if(isset($imagetmp2) && $imagetmp2){
        move_uploaded_file($imagetmp2, $imgpath2);
      }

   $stmt = $conn->prepare("INSERT INTO product (product_name, purchase_price, selling_price, discount_price, category, brand_id, description, image1, image2, image3, tags, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
   if($stmt === false) {
       die('Prepare failed: ' . $conn->error);
   }
   $stmt->bind_param(
       'sdddsdssssss',
       $product_name,
       $purchase_price,
       $selling_price,
       $discount_price,
       $category,
       $brand,
       $description,
       $imgname,
       $imgname1,
       $imgname2,
       $tags,
       $status
   );
   $result = $stmt->execute();
   if($result){
       $_SESSION['tost']=['text'=>'product added successfully','type'=>'success'];
    header("Location: dashbord.php?msg=New record created successfully");
       exit;
   }else{
       die('SQL Error: ' . $stmt->error);
   }
   $stmt->close();
    }else{
      $_SESSION['tost']=['text'=>'Insert at least one image','type'=>'error'];
      header("Location: ".$_SERVER['PHP_SELF']??'dashbord.php');
        exit;
    }
}
else{
    $_SESSION['tost']=['text'=>'All fields are required','type'=>'error'];
    header("Location: dashbord.php");
    header("Location: dashbord.php");
    exit;
}

?>
