-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2026 at 06:53 PM
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
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;
--
-- Database: `shifa`
--
CREATE DATABASE IF NOT EXISTS `shifa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shifa`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `serial_no` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `app_schedule_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 pending, 2 accepted, 3 rejected',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `serial_no`, `doctor_id`, `patient_id`, `age`, `appointment_date`, `app_schedule_id`, `note`, `status`, `updated_at`, `deleted_at`, `created_at`) VALUES
(1, 1, 1, 7, 55, '2026-08-15', 1, '', 2, '2026-08-13 04:59:25', NULL, '2026-08-13 00:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `bed_number` varchar(40) DEFAULT NULL,
  `is_occupied` int(11) NOT NULL DEFAULT 0 COMMENT '0 available 1 occupied',
  `status` int(2) DEFAULT NULL COMMENT '0=inactive, 1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `room_id`, `bed_number`, `is_occupied`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, '1', 1, 1, NULL, NULL, NULL),
(4, 2, '1', 1, 1, NULL, NULL, NULL),
(5, 2, '2', 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_collection`
--

CREATE TABLE `blood_collection` (
  `id` int(11) NOT NULL,
  `donation_id` varchar(25) DEFAULT NULL,
  `donor_id` varchar(40) DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `collection_volume` int(16) DEFAULT NULL,
  `bag_id` varchar(25) DEFAULT NULL,
  `blood_group` varchar(25) DEFAULT NULL,
  `collection_location` varchar(40) DEFAULT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_collection`
--

INSERT INTO `blood_collection` (`id`, `donation_id`, `donor_id`, `collection_date`, `collection_volume`, `bag_id`, `blood_group`, `collection_location`, `staff`, `created_at`, `update_at`, `deleted_at`) VALUES
(3, 'D001', '1', '2026-08-14', 460, 'B001', 'B+', 'CMC', 'Hasan', NULL, NULL, NULL),
(4, 'D002', '2', '2026-08-14', 360, 'B002', 'A+', 'CMC', 'Rahim', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_medicines`
--

CREATE TABLE `branch_medicines` (
  `branch_medicine_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_medicines`
--

INSERT INTO `branch_medicines` (`branch_medicine_id`, `branch_id`, `medicine_id`, `quantity`, `selling_price`, `deleted_at`) VALUES
(1, 1, 2, 118, 15.00, NULL),
(2, 1, 1, 50, 12.00, NULL),
(3, 3, 2, 35, 15.00, NULL),
(4, 3, 3, 38, 40.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT 'active=1, inactive=0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cardiology', 'Department specializing in the diagnosis and treatment of heart and cardiovascular diseases.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(2, 'Gynecology & Obstetrics', 'Department providing medical care for women, pregnancy, childbirth and reproductive health.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(3, 'Internal Medicine', 'Department focused on diagnosis, prevention and treatment of adult internal diseases.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(4, 'Pediatrics', 'Department providing healthcare services for infants, children and adolescents.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(5, 'Orthopedic Surgery', 'Department specializing in the treatment of bones, joints, muscles and musculoskeletal conditions.', 1, '2026-08-13 03:36:56', '2026-08-13 15:56:21', NULL),
(6, 'Dermatology', 'Department specializing in diagnosis and treatment of skin, hair and nail disorders.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(7, 'General Surgery', 'Department providing surgical treatment for a wide range of medical conditions.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(8, 'Ophthalmology', 'Department specializing in eye care, vision problems and eye-related diseases.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(9, 'ENT', 'Department specializing in diseases and disorders of the ear, nose and throat.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
(10, 'Psychiatry', 'Department focused on diagnosis and treatment of mental health and behavioral disorders.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation_name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(2) NOT NULL COMMENT '0=inactive, 1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation_name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Consultant', 'Experienced physician responsible for patient consultation, diagnosis and treatment planning.', 0, NULL, NULL, NULL),
(2, 'Senior Consultant', 'Senior-level physician providing specialized consultation and advanced patient care.', 0, NULL, NULL, NULL),
(3, 'Medical Officer', 'Doctor responsible for general patient assessment, treatment and routine medical care.', 0, NULL, NULL, NULL),
(4, 'Associate Consultant', 'Physician assisting in specialized consultation, diagnosis and patient management.', 0, NULL, NULL, NULL),
(5, 'Assistant Professor', 'Academic physician involved in patient care, medical education and clinical training.', 1, NULL, NULL, NULL),
(6, 'Professor', 'Senior academic physician responsible for advanced clinical care, teaching and research.', 0, NULL, NULL, NULL),
(7, 'Resident Medical Officer', 'Doctor responsible for regular patient monitoring, clinical assessment and treatment support.', 0, NULL, NULL, NULL),
(8, 'Specialist', 'Physician with advanced expertise and training in a specific medical specialty.', 0, NULL, NULL, NULL),
(9, 'Senior Specialist', 'Highly experienced specialist providing advanced diagnosis and treatment in a specific field.', 0, NULL, NULL, NULL),
(10, 'Chief Consultant', 'Senior-most consultant responsible for specialized patient care and clinical supervision.', 0, NULL, NULL, NULL);

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
(1, 5, 2, 3, '2026-08-16 19:06:00', 'Normal', 'weakness', 'good', 'Stable', 'rest', '2026-09-16', 'rest', '2026-08-16 23:07:46', '2026-08-16 23:17:03', NULL);

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
(1, 1, 5, 'INV-20260816193037', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Card', '2026-08-16 23:30:37', '2026-08-17 22:07:04', NULL),
(2, 1, 5, 'DIN-20260816195225', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Card', '2026-08-16 23:52:25', NULL, NULL),
(3, 1, 5, 'DIN-20260816195535', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 0.00, 12205.70, 'Unpaid', 'Card', '2026-08-16 23:55:35', NULL, NULL),
(4, 1, 5, 'DIN-20260817171531', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 0.00, 12205.70, 'Unpaid', 'Card', '2026-08-17 21:15:31', NULL, NULL),
(5, 1, 5, 'DIN-20260817171622', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Card', '2026-08-17 21:16:22', NULL, NULL),
(6, 1, 5, 'DIN-20260817174630', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 0.00, 12205.70, 'Unpaid', 'Card', '2026-08-17 21:46:30', NULL, NULL),
(7, 1, 5, 'DIN-20260817174653', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Card', '2026-08-17 21:46:53', NULL, NULL),
(8, 1, 5, 'DIN-20260817175849', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 0.00, 12205.70, 'Unpaid', 'Card', '2026-08-17 21:58:49', NULL, NULL),
(9, 1, 5, 'DIN-20260817175911', 1500.72, 1000.00, 6800.00, 1500.00, 1000.00, 779.98, 375.00, 12205.70, 12205.70, 0.00, 'Paid', 'Cash', '2026-08-17 21:59:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discharge_reports`
--

CREATE TABLE `discharge_reports` (
  `report_id` int(10) UNSIGNED NOT NULL,
  `discharge_id` int(10) UNSIGNED NOT NULL,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `test_name` varchar(150) NOT NULL,
  `test_date` date DEFAULT NULL,
  `result` text DEFAULT NULL,
  `normal_range` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 1, 1, 'Dr. Arif Hossain', 1, 'Cardiology', 'MBBS, FCPS (Medicine), MD (Cardiology)', 12, '01700000001', 'arif.hossain@example.com', 'Dhaka, Bangladesh', 1, '2026-08-13 03:19:30', '2026-08-13 03:50:42', NULL),
(2, 2, 2, 2, 'Dr. Nusrat Jahan', 0, 'Gynecology & Obstetrics', 'MBBS, FCPS (Gynae & Obs)', 9, '01700000002', 'nusrat.jahan@example.com', 'Chattogram, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(3, 3, 1, 1, 'Dr. Tanvir Ahmed', 0, 'Internal Medicine', 'MBBS, FCPS (Medicine)', 10, '01700000003', 'tanvir.ahmed@example.com', 'Sylhet, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(4, 1, 1, 2, 'Dr. Sadia Rahman', 2, 'Pediatrics', 'MBBS, DCH, FCPS (Pediatrics)', 8, '01700000004', 'sadia.rahman@example.com', 'Rajshahi, Bangladesh', 1, '2026-08-13 03:19:30', '2026-08-13 04:01:10', NULL),
(5, 5, 4, 3, 'Dr. Mahmudul Hasan', 0, 'Orthopedic Surgery', 'MBBS, MS (Orthopedics)', 15, '01700000005', 'mahmudul.hasan@example.com', 'Dhaka, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(6, 6, 5, 1, 'Dr. Farzana Akter', 0, 'Dermatology', 'MBBS, DDV, FCPS (Dermatology)', 7, '01700000006', 'farzana.akter@example.com', 'Khulna, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(7, 1, 1, 1, 'Dr. Rezaul Karim', 1, 'General Surgery', 'MBBS, FCPS (Surgery)', 14, '01700000007', 'rezaul.karim@example.com', 'Barishal, Bangladesh', 1, '2026-08-13 03:19:30', '2026-08-13 04:00:56', NULL),
(8, 8, 7, 3, 'Dr. Samia Sultana', 0, 'Ophthalmology', 'MBBS, DO, MS (Ophthalmology)', 11, '01700000008', 'samia.sultana@example.com', 'Mymensingh, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(9, 9, 8, 1, 'Dr. Imran Kabir', 0, 'ENT', 'MBBS, DLO, FCPS (ENT)', 13, '01700000009', 'imran.kabir@example.com', 'Cumilla, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL),
(10, 10, 9, 2, 'Dr. Tania Islam', 0, 'Psychiatry', 'MBBS, FCPS (Psychiatry)', 6, '01700000010', 'tania.islam@example.com', 'Rangpur, Bangladesh', 0, '2026-08-13 03:19:30', '2026-08-13 03:19:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` int(4) DEFAULT NULL COMMENT '1=male, 2=female',
  `phone` varchar(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `last_donation` date DEFAULT NULL,
  `donor_eligibility` int(4) DEFAULT NULL COMMENT '1=Eligible, 2=\r\nNot Eligible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`id`, `donor_name`, `age`, `gender`, `phone`, `address`, `blood_group`, `last_donation`, `donor_eligibility`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mahtab', 29, 1, '01878945612', 'Satkania, Chattogram', 'O+', '2026-07-21', 1, NULL, NULL, NULL),
(3, 'Imtiaz', 32, 1, '01842194963', 'GPO circle, new market', 'A+', '2026-07-22', 1, NULL, NULL, NULL),
(4, 'Ashfa', 19, 0, '01412365485', 'Gachbaria', 'B+', '2026-02-17', 1, NULL, NULL, NULL),
(5, 'Rahim', 22, 1, '01712345678', 'Chittagong', 'A+', NULL, 1, NULL, NULL, NULL),
(6, 'Karim', 25, 1, '01812345679', 'Dhaka', 'O+', NULL, 2, NULL, NULL, NULL),
(7, 'Nusrat', 21, 2, '01912345680', 'Comilla', 'B+', NULL, 1, NULL, NULL, NULL),
(8, 'Jannat', 24, 2, '01612345681', 'Feni', 'AB+', NULL, 2, NULL, NULL, NULL),
(9, 'Sakib', 20, 1, '01512345682', 'Noakhali', 'O-', NULL, 1, NULL, NULL, NULL),
(10, 'Mim', 23, 2, '01412345683', 'Gachbaria', 'A-', NULL, 2, NULL, NULL, NULL),
(11, 'Tanvir', 27, 1, '01312345684', 'Chandpur', 'B-', NULL, 1, NULL, NULL, NULL),
(12, 'Sumaiya', 26, 2, '01212345685', 'Cox\'s Bazar', 'AB-', NULL, 2, NULL, NULL, NULL),
(1, 'Mahtab', 29, 1, '01878945612', 'Satkania, Chattogram', 'O+', '2026-07-21', 1, NULL, NULL, NULL),
(3, 'Imtiaz', 32, 1, '01842194963', 'GPO circle, new market', 'A+', '2026-07-22', 1, NULL, NULL, NULL),
(4, 'Ashfa', 19, 0, '01412365485', 'Gachbaria', 'B+', '2026-02-17', 1, NULL, NULL, NULL),
(5, 'Rahim', 22, 1, '01712345678', 'Chittagong', 'A+', NULL, 1, NULL, NULL, NULL),
(6, 'Karim', 25, 1, '01812345679', 'Dhaka', 'O+', NULL, 2, NULL, NULL, NULL),
(7, 'Nusrat', 21, 2, '01912345680', 'Comilla', 'B+', NULL, 1, NULL, NULL, NULL),
(8, 'Jannat', 24, 2, '01612345681', 'Feni', 'AB+', NULL, 2, NULL, NULL, NULL),
(9, 'Sakib', 20, 1, '01512345682', 'Noakhali', 'O-', NULL, 1, NULL, NULL, NULL),
(10, 'Mim', 23, 2, '01412345683', 'Gachbaria', 'A-', NULL, 2, NULL, NULL, NULL),
(11, 'Tanvir', 27, 1, '01312345684', 'Chandpur', 'B-', NULL, 1, NULL, NULL, NULL),
(12, 'Sumaiya', 26, 2, '01212345685', 'Cox\'s Bazar', 'AB-', NULL, 2, NULL, NULL, NULL),
(0, 'Samina', 28, 1, '01878945623', 'Agrabad', 'AB+', '2026-02-04', 1, NULL, NULL, NULL),
(1, 'Mahtab', 29, 1, '01878945612', 'Satkania, Chattogram', '7', '2026-07-21', 1, NULL, NULL, NULL),
(3, 'Imtiaz', 32, 1, '01842194963', 'GPO circle, new market', '4', '2026-07-22', 1, NULL, NULL, NULL),
(4, 'Ashfa', 19, 0, '01412365485', 'Gachbaria', '3', '2026-02-17', 1, NULL, NULL, NULL),
(5, 'Rahim', 22, 1, '01712345678', 'Chittagong', '3', '2026-07-06', 1, NULL, NULL, NULL),
(6, 'Karim', 25, 1, '01812345679', 'Dhaka', '7', '2026-08-02', 1, NULL, NULL, NULL),
(7, 'Nusrat', 21, 0, '01912345680', 'Comilla', '5', '2026-08-07', 1, NULL, NULL, NULL),
(8, 'Jannat', 24, 0, '01612345681', 'Feni', '3', '2026-08-07', 1, NULL, NULL, NULL),
(9, 'Sakib', 20, 1, '01512345682', 'Noakhali', '8', '2026-04-15', 1, NULL, NULL, NULL),
(10, 'Mim', 23, 0, '01412345683', 'Gachbaria', '2', '2026-08-01', 1, NULL, NULL, NULL),
(11, 'Tanvir', 27, 1, '01312345684', 'Chandpur', '0', NULL, 1, NULL, NULL, NULL),
(12, 'Sumaiya', 26, 2, '01212345685', 'Cox\'s Bazar', '0', NULL, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustments`
--

CREATE TABLE `inventory_adjustments` (
  `adjustment_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `adjustment_type` enum('Increase','Decrease') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `adjusted_by` int(11) DEFAULT NULL,
  `adjustment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`category_id`, `category_name`, `description`, `status`, `deleted_at`, `created_at`) VALUES
(1, 'Gloves', 'Medical gloves', 'Active', NULL, '2026-08-16 14:58:10'),
(2, 'Surgical Items', 'Surgical and operation-related items', 'Active', NULL, '2026-08-16 14:58:10'),
(3, 'Equipment', 'Hospital equipment', 'Active', NULL, '2026-08-16 14:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_issues`
--

CREATE TABLE `inventory_issues` (
  `issue_id` int(11) NOT NULL,
  `issue_no` varchar(50) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `issue_date` datetime NOT NULL DEFAULT current_timestamp(),
  `issued_by` int(11) DEFAULT NULL,
  `status` enum('Issued','Pending','Cancelled') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_issue_items`
--

CREATE TABLE `inventory_issue_items` (
  `issue_item_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `unit` varchar(30) NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 0,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `expiry_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_locations`
--

CREATE TABLE `inventory_locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `location_type` enum('Central Store','Department Store','Emergency Store') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_locations`
--

INSERT INTO `inventory_locations` (`location_id`, `location_name`, `location_type`, `address`, `status`, `deleted_at`, `created_at`) VALUES
(1, 'Main Store', 'Central Store', NULL, 'Active', NULL, '2026-08-16 14:58:10'),
(2, 'OT Store', 'Department Store', NULL, 'Active', NULL, '2026-08-16 14:58:10'),
(3, 'Emergency Store', 'Emergency Store', NULL, 'Active', NULL, '2026-08-16 14:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_purchases`
--

CREATE TABLE `inventory_purchases` (
  `purchase_id` int(11) NOT NULL,
  `purchase_no` varchar(50) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `purchase_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('Paid','Pending','Partial') NOT NULL DEFAULT 'Pending',
  `status` enum('Received','Pending','Cancelled') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_purchase_items`
--

CREATE TABLE `inventory_purchase_items` (
  `purchase_item_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stock`
--

CREATE TABLE `inventory_stock` (
  `stock_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_suppliers`
--

CREATE TABLE `inventory_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 2, 2000.00, 100.00, 95.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(5, 1, 6500.00, 1300.00, 0.00, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(6, 2, 1700.00, 85.00, 242.25, 0.00, 0.00, 'Due', NULL, '2026-08-09', 1),
(7, 3, 7800.00, 234.00, 226.98, 0.00, 0.00, 'Due', NULL, '2026-08-12', 1),
(8, 2, 6500.00, 585.00, 1478.75, 0.00, 0.00, 'Due', NULL, '2026-08-12', 1);

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
(15, 4, 'Electrocardiogram(ECG)', 2000.00, 5.00, 0.00, NULL),
(16, 8, 'Vitamin D test', 6500.00, 9.00, 25.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lab_category`
--

CREATE TABLE `lab_category` (
  `id` int(11) NOT NULL,
  `test_name` varchar(255) DEFAULT NULL,
  `price` varchar(40) DEFAULT NULL,
  `test_accessor` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_category`
--

INSERT INTO `lab_category` (`id`, `test_name`, `price`, `test_accessor`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CBC', '1700', 'Kamal', NULL, NULL, NULL),
(2, 'Vitamin D test', '6500', 'Kamal', NULL, NULL, NULL),
(3, 'Colonscopy', '7800', 'Jamal', NULL, NULL, NULL),
(4, 'Endoscopy', '3300', 'Rahim', NULL, NULL, NULL),
(5, 'Electrocardiogram(ECG)', '2000', 'Rafiq', NULL, NULL, NULL);

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
(3, 'Tafnil', 'paracetamol', 'Painkiller', 'Tablet', '1000', 'Strip', 'painkiller', 'Aristopharma', 40.00, 25, '2026-08-16', 'Active', NULL, '2026-08-16 15:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL COMMENT '1=male,2=female,3=others',
  `age` varchar(10) DEFAULT NULL,
  `blood_group` varchar(6) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `gender`, `age`, `blood_group`, `phone`, `email`, `address`, `discount_percent`, `emergency_contact`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, 'hasan', 'Male', '20', 'O+', '01887499250', 'hasa222@gmail.com', 'khulshi', 0.00, '01887499250', '2026-08-13 04:33:07', '2026-08-13 04:33:07', NULL, NULL, NULL),
(2, 'shakira', 'Female', '22', 'A+', '01774489205', 'shakira222@gmail.com', 'Feni', 0.00, '01774489205', '2026-08-13 04:35:34', '2026-08-13 04:35:34', NULL, NULL, NULL),
(3, 'akib', 'Male', '25', 'B+', '0188929247', 'akib34@gmail.com', 'kotually', 0.00, '0188929247', '2026-08-13 04:36:59', '2026-08-13 04:36:59', NULL, NULL, NULL),
(5, 'Aftab', 'Male', '27', 'AB+', '0155399622', 'aftab653@yahoo.com', 'sylhet', 0.00, '0155399622', '2026-08-13 04:40:10', '2026-08-13 04:40:10', NULL, NULL, NULL),
(6, 'shakib', '1', '27', NULL, '01887925099', NULL, NULL, 0.00, NULL, '2026-08-13 04:46:22', '2026-08-13 04:46:22', NULL, NULL, NULL),
(7, 'arif', '1', '55', NULL, '0155069856', NULL, NULL, 0.00, NULL, '2026-08-13 04:59:25', '2026-08-13 04:59:25', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_admissions`
--

CREATE TABLE `patient_admissions` (
  `id` int(11) NOT NULL,
  `admission_no` varchar(30) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `bed_id` int(11) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_time` time DEFAULT NULL,
  `discharge_date` date DEFAULT NULL,
  `discharge_time` time DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0=inactive, 1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_admissions`
--

INSERT INTO `patient_admissions` (`id`, `admission_no`, `patient_id`, `doctor_id`, `room_id`, `bed_id`, `admission_date`, `admission_time`, `discharge_date`, `discharge_time`, `reason`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ADM-0001', 1, 8, 1, 3, '2026-08-16', NULL, NULL, NULL, ' health problem', NULL, NULL, NULL, NULL),
(2, 'ADM-0002', 5, 3, 2, 5, '2026-08-15', NULL, '2026-08-16', '19:06:00', 'mental problem', NULL, NULL, NULL, NULL),
(3, 'ADM-0003', 6, 10, 1, 3, '2026-08-18', NULL, NULL, NULL, 'health issue', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount`, `payment_method`, `payment_date`, `transaction_id`, `deleted_at`) VALUES
(1, 2, 2000.00, 'bKash', '2026-08-08', '123', NULL),
(2, 2, 426.60, 'Cash', '2026-08-08', '456', NULL),
(3, 7, 7792.98, 'Nagad', '2026-08-12', 'NG26081213706', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_branches`
--

CREATE TABLE `pharmacy_branches` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_code` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_branches`
--

INSERT INTO `pharmacy_branches` (`branch_id`, `branch_name`, `branch_code`, `location`, `address`, `phone`, `status`, `deleted_at`, `created_at`) VALUES
(1, 'SHIFA Agrabad Branch', 'Shifa1-Agr', 'Agrabad,Chittagong', 'Agrabad,Badamtoli', '019111111111', 'Active', NULL, '2026-08-15 15:21:37'),
(2, 'SHIFA Khulshi Pharmacy', 'Shifa2-kh', 'khulshi,Chattogram', 'South Khulshi', '019111111112', 'Inactive', NULL, '2026-08-16 15:11:42'),
(3, 'SHIFA Anowara Pharmacy', 'Shifa3-Ano', 'Anowara,Chittagong', 'Anowara,Chittagong', '019111111113', 'Active', NULL, '2026-08-16 15:13:47');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_sales`
--

CREATE TABLE `pharmacy_sales` (
  `sale_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `customer_name` varchar(150) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `sale_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` enum('Cash','Card','Mobile Banking') DEFAULT 'Cash',
  `status` enum('Completed','Pending','Cancelled') DEFAULT 'Completed',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_sales`
--

INSERT INTO `pharmacy_sales` (`sale_id`, `invoice_no`, `branch_id`, `customer_name`, `customer_phone`, `sale_date`, `total_amount`, `payment_method`, `status`, `deleted_at`) VALUES
(3, 'INV-20260815184932', 1, 'jamal', '01877777777', '2026-08-15 22:49:32', 30.00, 'Card', 'Completed', NULL),
(5, 'INV-20260816172152', 3, 'jobbar', '01877777778', '2026-08-16 21:21:52', 400.00, 'Mobile Banking', 'Completed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_sale_items`
--

CREATE TABLE `pharmacy_sale_items` (
  `sale_item_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacy_sale_items`
--

INSERT INTO `pharmacy_sale_items` (`sale_item_id`, `sale_id`, `medicine_id`, `quantity`, `unit_price`, `subtotal`, `deleted_at`) VALUES
(1, 3, 2, 2, 15.00, 30.00, NULL),
(2, 5, 3, 10, 40.00, 400.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `Obj_date` date NOT NULL,
  `Cc` text DEFAULT NULL,
  `Dx` text DEFAULT NULL,
  `Inv` text DEFAULT NULL,
  `medicines` longtext DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `Weight` varchar(20) DEFAULT NULL,
  `Next_visit_day` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `patient_id`, `doctor_id`, `Obj_date`, `Cc`, `Dx`, `Inv`, `medicines`, `additional_notes`, `Weight`, `Next_visit_day`) VALUES
(1, 1, 1, '2026-08-04', 'Jor, Matha betha, Kashi', 'Viral Fever', 'CBC, Urine R/E', '[{\"medicationName\":\"Napa Extra 500mg\",\"dosage\":\"1+0+1\",\"frequency\":\"7 days\",\"duration\":\"After Meal\",\"instructions\":\"Pani diye khaben\"},{\"medicationName\":\"Monas 10mg\",\"dosage\":\"0+0+1\",\"frequency\":\"5 days\",\"duration\":\"At Night\",\"instructions\":\"Khawar por\"},{\"medicationName\":\"Fexo 120mg\",\"dosage\":\"1+0+0\",\"frequency\":\"5 days\",\"duration\":\"Before Meal\",\"instructions\":\"\"}]', 'Besi kore pani khan, rest nen. Gorom khabar khaben na.', '65', 15),
(4, 2, 2, '2026-08-08', 'not healed yet', 'flu', 'serious', NULL, NULL, NULL, 30),
(5, 3, 4, '2026-08-08', 'headache', 'eye issue', 'cornia test', NULL, NULL, NULL, 20);

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicines`
--

CREATE TABLE `prescription_medicines` (
  `id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `frequency` varchar(100) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `instructions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_medicines`
--

INSERT INTO `prescription_medicines` (`id`, `prescription_id`, `medicine_name`, `dosage`, `frequency`, `duration`, `instructions`) VALUES
(2, 4, 'Frenxit', '0+0+1', 'after meal', '30 days', 'regular'),
(3, 4, 'Adovas Syrup', '1+1+1', 'after meal', '10 days', 'must'),
(4, 1, 'napa', '0+0+1', 'after meal', '5 days', 'regular'),
(5, 5, 'napa', '1+0+1', 'after meal', '5 days', 'regular');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `access` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `access`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'Full access to all system features', 1, '2026-07-25 05:51:10', '2026-07-25 05:51:10', NULL),
(2, 'Admin', 'Manage users, settings, and reports', 1, '2026-07-25 05:51:10', '2026-07-25 05:51:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(40) DEFAULT NULL,
  `room_type` varchar(40) DEFAULT NULL,
  `floor` varchar(40) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `charge_per_day` double(10,2) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0=inactive, 1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `floor`, `capacity`, `charge_per_day`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '101', 'Cabin', '1st', 2, 1500.72, 1, NULL, NULL, NULL),
(2, '102', 'VIP', '1st', 1, 1500.72, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `appointment_qty` int(11) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `doctor_id`, `day_of_week`, `start_time`, `end_time`, `appointment_qty`, `status`, `deleted_at`) VALUES
(1, 1, 'Saturday', '09:00:00', '13:00:00', 20, 'Active', NULL),
(2, 2, 'Sunday', '10:00:00', '14:00:00', 16, 'Active', NULL),
(3, 3, 'Monday', '09:00:00', '12:00:00', 15, 'Active', NULL),
(4, 4, 'Tuesday', '15:00:00', '19:00:00', 20, 'Active', NULL),
(5, 5, 'Wednesday', '10:00:00', '14:00:00', 16, 'Active', NULL),
(6, 6, 'Thursday', '16:00:00', '20:00:00', 20, 'Active', NULL),
(7, 7, 'Saturday', '14:00:00', '18:00:00', 20, 'Active', NULL),
(8, 8, 'Sunday', '09:00:00', '13:00:00', 20, 'Active', NULL),
(9, 9, 'Monday', '15:00:00', '19:00:00', 20, 'Active', NULL),
(10, 10, 'Tuesday', '09:00:00', '13:00:00', 20, 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `id` int(11) NOT NULL,
  `shift_name` varchar(100) DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0=inactive,1=active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`id`, `shift_name`, `shift_start`, `shift_end`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Morning Shift', '08:00:00', '12:00:00', 0, NULL, NULL, NULL),
(2, 'Day Shift', '10:00:00', '14:00:00', 0, NULL, NULL, NULL),
(3, 'Evening Shift', '14:00:00', '18:00:00', 0, NULL, NULL, NULL),
(4, 'Late Evening Shift', '16:00:00', '20:00:00', 0, NULL, NULL, NULL),
(5, 'Night Shift', '20:00:00', '00:00:00', 0, NULL, NULL, NULL),
(6, 'Extended Morning Shift', '07:00:00', '13:00:00', 0, NULL, NULL, NULL),
(7, 'Extended Day Shift', '09:00:00', '15:00:00', 0, NULL, NULL, NULL),
(8, 'Extended Evening Shift', '13:00:00', '19:00:00', 0, NULL, NULL, NULL),
(9, 'Late Night Shift', '22:00:00', '02:00:00', 0, NULL, NULL, NULL),
(10, 'Weekend Shift', '09:00:00', '17:00:00', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'kamal', 'kamal@yahoo.com', '0105', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-07-25 06:19:50', '2026-07-25 06:19:50', NULL),
(3, 'akib', 'akib@yahoo.com', '123456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, '2026-07-29 17:19:29', '2026-07-29 17:19:29', NULL),
(4, 'Faruq', 'faruq@yahoo.com', '015', '232758d2b2310c93c7a3fb207558f22b331793e4', 1, 1, '2026-07-29 18:24:59', '2026-07-29 18:24:59', NULL),
(5, 'Akibul Islam', 'akib34108@gmail.com', '01533198825', '6bb0e3b82a69a2bdf7139d17eeb5f79818b92a4d', 1, 1, '2026-08-01 16:24:13', '2026-08-01 16:24:13', NULL),
(1, 'kamal', 'kamal@yahoo.com', '0105', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-07-25 06:19:50', '2026-07-25 06:19:50', NULL),
(3, 'akib', 'akib@yahoo.com', '123456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, '2026-07-29 17:19:29', '2026-07-29 17:19:29', NULL),
(4, 'Faruq', 'faruq@yahoo.com', '015', '232758d2b2310c93c7a3fb207558f22b331793e4', 1, 1, '2026-07-29 18:24:59', '2026-07-29 18:24:59', NULL),
(5, 'Akibul Islam', 'akib34108@gmail.com', '01533198825', '6bb0e3b82a69a2bdf7139d17eeb5f79818b92a4d', 1, 1, '2026-08-01 16:24:13', '2026-08-01 16:24:13', NULL),
(0, 'Akib', 'akib3410@gmail.com', '01533198825', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-08-13 03:04:46', '2026-08-13 03:04:46', NULL),
(0, 'Fyroz anika', 'fyrozanika001@gmail.com', '01858053316', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 1, '2026-08-13 15:54:38', '2026-08-13 15:54:38', NULL),
(1, 'kamal', 'kamal@yahoo.com', '0105', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-07-25 06:19:50', '2026-07-25 06:19:50', NULL),
(3, 'akib', 'akib@yahoo.com', '123456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, '2026-07-29 17:19:29', '2026-07-29 17:19:29', NULL),
(4, 'Faruq', 'faruq@yahoo.com', '015', '232758d2b2310c93c7a3fb207558f22b331793e4', 1, 1, '2026-07-29 18:24:59', '2026-07-29 18:24:59', NULL),
(5, 'Akibul Islam', 'akib34108@gmail.com', '01533198825', '6bb0e3b82a69a2bdf7139d17eeb5f79818b92a4d', 1, 1, '2026-08-01 16:24:13', '2026-08-01 16:24:13', NULL),
(1, 'kamal', 'kamal@yahoo.com', '0105', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-07-25 06:19:50', '2026-07-25 06:19:50', NULL),
(3, 'akib', 'akib@yahoo.com', '123456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, '2026-07-29 17:19:29', '2026-07-29 17:19:29', NULL),
(4, 'Faruq', 'faruq@yahoo.com', '015', '232758d2b2310c93c7a3fb207558f22b331793e4', 1, 1, '2026-07-29 18:24:59', '2026-07-29 18:24:59', NULL),
(5, 'Akibul Islam', 'akib34108@gmail.com', '01533198825', '6bb0e3b82a69a2bdf7139d17eeb5f79818b92a4d', 1, 1, '2026-08-01 16:24:13', '2026-08-01 16:24:13', NULL),
(0, 'Akib', 'akib3410@gmail.com', '01533198825', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-08-13 03:04:46', '2026-08-13 03:04:46', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `blood_collection`
--
ALTER TABLE `blood_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_medicines`
--
ALTER TABLE `branch_medicines`
  ADD PRIMARY KEY (`branch_medicine_id`),
  ADD UNIQUE KEY `branch_id` (`branch_id`,`medicine_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discharges`
--
ALTER TABLE `discharges`
  ADD PRIMARY KEY (`discharge_id`),
  ADD KEY `idx_patient_id` (`patient_id`),
  ADD KEY `idx_admission_id` (`admission_id`),
  ADD KEY `idx_doctor_id` (`doctor_id`),
  ADD KEY `idx_discharge_date` (`discharge_date`);

--
-- Indexes for table `discharge_invoices`
--
ALTER TABLE `discharge_invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `uk_invoice_no` (`invoice_no`),
  ADD KEY `idx_discharge_id` (`discharge_id`),
  ADD KEY `idx_patient_id` (`patient_id`);

--
-- Indexes for table `discharge_reports`
--
ALTER TABLE `discharge_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `idx_discharge_id` (`discharge_id`),
  ADD KEY `idx_patient_id` (`patient_id`),
  ADD KEY `idx_test_date` (`test_date`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `inventory_adjustments`
--
ALTER TABLE `inventory_adjustments`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `fk_adjustment_item` (`item_id`),
  ADD KEY `fk_adjustment_location` (`location_id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `inventory_issues`
--
ALTER TABLE `inventory_issues`
  ADD PRIMARY KEY (`issue_id`),
  ADD UNIQUE KEY `issue_no` (`issue_no`),
  ADD KEY `fk_issue_location` (`location_id`);

--
-- Indexes for table `inventory_issue_items`
--
ALTER TABLE `inventory_issue_items`
  ADD PRIMARY KEY (`issue_item_id`),
  ADD KEY `fk_issue_items_issue` (`issue_id`),
  ADD KEY `fk_issue_items_item` (`item_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_code` (`item_code`),
  ADD KEY `fk_items_category` (`category_id`);

--
-- Indexes for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `inventory_purchases`
--
ALTER TABLE `inventory_purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD UNIQUE KEY `purchase_no` (`purchase_no`),
  ADD KEY `fk_purchase_supplier` (`supplier_id`),
  ADD KEY `fk_purchase_location` (`location_id`);

--
-- Indexes for table `inventory_purchase_items`
--
ALTER TABLE `inventory_purchase_items`
  ADD PRIMARY KEY (`purchase_item_id`),
  ADD KEY `fk_purchase_items_purchase` (`purchase_id`),
  ADD KEY `fk_purchase_items_item` (`item_id`);

--
-- Indexes for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD UNIQUE KEY `uq_stock_item_location` (`item_id`,`location_id`),
  ADD KEY `fk_stock_location` (`location_id`);

--
-- Indexes for table `inventory_suppliers`
--
ALTER TABLE `inventory_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_category`
--
ALTER TABLE `lab_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pharmacy_branches`
--
ALTER TABLE `pharmacy_branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `pharmacy_sales`
--
ALTER TABLE `pharmacy_sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `pharmacy_sale_items`
--
ALTER TABLE `pharmacy_sale_items`
  ADD PRIMARY KEY (`sale_item_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `prescription_medicines`
--
ALTER TABLE `prescription_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_id` (`prescription_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `branch_medicines`
--
ALTER TABLE `branch_medicines`
  MODIFY `branch_medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `discharges`
--
ALTER TABLE `discharges`
  MODIFY `discharge_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discharge_invoices`
--
ALTER TABLE `discharge_invoices`
  MODIFY `invoice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `discharge_reports`
--
ALTER TABLE `discharge_reports`
  MODIFY `report_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_adjustments`
--
ALTER TABLE `inventory_adjustments`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_issues`
--
ALTER TABLE `inventory_issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_issue_items`
--
ALTER TABLE `inventory_issue_items`
  MODIFY `issue_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_purchases`
--
ALTER TABLE `inventory_purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_purchase_items`
--
ALTER TABLE `inventory_purchase_items`
  MODIFY `purchase_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_suppliers`
--
ALTER TABLE `inventory_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lab_category`
--
ALTER TABLE `lab_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pharmacy_branches`
--
ALTER TABLE `pharmacy_branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pharmacy_sales`
--
ALTER TABLE `pharmacy_sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pharmacy_sale_items`
--
ALTER TABLE `pharmacy_sale_items`
  MODIFY `sale_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prescription_medicines`
--
ALTER TABLE `prescription_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch_medicines`
--
ALTER TABLE `branch_medicines`
  ADD CONSTRAINT `branch_medicines_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `pharmacy_branches` (`branch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_medicines_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_adjustments`
--
ALTER TABLE `inventory_adjustments`
  ADD CONSTRAINT `fk_adjustment_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adjustment_location` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`location_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_issues`
--
ALTER TABLE `inventory_issues`
  ADD CONSTRAINT `fk_issue_location` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`location_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_issue_items`
--
ALTER TABLE `inventory_issue_items`
  ADD CONSTRAINT `fk_issue_items_issue` FOREIGN KEY (`issue_id`) REFERENCES `inventory_issues` (`issue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_issue_items_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_purchases`
--
ALTER TABLE `inventory_purchases`
  ADD CONSTRAINT `fk_purchase_location` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`location_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `inventory_suppliers` (`supplier_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inventory_purchase_items`
--
ALTER TABLE `inventory_purchase_items`
  ADD CONSTRAINT `fk_purchase_items_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_items_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `inventory_purchases` (`purchase_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD CONSTRAINT `fk_stock_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_location` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`location_id`) ON UPDATE CASCADE;

--
-- Constraints for table `pharmacy_sales`
--
ALTER TABLE `pharmacy_sales`
  ADD CONSTRAINT `pharmacy_sales_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `pharmacy_branches` (`branch_id`);

--
-- Constraints for table `pharmacy_sale_items`
--
ALTER TABLE `pharmacy_sale_items`
  ADD CONSTRAINT `pharmacy_sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `pharmacy_sales` (`sale_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pharmacy_sale_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);
--
-- Database: `shifa_inventory`
--
CREATE DATABASE IF NOT EXISTS `shifa_inventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shifa_inventory`;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
