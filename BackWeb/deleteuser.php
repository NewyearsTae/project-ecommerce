<?php
include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$u_id=$_GET["user_id"];
$sql="DELETE FROM user WHERE u_id=$u_id";

$result=mysqli_query( $objCon, $sql );
if($result) {
    header("location:admin.php");
}else{
    echo"Fail";
}
?>