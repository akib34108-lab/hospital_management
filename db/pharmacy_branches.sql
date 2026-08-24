-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 04:08 PM
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
-- Table structure for table `pharmacy_branches`
--

CREATE TABLE `pharmacy_branches` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_code` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_branches`
--

INSERT INTO `pharmacy_branches` (`branch_id`, `branch_name`, `branch_code`, `location`, `phone`, `status`, `deleted_at`, `created_at`) VALUES
(1, 'SHIFA Anowara Pharmacy', 'Shifa3-Ano', 'Anowara,Chittagong', '019111111113', 'Active', NULL, '2026-08-15 15:21:37'),
(2, 'SHIFA Khulshi Pharmacy', 'SHP-002', 'khulshi,Chattogram', '019111111112', 'Active', NULL, '2026-08-24 13:10:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pharmacy_branches`
--
ALTER TABLE `pharmacy_branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pharmacy_branches`
--
ALTER TABLE `pharmacy_branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
