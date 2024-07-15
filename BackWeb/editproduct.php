<?php

include_once("./function.php");
$objCon = connectDB();
$id=$_GET["user_id"];
$lot=$_GET["lot"];

// เริ่ม session
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_login'])) {
    header("location: login.php");
    exit;
}

// ตรวจสอบสิทธิ์การเข้าถึง
$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}

$sql = "SELECT * FROM product WHERE id = '$id'";
$result = mysqli_query($objCon,$sql);

$sql_img = "SELECT * FROM product_img WHERE product_id = '$id'";
$result_img = mysqli_query($objCon, $sql_img);

$row = mysqli_fetch_assoc($result);


// ตรวจสอบการส่งข้อมูลแบบ POST
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $size = $_POST["size"];
    $lot = $_POST["lot"];
    // Check if a new image is uploaded
    if (isset($_FILES['images'])) {
        $total_images = count($_FILES['images']['name']);
        $product_images = [];
    
        // วนลูปเก็บรูปภาพทั้งหมด
        for ($i = 0; $i < $total_images; $i++) {
            $image_name = $_FILES['images']['name'][$i]; // รับชื่อไฟล์ภาพ
            $temp_image = $_FILES['images']['tmp_name'][$i]; // รับที่อยู่ของไฟล์ภาพชั่วคราว
            
            // ย้ายไฟล์ภาพไปยังโฟลเดอร์ที่ต้องการเก็บไฟล์
            move_uploaded_file($temp_image, "upload/$image_name");
            
            // เก็บชื่อไฟล์ภาพไว้ในอาร์เรย์
            $product_images[] = $image_name;
        }
    
        // SQL เพื่ออัปเดตข้อมูลในตาราง product
        $sql_product = "UPDATE product SET name = ?, description = ?, price = ?, size = ? WHERE id = ?";
        $stmt_product = mysqli_stmt_init($objCon);
        if (mysqli_stmt_prepare($stmt_product, $sql_product)) {
            mysqli_stmt_bind_param($stmt_product, "ssdsi", $name, $description, $price, $size, $id);
            mysqli_stmt_execute($stmt_product);
        }
    
        // SQL เพื่อลบรูปภาพเก่าในตาราง product_img
        $sql_delete_images = "DELETE FROM product_img WHERE product_id = ?";
        $stmt_delete_images = mysqli_stmt_init($objCon);
        if (mysqli_stmt_prepare($stmt_delete_images, $sql_delete_images)) {
            mysqli_stmt_bind_param($stmt_delete_images, "i", $id);
            mysqli_stmt_execute($stmt_delete_images);
        }
    
        // SQL เพื่อเพิ่มข้อมูลรูปภาพใหม่ลงในตาราง product_img
        foreach ($product_images as $image) {
            $sql_product_img = "INSERT INTO product_img (product_id, img_file) VALUES (?, ?)";
            $stmt_product_img = mysqli_stmt_init($objCon);
            if (mysqli_stmt_prepare($stmt_product_img, $sql_product_img)) {
                mysqli_stmt_bind_param($stmt_product_img, "is", $id, $image);
                mysqli_stmt_execute($stmt_product_img);
            }
        }
    
        // ส่งกลับไปยังหน้า product.php
        header("location: lotshowtest.php?lot=" . $lot);
    } else {
        // หากไม่มีการเลือกไฟล์ภาพ
        echo '<script>alert("Please select image files");window.location="editproduct.php?user_id=' . $id . '";</script>';
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ChicShop-Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/editconsole.css" rel="stylesheet">

</head>

<body>
    <div class="">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <div class="container-fluid">
                    <a href="Admin.php" class="navbar-brand mx-4 mb-3">
                        <img src="/Webpage/images/main-logo.png" class="logo">
                </a>
                </div>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <i class="fa fa-user-circle fa-3x rounded-circle" style="width: 40px; height: 40px;"></i>
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $user['fullname']; ?></h6>
                        <span><?php echo $user['level']; ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="dashbord.php" class="nav-item nav-link"><i
                            class="fa fa-tachometer-alt me-2"></i>Dashbord</a>
                    <a href="Admin.php" class="nav-item nav-link "><i class="fa fa-users me-2"></i>Member</a>
                    <a href="income.php" class="nav-item nav-link active"><i class="fa fa-box-open me-2"></i>Product</a>
                    <a href="order.php" class="nav-item nav-link"><i class="fa fa-shopping-cart me-2"></i>Order</a>
                    <a href="orderconfirm.php" class="nav-item nav-link"><i
                            class="fa fa-check-circle text-dark me-2"></i>Comfirm Order</a>
                    <a href="shipping.php" class="nav-item nav-link"><i class="fa fa-truck me-2"></i>Shipping</a>
                    <a href="history.php" class="nav-item nav-link"><i class="fa fa-history me-2"></i>Purchase
                        History</a>
                    <a href="reportpage.php" class="nav-item nav-link"><i
                            class="fa fa-exclamation-triangle me-2"></i>Complain</a>
                    <a href="complaindetails.php" class="nav-item nav-link"><i
                            class="fa fa-exclamation-circle me-2"></i>Complain History</a>
                    <a href="report.php" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Report</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
       

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle fa-2x rounded-circle" style="width: 40px; height: 40px;"></i>
                            <span class="d-none d-lg-inline-flex"><?php echo $user['fullname']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="logout_action.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
        <form method="post" action="editproduct.php?user_id=<?php echo $row["id"]; ?>" enctype="multipart/form-data" >
            <div class="bgeditmain">
                <div class="bgedit">
                <br>
                <h3>EDIT PRODUCT</h3>
                <br>
                <div class="mb-3">
                <?php          
                    // ตรวจสอบว่ามีการเลือกรูปภาพใหม่หรือไม่
                    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                        // ถ้ามีการเลือกรูปใหม่ ให้แสดงรูปภาพใหม่ที่เลือกเท่านั้น
                        $file_names = $_FILES['image']['name'];
                        foreach ($file_names as $file_name) {
                ?>
                            <img src="upload/<?php echo $file_name; ?>" alt="New Product Image" style="max-width: 100%; max-height: 200px; margin-bottom: 10px;">
                            <?php
                        }
                    } else {
                        // ถ้าไม่มีการเลือกรูปใหม่ ให้แสดงรูปเก่าทั้งหมด
                        while ($row_img = mysqli_fetch_assoc($result_img)) {
                            ?>
                            <img src="upload/<?php echo $row_img['img_file']; ?>" alt="Product Image" style="max-width: 100%; max-height: 200px; margin-bottom: 10px;">
                            <?php
                        }
                    }
                            ?>
                            </div>
                <div class="mb-3">
                    <!-- ส่วนเลือกไฟล์ภาพและแสดงตัวอย่างรูป -->
                    <input type="file" class="form-control" id="images" name="images[]" style="display: none" onchange="previewImages()" multiple>
                    <label for="images" class="btn btn-primary">Choose Files</label>
                    <div id="selectedFileNames"></div>
                    <div id="imagePreviewContainer" class="mt-3"></div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="name" required value="<?php echo $row["name"]; ?>">
                    <label for="name" class="form-label">Name</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="description" name="description" placeholder="Description" required ><?php echo $row["description"]; ?></textarea>
                    <label for="description" class="form-label">Description</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="price" name="price" placeholder="Price" step="0.01" required value="<?php echo $row["price"]; ?>">
                    <label for="u_price" class="form-label">PRICE</label>
                </div>
                <label for="u_size" class="form-label">SIZE</label>
                <div class="form-floating mb-3">
                    <select class="form-control" id="size" name="size" required>
                        <option value="">Choose size</option>
                        <option value="S" <?php if ($row["size"] == 'S') echo 'selected'; ?>>S</option>
                        <option value="M" <?php if ($row["size"] == 'M') echo 'selected'; ?>>M</option>
                        <option value="L" <?php if ($row["size"] == 'L') echo 'selected'; ?>>L</option>
                        <option value="XL" <?php if ($row["size"] == 'XL') echo 'selected'; ?>>XL</option>
                        <option value="XXL" <?php if ($row["size"] == 'XXL') echo 'selected'; ?>>XXL</option>
                        <option value="XXXL" <?php if ($row["size"] == 'XXXL') echo 'selected'; ?>>XXXL</option>
                    </select>
                </div>
                    <input type="hidden" name="lot" value="<?php echo $lot; ?>">
                    <button type="submit" name="submit" class="btn btn-success py-3 w-100 mb-4">ADD</button>
                    <a href="lotshowtest.php?lot=<?php echo $lot; ?>" class="w-100 btn btn-lg btn-primary">Back</a>
                </div>
                </div>            
            </div>
        </form>
        

           

        <script>
    function previewImages(inputId, previewContainerId, selectedFileNamesId) {
        var previewContainer = document.getElementById(previewContainerId);
        var fileInput = document.getElementById(inputId);
        var files = fileInput.files;
        var selectedFileNames = document.getElementById(selectedFileNamesId);

        previewContainer.innerHTML = ''; // Clear previous previews
        selectedFileNames.innerHTML = ''; // Clear previous file names

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                img.style.margin = '5px';
                img.src = e.target.result;
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);

            var fileName = document.createElement('p');
            fileName.textContent = 'Selected File: ' + file.name;
        }
    }
</script>

<script> // เเสดงราคาให้เป็นทศนิยม
    function formatPrice(input) {
        // แปลงค่าที่ใส่เข้ามาให้เป็นทศนิยม 2 ตำแหน่ง
        input.value = parseFloat(input.value).toFixed(2);
    }
</script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>