<?php

require_once("./api/function.php");
$objCon = connectDB();

$order_id = $_GET['order_id'];
$user_id = $_GET['user_id'];

// คำสั่ง SQL เพื่ออัปเดตค่า num และเวลาที่กดลงใน created_at_2 เมื่อคลิกปุ่ม "ได้รับสินค้าแล้ว"
$sql_update_num = "UPDATE orderhistory SET num = 1, created_at_2 = CURRENT_TIMESTAMP WHERE order_id = '$order_id'";

// รันคำสั่ง SQL เพื่ออัปเดตค่า num และเวลาที่กดลงใน created_at_2
if ($objCon->query($sql_update_num) === TRUE) {
    
    $url = "Trackingpage.php?order_id=$order_id&user_id=$user_id&isLoggedIn=true#tracking-page";
    header("Location: $url");
} else {
    // กรณีเกิดข้อผิดพลาดในการอัปเดตค่า num และเวลาที่กดลงใน created_at_2
    echo "Error updating record: " . $objCon->error;
}

// ปิดการเชื่อมต่อ
$objCon->close();
?>
