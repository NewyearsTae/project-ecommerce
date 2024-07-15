<?php
include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$id = $_GET["order_id"];

// ลบข้อมูลในตาราง order_line ที่มี order_id เท่ากับ $id
$sql_delete_order_line = "DELETE FROM order_line WHERE order_id = '$id'";
$result_delete_order_line = mysqli_query($objCon, $sql_delete_order_line);

// ลบข้อมูลในตาราง orders ที่มี order_id เท่ากับ $id
$sql_delete_order = "DELETE FROM orders WHERE order_id = '$id'";
$result_delete_order = mysqli_query($objCon, $sql_delete_order);

if ($result_delete_order_line && $result_delete_order) {
    header("location: order.php");
} else {
    echo "Fail";
}
?>
