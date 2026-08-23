-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2026 at 03:59 AM
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
-- Table structure for table `screening`
--

CREATE TABLE `screening` (
  `id` int(11) NOT NULL,
  `bag_id` varchar(11) DEFAULT NULL,
  `abo_group` int(4) DEFAULT NULL COMMENT '1=A, 2=B, 3=AB, 4=O',
  `rh_type` int(4) DEFAULT NULL COMMENT '1=positive(+), 2=negative(-)',
  `hiv` int(4) DEFAULT NULL COMMENT '1=pending, 2=non-reactive, 3=reactive, 4=invalid',
  `hbsag` int(4) DEFAULT NULL COMMENT '1=pending, 2=non-reactive, 3=reactive, 4=invalid',
  `hcv` int(4) DEFAULT NULL COMMENT '1=pending, 2=non-reactive, 3=reactive, 4=invalid',
  `syphilis` int(4) DEFAULT NULL COMMENT '1=pending, 2=non-reactive, 3=reactive, 4=invalid',
  `malaria` int(4) DEFAULT NULL COMMENT '1=pending, 2=non-reactive, 3=reactive, 4=invalid',
  `other` int(4) DEFAULT NULL COMMENT '1=positive(+), 2=negative(-)',
  `status` int(4) DEFAULT NULL COMMENT '1=pending, 2=passed, 3=quarantined, 4=reactive, 5=invalid, 6=discarded, 7=released',
  `tested_by` varchar(40) DEFAULT NULL,
  `tested_at` datetime DEFAULT NULL,
  `doctor_id` int(16) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screening`
--

INSERT INTO `screening` (`id`, `bag_id`, `abo_group`, `rh_type`, `hiv`, `hbsag`, `hcv`, `syphilis`, `malaria`, `other`, `status`, `tested_by`, `tested_at`, `doctor_id`, `verified_at`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'B001', 2, 1, 1, 1, 1, 1, 1, 1, 1, 'Kamal', '2026-08-06 14:44:00', 10, '2026-08-14 17:12:00', 'Still some tests need to do', NULL, NULL, NULL),
(2, 'B002', 3, 2, 1, 2, 2, 1, 1, 2, 4, 'Kamal', '2026-08-03 12:00:00', 9, '2026-08-07 14:00:00', 'Test again', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `screening`
--
ALTER TABLE `screening`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `screening`
--
ALTER TABLE `screening`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
