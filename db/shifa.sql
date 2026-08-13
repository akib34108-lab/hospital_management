-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2026 at 06:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(4, 1, 1, 1, 25, '2026-08-16', 8, '', 2, '2026-08-12 04:54:42', NULL, '2026-08-12 00:54:42'),
(5, 2, 1, 5, 56, '2026-08-16', 8, '', 2, '2026-08-12 04:56:49', NULL, '2026-08-12 00:56:49');

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
(3, 1, '1', 0, 1, NULL, NULL, NULL),
(4, 2, '1', 1, 1, NULL, NULL, NULL),
(5, 2, '2', 0, 1, NULL, NULL, NULL);

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
(5, 'Orthopedic Surgery', 'Department specializing in the treatment of bones, joints, muscles and musculoskeletal conditions.', 0, '2026-08-13 03:36:56', '2026-08-13 03:36:56', NULL),
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
(5, 'Assistant Professor', 'Academic physician involved in patient care, medical education and clinical training.', 0, NULL, NULL, NULL),
(6, 'Professor', 'Senior academic physician responsible for advanced clinical care, teaching and research.', 0, NULL, NULL, NULL),
(7, 'Resident Medical Officer', 'Doctor responsible for regular patient monitoring, clinical assessment and treatment support.', 0, NULL, NULL, NULL),
(8, 'Specialist', 'Physician with advanced expertise and training in a specific medical specialty.', 0, NULL, NULL, NULL),
(9, 'Senior Specialist', 'Highly experienced specialist providing advanced diagnosis and treatment in a specific field.', 0, NULL, NULL, NULL),
(10, 'Chief Consultant', 'Senior-most consultant responsible for specialized patient care and clinical supervision.', 0, NULL, NULL, NULL);

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
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` int(3) DEFAULT NULL COMMENT '1=male,2=female,3=others',
  `age` int(2) DEFAULT NULL,
  `blood_group` int(6) DEFAULT NULL,
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
(1, 'Md. Rahim Uddin', 0, NULL, 0, '01810000001', 'rahim.uddin@example.com', 'Dhaka, Bangladesh', 10.00, '01810000011', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(2, 'Nusrat Jahan', 0, NULL, 0, '01810000002', 'nusrat.jahan@example.com', 'Chattogram, Bangladesh', 5.00, '01810000012', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(3, 'Tanvir Ahmed', 0, NULL, 0, '01810000003', 'tanvir.ahmed@example.com', 'Sylhet, Bangladesh', 15.00, '01810000013', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(4, 'Sadia Akter', 0, NULL, 0, '01810000004', 'sadia.akter@example.com', 'Rajshahi, Bangladesh', 10.00, '01810000014', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(5, 'Mahmud Hasan', 0, NULL, 0, '01810000005', 'mahmud.hasan@example.com', 'Khulna, Bangladesh', 0.00, '01810000015', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(6, 'Farzana Rahman', 0, NULL, 0, '01810000006', 'farzana.rahman@example.com', 'Barishal, Bangladesh', 20.00, '01810000016', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(7, 'Imran Hossain', 0, NULL, 0, '01810000007', 'imran.hossain@example.com', 'Cumilla, Bangladesh', 5.00, '01810000017', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(8, 'Samia Sultana', 0, NULL, 0, '01810000008', 'samia.sultana@example.com', 'Mymensingh, Bangladesh', 10.00, '01810000018', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(9, 'Rakibul Islam', 0, NULL, 0, '01810000009', 'rakibul.islam@example.com', 'Rangpur, Bangladesh', 15.00, '01810000019', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(10, 'Tania Akter', 0, NULL, 0, '01810000010', 'tania.akter@example.com', 'Narayanganj, Bangladesh', 5.00, '01810000020', '2026-08-13 03:23:23', '2026-08-13 03:23:23', NULL, NULL, NULL),
(11, 'Md. Rahim Uddin', 1, 25, 0, '01810000001', 'rahim.uddin@example.com', 'Dhaka, Bangladesh', 0.00, '01810000011', '2026-08-13 03:56:18', '2026-08-13 03:56:18', NULL, NULL, NULL),
(12, 'Md. Rahim Uddin', 1, 22, 0, '01810000001', 'rahim.uddin@example.com', 'Dhaka, Bangladesh', 0.00, '01810000011', '2026-08-13 04:00:10', '2026-08-13 04:00:10', NULL, NULL, NULL);

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
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
