-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 08:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rescueflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `assetcategory_id` int(11) DEFAULT NULL,
  `asset_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Available','In Use','Maintenance','Damaged') DEFAULT 'Available',
  `last_maintenance_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `assetcategory_id`, `asset_name`, `description`, `status`, `last_maintenance_date`) VALUES
(12, 2, 'Ambulance B', 'Emergency medical vehicle', 'In Use', '2024-12-20'),
(13, 3, 'Rescue Boat C', 'Water rescue boat', 'Maintenance', '2025-02-10'),
(14, 4, 'Helicopter D', 'Search and rescue helicopter', 'Damaged', '2024-11-05'),
(20, 7, 'crowbar', 'matibay, pamukpok sa pabigat na kagroup', 'In Use', '2025-03-11'),
(21, 6, 'ladder', 'kaya abutin pati pangarap mo', 'Available', '2025-02-25'),
(25, 6, 'granada', 'malakas putok', 'Available', '2025-03-10'),
(26, 3, 'fire extinguisher', 'for demonstration', 'Maintenance', '2025-03-23'),
(27, 1, 'ladder', 'for demo purposes only', 'In Use', '2025-02-24');

-- --------------------------------------------------------

--
-- Table structure for table `assets_image`
--

CREATE TABLE `assets_image` (
  `asset_id` int(11) NOT NULL,
  `img_path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets_image`
--

INSERT INTO `assets_image` (`asset_id`, `img_path`) VALUES
(5, 'asset/images/celetaria_Completed Time.png'),
(6, 'asset/images/celetaria_starttime_Activity 6.5.1.png'),
(7, 'asset/images/1.jpg'),
(13, 'assets/images/2025.png'),
(14, 'assets/images/OPERATING SYSTEMS.png'),
(12, 'assets/images/2025.png'),
(21, 'assets/images/2025.png'),
(20, 'assets/images/OPERATING SYSTEMS.png'),
(25, 'assets/images/download.jfif'),
(26, 'assets/images/2025.png'),
(27, 'assets/images/download.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `asset_category`
--

CREATE TABLE `asset_category` (
  `AssetCategory_ID` int(11) NOT NULL,
  `Category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_category`
--

INSERT INTO `asset_category` (`AssetCategory_ID`, `Category`) VALUES
(1, 'Firetruck 1'),
(2, 'Firetruck 2'),
(3, 'Firetruck 3'),
(4, 'Firetruck 4'),
(6, 'Stationary'),
(7, 'Emergency Vehicle');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `shift_id` int(11) DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `personnel_id`, `timestamp`, `shift_id`, `time_out`) VALUES
(45, 9, '2025-03-22 06:12:59', 2, '2025-03-23 21:00:00'),
(57, 15, '2025-03-23 14:47:07', 2, '2025-03-23 23:00:01'),
(59, 15, '2025-03-24 04:46:27', 2, NULL),
(60, 18, '2025-03-26 00:24:07', 2, NULL),
(61, 19, '2025-03-26 00:31:49', 2, NULL),
(62, 9, '2025-03-27 22:58:04', 2, NULL),
(63, 3, '2025-03-27 23:56:58', 2, NULL),
(64, 21, '2025-03-28 02:09:28', 2, NULL),
(65, 3, '2025-04-03 01:21:46', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`) VALUES
(1, 'Bagumbayan'),
(2, 'Bambang'),
(3, 'Calzada'),
(19, 'Cembo'),
(20, 'Central Bicutan'),
(21, 'Central Signal Village'),
(4, 'Comembo'),
(22, 'East Rembo'),
(23, 'Fort Bonifacio'),
(5, 'Hagonoy'),
(6, 'Ibayo-Tipas'),
(24, 'Katuparan'),
(7, 'Ligid-Tipas'),
(8, 'Lower Bicutan'),
(25, 'Maharlika Village'),
(10, 'Napindan'),
(9, 'New Lower Bicutan'),
(26, 'North Daang Hari'),
(27, 'North Signal Village'),
(11, 'Palingon'),
(12, 'Pembo'),
(28, 'Pinagsama'),
(29, 'Pitogo'),
(30, 'Post Proper Northside'),
(31, 'Post Proper Southside'),
(13, 'Rizal'),
(14, 'San Miguel'),
(15, 'Santa Ana'),
(32, 'South Cembo'),
(33, 'South Daang Hari'),
(34, 'South Signal Village'),
(35, 'Tanyag'),
(16, 'Tuktukan'),
(36, 'Upper Bicutan'),
(17, 'Ususan'),
(18, 'Wawa'),
(37, 'West Rembo'),
(38, 'Western Bicutan');

-- --------------------------------------------------------

--
-- Table structure for table `dispatches`
--

CREATE TABLE `dispatches` (
  `disp_id` int(11) NOT NULL,
  `incident_id` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `dispatched_unit` varchar(100) NOT NULL,
  `dispatched_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatches`
--

INSERT INTO `dispatches` (`disp_id`, `incident_id`, `location`, `dispatched_unit`, `dispatched_at`, `status_id`) VALUES
(1, 0, 'taguig tup', 'Firetruck', '2025-02-26 06:21:35', 3),
(2, 0, 'western bicutan', 'Firetruck', '2025-02-26 06:26:48', 3),
(3, NULL, 'market market', 'Firetruck', '2025-02-26 09:38:55', 3),
(4, NULL, 'tup taguig', 'Firetruck', '2025-02-26 09:39:19', 3),
(5, NULL, 'blk 131 lt 1 taguig city upper bicutan', 'Firetruck', '2025-02-26 09:39:41', 3),
(6, NULL, 'tenement', 'Firetruck', '2025-02-26 09:41:55', 3),
(7, NULL, 'gladiola brgy rizal', 'Firetruck', '2025-02-26 09:43:40', 3),
(8, NULL, 'gladiola brgy rizal', 'Firetruck', '2025-02-26 09:54:14', 3),
(9, NULL, 'arc south taguig', 'Firetruck', '2025-02-27 11:59:44', 3),
(10, NULL, 'bicutan', 'Firetruck', '2025-02-27 12:01:04', 3),
(11, NULL, 'arc south taguig', 'Firetruck', '2025-02-27 13:15:12', 3),
(12, NULL, 'arc south taguig', 'Firetruck', '2025-02-28 14:07:10', 3),
(13, NULL, 'bicutan', 'Firetruck', '2025-02-28 14:36:17', 3),
(14, NULL, 'arc south taguig', 'Firetruck', '2025-03-01 02:04:03', 3),
(15, NULL, 'arc south taguig', 'Firetruck', '2025-03-15 00:43:18', 3),
(16, NULL, 'tup taguig', 'Firetruck', '2025-03-15 00:48:51', 3),
(17, NULL, 'tup taguig', 'Firetruck', '2025-03-15 00:51:56', 3),
(18, NULL, 'arc south taguig', 'Firetruck', '2025-03-16 01:21:28', 3),
(19, NULL, 'arca south taguig city', 'Firetruck', '2025-03-18 01:16:31', 3),
(20, NULL, 'arca south taguig city', 'Firetruck', '2025-03-18 01:16:48', 3),
(21, NULL, 'arca south taguig city', 'Firetruck', '2025-03-18 01:17:07', 3),
(22, NULL, 'arca south taguig city', 'Firetruck', '2025-03-18 01:17:25', 3),
(23, NULL, 'baranggay western bicutan taguig city', 'Firetruck', '2025-03-18 01:25:20', 3),
(24, NULL, 'arca south taguig city', 'Firetruck', '2025-03-22 20:14:36', 3),
(25, NULL, 'arca south taguig city', 'Firetruck', '2025-03-23 14:32:31', 3),
(26, NULL, 'arca south taguig city', 'Firetruck', '2025-03-23 14:38:00', 3),
(27, NULL, 'baranggay western bicutan taguig city', 'Firetruck', '2025-03-23 14:55:46', 3),
(28, NULL, 'arca south taguig city', 'Firetruck', '2025-03-23 15:11:07', 3),
(29, NULL, 'arca south taguig city', 'Firetruck', '2025-03-28 02:52:32', 3),
(30, NULL, 'arca south taguig city', 'Firetruck', '2025-03-28 02:53:31', 3),
(31, NULL, 'arca south taguig city', 'Firetruck', '2025-03-28 02:53:43', 3),
(32, NULL, 'upper bicutan', 'Firetruck', '2025-03-28 02:53:43', 3),
(33, NULL, 'arca south taguig city', 'Firetruck', '2025-03-28 03:10:04', 3),
(34, NULL, 'arca south taguig city', 'Firetruck', '2025-03-28 03:10:44', 3),
(35, NULL, 'arca south taguig city', 'Firetruck', '2025-04-03 01:22:48', 2);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_details`
--

CREATE TABLE `emergency_details` (
  `id` int(11) NOT NULL,
  `dispatch_id` int(11) DEFAULT NULL,
  `what` text NOT NULL,
  `where` text NOT NULL,
  `why` text NOT NULL,
  `caller_name` varchar(255) NOT NULL,
  `caller_phone` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_details`
--

INSERT INTO `emergency_details` (`id`, `dispatch_id`, `what`, `where`, `why`, `caller_name`, `caller_phone`, `timestamp`, `status`) VALUES
(14, 1, 'apoy', 'kiki', 'lu', 'lo', '0909', '2025-03-15 02:06:07', 1),
(20, NULL, 'jna', 'jna', 'jan', 'kjkj', '0900', '2025-03-16 02:39:25', 1),
(21, NULL, 'ksi', 'ksi', 'ksi', 'ksii', '123', '2025-03-16 02:44:54', 1),
(22, NULL, 'ne', 'ne', 'ne', 'ne', '0909009', '2025-03-16 02:47:19', 3),
(23, NULL, 'li', 'li', 'li', 'li', '09099', '2025-03-16 02:49:55', 1),
(24, NULL, 'tty', 'ty', 'ty', 'rtrt', '0909', '2025-03-16 02:52:29', 1),
(25, NULL, 'cv', 'cv', 'cv', 'we', '132', '2025-03-16 02:57:30', 1),
(26, NULL, 'loh', 'lk', 'lk', 'lk', '123', '2025-03-16 10:02:27', 1),
(27, NULL, 'ki', 'ki', 'ki', 'ki', '090908', '2025-03-16 10:09:00', 1),
(28, NULL, 'unog', 'kajs', 'kjskj', 'wkjdk', '9898', '2025-03-16 10:12:20', 1),
(29, NULL, 'nagkaroon ng malalang sunog', 'sa tup taguig branch', 'sunog malamang', 'flint celetaria', '2123231', '2025-03-20 14:54:10', 2),
(30, 1, 'fire emergency', 'bandang western bicutan', 'sunog malaki', '1234567890', 'rovic abonita', '2025-03-22 09:55:51', 3),
(31, 1, 'inuman', 'kila juliane', 'bagsak tatlo', 'sharwin', '12353465', '2025-03-22 20:12:07', 3),
(32, 1, 'inuman', 'kila juliane', 'bagsak tatlo', 'sharwin', '21312413', '2025-03-22 20:16:06', 3),
(33, 1, 'gumawa ng arduino', 'kila boss juliane', 'de joke tambay lang', 'evan piad', '1234124133', '2025-03-22 20:41:52', 2),
(34, 1, 'transport strike sa metro manila', 'metro manila', 'kasi walang masakyan', 'evan piad', '12342341', '2025-03-23 12:16:53', 3),
(35, 1, 'fire emergency', 'baranggay western bicutan', 'widespread fire among residential area', 'evan piad', '123456789', '2025-03-23 14:17:58', 3),
(36, 1, 'fire disaster', 'baranggay western bicutan', 'widespread fire incident in residential area', 'flint celetaria', '1233455646557', '2025-03-23 14:41:46', 1),
(40, 1, 'apoy', 'bicutan', 'sunog', 'werf', '4234', '2025-03-23 15:04:09', 2),
(41, 1, 'fire incident', 'arca south', 'wildfire potential', 'flint celetaria', '3431413', '2025-03-23 15:12:11', 2),
(42, 1, 'earthquake', 'wester bicutan taguig city', 'basically it is an earthquake', 'rovic abonita', '12345678', '2025-03-26 00:29:58', 1),
(43, 1, 'may sunog mga lods', 'dito lang samin', 'hahaha nasunog kaibigan ko ernz rabbi :(', 'Evan P. Garcia', '09382978477', '2025-03-26 00:40:35', 2),
(44, 1, 'fire incident', 'baranggay western bicutan', 'rapid spread of fire in dense rural areas', 'Jemuel', '09382978477', '2025-03-28 00:41:14', 1),
(45, 1, 'fire incident', 'baranggay western bicutan', 'rapid spread of fire in dense rural areas', 'Jemuel', '09382978477', '2025-03-28 00:42:40', 1),
(46, 1, 'fire incident', 'baranggay western bicutan', 'rapid spread of fire in dense rural areas', 'Jemuel', '09382978477', '2025-03-28 00:44:00', 1),
(47, 1, 'fire incident', 'baranggay western bicutan', 'rapid spread of fire in dense rural areas', 'Jemuel', '09382978477', '2025-03-28 00:44:49', 1),
(48, 1, 'fire incident', 'baranggay western  bicutan', 'fire ', 'jett axel', '13124124134', '2025-03-28 01:59:33', 3);

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` int(11) NOT NULL,
  `incident_type` varchar(100) NOT NULL,
  `severity_id` int(11) DEFAULT NULL,
  `location` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) NOT NULL,
  `reported_time` datetime DEFAULT current_timestamp(),
  `status_id` int(11) DEFAULT NULL,
  `cause` enum('Electrical Faults','Unattended Cooking','Candles & Open Flames','Smoking Indoors','Gas Leaks','Flammable Liquids','Children Playing with Fire','Heating Equipment','Faulty Appliances','Arson') DEFAULT NULL,
  `attachments` text DEFAULT NULL,
  `actions_taken` varchar(255) DEFAULT NULL,
  `barangay_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incident_id`, `incident_type`, `severity_id`, `location`, `address`, `reported_by`, `reported_time`, `status_id`, `cause`, `attachments`, `actions_taken`, `barangay_id`) VALUES
(55, 'Fire', 1, '', 'Duhat Street, Brgy. Western Bicutan, Taguig City', 'josh bernabe', '2025-03-23 19:30:17', NULL, 'Unattended Cooking', '../uploads/1742729417_download.jpg', '', 38),
(57, 'Fire', 2, '', 'Duhat Street, Brgy. Western Bicutan, Taguig City', 'jemuel', '2025-03-23 22:49:34', NULL, 'Gas Leaks', '../uploads/1742741374_download.jpg', '', 38),
(59, 'Fire', 1, '', 'Duhat Street, Brgy. Western Bicutan, Taguig City', 'jemuel', '2025-03-28 10:13:23', NULL, 'Gas Leaks', '../uploads/1743128003_bfp1.jpg,../uploads/1743128003_bfp2.jpg', '', 17),
(60, 'Fire', 1, '', 'bandang eskinita', 'flint', '2025-03-28 10:13:59', NULL, 'Unattended Cooking', '../uploads/1743128039_bfp3.jpg', '', 23),
(61, 'Fire', 3, '', 'malayo dito', 'jemuel', '2025-03-28 10:14:36', NULL, 'Electrical Faults', '../uploads/1743128076_LoginRegister.png', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `PersonnelID` int(11) NOT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `RankID` int(11) DEFAULT NULL,
  `ShiftID` int(11) DEFAULT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Profile` varchar(255) DEFAULT NULL,
  `personnelstatus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`PersonnelID`, `RoleID`, `RankID`, `ShiftID`, `FirstName`, `LastName`, `Email`, `PhoneNumber`, `Password`, `Profile`, `personnelstatus_id`) VALUES
(3, 4, 5, 2, 'Jemuel ', 'Malaga', 'malagajemuel@gmail.com', '09994617537', '$2y$10$ekR8aP7d9uG/jn6DUHX9WepX9Dmv92j5EA0KZ7jRu5fUrgVO3Q9q2', '67dff75cee3cb.png', 1),
(9, 1, 1, 2, 'Venus', 'Page', 'venuspage18@gmail.com', '', '$2y$10$A9YYck8XWAKgcP5ePuXwD.X8PVAoUV/YLb05dUyasuZYzX72IkJO.', NULL, 1),
(10, 1, 1, 2, 'Sharwin', 'Marbella', 'marbellasharwinjohn@gmail.com', '', '$2y$10$T95AGeT95SLjwfQxvq6ak.Veq051n2bYF4DTABoglD.ssrnAWIUri', NULL, 1),
(11, 1, 1, 2, 'Krsmur Chelvin', 'Lacorte', 'kclacorte27@gmail.com', '', '$2y$10$WfwnaUPtiyXhIpcyO.kirudQUxOGe1xQabk67CuSDJdy4/QWumf3e', NULL, 1),
(12, 3, 3, 1, 'Mary Jazmine', 'Malaga', 'malagamaryjazmine@gmail.com', '092423543235', '$2y$10$chBU/BUbEYRdoqlk/2yXGOMxq482/75pYQicprmCz26BnHXSl0c1a', '67dff6af4dd72.png', 1),
(13, 2, 3, 2, 'Jellian ', 'Malaga', 'jellianjesmalaga@gmail.com', '344141', '$2y$10$762wkyaacR9HtTJwnTPxm.GFn3S2XDLiUjPNstwqENsmk0mPv6xrK', '67e023f458ec6.png', 1),
(14, 3, 4, 1, 'Flint Axl', 'Celetaria', 'flintaxl.celetaria@gmail.com', '12432413435', '$2y$10$k71a.hn76EQ0ud4vFDvuYODJUychrD2cNi5z1K0ZvKyzx.K4YPMmy', '67e01ac1072a4.png', 1),
(15, 1, 1, 2, 'Jules Christian', 'Mayordo', 'juleschristianmayordo@gmail.com', '', '$2y$10$m7Wlxbh7sc5XGFPRfRGsn.afWQyh2xAeueLGw0DJW2ILlQXS5RVh2', NULL, 1),
(17, 1, 4, 1, 'Josefina', 'Malaga', 'josefinamalaga@gmail.com', 'r12343141', '$2y$10$p7w9NA3VwkUMF2Jm2ivrEef4U7OeaWCp0MMu2DgTntDQsWtxIIgpe', 'default.jpg', 1),
(18, 1, 1, 2, 'Alvin Symo', 'Yago', 'alvinsymo@gmaail.com', '', '$2y$10$QGXRPJ2hZSKklwuot5.uveunUeUaE6Gj15rQtI7gvgK9/KIy.Oox.', NULL, 1),
(19, 1, 1, 2, 'rovic', 'abonita', 'curtleyabonits@gmail.com', '', '$2y$10$NbfmvBJTuzxEq75R0ICYm.zoGQxKQUM/BEIjl0bmMsXfY5nZpWOWC', NULL, 1),
(20, 1, 1, 1, 'Gerald Loise', 'Garcia', 'loisegarcia07@gmail.com', '', '$2y$10$Cw66/dsUeEGIbTJ4Z64dEOHxLr5URYQ3DG48P26KOsXk.gJSx/Tc6', NULL, 1),
(21, 1, 1, 2, 'Ernz', 'Llabore', 'ernzllabore@gmail.com', '', '$2y$10$7frVNcBMfMgHIaua8KV//ehZP6DJn7f8ADNeLV11/mNK6E8NRwLGq', NULL, 2),
(22, 2, NULL, NULL, 'Gem', 'Alfaro', 'gemmalfaro023@gmail.com', '', '$2y$10$5Xr8XjGUUqSHk8GmAaP8COzgb6c/B0dIUitu9TOSRAvz8IEMmjLJq', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personnelstatus`
--

CREATE TABLE `personnelstatus` (
  `personnelstatus_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnelstatus`
--

INSERT INTO `personnelstatus` (`personnelstatus_id`, `status`) VALUES
(1, 'Active'),
(2, 'Not Active');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`rank_id`, `rank_name`) VALUES
(4, 'Captain'),
(5, 'Chief'),
(2, 'Firefighter First Class'),
(3, 'Lieutenant'),
(1, 'Probationary Firefighter');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(4, 'Administrator'),
(3, 'Dispatcher'),
(1, 'Firefighter'),
(2, 'Team Leader');

-- --------------------------------------------------------

--
-- Table structure for table `severity`
--

CREATE TABLE `severity` (
  `id` int(11) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `severity`
--

INSERT INTO `severity` (`id`, `level`) VALUES
(1, 'First Alarm'),
(2, 'Second Alarm'),
(3, 'Third Alarm'),
(4, 'Fourth Alarm'),
(5, 'Fifth Alarm');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Off Duty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `status`) VALUES
(1, 'Pending'),
(2, 'On Duty'),
(3, 'Off Duty');

-- --------------------------------------------------------

--
-- Table structure for table `shift_assign`
--

CREATE TABLE `shift_assign` (
  `shiftID` int(11) NOT NULL,
  `scheduled_timein` time NOT NULL,
  `scheduled_timeout` time NOT NULL,
  `day` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift_assign`
--

INSERT INTO `shift_assign` (`shiftID`, `scheduled_timein`, `scheduled_timeout`, `day`) VALUES
(1, '08:00:00', '08:00:00', 'Monday to Tuesday'),
(2, '08:00:00', '08:00:00', 'Tuesday to Wednesday'),
(3, '08:00:00', '08:00:00', 'Wednesday to Thursday'),
(4, '08:00:00', '08:00:00', 'Thursday to Friday'),
(5, '08:00:00', '08:00:00', 'Friday to Saturday'),
(6, '08:00:00', '08:00:00', 'Saturday to Sunday'),
(7, '08:00:00', '08:00:00', 'Sunday to Monday');

-- --------------------------------------------------------

--
-- Table structure for table `shift_schedule`
--

CREATE TABLE `shift_schedule` (
  `schedule_id` int(11) NOT NULL,
  `PersonnelID` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Off Duty',
  `shift_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift_schedule`
--

INSERT INTO `shift_schedule` (`schedule_id`, `PersonnelID`, `start_time`, `end_time`, `assigned_by`, `assigned_time`, `status`, `shift_day`) VALUES
(2, 3, '08:00:00', '16:00:00', NULL, '2025-03-26 05:34:00', 'On Duty', 'Thursday'),
(5, 19, '13:19:00', '13:20:00', NULL, '2025-03-26 05:20:28', 'On Duty', 'Tuesday'),
(6, 17, '13:31:00', '13:33:00', NULL, '2025-03-26 05:31:40', 'On Duty', 'Wednesday'),
(7, 9, '09:32:00', '09:33:00', NULL, '2025-03-27 01:33:08', 'On Duty', 'Friday'),
(8, 21, '10:15:00', '23:16:00', NULL, '2025-03-28 02:15:28', NULL, 'Monday'),
(9, 21, '11:06:00', '23:06:00', NULL, '2025-03-28 03:06:13', NULL, 'Sunday');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'In progress'),
(3, 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `training_id` int(11) NOT NULL,
  `training_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainings`
--

INSERT INTO `trainings` (`training_id`, `training_name`, `description`, `scheduled_date`) VALUES
(2, 'physical training', 'military type test/training to be held at arca south taguig at 6am to 10am. bring your hygiene and fueling necessities.', '2025-03-08'),
(3, 'mental training - testing', 'for demonstration purposes', '2025-02-28'),
(4, 'testing training only', 'testing training only for demonstration purposes.', '2025-03-23'),
(8, 'endurance training', 'for demonstration purposes', '2025-03-25'),
(9, 'mema training', 'for demo purposes', '2025-03-28'),
(10, 'ngayon', 'ngaypn', '2025-04-03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD KEY `fk_asset_category` (`assetcategory_id`);

--
-- Indexes for table `asset_category`
--
ALTER TABLE `asset_category`
  ADD PRIMARY KEY (`AssetCategory_ID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_shifts` (`shift_id`),
  ADD KEY `fk_attendance_personnel` (`personnel_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD UNIQUE KEY `barangay_name` (`barangay_name`);

--
-- Indexes for table `dispatches`
--
ALTER TABLE `dispatches`
  ADD PRIMARY KEY (`disp_id`);

--
-- Indexes for table `emergency_details`
--
ALTER TABLE `emergency_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispatch_id` (`dispatch_id`),
  ADD KEY `fk_emergency_details_status` (`status`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `fk_severity` (`severity_id`),
  ADD KEY `fk_status` (`status_id`),
  ADD KEY `fk_barangay` (`barangay_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`PersonnelID`),
  ADD KEY `RoleID` (`RoleID`),
  ADD KEY `RankID` (`RankID`),
  ADD KEY `ShiftID` (`ShiftID`),
  ADD KEY `fk_personnel_status` (`personnelstatus_id`);

--
-- Indexes for table `personnelstatus`
--
ALTER TABLE `personnelstatus`
  ADD PRIMARY KEY (`personnelstatus_id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`rank_id`),
  ADD UNIQUE KEY `rank_name` (`rank_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `severity`
--
ALTER TABLE `severity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `shift_assign`
--
ALTER TABLE `shift_assign`
  ADD PRIMARY KEY (`shiftID`);

--
-- Indexes for table `shift_schedule`
--
ALTER TABLE `shift_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `fk_personnel` (`PersonnelID`),
  ADD KEY `fk_assigned_by` (`assigned_by`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`training_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `asset_category`
--
ALTER TABLE `asset_category`
  MODIFY `AssetCategory_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `dispatches`
--
ALTER TABLE `dispatches`
  MODIFY `disp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `emergency_details`
--
ALTER TABLE `emergency_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `PersonnelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `personnelstatus`
--
ALTER TABLE `personnelstatus`
  MODIFY `personnelstatus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `shift_assign`
--
ALTER TABLE `shift_assign`
  MODIFY `shiftID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shift_schedule`
--
ALTER TABLE `shift_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `fk_asset_category` FOREIGN KEY (`assetcategory_id`) REFERENCES `asset_category` (`AssetCategory_ID`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_personnel` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`PersonnelID`),
  ADD CONSTRAINT `fk_shifts` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_details`
--
ALTER TABLE `emergency_details`
  ADD CONSTRAINT `emergency_details_ibfk_1` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`disp_id`),
  ADD CONSTRAINT `fk_emergency_details_status` FOREIGN KEY (`status`) REFERENCES `status` (`status_id`);

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `fk_barangay` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_severity` FOREIGN KEY (`severity_id`) REFERENCES `severity` (`id`),
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`);

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `fk_personnel_status` FOREIGN KEY (`personnelstatus_id`) REFERENCES `personnelstatus` (`personnelstatus_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_ibfk_2` FOREIGN KEY (`RankID`) REFERENCES `ranks` (`rank_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_ibfk_3` FOREIGN KEY (`ShiftID`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;

--
-- Constraints for table `shift_schedule`
--
ALTER TABLE `shift_schedule`
  ADD CONSTRAINT `fk_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `personnel` (`PersonnelID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_personnel` FOREIGN KEY (`PersonnelID`) REFERENCES `personnel` (`PersonnelID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
