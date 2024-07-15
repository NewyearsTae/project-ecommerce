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
              WHERE DATE(created_at) = CURDATE()";
$result_price = mysqli_query($objCon, $sql_price);
$row_price = mysqli_fetch_assoc($result_price);
$total_price = $row_price['total_price_after_damage'];

$sql_damage = "SELECT SUM(sc) AS total_damage 
FROM orderhistory 
WHERE DATE(created_at) = CURDATE()";
$result_damage = mysqli_query($objCon, $sql_damage);
$row_damage = mysqli_fetch_assoc($result_damage);
$total_damage = $row_damage['total_damage'];

$sql = "SELECT * FROM orders WHERE Status = 5";
$result = mysqli_query($objCon, $sql);
$total_complain_num_zero = mysqli_num_rows($result);

$sql_orders = "SELECT * FROM orders WHERE Status = 1";
$result_orders = mysqli_query($objCon, $sql_orders);
$total_orders = mysqli_num_rows($result_orders);

$sql_product = "SELECT * FROM product";
$result_product = mysqli_query($objCon, $sql_product);
$total_product = mysqli_num_rows($result_product);

$sql = "SELECT * FROM user";
$result = mysqli_query($objCon, $sql);

$sql_order = "SELECT orderhistory.order_id, orderhistory.created_at, orderhistory.sender, orderhistory.company,
               GROUP_CONCAT(product.id) AS product_ids,
               SUM(orderhistory.price) AS total_price, orderhistory.sc,
               GROUP_CONCAT(orderhistory.damage) AS product_damages,
               orderhistory.tracking
        FROM orderhistory
        INNER JOIN user ON orderhistory.user_id = user.u_id
        INNER JOIN product ON orderhistory.product_id = product.id
        WHERE DATE(orderhistory.created_at) = CURDATE()  -- เพิ่มเงื่อนไขเลือกข้อมูลของวันนี้
        GROUP BY orderhistory.order_id, orderhistory.sc, orderhistory.created_at, orderhistory.sender, orderhistory.tracking
        ORDER BY orderhistory.created_at DESC";
$result_order = mysqli_query($objCon, $sql_order);


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
    <style>
        /* สไตล์สำหรับตัวกล่อง pagination */
        .pagination {
            float: right;
            /* ให้กล่อง pagination ชิดขวา */
        }

        /* สไตล์สำหรับลิงก์ */
        .pagination a {
            display: inline-block;
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        /* สไตล์สำหรับลิงก์ Active */
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        /* สไตล์สำหรับลิงก์เมื่อชี้ (hover) */
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>



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
                    <div class="row g-4">
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                                <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Total Cost</p>
                                    <h6 class="mb-0"><?php echo $total_cost; ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                                <i class="fa fa-file-alt fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Complain</p>
                                    <h6 class="mb-0"><?php echo $total_complain_num_zero; ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                                <i class="fa fa-file-alt fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Order</p>
                                    <h6 class="mb-0"><?php echo $total_orders; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <br>
                <div class="button-container" style="margin-left: 3%;">
                    <button id="button" class="button active">Day</button>
                    <button id="button1" class="button">Week</button>
                    <button id="button3" class="button">Month</button>
                    <button id="button2" class="button">Year</button>
                </div>
                <br>
                <div class="row g-4" style="margin-left: 2%;">
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
            </div>
            <!-- Sale & Revenue End -->
            <div class="grahp1">
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
            <!-- Recent Sales Start -->
            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4" style="margin-bottom:5%;" id="list-products">
                <div class="bg-light text-center rounded p-4">
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Date</th>
                                    <th scope="col">Order No</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Shipping Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result_order)) {
                                    // จัดรูปแบบ total_price ให้มี comma และชิดขวา
                                    $formatted_total_price = number_format($row['total_price'], 2);
                                    $formatted_total_price2 = number_format($row['sc'], 2);

                                    // จัดรูปแบบวันที่
                                    $created_at = !empty($row['created_at']) ? date('H:i:s', strtotime($row['created_at'])) : '';

                                    echo '<td>' . $created_at . '</td>';
                                    echo '<td>' . $row['order_id'] . '</td>';
                                    echo '<td style="text-align: right;">' . $formatted_total_price . '</td>';
                                    echo '<td style="text-align: right;">' . $formatted_total_price2 . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                    </div>

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