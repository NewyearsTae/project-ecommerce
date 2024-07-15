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

$complain_id = $_GET["complain_id"];

$sql = "SELECT complain.*, user.u_fullname, user.email, user.Phonenumber, user.u_address, SUM(orderhistory.price) AS total_price 
        FROM complain 
        INNER JOIN user ON complain.user_id = user.u_id
        INNER JOIN orderhistory ON complain.order_id = orderhistory.order_id
        WHERE complain.complain_id = $complain_id
        GROUP BY complain.complain_id, user.u_fullname";
$result = mysqli_query($objCon, $sql);
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
    <link href="css/report.css" rel="stylesheet">
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

        <div class="reportbg">
            <div class="userdetail">
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Name</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['u_fullname']; ?>
                    <p>
                </div>
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Order id</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['order_id']; ?></p>
                </div>
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Price</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['total_price']; ?></p>
                </div>
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Email</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['email']; ?></p>
                </div>
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Tel.</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['Phonenumber']; ?></p>
                </div>
                <div class="bgbox">
                    <h5 style="margin-bottom: 20px;">Address</h5>
                    <p style="margin-bottom: 20px;"><?php echo $row['u_address']; ?></p>
                </div>
            </div>

            <div class="reportdetail">
                <div class="bgimgreport">
                    <img src="/Webpage/complain/<?php echo $row['image']; ?>" class="imgreport">
                </div>
                <div class="descriptionreport">
                    <h3>คำอธิบาย</h3>
                    <p style="color: white;"><?php echo $row['description']; ?></p>
                </div>
                <label for="exampleFormControlTextarea1" class="form-label"></label>
                <textarea class="form-control" id="complainDetailTextarea" name="complainDetailTextarea" rows="10"
                    style="width: 90%; height:15vw; padding: 10px; border-radius: 20px; margin-left: 4%;"></textarea>
                <br>
                <form action="complainclear.php" method="get" style="display:inline;"
                    onsubmit="copyTextareaValue('complainDetailTextarea', 'complainDetailHidden1');">
                    <input type="hidden" name="sender" value="<?php echo htmlspecialchars($user['fullname']); ?>">
                    <input type="hidden" name="complain_id" value="<?php echo $complain_id; ?>">
                    <input type="hidden" name="complainDetail" id="complainDetailHidden1">
                    <button type="submit" class="btn btn-success" style="margin-top: 10px; float: right;">Clear</button>
                </form>

                <form action="complainresell.php" method="get" style="display:inline;"
                    onsubmit="copyTextareaValue('complainDetailTextarea', 'complainDetailHidden2');">
                    <input type="hidden" name="sender" value="<?php echo htmlspecialchars($user['fullname']); ?>">
                    <input type="hidden" name="complain_id" value="<?php echo $complain_id; ?>">
                    <input type="hidden" name="complainDetail" id="complainDetailHidden2">
                    <button type="submit" class="btn btn-warning"
                        style="margin-top: 10px; margin-right: 10px; float: right;">Re Sell</button>
                </form>

                <form action="complaindamage.php" method="get" style="display:inline;"
                    onsubmit="copyTextareaValue('complainDetailTextarea', 'complainDetailHidden3');">
                    <input type="hidden" name="sender" value="<?php echo htmlspecialchars($user['fullname']); ?>">
                    <input type="hidden" name="complain_id" value="<?php echo $complain_id; ?>">
                    <input type="hidden" name="complainDetail" id="complainDetailHidden3">
                    <button type="submit" class="btn btn-danger"
                        style="margin-top: 10px; margin-right: 10px; float: right;">Order Damage</button>
                </form>
                <a href="reportpage.php" class="btn btn-primary"
                    style="margin-top: 10px; margin-right: 10px; float: right;">Back</a>
            </div>

            <script>
                function copyTextareaValue(textareaId, hiddenInputId) {
                    var textareaValue = document.getElementById(textareaId).value;
                    document.getElementById(hiddenInputId).value = textareaValue;
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