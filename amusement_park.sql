-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 31, 2026 at 06:04 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amusement_park`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Kavitha', 'kavi@gmail.com', 'kkk');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `ride_id` int DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `slot_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancel_date` date DEFAULT NULL,
  `refund_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `staff_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `ride_id`, `booking_date`, `slot_time`, `amount`, `status`, `ticket_image`, `cancel_date`, `refund_status`, `created_at`, `staff_id`) VALUES
(1, 1, 4, '2026-04-03', '10AM-12PM', 100, 'Confirmed', 'ticket_image/ticket_1.png', NULL, 'Pending', '2026-03-25 22:08:51', 0),
(2, 1, 1, '2026-03-26', '10AM-12PM', 500, 'Verified', 'ticket_image/ticket_2.png', NULL, 'Pending', '2026-03-25 22:09:06', 0),
(3, 1, 2, '2026-03-30', '10AM-12PM', 600, 'Confirmed', 'ticket_image/ticket_3.png', NULL, 'Pending', '2026-03-25 22:09:31', 0),
(4, 1, 5, '2026-03-26', '10AM-12PM', 300, 'Confirmed', 'ticket_image/ticket_4.png', NULL, 'Pending', '2026-03-25 22:12:03', 0),
(5, 1, 4, '2026-03-28', '10AM-12PM', 100, 'Confirmed', 'ticket_image/ticket_5.png', NULL, 'Pending', '2026-03-25 22:12:56', 0),
(6, 1, 3, '2026-04-03', '10AM-12PM', 500, 'Confirmed', 'ticket_image/ticket_6.png', NULL, 'Pending', '2026-03-25 22:16:07', 0),
(7, 1, 2, '2026-04-04', '10AM-12PM', 600, 'Confirmed', 'ticket_image/ticket_7.png', NULL, 'Pending', '2026-03-25 22:16:38', 0),
(8, 2, 8, '2026-04-10', '10AM-12PM', 50, 'Confirmed', 'ticket_image/ticket_8.png', NULL, 'Pending', '2026-03-25 22:17:23', 0),
(9, 2, 7, '2026-03-26', '4PM-6PM', 100, 'Cancelled', 'ticket_image/ticket_9.png', '2026-03-25', 'Pending', '2026-03-25 22:17:49', 0),
(10, 0, 1, '2026-03-25', '10AM-12PM', 550, 'Walk-In', '', NULL, 'Pending', '2026-03-25 22:19:36', 1),
(11, 1, 6, '2026-04-02', '10AM-12PM', 100, 'Cancelled', 'ticket_image/ticket_11.png', '2026-03-31', 'Pending', '2026-03-31 11:13:05', 0),
(12, 2, 2, '2026-04-04', '10AM-12PM', 600, 'Confirmed', 'ticket_image/ticket_12.png', NULL, 'Pending', '2026-03-31 11:29:57', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

DROP TABLE IF EXISTS `rides`;
CREATE TABLE IF NOT EXISTS `rides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ride_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `age_limit` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `images` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`id`, `ride_name`, `price`, `age_limit`, `description`, `images`, `category`, `capacity`, `status`) VALUES
(1, 'sky drop', 500, 12, 'Experience an extreme free fall from a tall tower that gives you an exciting adrenaline rush.', 'skydrop1.jpg.jpeg', 'Thrill', 10, 'Blocked'),
(3, 'Gaint wheel', 500, 5, 'A relaxing ride that lifts you high above the park, offering a beautiful panoramic view of the surroundings.', 'gaint.jpg', 'Family', 20, 'Active'),
(5, 'Acqua splash', 300, 5, 'Exciting water ride where splashes, speed, and fun thrill riders.', 'water.jpg', 'Mini Ride', 5, 'Active'),
(2, 'Roller coaster', 600, 12, 'A high-speed ride with sharp turns, steep drops, and thrilling loops that deliver an unforgettable adventure.', 'roller.jpg', 'Thrill', 20, 'Blocked'),
(4, 'Family boating', 100, 5, 'Family boating ride where families relax together and enjoy calm water.\r\n', 'boating1.jpg', 'Family', 5, 'Blocked'),
(6, 'Kids boating', 100, 3, 'Safe and fun boating ride designed specially for kids enjoyment.', 'boating2.jpg', 'Mini Ride', 10, 'Active'),
(7, 'Car driving', 100, 3, 'Fun mini car ride where kids enjoy driving safely.', 'car.jpg', 'Mini Ride', 10, 'Active'),
(8, 'Carousel ride', 50, 5, 'Classic spinning ride with colorful horses, fun for kids.', 'horse.jpg', 'Mini Ride', 20, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `ride_slots`
--

DROP TABLE IF EXISTS `ride_slots`;
CREATE TABLE IF NOT EXISTS `ride_slots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ride_id` int DEFAULT NULL,
  `slot_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ride_slots`
--

INSERT INTO `ride_slots` (`id`, `ride_id`, `slot_time`) VALUES
(1, 1, '10AM-12PM'),
(2, 1, '12PM-2PM'),
(3, 1, '2PM-4PM'),
(4, 1, '4PM-6PM'),
(5, 2, '10AM-12PM'),
(6, 2, '12PM-2PM'),
(7, 2, '2PM-4PM'),
(8, 2, '4PM-6PM'),
(9, 3, '10AM-12PM'),
(10, 3, '12PM-2PM'),
(11, 3, '2PM-4PM'),
(12, 3, '4PM-6PM'),
(13, 4, '10AM-12PM'),
(14, 4, '12PM-2PM'),
(15, 4, '2PM-4PM'),
(16, 4, '4PM-6PM'),
(17, 5, '10AM-12PM'),
(18, 5, '12PM-2PM'),
(19, 5, '2PM-4PM'),
(20, 5, '4PM-6PM'),
(21, 6, '10AM-12PM'),
(22, 6, '12PM-2PM'),
(23, 6, '2PM-4PM'),
(24, 6, '4PM-6PM'),
(25, 7, '10AM-12PM'),
(26, 7, '12PM-2PM'),
(27, 7, '2PM-4PM'),
(28, 7, '4PM-6PM'),
(29, 8, '10AM-12PM'),
(30, 8, '12PM-2PM'),
(31, 8, '2PM-4PM'),
(32, 8, '4PM-6PM');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ride_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_salary` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `password`, `role`, `created_at`, `ride_category`, `monthly_salary`) VALUES
(1, 'joe', 's1@gmail.com', 'sss', 'staff', '2026-03-23 06:36:11', 'Thrill', NULL),
(2, 'rio', 's2@gmail.com', 'sss', 'staff', '2026-03-23 06:37:28', 'Family', NULL),
(3, 'zaro', 's3@gmail.com', 'sss', 'staff', '2026-03-23 06:38:44', 'Mini Ride', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`) VALUES
(1, 'ammu', 'a@gmail.com', 'aaa', '6754322123', ''),
(2, 'banu', 'b@gmail.com', 'bbb', '2345432123', ''),
(3, 'chandra', 'c@gmail.com', 'ccc', '5678943321', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
