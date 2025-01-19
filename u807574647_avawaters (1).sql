-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 19, 2025 at 10:18 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u807574647_avawaters`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowed`
--

CREATE TABLE `borrowed` (
  `borrow_id` int(11) NOT NULL,
  `gallon_id` int(11) NOT NULL,
  `customername` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_borrowed` date NOT NULL,
  `status` enum('borrowed','returned') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed`
--

INSERT INTO `borrowed` (`borrow_id`, `gallon_id`, `customername`, `qty`, `date_borrowed`, `status`) VALUES
(7, 8, 'philip', 2, '0000-00-00', 'borrowed'),
(8, 9, 'philip', 2, '0000-00-00', 'borrowed'),
(9, 8, 'philip', 2, '0000-00-00', 'borrowed'),
(10, 11, 'philip', 1, '0000-00-00', 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(30) NOT NULL,
  `client_ip` varchar(20) NOT NULL,
  `user_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `qty` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `client_ip`, `user_id`, `product_id`, `qty`) VALUES
(21, '', 4, 8, 0),
(28, '', 2, 8, 2),
(35, '', 7, 10, 0),
(36, '', 7, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`) VALUES
(16, '25 Liters'),
(17, '10 Liters'),
(18, '500 ml'),
(19, 'despenser');

-- --------------------------------------------------------

--
-- Table structure for table `gallons`
--

CREATE TABLE `gallons` (
  `gallon_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallons`
--

INSERT INTO `gallons` (`gallon_id`, `name`, `description`, `qty`, `category_id`, `price`) VALUES
(8, 'Round Gallon', 'blue', 996, 16, 100),
(9, 'Nature Spring', 'Distiled', 998, 17, 60),
(10, 'natures', 'bottled', 0, 18, 60),
(11, 'hot and cold', 'water despenser', 1212, 19, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_reports`
--

CREATE TABLE `inventory_reports` (
  `id` int(11) NOT NULL,
  `gallon_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `qty_changed` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_reports`
--

INSERT INTO `inventory_reports` (`id`, `gallon_id`, `action`, `qty_changed`, `date`, `product_id`) VALUES
(17, 8, 'Stock-Out', 100, '2025-01-18 02:42:47', 0),
(18, 8, 'Stock-In', 1000, '2025-01-18 02:42:58', 0),
(19, 9, 'Stock-In', 1000, '2025-01-18 02:43:06', 0),
(20, 11, 'Stock-In', 2, '2025-01-18 08:32:38', 0),
(21, 11, 'Stock-In', 1213, '2025-01-18 08:32:44', 0);

-- --------------------------------------------------------

--
-- Table structure for table `new_orders`
--

CREATE TABLE `new_orders` (
  `ordernum` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(255) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Mobile` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Qty` int(11) NOT NULL,
  `Order` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `orderdate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_orders`
--

INSERT INTO `new_orders` (`ordernum`, `Name`, `Address`, `Email`, `Mobile`, `Status`, `Qty`, `Order`, `Amount`, `orderdate`) VALUES
(178, 'philip', 'Surallah', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, 360.00, '2025-01-18 02:45:15'),
(179, 'farhan', '9PGX+P9G, Surallah, South Cotabato, Philippines', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, NULL, '2025-01-18 02:55:55'),
(180, 'farhan', '9PGX+P9G, Surallah, South Cotabato, Philippines', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, NULL, '2025-01-18 02:59:49'),
(181, 'farhan', 'FRRX+X3W, Molave St, Koronadal City, South Cotabato, Philippines', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, NULL, '2025-01-18 03:03:52'),
(182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, NULL, '2025-01-18 03:21:02'),
(183, 'james', 'surallah', 'pjh092301@gmail.com', '09262860970', 'Delivered', 0, NULL, 200.00, '2025-01-18 03:52:43'),
(184, 'philip', 'GR2W+W4H, Morales Avenue, Koronadal City, South Cotabato, Philippines', 'tohkayatogami555@gmail.com', '0945789277', 'Delivered', 0, NULL, NULL, '2025-01-18 04:16:04'),
(185, 'Shem Heruka', 'zone 6 malvar st. surallah surallah south cotabato', 'shemheruka1988@gmail.com', '09103231017', 'To be Delivered', 0, NULL, NULL, '2025-01-18 14:28:22');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `mobile` text NOT NULL,
  `email` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `ordernum` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `ordernum`, `product_id`, `quantity`, `amount`) VALUES
(163, 178, 17, 2, 240.00),
(164, 178, 17, 1, 120.00),
(165, 179, 18, 3, 180.00),
(166, 179, 17, 2, 240.00),
(167, 180, 18, 3, 180.00),
(168, 180, 17, 2, 240.00),
(169, 181, 18, 2, 200.00),
(170, 181, 19, 1, 20.00),
(171, 182, 18, 2, 200.00),
(172, 183, 18, 2, 200.00),
(173, 184, 18, 2, 200.00),
(174, 185, 17, 1, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `qty` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(11) NOT NULL,
  `ordernum` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_items` text NOT NULL,
  `image_data` blob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `ordernum`, `name`, `address`, `mobile`, `total_amount`, `order_items`, `image_data`, `created_at`) VALUES
(50, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', '', '2025-01-18 03:11:39'),
(51, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', 0x363738623162656566336230615f696d6167652e6a706567, '2025-01-18 03:11:42'),
(52, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', 0x363738623163306435326333355f696d6167652e6a706567, '2025-01-18 03:12:13'),
(53, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', 0x363738623163313165363663315f696d6167652e6a706567, '2025-01-18 03:12:17'),
(54, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', '', '2025-01-18 03:12:24'),
(55, 178, 'philip', 'Surallah', '09262860970', 360.00, '[{\"name\":\"Nature Spring\",\"quantity\":\"2\",\"price\":\"120\",\"amount\":240,\"img_path\":\"1737169341_download (2).jpg\"},{\"name\":\"Nature Spring\",\"quantity\":\"1\",\"price\":\"120\",\"amount\":120,\"img_path\":\"1737169341_download (2).jpg\"}]', 0x363738623163323232323965645f696d6167652e6a706567, '2025-01-18 03:12:34'),
(56, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:37'),
(57, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:49'),
(58, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:52'),
(59, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:53'),
(60, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:53'),
(61, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:54'),
(62, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:54'),
(63, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:54'),
(64, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:16:58'),
(65, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:17:02'),
(66, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:17:05'),
(67, 182, 'farhan', '20 Confessor Street, Koronadal City, 9506 South Cotabato, Philippines', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:17:13'),
(68, 183, 'james', 'surallah', '09262860970', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', '', '2025-01-18 04:18:58'),
(69, 184, 'philip', 'GR2W+W4H, Morales Avenue, Koronadal City, South Cotabato, Philippines', '0945789277', 200.00, '[{\"name\":\"Round Gallon\",\"quantity\":\"2\",\"price\":\"100\",\"amount\":200,\"img_path\":\"1737169311_round-removebg-preview.png\"}]', 0x363738623636636665663438655f696d6167652e6a706567, '2025-01-18 08:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `img_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0= unavailable, 2 Available',
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `category_id`, `name`, `description`, `price`, `img_path`, `status`, `qty`) VALUES
(17, 17, 'Nature Spring', 'distiled', 120, '1737169341_download (2).jpg', 1, 975),
(18, 16, 'Round Gallon', 'Distiled', 100, '1737169311_round-removebg-preview.png', 1, 969),
(19, 18, 'natures', 'bottled', 20, '1737168833_image__5_-removebg-preview.png', 1, 994),
(20, 19, 'hot and cold', 'Water despenser', 2500, '1737172958_dispenser.jpg', 1, 900);

-- --------------------------------------------------------

--
-- Table structure for table `rental_items`
--

CREATE TABLE `rental_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rented`
--

CREATE TABLE `rented` (
  `rent_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `customername` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `date_rented` date NOT NULL,
  `status` enum('rented','returned') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`id`, `name`, `username`, `password`) VALUES
(1, 'gvc', 'gvc', 'gvc'),
(2, 'a', 'a', 'a'),
(4, 'avawaters', 'avawaters', 'avawaters');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'Ava Water Delivery', 'Avawater@delivery.com', '09123456789', '1736242020_ava.png', '&lt;h1 style=&quot;margin-bottom: 0px; padding: 0px; line-height: 90px; color: rgb(0, 0, 0); text-align: center; font-size: 70px; font-family: DauphinPlain;&quot;&gt;About Us&lt;/h1&gt;&lt;blockquote style=&quot;margin: 0 0 0 40px; border: none; padding: 0px;&quot;&gt;&lt;p style=&quot;text-align: left;&quot;&gt;&lt;span lang=&quot;EN-PH&quot; style=&quot;text-align: left;&quot;&gt;AVA water refilling station located at Allah Valley Drive Surallah South Cotabato the AVA water refilling station stablish in the year 2013, manage and owned by Mrs. Celedonia P. Albuya, AVA water refilling station have their own tanks and equipment that intend on their business. The station maintained by the Department of Health (DOH), every month they monitor the water refilling and inspected their tanks, filters, pipes, and equipment Every month.&lt;/span&gt;&lt;/p&gt;&lt;/blockquote&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Administrator', 'admin', '$2y$10$hG3oBlDDhuIB9EPOHR/Hae2MEuhpmmmjvxQT9xOumiJJBEyFiotMa', 1),
(7, 'Gvc', 'gvc', '$2y$10$jWdMapcrNeegk9cpr7RQDeP8bCWAWrM5Ur8bdDUSyoSR2zVAAJ6rS', 1),
(11, 'hechanova', 'hechanova', '$2y$10$KtcBiLMwsCYY0SRBFl8ixO0QKcYZT1rm7KQQEt57yMHZryb.kpJGO', 2),
(12, 'a', 'a', '$2y$10$XBjZlUuIJdNOAGelq/vLT.J57jOMc82FiaeEXLvWNW.5VXe1I72m.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_id` int(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address`) VALUES
(1, 'Mark', 'Cooper', 'mcooper@mail.com', '$2y$10$Z.LYi0zrDsrCYIgU1e7yCOkn1lbREGbUcIpSvgkB0OPapDfp7Xc0a', '0912345698', 'Sample Address'),
(2, 'a', 'a', 'a@gmail.com', '$2y$10$om0V/OIqA1nLmdzLldIf6OQdvusEJyezEG/J6n0nqYTz007Agnuyy', '123123', 'a'),
(3, 'bb', 'bb', 'bb@gmail.com', '$2y$10$/tk0hWxDajQqDpjDDpr3p.aZXTdo88mB4rM/RdiWB97VL2bcvQTAe', 'bb', 'bb'),
(4, 'farhan', 'macala', 'tohkayatogami555@gmail.com', '$2y$10$sbzkMFMvnXtd3D/wOfvbe.vfWLGDk9sEdQ0XHz6pgMQdT.oflppr.', '0945789277', 'Koronadal City'),
(5, 'philip', 'hechanova', 'pjh092301@gmail.com', '$2y$10$iJ3K8yH81JakpDaFVtqWBeREriySqwydLLaUQv0Nz6fp1veogQ5Eq', '0926286097', 'zone 1 baranggay libertad'),
(6, 'gg', 'gg', 'gg@gmail.com', '$2y$10$hZgiMH8kozaidZNZx2sOeuUMJ5kKLYhp1otd2zfDzjg7B5tOR8fOy', 'gg', 'gg'),
(7, 'b', 'b', 'b', '$2y$10$bcL9YI1/TyCP2ToghIWIYuLRHC6pgiLiutgzYQdb0LiXqcSZ/sK72', '213123123', 'b'),
(8, 'g', 'g', 'g@gmail.com', '$2y$10$11WGT41J6uEgSDVtBnk09urll5sWWd.73k/J63oDnac1pSUdfnbr2', '123123', 'g');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `gallon_id` (`gallon_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallons`
--
ALTER TABLE `gallons`
  ADD PRIMARY KEY (`gallon_id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallon_id` (`gallon_id`);

--
-- Indexes for table `new_orders`
--
ALTER TABLE `new_orders`
  ADD PRIMARY KEY (`ordernum`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordernum` (`ordernum`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_items`
--
ALTER TABLE `rental_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `rented`
--
ALTER TABLE `rented`
  ADD PRIMARY KEY (`rent_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed`
--
ALTER TABLE `borrowed`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `gallons`
--
ALTER TABLE `gallons`
  MODIFY `gallon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `new_orders`
--
ALTER TABLE `new_orders`
  MODIFY `ordernum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rental_items`
--
ALTER TABLE `rental_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rented`
--
ALTER TABLE `rented`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD CONSTRAINT `borrowed_ibfk_1` FOREIGN KEY (`gallon_id`) REFERENCES `gallons` (`gallon_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD CONSTRAINT `inventory_reports_ibfk_1` FOREIGN KEY (`gallon_id`) REFERENCES `gallons` (`gallon_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ordernum`) REFERENCES `new_orders` (`ordernum`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`);

--
-- Constraints for table `rented`
--
ALTER TABLE `rented`
  ADD CONSTRAINT `rented_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `rental_items` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
