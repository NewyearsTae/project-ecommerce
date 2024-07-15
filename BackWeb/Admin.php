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

// กำหนดจำนวนรายการต่อหน้า
$items_per_page = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// ดึงข้อมูลทั้งหมดเพื่อคำนวณจำนวนหน้าทั้งหมด
$sql_total_users = "SELECT * FROM user";
$result_total_users = mysqli_query($objCon, $sql_total_users);
$total_users = mysqli_num_rows($result_total_users);

// ดึงข้อมูลตามหน้าที่เลือก
$sql = "SELECT * FROM user ORDER BY u_fullname ASC LIMIT $offset, $items_per_page";
$result = mysqli_query($objCon, $sql);

// คำนวณจำนวนหน้าทั้งหมด
$total_pages = ceil($total_users / $items_per_page);

$sql_admins = "SELECT * FROM user WHERE u_level = 'administrator'";
$result_admins = mysqli_query($objCon, $sql_admins);
$total_admins = mysqli_num_rows($result_admins);

$sql_user = "SELECT * FROM user WHERE u_level = 'user'";
$result_user = mysqli_query($objCon, $sql_user);
$total_user = mysqli_num_rows($result_user);

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
    <link href="css/search.css" rel="stylesheet">

</head>

<body>
    <div>
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
                    <a href="Admin.php" class="nav-item nav-link active"><i class="fa fa-users me-2"></i>Member</a>
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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-user-circle fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Member</p>
                                <h6 class="mb-0"><?php echo $total_users; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-user-tie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Admin</p>
                                <h6 class="mb-0"><?php echo $total_admins; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-user fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">User</p>
                                <h6 class="mb-0"><?php echo $total_user; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->
            <?php { ?>
                <div class="searchtab">
                    <form action="searchdata.php" class="form-group" method="POST">
                        <label for="search-input">Search:</label>
                        <input type="text" id="search-input" name="empname">
                        <input type="submit" value="Search" class="btn btn-dark my-2">
                    </form>
                </div>
            <?php } ?>

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Member</h6>
                        <a href="adduser.php" class="btn btn-success">Add User</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">No</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">DELETE</th>
                                    <th scope="col">EDIT</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $start_number = $offset + 1; // หมายเลขเริ่มต้นของหน้านี้
                                
                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $start_number; ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['u_fullname'] ?></td>
                                        <td><?php echo $row['u_username'] ?></td>
                                        <td><?php echo $row['u_level'] ?></td>
                                        <td>
                                            <a href="address.php?user_id=<?php echo $row["u_id"] ?>"
                                                class="btn btn-success">ADDRESS</a>
                                        </td>
                                        <td>
                                            <a href="deleteForm.php?user_id=<?php echo $row["u_id"] ?>"
                                                class="btn btn-danger">DELETE</a>
                                        </td>
                                        <td>
                                            <a href="edituser.php?user_id=<?php echo $row["u_id"] ?>"
                                                class="btn btn-warning">EDIT</a>
                                        </td>
                                    </tr>
                                    <?php $start_number++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        <?php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($page - 1) * 30;

                        if ($page > 1) {
                            echo '<a class="btnpage" href="?page=' . ($page - 1) . '">Previous</a>';
                        }

                        $total_records = mysqli_num_rows(mysqli_query($objCon, "SELECT * FROM user"));
                        $total_pages = ceil($total_records / 30);

                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $page) {
                                echo '<span class="current-page">' . $i . '</span>';
                            } else {
                                echo '<a class="btnpage" href="?page=' . $i . '">' . $i . '</a>';
                            }
                        }

                        if ($page < $total_pages) {
                            echo '<a class="btnpage" href="?page=' . ($page + 1) . '">Next</a>';
                        }
                        ?>
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