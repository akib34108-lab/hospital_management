-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2026 at 04:53 PM
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
-- Table structure for table `discharge_invoices`
--

CREATE TABLE `discharge_invoices` (
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `discharge_id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `bed_bill` decimal(12,2) NOT NULL DEFAULT 0.00,
  `doctor_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `test_bill` decimal(12,2) NOT NULL DEFAULT 0.00,
  `medicine_bill` decimal(12,2) NOT NULL DEFAULT 0.00,
  `service_bill` decimal(12,2) NOT NULL DEFAULT 0.00,
  `other_bill` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(30) NOT NULL DEFAULT 'Due',
  `payment_method` varchar(30) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discharge_invoices`
--

INSERT INTO `discharge_invoices` (`invoice_id`, `discharge_id`, `patient_id`, `invoice_no`, `bed_bill`, `doctor_fee`, `test_bill`, `medicine_bill`, `service_bill`, `other_bill`, `discount`, `total_amount`, `paid_amount`, `due_amount`, `payment_status`, `payment_method`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 'INV-20260816193037', 1500.00, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12204.98, 0.00, 12204.98, 'Unpaid', 'Card', '2026-08-16 23:30:37', NULL, NULL),
(2, 1, 5, 'DIN-20260816195225', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Card', '2026-08-16 23:52:25', NULL, NULL),
(3, 1, 5, 'DIN-20260816195535', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 0.00, 12205.70, 'Unpaid', 'Card', '2026-08-16 23:55:35', NULL, NULL),
(5, 2, 6, 'DIS-20260821162727', 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 2400.00, 0.00, 2400.00, 'Due', '', '2026-08-21 16:27:27', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discharge_invoices`
--
ALTER TABLE `discharge_invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discharge_invoices`
--
ALTER TABLE `discharge_invoices`
  MODIFY `invoice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
