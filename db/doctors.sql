-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2026 at 08:56 AM
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
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL COMMENT '1=male,2=female,3=others',
  `specialization` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `department_id`, `designation_id`, `shift_id`, `name`, `gender`, `specialization`, `qualification`, `experience`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Dr.Sarah Ahmed', 2, 'BDS', 'FCPS', 8, '01711111111', 'sarah@hospital.com', 'Dhaka', '2026-08-02 04:43:34', '2026-08-02 04:43:34'),
(2, 2, 2, 2, 'Dr.Rahim Uddin', 1, 'Neurologist', 'MBBS,MD (Neurology)', 12, '01722222222', 'rahim@hospital.com', 'Chattogram', '2026-08-02 06:55:16', '2026-08-02 06:55:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
