-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2026 at 07:36 PM
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
  `status` int(2) NOT NULL COMMENT '0=inactive,1=active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `department_id`, `designation_id`, `shift_id`, `name`, `gender`, `specialization`, `qualification`, `experience`, `phone`, `email`, `address`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 'Dr.Sarah Ahmed', 2, 'BDS', 'FCPS', 8, '01711111111', 'sarah@hospital.com', 'Dhaka', 0, '2026-08-02 04:43:34', '2026-08-07 15:34:54', NULL),
(2, 1, 1, 1, 'Dr.Rahim Uddin', 1, 'Neurologist', 'MBBS,MD (Neurology)', 12, '01722222222', 'rahim@hospital.com', 'Chattogram', 1, '2026-08-02 06:55:16', '2026-08-07 15:36:23', NULL),
(3, 1, 1, 1, 'Dr.Bilal Abbas', 1, 'cardio', 'MRCPS', 6, '019111111112', 'bilal01@gmail.com', 'Muradpur', 0, '2026-08-07 14:55:01', '2026-08-07 15:35:01', NULL),
(4, 1, 1, 1, 'Dr.shahriar', 1, 'cardio', 'FCPS', 3, '01711111111', 'bilal01@gmail.com', 'Muradpur', 1, '2026-08-07 14:56:05', '2026-08-07 15:35:23', NULL),
(6, 2, 2, 2, 'Dr.Fyroz', 2, 'cardio', 'MBBS,MD (Neurology)', 12, '01635110533', 'fyroz@gmail.com', 'Mehedibag', 0, '2026-08-07 15:38:05', '2026-08-07 15:38:05', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
