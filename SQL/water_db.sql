-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2024 at 11:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `water_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `name`, `username`, `password`, `datetime`) VALUES
(1, '', 'President', '21232f297a57a5a743894a0e4a801fc3', '2024-02-12 10:42:37'),
(2, '', 'Secretary', '21232f297a57a5a743894a0e4a801fc3', '2024-02-12 10:42:43'),
(3, '', 'Auditor', '21232f297a57a5a743894a0e4a801fc3', '2024-02-12 10:42:47'),
(4, '', 'Treasurer', '21232f297a57a5a743894a0e4a801fc3', '2024-02-12 10:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cubic_consume`
--

CREATE TABLE `cubic_consume` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `january` int(50) DEFAULT NULL,
  `february` int(50) DEFAULT NULL,
  `march` int(50) DEFAULT NULL,
  `april` int(50) DEFAULT NULL,
  `may` int(50) DEFAULT NULL,
  `june` int(50) DEFAULT NULL,
  `july` int(50) DEFAULT NULL,
  `august` int(50) DEFAULT NULL,
  `september` int(50) DEFAULT NULL,
  `october` int(50) DEFAULT NULL,
  `november` int(50) DEFAULT NULL,
  `december` int(50) DEFAULT NULL,
  `cubic_sum` int(50) DEFAULT NULL,
  `total` int(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cu_price`
--

CREATE TABLE `cu_price` (
  `id` int(11) NOT NULL,
  `cu_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cu_price`
--

INSERT INTO `cu_price` (`id`, `cu_price`) VALUES
(1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `event_logs` varchar(100) NOT NULL,
  `datetime` varchar(20) NOT NULL,
  `timestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

CREATE TABLE `payment_status` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `stat_january` varchar(10) DEFAULT NULL,
  `stat_february` varchar(10) DEFAULT NULL,
  `stat_march` varchar(10) DEFAULT NULL,
  `stat_april` varchar(10) DEFAULT NULL,
  `stat_may` varchar(10) DEFAULT NULL,
  `stat_june` varchar(10) DEFAULT NULL,
  `stat_july` varchar(10) DEFAULT NULL,
  `stat_august` varchar(10) DEFAULT NULL,
  `stat_september` varchar(10) DEFAULT NULL,
  `stat_october` varchar(10) DEFAULT NULL,
  `stat_november` varchar(10) DEFAULT NULL,
  `stat_december` varchar(10) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cubic_consume`
--
ALTER TABLE `cubic_consume`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cu_price`
--
ALTER TABLE `cu_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_status`
--
ALTER TABLE `payment_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cubic_consume`
--
ALTER TABLE `cubic_consume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cu_price`
--
ALTER TABLE `cu_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_status`
--
ALTER TABLE `payment_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
