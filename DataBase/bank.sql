-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2023 at 06:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_transactions_by_receiver` (IN `receiver_hash` VARCHAR(50))   SELECT * FROM transaction WHERE receiver = receiver_hash$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `backup_transaction`
--

CREATE TABLE `backup_transaction` (
  `tid` int(50) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hfm`
--

CREATE TABLE `hfm` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `monthly_return` int(11) NOT NULL,
  `risk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hfm`
--

INSERT INTO `hfm` (`id`, `name`, `monthly_return`, `risk`) VALUES
(1, 'FX Broker', 12, 30),
(2, 'Capital Age', 6, 14),
(3, 'XGT Broker', 7, 10),
(4, 'Bulls', 38, 92);

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `account` varchar(60) NOT NULL,
  `hfm` varchar(60) NOT NULL,
  `capital` int(11) NOT NULL,
  `profit` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `account`, `hfm`, `capital`, `profit`) VALUES
(1, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'Capital Age', 78500, 5866),
(2, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'XGT Broker', 10500, 792),
(3, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'Capital Age', 444, 0),
(4, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'Bulls', 79, 0),
(5, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'Bulls', 56, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `lid` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `paid` varchar(50) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`lid`, `account`, `amount`, `paid`, `status`) VALUES
(2, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'eGUxekIzTFBYbjdxYVNjaGo4K0t2QT09', '44', 1),
(3, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'eGUxekIzTFBYbjdxYVNjaGo4K0t2QT09', '0', 0),
(4, 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'bHoweWI1OURkeW5vcVlnd0NQeGVYUT09', '0', 0),
(5, 'WTBjRE5lK0MvUTlYY05kS0F0UUEzdz09', 'cm5NRnEvWXpqdmJ2d1lWWTh1TGlvQT09', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `tid` int(50) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`tid`, `sender`, `receiver`, `amount`, `date`) VALUES
(10, 'M0hGUkRLVE9HUzd3Y0MxZFBhMjh1UT09', 'OTB6TEhtRTJiWHdlRTI0SGtuZS9kQT09', 'QjdTK3FINmczYUJoK0VtcEltK0hrdz09', '2023-12-03'),
(11, 'M0hGUkRLVE9HUzd3Y0MxZFBhMjh1UT09', 'OTB6TEhtRTJiWHdlRTI0SGtuZS9kQT09', 'cm5NRnEvWXpqdmJ2d1lWWTh1TGlvQT09', '2023-12-03'),
(12, 'M0hGUkRLVE9HUzd3Y0MxZFBhMjh1UT09', 'OTB6TEhtRTJiWHdlRTI0SGtuZS9kQT09', 'aUFpbVEvVFlzeUJMUDVZY3VXQXVkUT09', '2023-12-03'),
(13, 'M0hGUkRLVE9HUzd3Y0MxZFBhMjh1UT09', 'OTB6TEhtRTJiWHdlRTI0SGtuZS9kQT09', 'TlBJMERNaHphUVBvYko1TnVvU05ydz09', '2023-12-03');

--
-- Triggers `transaction`
--
DELIMITER $$
CREATE TRIGGER `backup_deleted_transaction` AFTER DELETE ON `transaction` FOR EACH ROW INSERT INTO `backup_transaction` (tid, sender, receiver, amount, date)
    VALUES (OLD.tid, OLD.sender, OLD.receiver, OLD.amount, OLD.date)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `balance` varchar(50) DEFAULT 'SGFNZ2Q3TGZKenhaZHF2cnJkdEs4Zz09',
  `role` varchar(40) NOT NULL DEFAULT 'Customer',
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `account_number`, `balance`, `role`, `locked`, `created_at`) VALUES
(39, 'RjlPdkhTN3gydjlSbXpZZnZwS1VMdz09', 'bFIvd3dGUjRveFFMaWQwR3BRWmFtZz09', 'bHpOaTBkenZCVS9WMzJRYSt5aUw1QT09', 'eTl2eklwZGhLaDNHajlBWXdySFJydz09', 'Admin', 0, '2023-12-03 16:22:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backup_transaction`
--
ALTER TABLE `backup_transaction`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `hfm`
--
ALTER TABLE `hfm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`account_number`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backup_transaction`
--
ALTER TABLE `backup_transaction`
  MODIFY `tid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hfm`
--
ALTER TABLE `hfm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `tid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
