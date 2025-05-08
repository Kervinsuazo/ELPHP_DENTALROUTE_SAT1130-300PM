-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 12:49 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.5.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `DentalRoute`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `updationDate`) VALUES
(1, 'admin', 'saysay123', '04-03-2024 11:42:05 AM');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `doctorSpecialization` varchar(255) DEFAULT NULL,
  `doctorId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `consultancyFees` int(11) DEFAULT NULL,
  `appointmentDate` varchar(255) DEFAULT NULL,
  `appointmentTime` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `userStatus` int(11) DEFAULT NULL,
  `doctorStatus` int(11) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `doctorSpecialization`, `doctorId`, `userId`, `consultancyFees`, `appointmentDate`, `appointmentTime`, `postingDate`, `userStatus`, `doctorStatus`, `updationDate`) VALUES
(1, 'Orthodontist', 2, 1, 0, '2025-03-23', '19:00', '2025-03-25 10:49:07', 0, 1, '2025-03-25 10:52:03'),
(2, 'Dentist', 31, 1, 0, '2025-03-27', '19:00', '2025-03-25 10:52:22', 1, 0, '2025-03-25 10:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `specilization`, `doctorName`, `address`, `docFees`, `contactno`, `docEmail`, `password`, `creationDate`, `updationDate`) VALUES
(1, 'Dentist', 'Dr. John Smith', 'Cebu Province', '500', 9123456789, 'johnsmith@example.com', 'hashedpassword1', '2025-03-25 10:32:41', '2025-03-25 10:46:18'),
(2, 'Orthodontist', 'Dr. Anna Brown', NULL, '700', 9234567890, 'annabrown@example.com', 'hashedpassword2', '2025-03-25 10:32:41', NULL),
(3, 'Cardiologist', 'Dr. Mark Wilson', NULL, '1000', 9345678901, 'markwilson@example.com', 'hashedpassword3', '2025-03-25 10:32:41', NULL),
(4, 'Dermatologist', 'Dr. Lisa Green', NULL, '600', 9456789012, 'lisagreen@example.com', 'hashedpassword4', '2025-03-25 10:32:41', NULL),
(5, 'Neurologist', 'Dr. Robert White', NULL, '1200', 9567890123, 'robertwhite@example.com', 'hashedpassword5', '2025-03-25 10:32:41', NULL),
(6, 'Dentist', 'Dr. John Smith', NULL, '500', 9123456789, 'johnsmith@example.com', 'hashedpassword1', '2025-03-25 10:36:25', NULL),
(7, 'Dentist', 'Dr. Alice Johnson', NULL, '550', 9234567891, 'alicejohnson@example.com', 'hashedpassword2', '2025-03-25 10:36:25', NULL),
(8, 'Dentist', 'Dr. Michael Lee', NULL, '600', 9345678902, 'michaellee@example.com', 'hashedpassword3', '2025-03-25 10:36:25', NULL),
(9, 'Dentist', 'Dr. Sarah Carter', NULL, '650', 9456789013, 'sarahcarter@example.com', 'hashedpassword4', '2025-03-25 10:36:25', NULL),
(10, 'Dentist', 'Dr. James Anderson', NULL, '700', 9567890124, 'jamesanderson@example.com', 'hashedpassword5', '2025-03-25 10:36:25', NULL),
(11, 'Orthodontist', 'Dr. Anna Brown', NULL, '700', 9678901235, 'annabrown@example.com', 'hashedpassword6', '2025-03-25 10:36:25', NULL),
(12, 'Orthodontist', 'Dr. Brian Clark', NULL, '750', 9789012346, 'brianclark@example.com', 'hashedpassword7', '2025-03-25 10:36:25', NULL),
(13, 'Orthodontist', 'Dr. Emily Davis', NULL, '800', 9890123457, 'emilydavis@example.com', 'hashedpassword8', '2025-03-25 10:36:25', NULL),
(14, 'Orthodontist', 'Dr. Kevin Harris', NULL, '850', 9901234568, 'kevinharris@example.com', 'hashedpassword9', '2025-03-25 10:36:25', NULL),
(15, 'Orthodontist', 'Dr. Olivia Martin', NULL, '900', 9112345679, 'oliviamartin@example.com', 'hashedpassword10', '2025-03-25 10:36:25', NULL),
(16, 'Pediatric Dentist', 'Dr. David Scott', NULL, '600', 9223456780, 'davidscott@example.com', 'hashedpassword11', '2025-03-25 10:36:25', NULL),
(17, 'Pediatric Dentist', 'Dr. Laura White', NULL, '650', 9334567891, 'laurawhite@example.com', 'hashedpassword12', '2025-03-25 10:36:25', NULL),
(18, 'Pediatric Dentist', 'Dr. Benjamin Taylor', NULL, '700', 9445678902, 'benjamintaylor@example.com', 'hashedpassword13', '2025-03-25 10:36:25', NULL),
(19, 'Pediatric Dentist', 'Dr. Sophia King', NULL, '750', 9556789013, 'sophiaking@example.com', 'hashedpassword14', '2025-03-25 10:36:25', NULL),
(20, 'Pediatric Dentist', 'Dr. Daniel Moore', NULL, '800', 9667890124, 'danielmoore@example.com', 'hashedpassword15', '2025-03-25 10:36:25', NULL),
(21, 'Periodontist', 'Dr. Henry Wilson', NULL, '800', 9778901235, 'henrywilson@example.com', 'hashedpassword16', '2025-03-25 10:36:25', NULL),
(22, 'Periodontist', 'Dr. Grace Hall', NULL, '850', 9889012346, 'gracehall@example.com', 'hashedpassword17', '2025-03-25 10:36:25', NULL),
(23, 'Periodontist', 'Dr. Andrew Lee', NULL, '900', 9990123457, 'andrewlee@example.com', 'hashedpassword18', '2025-03-25 10:36:25', NULL),
(24, 'Periodontist', 'Dr. Natalie Adams', NULL, '950', 9101234568, 'natalieadams@example.com', 'hashedpassword19', '2025-03-25 10:36:25', NULL),
(25, 'Periodontist', 'Dr. William Parker', NULL, '1000', 9212345679, 'williamparker@example.com', 'hashedpassword20', '2025-03-25 10:36:25', NULL),
(26, 'Endodontist', 'Dr. Charles Thomas', NULL, '900', 9323456780, 'charlesthomas@example.com', 'hashedpassword21', '2025-03-25 10:36:25', NULL),
(27, 'Endodontist', 'Dr. Madison Green', NULL, '950', 9434567891, 'madisongreen@example.com', 'hashedpassword22', '2025-03-25 10:36:25', NULL),
(28, 'Endodontist', 'Dr. Ryan Nelson', NULL, '1000', 9545678902, 'ryannelson@example.com', 'hashedpassword23', '2025-03-25 10:36:25', NULL),
(29, 'Endodontist', 'Dr. Victoria Bennett', NULL, '1050', 9656789013, 'victoriabennett@example.com', 'hashedpassword24', '2025-03-25 10:36:25', NULL),
(30, 'Endodontist', 'Dr. Jacob Mitchell', NULL, '1100', 9767890124, 'jacobmitchell@example.com', 'hashedpassword25', '2025-03-25 10:36:25', NULL),
(31, 'Dentist', 'Dokie Tae', '410 Rallos St. Poblacion Carmen, Cebu', '850', 9150764179, 'shakespearesye@gmail.com', '412386b02035be3460cc0f62799a4545', '2025-03-25 10:50:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctorslog`
--

CREATE TABLE `doctorslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctorslog`
--

INSERT INTO `doctorslog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(1, 31, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:51:17', '25-03-2025 04:21:36 PM', 1),
(2, 31, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:52:40', '25-03-2025 04:23:24 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctorspecilization`
--

CREATE TABLE `doctorspecilization` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctorspecilization`
--

INSERT INTO `doctorspecilization` (`id`, `specilization`, `creationDate`, `updationDate`) VALUES
(1, 'Dentist', '2025-03-25 10:26:08', NULL),
(2, 'Orthodontist', '2025-03-25 10:26:08', NULL),
(3, 'Pediatric Dentist', '2025-03-25 10:26:08', NULL),
(4, 'Periodontist', '2025-03-25 10:26:08', NULL),
(5, 'Endodontist', '2025-03-25 10:26:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactus`
--

CREATE TABLE `tblcontactus` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` bigint(12) DEFAULT NULL,
  `message` mediumtext,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `AdminRemark` mediumtext,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `IsRead` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontactus`
--

INSERT INTO `tblcontactus` (`id`, `fullname`, `email`, `contactno`, `message`, `PostingDate`, `AdminRemark`, `LastupdationDate`, `IsRead`) VALUES
(1, 'Cyriel Maningo', 'shakespearesye@gmail.com', 9150764179, 'asdas', '2025-03-25 10:57:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblmedicalhistory`
--

CREATE TABLE `tblmedicalhistory` (
  `ID` int(10) NOT NULL,
  `PatientID` int(10) DEFAULT NULL,
  `BloodPressure` varchar(200) DEFAULT NULL,
  `BloodSugar` varchar(200) NOT NULL,
  `Weight` varchar(100) DEFAULT NULL,
  `Temperature` varchar(200) DEFAULT NULL,
  `MedicalPres` mediumtext,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` varchar(200) DEFAULT NULL,
  `PageDescription` mediumtext,
  `Email` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `OpenningTime` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblpatient`
--

CREATE TABLE `tblpatient` (
  `ID` int(10) NOT NULL,
  `Docid` int(10) DEFAULT NULL,
  `PatientName` varchar(200) DEFAULT NULL,
  `PatientContno` bigint(10) DEFAULT NULL,
  `PatientEmail` varchar(200) DEFAULT NULL,
  `PatientGender` varchar(50) DEFAULT NULL,
  `PatientAdd` mediumtext,
  `PatientAge` int(10) DEFAULT NULL,
  `PatientMedhis` mediumtext,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(1, 1, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 09:58:15', NULL, 1),
(2, 1, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:02:11', NULL, 1),
(3, NULL, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:51:50', NULL, 0),
(4, 1, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:51:55', '25-03-2025 04:22:32 PM', 1),
(5, NULL, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:53:33', NULL, 0),
(6, 1, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:53:38', '25-03-2025 04:24:33 PM', 1),
(7, 1, 'shakespearesye@gmail.com', 0x3a3a3100000000000000000000000000, '2025-03-25 10:54:38', '25-03-2025 04:25:42 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `address` longtext,
  `city` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `regDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `address`, `city`, `gender`, `email`, `password`, `regDate`, `updationDate`) VALUES
(1, 'Cyriel Maningo', 'Carmen Cebu', 'Cebu', 'male', 'shakespearesye@gmail.com', '839c109fa3043e52703e588ce85dbe4f', '2025-03-25 09:57:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorslog`
--
ALTER TABLE `doctorslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpatient`
--
ALTER TABLE `tblpatient`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `doctorslog`
--
ALTER TABLE `doctorslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblmedicalhistory`
--
ALTER TABLE `tblmedicalhistory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblpatient`
--
ALTER TABLE `tblpatient`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
