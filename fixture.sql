-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 01, 2012 at 08:42 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kra_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`name`) VALUES
('admin');

-- --------------------------------------------------------

--
-- Table structure for table `group_user`
--

DROP TABLE IF EXISTS `group_user`;
CREATE TABLE IF NOT EXISTS `group_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `group_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_c4baf0665dbbcd06ff4b9ae5ae4dcd47b1e32cd3` (`group_id`,`user_id`),
  KEY `index_for_group_user_user_id` (`user_id`),
  KEY `index_for_group_user_group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Dumping data for table `group_user`
--

INSERT INTO `group_user` (`user_id`, `group_id`) VALUES (1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_activation_request` int(11) unsigned DEFAULT NULL,
  `lost_password_request` tinyint(3) unsigned DEFAULT NULL,
  `is_active` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sign_up_date` int(11) unsigned DEFAULT NULL,
  `last_sign_in` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`fullname`,`username`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `is_active`, `sign_up_date`, `last_sign_in`) VALUES
('Laura Agutu', 'laura', 'c201722a3f1689cbafae1be003400bafe37ef5482462114c7f505347b396b8122', 'laura.agutu@gmail.com', 'bce86b6df8d51bc07aac5c3d01db2f40', 1327948989, 0, '1', 1327948989, 0),
('Ita Tikani','ita', '3a1b91937876839371be7f519148384408f1c8985edbbaef80856c3e90e5499dc', 'kanarelo@gmail.com', NULL, NULL, NULL, '1', NULL, NULL),
('Ian Nes', 'onesmus', '0b6a5e65e6beddfaacf11907e551b0a98eb43bcc533472d1b1a22dbfd7098164a', 'mkmeonda@gmail.com', NULL, NULL, NULL, '1', NULL, NULL),
('Yuppy', 'nes', '020cabf59e68ad5c739afd002f57abc8f7b67b09a4816d36900d2dcc1e630439c', 'nesi@gmail.com', NULL, NULL, NULL, '1', NULL, NULL),
('Grumpy', 'monda', '5cd4fff22cf81d2a3e738d0f9433b8a6109cc9f4884e3529e77041de2e747a7db', 'mkemonda@yahoo.com', NULL, NULL, NULL, '1', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
