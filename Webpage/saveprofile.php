<?php
require("./api/function.php");
$objCon = connectDB();

// ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์ม POST หรือไม่
if (isset($_POST["submit"])) {
    $user_id = $_GET["user_id"]; // รับค่า user_id จาก URL
    
    // รับค่าข้อมูลจากฟอร์ม
    $u_fullname = $_POST["u_fullname"];
    $u_address = $_POST["u_address"];
    $Phonenumber = $_POST["Phonenumber"];
    
    // ตรวจสอบความถูกต้องของข้อมูล (ในที่นี้คือตรวจสอบว่าชื่อเต็มไม่ว่างเปล่า)
    $errors = [];

    // ตรวจสอบว่ามีข้อผิดพลาดหรือไม่
    if (count($errors) > 0) {
        // กรณีมีข้อผิดพลาด ให้แสดงข้อผิดพลาดและหยุดการทำงาน
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger small-alert'>$error</div>";
        }
    } else {
        // กรณีไม่มีข้อผิดพลาด ให้ทำการอัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE user SET u_fullname = ?, u_address = ?, Phonenumber = ? WHERE u_id = ?";
        $stmt = mysqli_stmt_init($objCon);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $u_fullname, $u_address, $Phonenumber, $user_id);
            mysqli_stmt_execute($stmt);
            header("Location: /Webpage/Profile.php?isLoggedIn=true&user_id=" . $user['id']);
            exit;
        } else {
            echo '<div class="alert alert-danger small-alert">Something went wrong</div>';
        }
    }
}
?>
