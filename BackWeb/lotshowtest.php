<?php 
require("./function.php");
$objCon = connectDB();


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

$lot = $_GET["lot"];

$sql = "SELECT * FROM product WHERE lot = '$lot'";
$result=mysqli_query($objCon,$sql);

$total_product = mysqli_num_rows($result);


if (isset($_POST["submit"])) {
    $u_fullname = $_POST["u_fullname"];
    $u_username = $_POST["u_username"];
    $u_password = $_POST["u_password"];
    $u_password2 = $_POST["u_password2"];
    $u_level = $_POST["u_level"];
    $passwordHash = password_hash($u_password, PASSWORD_DEFAULT);
    $errors =  array();
    
    if (empty($u_fullname)){
        array_push($errors,"Please fill Fullname");        
    }
    if(empty($u_username)){
        array_push($errors,"Please fill Username");     
    }
    if(empty($u_password)){
        array_push($errors,"Please fill Password");     
    }
    if(empty($u_password2)){
        array_push($errors,"Please fill Repeat Password");     
    }
    if ($u_password!==$u_password2){
        array_push($errors,"Password does not match");
    }

    $sql = "SELECT * FROM user WHERE u_username = ? AND u_id != ?";
    $stmt = mysqli_stmt_init($objCon);
    if(mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $u_username, $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            array_push($errors, "Username already exists with a different ID!");
        }   
}


    if(count($errors) > 0){

    } else {
        $sql = "UPDATE user SET u_fullname = ?, u_username = ?, u_password = ?, u_level = ? WHERE u_id = ?";
        $stmt = mysqli_stmt_init($objCon);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $u_fullname, $u_username, $passwordHash, $u_level, $id);
            mysqli_stmt_execute($stmt);
            header("location:admin.php");
            exit;
        } else {
            echo '<div class="alert alert-danger small-alert">Something went wrong</div>';
        }

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
    <link href="css/income.css" rel="stylesheet">

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
            <div class="container-fluid pt-4 px-4" style ="display:flex; justify-content: space-between;">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-12">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">          
                            <i class="fa fa-cube fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Product</p>
                                <h6 class="mb-0"><?php echo $total_product; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-lot">
                    <a href="addproduct.php?lot=<?php echo $lot; ?>" class="btn btn-success">Add Product</a>
                </div>
            </div>
            <!-- Navbar End -->
        <form method="post">
            <div class="bgeditmain">
                <div class="bgedit">
                <br>
                <h3>Detail Lot</h3>
                <br>
                <?php
            $sql = "SELECT * FROM lot WHERE lot = '$lot'";
            $result1=mysqli_query($objCon,$sql);
            $row1 = mysqli_fetch_assoc($result1); 
            $created_at = date('d/m/Y', strtotime($row1['created_at']));

            echo '<div class="container-fluid pt-4 px-4">';
            echo '<div class="bg-light text-center rounded p-4">';
            echo '<div class="d-flex align-items-center justify-content-between mb-4">';
            echo '<h6 class="mb-0">Product Lot :' . $row1['lot'] . ' Cost : ' . $row1['price'] . ' วันที่สร้าง LOT :' . $created_at . '</h6>';
            echo '</div>';
            echo '<div class="table-responsive">';
            echo '<table class="table text-start align-middle table-bordered table-hover mb-0">';
            echo '<thead>';
            echo '<tr class="text-dark">';
            echo '<th scope="col">IMAGE</th>';
            echo '<th scope="col">ID</th>';
            echo '<th scope="col">Name</th>';
            echo '<th scope="col">Description</th>';
            echo '<th scope="col">Price</th>';
            echo '<th scope="col">Size</th>';
            echo '<th scope="col">DELETE</th>';
            echo '<th scope="col">EDIT</th>';
            echo '<th scope="col">Sale</th>';
            echo '<th scope="col">Status</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            $query = "SELECT * FROM product WHERE lot = '" . $row1['lot'] . "'";
            $result2 = mysqli_query($objCon, $query);
            
            while ($row2 = mysqli_fetch_assoc($result2)) {
                echo '<tr>';
                echo '<td>';
                echo '<a href="viewimgP.php?user_id=' . $row2['id'] . '&lot=' . $row2['lot'] . '" class="btn btn-info">View Images</a>';
                echo '<td>' . $row2['id'] . '</td>';
                echo '<td>' . $row2['name'] . '</td>';
                echo '<td>' . $row2['description'] . '</td>';
                echo '<td>' . $row2['price'] . '</td>';
                echo '<td>' . $row2['size'] . '</td>';
                echo '<td><a href="deleteproduct.php?user_id=' . $row2['id'] . '&lot=' . $row2['lot'] .  '" class="btn btn-danger">DELETE</a></td>';
                echo '<td><a href="editproduct.php?user_id=' . $row2['id'] . '&lot=' . $row2['lot'] .  '" class="btn btn-primary">EDIT</a></td>';
                echo '<td><a href="saleproduct.php?user_id=' . $row2['id'] . '&lot=' . $row2['lot'] .  '" class="btn btn-warning">Sale</a></td>';
                if ($row2['num'] == 1) {
                    echo '<td><center><div class="textstatuscomplete"><center><p>Complete<p><center></div><center></td>';
                } elseif ($row2['num'] == 3) {
                    echo '<td><center><div class="textstatusdamage"><center><p>Damage<p><center></div><center></td>';
                }else {
                    echo '<td><center><div class="textstatusempty"><center><p>ON Sell<p><center></div><center></td>';
                }
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            ?>
            <br>
                    <a href="product.php" class="w-100 btn btn-lg btn-primary">Back</a>
                </div>
                </div>            
            </div>
        </form>
        

           

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