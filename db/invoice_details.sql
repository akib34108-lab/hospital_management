-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2026 at 05:37 AM
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
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL COMMENT 'Medicine/Test/Service Name',
  `price` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_details`
--

INSERT INTO `invoice_details` (`id`, `invoice_id`, `Name`, `price`, `discount`, `tax`, `deleted_at`) VALUES
(4, 1, 'cbc', 2400.00, 20.00, 15.00, NULL),
(5, 1, 'vitamin D', 6000.00, 17.00, 15.00, NULL),
(6, 1, 'Thyroid', 4000.00, 13.00, 15.00, NULL),
(8, 2, 'cbc', 2200.00, 12.00, 15.00, NULL),
(9, 3, 'CBC', 2400.00, 15.00, 7.00, NULL),
(10, 0, 'CBC', 1700.00, 5.00, 0.00, NULL),
(11, 0, 'Vitamin D test', 6500.00, 10.00, 0.00, NULL),
(12, 0, 'CBC', 1700.00, 5.00, 0.00, NULL),
(13, 6, 'CBC', 1700.00, 5.00, 15.00, NULL),
(14, 7, 'Colonscopy', 7800.00, 3.00, 3.00, NULL),
(15, 4, 'Electrocardiogram(ECG)', 2000.00, 5.00, 0.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
