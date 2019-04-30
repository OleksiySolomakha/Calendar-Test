-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2019 at 05:16 PM
-- Server version: 10.3.13-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php-jquery_example`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `event_start` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_end` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_title`, `event_desc`, `event_start`, `event_end`) VALUES
(92, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(5, 'Simple test', '', '2019-01-10 13:55:10', '2019-01-10 16:40:00'),
(8, 'test 2 ', '', '2019-01-18 13:00:10', '2019-01-18 16:00:00'),
(29, 'zzzz', '', '2019-01-02 10:00:10', '2019-01-02 10:40:00'),
(28, 'Alex', '', '2019-01-05 10:00:10', '2019-01-05 10:40:00'),
(91, 'Last day of month', '', '2019-01-31 10:00:00', '2019-01-31 21:59:59'),
(93, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(94, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(95, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(96, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(97, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(98, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(99, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(100, 'test day', '', '2019-01-20 10:00:00', '2019-01-20 21:59:59'),
(101, 'Just fast test', '', '2019-01-15 10:00:00', '2019-01-15 21:59:59'),
(102, 'Fun event', '', '2019-01-23 10:00:00', '2019-01-23 21:59:59'),
(103, 'Funny 2', '', '2019-01-12 10:00:00', '2019-01-12 21:59:59'),
(104, 'funny 3', '', '2019-01-02 10:00:10', '2019-01-02 10:40:00'),
(105, 'testik', '', '2019-01-10 09:00:00', '2019-01-10 10:40:00'),
(106, 'test 2', '', '2019-01-01 10:00:00', '2019-01-01 21:59:59'),
(107, 'test 2', '', '2019-01-01 10:00:00', '2019-01-01 21:59:59'),
(108, 'test 3', '', '2019-01-31 10:00:00', '2019-01-31 21:59:59'),
(109, 'test 4', '', '2019-01-03 10:00:10', '2019-01-03 10:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(80) DEFAULT NULL,
  `user_pass` varchar(60) DEFAULT NULL,
  `user_email` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_pass`, `user_email`) VALUES
(1, 'testuser', 'ae575a3bcd5e1253ce71bcb0ec3d348c11888d8afae5921', 'admin@xample.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
