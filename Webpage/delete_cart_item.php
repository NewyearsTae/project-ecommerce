<?php
// เชื่อมต่อกับฐานข้อมูล
require_once("./api/function.php");
$objCon = connectDB();

// ตรวจสอบว่ามีค่า product_id ที่ส่งมาหรือไม่
if(isset($_POST["id"])) {
    $product_id = $_POST["id"];

    // คำสั่ง SQL เพื่อลบรายการในตาราง "cart" โดยใช้ product_id เป็นเงื่อนไข
    $sql_delete_cart = "DELETE FROM cart WHERE id = $product_id";
    
    // ทำการ query และตรวจสอบผลลัพธ์
    if(mysqli_query($objCon, $sql_delete_cart)) {
        // ส่งข้อความกลับเพื่อแสดงว่าลบสำเร็จ
        echo "Deleted successfully.";
    } else {
        // หากเกิดข้อผิดพลาดในการลบ
        echo "Error: " . mysqli_error($objCon);
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($objCon);
?>
