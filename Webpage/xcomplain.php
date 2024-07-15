<?php
// เชื่อมต่อกับฐานข้อมูล
include_once("./api/function.php");
$objCon = connectDB();

// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $order_id = mysqli_real_escape_string($objCon, $_POST["order_id"]);
    $user_id = mysqli_real_escape_string($objCon, $_POST["user_id"]);
    $description = mysqli_real_escape_string($objCon, $_POST["description"]);

    if (!empty($_FILES['complain']['name'])) {
        // รับข้อมูลรูปจากฟอร์ม
        $complain_tmp = $_FILES['complain']['tmp_name'];
        $complain_name = $_FILES['complain']['name'];
    
        // ย้ายไฟล์รูปไปยังโฟลเดอร์ที่ต้องการเก็บ
        $upload_directory = "complain/";
        $complain_path = $upload_directory . $complain_name;
        move_uploaded_file($complain_tmp , $complain_path);
    } else {
        // ถ้าไม่มีการใส่รูป complain ให้แสดงข้อความแจ้งเตือนและหยุดการทำงาน
        echo "Please upload complain image.";
        exit;
    }    

    // เพิ่มข้อมูลลงในตาราง complain
    $sql_insert_complain = "INSERT INTO complain (order_id, user_id, description, image) VALUES ('$order_id', '$user_id', '$description', '$complain_name')";
    
    // ทำการ query เพื่อเพิ่มข้อมูล
    if (mysqli_query($objCon, $sql_insert_complain)) {
        // อัปเดตค่า Status ในตาราง orders เป็น 5
        $sql_update_orders = "UPDATE orders SET Status = '5' WHERE order_id = '$order_id'";
        if (mysqli_query($objCon, $sql_update_orders)) {
            header("location: Statuscomplain.php?isLoggedIn=true&user_id=$user_id");
            exit;
        } else {
            echo "Error updating order status: " . mysqli_error($objCon);
        }
    } else {
        echo "ผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($objCon);
    }    

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    mysqli_close($objCon);
}
?>
