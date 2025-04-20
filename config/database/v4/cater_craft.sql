-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 05:00 PM
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
-- Database: `cater_craft`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_statuses`
--

CREATE TABLE `account_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_statuses`
--

INSERT INTO `account_statuses` (`id`, `status`) VALUES
(1, 'active'),
(3, 'banned'),
(2, 'suspended');

-- --------------------------------------------------------

--
-- Table structure for table `availability_statuses`
--

CREATE TABLE `availability_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability_statuses`
--

INSERT INTO `availability_statuses` (`id`, `status`) VALUES
(1, 'available'),
(2, 'unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `guests` int(11) NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status_id` int(11) DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `availability_status_id` int(11) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `event_date`, `event_time`, `guests`, `venue`, `special_requests`, `status_id`, `total_amount`, `cancellation_reason`, `package_id`, `availability_status_id`, `created_at`, `updated_at`) VALUES
(10, 2, '2025-03-15', '18:00:00', 100, 'Grand Hall', 'Vegetarian options needed', 2, 5000.00, NULL, NULL, 1, '2025-02-27 00:01:45', '2025-03-19 16:05:21'),
(11, 3, '2025-04-10', '12:30:00', 50, 'Riverside Garden', 'Outdoor setup with flowers', 2, 3000.00, NULL, 2, 1, '2025-02-27 00:01:45', '2025-02-27 00:01:45'),
(12, 4, '2025-05-22', '15:00:00', 200, 'Luxury Ballroom', NULL, 2, 10000.00, NULL, 3, 2, '2025-02-27 00:01:45', '2025-03-19 16:05:12'),
(30, 1, '2025-03-19', '16:05:59', 1, NULL, NULL, 1, 0.00, NULL, NULL, 1, '2025-03-19 16:05:59', '2025-03-19 16:05:59'),
(31, 2, '2025-04-20', '22:48:00', 2321, 'dsadsassddsadas', 'nothing but you yeeeee <3 ', 1, 200.00, NULL, NULL, 1, '2025-04-20 19:48:58', '2025-04-20 19:48:58'),
(32, 2, '2025-04-20', '03:05:00', 221, 'dsadas', 'dsssdsdsddsdsds', 1, 200.00, NULL, NULL, 1, '2025-04-20 21:05:32', '2025-04-20 21:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

CREATE TABLE `booking_items` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_items`
--

INSERT INTO `booking_items` (`id`, `booking_id`, `menu_item_id`, `quantity`, `subtotal`) VALUES
(40, 10, 1, 2, 500.00);

--
-- Triggers `booking_items`
--
DELIMITER $$
CREATE TRIGGER `before_insert_booking_items` BEFORE INSERT ON `booking_items` FOR EACH ROW BEGIN
    IF NEW.quantity <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantity must be greater than 0';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `booking_statuses`
--

CREATE TABLE `booking_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_statuses`
--

INSERT INTO `booking_statuses` (`id`, `status`) VALUES
(4, 'cancelled'),
(3, 'completed'),
(2, 'confirmed'),
(1, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `gcash_proof`
--

CREATE TABLE `gcash_proof` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `category`) VALUES
(1, 'Appetizers'),
(4, 'Beverages'),
(3, 'Desserts'),
(5, 'Gulay'),
(2, 'Main Course');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unit_type` varchar(50) NOT NULL DEFAULT 'plate'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `image`, `availability`, `category_id`, `created_at`, `updated_at`, `unit_type`) VALUES
(1, 'Spring Rollss', 'Crispy spring rolls with sweet chili sauce', 5.99, '1745122072_5.jpg', 1, NULL, '2025-02-26 23:23:44', '2025-04-20 12:47:04', 'pcs'),
(14, 'Creamy Dessert with Cheese', 'Creamy Dessert with Cheese', 100.00, '3.jpg', 1, 3, '2025-04-20 12:11:33', '2025-04-20 12:11:33', 'plate'),
(16, 'Creamy Baked Sushi', 'Creamy Baked Sushi', 120.00, '4.jpg', 1, 3, '2025-04-20 12:13:24', '2025-04-20 12:13:24', 'plate'),
(17, 'Kalabasa', 'Kalabasa', 45.00, '111.jpg', 1, 5, '2025-04-20 12:15:08', '2025-04-20 12:15:08', 'plate'),
(19, 'Baguio Dish', 'Baguio Dish', 200.00, 'g3.jpg', 1, 5, '2025-04-20 12:48:12', '2025-04-20 12:48:12', 'plate'),
(20, 'Pechay and String Beans', 'Pechay and String Beans', 299.00, 'g4.jpg', 1, 5, '2025-04-20 12:49:02', '2025-04-20 12:49:02', 'plate'),
(21, 'Ginisang Gulay', 'Ginisang Gulay', 80.00, 'g2.jpg', 1, 5, '2025-04-20 12:50:13', '2025-04-20 12:50:13', 'plate'),
(22, 'Mixed Vegetables', 'Mixed Vegetables', 250.00, 'g5.jpg', 1, 5, '2025-04-20 12:50:53', '2025-04-20 12:50:53', 'plate'),
(23, 'Ginisang Tomato', 'Ginisang Tomato', 40.00, 'g6.jpg', 1, 5, '2025-04-20 12:51:28', '2025-04-20 12:51:28', 'plate'),
(24, 'Ginisang Brocolli', 'Ginisang Brocolli', 60.00, 'g7.jpg', 1, 5, '2025-04-20 12:52:03', '2025-04-20 12:52:03', 'plate'),
(25, 'Main Course 1', 'Main Course 1', 200.00, 'mc1.jpg', 1, 2, '2025-04-20 12:58:40', '2025-04-20 12:58:40', 'plate'),
(26, 'Main Course 2', 'Main Course 2', 201.00, 'mc2.jpg', 1, 2, '2025-04-20 12:59:03', '2025-04-20 12:59:03', 'plate'),
(27, 'Main Course 3', 'Main Course 3', 202.00, 'mc3.jpg', 1, 2, '2025-04-20 12:59:23', '2025-04-20 12:59:23', 'plate'),
(28, 'Main Course 4', 'Main Course 4', 203.00, 'mc4.jpg', 1, 2, '2025-04-20 12:59:44', '2025-04-20 12:59:44', 'plate'),
(29, 'Main Course 5', 'Main Course 5', 205.00, 'mc5.jpg', 1, 2, '2025-04-20 13:00:03', '2025-04-20 13:00:03', 'plate'),
(30, 'Main Course 6', 'Main Course 6', 206.00, 'mc6.jpg', 1, 2, '2025-04-20 13:00:20', '2025-04-20 13:00:20', 'plate'),
(31, 'Main Course 7', 'Main Course 6', 207.00, 'mc7.jpg', 1, 2, '2025-04-20 13:00:41', '2025-04-20 13:00:41', 'plate'),
(32, 'Main Course 8', 'Main Course 8', 208.00, 'mc8.jpg', 1, 2, '2025-04-20 13:00:59', '2025-04-20 13:00:59', 'plate'),
(33, 'Main Course 9', 'Main Course 9', 209.00, 'mc9.jpg', 1, 2, '2025-04-20 13:01:19', '2025-04-20 13:01:19', 'plate'),
(34, 'Main Course 10', 'Main Course 10', 210.00, 'mc10.jpg', 1, 2, '2025-04-20 13:01:40', '2025-04-20 13:01:40', 'plate'),
(35, 'Main Course 11', 'Main Course 11', 211.00, 'mc11.jpg', 1, 2, '2025-04-20 13:02:00', '2025-04-20 13:02:00', 'plate'),
(36, 'Main Course 12', 'Main Course 12', 212.00, 'mc12.jpg', 1, 2, '2025-04-20 13:02:16', '2025-04-20 13:02:16', 'plate'),
(37, 'Main Course 13', 'Main Course 13', 213.00, 'mc13.jpg', 1, 2, '2025-04-20 13:02:34', '2025-04-20 13:02:34', 'plate'),
(38, 'Main Course 14', 'Main Course 14', 214.00, 'mc14.jpg', 1, 2, '2025-04-20 13:02:51', '2025-04-20 13:02:51', 'plate'),
(39, 'Main Course 15', 'Main Course 15', 215.00, 'mc15.jpg', 1, 2, '2025-04-20 13:03:08', '2025-04-20 13:03:08', 'plate'),
(40, 'Main Course 16', 'Main Course 16', 216.00, 'mc16.jpg', 1, 2, '2025-04-20 13:03:27', '2025-04-20 13:03:27', 'plate'),
(41, 'Main Course 17', 'Main Course 17', 217.00, 'mc17.jpg', 1, 2, '2025-04-20 13:04:13', '2025-04-20 13:04:13', 'plate'),
(42, 'Main Course 18', 'Main Course 18', 218.00, 'mc18.jpg', 1, 2, '2025-04-20 13:04:34', '2025-04-20 13:04:34', 'plate'),
(43, 'Main Course 19', 'Main Course 19', 219.00, 'mc19.jpg', 1, 2, '2025-04-20 13:05:09', '2025-04-20 13:05:09', 'plate'),
(44, 'Main Course 20', 'Main Course 20', 220.00, 'mc20.jpg', 1, 2, '2025-04-20 13:05:38', '2025-04-20 13:05:38', 'plate'),
(45, 'Main Course 21', 'Main Course 21', 221.00, 'mc21.jpg', 1, 2, '2025-04-20 13:06:03', '2025-04-20 13:06:03', 'plate'),
(46, 'Main Course 22', 'Main Course 22', 222.00, 'mc22.jpg', 1, 2, '2025-04-20 13:06:20', '2025-04-20 13:06:20', 'plate'),
(47, 'Main Course 23', 'Main Course 23', 223.00, 'mc23.jpg', 1, 2, '2025-04-20 13:06:40', '2025-04-20 13:06:40', 'plate'),
(48, 'Main Course 24', 'Main Course 24', 224.00, 'mc24.jpg', 1, 2, '2025-04-20 13:06:57', '2025-04-20 13:06:57', 'plate');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `admin_id`, `name`, `description`, `price`, `image`, `created_at`, `updated_at`) VALUES
(2, 1, 'Gold Package', 'Includes premium meals and dessertss', 5000.00, '1745128862_444.jpg', '2025-02-27 00:01:36', '2025-04-20 14:01:02'),
(3, 1, 'Platinum Package', 'Luxury catering with full-course meals', 12000.00, '1745128867_5.jpg', '2025-02-27 00:01:36', '2025-04-20 14:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `package_menu_items`
--

CREATE TABLE `package_menu_items` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_orders`
--

CREATE TABLE `package_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `unit_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','preparing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `ordered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_orders`
--

INSERT INTO `package_orders` (`id`, `user_id`, `package_id`, `quantity`, `unit_price`, `status`, `ordered_at`, `updated_at`) VALUES
(1, 2, 2, 1, 5000.00, 'cancelled', '2025-04-20 15:47:41', '2025-04-20 22:23:05'),
(2, 2, 2, 2, 5000.00, 'pending', '2025-04-20 15:49:00', '2025-04-20 15:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `partnerships`
--

CREATE TABLE `partnerships` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partnerships`
--

INSERT INTO `partnerships` (`id`, `name`, `service_type_id`, `contact_person`, `phone`, `email`, `created_at`) VALUES
(1, 'Elite Catering Services', 1, 'John Doe', '123-456-7890', 'john@elitecatering.com', '2025-02-27 00:06:49'),
(2, 'Dream Weddings', 2, 'Sarah Smith', '987-654-3210', 'sarah@dreamweddings.com', '2025-02-27 00:06:49'),
(3, 'Floral Fantasy', 3, 'Mike Johnson', '555-123-4567', 'mike@floralfantasy.com', '2025-02-27 00:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_status_id` int(11) DEFAULT 1,
  `gcash_reference` varchar(50) DEFAULT NULL,
  `gcash_receipt` varchar(255) DEFAULT NULL,
  `cash_paid` decimal(10,2) DEFAULT NULL,
  `refund_status_id` int(11) DEFAULT 1,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_reason` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','confirmed','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `payment_method_id`, `payment_status_id`, `gcash_reference`, `gcash_receipt`, `cash_paid`, `refund_status_id`, `refund_amount`, `refund_reason`, `created_at`, `updated_at`, `status`) VALUES
(1, 10, 1, 500.00, 1, 1, 'GC123456', '1.webp', NULL, 1, NULL, NULL, '2025-03-19 14:16:25', '2025-03-19 16:05:21', 'confirmed'),
(2, 11, 2, 750.50, 2, 1, NULL, '1.webp', 750.50, 1, NULL, NULL, '2025-03-19 14:16:25', '2025-03-19 14:49:17', 'pending'),
(3, 12, 3, 1200.75, 1, 2, 'GC987654', '1.webp', NULL, 2, 200.00, 'Customer requested refund', '2025-03-19 14:16:25', '2025-03-19 16:05:12', 'confirmed'),
(19, 31, 2, 200.00, 1, 1, '2323332321231', 'uploads/receipts/1745149738_444.jpg', NULL, 1, NULL, NULL, '2025-04-20 19:48:58', '2025-04-20 19:48:58', 'pending'),
(20, 32, 2, 200.00, 1, 1, 'dsada', 'uploads/receipts/1745154332_1.jpg', NULL, 1, NULL, NULL, '2025-04-20 21:05:32', '2025-04-20 21:05:32', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method`) VALUES
(2, 'cash'),
(1, 'gcash');

-- --------------------------------------------------------

--
-- Table structure for table `payment_statuses`
--

CREATE TABLE `payment_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_statuses`
--

INSERT INTO `payment_statuses` (`id`, `status`) VALUES
(2, 'completed'),
(3, 'failed'),
(1, 'pending'),
(4, 'refunded');

-- --------------------------------------------------------

--
-- Table structure for table `refund_statuses`
--

CREATE TABLE `refund_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refund_statuses`
--

INSERT INTO `refund_statuses` (`id`, `status`) VALUES
(2, 'approved'),
(1, 'pending'),
(3, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'dsa', 'dsa@gmail.com', 'dsa', '2025-04-20 07:45:01');

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `name`, `description`) VALUES
(1, 'Wedding', 'Food and beverage service for events'),
(2, 'Birthday', 'Professional event photography service'),
(3, 'Anniversary', 'Full event planning and coordination');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `phone_verified` tinyint(1) DEFAULT 0,
  `account_status_id` int(11) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `user_type_id`, `email_verified`, `phone_verified`, `account_status_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'cater-craft@services.com', '$2y$10$cH8tDhHwz9xBktxFD4sed.0CSY0fkILD2reqL9hGcmkoK2I6R799a', '1234567890', '123 Admin St', 2, 0, 0, 1, '2025-02-26 23:55:25', '2025-02-27 10:56:34'),
(2, 'franklyn', 'franklyn@gmail.com', '$2y$10$90SiOSCXDUFwtKMJAC6b3ueRkajK9993K101XGSC2aVhRZsSYBLJq', '09559998986', '123 Dayag Street', 1, 1, 1, 1, '2025-02-26 23:59:59', '2025-04-20 19:43:45'),
(3, 'leigh', 'leigh@gmail.com', '$2y$10$oJ1cl8chQLJHWyiOJ3MuXe0XAJbAmSkp2po.2.1Pge/tIoXOo/SLe', '09559998988', '321 leigh Street ', 1, 1, 1, 1, '2025-02-26 23:59:59', '2025-04-20 19:43:47'),
(4, 'jay', 'jay@gmail.com', '$2y$10$oJ1cl8chQLJHWyiOJ3MuXe0XAJbAmSkp2po.2.1Pge/tIoXOo/SLe', '09559998333', '098 jay street', 1, 1, 1, 1, '2025-02-26 23:59:59', '2025-04-20 19:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type`) VALUES
(2, 'admin'),
(1, 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_statuses`
--
ALTER TABLE `account_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `availability_statuses`
--
ALTER TABLE `availability_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `availability_status_id` (`availability_status_id`),
  ADD KEY `idx_bookings_status` (`status_id`),
  ADD KEY `idx_bookings_customer` (`customer_id`);

--
-- Indexes for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `booking_statuses`
--
ALTER TABLE `booking_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `gcash_proof`
--
ALTER TABLE `gcash_proof`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `package_menu_items`
--
ALTER TABLE `package_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `partnerships`
--
ALTER TABLE `partnerships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_type_id` (`service_type_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gcash_reference` (`gcash_reference`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `refund_status_id` (`refund_status_id`),
  ADD KEY `idx_payments_status` (`payment_status_id`),
  ADD KEY `idx_payments_user` (`user_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method` (`method`);

--
-- Indexes for table `payment_statuses`
--
ALTER TABLE `payment_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `refund_statuses`
--
ALTER TABLE `refund_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `account_status_id` (`account_status_id`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_user_type` (`user_type_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_statuses`
--
ALTER TABLE `account_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `availability_statuses`
--
ALTER TABLE `availability_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `booking_statuses`
--
ALTER TABLE `booking_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gcash_proof`
--
ALTER TABLE `gcash_proof`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `package_menu_items`
--
ALTER TABLE `package_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `package_orders`
--
ALTER TABLE `package_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `partnerships`
--
ALTER TABLE `partnerships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_statuses`
--
ALTER TABLE `payment_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `refund_statuses`
--
ALTER TABLE `refund_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `booking_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`availability_status_id`) REFERENCES `availability_statuses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gcash_proof`
--
ALTER TABLE `gcash_proof`
  ADD CONSTRAINT `gcash_proof_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `package_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_menu_items`
--
ALTER TABLE `package_menu_items`
  ADD CONSTRAINT `package_menu_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_menu_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD CONSTRAINT `package_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_orders_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partnerships`
--
ALTER TABLE `partnerships`
  ADD CONSTRAINT `partnerships_ibfk_1` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`refund_status_id`) REFERENCES `refund_statuses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`account_status_id`) REFERENCES `account_statuses` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
