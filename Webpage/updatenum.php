<?php

require_once("./api/function.php");
$objCon = connectDB();

$order_id = mysqli_real_escape_string($objCon, $_GET['order_id']);
$user_id = mysqli_real_escape_string($objCon, $_GET['user_id']);

// คำสั่ง SQL เพื่ออัปเดตค่า num และ created_at_2 ในตาราง orderhistory
$sql_update_orderhistory = "UPDATE orderhistory SET num = 1, created_at_2 = NOW() WHERE order_id = '$order_id'";

if ($objCon->query($sql_update_orderhistory) === TRUE) {
    // อัปเดตค่า Status ในตาราง orders เป็น 4
    $sql_update_orders = "UPDATE orders SET Status = '4' WHERE order_id = '$order_id'";
    
    if ($objCon->query($sql_update_orders) === TRUE) {
        $url = "Trackingpage.php?order_id=$order_id&user_id=$user_id&isLoggedIn=true#tracking-page";
        header("Location: $url");
        exit;
    } else {
        // กรณีเกิดข้อผิดพลาดในการอัปเดตค่า Status ในตาราง orders
        echo "Error updating order status: " . $objCon->error;
    }
} else {
    // กรณีเกิดข้อผิดพลาดในการอัปเดตค่า num หรือ created_at_2 ในตาราง orderhistory
    echo "Error updating order history: " . $objCon->error;
}

// ปิดการเชื่อมต่อ
$objCon->close();
?>
