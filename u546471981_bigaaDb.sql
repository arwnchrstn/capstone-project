-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 26, 2022 at 11:36 AM
-- Server version: 10.5.13-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u546471981_bigaaDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `admin_id` varchar(60) NOT NULL,
  `admin_user` varchar(60) NOT NULL,
  `admin_password` varchar(256) NOT NULL,
  `position` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`admin_id`, `admin_user`, `admin_password`, `position`) VALUES
('ADMIN_1647520285', 'Maryflor', '$2y$10$lR5Wrm9diTU5SIdn894BJueYZRNM0LKwV/BXvcma.g6zkw/Is3gei', 'CLERK'),
('ADMIN_1647520940', 'asdasdsa', '$2y$10$zruFPoVcl/p7.cRBqBIfC.mCWUIItsQBiKtj6gDJTEvRzqxOIuq7u', 'SADASDASD'),
('ADMIN_1647526245', 'Admin 1', '$2y$10$m5X30j7P0Dn0XSLBqLUMLOy8KvadaHxF7f/u3IEyNEFQ81dE/o82G', 'CLERK'),
('ADMIN_1647791760', 'sampleAdmin', '$2y$10$R7.k1Yw/X0I/DuefXKY1rOjSMRXUeePUqMqZ4gKfla1tyIGmjBIky', 'ADMIN'),
('ADMIN_1647793043', 'Maria', '$2y$10$IR2Q1hOrWzyDaLSbBfZvhOsJF3dk3Q67AhwuSJE9NINyvl6.roBBu', 'CLERK'),
('ADMIN_1647807978', 'abc', '$2y$10$MRgXCcMu/8rJ.cMQmqVtGOCbpbCQB3n1Hi1abAwjVg/5DXf6c7fUa', '123'),
('ADMIN_1647808587', 'sampleAdmin', '$2y$10$jhLPhawOAEHW0RKNk8f02.Pk49d5JtgrT/tSTxgNEv.DG9QmICISa', 'ADMIN'),
('ADMIN_1647808590', 'sampleAdmin', '$2y$10$7umjhRJX04n.10wDMlTkGe1Ocr4C81UASbhXEH5Xp45jjZRUVmNz6', 'ADMIN'),
('ADMIN_1648000200', 'Admin_1', '$2y$10$FVunPrELo2EGjfZUKi5WjecjI0mtVzixgs6uf9OaD4V5Pm.81yy.y', 'CLERK');

-- --------------------------------------------------------

--
-- Table structure for table `control_number`
--

CREATE TABLE `control_number` (
  `control_id` varchar(16) NOT NULL,
  `control_no` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `control_number`
--

INSERT INTO `control_number` (`control_id`, `control_no`) VALUES
('CONTROL_NUMBER', '9090');

-- --------------------------------------------------------

--
-- Table structure for table `requests_list`
--

CREATE TABLE `requests_list` (
  `request_id` varchar(60) NOT NULL,
  `resident_id` varchar(60) NOT NULL,
  `request_type` varchar(16) NOT NULL,
  `purpose` varchar(256) NOT NULL,
  `request_status` varchar(16) NOT NULL,
  `request_date` timestamp NULL DEFAULT NULL,
  `date_completed` timestamp NULL DEFAULT NULL,
  `remarks` varchar(256) DEFAULT NULL,
  `ctrl_no_clearance` int(8) NOT NULL DEFAULT 0,
  `admin_processed` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requests_list`
--

INSERT INTO `requests_list` (`request_id`, `resident_id`, `request_type`, `purpose`, `request_status`, `request_date`, `date_completed`, `remarks`, `ctrl_no_clearance`, `admin_processed`) VALUES
('RQST_1645171752', 'BIGAA-1644879537', 'INDIGENCY', 'FOR SCHOLARSHIP PURPOSES', 'COMPLETED', '2022-02-18 16:09:12', '2022-03-12 06:03:17', 'Request completed', 0, NULL),
('RQST_1645295628', 'BIGAA-1644879537', 'INDIGENCY', 'FOR SCHOLARSHIP PURPOSES', 'COMPLETED', '2022-02-20 02:33:48', '2022-02-20 02:52:04', 'Request completed', 0, NULL),
('RQST_1645296045', 'BIGAA-1644879537', 'INDIGENCY', 'FOR SCHOLARSHIP PURPOSES', 'COMPLETED', '2022-02-20 02:40:45', '2022-02-20 02:52:03', 'Request completed', 0, NULL),
('RQST_1645350141', 'BIGAA-1645350070', 'INDIGENCY', 'SCHOLARSHIP', 'DECLINED', '2022-02-20 17:42:21', NULL, 'Trip ko lang', 0, NULL),
('RQST_1645350156', 'BIGAA-1645350070', 'CLEARANCE', 'DOCS', 'COMPLETED', '2022-02-20 17:42:36', '2022-03-12 06:22:08', 'Request completed', 5000, NULL),
('RQST_1645350173', 'BIGAA-1645350070', 'RESIDENCY', 'LOAN', 'DECLINED', '2022-02-20 17:42:53', NULL, 'Invalid', 0, NULL),
('RQST_1645384692', 'BIGAA-1645378974', 'CLEARANCE', 'EMPLOYMENT', 'COMPLETED', '2022-02-21 03:18:12', '2022-03-16 00:15:38', 'Request completed', 5001, NULL),
('RQST_1645384947', 'BIGAA-1645378974', 'CLEARANCE', 'EMPLOYMENT', 'DECLINED', '2022-02-21 03:22:27', NULL, '.', 0, NULL),
('RQST_1645385172', 'BIGAA-1645378974', 'RESIDENCY', 'FOR EMPLOYMENT PURPOSES', 'COMPLETED', '2022-02-21 03:26:12', '2022-03-18 18:21:08', 'Request completed', 0, NULL),
('RQST_1645385234', 'BIGAA-1645378974', 'INDIGENCY', 'EMPLOYMENT', 'CANCELLED', '2022-02-21 03:27:14', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1645385600', 'BIGAA-1645378974', 'RESIDENCY', 'EMPLOYMENT', 'CANCELLED', '2022-02-21 03:33:19', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1645385913', 'BIGAA-1645378974', 'INDIGENCY', 'EMPLOYMENT', 'CANCELLED', '2022-02-21 03:38:33', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1645385984', 'BIGAA-1645378974', 'RESIDENCY', 'EMPLOYMENT', 'CANCELLED', '2022-02-21 03:39:44', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1645386226', 'BIGAA-1645378974', 'RESIDENCY', 'EMPLOYMENT', 'CANCELLED', '2022-02-21 03:43:46', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1645632523', 'BIGAA-1645378974', 'INDIGENCY', 'FOR EMPLOYMENT PURPOSES', 'COMPLETED', '2022-02-24 00:08:43', '2022-03-17 10:49:31', 'Request completed', 0, NULL),
('RQST_1645715884', 'BIGAA-1644938894', 'CLEARANCE', 'FOR EMPLOYMENT PURPOSES', 'COMPLETED', '2022-02-24 23:18:04', '2022-03-12 06:19:13', 'Request completed', 5002, NULL),
('RQST_1645715889', 'BIGAA-1645378974', 'INDIGENCY', 'EMPLOYMENT', 'DECLINED', '2022-02-24 23:18:09', NULL, 'Invalid purpose', 0, NULL),
('RQST_1645715897', 'BIGAA-1645378974', 'RESIDENCY', 'EMPLOYMENT', 'COMPLETED', '2022-02-24 23:18:17', '2022-03-19 03:01:14', 'Request completed', 0, NULL),
('RQST_1645715906', 'BIGAA-1645378974', 'CLEARANCE', 'EMPLOYMENT', 'COMPLETED', '2022-02-24 23:18:26', '2022-03-16 00:15:46', 'Request completed', 5003, NULL),
('RQST_1645716002', 'BIGAA-1644938894', 'INDIGENCY', 'EMPLOYMENT PURPOSES', 'COMPLETED', '2022-02-24 23:20:02', '2022-03-16 00:15:35', 'Request completed', 0, NULL),
('RQST_1645716007', 'BIGAA-1645350070', 'INDIGENCY', 'PAG-IBIG', 'COMPLETED', '2022-02-24 23:20:07', '2022-03-13 12:48:07', 'Request completed', 0, NULL),
('RQST_1645716016', 'BIGAA-1645350070', 'RESIDENCY', 'FOR SCHOLARSHIP PURPOSES', 'COMPLETED', '2022-02-24 23:20:16', '2022-03-20 19:53:22', 'Request completed', 0, NULL),
('RQST_1645716026', 'BIGAA-1645350070', 'CLEARANCE', 'FOR POSTAL ID', 'COMPLETED', '2022-02-24 23:20:26', '2022-02-24 23:29:00', 'Request completed', 5003, NULL),
('RQST_1645716291', 'BIGAA-1645716092', 'INDIGENCY', 'SCHOLAR', 'COMPLETED', '2022-02-24 23:24:51', '2022-03-12 06:07:08', 'Request completed', 0, 'ADMIN_1637945628'),
('RQST_1645716362', 'BIGAA-1645716092', 'RESIDENCY', 'EMPLOYMENT', 'COMPLETED', '2022-02-24 23:26:02', '2022-03-21 04:22:03', 'Request completed', 0, 'admin'),
('RQST_1645716373', 'BIGAA-1645716092', 'CLEARANCE', 'EMPLOYMENT', 'COMPLETED', '2022-02-24 23:26:13', '2022-03-21 04:22:14', 'Request completed', 5004, NULL),
('RQST_1645920370', 'BIGAA-1644879537', 'INDIGENCY', 'EMPLOYMENT', 'COMPLETED', '2022-02-27 08:06:10', '2022-03-12 06:07:02', 'Request completed', 0, NULL),
('RQST_1645927836', 'BIGAA-1645926985', 'INDIGENCY', 'SCHOLARSHIP', 'COMPLETED', '2022-02-27 10:10:36', '2022-02-27 10:24:07', 'Request completed', 0, NULL),
('RQST_1646916728', 'BIGAA-1645926985', 'CLEARANCE', 'EMPLOYMENT', 'FOR PICKUP', '2022-03-10 12:52:08', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 5001, NULL),
('RQST_1646916930', 'BIGAA-1645926985', 'INDIGENCY', 'ASDDAD', 'CANCELLED', '2022-03-10 12:55:30', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1646916959', 'BIGAA-1645926985', 'CLEARANCE', 'ASDAD', 'COMPLETED', '2022-03-10 12:55:59', '2022-03-10 12:57:57', 'Request completed', 5002, NULL),
('RQST_1647017164', 'BIGAA-1645926985', 'INDIGENCY', 'ASDADS', 'DECLINED', '2022-03-11 16:46:04', NULL, 'Invalid request', 0, 'admin'),
('RQST_1647067115', 'BIGAA-1645926985', 'RESIDENCY', 'ASDAD', 'FOR PICKUP', '2022-03-12 06:38:35', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'admin'),
('RQST_1647072060', 'BIGAA-1645926985', 'INDIGENCY', 'ASDASDAS', 'FOR PICKUP', '2022-03-12 08:01:00', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'admin'),
('RQST_1647175485', 'BIGAA-1645926985', 'INDIGENCY', 'TEST', 'DECLINED', '2022-03-13 12:44:45', NULL, 'Wala lang kasi bobo ka', 0, 'admin'),
('RQST_1647388533', 'BIGAA-1645926985', 'INDIGENCY', 'ASDASDAS', 'FOR PICKUP', '2022-03-15 23:55:33', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'admin'),
('RQST_1647388534', 'BIGAA-1645926985', 'RESIDENCY', 'ASDSADASD', 'CANCELLED', '2022-03-15 23:55:33', NULL, 'Cancelled by the user', 0, 'admin'),
('RQST_1647388535', 'BIGAA-1645926985', 'CLEARANCE', 'ASDASDASD', 'FOR PICKUP', '2022-03-15 23:55:33', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 5013, 'admin'),
('RQST_1647388783', 'BIGAA-1645926985', 'INDIGENCY', 'ASDSAD', 'CANCELLED', '2022-03-15 23:59:43', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1647388784', 'BIGAA-1645926985', 'RESIDENCY', 'ASDASD', 'CANCELLED', '2022-03-15 23:59:43', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1647388785', 'BIGAA-1645926985', 'CLEARANCE', 'ASDASDASD', 'FOR PICKUP', '2022-03-15 23:59:43', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 5015, 'admin'),
('RQST_1647389177', 'BIGAA-1645926985', 'INDIGENCY', 'ASDASD', 'CANCELLED', '2022-03-16 00:06:17', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1647389248', 'BIGAA-1645926985', 'INDIGENCY', 'ASDASDA', 'COMPLETED', '2022-03-16 00:07:28', '2022-03-19 03:02:55', 'Request completed', 0, 'Maryflor'),
('RQST_1647526120', 'BIGAA-1645926985', 'INDIGENCY', 'SCHOLARSHIP', 'FOR PICKUP', '2022-03-17 14:08:40', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'Maryflor'),
('RQST_1647526121', 'BIGAA-1645926985', 'RESIDENCY', 'EMPLOYMENT', 'CANCELLED', '2022-03-17 14:08:40', NULL, 'Cancelled by the user', 0, NULL),
('RQST_1647526122', 'BIGAA-1645926985', 'CLEARANCE', 'POSTAL ID', 'COMPLETED', '2022-03-17 14:08:40', '2022-03-19 04:51:53', 'Request completed', 5014, 'Maryflor'),
('RQST_1647636013', 'BIGAA-1645926985', 'INDIGENCY', 'SCHOLARSHIP', 'FOR PICKUP', '2022-03-19 04:40:13', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'Maryflor'),
('RQST_1647773258', 'BIGAA-1647701445', 'INDIGENCY', 'HOTDGO', 'FOR PICKUP', '2022-03-20 18:47:38', NULL, 'Your certificate is ready, please pickup your document at the barangay hall', 0, 'Maryflor'),
('RQST_1647773259', 'BIGAA-1647701445', 'RESIDENCY', '-', 'CANCELLED', '2022-03-20 18:47:38', NULL, 'Cancelled by the user', 0, 'Maryflor'),
('RQST_1647773260', 'BIGAA-1647701445', 'CLEARANCE', '-', 'CANCELLED', '2022-03-20 18:47:38', NULL, 'Cancelled by the user', 0, 'Maryflor'),
('RQST_1647774541', 'BIGAA-1647701445', 'INDIGENCY', '-', 'PROCESSING', '2022-03-20 19:09:01', NULL, 'Your request is being processed', 0, 'Maryflor'),
('RQST_1647774542', 'BIGAA-1647701445', 'RESIDENCY', '-', 'DECLINED', '2022-03-20 19:09:01', NULL, '123', 0, 'Maryflor'),
('RQST_1647774543', 'BIGAA-1647701445', 'CLEARANCE', '-', 'DECLINED', '2022-03-20 19:09:01', NULL, 'Unverified user', 0, 'Maryflor'),
('RQST_1647775704', 'BIGAA-1647701445', 'INDIGENCY', '-', 'DECLINED', '2022-03-20 19:28:24', NULL, 'asd', 0, 'Maryflor'),
('RQST_1647775705', 'BIGAA-1647701445', 'RESIDENCY', '-', 'PENDING', '2022-03-20 19:28:24', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1647775706', 'BIGAA-1647701445', 'CLEARANCE', '-', 'PENDING', '2022-03-20 19:28:24', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1647805157', 'BIGAA-1647701445', 'INDIGENCY', '--', 'PENDING', '2022-03-21 03:39:17', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1647805163', 'BIGAA-1647701445', 'INDIGENCY', 'A', 'PENDING', '2022-03-21 03:39:23', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1647805784', 'BIGAA-1647701445', 'INDIGENCY', 'SCHOLARSHIPAAAAAA', 'PENDING', '2022-03-21 03:49:44', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1648000081', 'BIGAA-1645926985', 'INDIGENCY', 'EMPLOYMENT', 'PENDING', '2022-03-23 09:48:01', NULL, 'Your request is filed and waiting to be processed', 0, NULL),
('RQST_1648000082', 'BIGAA-1645926985', 'RESIDENCY', 'SCHOLARSHIP', 'PENDING', '2022-03-23 09:48:01', NULL, 'Your request is filed and waiting to be processed', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `resident_id` varchar(60) NOT NULL,
  `reset_key` varchar(128) DEFAULT NULL,
  `date_requested` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reset_password_request`
--

INSERT INTO `reset_password_request` (`resident_id`, `reset_key`, `date_requested`) VALUES
('BIGAA-1644879537', 'b88789eae8d0f4890de2d3e53e0d79c7', '2022-02-20 02:39:09'),
('BIGAA-1645926985', '589b66105340a684ea8e77a9560c2714', '2022-03-08 21:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `resident_info`
--

CREATE TABLE `resident_info` (
  `resident_id` varchar(60) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `middle_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(60) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `civil_status` varchar(10) NOT NULL,
  `address` varchar(256) NOT NULL,
  `year_of_stay` date NOT NULL,
  `mobile_number` varchar(11) NOT NULL,
  `picture_name` varchar(60) NOT NULL,
  `voters_picture_name` varchar(60) NOT NULL,
  `account_created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resident_info`
--

INSERT INTO `resident_info` (`resident_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `birthplace`, `gender`, `civil_status`, `address`, `year_of_stay`, `mobile_number`, `picture_name`, `voters_picture_name`, `account_created`) VALUES
('BIGAA-1644879537', 'ARWEN CHRISTIAN', 'COROD', 'CERES', 'N/A', '2000-09-09', 'CALAMBA CITY, LAGUNA', 'MALE', 'SINGLE', 'PUROK 5, BIGAA, CITY OF CABUYAO, LAGUNA', '0000-00-00', '09298379612', '1645920583.png', '1644879537votersID.png', '2022-02-15 06:58:57'),
('BIGAA-1644913773', 'MARK', 'DE LUMEN', 'FELICIA', 'N/A', '1999-11-16', 'CALAMBA CITY, LAGUNA', 'MALE', 'SINGLE', 'ROAD 4 #02421, BIGAA, CITY OF CABUYAO, LAGUNA', '0000-00-00', '09551736242', '1644913773.png', '1644913773votersID.png', '2022-02-15 16:29:33'),
('BIGAA-1644938894', 'ANGELICA', 'ANTONIO', 'SOYANGCO', 'N/A', '1999-06-27', 'BALANGA, BATAAN', 'FEMALE', 'SINGLE', 'BLK 17 LOT 4 APITONG ST, BIGAA, CITY OF CABUYAO, LAGUNA', '0000-00-00', '09153576629', '1644938894.png', '1644938894votersID.png', '2022-02-15 23:28:14'),
('BIGAA-1645350070', 'JUAN', 'ANTONIO', 'DELA CRUZ', 'SR', '1991-02-01', 'CALAMBA, LAGUNA', 'MALE', 'SINGLE', '#68 DR.SIXTO AVENUE, BIGAA, CITY OF CABUYAO, LAGUNA', '2016-09-12', '09551736242', '1645350070.png', '1645350070votersID.png', '2022-02-20 17:41:09'),
('BIGAA-1645378974', 'CHABELITA', 'DUZA', 'ALIMPUYO', 'N/A', '1999-08-18', 'CABUYAO', 'FEMALE', 'SINGLE', '209 PUROK 5, BIGAA, CITY OF CABUYAO, LAGUNA', '2010-06-20', '09488092053', '1645632547.png', '1645378974votersID.png', '2022-02-21 01:42:53'),
('BIGAA-1645716092', 'SONA', 'CALAPE', 'MANCIO', 'N/A', '2022-02-03', 'BINAN', 'MALE', 'SINGLE', '123 SAMPALOC ST, BIGAA, CITY OF CABUYAO, LAGUNA', '0000-00-00', '09083526212', '1645716861.png', '1645716092votersID.png', '2022-02-24 23:21:32'),
('BIGAA-1645926985', 'JUAN', '', 'DELA CRUZ', 'N/A', '1968-04-18', 'CALAMBA CITY LAGUNA', 'MALE', 'SINGLE', 'PUROK 5, BIGAA, CITY OF CABUYAO, LAGUNA', '2016-09-01', '09298379612', '1647067616.png', '1645926985votersID.png', '2022-02-27 09:56:25'),
('BIGAA-1647680883', 'ASDASD', 'ASDA', 'DASDA', 'N/A', '2015-09-29', 'AASDADASD', 'MALE', 'SINGLE', 'ASDASDASDASD BIGAA, CITY OF CABUYAO, LAGUNA', '2015-04-01', '09125115151', '1647680883.png', '1647680883votersID.png', '2022-03-19 17:08:03'),
('BIGAA-1647701445', 'BRYAN', 'CANE', 'ALONTE', 'N/A', '1989-08-21', 'CABUYAO CITY', 'MALE', 'SINGLE', '123 ST MABUHAY VILLAGE BIGAA, CITY OF CABUYAO, LAGUNA', '2000-04-01', '09083526212', '1647803313.png', '1647701445votersID.png', '2022-03-19 22:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `resident_login_info`
--

CREATE TABLE `resident_login_info` (
  `resident_id` varchar(60) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resident_login_info`
--

INSERT INTO `resident_login_info` (`resident_id`, `email`, `password`) VALUES
('BIGAA-1644879537', 'arwenceres@gmail.com', '$2y$10$ysh53vGEqCcs8QH492Mdoet5jfPvZmATyNuWYS1sDQF4jaYW0s.xW'),
('BIGAA-1644913773', 'mark.gv@yahoo.com', '$2y$10$Xv7x5qx5IFMBlGZ3DlYxpOamxJn/lvEassDNyaKxjF891BR0/ossy'),
('BIGAA-1644938894', 'angelicasoyangco0@gmail.com', '$2y$10$Zk6rccMPWQLnH6.IX0lo3.Qmdbh.xEsYIORqSLwhL4BmMvo.ITXOa'),
('BIGAA-1645350070', 'boots.dota2@yahoo.com', '$2y$10$f1E24m5VkGpLkRtrN9mw8eDPhytjMW6P/eBRmExX1f47hHFclnkWi'),
('BIGAA-1645378974', 'alimpuyochabelita@gmail.com', '$2y$10$ahJiVFCKzmDOoWApnj7DJ.vdKasOrkqWNhT7d5x.xxd4jFC0UDBG.'),
('BIGAA-1645716092', 'manciochristian@gmail.com', '$2y$10$bRd8MuQaH376CjZBfJRonOgfa7rJe1mF72QPSX6Zq16tTRCV6NwUG'),
('BIGAA-1645926985', 'ceres703@gmail.com', '$2y$10$byy1clstIChSCAJjSU/z..5RtsW88VeDJXrYkXLlxahdIXlhi1BWW'),
('BIGAA-1647680883', 'asdasdasd@asdas.com', '$2y$10$8YX8pi5FSOlZmb83xKU4yuVvbijDQwjtH2pQXY7uUhXAmRUpGbS5m'),
('BIGAA-1647701445', 'manciosona@gmail.com', '$2y$10$yt./z6Ql88uAsbIJX2l7teSNiUINApcIUlBiPTivNXDXW216I1pjy');

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `resident_id` varchar(60) NOT NULL,
  `verification_key` varchar(256) NOT NULL,
  `isEmailVerified` int(1) DEFAULT 0,
  `isAccountVerified` int(1) DEFAULT 0,
  `isMobileVerified` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`resident_id`, `verification_key`, `isEmailVerified`, `isAccountVerified`, `isMobileVerified`) VALUES
('BIGAA-1644879537', '32416a2da47be454e8996eb359fae3d0', 1, 1, 1),
('BIGAA-1644913773', 'cff4cebe669a738b30a246a2613c5fea', 1, 1, 1),
('BIGAA-1644938894', 'b7c97de020fdc2ad05c5c9dcfc65c44c', 1, 1, 1),
('BIGAA-1645350070', '320966176c0335bc59329d4020e6e0cb', 1, 1, 1),
('BIGAA-1645378974', '6957e42055fb25b780168d8c008c405f', 1, 1, 1),
('BIGAA-1645716092', '308f6720b5cf5f62065213b6ba2905f9', 1, 1, 1),
('BIGAA-1645926985', '63e93149e76b004a831b2d99f9abe7a6', 1, 1, 1),
('BIGAA-1647680883', '35f47a2bc009f248f9d8e6a6020522cd', 0, 0, 0),
('BIGAA-1647701445', '45e466d97a8ab9cfa683341082b7cdfb', 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `control_number`
--
ALTER TABLE `control_number`
  ADD PRIMARY KEY (`control_id`);

--
-- Indexes for table `requests_list`
--
ALTER TABLE `requests_list`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_resident_id` (`resident_id`);

--
-- Indexes for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`resident_id`);

--
-- Indexes for table `resident_info`
--
ALTER TABLE `resident_info`
  ADD PRIMARY KEY (`resident_id`);

--
-- Indexes for table `resident_login_info`
--
ALTER TABLE `resident_login_info`
  ADD PRIMARY KEY (`resident_id`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`resident_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests_list`
--
ALTER TABLE `requests_list`
  ADD CONSTRAINT `fk_resident_id` FOREIGN KEY (`resident_id`) REFERENCES `resident_info` (`resident_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `reset_password_request_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident_info` (`resident_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resident_login_info`
--
ALTER TABLE `resident_login_info`
  ADD CONSTRAINT `resident_login_info_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident_info` (`resident_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `verification_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident_info` (`resident_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
