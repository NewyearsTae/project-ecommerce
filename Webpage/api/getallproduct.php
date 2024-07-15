<?php

require_once('./function.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $object = new stdClass();

        // เชื่อมต่อฐานข้อมูล
        $objCon = connectDB();

        // ดึงข้อมูลจากตาราง product พร้อมรายการสินค้ารูปภาพจากตาราง product-img
        $sql = 'SELECT product.id, product.name, product.description, product.price, product.size, product_img.img_file
                            FROM product 
                            LEFT JOIN product_img ON product.id = product_img.product_id
                            ORDER BY product.id DESC';

        $result = mysqli_query($objCon, $sql);

        if ($result) {
            $num = mysqli_num_rows($result);
            if ($num > 0) {

                $object->Result = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    extract($row);

                    // สร้าง associative array เพื่อเก็บข้อมูลสินค้ารวมถึงรูปภาพ
                    $product_item = array(
                        "name" => $name,
                        "description" => $description,
                        "price" => $price,
                        "size" => $size,
                        "img_file" => $img_file  // เปลี่ยนเป็น img_file
                    );

                    // เพิ่ม associative array ของสินค้าลงใน Result
                    array_push($object->Result, $product_item);
                }
                $object->RespCode = 200;
                $object->RespMessage = 'success';
                http_response_code(200);
                echo json_encode($object);
            } else {
                $object->RespCode = 400;
                $object->log = 0;
                $object->RespMessage = 'bad : Not Found data';
                http_response_code(400);
                echo json_encode($object);
            }
        } else {
            $object->RespCode = 500;
            $object->log = 0;
            $object->RespMessage = 'bad : bad sql';
            http_response_code(500);
            echo json_encode($object);
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($objCon);
    } else {
        http_response_code(405);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo $e->getMessage();
}

?>
