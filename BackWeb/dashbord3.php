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

$sql_cost = "SELECT SUM(lot.price) AS total_cost FROM lot";
$result_cost = mysqli_query($objCon, $sql_cost);
$row_cost = mysqli_fetch_assoc($result_cost);
$total_cost = $row_cost['total_cost'];

$sql_price = "SELECT SUM(price) AS total_price_after_damage 
              FROM orderhistory 
              WHERE YEAR(created_at) = YEAR(CURDATE())";
$result_price = mysqli_query($objCon, $sql_price);
$row_price = mysqli_fetch_assoc($result_price);
$total_price = $row_price['total_price_after_damage'];

$sql_damage = "SELECT SUM(sc) AS total_damage 
FROM orderhistory 
WHERE YEAR(created_at) = YEAR(CURDATE())";
$result_damage = mysqli_query($objCon, $sql_damage);
$row_damage = mysqli_fetch_assoc($result_damage);
$total_damage = $row_damage['total_damage'];

$sql_complain = "SELECT * FROM complain";
$result_complain = mysqli_query($objCon, $sql_complain);
$total_complain = mysqli_num_rows($result_complain);

$sql_product = "SELECT * FROM product";
$result_product = mysqli_query($objCon, $sql_product);
$total_product = mysqli_num_rows($result_product);

$sql = "SELECT * FROM user";
$result = mysqli_query($objCon, $sql);

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
    <link href="css/dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                    <a href="dashbord.php" class="nav-item nav-link active"><i
                            class="fa fa-tachometer-alt me-2"></i>Dashbord</a>
                    <a href="Admin.php" class="nav-item nav-link"><i class="fa fa-users me-2"></i>Member</a>
                    <a href="income.php" class="nav-item nav-link"><i class="fa fa-box-open me-2"></i>Product</a>
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


            <!-- Sale & Revenue Start -->'
            <div class="bgbgbg">
                <div class="container-fluid pt-4 px-4">

                </div>
                <div class="button-container">
                    <button id="button" class="button">Day</button>
                    <button id="button1" class="button">Week</button>
                    <button id="button3" class="button">Month</button>
                    <button id="button2" class="button active">Year</button>
                </div>
            </div>
            <br>
            <div class="row g-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                        <div class="ms-3">
                            <p class="mb-2">Total Income</p>
                            <h6 class="mb-0"><?php echo $total_price; ?></h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                        <div class="ms-3">
                            <p class="mb-2">Total Expenses</p>
                            <h6 class="mb-0"><?php echo $total_damage; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->
            <div class="grahp1">
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
            <!-- Recent Sales Start -->
            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4" style="margin-bottom:5%;">
                <div class="bg-light text-center rounded p-4">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Date</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Shipping Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // เริ่มต้นจากเดือนมกราคมของปีปัจจุบัน
                                $month = 1;

                                // วนลูปแสดงผลตาราง 12 เดือน
                                for ($i = 0; $i < 12; $i++) {
                                    // กำหนดวันที่เริ่มต้นและสิ้นสุดของแต่ละเดือน
                                    $startDate = date('Y-m-d', strtotime(date('Y') . '-' . $month . '-01'));
                                    $endDate = date('Y-m-d', strtotime(date('Y') . '-' . ($month + 1) . '-01'));

                                    // ค้นหาข้อมูลในช่วงเวลานี้
                                    $sql_order = "SELECT MONTH(created_at) AS order_month, COUNT(order_id) AS quantity, SUM(price) AS total_price, SUM(sc) AS total_shipping_cost FROM orderhistory WHERE created_at >= '$startDate' AND created_at < '$endDate' GROUP BY MONTH(created_at)";
                                    $result_order = mysqli_query($objCon, $sql_order);

                                    // แสดงผลลัพธ์ของแต่ละเดือน
                                    echo '<tr>';
                                    echo '<td>' . date('F', strtotime($startDate)) . '</td>'; // แสดงชื่อเดือน
                                
                                    // ตรวจสอบว่ามีข้อมูลหรือไม่
                                    if ($result_order->num_rows > 0) {
                                        $row = $result_order->fetch_assoc();
                                        echo '<td>' . $row["quantity"] . '</td>';
                                        echo '<td>' . $row["total_price"] . '</td>';
                                        echo '<td>' . $row["total_shipping_cost"] . '</td>';
                                    } else {
                                        // ถ้าไม่มีข้อมูลให้แสดงเป็นข้อมูลว่างเช่นเดียวกัน
                                        echo '<td>0</td>';
                                        echo '<td>0</td>';
                                        echo '<td>0</td>';
                                    }
                                    echo '</tr>';

                                    // เพิ่มเดือนสำหรับการคำนวณต่อไป
                                    $month++;
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

    <script>
        var totalPrice = <?php echo $total_price; ?>;
        var totalCost = <?php echo $total_cost; ?>;
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Income', 'Total Cost'],
                datasets: [{
                    label: 'Income and Cost',
                    data: [totalPrice, totalCost],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    legend: {
                        display: false // ปิดคำอธิบาย
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>
</body>

</html>