-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2025 at 10:37 AM
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
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `affected_user_id` int(11) DEFAULT NULL,
  `affected_booking_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `caterer_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `guests` int(11) NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed','disputed') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `caterer_id`, `event_date`, `event_time`, `guests`, `venue`, `special_requests`, `status`, `total_amount`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-02-20', '18:00:00', 50, 'Cityville Conference Hall', 'Vegan options, no seafood', 'pending', 12500.00, NULL, '2025-02-02 17:19:31', '2025-02-02 17:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `booking_menus`
--

CREATE TABLE `booking_menus` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_menus`
--

INSERT INTO `booking_menus` (`id`, `booking_id`, `menu_id`, `quantity`) VALUES
(1, 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `booking_packages`
--

CREATE TABLE `booking_packages` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caterers`
--

CREATE TABLE `caterers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `business_registration` varchar(255) DEFAULT NULL,
  `food_permit` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `operating_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`operating_hours`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caterers`
--

INSERT INTO `caterers` (`id`, `user_id`, `business_name`, `description`, `location`, `contact`, `business_registration`, `food_permit`, `verification_status`, `operating_hours`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nica\'s Touch', 'Best catering service in Cityville', 'Cityville, Downtown', '09123456789', 'BR12345', 'FP12345', 'verified', '{\"Monday-Sunday\":\"10:00-22:00\"}', '2025-02-02 17:19:09', '2025-02-05 04:20:12'),
(2, 102, 'Ingga\'s Catering Services', 'Authentic Filipino cuisine for weddings and corporate events.', 'Makati, Philippines', '09234567890', 'BR-002', 'FP-002', 'verified', '{\"Monday-Sunday\":\"10:00-22:00\"}', '2025-02-05 04:17:20', '2025-02-05 04:22:52'),
(3, 103, 'Franklin\'s Touch', 'Specializing in fresh and delicious seafood platters.', 'Cebu City, Philippines', '09345678901', 'BR-003', 'FP-003', 'verified', '{\"Monday-Saturday\":\"07:00-20:00\",\"Sunday\":\"Closed\"}', '2025-02-05 04:17:20', '2025-02-05 04:21:57'),
(4, 104, 'Sample Catering name', 'Expertly grilled meats and BBQ catering services.', 'Davao City, Philippines', '09456789012', 'BR-004', 'FP-004', 'verified', '{\"Monday-Sunday\":\"11:00-23:00\"}', '2025-02-05 04:17:20', '2025-02-05 04:22:16'),
(5, 105, 'Elegant Events Catering', 'Premium catering for weddings and upscale gatherings.', 'Taguig, Philippines', '09567890123', 'BR-005', 'FP-005', 'rejected', '{\"Monday-Friday\":\"09:00-20:00\",\"Saturday\":\"10:00-18:00\",\"Sunday\":\"Closed\"}', '2025-02-05 04:17:20', '2025-02-05 04:17:20'),
(6, 101, 'Gourmet Delights Catering', 'Providing high-quality gourmet dishes for all occasions.', 'Quezon City, Philippines', '09123456789', 'BR-001', 'FP-001', 'verified', '{\"Monday-Friday\":\"08:00-18:00\",\"Saturday\":\"09:00-15:00\",\"Sunday\":\"Closed\"}', '2025-02-05 04:17:20', '2025-02-05 04:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `item_reviews`
--

CREATE TABLE `item_reviews` (
  `id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewed_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_type` enum('caterer','menu','service','package') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `caterer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `caterer_id`, `name`, `description`, `price`, `category`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Spaghetti', 'Delicious spaghetti with meatballs', 250.00, 'Pasta', 'spaghetti.jpg', '2025-02-02 17:19:21', '2025-02-02 17:19:21'),
(2, 1, 'Grilled Chicken', 'Juicy grilled chicken with spices', 300.00, 'Main Course', 'grilled_chicken.jpg', '2025-02-05 03:45:00', '2025-02-05 03:45:00'),
(3, 1, 'Caesar Salad', 'Fresh greens with creamy Caesar dressing', 150.00, 'Salads', 'caesar_salad.jpg', '2025-02-05 03:45:00', '2025-02-05 03:45:00'),
(4, 1, 'Chocolate Cake', 'Rich chocolate cake with layers of ganache', 200.00, 'Desserts', 'chocolate_cake.jpg', '2025-02-05 03:45:00', '2025-02-05 03:45:00'),
(5, 1, 'Mango Smoothie', 'Fresh mango blended with creamy yogurt', 100.00, 'Beverages', 'mango_smoothie.jpg', '2025-02-05 03:45:00', '2025-02-05 03:45:00'),
(6, 1, 'Classic Italian Pasta', 'Delicious spaghetti with homemade sauce', 250.00, 'Main Course', 'pasta.jpg', '2025-02-05 03:45:00', '2025-02-05 03:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `caterer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `caterer_id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(2, 1, 'Birthday Package', 'A special menu for birthday celebrations', 3500.00, '2025-02-05 03:45:36', '2025-02-05 03:45:36'),
(3, 1, 'Corporate Package', 'Customizable menu for company events', 7000.00, '2025-02-05 03:45:36', '2025-02-05 03:45:36'),
(4, 1, 'Party Package', 'A selection of finger foods and drinks', 4000.00, '2025-02-05 03:45:36', '2025-02-05 03:45:36'),
(5, 1, 'Anniversary Package', 'Romantic dinner setup with gourmet dishes', 6000.00, '2025-02-05 03:45:36', '2025-02-05 03:45:36'),
(6, 1, 'Wedding Package', 'Complete wedding catering with appetizers, main course, and desserts', 5000.00, '2025-02-05 03:45:36', '2025-02-05 03:45:36');

-- --------------------------------------------------------

--
-- Table structure for table `package_services`
--

CREATE TABLE `package_services` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_services`
--

INSERT INTO `package_services` (`id`, `package_id`, `service_id`) VALUES
(1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `gcash_reference` varchar(100) NOT NULL,
  `refund_status` enum('not_requested','pending','approved','denied') DEFAULT 'not_requested',
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reported_user_id` int(11) NOT NULL,
  `reporter_user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','resolved','escalated') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `caterer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `caterer_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Buffet Service', 'All-you-can-eat buffet for guests', '2025-02-05 03:46:56', '2025-02-05 03:46:56'),
(2, 1, 'Table Service', 'Full-service dining with waiters', '2025-02-05 03:46:56', '2025-02-05 03:46:56'),
(3, 1, 'Cocktail Service', 'Drinks and snacks for receptions', '2025-02-05 03:46:56', '2025-02-05 03:46:56'),
(4, 1, 'Live Cooking Station', 'Live chefs preparing food at the venue', '2025-02-05 03:46:56', '2025-02-05 03:46:56'),
(5, 1, 'Event Decoration', 'Complete table and venue decoration', '2025-02-05 03:46:56', '2025-02-05 03:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `service_items`
--

CREATE TABLE `service_items` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_items`
--

INSERT INTO `service_items` (`id`, `service_id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Buffet Setup', 'Includes chafing dishes, serving trays, and utensils', 500.00, '2025-02-05 03:47:13', '2025-02-05 03:47:13'),
(2, 2, 'Waiter Service', 'Professional waitstaff for event service', 1000.00, '2025-02-05 03:47:13', '2025-02-05 03:47:13'),
(3, 3, 'Signature Cocktail', 'Specially crafted drinks by expert mixologists', 300.00, '2025-02-05 03:47:13', '2025-02-05 03:47:13'),
(4, 4, 'Live Grilling', 'Freshly grilled meat and seafood cooked on-site', 1200.00, '2025-02-05 03:47:13', '2025-02-05 03:47:13'),
(5, 5, 'Floral Arrangements', 'Beautiful flower centerpieces for tables', 800.00, '2025-02-05 03:47:13', '2025-02-05 03:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `user_type` enum('customer','caterer','admin') NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `phone_verified` tinyint(1) DEFAULT 0,
  `id_verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `account_status` enum('active','suspended','banned') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `user_type`, `email_verified`, `phone_verified`, `id_verification_status`, `account_status`, `created_at`, `updated_at`) VALUES
(1, 'John sample', 'john.doe@example.com', '$2a$12$41Pc7//PW2YBUN2pkHelmOG9sXyRF.nxXWC3HHGOG/MAjAJ5JoWZa', '09123456789', 'Ugac Sur', 'customer', 1, 1, 'verified', 'active', '2025-02-02 17:17:44', '2025-02-05 04:17:55'),
(101, 'Juan Dela Cruz', 'juan@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', '09123456789', 'Ugac Norte', 'caterer', 1, 1, 'verified', 'active', '2025-02-05 04:16:56', '2025-02-05 04:17:59'),
(102, 'Maria Santos', 'maria@example.com', '9878d344400c00f8bab1a4ba1a3488b3ace88aea983e3d94ba1c781e09ba32bb', '09234567890', 'Caritan', 'caterer', 1, 1, 'verified', 'active', '2025-02-05 04:16:56', '2025-02-05 04:18:09'),
(103, 'Pedro Lopez', 'pedro@example.com', 'eedc6dc73edebdcd6b600c7e877d66bf085580a1f61fbfdace7540ea9a795d56', '09345678901', 'Buntun', 'customer', 1, 0, 'pending', 'active', '2025-02-05 04:16:56', '2025-02-05 04:18:17'),
(104, 'Ana Reyes', 'ana@example.com', 'e619d261dba72b09e04b58c92ae92a92f8a8e1048af42a206faf71d83712fdfc', '09456789012', 'Atulayan', 'admin', 1, 1, 'verified', 'active', '2025-02-05 04:16:56', '2025-02-05 04:18:23'),
(105, 'Carlos Mendoza', 'carlos@example.com', 'bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f', '09567890123', 'Capatan', 'caterer', 0, 0, 'pending', 'suspended', '2025-02-05 04:16:56', '2025-02-05 04:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('id_document','id_selfie') NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `document_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `file_size` int(11) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `caterer_id` (`caterer_id`),
  ADD KEY `customer_id_2` (`customer_id`,`status`);

--
-- Indexes for table `booking_menus`
--
ALTER TABLE `booking_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `booking_packages`
--
ALTER TABLE `booking_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `caterers`
--
ALTER TABLE `caterers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviewer_id_2` (`reviewer_id`,`reviewed_id`,`review_type`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `reviewed_id` (`reviewed_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caterer_id` (`caterer_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caterer_id` (`caterer_id`);

--
-- Indexes for table `package_services`
--
ALTER TABLE `package_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gcash_reference` (`gcash_reference`),
  ADD KEY `gcash_reference_2` (`gcash_reference`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_user_id` (`reported_user_id`),
  ADD KEY `reporter_user_id` (`reporter_user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caterer_id` (`caterer_id`);

--
-- Indexes for table `service_items`
--
ALTER TABLE `service_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_menus`
--
ALTER TABLE `booking_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_packages`
--
ALTER TABLE `booking_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caterers`
--
ALTER TABLE `caterers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_reviews`
--
ALTER TABLE `item_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `package_services`
--
ALTER TABLE `package_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_items`
--
ALTER TABLE `service_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `user_documents`
--
ALTER TABLE `user_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`caterer_id`) REFERENCES `caterers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_menus`
--
ALTER TABLE `booking_menus`
  ADD CONSTRAINT `booking_menus_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_menus_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_packages`
--
ALTER TABLE `booking_packages`
  ADD CONSTRAINT `booking_packages_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `caterers`
--
ALTER TABLE `caterers`
  ADD CONSTRAINT `caterers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD CONSTRAINT `item_reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_reviews_ibfk_2` FOREIGN KEY (`reviewed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`caterer_id`) REFERENCES `caterers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`caterer_id`) REFERENCES `caterers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_services`
--
ALTER TABLE `package_services`
  ADD CONSTRAINT `package_services_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reporter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`caterer_id`) REFERENCES `caterers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_items`
--
ALTER TABLE `service_items`
  ADD CONSTRAINT `service_items_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD CONSTRAINT `user_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
