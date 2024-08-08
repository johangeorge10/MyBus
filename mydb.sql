-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2023 at 08:37 PM
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
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accinfo`
--

CREATE TABLE `accinfo` (
  `uid` int(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` varchar(1) NOT NULL,
  `ssn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accinfo`
--

INSERT INTO `accinfo` (`uid`, `email`, `password`, `type`, `ssn`) VALUES
(31, 'johan@gmail.com', '7fedcb034ecf9df4be8c1ea13362053b', 'u', ''),
(38, 'king@gimal.com', 'b2086154f101464aab3328ba7e060deb', 'a', 'king'),
(39, 'johangeorge2002@gmail.com', 'd81533713a9c44c0720296d49a59c53f', 'a', '56989875');

-- --------------------------------------------------------

--
-- Table structure for table `booked`
--

CREATE TABLE `booked` (
  `busid` int(10) NOT NULL,
  `seat` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked`
--

INSERT INTO `booked` (`busid`, `seat`) VALUES
(2, 15),
(2, 14),
(2, 7),
(2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `businfo`
--

CREATE TABLE `businfo` (
  `busid` int(10) NOT NULL,
  `busname` varchar(50) NOT NULL,
  `licenseno` varchar(20) NOT NULL,
  `seatcapacity` int(10) NOT NULL,
  `status` varchar(20) NOT NULL,
  `startingpoint` varchar(30) NOT NULL,
  `destination` varchar(50) NOT NULL,
  `cost` int(10) NOT NULL,
  `arrtime` time NOT NULL,
  `deptime` time NOT NULL,
  `distance` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `businfo`
--

INSERT INTO `businfo` (`busid`, `busname`, `licenseno`, `seatcapacity`, `status`, `startingpoint`, `destination`, `cost`, `arrtime`, `deptime`, `distance`) VALUES
(2, '    johan', ' 1212', 20, 'active', 'kallooppara', 'tvla', 20, '12:00:00', '02:00:00', 90),
(3, ' adasd', ' asdas12 ', 12, 'not active', '', '', 0, '00:00:00', '00:00:00', 0),
(5, ' asdsad11', ' asd ', 11, 'not active', '', '', 0, '00:00:00', '00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `empinfo`
--

CREATE TABLE `empinfo` (
  `empid` varchar(5) NOT NULL,
  `empname` varchar(25) NOT NULL,
  `phno` int(10) NOT NULL,
  `email` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empinfo`
--

INSERT INTO `empinfo` (`empid`, `empname`, `phno`, `email`) VALUES
('E100', ' kaern1112', 90897867, 'karen@gmail.com'),
('E200', 'stfy', 26753675, 'hooi@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `ticketmanagement`
--

CREATE TABLE `ticketmanagement` (
  `slno` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `emailid` varchar(50) NOT NULL,
  `seatno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accinfo`
--
ALTER TABLE `accinfo`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `businfo`
--
ALTER TABLE `businfo`
  ADD PRIMARY KEY (`busid`);

--
-- Indexes for table `empinfo`
--
ALTER TABLE `empinfo`
  ADD PRIMARY KEY (`empid`),
  ADD UNIQUE KEY `phno` (`phno`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ticketmanagement`
--
ALTER TABLE `ticketmanagement`
  ADD PRIMARY KEY (`ticketid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accinfo`
--
ALTER TABLE `accinfo`
  MODIFY `uid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `businfo`
--
ALTER TABLE `businfo`
  MODIFY `busid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
