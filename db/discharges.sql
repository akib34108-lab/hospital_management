-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2026 at 08:26 PM
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
-- Table structure for table `discharges`
--

CREATE TABLE `discharges` (
  `discharge_id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `admission_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED DEFAULT NULL,
  `discharge_date` datetime NOT NULL,
  `discharge_type` varchar(50) NOT NULL DEFAULT 'Normal',
  `diagnosis` text DEFAULT NULL,
  `treatment_summary` text DEFAULT NULL,
  `discharge_condition` varchar(50) DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discharges`
--

INSERT INTO `discharges` (`discharge_id`, `patient_id`, `admission_id`, `doctor_id`, `discharge_date`, `discharge_type`, `diagnosis`, `treatment_summary`, `discharge_condition`, `advice`, `follow_up_date`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 2, 3, '2026-08-16 19:06:00', 'Normal', 'weakness', 'good', 'Stable', 'rest', '2026-09-16', 'rest', '2026-08-16 23:07:46', '2026-08-16 23:17:03', NULL),
(2, 6, 3, 10, '2026-08-19 15:28:00', 'Normal', 'jygyty', 'gtygv', 'Stable', 'yugu', '2026-09-19', 'ygyug', '2026-08-19 19:29:26', NULL, NULL),
(4, 1, 1, 8, '2026-08-19 17:38:00', 'Normal', 'fgdgb', 'bhvbh', 'Stable', 'vgnc', '2026-09-19', 'vgbng', '2026-08-19 21:39:39', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discharges`
--
ALTER TABLE `discharges`
  ADD PRIMARY KEY (`discharge_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discharges`
--
ALTER TABLE `discharges`
  MODIFY `discharge_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
