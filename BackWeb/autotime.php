<?php
require("./function.php");
$objCon = connectDB();

if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($objCon, $_GET['order_id']); // ตรวจสอบและทำความสะอาดข้อมูล order_id

    // ตั้งค่าเวลาให้เป็น 00:00:00
    $timeStamp = strtotime(date("Y-m-d"));
    $created_at_2 = date("Y-m-d H:i:s", $timeStamp);

    // อัปเดตเวลาและค่า num ในตาราง orderhistory
    $sql_update_orderhistory = "UPDATE orderhistory SET created_at_2 = '$created_at_2', num = 1 WHERE order_id = '$order_id'";
    if (mysqli_query($objCon, $sql_update_orderhistory)) {
        echo "Order history updated successfully.";
    } else {
        echo "Error updating order history: " . mysqli_error($objCon);
    }

    // อัปเดตค่า Status ในตาราง orders เป็น 4
    $sql_update_orders = "UPDATE orders SET Status = '4' WHERE order_id = '$order_id'";
    if (mysqli_query($objCon, $sql_update_orders)) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status: " . mysqli_error($objCon);
    }

    // ส่งกลับไปยังหน้าเดิมหลังจากเซ็ตเวลาและอัปเดตสถานะเรียบร้อยแล้ว
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    // หากไม่ได้รับ order_id จากการส่งค่าผ่าน URL ให้ redirect กลับไปหน้าก่อนหน้า
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
?>
