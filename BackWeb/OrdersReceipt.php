<?php
include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
session_start();
if (!isset($_SESSION['user_login'])) { 
    header("location: login.php"); 
    exit;
}

if(isset($_GET["order_id"])) {
  $order_id= $_GET["order_id"];
  // ต่อไปคุณสามารถใช้ $user_id ได้ต่อไปในโค้ดของคุณ
} else {
  // ถ้าไม่มี user_id ที่ส่งมา
  // คุณสามารถจัดการในส่วนนี้ได้ตามต้องการ เช่น แสดงข้อความแจ้งเตือนหรือทำอะไรก็ตามที่ต้องการ
}


?>

<!DOCTYPE html>
<html>
  <head>
    <title>ChicShop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="receipt.css">
    <link rel="stylesheet" href="cartsidebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- script
    ================================================== -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="js/modernizr.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
  function printPage() {
    window.print();
  }
</script>
</head>
 
<body>
    <div class="paperbg">
    <div class="paperorder">
        <div class="toppic">
        <div class="logopaper">
        <img src="/Webpage/images/main-logo.png" class="logo">
        </div>
        <div class="NOorders">
        <div class="namepddt">
            Orders No. &nbsp;&nbsp;&nbsp;&nbsp;<p><?php echo isset($order_id) ? $order_id : '...'; ?></p>
        </div>
        </div>
        </div>
        <?php
        // คิวรี่ข้อมูลจากตาราง orders เพื่อหาข้อมูลที่ต้องการ
        $sql = "SELECT u.u_fullname, u.u_address, u.Phonenumber
                FROM orders AS o
                JOIN user AS u ON o.user_id = u.u_id
                WHERE o.order_id = ?";
        $stmt = mysqli_prepare($objCon, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $u_fullname, $u_address, $Phonenumber);
            mysqli_stmt_fetch($stmt);

            // แสดงข้อมูลในแต่ละเท็บของ HTML
            echo '<div class="detailorderpp">';
            echo '<div class="namepddt">';
            echo '<h4>Name</h4>&nbsp;&nbsp;&nbsp;&nbsp;<p>' . $u_fullname . '</p>';
            echo '</div>';
            echo '<div class="namepddt">';
            echo '<h4>Address</h4>&nbsp;&nbsp;&nbsp;&nbsp;<p>' . $u_address . '</p>';
            echo '</div>';
            echo '<div class="namepddt">';
            echo '<h4>Phone number</h4>&nbsp;&nbsp;&nbsp;&nbsp;<p>' . $Phonenumber . '</p>';
            echo '</div>';
            echo '</div>';
        }

        mysqli_stmt_close($stmt);
        ?>

    <div class="tablepp">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">ID Product</th>
      <th scope="col">Product Name</th>
      <th scope="col">Size</th>
      <th scope="col">Price</th>
    </tr>
  </thead>

  <?php

// คิวรี่ข้อมูลจากตาราง order_line และ product
$sql = "SELECT ol.product_id, p.name, p.size, p.price
        FROM order_line AS ol
        INNER JOIN product AS p ON ol.product_id = p.id
        WHERE ol.order_id = ?";
$stmt = mysqli_prepare($objCon, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total_price = 0; // เริ่มต้นคำนวณราคาทั้งหมดเป็น 0

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (mysqli_num_rows($result) > 0) {
    // วนลูปเพื่อแสดงข้อมูลสินค้าที่มีใน order_line
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<th scope="row">' . $row['product_id'] . '</th>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['size'] . '</td>';
        echo '<td>' . number_format($row['price'], 2) . ' THB</td>';
        echo '</tr>';
        
        // เพิ่มราคาของสินค้าลงในราคาทั้งหมด
        $total_price += $row['price'];
    }
}

mysqli_stmt_close($stmt);

  ?>
  <thead>
    <tr>
        <th scope="col">Total Price</th>
        <th scope="col"><?php echo number_format($total_price, 2); ?> THB</th>
    </tr>
</thead>
</table>
    </div>
    <div style="text-align: right;">
    <button onclick="printPage()">Print (Ctrl + P)</button>
</div>


    </div>
    </div>
    


</body>

  </html>