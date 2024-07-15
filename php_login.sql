-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2024 at 02:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_login`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `RandomTimestamp` (`startDate` DATE, `endDate` DATE) RETURNS TIMESTAMP  BEGIN
  DECLARE random_seconds INT;
  SET random_seconds = FLOOR(RAND() * (UNIX_TIMESTAMP(endDate) - UNIX_TIMESTAMP(startDate) + 1));
  RETURN FROM_UNIXTIME(UNIX_TIMESTAMP(startDate) + random_seconds);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL COMMENT 'รหัสตะกร้าสินค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complain`
--

CREATE TABLE `complain` (
  `complain_id` int(11) NOT NULL COMMENT 'รหัสคำร้อง',
  `order_id` int(11) DEFAULT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `user_id` int(11) DEFAULT NULL COMMENT 'รหัสผู้ใช้งาน',
  `description` text DEFAULT NULL COMMENT 'คำอธิบายของคำร้อง',
  `image` varchar(255) DEFAULT NULL COMMENT 'รูปภาพคำร้อง',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'เวลาที่คำร้องถูกส่งมา',
  `num` int(11) NOT NULL DEFAULT 0 COMMENT '0 = ยังไม่ได้จัดการคำร้อง\r\n1 = จัดการคำร้องเเล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `complain`
--

INSERT INTO `complain` (`complain_id`, `order_id`, `user_id`, `description`, `image`, `created_at`, `num`) VALUES
(1, 85, 33, '        avsdvasd', '7_copy.png', '2024-06-13 15:48:25', 1),
(2, 85, 33, '        avsdvasdv', '7_copy.png', '2024-06-13 15:51:11', 1),
(3, 85, 33, '        asdvasdvasd', '7_copy.png', '2024-06-14 11:57:48', 0),
(4, 87, 33, '        ฟหอกหฟอกหฟอก', '7_copy.png', '2024-06-14 12:00:06', 3);

-- --------------------------------------------------------

--
-- Table structure for table `complaindetails`
--

CREATE TABLE `complaindetails` (
  `complain_id` int(11) DEFAULT NULL COMMENT 'รหัสคำร้อง',
  `order_id` int(11) NOT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `description` text NOT NULL COMMENT 'คำอธิบายการเเก้ไขคำร้อง',
  `status` varchar(255) NOT NULL COMMENT 'Clear = รายการสินค้าของคำร้องเเก้ไขได้เรียบร้อย\r\nRe Sell = รายการสินค้าของคำร้องผิดพลาด ทำการส่งสินค้าที่ถูกต้องให้ลูกค้า\r\nOrdamage = รายการสินค้าของคำร้องนั้นเสียหาย ทำการคืนเงินลูกค้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่ได้ทำการเเก้ไขคำร้อง',
  `sender` varchar(255) DEFAULT NULL COMMENT 'ผู้เเก้ไขคำร้อง',
  `accept` int(11) DEFAULT 0 COMMENT '0 = ลูกค้ายังไม่ได้ยอมรับวิธีการเเก้ไขคำร้อง\r\n1 = ลูกค้ายอมรับวิธีการเเก้ไขคำร้องเเล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `complaindetails`
--

INSERT INTO `complaindetails` (`complain_id`, `order_id`, `user_id`, `description`, `status`, `created_at`, `sender`, `accept`) VALUES
(1, 85, 33, 'avsdvasdvasd', 'Clear', '2024-06-13 15:48:42', 'ball ball', 1),
(1, 85, 33, 'avsdvasdvasd', 'Clear', '2024-06-13 15:49:18', 'ball ball', 1),
(2, 85, 33, 'asdasdva s', 'Re sell', '2024-06-14 11:56:19', 'ball ball', 0),
(2, 85, 33, 'asdasdva s', 'Re sell', '2024-06-14 11:57:15', 'ball ball', 0),
(4, 87, 33, 'ฟอหกฟหอก', 'Order Damage', '2024-06-14 12:01:48', 'ball ball', 0);

-- --------------------------------------------------------

--
-- Table structure for table `confirm`
--

CREATE TABLE `confirm` (
  `order_id` int(11) DEFAULT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `product_id` int(11) DEFAULT NULL COMMENT 'รหัสสินค้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่คำสั่งซื้อถูกยอมรับ',
  `num` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `confirm`
--

INSERT INTO `confirm` (`order_id`, `product_id`, `created_at`, `num`) VALUES
(79, 1, '2024-06-10 19:23:43', 1),
(79, 2, '2024-06-10 19:23:43', 1),
(80, 3, '2024-06-10 19:35:28', 1),
(81, 4, '2024-06-11 12:01:17', 1),
(82, 8, '2024-06-11 12:30:35', 1),
(83, 5, '2024-06-11 12:39:11', 1),
(86, 9, '2024-06-13 15:26:31', 1),
(85, 7, '2024-06-13 15:34:15', 1),
(87, 7, '2024-06-14 11:58:46', 1),
(87, 7, '2024-06-14 11:58:46', 1),
(87, 7, '2024-06-14 11:58:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `confirmorder`
--

CREATE TABLE `confirmorder` (
  `order_id` int(11) DEFAULT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `tracking` varchar(255) DEFAULT NULL COMMENT 'เลขติดตามพัสดุสินค้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'เวลาที่ส่งพัสดุ',
  `sc` decimal(10,2) DEFAULT NULL COMMENT 'ค่าส่งพัสดุ',
  `sender` varchar(255) DEFAULT NULL COMMENT 'คนที่ส่งพัสดุ',
  `company` varchar(255) DEFAULT NULL COMMENT 'บริษัทขนส่ง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `confirmorder`
--

INSERT INTO `confirmorder` (`order_id`, `tracking`, `created_at`, `sc`, `sender`, `company`) VALUES
(79, '12345670', '2024-06-10 19:25:09', 50.00, 'ball ball', 'Kerry'),
(80, '65412345', '2024-06-10 19:35:46', 50.00, 'ball ball', 'J&T'),
(81, '12312121', '2024-06-11 12:01:30', 60.00, 'ball ball', 'Ems'),
(82, '999999', '2024-06-11 12:30:41', 45.00, 'ball ball', 'Kerry'),
(83, '12312121', '2024-06-11 12:39:19', 45.00, 'ball ball', 'Ems'),
(86, '45', '2024-06-13 15:32:36', 50.00, 'ball ball', 'Kerry'),
(85, '1231212145', '2024-06-13 15:34:28', 45.00, 'ball ball', 'Kerry'),
(87, '999999', '2024-06-14 11:58:54', 45.00, 'ball ball', 'Ems');

-- --------------------------------------------------------

--
-- Table structure for table `lot`
--

CREATE TABLE `lot` (
  `lot` int(11) DEFAULT NULL COMMENT 'รหัสล็อตสินค้า',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'ราคาล็อตสินค้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่เพิ่มล็อตสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `lot`
--

INSERT INTO `lot` (`lot`, `price`, `created_at`) VALUES
(6, 9900.00, '2024-06-10 19:38:33'),
(1, 10000.00, '2024-06-10 19:38:54'),
(2, 11000.00, '2024-06-10 19:39:15'),
(3, 9000.00, '2024-06-10 19:39:30'),
(4, 10000.00, '2024-06-10 19:39:37'),
(5, 15000.00, '2024-06-10 19:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `orderhistory`
--

CREATE TABLE `orderhistory` (
  `order_id` int(11) NOT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `user_id` int(11) DEFAULT NULL COMMENT 'รหัสผู้ใช้งาน',
  `product_id` int(11) DEFAULT NULL COMMENT 'รหัสสินค้า',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'ราคาคำสั่งซื้อ',
  `tracking` varchar(255) DEFAULT NULL COMMENT 'เลขติดตามพัสดุ',
  `num` int(11) DEFAULT NULL COMMENT '0 = ลูกค้ายังไม่ได้กดได้รับสินค้า\r\n1 = ลูกค้าได้รับสินค้าเเล้ว',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่ส่งพัสดุ',
  `sc` decimal(10,2) DEFAULT NULL COMMENT 'ค่าส่งพัสดุ',
  `sender` varchar(255) DEFAULT NULL COMMENT 'ผู้ส่งพัสดุ',
  `company` varchar(255) DEFAULT NULL COMMENT 'บริษัทขนส่ง',
  `damage` int(11) DEFAULT 0,
  `created_at_2` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orderhistory`
--

INSERT INTO `orderhistory` (`order_id`, `user_id`, `product_id`, `price`, `tracking`, `num`, `created_at`, `sc`, `sender`, `company`, `damage`, `created_at_2`) VALUES
(79, 33, 1, 100.00, '12345670', 1, '2024-06-10 19:25:09', 50.00, 'ball ball', 'Kerry', 0, NULL),
(79, 33, 2, 200.00, '12345670', 1, '2024-06-10 19:25:09', 50.00, 'ball ball', 'Kerry', 0, NULL),
(80, 33, 3, 300.00, '65412345', 1, '2024-06-10 19:35:46', 50.00, 'ball ball', 'J&T', 0, '2024-06-10 17:00:00'),
(81, 33, 4, 400.00, '12312121', 1, '2024-06-11 12:01:30', 60.00, 'ball ball', 'Ems', 0, '2024-06-10 17:00:00'),
(82, 33, 8, 800.00, '999999', 1, '2024-06-11 12:30:41', 45.00, 'ball ball', 'Kerry', 0, '2024-06-11 12:31:29'),
(83, 33, 5, 500.00, '12312121', 1, '2024-06-11 12:39:19', 45.00, 'ball ball', 'Ems', 0, '2024-06-11 12:39:28'),
(86, 33, 9, 900.00, '45', 1, '2024-06-13 15:32:36', 50.00, 'ball ball', 'Kerry', 0, '2024-06-12 17:00:00'),
(85, 33, 7, 700.00, '1231212145', 1, '2024-06-13 15:34:28', 45.00, 'ball ball', 'Kerry', 0, '2024-06-13 17:00:00'),
(87, 33, 7, 700.00, '999999', 1, '2024-06-14 11:58:54', 45.00, 'ball ball', 'Ems', 0, '2024-06-13 17:00:00'),
(87, 33, 7, 700.00, '999999', 1, '2024-06-14 11:58:54', 45.00, 'ball ball', 'Ems', 0, '2024-06-13 17:00:00'),
(87, 33, 7, 700.00, '999999', 1, '2024-06-14 11:58:54', 45.00, 'ball ball', 'Ems', 0, '2024-06-13 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL COMMENT 'รหัสรายการคำสั่งซื้อ',
  `user_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `total_price` decimal(10,2) DEFAULT NULL COMMENT 'ราคาคำสั่งซื้อ',
  `receipt` varchar(255) DEFAULT NULL COMMENT 'รูปสลิปโอนเงิน',
  `Status` varchar(3) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่คำสั่งซื้อเข้ามา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `receipt`, `Status`, `created_at`) VALUES
(79, 33, 300.00, 'IMG_2536.jpg', '2', '2024-06-10 19:22:56'),
(80, 33, 300.00, 'IMG_2536.jpg', '2', '2024-06-10 19:35:06'),
(81, 33, 400.00, 'image (1).png', '2', '2024-06-11 12:01:11'),
(82, 33, 800.00, 'image (1).png', '2', '2024-06-11 12:30:32'),
(83, 33, 500.00, 'image (2).png', '2', '2024-06-11 12:38:41'),
(85, 33, 700.00, '7_copy.png', '4', '2024-06-13 15:24:14'),
(86, 33, 900.00, '7_copy.png', '4', '2024-06-13 15:26:14'),
(87, 33, 2100.00, '7_copy.png', '4', '2024-06-14 11:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_line`
--

CREATE TABLE `order_line` (
  `order_id` int(11) NOT NULL COMMENT 'รหัสรายการคำสั่งซื่้อ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `name` varchar(255) NOT NULL COMMENT 'ชื่อสินค้า',
  `description` varchar(255) NOT NULL COMMENT 'คำอธิบายสินค้า',
  `price` decimal(10,2) NOT NULL COMMENT 'ราคาสินค้า',
  `size` varchar(10) NOT NULL COMMENT 'ขนาดสินค้า',
  `img` varchar(255) DEFAULT NULL COMMENT 'รูปหลักสินค้า',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเวลาที่สินค้าเข้ามา',
  `lot` int(11) DEFAULT NULL COMMENT 'เลขล็อตสินค้า',
  `num` int(11) DEFAULT 0 COMMENT '0 = สินค้ายังไม่ได้ขา่ย\r\n1 = สินค้าขายไปเเล้ว\r\n3 = สินค้าเสียหาย',
  `Sale` decimal(10,2) DEFAULT NULL COMMENT 'ราคาส่วนลด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `size`, `img`, `created_at`, `lot`, `num`, `Sale`) VALUES
(1, 'Product 1-1', 'Description 1-1', 100.00, 'M', 'product-03.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(2, 'Product 1-2', 'Description 1-2', 200.00, 'L', 'product-02.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(3, 'Product 1-3', 'Description 1-3', 300.00, 'S', 'product-05.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(4, 'Product 1-4', 'Description 1-4', 400.00, 'XL', 'product-01.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(5, 'Product 1-5', 'Description 1-5', 500.00, 'XXL', 'product-04.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(6, 'Product 1-6', 'Description 1-6', 600.00, 'M', 'product-02.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(7, 'Product 1-7', 'Description 1-7', 700.00, 'L', 'product-03.jpg', '2024-06-10 19:22:10', 1, 0, NULL),
(8, 'Product 1-8', 'Description 1-8', 800.00, 'S', 'product-01.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(9, 'Product 1-9', 'Description 1-9', 900.00, 'XL', 'product-05.jpg', '2024-06-10 19:22:10', 1, 1, NULL),
(10, 'Product 1-10', 'Description 1-10', 1000.00, 'XXL', 'product-04.jpg', '2024-06-10 19:22:10', 1, 0, NULL),
(11, 'Product 2-1', 'Description 2-1', 1100.00, 'M', 'product-01.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(12, 'Product 2-2', 'Description 2-2', 1200.00, 'L', 'product-03.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(13, 'Product 2-3', 'Description 2-3', 1300.00, 'S', 'product-05.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(14, 'Product 2-4', 'Description 2-4', 1400.00, 'XL', 'product-04.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(15, 'Product 2-5', 'Description 2-5', 1500.00, 'XXL', 'product-02.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(16, 'Product 2-6', 'Description 2-6', 1600.00, 'M', 'product-03.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(17, 'Product 2-7', 'Description 2-7', 1700.00, 'L', 'product-01.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(18, 'Product 2-8', 'Description 2-8', 1800.00, 'S', 'product-04.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(19, 'Product 2-9', 'Description 2-9', 1900.00, 'XL', 'product-05.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(20, 'Product 2-10', 'Description 2-10', 2000.00, 'XXL', 'product-02.jpg', '2024-06-10 19:22:10', 2, 0, NULL),
(21, 'Product 3-1', 'Description 3-1', 2100.00, 'M', 'product-05.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(22, 'Product 3-2', 'Description 3-2', 2200.00, 'L', 'product-04.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(23, 'Product 3-3', 'Description 3-3', 2300.00, 'S', 'product-02.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(24, 'Product 3-4', 'Description 3-4', 2400.00, 'XL', 'product-03.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(25, 'Product 3-5', 'Description 3-5', 2500.00, 'XXL', 'product-01.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(26, 'Product 3-6', 'Description 3-6', 2600.00, 'M', 'product-04.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(27, 'Product 3-7', 'Description 3-7', 2700.00, 'L', 'product-05.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(28, 'Product 3-8', 'Description 3-8', 2800.00, 'S', 'product-01.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(29, 'Product 3-9', 'Description 3-9', 2900.00, 'XL', 'product-02.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(30, 'Product 3-10', 'Description 3-10', 3000.00, 'XXL', 'product-03.jpg', '2024-06-10 19:22:10', 3, 0, NULL),
(31, 'Product 4-1', 'Description 4-1', 3100.00, 'M', 'product-01.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(32, 'Product 4-2', 'Description 4-2', 3200.00, 'L', 'product-04.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(33, 'Product 4-3', 'Description 4-3', 3300.00, 'S', 'product-05.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(34, 'Product 4-4', 'Description 4-4', 3400.00, 'XL', 'product-02.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(35, 'Product 4-5', 'Description 4-5', 3500.00, 'XXL', 'product-03.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(36, 'Product 4-6', 'Description 4-6', 3600.00, 'M', 'product-05.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(37, 'Product 4-7', 'Description 4-7', 3700.00, 'L', 'product-04.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(38, 'Product 4-8', 'Description 4-8', 3800.00, 'S', 'product-02.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(39, 'Product 4-9', 'Description 4-9', 3900.00, 'XL', 'product-03.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(40, 'Product 4-10', 'Description 4-10', 4000.00, 'XXL', 'product-01.jpg', '2024-06-10 19:22:10', 4, 0, NULL),
(41, 'Product 5-1', 'Description 5-1', 4100.00, 'M', 'product-03.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(42, 'Product 5-2', 'Description 5-2', 4200.00, 'L', 'product-02.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(43, 'Product 5-3', 'Description 5-3', 4300.00, 'S', 'product-05.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(44, 'Product 5-4', 'Description 5-4', 4400.00, 'XL', 'product-01.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(45, 'Product 5-5', 'Description 5-5', 4500.00, 'XXL', 'product-04.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(46, 'Product 5-6', 'Description 5-6', 4600.00, 'M', 'product-02.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(47, 'Product 5-7', 'Description 5-7', 4700.00, 'L', 'product-03.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(48, 'Product 5-8', 'Description 5-8', 4800.00, 'S', 'product-01.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(49, 'Product 5-9', 'Description 5-9', 4900.00, 'XL', 'product-05.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(50, 'Product 5-10', 'Description 5-10', 5000.00, 'XXL', 'product-04.jpg', '2024-06-10 19:22:10', 5, 0, NULL),
(51, 'abc', 'qweqweqwe', 1000.00, 'XL', 'c06b2970c9a9d530b780be650a84cfc7.jpg', '2024-06-10 21:16:50', 6, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_img`
--

CREATE TABLE `product_img` (
  `product_id` int(11) DEFAULT NULL COMMENT 'รหัสสินค้า',
  `img_file` varchar(255) DEFAULT NULL COMMENT 'รูปรองสินค้า',
  `image_count` int(11) DEFAULT 0 COMMENT 'จำนวนรูปรองของเเต่ละสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_img`
--

INSERT INTO `product_img` (`product_id`, `img_file`, `image_count`) VALUES
(51, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `u_fullname` varchar(255) DEFAULT NULL COMMENT 'ชื่อเต็มของผู้ใช้งาน',
  `u_username` varchar(255) DEFAULT NULL COMMENT 'ชื่อผู้ใช้งาน',
  `u_password` varchar(255) DEFAULT NULL COMMENT 'รหัสผ่านผู้ใช้งาน',
  `u_level` varchar(255) DEFAULT NULL COMMENT 'ระดับผู้ใช้งาน',
  `u_address` varchar(255) DEFAULT NULL COMMENT 'ที่อยู่ผู้ใช้งาน',
  `Phonenumber` varchar(20) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์ผู้ใช้งาน',
  `email` varchar(255) DEFAULT NULL COMMENT 'อีเมลผู้ใช้งาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_fullname`, `u_username`, `u_password`, `u_level`, `u_address`, `Phonenumber`, `email`) VALUES
(1, 'ball ball', 'ballkoip4210', '6c869e7009000bfdac4bcebf13a5b2fb', 'administrator', '50/1 หมู่', NULL, '123asd@gmail.com'),
(24, 'UserTae', 'user3', 'df9268ce20c250142ce6752b805800d4', 'user', '50/2 หมู่6', NULL, '123qweqwe@gmail.com'),
(33, 'ball ball', 'Test001', '$2y$10$CdtKpcatL1Z.e/Pvv54xn.SDvxlzDG2lPG7Ms/E02biaK2HgbRRky', 'administrator', '267/12', '06666667', 'ballball@gmail.com'),
(34, 'Nawaphol Kompethpanid', 'User1', '$2y$10$KZW308FhDTeWzATPKFkmz.B6jMl8KgvQhIOY8gFAZzO1BRTqzV6lq', 'user', NULL, NULL, 'Nawaphol@gmail.com'),
(35, 'user2', 'user2', '$2y$10$wnvImozXCMD3nJdm7RMTlune3G1wdIa6q98SRvDMdeh.6sDvOzrrW', 'user', NULL, NULL, 'user2@gmail.com'),
(36, 'Guest1', 'Guest1', '$2y$10$tPoUyjya2A2vlyjA9MSwaeIUcooHUzdjy8CPEP3Ngyuc..4ApxIQ.', 'user', NULL, NULL, 'qqqqqqq@gmail.com'),
(38, 'Tae', 'NwpTae', '$2y$10$XuLYeggfDq5pcrA3g0rq/O2MxHpq7A70nw.gTW5aZs5O/a1yfN2vm', 'administrator', '', '', 's0s8s7s5s8s7@gmail.com'),
(40, 'John Doe', 'johndoe', '$2y$10$abcdefghijklmnopqrstuv', 'user', '1234 Elm Street', '555-0100', 'johndoe@example.com'),
(41, 'Jane Smith', 'janesmith', '$2y$10$abcdefghijklmnopqrstuv', 'user', '2345 Oak Street', '555-0101', 'janesmith@example.com'),
(42, 'Alice Johnson', 'alicej', '$2y$10$abcdefghijklmnopqrstuv', 'user', '3456 Pine Street', '555-0102', 'alicej@example.com'),
(43, 'Bob Brown', 'bobb', '$2y$10$abcdefghijklmnopqrstuv', 'administrator', '4567 Maple Street', '555-0103', 'bobb@example.com'),
(44, 'Charlie Black', 'charlieb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '5678 Birch Street', '555-0104', 'charlieb@example.com'),
(45, 'David White', 'davidw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '6789 Cedar Street', '555-0105', 'davidw@example.com'),
(46, 'Eve Green', 'eveg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '7890 Walnut Street', '555-0106', 'eveg@example.com'),
(47, 'Frank Blue', 'frankb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '8901 Chestnut Street', '555-0107', 'frankb@example.com'),
(48, 'Grace Pink', 'gracep', '$2y$10$abcdefghijklmnopqrstuv', 'user', '9012 Spruce Street', '555-0108', 'gracep@example.com'),
(49, 'Hank Grey', 'hankg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '1123 Elm Street', '555-0109', 'hankg@example.com'),
(50, 'Ivy Red', 'ivyr', '$2y$10$abcdefghijklmnopqrstuv', 'user', '2234 Oak Street', '555-0110', 'ivyr@example.com'),
(51, 'Jack Purple', 'jackp', '$2y$10$abcdefghijklmnopqrstuv', 'user', '3345 Pine Street', '555-0111', 'jackp@example.com'),
(52, 'Karen Yellow', 'kareny', '$2y$10$abcdefghijklmnopqrstuv', 'user', '4456 Maple Street', '555-0112', 'kareny@example.com'),
(53, 'Larry Orange', 'larryo', '$2y$10$abcdefghijklmnopqrstuv', 'user', '5567 Birch Street', '555-0113', 'larryo@example.com'),
(54, 'Mona Blue', 'monab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '6678 Cedar Street', '555-0114', 'monab@example.com'),
(55, 'Nina Green', 'ninag', '$2y$10$abcdefghijklmnopqrstuv', 'user', '7789 Walnut Street', '555-0115', 'ninag@example.com'),
(56, 'Oscar Red', 'oscarr', '$2y$10$abcdefghijklmnopqrstuv', 'user', '8890 Chestnut Street', '555-0116', 'oscarr@example.com'),
(57, 'Paul White', 'paulw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '9901 Spruce Street', '555-0117', 'paulw@example.com'),
(58, 'Queen Black', 'queenb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '1012 Elm Street', '555-0118', 'queenb@example.com'),
(59, 'Ray Blue', 'rayb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '2123 Oak Street', '555-0119', 'rayb@example.com'),
(60, 'Sara Pink', 'sarap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '3234 Pine Street', '555-0120', 'sarap@example.com'),
(61, 'Tom Brown', 'tomb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '4345 Maple Street', '555-0121', 'tomb@example.com'),
(62, 'Uma Yellow', 'umay', '$2y$10$abcdefghijklmnopqrstuv', 'user', '5456 Birch Street', '555-0122', 'umay@example.com'),
(63, 'Victor Orange', 'victoro', '$2y$10$abcdefghijklmnopqrstuv', 'user', '6567 Cedar Street', '555-0123', 'victoro@example.com'),
(64, 'Wendy Green', 'wendyg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '7678 Walnut Street', '555-0124', 'wendyg@example.com'),
(65, 'Xander Red', 'xanderr', '$2y$10$abcdefghijklmnopqrstuv', 'user', '8789 Chestnut Street', '555-0125', 'xanderr@example.com'),
(66, 'Yara Blue', 'yarab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '9890 Spruce Street', '555-0126', 'yarab@example.com'),
(67, 'Zack White', 'zackw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '1091 Elm Street', '555-0127', 'zackw@example.com'),
(68, 'Amy Brown', 'amyb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '2192 Oak Street', '555-0128', 'amyb@example.com'),
(69, 'Brian Green', 'briang', '$2y$10$abcdefghijklmnopqrstuv', 'user', '3293 Pine Street', '555-0129', 'briang@example.com'),
(70, 'Chris Black', 'chrisb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Apple St', '555-0130', 'chrisb@example.com'),
(71, 'Dana White', 'danaw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Banana St', '555-0131', 'danaw@example.com'),
(72, 'Evan Green', 'evang', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Cherry St', '555-0132', 'evang@example.com'),
(73, 'Fay Brown', 'fayb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Date St', '555-0133', 'fayb@example.com'),
(74, 'Gary Grey', 'garyg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Elder St', '555-0134', 'garyg@example.com'),
(75, 'Holly Yellow', 'hollyy', '$2y$10$abcdefghijklmnopqrstuv', 'user', '678 Fig St', '555-0135', 'hollyy@example.com'),
(76, 'Ivy Blue', 'ivyb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '789 Grape St', '555-0136', 'ivyb@example.com'),
(77, 'Jake Red', 'jaker', '$2y$10$abcdefghijklmnopqrstuv', 'user', '890 Honeydew St', '555-0137', 'jaker@example.com'),
(78, 'Kara Pink', 'karap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '901 Kiwi St', '555-0138', 'karap@example.com'),
(79, 'Leo Orange', 'leoo', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Lemon St', '555-0139', 'leoo@example.com'),
(80, 'Mila Purple', 'milap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Mango St', '555-0140', 'milap@example.com'),
(81, 'Nina White', 'ninaw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Nectarine St', '555-0141', 'ninaw@example.com'),
(82, 'Oscar Black', 'oscarb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Olive St', '555-0142', 'oscarb@example.com'),
(83, 'Paula Green', 'paulag', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Papaya St', '555-0143', 'paulag@example.com'),
(84, 'Quincy Red', 'quincyr', '$2y$10$abcdefghijklmnopqrstuv', 'user', '678 Quince St', '555-0144', 'quincyr@example.com'),
(85, 'Rita Brown', 'ritab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '789 Raspberry St', '555-0145', 'ritab@example.com'),
(86, 'Sam Yellow', 'samy', '$2y$10$abcdefghijklmnopqrstuv', 'user', '890 Strawberry St', '555-0146', 'samy@example.com'),
(87, 'Tina Blue', 'tinab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '901 Tangerine St', '555-0147', 'tinab@example.com'),
(88, 'Umar Orange', 'umaro', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Ugli St', '555-0148', 'umaro@example.com'),
(89, 'Vera Pink', 'verap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Vanilla St', '555-0149', 'verap@example.com'),
(90, 'Walt Purple', 'waltp', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Watermelon St', '555-0150', 'waltp@example.com'),
(91, 'Xena White', 'xenaw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Xylophone St', '555-0151', 'xenaw@example.com'),
(92, 'Yogi Brown', 'yogib', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Yarrow St', '555-0152', 'yogib@example.com'),
(93, 'Zara Green', 'zarag', '$2y$10$abcdefghijklmnopqrstuv', 'user', '678 Zucchini St', '555-0153', 'zarag@example.com'),
(94, 'Andy Black', 'andyb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '789 Apple St', '555-0154', 'andyb@example.com'),
(95, 'Betty White', 'bettyw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '890 Banana St', '555-0155', 'bettyw@example.com'),
(96, 'Cathy Green', 'cathyg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '901 Cherry St', '555-0156', 'cathyg@example.com'),
(97, 'Derek Brown', 'derekb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Date St', '555-0157', 'derekb@example.com'),
(98, 'Ella Grey', 'ellag', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Elder St', '555-0158', 'ellag@example.com'),
(99, 'Fred Yellow', 'fredy', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Fig St', '555-0159', 'fredy@example.com'),
(100, 'Gina Blue', 'ginab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Grape St', '555-0160', 'ginab@example.com'),
(101, 'Hank Red', 'hankr', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Honeydew St', '555-0161', 'hankr@example.com'),
(102, 'Ivy Pink', 'ivyp', '$2y$10$abcdefghijklmnopqrstuv', 'user', '678 Kiwi St', '555-0162', 'ivyp@example.com'),
(103, 'Jack Orange', 'jacko', '$2y$10$abcdefghijklmnopqrstuv', 'user', '789 Lemon St', '555-0163', 'jacko@example.com'),
(104, 'Kara Purple', 'karap2', '$2y$10$abcdefghijklmnopqrstuv', 'user', '890 Mango St', '555-0164', 'karap2@example.com'),
(105, 'Leo White', 'leow', '$2y$10$abcdefghijklmnopqrstuv', 'user', '901 Nectarine St', '555-0165', 'leow@example.com'),
(106, 'Mia Black', 'miab', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Olive St', '555-0166', 'miab@example.com'),
(107, 'Nina Green', 'ninag2', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Papaya St', '555-0167', 'ninag2@example.com'),
(108, 'Oscar Red', 'oscarr2', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Quince St', '555-0168', 'oscarr2@example.com'),
(109, 'Paul Brown', 'paulb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Raspberry St', '555-0169', 'paulb@example.com'),
(110, 'Quincy Yellow', 'quincyy', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Strawberry St', '555-0170', 'quincyy@example.com'),
(111, 'Rita Blue', 'ritab2', '$2y$10$abcdefghijklmnopqrstuv', 'user', '678 Tangerine St', '555-0171', 'ritab2@example.com'),
(112, 'Sam Orange', 'samo', '$2y$10$abcdefghijklmnopqrstuv', 'user', '789 Ugli St', '555-0172', 'samo@example.com'),
(113, 'Tina Pink', 'tinap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '890 Vanilla St', '555-0173', 'tinap@example.com'),
(114, 'Uma Purple', 'umap', '$2y$10$abcdefghijklmnopqrstuv', 'user', '901 Watermelon St', '555-0174', 'umap@example.com'),
(115, 'Victor White', 'victorw', '$2y$10$abcdefghijklmnopqrstuv', 'user', '123 Xylophone St', '555-0175', 'victorw@example.com'),
(116, 'Wendy Brown', 'wendyb', '$2y$10$abcdefghijklmnopqrstuv', 'user', '234 Yarrow St', '555-0176', 'wendyb@example.com'),
(117, 'Xander Green', 'xanderg', '$2y$10$abcdefghijklmnopqrstuv', 'user', '345 Zucchini St', '555-0177', 'xanderg@example.com'),
(118, 'Yara Red', 'yarar', '$2y$10$abcdefghijklmnopqrstuv', 'user', '456 Apple St', '555-0178', 'yarar@example.com'),
(119, 'Zack Pink', 'zackp', '$2y$10$abcdefghijklmnopqrstuv', 'user', '567 Banana St', '555-0179', 'zackp@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `complain`
--
ALTER TABLE `complain`
  ADD PRIMARY KEY (`complain_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_line`
--
ALTER TABLE `order_line`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_img`
--
ALTER TABLE `product_img`
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสตะกร้าสินค้า', AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `complain`
--
ALTER TABLE `complain`
  MODIFY `complain_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสคำร้อง', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการคำสั่งซื้อ', AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสินค้า', AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้งาน', AUTO_INCREMENT=120;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `order_line`
--
ALTER TABLE `order_line`
  ADD CONSTRAINT `order_line_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_line_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product_img`
--
ALTER TABLE `product_img`
  ADD CONSTRAINT `product_img_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
