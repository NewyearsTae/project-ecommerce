<?php
// เชื่อมต่อกับฐานข้อมูล
require("./api/function.php");
$objCon = connectDB();
$user_id = $_GET["user_id"];
$totalPrice = $_POST["totalPrice"];

// ตรวจสอบว่ามีการใส่รูป receipt หรือไม่
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    // รับข้อมูลรูปจากฟอร์ม
    $receipt_tmp = $_FILES['receipt']['tmp_name'];
    $receipt_name = $_FILES['receipt']['name'];

    // ย้ายไฟล์รูปไปยังโฟลเดอร์ที่ต้องการเก็บ
    $upload_directory = "receipt/";
    $receipt_path = $upload_directory . $receipt_name;
    move_uploaded_file($receipt_tmp , $receipt_path);
} else {
    // ถ้าไม่มีการใส่รูป receipt ให้แจ้งเตือนและหยุดการทำงาน
    echo "Please upload receipt image.";
    exit;
}

// คำสั่ง SQL เพื่อดึงค่า num จากตาราง product โดยอ้างอิงจาก product_id ในตะกร้า (cart)
$sql_check_product_availability = "SELECT num FROM product WHERE id IN (SELECT product_id FROM cart WHERE user_id = '$user_id')";
$result = mysqli_query($objCon, $sql_check_product_availability);

// วนลูปเพื่อตรวจสอบค่า num ของสินค้าที่มีในตะกร้า
while ($row = mysqli_fetch_assoc($result)) {
    // เช็คว่า num เป็น 0 หรือไม่
    if ($row['num'] == 1) {
        // ถ้า num เป็น 0 ให้แจ้งเตือนว่าสินค้าหมดแล้ว
        echo "สินค้าหมดแล้ว";
        exit;
    }
}
// คำสั่ง SQL เพื่อเพิ่มข้อมูลลงในตาราง "orders"
$sql_insert_order = "INSERT INTO orders (user_id, total_price, receipt) VALUES ('$user_id', '$totalPrice', '$receipt_name')";
mysqli_query($objCon, $sql_insert_order);

// ดึง order_id ที่เพิ่มล่าสุด
$order_id = mysqli_insert_id($objCon);

// คำสั่ง SQL เพื่อเพิ่มข้อมูลลงในตาราง "order_line"
$sql_insert_order_line = "INSERT INTO order_line (order_id, product_id) SELECT '$order_id', product_id FROM cart WHERE user_id = '$user_id'";
mysqli_query($objCon, $sql_insert_order_line);

$sql_update_product_num = "UPDATE product 
                           SET num = 1 
                           WHERE id IN (SELECT product_id FROM cart WHERE user_id = '$user_id')";
mysqli_query($objCon, $sql_update_product_num);

// คำสั่ง SQL เพื่อลบข้อมูลในตาราง "cart" ที่มี product_id ตรงกับที่ถูกเพิ่มเข้าตาราง "order_line"
$sql_delete_cart = "DELETE cart FROM cart INNER JOIN order_line ON cart.product_id = order_line.product_id WHERE cart.user_id = '$user_id'";
mysqli_query($objCon, $sql_delete_cart);

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($objCon);

header("location: ConfirmOrders.php?isLoggedIn=true&user_id=$user_id&order_id=$order_id");
exit;
?>
