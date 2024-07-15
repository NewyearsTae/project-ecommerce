<?php 
require("./function.php");
$objCon = connectDB();
$id=$_GET["user_id"];

$sql = "SELECT * FROM product WHERE id = '$id'";
$result = mysqli_query($objCon,$sql);

$sql_img = "SELECT * FROM product_img WHERE product_id = '$id'";
$result_img = mysqli_query($objCon, $sql_img);

$row = mysqli_fetch_assoc($result);

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


        <!-- Sign In Start -->
        <div class="container-fluid">
            <form method="post" action="delproduct.php">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3>Delete User</h3>
                            </div>
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
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $row["name"]; ?>" disabled>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" disabled><?php echo $row["description"]; ?></textarea>
                            </div>
                            <div class="mb-5">
                                <label for="u_price" class="form-label">PRICE</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?php echo $row["price"]; ?>" disabled>
                            </div>
                            <div class="mb-6">
                                <label for="u_price" class="form-label">SIZE</label>
                                <input type="text" class="form-control" id="size" name="size" value="<?php echo $row["size"]; ?>" disabled> 
                            </div>
                            <a href="deleteproduct.php?user_id=<?php echo $row["id"]; ?>" class="w-100 btn btn-lg btn-danger mt-3">Confirm Delete</a>
                            <a href="product.php" class="w-100 btn btn-lg btn-primary mt-3">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Sign In End -->
    </div>

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