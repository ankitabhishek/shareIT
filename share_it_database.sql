-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2016 at 11:39 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `share_it`
--
CREATE DATABASE IF NOT EXISTS `share_it` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `share_it`;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `item_desc` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `entry_date` datetime NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_type`, `item_desc`, `user_id`, `entry_date`) VALUES
(1, 'mlml', 'Others', 'llml;', 1, '2013-12-24 22:48:53'),
(2, 'llm l', 'Others', 'll l', 1, '2013-12-24 22:49:03'),
(3, ';ml;ml', 'Others', 'l lm l', 1, '2013-12-24 22:49:11'),
(4, '=oll', 'Others', 'lp,,l', 1, '2013-12-24 22:49:21'),
(5, '-3or02ijf', 'Others', '2fe', 1, '2013-12-24 22:49:25'),
(6, '-oop', 'Others', 'mlm', 1, '2013-12-24 22:49:31'),
(7, '-pl', 'Others', 'kl k', 3, '2013-12-24 22:49:55'),
(8, '00k', 'Others', 'bbb', 3, '2013-12-24 22:50:00'),
(9, '89oo', 'Others', '0o', 3, '2013-12-24 22:50:06'),
(10, 'o9jm', 'Others', 'o9', 3, '2013-12-24 22:50:11'),
(11, '0ki0', 'Others', '0polk', 3, '2013-12-24 22:50:23'),
(12, 'kpn', 'Others', 'k k', 3, '2013-12-24 22:50:28'),
(13, 'ihn8o', 'Movies', 'nmo', 2, '2013-12-24 22:50:53'),
(14, '9on', 'Movies', 'nlonk', 2, '2013-12-24 22:50:58'),
(15, 'jool', 'Movies', 'olkn', 2, '2013-12-24 22:51:05'),
(16, 'konmk', 'Others', 'mnnk', 2, '2013-12-24 22:51:12'),
(17, 'k', 'Others', 'm', 2, '2013-12-24 22:51:16'),
(18, 'xc x', 'Others', 'xcvx', 2, '2013-12-24 22:56:17'),
(19, 'nn', 'Others', 'n', 4, '2013-12-25 00:58:10'),
(21, 'Updated Item', 'Movies', 'First ever updated item. :)', 3, '2014-08-20 23:56:20'),
(22, '-3or02ijf', 'Notes', 'This is the first edited item', 1, '2014-08-24 19:34:14'),
(23, 'v c', 'Movies', 'cxvv', 1, '2014-08-28 00:55:26'),
(24, 'Second Updated Item', 'Movies', 'This is the second updated item. Yo Ho!!!! :D', 3, '2014-09-14 01:02:25'),
(25, '4tryet', 'Notes', 'yeyr', 3, '2014-09-14 01:03:13'),
(26, 'New Item reCaptcha', 'Notes', 'This item was inserted to check the new reCaptcha for sharing items.', 1, '2014-09-14 04:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` char(40) NOT NULL,
  `reg_date` datetime NOT NULL,
  `pass_decrypted` char(20) NOT NULL,
  `profile_pic` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `pass`, `reg_date`, `pass_decrypted`, `profile_pic`) VALUES
(1, 'Ankit', 'Abhishek', 'ankitabhishek@example.com', 'b1b3773a05c0ed0176787a4f1574ff0075f7521e', '2013-12-24 01:01:41', 'qwerty', '54646df3dd318'),
(2, 'Mike', 'Grath', 'mike@tmg.com', 'b1b3773a05c0ed0176787a4f1574ff0075f7521e', '2013-12-24 01:08:47', 'qwerty', '5425ce39adb48'),
(3, 'Hype', 'Hello', 'php@php.com', '47425e4490d1548713efea3b8a6f5d778e4b1766', '2013-12-24 12:55:49', 'php', '55d967cac1453'),
(4, 'Ankit', 'Kumar', 'ankit@example.com', '7c286a779653e0c1d9cbc2b9c772fbea7033e452', '2013-12-24 23:58:11', 'qaz', '5425bef7e4c8a'),
(5, 'JJ', 'Thompson', 'jj@gmail.com', '7323a5431d1c31072983a6a5bf23745b655ddf59', '2014-08-15 19:30:37', 'jj', '5425bef7e4c8a'),
(6, 'jbjb', 'jbjb', 'j j j', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2014-09-14 01:51:38', '123', '5425bef7e4c8a'),
(7, 'kb', 'jvj', '78678', '011c945f30ce2cbafc452f39840f025693339c42', '2014-09-14 01:55:57', '1111', '5425bef7e4c8a');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
