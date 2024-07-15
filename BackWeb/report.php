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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/report.css" rel="stylesheet">

    <style type="text/css">
        input[type="date"]::-webkit-datetime-edit,
        input[type="date"]::-webkit-inner-spin-button,
        input[type="date"]::-webkit-clear-button {
            color: #fff;
            position: relative;
        }

        input[type="date"]::-webkit-datetime-edit-year-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: black;
            left: 56px;
        }

        input[type="date"]::-webkit-datetime-edit-month-field {
            position: absolute !important;
            border-left: 1px solid #8c8c8c;
            padding: 2px;
            color: black;
            left: 26px;
        }

        input[type="date"]::-webkit-datetime-edit-day-field {
            position: absolute !important;
            color: black;
            padding: 2px;
            left: 4px;
        }
    </style>

</head>

<body>
    <div class="">
        <!-- Spinner Start -->
        <!-- <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
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
                    <a href="report.php" class="nav-item nav-link active"><i class="fa fa-chart-bar me-2"></i>Report</a>
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
            <br>
            <br>
            <div class="bgbgreport">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <br>
                        <h2>Report</h2>
                        <br>
                        <form action="" method="get">
                            <div class="row g-9">
                                <div class="col-auto">
                                    <label class="col-form-label">เริ่มต้น</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" name="start_date" data-date-format="dd-mm-Y" class="form-control"
                                        required value="<?php if (isset($_GET['start_date'])) {
                                            echo $_GET['start_date'];
                                        } else {
                                            echo '2024-05-27';
                                        } ?>">
                                </div>
                                <div class="col-auto">
                                    <label class="col-form-label">ถึง</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" name="end_date" data-date-format="dd-mm-Y" class="form-control"
                                        required value="<?php if (isset($_GET['end_date'])) {
                                            echo $_GET['end_date'];
                                        } else {
                                            echo '2024-05-30';
                                        } ?>">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">ค้นหาข้อมูล</button>
                                    <a href="report.php" class="btn btn-warning">เคลียร์ข้อมูล</a>
                                </div>
                            </div>
                        </form>

                        <?php
                        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                            // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลตามช่วงวันที่
                            $end_date = date('Y-m-d', strtotime($_GET['end_date'] . ' +1 day'));
                            // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลตามช่วงวันที่
                            $sql = "SELECT * FROM orderhistory WHERE created_at BETWEEN ? AND ?";
                            $stmt = $objCon->prepare($sql);
                            $stmt->bind_param("ss", $_GET['start_date'], $end_date);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        
                            // ถ้ามีข้อมูล
                            if ($result->num_rows > 0) {
                                ?>
                                <br>
                                <h4>รายการขายวันที่ : <?= date('d/m/Y', strtotime($_GET['start_date'])); ?>
                                    ถึง
                                    <?= date('d/m/Y', strtotime($_GET['end_date'])); ?>
                                </h4>
                                <br>
                        
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th width="5%">No.</th>
                                            <th width="55%" class="text-center">Order No.</th>
                                            <th width="20%" class="text-center">Price</th>
                                            <th width="20%" class="text-center">Shipping cost</th>
                                            <th width="20%" class="text-center">D/m/y</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // กำหนดตัวแปรเพื่อแสดงลำดับ
                                        $i = 1;
                                        // กำหนดตัวแปรเพื่อเก็บผลรวมยอดขาย
                                        $total = 0;
                                        $total2 = 0;
                                        $total3 = 0;
                                        while ($row = $result->fetch_assoc()) {
                                            // ตรวจสอบว่าแถวนี้มีค่า damage หรือไม่ ถ้ามีให้ใช้ค่านั้นแทน price
                                            if (isset($row['damage']) && $row['damage'] != 0) {
                                                $price = 0; // ใช้ค่า damage และมีเครื่องหมาย - นำหน้า
                                            } else {
                                                $price = $row['price'];
                                            }
                        
                                            // เพิ่มยอดขายในผลรวม
                                            $total += $row['price'];
                                            $total2 += $row['sc'];
                                            $total3 += $row['price'] - $row['sc'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $i++; ?></td>
                                                <td><?= $row['order_id']; ?></td>
                                                <td align="right"><?= number_format($price, 2); ?></td>
                                                <td align="right"><?= number_format($row['sc'], 2); ?></td>
                                                <td class="text-center"><?= date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="table-success">
                                            <td colspan="2" class="text-center"></td>
                                            <td align="right">Total Price</td>
                                            <td align="right">Total Shipping</td>
                                            <td align="right">Total</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="2" class="text-center">Total</td>
                                            <td align="right"><?= number_format($total, 2); ?></td>
                                            <td align="right"><?= number_format($total2, 2); ?></td>
                                            <td align="right"><?= number_format($total3, 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo '<br>';
                                echo '<center> -ไม่พบข้อมูล !! </center>';
                            }
                        } //isset
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