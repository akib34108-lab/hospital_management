-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2026 at 07:33 PM
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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `sub_amount` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(50) NOT NULL DEFAULT 'Due',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `status` int(11) DEFAULT 1 COMMENT '1=Active, 0=Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `patient_id`, `sub_amount`, `discount`, `tax`, `paid_amount`, `due_amount`, `payment_status`, `deleted_at`, `invoice_date`, `status`) VALUES
(1, 2, 12240.00, 300.00, 10.00, 0.00, 0.00, 'Due', NULL, '2026-08-08', 1),
(2, 5, 2266.00, 66.00, 10.00, 0.00, 0.00, 'Due', NULL, '2026-08-08', 1),
(3, 2, 2208.00, 1000.00, 800.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(4, 2, 1700.00, 170.00, 0.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(5, 1, 6500.00, 1300.00, 0.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(6, 1, 6500.00, 650.00, 325.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(7, 5, 3300.00, 561.00, 273.90, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
