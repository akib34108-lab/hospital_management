-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 05:54 AM
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
-- Database: `dreams`
--

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `stock_date` date DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `purchase_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `stock_transfer_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_date`, `product_id`, `warehouse_id`, `quantity`, `purchase_id`, `sale_id`, `stock_transfer_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2026-08-12', 11, 6, 100, 1, NULL, NULL, 2026, '2026-08-12 06:28:21', NULL),
(2, '2026-08-12', 3, 6, 100, 1, NULL, NULL, 2026, '2026-08-12 06:28:21', NULL),
(4, '2026-08-12', 11, 6, 100, 3, NULL, NULL, 2026, '2026-08-12 06:34:54', NULL),
(5, '2026-08-12', 3, 6, 100, 3, NULL, NULL, 2026, '2026-08-12 06:34:54', NULL),
(6, '2026-08-13', 3, 6, -10, NULL, 3, NULL, 2026, '2026-08-12 07:21:37', NULL),
(7, '2026-08-12', 12, 6, 50, 4, NULL, NULL, 2026, '2026-08-12 07:23:30', NULL),
(8, '2026-08-12', 12, 6, -10, NULL, 4, NULL, 2026, '2026-08-12 07:24:00', NULL),
(9, '2026-08-09', 18, 4, 2, 5, NULL, NULL, 2026, '2026-08-12 16:12:44', NULL),
(10, '2026-08-10', 18, 3, 5, 6, NULL, NULL, 2026, '2026-08-12 16:53:01', NULL),
(11, '2026-08-11', 18, 4, -1, NULL, 5, NULL, 2026, '2026-08-12 16:53:58', NULL),
(12, NULL, 18, 3, 12, NULL, NULL, NULL, 0, '2026-08-13 14:11:46', NULL),
(13, '2026-08-13', 18, 3, 2, 7, NULL, NULL, 2026, '2026-08-14 05:31:47', NULL),
(14, NULL, 3, 4, 25, NULL, NULL, NULL, 0, '2026-08-14 06:03:08', NULL),
(15, '2026-08-15', 18, 3, -2, NULL, 6, NULL, 2026, '2026-08-15 13:32:11', NULL),
(16, '2026-08-15', 3, 6, -5, NULL, 7, NULL, 2026, '2026-08-15 13:42:06', NULL),
(17, '2026-08-17', 3, 4, -10, NULL, 8, NULL, 2026, '2026-08-16 05:59:09', NULL),
(18, '2026-08-20', 9, 6, 100, 8, NULL, NULL, 2026, '2026-08-20 05:18:30', NULL),
(19, '2026-08-20', 12, 6, 10, 9, NULL, NULL, 2026, '2026-08-20 05:19:44', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_ibfk_1` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
