<?php
include_once ("./api/function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
if (isset($_GET["user_id"])) {
  $user_id = $_GET["user_id"];
  // ต่อไปคุณสามารถใช้ $user_id ได้ต่อไปในโค้ดของคุณ
} else {
  // ถ้าไม่มี user_id ที่ส่งมา
  // คุณสามารถจัดการในส่วนนี้ได้ตามต้องการ เช่น แสดงข้อความแจ้งเตือนหรือทำอะไรก็ตามที่ต้องการ
}

if (isset($_GET["order_id"])) {
  $order_id = $_GET["order_id"];
  // ต่อไปคุณสามารถใช้ $user_id ได้ต่อไปในโค้ดของคุณ
} else {
  // ถ้าไม่มี user_id ที่ส่งมา
  // คุณสามารถจัดการในส่วนนี้ได้ตามต้องการ เช่น แสดงข้อความแจ้งเตือนหรือทำอะไรก็ตามที่ต้องการ
}

session_start();
if (isset($_SESSION['user_login'])) {
  $user = $_SESSION['user_login'];
}

if (isset($_GET['isLoggedIn'])) {
  // รับค่า $isLoggedIn
  $isLoggedIn = $_GET['isLoggedIn'];
}

$sql = "SELECT * FROM user
        LEFT JOIN cart ON user.u_id = cart.user_id
        LEFT JOIN product ON cart.product_id = product_id";
$result = mysqli_query($objCon, $sql);

if (isset($user_id) && !empty($user_id)) {
  // ตรวจสอบว่า $user_id มีค่าหรือไม่ก่อนที่จะ query ข้อมูล
  $sql_order_line = "SELECT orderhistory.*, product.name, product.size, product.price, product.img
                     FROM orderhistory 
                     LEFT JOIN product ON orderhistory.product_id = product.id 
                     WHERE orderhistory.order_id = '$order_id'";


  // ทำการ query ข้อมูล
  $result_order_line = mysqli_query($objCon, $sql_order_line);
} else {

}

if (isset($user_id) && !empty($user_id)) {
  // ตรวจสอบว่า $user_id มีค่าหรือไม่ก่อนที่จะ query ข้อมูล
  $sql_cart = "SELECT cart.*, product.name, product.size, product.price, product.img
             FROM cart 
             LEFT JOIN product ON cart.product_id = product.id 
             WHERE cart.user_id = '$user_id'
             GROUP BY cart.id"; // ตัวแปร $user_id คือ user_id ที่ได้รับจาก $_GET["user_id"]

  // ทำการ query ข้อมูล
  $result_cart = mysqli_query($objCon, $sql_cart);

} else {

}
if (isset($user_id) && !empty($user_id)) {
  $sql = "SELECT o.order_id, o.created_at, o.total_price 
          FROM orders o
          LEFT JOIN orderhistory oh ON o.order_id = oh.order_id
          WHERE o.user_id = $user_id AND oh.order_id IS NULL";
  $result_orders = mysqli_query($objCon, $sql);
  $row2 = mysqli_fetch_assoc($result_orders);
} else {

}
?>

<!DOCTYPE html>
<html>

<head>
  <title>ChicShop</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="product.css">
  <link rel="stylesheet" href="cartsidebar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&family=Lato:wght@300;400;700&display=swap"
    rel="stylesheet">

  <!-- script
    ================================================== -->
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="js/modernizr.js"></script>
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true"
  tabindex="0">
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
      <title>Search</title>
      <path fill="currentColor"
        d="M19 3C13.488 3 9 7.488 9 13c0 2.395.84 4.59 2.25 6.313L3.281 27.28l1.439 1.44l7.968-7.969A9.922 9.922 0 0 0 19 23c5.512 0 10-4.488 10-10S24.512 3 19 3zm0 2c4.43 0 8 3.57 8 8s-3.57 8-8 8s-8-3.57-8-8s3.57-8 8-8z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 16 16">
      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 16 16">
      <path
        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
    <svg xmlns="http://www.w3.org/2000/svg" id="chevron-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
    </svg>
    <symbol xmlns="http://www.w3.org/2000/svg" id="chevron-right" viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="cart-outline" viewBox="0 0 16 16">
      <path
        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="quality" viewBox="0 0 16 16">
      <path
        d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z" />
      <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="price-tag" viewBox="0 0 16 16">
      <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0z" />
      <path
        d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1zm0 5.586 7 7L13.586 9l-7-7H2v4.586z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="shield-plus" viewBox="0 0 16 16">
      <path
        d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
      <path
        d="M8 4.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V9a.5.5 0 0 1-1 0V7.5H6a.5.5 0 0 1 0-1h1.5V5a.5.5 0 0 1 .5-.5z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="star-fill" viewBox="0 0 16 16">
      <path
        d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="star-empty" viewBox="0 0 16 16">
      <path
        d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="star-half" viewBox="0 0 16 16">
      <path
        d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="quote" viewBox="0 0 24 24">
      <path fill="currentColor" d="m15 17l2-4h-4V6h7v7l-2 4h-3Zm-9 0l2-4H4V6h7v7l-2 4H6Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="facebook" viewBox="0 0 24 24">
      <path fill="currentColor"
        d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3v-4h-3a5 5 0 0 0-5 5v2.01h-2l-.396 3.98h2.396v8.01Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="youtube" viewBox="0 0 32 32">
      <path fill="currentColor"
        d="M29.41 9.26a3.5 3.5 0 0 0-2.47-2.47C24.76 6.2 16 6.2 16 6.2s-8.76 0-10.94.59a3.5 3.5 0 0 0-2.47 2.47A36.13 36.13 0 0 0 2 16a36.13 36.13 0 0 0 .59 6.74a3.5 3.5 0 0 0 2.47 2.47c2.18.59 10.94.59 10.94.59s8.76 0 10.94-.59a3.5 3.5 0 0 0 2.47-2.47A36.13 36.13 0 0 0 30 16a36.13 36.13 0 0 0-.59-6.74ZM13.2 20.2v-8.4l7.27 4.2Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="twitter" viewBox="0 0 256 256">
      <path fill="currentColor"
        d="m245.66 77.66l-29.9 29.9C209.72 177.58 150.67 232 80 232c-14.52 0-26.49-2.3-35.58-6.84c-7.33-3.67-10.33-7.6-11.08-8.72a8 8 0 0 1 3.85-11.93c.26-.1 24.24-9.31 39.47-26.84a110.93 110.93 0 0 1-21.88-24.2c-12.4-18.41-26.28-50.39-22-98.18a8 8 0 0 1 13.65-4.92c.35.35 33.28 33.1 73.54 43.72V88a47.87 47.87 0 0 1 14.36-34.3A46.87 46.87 0 0 1 168.1 40a48.66 48.66 0 0 1 41.47 24H240a8 8 0 0 1 5.66 13.66Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="instagram" viewBox="0 0 256 256">
      <path fill="currentColor"
        d="M128 80a48 48 0 1 0 48 48a48.05 48.05 0 0 0-48-48Zm0 80a32 32 0 1 1 32-32a32 32 0 0 1-32 32Zm48-136H80a56.06 56.06 0 0 0-56 56v96a56.06 56.06 0 0 0 56 56h96a56.06 56.06 0 0 0 56-56V80a56.06 56.06 0 0 0-56-56Zm40 152a40 40 0 0 1-40 40H80a40 40 0 0 1-40-40V80a40 40 0 0 1 40-40h96a40 40 0 0 1 40 40ZM192 76a12 12 0 1 1-12-12a12 12 0 0 1 12 12Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="linkedin" viewBox="0 0 24 24">
      <path fill="currentColor"
        d="M6.94 5a2 2 0 1 1-4-.002a2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="nav-icon" viewBox="0 0 16 16">
      <path
        d="M14 10.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0 0 1h7a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 16 16">
      <path
        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="navbar-icon" viewBox="0 0 16 16">
      <path
        d="M14 10.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0 0 1h7a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z" />
    </symbol>
  </svg>

  <div class="search-popup">
    <div class="search-popup-container">

      <form role="search" method="get" class="search-form" action="">
        <input type="search" id="search-form" class="search-field" placeholder="Type and press enter" value=""
          name="s" />
        <button type="submit" class="search-submit"><svg class="search">
            <use xlink:href="#search"></use>
          </svg></button>
      </form>

      <h5 class="cat-list-title">Browse Categories</h5>

      <ul class="cat-list">
        <li class="cat-list-item">
          <a href="#" title="White Shirt">White Shirt</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="Black Shirt">Black Shirt</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="Size XL">Size XL</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="Size L">Size L</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="Brands">Brands</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="Promotion">Promotion</a>
        </li>
        <li class="cat-list-item">
          <a href="#" title="HIT">HIT</a>
        </li>
      </ul>

    </div>
  </div>

  <header id="header" class="site-header header-scrolled position-fixed text-black bg-light">
    <nav id="header-nav" class="navbar navbar-expand-lg px-3 mb-3">
      <div class="container-fluid">
        <?php
        if (isset($user) && $user) {
          echo '<a class="navbar-brand" href="index.php?isLoggedIn=true&user_id=' . $user["id"] . '">';
        } else {
          echo '<a class="navbar-brand" href="index.php">';
        }
        ?>
        <img src="images/main-logo.png" class="logo">
        </a>
        <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <svg class="navbar-icon">
            <use xlink:href="#navbar-icon"></use>
          </svg>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
          <div class="offcanvas-header px-4 pb-0">
            <?php
            if (isset($user) && $user) {
              echo '<a class="navbar-brand" href="index.php?isLoggedIn=true&user_id=' . $user["id"] . '">';
            } else {
              echo '<a class="navbar-brand" href="index.php">';
            }
            ?>
            <img src="images/main-logo.png" class="logo">
            </a>
            <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas" aria-label="Close"
              data-bs-target="#bdNavbar"></button>
          </div>
          <div class="offcanvas-body">
            <ul id="navbar" class="navbar-nav text-uppercase justify-content-end align-items-center flex-grow-1 pe-3">
              <li class="nav-item">
                <?php
                if (isset($user) && $user) {
                  echo '<a class="nav-link me-4" href="index.php?isLoggedIn=true&user_id=' . $user["id"] . '#billboard">Home</a>';
                } else {
                  echo '<a class="nav-link me-4" href="index.php">Home</a>';
                }
                ?>
              </li>
              <li class="nav-item">
                <?php
                if (isset($user) && $user) {
                  echo '<a class="nav-link me-4" href="index.php?isLoggedIn=true&user_id=' . $user["id"] . '#mobile-products">Shop</a>';
                } else {
                  echo '<a class="nav-link me-4" href="index.php">Shop</a>';
                }
                ?>
              </li>
              <li class="nav-item">
                <?php
                if (isset($user) && $user) {
                  echo '<a class="nav-link me-4" href="Contact.php?isLoggedIn=true&user_id=' . $user["id"] . '">Contact</a>';
                } else {
                  echo '<a class="nav-link me-4" href="Contact.php">Contact</a>';
                }
                ?>
              </li>
              <li class="nav-item">
                <?php
                if (isset($user) && $user) {
                  echo '<a class="nav-link me-4" href="blog.php?isLoggedIn=true&user_id=' . $user["id"] . '">blog</a>';
                } else {
                  echo '<a class="nav-link me-4" href="blog.php">blog</a>';
                }
                ?>
              </li>
              <li class="nav-item">
                <div class="user-items ps-5">
                  <ul class="d-flex justify-content-end list-unstyled">
                    <li class="search-item pe-3">
                      <a href="#" class="search-button">
                        <svg class="search">
                          <use xlink:href="#search"></use>
                        </svg>
                      </a>
                    </li>
                    <li class="pe-3 dropdown">
                      <a href="#" class="dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <svg class="user">
                          <use xlink:href="#user"></use>
                        </svg>
                        <?php
                        // ตรวจสอบว่ามีการล็อกอินหรือไม่
                        if ((isset($isLoggedIn) && $isLoggedIn)) {
                          // ถ้าล็อกอินแล้ว ให้แสดงชื่อผู้ใช้
                          echo $user['fullname'];
                        } else {
                          // ถ้ายังไม่ล็อกอิน ให้แสดงข้อความที่เหมาะสม
                          echo "Guest";
                        }
                        ?>
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <?php
                        // ตรวจสอบว่ามีการล็อกอินหรือไม่
                        if ((isset($isLoggedIn) && $isLoggedIn)) {
                          // ถ้าล็อกอินแล้ว
                          echo '<li><a class="dropdown-item" href="Profile.php?isLoggedIn=true&user_id=' . $user['id'] . '">My Profile</a></li>';
                          echo '<li><a class="dropdown-item" href="Historypage.php?isLoggedIn=true&user_id=' . $user['id'] . '">PURCHASE HISTORY</a></li>';
                          echo '<li><a class="dropdown-item" href="Statuscomplain.php?isLoggedIn=true&user_id=' . $user['id'] . '">COMPLAIN HISTORY</a></li>';
                          echo '<li><a class="dropdown-item" href="/BackWeb/logout_action.php">Log Out</a></li>';
                        } else {
                          // ถ้ายังไม่ล็อกอิน
                          echo '<li><a class="dropdown-item" href="/BackWeb/login.php">Login</a></li>';
                          echo '<li><a class="dropdown-item" href="/BackWeb/register.php">Register</a></li>';
                        }
                        ?>
                      </ul>
                    </li>
                    <?php
                    // ตรวจสอบว่ามีการล็อกอินหรือไม่
                    if (isset($isLoggedIn) && $isLoggedIn) {
                      // หากมีการล็อกอิน
                      echo '<li><a id="cartIcon"><svg class="cart"><use xlink:href="#cart"></use></svg></a></li>';
                      // เพิ่มระยะห่าง
                      echo '<li><span style="margin-right: 10px;"></span></li>'; // หรือสามารถใช้ CSS class สำหรับการกำหนดระยะห่างได้
                      echo '<li><a id="cartIconII"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M14 2.2C22.5-1.7 32.5-.3 39.6 5.8L80 40.4 120.4 5.8c9-7.7 22.3-7.7 31.2 0L192 40.4 232.4 5.8c9-7.7 22.3-7.7 31.2 0L304 40.4 344.4 5.8c7.1-6.1 17.1-7.5 25.6-3.6s14 12.4 14 21.8V488c0 9.4-5.5 17.9-14 21.8s-18.5 2.5-25.6-3.6L304 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L192 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L80 471.6 39.6 506.2c-7.1 6.1-17.1 7.5-25.6 3.6S0 497.4 0 488V24C0 14.6 5.5 6.1 14 2.2zM96 144c-8.8 0-16 7.2-16 16s7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96zM80 352c0 8.8 7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96c-8.8 0-16 7.2-16 16zM96 240c-8.8 0-16 7.2-16 16s7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96z"/></svg></a></li>';

                    } else {
                      // ถ้าไม่ได้ล็อกอิน ซ่อนไอคอนรถเข็นไว้
                      // ไม่ต้องทำอะไรเพิ่ม
                    }
                    ?>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <section id="billboard" class="position-relative overflow-hidden bg-light-blue">
    <div class="swiper main-swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="container">
            <div class="row d-flex align-items-center">
              <div class="col-md-6">
                <div class="banner-content">
                  <h1 class="display-2 text-uppercase text-dark pb-5">Chic Chic</h1>
                  <a href="shop.html" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Product</a>
                </div>
              </div>
              <div class="col-md-5">
                <div class="image-holder">
                  <img src="images/banner-image.png" alt="banner">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
              <div class="col-md-6">
                <div class="banner-content">
                  <h1 class="display-2 text-uppercase text-dark pb-5">Cool Cool</h1>
                  <a href="shop.html" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Product</a>
                </div>
              </div>
              <div class="col-md-5">
                <div class="image-holder">
                  <img src="images/banner-image.png" alt="banner">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-icon swiper-arrow swiper-arrow-prev">
      <svg class="chevron-left">
        <use xlink:href="#chevron-left" />
      </svg>
    </div>
    <div class="swiper-icon swiper-arrow swiper-arrow-next">
      <svg class="chevron-right">
        <use xlink:href="#chevron-right" />
      </svg>
    </div>
  </section>
  <br>
  <br>
  <br>
  <br>

  <section id="tracking-page">
    <div class="container">
      <div class="row">
        <br><br>
        <div class="col-2 col-sm-2">
          <a href="allcart.php?isLoggedIn=true&user_id=<?php echo $user['id']; ?>">
            <button class="btn btn-success" style="width: 100%;"> รอชำระ <br> เงินแล้ว </button>
          </a>
        </div>
        <div class="col-1 col-sm-1" style="margin-top: 15px;">
          <ion-icon name="arrow-forward-outline" size="large"></ion-icon>
        </div>
        <div class="col-2 col-sm-2">
          <a
            href="ConfirmOrders.php?isLoggedIn=true&order_id=<?php echo $order_id; ?>&user_id=<?php echo $user['id']; ?>#confirm-page">
            <button class="btn btn-success" style="width: 100%;">ชำระ <br> เงินแล้ว</button>
            <p style="text-align: center; margin-top: 5px;">
              <?php
              $sql = "SELECT orders.created_at
        FROM orders
        WHERE orders.order_id = $order_id";
              $result = mysqli_query($objCon, $sql);
              $row = mysqli_fetch_assoc($result);
              $created_at_2 = !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) . '<br> ' . date('H:i:s', strtotime($row['created_at'])) : '';

              echo $created_at_2;
              ?>

            </p>
          </a>
        </div>
        <div class="col-1 col-sm-1" style="margin-top: 15px;">
          <ion-icon name="arrow-forward-outline" size="large"></ion-icon>
        </div>
        <div class="col-2 col-sm-2">
          <button class="btn btn-success" style="width: 100%;"> ตรวจสอบเลข <br> Tracking </button>
          <p style="text-align: center; margin-top: 5px;">
            <?php
            $sql = "SELECT orderhistory.created_at
                    FROM orderhistory
                    WHERE orderhistory.order_id = $order_id";
            $result = mysqli_query($objCon, $sql);
            $row3 = mysqli_fetch_assoc($result);
            $created_at_3 = !empty($row['created_at']) ? date('d/m/Y', strtotime($row3['created_at'])) . '<br> ' . date('H:i:s', strtotime($row3['created_at'])) : '';
            echo $created_at_3;
            ?>
          </p>
        </div>
        <div class="col-1 col-sm-1" style="margin-top: 15px;">
          <ion-icon name="arrow-forward-outline" size="large"></ion-icon>
        </div>
        <div class="col-2 col-sm-2">
          <a href="Historypage.php?isLoggedIn=true&user_id=<?php echo $user['id']; ?>">
            <?php
            $sql_check_num1 = "SELECT num FROM orderhistory WHERE order_id = '$order_id'";
            $result_check_num1 = mysqli_query($objCon, $sql_check_num1);
            if ($result_check_num1 && mysqli_num_rows($result_check_num1) > 0) {
              $row9 = mysqli_fetch_assoc($result_check_num1);
              $num9 = $row9['num'];

              // ตรวจสอบค่า num เพื่อกำหนดการแสดงผล
              if ($num9 == 0) {
                // ถ้า num เป็น 0 ให้แสดงปุ่มเดิม
                echo '<button class="btn btn-secondary" style="width: 100%;"> จัดส่ง <br> สำเร็จ </button>';
              } elseif ($num9 == 1) {
                // ถ้า num เป็น 1 ให้แสดงปุ่มใหม่
                echo '<button class="btn btn-success" style="width: 100%;"> จัดส่ง <br> สำเร็จ </button>';
                echo '<p style="text-align: center; margin-top: 5px;">';
                $sql = "SELECT orderhistory.created_at_2
                    FROM orderhistory
                    WHERE orderhistory.order_id = $order_id";
                $result = mysqli_query($objCon, $sql);
                $row4 = mysqli_fetch_assoc($result);
                $created_at_4 = !empty($row4['created_at_2']) ? date('d/m/Y', strtotime($row4['created_at_2'])) . '<br> ' . date('H:i:s', strtotime($row4['created_at_2'])) : '';
                echo $created_at_4;
                echo '</p>';
              }
            }
            ?>
          </a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <br><br>
          <h4>Product in order </h4>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col" class="text-center">#</th>
                  <th scope="col">Product Name</th>
                  <th scope="col" class="text-center">Price</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalPrice = 0; // เริ่มต้นให้ totalPrice เป็น 0
                $count = 1;
                while ($row = mysqli_fetch_assoc($result_order_line)) {
                  // แสดงข้อมูลของแต่ละรายการสินค้า
                  ?>
                  <tr>
                    <th><?php echo $count++; ?></th>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                  </tr>
                  <?php
                  // บวกค่า price ของแต่ละรายการสินค้าเพิ่มเข้าไปใน totalPrice
                  $totalPrice += $row['price'];
                }
                ?>
                <!-- แสดงผลราคารวมทั้งหมด -->
                <tr class="table-info">
                  <td colspan="2" align="right"><b>Total:</b></td>
                  <td align="right"><?php echo number_format($totalPrice, 2); ?></td>
                </tr>
              </tbody>
            </table>
            <div class="col-sm-12">
              <br>
              <?php
              // ตรวจสอบค่า tracking จากตาราง confirmorder
              $sql_tracking = "SELECT tracking FROM confirmorder WHERE order_id = ?";
              $stmt_tracking = mysqli_prepare($objCon, $sql_tracking);
              mysqli_stmt_bind_param($stmt_tracking, "i", $order_id);
              mysqli_stmt_execute($stmt_tracking);
              mysqli_stmt_store_result($stmt_tracking);

              if (mysqli_stmt_num_rows($stmt_tracking) > 0) {
                mysqli_stmt_bind_result($stmt_tracking, $tracking);
                mysqli_stmt_fetch($stmt_tracking);

                // ตรวจสอบค่า tracking
                if (!empty($tracking)) {
                  // แสดงข้อความเมื่อมี tracking number
                  echo '<div class="alert alert-success d-flex justify-content-between" role="alert">';
                  echo 'Tracking Number: ' . $tracking;

                  $sql_check_num = "SELECT num FROM orderhistory WHERE order_id = ?";
                  $stmt_check_num = mysqli_prepare($objCon, $sql_check_num);
                  mysqli_stmt_bind_param($stmt_check_num, "i", $order_id);
                  mysqli_stmt_execute($stmt_check_num);
                  mysqli_stmt_store_result($stmt_check_num);

                  if (mysqli_stmt_num_rows($stmt_check_num) > 0) {
                    mysqli_stmt_bind_result($stmt_check_num, $num);
                    mysqli_stmt_fetch($stmt_check_num);

                    // ตรวจสอบค่า num เพื่อกำหนดการแสดงผล
                    if ($num == 0) {
                      // ถ้า num เป็น 0 ให้แสดงปุ่มเดิม
                      echo '<a href="updatenum.php?order_id=' . $order_id . '&user_id=' . $user_id . '&isLoggedIn=true" class="btn btn-primary">ยืนยันการได้รับสินค้า</a>';
                      echo '</div>';
                    } elseif ($num == 1) {
                      // ถ้า num เป็น 1 ให้แสดงปุ่มใหม่
                      echo '<span>ได้รับสินค้าแล้ว</span>';
                      echo '</div>';

                      // ตรวจสอบว่ามี order_id ในตาราง complain หรือไม่
                      $checkComplainQuery = "SELECT * FROM complain WHERE order_id = ?";
                      $stmt_check_complain = mysqli_prepare($objCon, $checkComplainQuery);
                      mysqli_stmt_bind_param($stmt_check_complain, "i", $order_id);
                      mysqli_stmt_execute($stmt_check_complain);
                      mysqli_stmt_store_result($stmt_check_complain);

                      if (mysqli_stmt_num_rows($stmt_check_complain) > 0) {
                        // ถ้า order_id มีในตาราง complain อยู่แล้ว
                        echo '<span>รายงาน complain คำสั่งซื้อนี้ไปแล้ว</span>';
                      } else {
                        // ถ้า order_id ยังไม่มีในตาราง complain
                        echo '<a href="complain.php?order_id=' . $order_id . '&user_id=' . $user_id . '&isLoggedIn=true" class="btn btn-danger ml-2">Complain</a>';
                      }
                      mysqli_stmt_close($stmt_check_complain);
                    }
                  }
                  mysqli_stmt_close($stmt_check_num);
                } else {
                  // แสดงข้อความเมื่อยังไม่มี tracking number
                  echo '<div class="alert alert-danger" role="alert">';
                  echo 'Wait for tracking number';
                  echo '</div>';
                }
              }

              mysqli_stmt_close($stmt_tracking);
              ?>







            </div>

          </div>
        </div>
      </div>
  </section>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <footer id="footer" class="overflow-hidden">
    <div class="container">
      <div class="row">
        <div class="footer-top-area">
          <div class="row d-flex flex-wrap justify-content-between">
            <div class="col-lg-3 col-sm-6 pb-3">
              <div class="footer-menu">
                <p>มาอุดหนุนหน่อยนะครับ.</p>
                <div class="social-links">
                  <ul class="d-flex list-unstyled">
                    <li>
                      <a href="#">
                        <svg class="facebook">
                          <use xlink:href="#facebook" />
                        </svg>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <svg class="instagram">
                          <use xlink:href="#instagram" />
                        </svg>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-sm-6 pb-3">
              <div class="footer-menu text-uppercase">
                <h5 class="widget-title pb-2">Quick Links</h5>
                <ul class="menu-list list-unstyled text-uppercase">
                  <li class="menu-item pb-2">
                    <a href="#">Home</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">About</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Shop</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Blogs</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Contact</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 pb-3">
              <div class="footer-menu text-uppercase">
                <h5 class="widget-title pb-2">Help & Info Help</h5>
                <ul class="menu-list list-unstyled">
                  <li class="menu-item pb-2">
                    <a href="#">Track Your Order</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Returns Policies</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Shipping + Delivery</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Contact Us</a>
                  </li>
                  <li class="menu-item pb-2">
                    <a href="#">Faqs</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 pb-3">
              <div class="footer-menu contact-item">
                <h5 class="widget-title text-uppercase pb-2">Contact Us</h5>
                <p>Do you have any queries or suggestions? <a href="mailto:">nuttakarn4210@gmail.com</a>
                </p>
                <p>If you need support? Just give us a call. <a href="">+00 000 000 000 00</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
  </footer>
  <script src="js/jquery-1.11.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="js/plugins.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
</body>


<!-- sidebar -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartSidebarLabel">Shopping Cart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- เนื้อหาของตะกร้าสินค้า -->
    <div class="ordercart">
      <?php

      while ($row = mysqli_fetch_assoc($result_cart)) {
        ?>
        <?php echo '<div class="ordershow">' ?>
        <?php echo '<img src="/BackWeb/upload/' . $row['img'] . '" class="imgcart">' ?>
        <?php echo '<div class="cartorderdetail">' ?>
        <?php echo '<h5>' . $row['name'] . '</h5>' ?>
        <?php echo '<h5>' . $row['size'] . '</h5>' ?>
        <?php echo '<h5>' . $row['price'] . '</h5>' ?>

      </div>
      <div><button type="button" class="btn btn-outline-danger btn-sm"
          onclick="deleteCartItem(<?php echo $row['id']; ?>); reloadPage();">X</button>
      </div>
    </div>
  <?php } ?>

</div>
<hr>
<div class="btncartbuy" style="font-family: fantasy; color: white; font-size: 1.1vw;">
  <a href="allcart.php?isLoggedIn=true&user_id=<?php echo $user['id']; ?>#allcart-page"
    style="color: white; text-decoration: none;">Buy</a>
</div>
</div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebarII" aria-labelledby="cartSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartSidebarLabel">Shopping CartII</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- เนื้อหาของตะกร้าสินค้า -->
    <div class="ordercart">
      <?php
      while ($row = mysqli_fetch_assoc($result_orders)) {
        ?>
        <?php echo '<a href="ConfirmOrders.php?isLoggedIn=true&user_id=' . $user_id . '&order_id=' . $row['order_id'] . '#confirm-page">' ?>
        <?php echo '<div class="ordershow">' ?>
        <?php echo '<div style="width: 30px; height: 40px; margin-left:20px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M14 2.2C22.5-1.7 32.5-.3 39.6 5.8L80 40.4 120.4 5.8c9-7.7 22.3-7.7 31.2 0L192 40.4 232.4 5.8c9-7.7 22.3-7.7 31.2 0L304 40.4 344.4 5.8c7.1-6.1 17.1-7.5 25.6-3.6s14 12.4 14 21.8V488c0 9.4-5.5 17.9-14 21.8s-18.5 2.5-25.6-3.6L304 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L192 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L80 471.6 39.6 506.2c-7.1 6.1-17.1 7.5-25.6 3.6S0 497.4 0 488V24C0 14.6 5.5 6.1 14 2.2zM96 144c-8.8 0-16 7.2-16 16s7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96zM80 352c0 8.8 7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96c-8.8 0-16 7.2-16 16zM96 240c-8.8 0-16 7.2-16 16s7.2 16 16 16H288c8.8 0 16-7.2 16-16s-7.2-16-16-16H96z"/></svg></div>'; ?>
        <?php echo '<div class="cartorderdetail" style="margin-right:50px;">' ?>
        <?php echo '<h5> Order No.' . $row['order_id'] . '</h5>' ?>
        <?php echo '<h5> Total Price:' . $row['total_price'] . '</h5>' ?>
      </div>
    </div>
    </a>
  <?php } ?>

</div>
<hr>
</div>
</div>

<script>
  function previewReceipt(inputId, previewContainerId, fileNamesContainerId) {
    var input = document.getElementById(inputId);
    var previewContainer = document.getElementById(previewContainerId);
    var fileNamesContainer = document.getElementById(fileNamesContainerId);
    var files = input.files;

    // Clear the previous preview
    previewContainer.innerHTML = '';

    // Display selected file names
    fileNamesContainer.innerHTML = '';
    for (var i = 0; i < files.length; i++) {
      fileNamesContainer.innerHTML += '<p>' + files[i].name + '</p>';
    }

    // Display image previews
    if (files && files.length > 0) {
      for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var image = document.createElement('img');
          image.className = 'preview-image';
          image.src = e.target.result;
          previewContainer.appendChild(image);
        };

        reader.readAsDataURL(files[i]);
      }
    }
  }

</script>

<script>
  function deleteCartItem(cartId) {
    // สร้าง XMLHttpRequest object
    var xhttp = new XMLHttpRequest();
    // กำหนด function ที่จะเรียกเมื่อมีการเปลี่ยนแปลงในสถานะการเชื่อมต่อ
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        // เมื่อข้อมูลถูกส่งไปและได้รับ response จาก server
        console.log(this.responseText); // แสดงผลลัพธ์ใน console
        // ลบอิเล็กเมนต์ที่มี id ตาม cartId ออกจาก DOM
        var cartItem = document.getElementById('cartItem-' + cartId);
        if (cartItem) {
          cartItem.remove();
        }
      }
    };
    // กำหนด method, URL และ async
    xhttp.open("POST", "delete_cart_item.php", true);
    // กำหนด header สำหรับการส่งข้อมูลแบบ POST
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // ส่ง request พร้อมกับ parameter cart_id
    xhttp.send("id=" + cartId);
  }
</script>
<script>
  // รีไดเร็กหน้าเดิมเมื่อมีการกดลบรายการในตะกร้า
  function reloadPage() {
    location.reload();
  }
</script>

<script>
  document.getElementById('cartIcon').addEventListener('click', function () {
    var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartSidebar'));
    myOffcanvas.show();
  });
</script>

<script>
  document.getElementById('cartIconII').addEventListener('click', function () {
    var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartSidebarII'));
    myOffcanvas.show();
  });
</script>
<!-- sidebar end -->



</html>