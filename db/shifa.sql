-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2026 at 05:43 PM
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
(1, 'Dentists', 'Provides diagnosis, treatment, and prevention of oral and dental diseases, including routine checkups, tooth extractions, fillings, root canal treatment, and oral health care.', 1, '2026-08-01 16:43:53', '2026-08-02 16:20:27', NULL),
(2, 'Neorology', 'Provides diagnosis and treatment of disorders affecting the brain, spinal cord, nerves, and muscles, including stroke, epilepsy, migraines, Parkinson disease, and other neurological conditions.', 1, '2026-08-01 16:45:34', '2026-08-02 15:40:12', NULL),
(3, 'Cancer Department', 'Provides diagnosis, treatment, and ongoing care for patients with cancer through chemotherapy, immunotherapy, targeted therapy, and coordinated multidisciplinary support.', 1, '2026-08-01 16:46:59', '2026-08-01 16:46:59', NULL);

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
(1, 'Doctor', 'Medical Officer', 1, NULL, NULL, NULL),
(2, 'Nurse', 'Staff Nurse', 1, NULL, NULL, NULL);

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
  `invoice_date` date NOT NULL,
  `status` int(11) DEFAULT 1 COMMENT '1=Active, 0=Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tax` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_category`
--

CREATE TABLE `lab_category` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(25) DEFAULT NULL,
  `cat_code` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_category`
--

INSERT INTO `lab_category` (`id`, `cat_name`, `cat_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'gdfg', 'ter', NULL, NULL, NULL);

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

INSERT INTO `patients` (`id`, `name`, `gender`, `age`, `blood_group`, `phone`, `email`, `address`, `emergency_contact`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, 'Azad Hossain', 1, 25, 1, '01712345678', 'azad@gmail.com', 'Feni, Bangladesh', '01812345678', '2026-08-02 04:35:22', '2026-08-02 04:35:22', NULL, 1, 1),
(2, 'Akib', 1, 25, 1, '0153321562', 'akib@gmail.com', 'kdlk, Bangladesh', '01812345678', '2026-08-02 05:24:16', '2026-08-02 05:24:16', NULL, 1, 1),
(3, 'sdfg', 0, 56, 0, '0105', 'jamal@yahoo.com', '2no Gate', '012353', '2026-08-04 06:46:36', '2026-08-04 06:46:36', NULL, NULL, NULL),
(4, 'Azad Hossain', 1, 55, 0, '01712345678', 'azad@gmail.com', 'Feni, Bangladesh', '01812345678', '2026-08-04 06:46:45', '2026-08-04 06:46:45', NULL, NULL, NULL),
(5, 'Kamal Uddin', 1, 56, NULL, '01712345679', NULL, NULL, NULL, '2026-08-04 07:01:41', '2026-08-04 07:01:41', NULL, NULL, NULL);

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
(1, 'ADM-0001', 5, 2, 2, 4, '2026-08-04', NULL, NULL, NULL, 'fdsasd', NULL, NULL, NULL, NULL);

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
(2, 1, 'Wednesday', '10:00:00', '11:59:00', 15, '', NULL),
(6, 1, 'Monday', '14:00:00', '16:00:00', 52, '', NULL),
(7, 1, 'Sunday', '14:00:00', '16:00:00', 52, 'Active', NULL),
(8, 1, 'Sunday', '17:00:00', '22:00:00', 10, 'Active', NULL),
(9, 1, 'Sunday', '00:00:00', '22:00:00', 45, '', NULL),
(10, 2, 'Friday', '15:00:00', '18:00:00', 30, '', NULL),
(11, 2, 'Thursday', '13:50:00', '15:50:00', 30, '', NULL);

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
(1, 'Morning', '07:00:00', '17:00:00', 1, NULL, NULL, NULL);

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
(5, 'Akibul Islam', 'akib34108@gmail.com', '01533198825', '6bb0e3b82a69a2bdf7139d17eeb5f79818b92a4d', 1, 1, '2026-08-01 16:24:13', '2026-08-01 16:24:13', NULL);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_category`
--
ALTER TABLE `lab_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
