
<?php 
session_start();
include 'databaseconn.php';

if (isset($_POST['size_name']))
{
    $size_name = $_POST['size_name'];

   $sql ="INSERT INTO sizes (size_name) values ('$size_name')";
   $result  = $conn -> query($sql);
   if($result){
    $_SESSION['tost']=['text'=>'Size added successfully','type'=>'success'];
    header("Location: dashbord.php");
     exit;
   }else{
    $_SESSION['tost']=['text'=>'Failed to add size','type'=>'error'];
    header("Location: dashbord.php");
     exit;
   }
}
else{
    $_SESSION['tost']=['text'=>'Size name is required','type'=>'error'];
    header("Location: dashbord.php");
    exit;
}
?>