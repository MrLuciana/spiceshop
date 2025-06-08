-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 06:27 PM
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
-- Database: `spiceshop-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(10, '500 g', 'หมวดหมู่ของแห้ง 500 g'),
(11, '1000 g', 'หมวดหมู่ของแห้ง 1000 g'),
(12, '5000 g', 'หมวดหมู่ของแห้ง 5000 g');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `shipping_address` text NOT NULL,
  `status` enum('pending','shipped','delivered','cancelled','waiting_payment_verify') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('qr','cod') NOT NULL,
  `payment_slip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `shipping_address`, `status`, `total_amount`, `payment_method`, `payment_slip`) VALUES
(1, 1, '2025-05-07 08:56:10', '', 'pending', 350.00, 'qr', ''),
(3, 1, '2025-05-07 09:00:40', '', 'delivered', 350.00, 'qr', ''),
(4, 1, '2025-05-07 09:03:32', '', 'shipped', 350.00, 'qr', ''),
(5, 11, '2025-05-07 16:55:10', 'test', 'waiting_payment_verify', 250.00, 'qr', 'slip_1746637902_5911.jpg'),
(6, 10, '2025-05-08 08:24:34', 'testttt', 'waiting_payment_verify', 100.00, 'qr', 'slip_1746692694_6686.jpg'),
(7, 10, '2025-05-08 09:26:23', 'ทดสอบ', 'pending', 100.00, 'cod', NULL),
(8, 10, '2025-05-08 09:27:54', 'ทดสอบ', 'pending', 150.00, 'cod', NULL),
(9, 10, '2025-05-08 09:28:27', 'ทดสอบ', 'waiting_payment_verify', 100.00, 'qr', 'slip_1746696521_2721.jpg'),
(10, 10, '2025-05-08 09:29:11', 'ทดสอบ', 'delivered', 100.00, 'qr', 'slip_1746698498_5289.jpg'),
(11, 13, '2025-05-15 08:05:49', '1', 'pending', 0.00, 'cod', NULL),
(12, 1, '2025-05-15 08:38:23', '225', 'delivered', 200.00, 'cod', NULL),
(13, 13, '2025-05-22 06:18:54', '1', 'delivered', 200.00, 'qr', 'slip_1747894753_9888.jpg'),
(14, 1, '2025-05-22 06:26:15', '1', 'pending', 200.00, 'qr', 'slip_1747895190_3586.jpg'),
(15, 1, '2025-05-22 06:34:38', '12', 'waiting_payment_verify', 200.00, 'qr', 'slip_1748092893_1332.png'),
(16, 13, '2025-05-22 07:57:45', '1', 'pending', 400.00, 'qr', NULL),
(17, 13, '2025-05-24 13:20:07', '1', 'shipped', 100.00, 'qr', 'slip_1748092834_2788.png'),
(18, 1, '2025-05-24 13:22:26', '1', 'delivered', 200.00, 'qr', 'slip_1748092952_8647.png'),
(19, 1, '2025-06-04 16:14:59', '1', 'pending', 900.00, 'qr', NULL),
(20, 1, '2025-06-06 16:55:05', '123', 'pending', 750.00, 'qr', NULL),
(21, 1, '2025-06-06 18:16:02', '123', 'shipped', 750.00, 'qr', 'slip_1749233780_6146.png'),
(22, 1, '2025-06-06 19:13:28', '123', 'shipped', 750.00, 'qr', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(7, 1, 10, 2, 100.00),
(8, 1, 12, 1, 150.00),
(9, 5, 11, 1, 150.00),
(10, 5, 10, 1, 100.00),
(11, 6, 12, 1, 100.00),
(12, 7, 12, 1, 100.00),
(13, 8, 11, 1, 150.00),
(14, 9, 12, 1, 100.00),
(15, 10, 12, 1, 100.00),
(16, 11, 21, 1, 0.00),
(17, 11, 20, 1, 0.00),
(18, 11, 19, 1, 0.00),
(19, 12, 16, 1, 200.00),
(20, 13, 17, 1, 200.00),
(21, 14, 16, 1, 200.00),
(22, 15, 15, 1, 200.00),
(23, 16, 16, 2, 200.00),
(24, 17, 11, 1, 100.00),
(25, 18, 16, 1, 200.00),
(26, 19, 20, 1, 750.00),
(27, 19, 16, 1, 150.00),
(28, 20, 20, 1, 750.00),
(29, 21, 20, 1, 750.00),
(30, 22, 15, 5, 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `category_id`, `image`) VALUES
(10, 'เครื่องแกงเขียวหวาน 500 g', 'เครื่องแกงเขียวหวาน🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง  แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ  แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ  ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 75.00, 100, 10, '1747295099_1747216039_เครื่องแกงเขียวหวาน.jpg'),
(11, 'เครื่องแกงส้ม 500 g', 'เครื่องแกงส้ม🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง  แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ  แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ  ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 75.00, 100, 10, '1747295191_1747216575_เครื่องแกงส้ม.jpg'),
(12, 'เครื่องแกงเผ็ด 500 g', 'เครื่องแกงเผ็ด🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 75.00, 100, 10, '1747295244_1747216822_แกงเผ็ด.jpg'),
(13, 'เครื่องแกงกะทิ 500 g', 'เครื่องแกงกะทิ🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 75.00, 100, 10, '1747295280_1747218717_1747217408_d39b64d812398e801d145e5c880f28b7.jpg'),
(14, 'เครื่องแกงส้ม 1000 g', 'เครื่องแกงส้ม🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 150.00, 100, 11, '1747295349_1747216575_เครื่องแกงส้ม.jpg'),
(15, 'เครื่องแกงเผ็ด 1000 g', 'เครื่องแกงเผ็ด🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 150.00, 100, 11, '1747295378_1747216822_แกงเผ็ด.jpg'),
(16, 'เครื่องแกงเขียวหวาน 1000 g', 'เครื่องแกงเขียวหวาน🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 150.00, 100, 11, '1747295411_1747216039_เครื่องแกงเขียวหวาน.jpg'),
(17, 'เครื่องแกงกะทิ 1000 g', 'เครื่องแกงกะทิ🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 150.00, 100, 11, '1747295440_1747217408_d39b64d812398e801d145e5c880f28b7.jpg'),
(18, 'เครื่องแกงกะทิ 5000 g', 'เครื่องแกงกะทิ🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 750.00, 100, 12, '1747295474_1747217408_d39b64d812398e801d145e5c880f28b7.jpg'),
(19, 'เครื่องแกงส้ม 5000 g', 'เครื่องแกงส้ม🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 750.00, 100, 12, '1747295506_1747216575_เครื่องแกงส้ม.jpg'),
(20, 'เครื่องแกงเผ็ด 5000 g', 'เครื่องแกงเผ็ด🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 750.00, 100, 12, '1747295537_1747216822_แกงเผ็ด.jpg'),
(21, 'เครื่องแกงเขียวหวาน 5000 g', 'เครื่องแกงเขียวหวาน🔥 เข้มข้น สินค้าผลิตใหม่ทุกวัน พร้อมส่งตลอด ไม่ใส่สารกันบูด สามารถเก็บไว้ได้นาน\r\n\r\nวิธีการเก็บรักษา : เก็บในอุณภูมิห้อง อยู่ได้ 7 วัน แช่เย็น อยู่ได้ 3 เดือน ❄\r\n\r\nวิธีใช้\r\n- ต้องการ (เผ็ดมาก🌶🌶🌶) : 1 ห่อ แบ่งครึ่ง ใช้ได้ 2 ครั้ง แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- ต้องการ (เผ็ดน้อย🌶) : 1 ห่อ แบ่ง 3 ส่วน นำไปโคลกกับ หัวหอม และ กระเทียม (เครื่องแกงส้ม ให้โคลกเฉพาะกระเทียม) เพิ่ม เพื่อลดความเผ็ด แกง 1 หม้อ สำหรับ ปริมาณ 4 คน รับประทาน\r\n- กรณีนำไปผัด สำหรับเครื่องแกงเผ็ด และแกงกะทิ ใช้ในปริมาณเท่ากับการทำแกง สำหรับ 4 คน รับประทาน\r\n\r\n* ปริมาณที่แนะนำ เป็นการใช้ที่พอเหมาะ เพื่อให้ได้รสชาติเข้มข้น', 750.00, 100, 12, '1747295565_1747216039_เครื่องแกงเขียวหวาน.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92SDF1/gM.fxF56tKoEceuEUMTTBJrZ8I0IZxXKexdthuw7Cg3GSa', 'admin', '2025-05-07 07:54:34'),
(10, 'test', 'tttt@gmail.com', '$2y$10$hiS2J4cW1sAUPbwx1k2TaOxzUlaV1I2/cajo.bn34UOrSwfAX2qm6', 'customer', '2025-05-07 14:19:03'),
(11, 'hello', 'hello@gmail.com', '$2y$10$SAzdksii8GWXCOnQg1VQ1eLc3h7yfMj75uh0lJnGCeqqkuEWtfBSG', 'customer', '2025-05-07 14:20:35'),
(12, 't1', 't1@gmail.com', '$2y$10$K7OUHK9I11ZE6xIjNfhwMuYY/jVNrLWlnrH2lz2zDieWq8EWVULQG', 'customer', '2025-05-07 14:54:45'),
(13, 'parisa.k', 'parisa20@gmail.com', '$2y$10$MniDpGAyG4vlxf85j3EZs.eCZAoBfFzM8RrTlJyoh31CaDiHRVoc6', 'customer', '2025-05-15 07:37:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`),
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
