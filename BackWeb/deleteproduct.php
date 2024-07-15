<?php
include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$id = $_GET["user_id"];
$lot = $_GET["lot"];

try {
    // Begin transaction
    $objCon->begin_transaction();

    // ลบข้อมูลในตาราง product_img ที่มี product_id เท่ากับ $id
    $sql_delete_product_img = "DELETE FROM product_img WHERE product_id = ?";
    $stmt = $objCon->prepare($sql_delete_product_img);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ลบข้อมูลในตาราง product ที่มี id เท่ากับ $id
    $sql_delete_product = "DELETE FROM product WHERE id = ?";
    $stmt = $objCon->prepare($sql_delete_product);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Commit transaction
    $objCon->commit();

    // Redirect to product page
    header("location:product.php");
    exit();
} catch (mysqli_sql_exception $e) {
    // Rollback transaction in case of error
    $objCon->rollback();

    // Check if the error is due to a foreign key constraint
    if ($e->getCode() === 1451) {
        echo "ไม่สามารถลบสินค้านี้ได้ เพราะว่าสินค้านี้อยู่ใน Order ลูกค้า";
        echo '<script>alert("ไม่สามารถลบสินค้านี้ได้ เพราะว่าสินค้านี้อยู่ใน Order ลูกค้า");window.location="lotshowtest.php?lot=' . $lot . '";</script>';
    } else {
        echo "An error occurred: " . $e->getMessage();
    }
} finally {
    // Close the statement if it was prepared
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    // Close the connection
    $objCon->close();
}
?>
