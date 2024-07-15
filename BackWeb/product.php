<?php
include_once ("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
session_start();
if (!isset($_SESSION['user_login'])) {
    header("location: login.php");
    exit;
}

$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}

$sql = "SELECT * FROM product";
$result = mysqli_query($objCon, $sql);
$total_users = mysqli_num_rows($result);

$sql = "SELECT * FROM lot";
$result = mysqli_query($objCon, $sql);

$total_product = mysqli_num_rows($result);
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
    <link href="css/product.css" rel="stylesheet">
    <link href="css/search.css" rel="stylesheet">
</head>

<body>
    <div class="">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4" style="display:flex; justify-content: space-between;">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-12">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-cube fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Lot</p>
                                <h6 class="mb-0"><?php echo $total_product; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="add-lot">
                    <a href="addlot.php" class="btn btn-success">Add Lot</a>
                </div>
            </div>
            <div class="bgbtnpage">
                <div class="pageproduct">
                    <a href="income.php">
                        <center>
                            <p style="margin-top: 6px; color:white;">Product</p>
                        </center>
                    </a>
                </div>
                <div class="pageproduct-active">
                    <center>
                        <p style="margin-top: 6px; color:white;">LOT</p>
                    </center>
                </div>
            </div>
            <!-- Sale & Revenue End -->

            <?php { ?>
                <div class="searchtab">
                    <form action="searchlot.php" class="form-group" method="POST">
                        <label for="search-input">Search:</label>
                        <input type="text" id="search-input" name="lot">
                        <input type="submit" value="Search" class="btn btn-dark my-2">
                    </form>
                </div>
            <?php } ?>
            <div class="test" style="margin-left: 2%;">
                <?php
                $sql = "SELECT * FROM lot ORDER BY lot DESC";
                $result1 = mysqli_query($objCon, $sql);

                while ($row1 = mysqli_fetch_assoc($result1)) {
                    // Query เพื่อหาจำนวนสินค้าที่มีค่า num = 1 สำหรับ lot นี้
                    $lot_id = $row1['lot'];
                    $sql2 = "SELECT COUNT(*) as soldout_count FROM product WHERE lot = '$lot_id' AND num = 1";
                    $result2 = mysqli_query($objCon, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $soldout_count = $row2['soldout_count'];

                    // Query เพื่อหาจำนวนสินค้าที่มีค่า num = 0 สำหรับ lot นี้
                    $sql3 = "SELECT COUNT(*) as sellingCount FROM product WHERE lot = '$lot_id' AND num = 0";
                    $result3 = mysqli_query($objCon, $sql3);
                    $row3 = mysqli_fetch_assoc($result3);
                    $sellingCount = $row3['sellingCount'];

                    $sql4 = "SELECT COUNT(*) as damageCount FROM product WHERE lot = '$lot_id' AND num = 3";
                    $result4 = mysqli_query($objCon, $sql4);
                    $row4 = mysqli_fetch_assoc($result4);
                    $damageCount = $row4['damageCount'];

                    echo '<div class="bgbtnlot">';
                    echo '<div class="btnlot">';
                    echo '<form action="lotshowtest.php" method="GET">';
                    echo '<input type="hidden" name="lot" value="' . $row1['lot'] . '">'; // ส่งค่า lot ไปทาง GET
                    echo '<button type="submit" class="btn btn-link">'; // เปลี่ยน <div> เป็น <form> และปุ่ม
                    echo '<h6> Lot: ' . $row1['lot'] . ' Cost: ' . $row1['price'] . '</h6>';
                    echo '<div class="statuslot">';
                    echo '<h6 class="stlot1">Selling</h6>';
                    echo '<h6 class="stdetail">' . $sellingCount . '</h6>';
                    echo '<h6 class="stdetail">ชิ้น</h6>';
                    echo '</div>';
                    echo '<div class="statuslot">';
                    echo '<h6 class="stlot2">Sold Out</h6>';
                    echo '<h6 class="stdetail">' . $soldout_count . '</h6>';
                    echo '<h6 class="stdetail">ชิ้น</h6>';
                    echo '</div>';
                    echo '<div class="statuslot">';
                    echo '<h6 class="stlot3">Damage</h6>';
                    echo '<h6 class="stdetail">' . $damageCount . '</h6>';
                    echo '<h6 class="stdetail">ชิ้น</h6>';
                    echo '</div>';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>


            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            if (confirm("คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?")) {
                window.location.href = "deleteuser.php?user_id=" + userId;
            } else {
                // ไม่กระทำใดๆ ถ้ายกเลิกการลบ
            }
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