<?php
session_start(); // เปิดใช้งาน session
if (isset($_SESSION['user_login'])) { // ถ้าเข้าระบบอยู่
    header("location: index.php"); // redirect ไปยังหน้า index.php
    exit;
}else{

}

if (isset($_POST["submit"])){
    $u_fullname = $_POST["u_fullname"];
    $u_username = $_POST["u_username"];
    $u_password = $_POST["u_password"];
    $u_password2 = $_POST["u_password2"];
    $u_level = $_POST["u_level"];
    $passwordHash = password_hash($u_password, PASSWORD_DEFAULT);
    $email = $_POST["email"];
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
    if (empty($email)){
        array_push($email,"Please fill Email");        
    }

    require("./function.php");
    $objCon = connectDB();
    $sql = "SELECT * FROM user WHERE u_username = '$u_username'";
    $result = mysqli_query($objCon, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount>0) {
        array_push($errors,"Username already exists!");
    }
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result2 = mysqli_query($objCon, $sql);
    $rowCount2 = mysqli_num_rows($result2);
    if ($rowCount2>0) {
        array_push($errors,"Email already exists!");
    }

    if(count($errors)>0){
        
    }else {
        $sql = "INSERT INTO user (u_fullname, u_username, u_password, u_level, email) VALUE ( ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($objCon);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"sssss",$u_fullname, $u_username, $passwordHash ,$u_level,$email);
        mysqli_stmt_execute($stmt);
        header("location:login.php");
        exit;
    }else {
        die("Something went wrong");
    }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ChicShop-Register</title>
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
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign Up Start -->
        <div class="container-fluid">
            <form method="post" action="register.php">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3>Sign Up</h3>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                                <label for="floatingInput">Email</label>
                                <?php
                                if (isset($errors) && in_array("Email already exists!", $errors)) {
                                    echo "<div class='alert alert-danger small-alert'>Email already exists!</div>";
                                }else if (isset($errors) && in_array("Please fill Email", $errors)){
                                    echo "<div class='alert alert-danger small-alert'>Please fill Email!</div>";
                                }
                                ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="u_fullname" name="u_fullname" placeholder="Name" value="<?php echo isset($u_fullname) ? htmlspecialchars($u_fullname) : ''; ?>">
                                <label for="floatingText">Name</label>
                                <?php
                                if (isset($errors) && in_array("Please fill Fullname", $errors)) {
                                    echo "<div class='alert alert-danger small-alert'>Please fill Fullname</div>";
                                }
                                ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="u_username" name="u_username" placeholder="Username" value="<?php echo isset($u_username) ? htmlspecialchars($u_username) : ''; ?>">
                                <label for="floatingInput">Username</label>
                                <?php
                                if (isset($errors) && in_array("Username already exists!", $errors)) {
                                    echo "<div class='alert alert-danger small-alert'>Username already exists!</div>";
                                }else if (isset($errors) && in_array("Please fill Username", $errors)){
                                    echo "<div class='alert alert-danger small-alert'>Please fill Username!</div>";
                                }
                                ?>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="u_password" name="u_password" placeholder="Password" >
                                <label for="floatingPassword">Password</label>
                                <?php
                                if (isset($errors) && in_array("Please fill Password", $errors)) {
                                    echo "<div class='alert alert-danger small-alert'>Please fill Password!</div>";
                                }
                                ?>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="u_password2" name="u_password2" placeholder="Repeat Password">
                                <label for="floatingPassword">Repeat Password</label>
                                <?php
                                if (isset($errors) && in_array("Password does not match", $errors)) {
                                    echo "<div class='alert alert-danger small-alert'>Password does not match</div>";
                                }else if (isset($errors) && in_array("Please fill Repeat Password", $errors)){
                                    echo "<div class='alert alert-danger small-alert'>Please fill Repeat Password!</div>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="u_level" class="form-label" ></label>
                                <input type="hidden" id="u_level" name="u_level" value="user">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary py-3 w-100 mb-4">Sign Up</button>
                            <p class="text-center mb-0">Already have an Account? <a href="login.php">Sign In</a></p>
                            <p class="text-center mb-0">Back to Website <a href="/Webpage/index.php">Back</a></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Sign Up End -->
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