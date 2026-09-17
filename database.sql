-- phpMyAdmin SQL Dump
-- Kuro VIP Panel with FamGateway Automated UPI Payment Gateway
-- Database Schema

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `credit`
--

CREATE TABLE IF NOT EXISTS `credit` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `credit` (`id`, `name`) VALUES
(1, '') ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `Feature`
--

CREATE TABLE IF NOT EXISTS `Feature` (
  `id` int(11) NOT NULL,
  `ESP` varchar(3) NOT NULL,
  `Item` varchar(3) NOT NULL,
  `SilentAim` varchar(3) NOT NULL,
  `AIM` varchar(3) NOT NULL,
  `BulletTrack` varchar(3) NOT NULL,
  `Memory` varchar(3) NOT NULL,
  `Floating` varchar(3) NOT NULL,
  `Setting` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `Feature` (`id`, `ESP`, `Item`, `SilentAim`, `AIM`, `BulletTrack`, `Memory`, `Floating`, `Setting`) VALUES
(1, 'off', 'off', 'off', 'off', 'off', 'off', 'on', 'off') ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id_history` int(11) NOT NULL AUTO_INCREMENT,
  `keys_id` varchar(33) DEFAULT NULL,
  `user_do` varchar(33) DEFAULT NULL,
  `info` mediumtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_history`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `keys_code`
--

CREATE TABLE IF NOT EXISTS `keys_code` (
  `id_keys` int(11) NOT NULL AUTO_INCREMENT,
  `game` varchar(32) NOT NULL,
  `user_key` varchar(32) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `max_devices` int(11) DEFAULT NULL,
  `devices` mediumtext DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `registrator` varchar(32) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_keys`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lib`
--

CREATE TABLE IF NOT EXISTS `lib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `game` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `modname`
--

CREATE TABLE IF NOT EXISTS `modname` (
  `id` int(11) NOT NULL,
  `modname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `modname` (`id`, `modname`) VALUES
(1, 'ARYANISPE') ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `onoff`
--

CREATE TABLE IF NOT EXISTS `onoff` (
  `id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `onoff` (`id`, `status`) VALUES
(1, 'on') ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(66) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `utr` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `referral_code`
--

CREATE TABLE IF NOT EXISTS `referral_code` (
  `id_reff` int(11) NOT NULL AUTO_INCREMENT,
  `referral` varchar(32) NOT NULL,
  `created_by` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `used_by` varchar(32) DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_reff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_users` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(66) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT 'User Admin',
  `level` int(11) NOT NULL DEFAULT 3,
  `uplink` varchar(255) DEFAULT 'Owner',
  `saldo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 1,
  `expiration_date` datetime DEFAULT NULL,
  `reset_user` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin Account: Username: admin | Password: password
INSERT INTO `users` (`id_users`, `username`, `password`, `fullname`, `level`, `uplink`, `saldo`, `status`, `expiration_date`, `reset_user`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$eE0U4y0W7926M9Hj3N2Fae6K7wQhXz7pM4C6f1V8jYkL3j9.2d0rW', 'Admin Owner', 1, 'Owner', 1000.00, 1, '2030-01-01 00:00:00', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `id_users`=`id_users`;

-- --------------------------------------------------------

--
-- Table structure for table `_ftext`
--

CREATE TABLE IF NOT EXISTS `_ftext` (
  `id` int(11) NOT NULL,
  `ftext` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `_ftext` (`id`, `ftext`) VALUES
(1, 'MOD BY ARYANISPE') ON DUPLICATE KEY UPDATE `id`=`id`;

COMMIT;
