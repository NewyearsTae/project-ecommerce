<?php
require("./function.php");
$objCon = connectDB();

// ตรวจสอบว่ามีการส่งค่า order_id มาหรือไม่
if (!isset($_GET["order_id"])) {
    echo "Invalid order ID.";
    exit;
}

$order_id = $_GET["order_id"];

// เรียกข้อมูล product_id จากตาราง order_line ที่มี order_id ตรงกับที่ระบุ
$sql_select_product = "SELECT product_id FROM order_line WHERE order_id = ?";
$stmt = mysqli_prepare($objCon, $sql_select_product);
mysqli_stmt_bind_param($stmt, "i", $order_id); // "i" หมายถึง order_id เป็น integer

if (!$stmt) {
    echo "Error: " . mysqli_error($objCon);
    exit;
}

mysqli_stmt_execute($stmt);
$result_select_product = mysqli_stmt_get_result($stmt);

if (!$result_select_product) {
    echo "Error: " . mysqli_error($objCon);
    exit;
}


while ($row_product = mysqli_fetch_assoc($result_select_product)) {
    $product_id = mysqli_real_escape_string($objCon, $row_product['product_id']);

    // เพิ่มข้อมูลลงในตาราง confirm
    $sql_insert_confirm = "INSERT INTO confirm (order_id, product_id) VALUES (?, ?)";
    $stmt_insert_confirm = mysqli_prepare($objCon, $sql_insert_confirm);
    mysqli_stmt_bind_param($stmt_insert_confirm, "ii", $order_id, $product_id); // "ii" หมายถึง order_id และ product_id เป็น integer

    if (!$stmt_insert_confirm) {
        echo "Error: " . mysqli_error($objCon);
        exit;
    }

    mysqli_stmt_execute($stmt_insert_confirm);

    if (mysqli_stmt_affected_rows($stmt_insert_confirm) <= 0) {
        echo "Error: " . mysqli_error($objCon);
        exit;
    }

    mysqli_stmt_close($stmt_insert_confirm);

}
// เปลี่ยนค่าคอลัมน์ "Status" จาก "No" เป็น "Yes" ในตาราง "orders"
$sql_update_status = "UPDATE orders SET Status = '2' WHERE order_id = ?";
$stmt_update_status = mysqli_prepare($objCon, $sql_update_status);
mysqli_stmt_bind_param($stmt_update_status, "i", $order_id);

if (!$stmt_update_status) {
    echo "Error:1 " . mysqli_error($objCon);
    exit;
}

mysqli_stmt_execute($stmt_update_status);

if (mysqli_stmt_affected_rows($stmt_update_status) <= 0) {

}

mysqli_stmt_close($stmt);

echo '<script>window.open("Ordersreceipt.php?order_id=' . $order_id . '", "_blank");</script>';
header("Location: order.php");

?>
