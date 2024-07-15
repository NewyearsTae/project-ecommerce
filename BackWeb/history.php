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

$sql = "SELECT COUNT(DISTINCT order_id) AS total_order FROM orderhistory";
$result = mysqli_query($objCon, $sql);
$row = mysqli_fetch_assoc($result);
$total_order = $row['total_order'];


$sql = "SELECT orderhistory.order_id,
               orderhistory.created_at,
               orderhistory.created_at_2,
               orderhistory.sender,
               orderhistory.company,
               GROUP_CONCAT(orderhistory.product_id) AS product_ids,
               SUM(orderhistory.price) AS total_price,
               GROUP_CONCAT(orderhistory.damage) AS product_damages,  -- เพิ่มคอลัมน์ damage
               orderhistory.tracking,
               confirm.created_at AS created_at2,  -- ดึงข้อมูล created_at จากตาราง confirm
               orders.created_at AS created_at3   -- ดึงข้อมูล created_at จากตาราง orders
        FROM orderhistory
        INNER JOIN user ON orderhistory.user_id = user.u_id
        LEFT JOIN confirm ON orderhistory.order_id = confirm.order_id  -- เข้าร่วมกับตาราง confirm
        LEFT JOIN orders ON orderhistory.order_id = orders.order_id    -- เข้าร่วมกับตาราง orders
        GROUP BY orderhistory.order_id,
                 orderhistory.created_at,
                 orderhistory.created_at_2,
                 orderhistory.sender,
                 orderhistory.tracking,
                 confirm.created_at,  -- รวม confirm.created_at ใน GROUP BY
                 orders.created_at    -- รวม orders.created_at ใน GROUP BY
        ORDER BY orderhistory.created_at DESC";  // เปลี่ยน ASC เป็น DESC

$result2 = mysqli_query($objCon, $sql);


$sql = "SELECT SUM(orderhistory.price) AS total_price_after_damage FROM orderhistory";
$result = mysqli_query($objCon, $sql);
$row2 = mysqli_fetch_assoc($result);
$total_price = $row2['total_price_after_damage'];


$sql = "SELECT SUM(lot.price) AS total_cost FROM lot";
$result9 = mysqli_query($objCon, $sql);
$row9 = mysqli_fetch_assoc($result9);
$total_cost = $row9['total_cost'];


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
                    <a href="income.php" class="nav-item nav-link"><i class="fa fa-box-open me-2"></i>Product</a>
                    <a href="order.php" class="nav-item nav-link"><i class="fa fa-shopping-cart me-2"></i>Order</a>
                    <a href="orderconfirm.php" class="nav-item nav-link"><i
                            class="fa fa-check-circle text-dark me-2"></i>Comfirm Order</a>
                    <a href="shipping.php" class="nav-item nav-link"><i class="fa fa-truck me-2"></i>Shipping</a>
                    <a href="history.php" class="nav-item nav-link active"><i class="fa fa-history me-2"></i>Purchase
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
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-file-alt fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Order</p>
                                <h6 class="mb-0"><?php echo $total_order; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Income</p>
                                <h6 class="mb-0"><?php echo $total_price ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Cost</p>
                                <h6 class="mb-0"><?php echo $total_cost ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Sale & Revenue End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-1">
                <div class="bg-light text-center rounded p-2">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Order No.</th>
                                    <th scope="col">Product ID</th>
                                    <th scope="col">Confirm Date</th>
                                    <th scope="col" style="width: 100px;">Sent Date</th>
                                    <th scope="col">Sender</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Tracking No.</th>
                                    <th scope="col">Date Received</th>
                                    <th scope="col">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    // จัดรูปแบบ total_price ให้มี comma และชิดขวา
                                    $formatted_total_price = number_format($row['total_price'], 2);

                                    // จัดรูปแบบวันที่
                                    $created_at = !empty($row['created_at']) ? date('d/m/Y H:i:s', strtotime($row['created_at'])) : '';
                                    $created_at2 = !empty($row['created_at2']) ? date('d/m/Y H:i:s', strtotime($row['created_at2'])) : '';
                                    $created_at3 = !empty($row['created_at3']) ? date('d/m/Y H:i:s', strtotime($row['created_at3'])) : '';
                                    $created_at_2 = !empty($row['created_at_2']) ? date('d/m/Y H:i:s', strtotime($row['created_at_2'])) : '';

                                    echo '<tr>';
                                    echo '<td>' . $created_at3 . '</td>';
                                    echo '<td>' . $row['order_id'] . '</td>';
                                    echo '<td>' . $row['product_ids'] . '</td>';
                                    echo '<td>' . $created_at2 . '</td>';
                                    echo '<td>' . $created_at . '</td>';
                                    echo '<td>' . $row['sender'] . '</td>';
                                    echo '<td>' . $row['company'] . '</td>';
                                    echo '<td>' . $row['tracking'] . '</td>';
                                    if (!empty($created_at_2)) {
                                        echo '<td>' . $created_at_2 . '</td>';
                                    } else {
                                        echo '<td>Not Arrived</td>';
                                    }
                                    echo '<td style="text-align: right;">' . $formatted_total_price . '</td>';
                                    echo '</tr>';
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->
        </div>
        <!-- Content End -->
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