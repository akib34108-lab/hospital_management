-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 04:09 PM
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
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(150) NOT NULL,
  `generic_name` varchar(150) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `dosage_form` varchar(50) DEFAULT NULL,
  `strength` varchar(50) DEFAULT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `manufacturer` varchar(150) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `reorder_level` int(11) DEFAULT 10,
  `expiry_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `medicine_name`, `generic_name`, `category`, `dosage_form`, `strength`, `unit`, `description`, `manufacturer`, `unit_price`, `reorder_level`, `expiry_date`, `status`, `deleted_at`, `created_at`) VALUES
(1, 'napa', 'paracetamol', 'Painkiller', 'Tablet', '500', 'Piece', 'tghhgt', 'beximco', 20.00, 10, '2026-08-15', 'Active', NULL, '2026-08-15 14:45:39'),
(2, 'Frenxit', 'sleep', 'Antihistamine', 'Tablet', '40', 'Piece', 'dffvd', 'square', 15.00, 5, '2026-08-15', 'Active', NULL, '2026-08-15 14:49:24'),
(3, 'Tafnil', 'paracetamol', 'Painkiller', 'Tablet', '1000', 'Strip', 'painkiller', 'Aristopharma', 40.00, 25, '2026-08-16', 'Active', NULL, '2026-08-16 15:18:22'),
(4, 'Reset ER', 'paracetamol', 'Painkiller', 'Tablet', '650', 'Strip', 'Painkiller', 'Square', 30.00, 10, '2026-12-24', 'Active', NULL, '2026-08-24 13:50:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
