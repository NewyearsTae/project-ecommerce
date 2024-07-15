<?php
// เชื่อมต่อกับฐานข้อมูล
require("./api/function.php");
$objCon = connectDB();
$id=$_GET["id"];
$user_id=$_GET["user_id"];

// คำสั่ง SQL เพื่อเพิ่มข้อมูลลงในตาราง "Cart"
$sql_insert_cart = "INSERT INTO cart (product_id, user_id) VALUES ('$id', '$user_id')";

// ทำการเพิ่มข้อมูลลงในตาราง "Cart"
if (mysqli_query($objCon, $sql_insert_cart)) {
    header("Location: /Webpage/index.php?isLoggedIn=true&user_id=" . $user_id);
} else {
    
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($objCon);
?>
