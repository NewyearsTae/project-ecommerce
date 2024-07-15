<?php
require('function.php');

$objCon = connectDB();

$name = $_POST["empname"];

$sql = "SELECT * FROM user WHERE u_fullname LIKE '%$name%'";
$result = mysqli_query($objCon, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
        echo "<br>"; // แสดงช่องว่างระหว่างแถวผลลัพธ์
    }
} else {
    echo "No results found.";
}
?>