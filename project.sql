-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2023 at 08:05 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(5) NOT NULL,
  `password` varchar(50) NOT NULL,
  `studentId` varchar(14) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `download_permissions` tinyint(1) NOT NULL,
  `member_manage_permission` tinyint(1) NOT NULL,
  `account_manage_permission` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `author_thesis`
--

CREATE TABLE `author_thesis` (
  `list_id` int(11) NOT NULL,
  `student_id` varchar(14) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `order_member` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE `keyword` (
  `keyword_id` int(11) NOT NULL,
  `keyword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `list_option`
--

CREATE TABLE `list_option` (
  `listOption_id` int(11) NOT NULL,
  `category` text NOT NULL,
  `list` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `thesis_document`
--

CREATE TABLE `thesis_document` (
  `thesis_id` int(11) NOT NULL,
  `thai_name` text NOT NULL,
  `english_name` text NOT NULL,
  `abstract` text NOT NULL,
  `printed_year` varchar(10) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `approval_year` varchar(10) NOT NULL,
  `thesis_file` text NOT NULL,
  `approval_file` text NOT NULL,
  `poster_file` text NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `prefix_chairman` varchar(50) NOT NULL,
  `name_chairman` varchar(50) NOT NULL,
  `surname_chairman` varchar(50) NOT NULL,
  `prefix_director1` varchar(50) NOT NULL,
  `name_director1` varchar(50) NOT NULL,
  `surname_director1` varchar(50) NOT NULL,
  `prefix_director2` varchar(50) NOT NULL,
  `name_director2` varchar(50) NOT NULL,
  `surname_director2` varchar(50) NOT NULL,
  `prefix_advisor` varchar(50) NOT NULL,
  `name_advisor` varchar(50) NOT NULL,
  `surname_advisor` varchar(50) NOT NULL,
  `prefix_coAdvisor` varchar(50) NOT NULL,
  `name_coAdvisor` varchar(50) NOT NULL,
  `surname_coAdvisor` varchar(50) NOT NULL,
  `thesis_status` int(1) NOT NULL,
  `approval_status` tinyint(1) NOT NULL,
  `dateTime_import` datetime NOT NULL,
  `dateTime_deleted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `author_thesis`
--
ALTER TABLE `author_thesis`
  ADD PRIMARY KEY (`list_id`);

--
-- Indexes for table `keyword`
--
ALTER TABLE `keyword`
  ADD PRIMARY KEY (`keyword_id`);

--
-- Indexes for table `list_option`
--
ALTER TABLE `list_option`
  ADD PRIMARY KEY (`listOption_id`);

--
-- Indexes for table `thesis_document`
--
ALTER TABLE `thesis_document`
  ADD PRIMARY KEY (`thesis_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `author_thesis`
--
ALTER TABLE `author_thesis`
  MODIFY `list_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyword`
--
ALTER TABLE `keyword`
  MODIFY `keyword_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `list_option`
--
ALTER TABLE `list_option`
  MODIFY `listOption_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thesis_document`
--
ALTER TABLE `thesis_document`
  MODIFY `thesis_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
