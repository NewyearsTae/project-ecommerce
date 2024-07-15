<?php
require("./function.php");
$objCon = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีการส่งค่า order_id มาหรือไม่
    if (isset($_GET["order_id"])) {
        $order_id = mysqli_real_escape_string($objCon, $_GET["order_id"]); // ตรวจสอบและทำความสะอาดข้อมูล order_id

        // ตรวจสอบว่ามีการส่งค่า tracking, sc, sender และ company มาหรือไม่
        if (isset($_POST["tracking"]) && isset($_POST["sc"]) && isset($_POST["sender"]) && isset($_POST["company"])) {
            $tracking = mysqli_real_escape_string($objCon, $_POST["tracking"]); // ตรวจสอบและทำความสะอาดข้อมูล tracking
            $sc = mysqli_real_escape_string($objCon, $_POST["sc"]); // ตรวจสอบและทำความสะอาดข้อมูล sc
            $sender = mysqli_real_escape_string($objCon, $_POST["sender"]); // ตรวจสอบและทำความสะอาดข้อมูล sender
            $company = mysqli_real_escape_string($objCon, $_POST["company"]); // ตรวจสอบและทำความสะอาดข้อมูล company

            // เพิ่มข้อมูลลงในตาราง confirmorder
            $sql = "INSERT INTO confirmorder (order_id, tracking, sc, sender, company) VALUES ('$order_id', '$tracking', '$sc', '$sender', '$company')";
            if (mysqli_query($objCon, $sql)) {
                echo "Order confirmed successfully.";

                // อัปเดตค่า num ในตาราง confirm
                $update_num_sql = "UPDATE confirm SET num = 1 WHERE order_id = '$order_id'";
                if (mysqli_query($objCon, $update_num_sql)) {
                    echo "Updated num in confirm table successfully.";
                } else {
                    echo "Error updating num in confirm table: " . mysqli_error($objCon);
                }

                // อัปเดตค่า Status ในตาราง orders เป็น 3
                $update_status_sql = "UPDATE orders SET Status = '3' WHERE order_id = '$order_id'";
                if (mysqli_query($objCon, $update_status_sql)) {
                    echo "Updated Status in orders table successfully.";
                } else {
                    echo "Error updating Status in orders table: " . mysqli_error($objCon);
                }

                // เพิ่มข้อมูลลงในตาราง orderhistory
                $insert_orderhistory_sql = "INSERT INTO orderhistory (order_id, user_id, product_id, price, tracking, sc, sender, company) 
                SELECT o.order_id, o.user_id, ol.product_id, p.price, co.tracking, co.sc, co.sender, co.company
                FROM orders AS o
                INNER JOIN order_line AS ol ON o.order_id = ol.order_id
                INNER JOIN product AS p ON ol.product_id = p.id
                INNER JOIN confirmorder AS co ON o.order_id = co.order_id
                WHERE o.order_id = '$order_id'";

                if (mysqli_query($objCon, $insert_orderhistory_sql)) {
                    echo "Data inserted into orderhistory successfully.";
                } else {
                    echo "Error inserting data into orderhistory: " . mysqli_error($objCon);
                }

                // ทำการลบข้อมูลในตาราง order_line
                $delete_order_line_sql = "DELETE FROM order_line WHERE order_id = '$order_id'";
                if (mysqli_query($objCon, $delete_order_line_sql)) {
                    echo "Previous order line data deleted successfully.";
                } else {
                    echo "Error deleting previous order line data: " . mysqli_error($objCon);
                }

                header("location: orderconfirm.php");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($objCon);
            }
        } else {
            echo "Error: tracking, sc, sender, or company not set.";
        }
    } else {
        header("location: orderconfirm.php"); // กลับไปที่หน้า orderconfirm.php เมื่อไม่มี order_id ส่งมา
        exit;
    }
} else {
    // ไม่มีการส่งข้อมูลด้วยวิธี POST กลับไปหน้า orderconfirm.php
    header("location: orderconfirm.php");
    exit;
}
?>
