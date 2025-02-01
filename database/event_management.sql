-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 07:57 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `event_id`, `name`, `email`, `phone`, `registered_at`) VALUES
(9, 11, 'dawddaw test', '100shop1688@gmail.com', '01776387693', '2025-01-31 15:30:42'),
(10, 9, 'Maires', 'falconzonebd@gmail.com', '01776387697', '2025-01-31 15:30:49'),
(11, 9, 'Nahin test', 'o@oo', '01776387693', '2025-01-31 18:35:47'),
(12, 9, 'Nahin test', 'falconzonebd@gmail.com', '01776387693', '2025-01-31 18:35:55'),
(14, 9, 'KIDS BOTTOM DENIM', '', '01776387693', '2025-01-31 18:36:17'),
(17, 9, 'nahin f', 'nahinislam6@gmail.com', '0177638793', '2025-01-31 20:16:21'),
(18, 9, 'Nahin test', 'lokmanakram1@gmail.com', '01776387690', '2025-01-31 20:25:58'),
(19, 9, 'dawddaw test', 'o@oo', '01776387697', '2025-01-31 20:26:53'),
(20, 9, 'adv', '', '01776387693', '2025-01-31 20:28:16'),
(21, 9, 'Nahin testing', '', '01776387697', '2025-01-31 20:29:00'),
(22, 9, 'awdwad', '', '01776387690', '2025-01-31 20:30:38'),
(23, 9, 'new', '', '01776387693', '2025-01-31 20:32:53'),
(24, 9, 'new', '', '01776387693', '2025-01-31 20:33:00'),
(25, 9, 'faw', 'lokmanakram1@gmail.com', '01776387697', '2025-01-31 20:34:40'),
(26, 9, 'NEW', 'falconzonebd@gmail.com', '01776387693', '2025-01-31 20:35:58'),
(27, 9, 'LFE', 'o@oo', '01776387697', '2025-01-31 20:36:09'),
(30, 9, 'Nahin test', 'falconzonebd@gmail.com', '01776387693', '2025-01-31 20:48:36'),
(31, 9, 'Maires', 'o@oo', '01776387693', '2025-01-31 20:49:00'),
(32, 9, 'load', '', '01776387693', '2025-01-31 20:52:04'),
(33, 9, 'Nahin test load', 'lokmanakram1@gmail.com', '01776387697', '2025-01-31 21:07:02'),
(34, 9, 'Nahin test', 'a@aa', '0', '2025-01-31 21:07:17'),
(35, 9, 'Nahin test', 'falconzonebd@gmail.com', '01776387693', '2025-01-31 21:13:37'),
(36, 9, 'ajax', '100shop1688@gmail.com', '01776387697', '2025-01-31 21:16:06'),
(37, 9, 'load', 'o@oo', '01776387693', '2025-01-31 21:16:22'),
(38, 9, 'ne load', 'o@oo', '01776387693', '2025-01-31 21:20:18'),
(39, 9, 'Nahin test', 'o@oo', '01776387693', '2025-01-31 21:20:53'),
(40, 9, 'Nahin test', 'o@oo', '01776387693', '2025-01-31 21:23:59'),
(41, 9, 'Nahin sess', '', '01776387693', '2025-01-31 21:27:30'),
(42, 9, 'Nahin test', 'o@oo', '01776387693', '2025-01-31 21:27:49'),
(43, 9, 'gg', 'o@oo', '01776387697', '2025-01-31 21:29:19'),
(44, 9, 'aj', '', '01776387697', '2025-01-31 21:32:26'),
(45, 9, 'LFE', 'lokmanakram1@gmail.com', '01776387697', '2025-01-31 21:34:19'),
(46, 9, 'Nahin test', 'lokmanakram1@gmail.com', '01776387693', '2025-01-31 21:36:41'),
(47, 9, 'Nahin test', 'lokmanakram1@gmail.com', '01776387693', '2025-01-31 21:37:10'),
(48, 9, 'testing', 'lokmanakram1@gmail.com', '01776387693', '2025-01-31 21:46:00'),
(49, 9, 'Nahin test ajax', '100shop1688@gmail.com', '01776387693', '2025-01-31 21:47:28'),
(50, 9, 'Nahin test', 'falconzonebd@gmail.com', '01776387697', '2025-01-31 21:48:35'),
(51, 9, 'ajax', 'a@aa', '01776387697', '2025-01-31 21:50:02'),
(52, 9, 'aff', '100shop1688@gmail.com', '01776387697', '2025-01-31 21:54:25'),
(53, 9, 'Nahin test', 'lokmanakram1@gmail.com', '01776387693', '2025-01-31 22:13:20'),
(54, 9, 'ajax', '', '01776387693', '2025-01-31 22:13:30'),
(55, 9, 'Nahin test', '100shop1688@gmail.com', '01776387693', '2025-01-31 22:16:05'),
(56, 9, 'Nahin test', '100shop1688@gmail.com', '01776387697', '2025-01-31 22:19:35'),
(57, 9, 'ajax a', 'o@oo', '01776387693', '2025-01-31 22:19:47'),
(58, 9, 'form a', 'a@aa.com', '01776387693', '2025-01-31 22:19:59'),
(59, 9, 'new aj', 'o@oo', '01776387693', '2025-01-31 22:29:04'),
(60, 9, 'new lio', 'o@oo', '01776387693', '2025-01-31 22:29:16'),
(61, 9, 'Nahin', 'o@oo', '01776387697', '2025-02-01 17:08:32'),
(62, 9, 'Nahin', 'o@oo', '01776387697', '2025-02-01 17:08:38'),
(63, 9, 'Nahin testing aa', 'o@oaao', '01776387697', '2025-02-01 17:11:10'),
(64, 11, 'Nahin test', 'a@aa.com', '01776387697', '2025-02-01 17:14:08'),
(65, 11, 'LFE', 'a@aa.com', '01776387697', '0000-00-00 00:00:00'),
(66, 9, 'Maires', '', '01776387693', '2025-02-03 04:00:00'),
(67, 9, 'test', 'o@oo', '01776387693', '2025-02-02 18:16:31'),
(68, 11, 'test user', 'o@oo', '01776387693', '2025-02-03 17:19:28'),
(69, 14, 'Nahin', '', '01776387693', '2025-02-01 18:40:03'),
(70, 14, 'Kawsar bhai', 'o@oo', '01776387690', '2025-02-01 18:42:58'),
(71, 14, 'Shawn', 'lok@gmail.com', '01776387697', '2025-02-01 18:43:42'),
(72, 14, 'Imran', '', '01776387697', '2025-02-01 18:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_capacity` int(11) NOT NULL,
  `current_capacity` int(11) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `location` text NOT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `max_capacity`, `current_capacity`, `created_by`, `location`, `image`, `created_at`, `updated_at`) VALUES
(9, 'LFE', 'Goooooooooooooooooo', 85, 80, 8, 'IUB', 'received_621439299491197.jpeg', '2025-01-29 19:07:37', '2025-02-01 17:18:23'),
(11, 'Birthdays', 'd', 51, 8, 8, 'Dhaka,bd', 'Lenna_(test_image).png', '2025-01-31 10:41:25', '2025-02-01 17:19:28'),
(12, 'KIDS BOTTOM DENIM', 'bashundhara RA', 44, 44, 8, 'bashundhara RA', 'admin.jpg', '2025-01-31 11:40:03', '2025-01-31 22:43:02'),
(13, 'Wedding ', 'wedddddddddddddddddinggggggggggg', 20, 0, 8, 'Dhaka,bd', 'png-transparent-computer-icons-shopping-happiness-customer-miscellaneous-hand-logo.png', '2025-02-01 17:36:37', '2025-02-01 17:36:37'),
(14, 'Concert', 'ICCB CENTER BASHUNDHARA', 200, 4, 9, 'ICCB CENTER BASHUNDHARA', 'FB_IMG_1663930226778.jpg', '2025-02-01 18:38:43', '2025-02-01 18:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(7, 'naas', 'nahinislam6@gmail.com', '$2y$10$EoPTM/l3xIY.TeoKW8krAunPHIwVWyBsBIxt.2d4pGY...', 'user', '2025-01-29 16:15:13'),
(8, 'Nahin islam', 'a@aa.com', '$2y$10$EoPTM/l3xIY.TeoKW8krAunPHIwVWyBsBIxt.2d4pGY1CdU1Ze8SK', 'user', '2025-01-29 16:23:29'),
(9, 'Nahin', 'n@n.com', '$2y$10$4nZ0zP8kMSxgKJbJtDWdouQpls8QYJxE20Fcxmiy2LaBIyGmRBvve', 'user', '2025-02-01 18:35:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
