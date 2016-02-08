-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2016 at 02:23 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pigeon`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE IF NOT EXISTS `conversation` (
  `conv_id` int(11) NOT NULL,
  `sent_from` varchar(50) NOT NULL,
  `sent_to` varchar(50) NOT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `reply` longtext,
  `group_chat` int(11) DEFAULT NULL,
  `notification` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`conv_id`, `sent_from`, `sent_to`, `ip`, `time`, `reply`, `group_chat`, `notification`) VALUES
(1, '9788075526', '9788059114', '127.0.0.1', '2015-12-03 14:20:26', 'Hi Testing Notification', 0, 1),
(2, '9788075526', '9788059114', '127.0.0.1', '2015-12-03 14:24:48', 'Hai sathish   how are you?', 0, 1),
(3, '9788059114', '9788075526', '127.0.0.1', '2015-12-03 14:25:51', 'I am fine Suresh what about you?', 0, 1),
(4, '9788059114', '9788075526', '127.0.0.1', '2015-12-03 14:30:48', 'Hello are you there', 0, 1),
(5, '9788059114', '9788075526', '::1', '2015-12-04 14:26:07', 'tetet', 0, 1),
(6, '9788059114', '9788075526', '::1', '2015-12-04 14:33:42', 'tetetetastasetasetasetaest', 0, 1),
(7, '9788059114', '9788075526', '::1', '2015-12-04 14:34:13', 'fsdfsdfsdf', 0, 1),
(8, '9788059114', '9788075526', '::1', '2015-12-04 15:06:28', 'sathish', 0, 1),
(9, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 15:22:44', 'I am testing Day', 0, 1),
(10, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 16:02:25', 'I am working here', 0, 1),
(11, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 16:15:05', 'Suresh R u there or Not ?', 0, 1),
(12, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 16:20:34', 'Hai', 0, 1),
(13, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 16:24:09', 'test', 0, 1),
(14, '9788059114', '9788075526', '127.0.0.1', '2015-12-08 21:01:26', 'test', 0, 1),
(15, '9788059114', '9788075526', '127.0.0.1', '2015-12-10 17:23:13', 'Hi', 0, 1),
(16, '9788059114', '9788075526', '127.0.0.1', '2015-12-10 17:51:48', 'I am testing the height', 0, 1),
(17, '9788059114', '9788075526', '127.0.0.1', '2015-12-10 17:51:53', 'yes it is working', 0, 1),
(18, '9788059114', '9788075526', '127.0.0.1', '2015-12-10 17:51:56', 'fine', 0, 1),
(19, '9788059114', '9788075526', '127.0.0.1', '2015-12-10 20:13:21', 'Doing well', 0, 1),
(20, '9788075526', '9788059114', '127.0.0.1', '2015-12-11 11:46:28', 'Hai Do you have exam today?', 0, 1),
(21, '9788059114', '9788075526', '127.0.0.1', '2015-12-11 11:46:47', 'No Suresh !', 0, 1),
(22, '9788075526', '9788059114', '127.0.0.1', '2015-12-11 19:00:02', 'Hai suresh how are you & what doing?', 0, 1),
(23, '9788059114', '9788075526', '127.0.0.1', '2015-12-11 19:00:14', 'I doing good', 0, 1),
(24, '9788059114', '9788075526', '127.0.0.1', '2015-12-11 19:00:26', 'what about youj?', 0, 1),
(25, '9788075526', '9788059114', '127.0.0.1', '2015-12-11 19:00:47', 'Fine doing something use full', 0, 1),
(26, '9788075529', '9788059114', '127.0.0.1', '2015-12-06 21:34:04', 'Hai machi', 0, 1),
(27, '9788059114', '9788075526', '::1', '2015-12-14 15:27:39', 'Hello', 0, 1),
(28, '9788059114', '9788075526', '127.0.0.1', '2015-12-14 21:23:39', 'test', 0, 1),
(29, '9788059114', '9788075526', '127.0.0.1', '2015-12-14 21:23:43', 'hai', 0, 1),
(30, '9788059114', '3', '127.0.0.1', '2015-12-16 20:16:40', 'hai', 1, 0),
(31, '9788075529', '3', '127.0.0.1', '2015-12-16 21:10:22', 'Hai tentians !!!!', 1, 0),
(32, '9788075526', '3', '127.0.0.1', '2015-12-16 21:14:22', 'Hai How are you ALL ?', 1, 0),
(33, '9788059114', '3', '127.0.0.1', '2015-12-16 21:14:44', 'fine @ suresh', 1, 0),
(34, '9788059114', '3', '127.0.0.1', '2015-12-16 21:28:05', 'hai vikaram this is sathish', 1, 0),
(35, '9788075529', '3', '127.0.0.1', '2015-12-16 21:28:23', 'Hai sathish this is vikra', 1, 0),
(36, '9788075529', '9788070792', '127.0.0.1', '2015-12-17 11:54:11', 'hai mani', 0, 1),
(37, '9788075529', '9788070792', '127.0.0.1', '2015-12-17 11:54:19', 'this is vikram', 0, 1),
(38, '9788075529', '9788070792', '127.0.0.1', '2015-12-17 11:54:25', 'how are you?', 0, 1),
(39, '9788070792', '9788075529', '127.0.0.1', '2015-12-17 12:18:03', 'I am fine vikram what about you', 0, 1),
(40, '9788075529', '9788070792', '127.0.0.1', '2015-12-17 12:19:09', '5n', 0, 1),
(41, '9788075529', '9788070792', '127.0.0.1', '2015-12-17 12:19:15', 'then what doing', 0, 1),
(42, '9788070792', '9788075529', '127.0.0.1', '2015-12-17 12:19:21', 'doing good', 0, 1),
(43, '9788059114', '9788075526', '127.0.0.1', '2015-12-21 17:01:07', 'doing testing for time functions', 0, 1),
(44, '9788059114', '9788075529', '1', '2016-01-18 18:59:12', 'audiofile.amr', 0, 1),
(45, '9788059114', '9788075529', '1', '2016-01-18 18:59:12', '9788059114_2016_01_20_18_05_33.jpg', 0, 1),
(46, '9788059114', '9788075529', '1', '2016-01-18 18:59:12', '9788059114_2016_01_20_18_05_33.jpg', 0, 1),
(47, '9788059114', '9788075529', '127.0.0.1', '2016-01-22 15:55:03', 'hai', 0, 1),
(48, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 17:32:49', 'Had your luch sathish?', 0, 1),
(49, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 17:39:32', 'R u there sathish', 0, 1),
(50, '9788059114', '9788075529', '127.0.0.1', '2016-01-25 17:41:14', 'hai Vikram', 0, 1),
(51, '9788059114', '9788075529', '127.0.0.1', '2016-01-25 17:41:21', 'I am here da', 0, 1),
(52, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 17:50:00', 'ans the question', 0, 1),
(53, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 17:51:22', 'hi', 0, 1),
(54, '9788059114', '9788075529', '127.0.0.1', '2016-01-25 17:59:30', 'hi vkram where are you now', 0, 1),
(55, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 18:00:08', 'fine sathish .... how are you and your family?????', 0, 1),
(56, '9788059114', '9788075529', '127.0.0.1', '2016-01-25 18:00:28', 'FIne and great', 0, 1),
(57, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 18:17:55', 'then', 0, 1),
(58, '9788059114', '3', '127.0.0.1', '2016-01-25 18:21:26', 'Yes sathish here', 1, 0),
(59, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 21:21:58', 'Gud evening', 0, 1),
(60, '9788075529', '9788059114', '127.0.0.1', '2016-01-25 21:23:43', 'gud eve sathish', 0, 1),
(61, '9788059114', '3', '127.0.0.1', '2016-02-01 17:17:53', 'hai', 1, 0),
(62, '9788059114', '3', '127.0.0.1', '2016-02-01 17:18:09', 'hai', 1, 0),
(63, '9788075529', '9788059114', '127.0.0.1', '2016-02-01 17:18:30', 'Testing Push Notification', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_chat`
--

CREATE TABLE IF NOT EXISTS `group_chat` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `admin` varchar(50) DEFAULT NULL,
  `group_pic` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_chat`
--

INSERT INTO `group_chat` (`group_id`, `group_name`, `admin`, `group_pic`) VALUES
(1, 'TestGroup', '9788059114', NULL),
(2, 'New Group', '9788059114', 'groupIcon.png'),
(3, 'Tent', '9788059114', 'groupIcon.png');

-- --------------------------------------------------------

--
-- Table structure for table `group_contacts`
--

CREATE TABLE IF NOT EXISTS `group_contacts` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `parti_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_contacts`
--

INSERT INTO `group_contacts` (`id`, `group_id`, `parti_id`) VALUES
(1, 2, '9788070792'),
(2, 2, '9788075526'),
(3, 3, '9788075529'),
(4, 3, '9788070792'),
(5, 2, '9788059114'),
(6, 3, '9788059114'),
(7, 3, '9788075526');

-- --------------------------------------------------------

--
-- Table structure for table `pigeon_users`
--

CREATE TABLE IF NOT EXISTS `pigeon_users` (
  `id` int(11) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail_id` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `profile_pic` text,
  `status` varchar(20) DEFAULT NULL,
  `registration_id` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pigeon_users`
--

INSERT INTO `pigeon_users` (`id`, `mobile_no`, `name`, `mail_id`, `password`, `profile_pic`, `status`, `registration_id`) VALUES
(1, '9788059114', 'sathish', 'sathish@tentsoftware.com', 'tenttent', '', '1', 'c4gqeT8Hxac:APA91bHjBPwAceW0Z08zfwGpEnvG2qGAGcPq6bFqMTrtUZ1cxH7qj_r7h9ts9kJ1bPWJMlZnDIma2uRp-KIgRiiIybMaaDBli9JdhUme6ouyS-CObvskbV-jGeJhVl6kwEY_XgSJIzIa'),
(4, '9788070792', 'Mani', 'sathish@tent.com', 'testt', '', '1', NULL),
(2, '9788075526', 'Suresh', 'suresh@gmail.com', 'testt', '', '1', NULL),
(3, '9788075529', 'Vikram', 'guru@gmail.com', 'testt', '', '1', NULL),
(6, '9797885026', 'Ruba', 'tuba@gmail.com', 'testt', '', '0', NULL),
(5, '9966554041', 'Joan', 'reen@gmail.com', 'testt', '', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE IF NOT EXISTS `user_contacts` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `contacts` longtext,
  `request_status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_contacts`
--

INSERT INTO `user_contacts` (`id`, `user_id`, `contacts`, `request_status`) VALUES
(1, '9788059114', '9788075526', 1),
(2, '9788075526', '9788059114', 1),
(3, '9788059114', '9788075529', 1),
(4, '9788075529', '9788059114', 1),
(5, '9788075529', '9788070792', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`conv_id`),
  ADD KEY `sent_from` (`sent_from`),
  ADD KEY `sent_to` (`sent_to`);

--
-- Indexes for table `group_chat`
--
ALTER TABLE `group_chat`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `admin` (`admin`);

--
-- Indexes for table `group_contacts`
--
ALTER TABLE `group_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `parti_id` (`parti_id`);

--
-- Indexes for table `pigeon_users`
--
ALTER TABLE `pigeon_users`
  ADD PRIMARY KEY (`mobile_no`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `conv_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `group_chat`
--
ALTER TABLE `group_chat`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `group_contacts`
--
ALTER TABLE `group_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pigeon_users`
--
ALTER TABLE `pigeon_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_chat`
--
ALTER TABLE `group_chat`
  ADD CONSTRAINT `group_chat_ibfk_1` FOREIGN KEY (`admin`) REFERENCES `pigeon_users` (`mobile_no`);

--
-- Constraints for table `group_contacts`
--
ALTER TABLE `group_contacts`
  ADD CONSTRAINT `group_contacts_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group_chat` (`group_id`),
  ADD CONSTRAINT `group_contacts_ibfk_2` FOREIGN KEY (`parti_id`) REFERENCES `pigeon_users` (`mobile_no`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
