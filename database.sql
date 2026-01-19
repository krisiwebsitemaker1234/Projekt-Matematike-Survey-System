-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 07:25 PM
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
-- Database: `survey_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `num_options` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `theme`, `description`, `num_options`, `created_at`) VALUES
(1, 'Favorite Books', '', 5, '2026-01-05 21:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `record_options`
--

CREATE TABLE `record_options` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `option_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `record_options`
--

INSERT INTO `record_options` (`id`, `record_id`, `option_text`, `option_order`) VALUES
(1, 1, 'Dune', 1),
(2, 1, 'The Alchemist', 2),
(3, 1, 'Harry Potter', 3),
(4, 1, 'Lord Of The Rings', 4),
(5, 1, 'Twilight', 5);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `respondent_name` varchar(100) NOT NULL,
  `option_id` int(11) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `record_id`, `user_id`, `respondent_name`, `option_id`, `submitted_at`) VALUES
(1, 1, 1, 'Miri', 1, '2026-01-05 21:27:06'),
(2, 1, 1, 'Beni', 3, '2026-01-05 21:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$5ponfnJPuQ8WrDjDbUwTWO9k6I6vbrkWHJlJJ2f4f8iRafLPL00Na', '2026-01-05 21:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `created_at`) VALUES
(1, 'User1', '$2y$10$CrjhYXTUokBabgL03oAUHuAhK22i1trP5PiQdwR6eC/SnI6osCTxS', 'User One', '2026-01-05 21:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_assignments`
--

CREATE TABLE `user_assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_assignments`
--

INSERT INTO `user_assignments` (`id`, `user_id`, `record_id`, `assigned_at`) VALUES
(1, 1, 1, '2026-01-05 21:26:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `record_options`
--
ALTER TABLE `record_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_assignments`
--
ALTER TABLE `user_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`user_id`,`record_id`),
  ADD KEY `record_id` (`record_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `record_options`
--
ALTER TABLE `record_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_assignments`
--
ALTER TABLE `user_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `record_options`
--
ALTER TABLE `record_options`
  ADD CONSTRAINT `record_options_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `responses_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `record_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_assignments`
--
ALTER TABLE `user_assignments`
  ADD CONSTRAINT `user_assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_assignments_ibfk_2` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
