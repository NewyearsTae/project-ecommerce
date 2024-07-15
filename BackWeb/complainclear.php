<?php
include_once("./function.php");
$objCon = connectDB();

if ($objCon === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$complain_id = $_GET["complain_id"];
$complaindetail = $_GET["complainDetail"];
$sender = $_GET["sender"];

echo "Complain ID: " . $complain_id . "<br>";
echo "Complain Detail: " . $complaindetail . "<br>";

// ดึงข้อมูล order_id และ user_id จากตาราง complain อิงตาม complain_id
$sql_select_complain = "SELECT order_id, user_id FROM complain WHERE complain_id = '$complain_id'";
$result_select_complain = mysqli_query($objCon, $sql_select_complain);

if ($result_select_complain && mysqli_num_rows($result_select_complain) > 0) {
    $row = mysqli_fetch_assoc($result_select_complain);
    $order_id = $row["order_id"];
    $user_id = $row["user_id"];
    
    // เพิ่มข้อมูลลงในตาราง complaindetails
    $sql_insert_complaindetail = "INSERT INTO complaindetails (complain_id, order_id, user_id, description, status, sender) 
                                  VALUES ('$complain_id', '$order_id', '$user_id', '$complaindetail', 'Clear','$sender')";
    $result_insert_complaindetail = mysqli_query($objCon, $sql_insert_complaindetail);

    if ($result_insert_complaindetail) {
        // ลบข้อมูลในตาราง complain ที่มี complain_id เท่ากับ $complain_id
        $sql_update_complain = "UPDATE complain SET num = 1 WHERE complain_id = '$complain_id'";
        $result_update_complain = mysqli_query($objCon, $sql_update_complain);

        if ($result_update_complain) {
            // อัปเดตค่า Status ในตาราง orders เป็น 4
            $sql_update_orders = "UPDATE orders SET Status = '4' WHERE order_id = '$order_id'";
            $result_update_orders = mysqli_query($objCon, $sql_update_orders);

            if ($result_update_orders) {
                header("location: reportpage.php");
            } else {
                echo "Error updating status in orders table: " . mysqli_error($objCon);
            }
        } else {
            echo "Fail to update complain: " . mysqli_error($objCon);
        }
    } else {
        echo "Fail to insert complaindetail: " . mysqli_error($objCon);
    }
} else {
    echo "Complain not found: " . mysqli_error($objCon);
}

mysqli_close($objCon);
?>
