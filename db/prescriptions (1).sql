-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2026 at 09:07 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
