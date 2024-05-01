-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 07:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `420dw3_07278_integration_project`
--
CREATE DATABASE IF NOT EXISTS `420dw3_07278_integration_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `420dw3_07278_integration_project`;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_identifier` varchar(255) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `permission_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_identifier` (`permission_identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_identifier`, `permission_name`, `permission_description`, `created_at`, `modified_at`) VALUES
(1, 'LOGIN_ALLOWED', 'Login Allowed', 'Allows users to log in', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(2, 'MANAGE_PERMISSIONS', 'Manage Permissions', 'Allows access to management of permission entities, including CRUD operations', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(3, 'MANAGE_USERGROUPS', 'Manage User Groups', 'Allows access to management of user group entities, including CRUD operations', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(4, 'MANAGE_USERS', 'Manage Users', 'Allows access to management of user entities, including CRUD operations', '2024-04-29 13:17:28', '2024-04-29 13:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE IF NOT EXISTS `usergroups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `group_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`group_id`, `group_name`, `group_description`, `created_at`, `modified_at`) VALUES
(1, 'Admins', 'Administrative users group', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(2, 'Editors', 'Editorial users group', '2024-04-29 13:17:28', '2024-04-29 13:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup_permission`
--

DROP TABLE IF EXISTS `usergroup_permission`;
CREATE TABLE IF NOT EXISTS `usergroup_permission` (
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usergroup_permission`
--

INSERT INTO `usergroup_permission` (`group_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`, `created_at`, `modified_at`) VALUES
(1, 'admin', 'admin123', 'admin@example.com', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(2, 'editor1', 'editor123', 'editor1@example.com', '2024-04-29 13:17:28', '2024-04-29 13:17:28'),
(3, 'editor2', 'editor123', 'editor2@example.com', '2024-04-29 13:17:28', '2024-04-29 13:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_usergroup`
--

DROP TABLE IF EXISTS `user_usergroup`;
CREATE TABLE IF NOT EXISTS `user_usergroup` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_usergroup`
--

INSERT INTO `user_usergroup` (`user_id`, `group_id`) VALUES
(1, 1),
(2, 2),
(3, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `usergroup_permission`
--
ALTER TABLE `usergroup_permission`
  ADD CONSTRAINT `usergroup_permission_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `usergroups` (`group_id`),
  ADD CONSTRAINT `usergroup_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`);

--
-- Constraints for table `user_usergroup`
--
ALTER TABLE `user_usergroup`
  ADD CONSTRAINT `user_usergroup_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_usergroup_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `usergroups` (`group_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
