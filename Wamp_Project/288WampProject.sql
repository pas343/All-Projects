-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 16, 2021 at 07:54 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `288WampProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_courses`
--

CREATE TABLE `t_courses` (
  `ID_course` int(11) NOT NULL,
  `course_code` char(5) NOT NULL,
  `course_desc` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_courses`
--

INSERT INTO `t_courses` (`ID_course`, `course_code`, `course_desc`) VALUES
(1, 'CS301', 'Computer Programming I'),
(4, 'CS302', 'Computer Programming II');

-- --------------------------------------------------------

--
-- Table structure for table `t_schedules`
--

CREATE TABLE `t_schedules` (
  `ID_schedule` int(11) NOT NULL,
  `ID_student` int(11) NOT NULL,
  `ID_course` int(11) NOT NULL,
  `sched_yr` int(11) NOT NULL,
  `sched_sem` char(2) NOT NULL,
  `grade_letter` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_schedules`
--

INSERT INTO `t_schedules` (`ID_schedule`, `ID_student`, `ID_course`, `sched_yr`, `sched_sem`, `grade_letter`) VALUES
(5, 6, 4, 2021, 'S2', 'B+'),
(6, 7, 1, 2021, 'S1', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `t_students`
--

CREATE TABLE `t_students` (
  `ID_student` int(11) NOT NULL,
  `fname` char(10) NOT NULL,
  `lname` char(15) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `email` char(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `start_dte` date NOT NULL,
  `end_dte` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_students`
--

INSERT INTO `t_students` (`ID_student`, `fname`, `lname`, `phone`, `email`, `status`, `start_dte`, `end_dte`) VALUES
(6, 'Dawud', 'Ahmad', '(234) 567-8982', 'test@gmail.com', 1, '2021-08-16', '2021-10-31'),
(7, 'Akash', 'Guru', '(123) 286-721', 'guruakash@yahoo.com', 1, '2021-08-19', '2021-11-30'),
(8, 'Gara', 'Adekul', '(348) 067-5467', 'another@gmail.com', 1, '2021-08-24', '2021-08-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_courses`
--
ALTER TABLE `t_courses`
  ADD PRIMARY KEY (`ID_course`);

--
-- Indexes for table `t_schedules`
--
ALTER TABLE `t_schedules`
  ADD PRIMARY KEY (`ID_schedule`),
  ADD KEY `ID_student` (`ID_student`),
  ADD KEY `ID_course` (`ID_course`);

--
-- Indexes for table `t_students`
--
ALTER TABLE `t_students`
  ADD PRIMARY KEY (`ID_student`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_courses`
--
ALTER TABLE `t_courses`
  MODIFY `ID_course` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_schedules`
--
ALTER TABLE `t_schedules`
  MODIFY `ID_schedule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_students`
--
ALTER TABLE `t_students`
  MODIFY `ID_student` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_schedules`
--
ALTER TABLE `t_schedules`
  ADD CONSTRAINT `t_schedules_ibfk_1` FOREIGN KEY (`ID_student`) REFERENCES `t_students` (`ID_student`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_schedules_ibfk_2` FOREIGN KEY (`ID_course`) REFERENCES `t_courses` (`ID_course`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
