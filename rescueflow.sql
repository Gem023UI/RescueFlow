-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 12:43 AM
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
(16, 1, 'bidet', 'malakas', 'Available', '2025-03-11'),
(18, 1, 'bidet', 'mahina na', 'Damaged', '2025-03-01'),
(19, 2, 'water hose', 'malakas, magastos sa tubig', 'In Use', '2025-03-26'),
(20, 7, 'crowbar', 'matibay, pamukpok sa pabigat na kagroup', 'In Use', '2025-03-11'),
(21, 6, 'ladder', 'kaya abutin pati pangarap mo', 'Available', '2025-02-25');

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
(19, 'assets/images/OPERATING SYSTEMS.png'),
(13, 'assets/images/2025.png'),
(14, 'assets/images/OPERATING SYSTEMS.png'),
(12, 'assets/images/2025.png'),
(21, 'assets/images/2025.png'),
(16, 'assets/images/2025.png'),
(18, 'assets/images/2025.png'),
(20, 'assets/images/OPERATING SYSTEMS.png');

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
  `member_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `shift_id` int(11) DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(23, NULL, 'baranggay western bicutan taguig city', 'Firetruck', '2025-03-18 01:25:20', 2);

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
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_details`
--

INSERT INTO `emergency_details` (`id`, `dispatch_id`, `what`, `where`, `why`, `caller_name`, `caller_phone`, `timestamp`) VALUES
(14, 1, 'apoy', 'kiki', 'lu', 'lo', '0909', '2025-03-15 02:06:07'),
(20, NULL, 'jna', 'jna', 'jan', 'kjkj', '0900', '2025-03-16 02:39:25'),
(21, NULL, 'ksi', 'ksi', 'ksi', 'ksii', '123', '2025-03-16 02:44:54'),
(22, NULL, 'ne', 'ne', 'ne', 'ne', '0909009', '2025-03-16 02:47:19'),
(23, NULL, 'li', 'li', 'li', 'li', '09099', '2025-03-16 02:49:55'),
(24, NULL, 'tty', 'ty', 'ty', 'rtrt', '0909', '2025-03-16 02:52:29'),
(25, NULL, 'cv', 'cv', 'cv', 'we', '132', '2025-03-16 02:57:30'),
(26, NULL, 'loh', 'lk', 'lk', 'lk', '123', '2025-03-16 10:02:27'),
(27, NULL, 'ki', 'ki', 'ki', 'ki', '090908', '2025-03-16 10:09:00'),
(28, NULL, 'unog', 'kajs', 'kjskj', 'wkjdk', '9898', '2025-03-16 10:12:20'),
(29, NULL, 'nagkaroon ng malalang sunog', 'sa tup taguig branch', 'sunog malamang', 'flint celetaria', '2123231', '2025-03-20 14:54:10');

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
(52, 'Fire', 2, '', 'Duhat Street, Brgy. Western Bicutan, Taguig City', 'jazmine', '2025-03-19 10:18:06', NULL, 'Electrical Faults', '../uploads/1742350686_438089781_964928398193670_8243827508647878314_n.jpg', '', 19),
(53, 'Fire', 1, '', 'bandang eskinita', 'flint', '2025-03-19 19:15:40', NULL, 'Smoking Indoors', '../uploads/1742382940_WIN_20240118_20_02_58_Pro.jpg', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dispatch_id` int(11) DEFAULT NULL,
  `what` text DEFAULT NULL,
  `where` text DEFAULT NULL,
  `why` text DEFAULT NULL,
  `caller_name` varchar(255) DEFAULT NULL,
  `caller_phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `status`, `created_at`, `dispatch_id`, `what`, `where`, `why`, `caller_name`, `caller_phone`) VALUES
(15, 'New emergency report: What - ki, Where - ki, Why - ki, Caller - ki, Phone - 090908.', 1, '2025-03-16 10:09:00', 27, 'ki', 'ki', 'ki', 'ki', '090908'),
(16, 'New emergency report: What - unog, Where - kajs, Why - kjskj, Caller - wkjdk, Phone - 9898.', 0, '2025-03-16 10:12:20', 28, 'unog', 'kajs', 'kjskj', 'wkjdk', '9898'),
(17, 'New emergency report: What - nagkaroon ng malalang sunog, Where - sa tup taguig branch, Why - sunog malamang, Caller - flint celetaria, Phone - 2123231.', 0, '2025-03-20 14:54:10', 29, 'nagkaroon ng malalang sunog', 'sa tup taguig branch', 'sunog malamang', 'flint celetaria', '2123231');

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
  `Profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`PersonnelID`, `RoleID`, `RankID`, `ShiftID`, `FirstName`, `LastName`, `Email`, `PhoneNumber`, `Password`, `Profile`) VALUES
(3, 4, 5, 1, 'Jemuel ', 'Malaga', 'malagajemuel@gmail.com', '09994617537', '$2y$10$ekR8aP7d9uG/jn6DUHX9WepX9Dmv92j5EA0KZ7jRu5fUrgVO3Q9q2', NULL),
(4, 1, 1, 1, 'Flint Axl', 'Celetaria', 'gemmalfaro023@gmail.com', '09994617539', '$2y$10$pL7d8o9M0Lcv8BALHvvGZuDJ.cF6WVv.D2u7/xuWp7YwyKX.rDcwi', NULL);

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
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` varchar(20) DEFAULT 'Off Duty',
  `shift_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `start_time`, `end_time`, `status`, `shift_day`) VALUES
(1, '00:00:00', '00:00:00', 'Pending', 'Sunday'),
(34, '06:00:00', '06:00:00', 'Off Duty', 'Sunday'),
(35, '06:00:00', '06:00:00', 'Off Duty', 'Monday'),
(36, '06:00:00', '06:00:00', 'Off Duty', 'Tuesday'),
(37, '06:00:00', '06:00:00', 'Off Duty', 'Wednesday'),
(38, '06:00:00', '06:00:00', 'Off Duty', 'Thursday'),
(39, '06:00:00', '06:00:00', 'Off Duty', 'Friday'),
(40, '06:00:00', '06:00:00', 'Off Duty', 'Saturday'),
(41, '06:00:00', '06:00:00', 'On Duty', 'Sunday'),
(42, '06:00:00', '06:00:00', 'On Duty', 'Monday'),
(43, '06:00:00', '06:00:00', 'On Duty', 'Tuesday'),
(44, '06:00:00', '06:00:00', 'On Duty', 'Wednesday'),
(45, '06:00:00', '06:00:00', 'On Duty', 'Thursday'),
(46, '06:00:00', '06:00:00', 'On Duty', 'Friday'),
(47, '06:00:00', '06:00:00', 'On Duty', 'Saturday');

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
(2, 'bimbang', 'qw', '2025-02-14'),
(3, 'bembangan', 'ok', '2025-02-27');

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
  ADD KEY `member_id` (`member_id`),
  ADD KEY `fk_shifts` (`shift_id`);

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
  ADD KEY `dispatch_id` (`dispatch_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`PersonnelID`),
  ADD KEY `RoleID` (`RoleID`),
  ADD KEY `RankID` (`RankID`),
  ADD KEY `ShiftID` (`ShiftID`);

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
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `asset_category`
--
ALTER TABLE `asset_category`
  MODIFY `AssetCategory_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `dispatches`
--
ALTER TABLE `dispatches`
  MODIFY `disp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `emergency_details`
--
ALTER TABLE `emergency_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `PersonnelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `fk_shifts` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_details`
--
ALTER TABLE `emergency_details`
  ADD CONSTRAINT `emergency_details_ibfk_1` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`disp_id`);

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
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_ibfk_2` FOREIGN KEY (`RankID`) REFERENCES `ranks` (`rank_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_ibfk_3` FOREIGN KEY (`ShiftID`) REFERENCES `shifts` (`shift_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
