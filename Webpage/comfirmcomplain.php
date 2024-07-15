<?php
require("./api/function.php");
$objCon = connectDB();

// ดึงค่า complain_id, user_id และ isLoggedIn จาก URL
$complain_id = $_GET['complain_id'];
$user_id = $_GET['user_id'];
$isLoggedIn = $_GET['isLoggedIn'];

// ทำการเปลี่ยนค่า accept เป็น 1 ในตาราง complaindetails อิงตาม complain_id ที่ส่งมา
$sql = "UPDATE complaindetails SET accept = 1 WHERE complain_id = '$complain_id'";
mysqli_query($objCon, $sql);

// กลับไปที่หน้า Statuscomplain.php พร้อมส่งค่า isLoggedIn และ user_id กลับไปด้วย
header("Location: Statuscomplain.php?isLoggedIn=true&user_id=$user_id");
exit();
?>