-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2026 at 02:11 AM
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
-- Database: `shifa`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient_lab_tests`
--

CREATE TABLE `patient_lab_tests` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  `test_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `test_date` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Completed',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_lab_tests`
--

INSERT INTO `patient_lab_tests` (`id`, `patient_id`, `admission_id`, `test_id`, `test_price`, `test_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 1700.00, '2026-08-21', 'Completed', '2026-08-21 12:16:23', NULL, NULL),
(3, 5, 2, 2, 6500.00, '2026-08-21', 'Completed', '2026-08-21 14:06:06', NULL, NULL),
(4, 5, 2, 1, 1700.00, '2026-08-21', 'Completed', '2026-08-21 14:06:11', NULL, NULL),
(5, 9, 4, 2, 6500.00, '2026-08-22', 'Completed', '2026-08-22 13:05:05', NULL, NULL),
(6, 9, 4, 1, 1700.00, '2026-08-22', 'Completed', '2026-08-22 13:05:11', NULL, NULL),
(7, 10, 5, 1, 1700.00, '2026-08-24', 'Completed', '2026-08-24 13:55:22', NULL, NULL),
(8, 10, 5, 2, 6500.00, '2026-08-24', 'Completed', '2026-08-24 13:55:26', NULL, NULL),
(9, 9, 4, 3, 7800.00, '2026-08-25', '1', '2026-08-25 19:50:46', '2026-08-25 19:50:46', NULL),
(10, 9, 4, 3, 7800.00, '2026-08-25', '1', '2026-08-25 19:51:52', '2026-08-25 19:51:52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patient_lab_tests`
--
ALTER TABLE `patient_lab_tests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patient_lab_tests`
--
ALTER TABLE `patient_lab_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
