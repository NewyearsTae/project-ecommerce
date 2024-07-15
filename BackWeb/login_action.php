<?php
session_start();
if (isset($_SESSION['user_login'])) {
    // ถ้ามี Session ของผู้ใช้งานอยู่แล้ว ให้กำหนดค่า isLoggedIn เป็น true
    $isLoggedIn = true;
    // ส่วนอื่น ๆ ของโค้ดที่ทำงานหลังจากการล็อกอิน เช่น redirect ไปยังหน้าหลัก
    header("location: index.php");
    exit;
} else {
    // ถ้ายังไม่มี Session ของผู้ใช้งาน ให้กำหนดค่า isLoggedIn เป็น false
    $isLoggedIn = false;
}

include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($objCon, $_POST['username']);
    $password = mysqli_real_escape_string($objCon, $_POST['password']);

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ username
    $strSQL = "SELECT * FROM user WHERE u_username = '$username'";
    $objQuery = mysqli_query($objCon, $strSQL);
    $user = mysqli_fetch_assoc($objQuery);

    // ตรวจสอบว่ามีผู้ใช้นี้หรือไม่
    if ($user) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['u_password'])) {
            // รหัสผ่านถูกต้อง
            $_SESSION['user_login'] = array(
                'id' => $user['u_id'],
                'fullname' => $user['u_fullname'],
                'level' => $user['u_level']
            );
            // หลังจากล็อกอินสำเร็จ กำหนดค่า isLoggedIn เป็น true
            $isLoggedIn = true;
            if ($user['u_level'] === 'user') {
                header("Location: /Webpage/index.php?isLoggedIn=true&user_id=" . $user['u_id']);
            } elseif ($user['u_level'] === 'administrator') {
                header("Location: index.php");
            }
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo '<script>alert("Password ไม่ถูกต้อง!");window.location="login.php";</script>';
        }
    } else {
        // ไม่พบผู้ใช้
        echo '<script>alert("Username หรือ Password ไม่ถูกต้อง!");window.location="login.php";</script>';
    }
}
?>
