-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 05:19 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecc`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_code`, `description`, `status`, `created_at`) VALUES
(1, 'Cloud Computing', 'SSA001', '', 'active', '2025-08-29 08:37:57'),
(2, 'Automotive Service Technician', 'SSA002', '', 'active', '2025-08-30 05:25:27'),
(3, 'IIoT', 'SSA003', '', 'active', '2025-08-30 05:25:57'),
(4, 'Designer Mechanical', 'SSA004', '', 'active', '2025-08-30 06:33:00'),
(5, 'Electric Vehicle Technician', 'SSA005', '', 'active', '2025-09-01 09:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','short_answer') NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `negative_points` int(11) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `course_id`, `staff_id`, `question_text`, `question_type`, `points`, `negative_points`, `difficulty`, `attachment_path`, `created_at`) VALUES
(1, 1, 2, 'What does AWS EC2 stand for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(2, 1, 2, 'Which AWS service is primarily used for object storage?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(3, 1, 2, 'What is the maximum size of an object that can be stored in S3?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(4, 1, 2, 'Which AWS service provides a managed relational database?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(5, 1, 2, 'What is AWS IAM used for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(6, 1, 2, 'Which of the following is NOT an AWS availability zone concept?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(7, 1, 2, 'What is the purpose of AWS CloudWatch?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(8, 1, 2, 'Which AWS service is used for content delivery and caching?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(9, 1, 2, 'What does AWS VPC stand for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(10, 1, 2, 'What is the primary goal of DevOps?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(11, 1, 2, 'Which tool is commonly used for version control in DevOps?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(12, 1, 2, 'What is CI/CD?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(13, 1, 2, 'Which tool is primarily used for containerization?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(14, 1, 2, 'What is Infrastructure as Code (IaC)?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(15, 1, 2, 'Which tool is commonly used for configuration management?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(16, 1, 2, 'What is the purpose of Jenkins in DevOps?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(17, 1, 2, 'Which practice involves deploying small, frequent changes?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(18, 1, 2, 'What is a key benefit of using containers in DevOps?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(19, 1, 2, 'Which command is used to list files and directories in Linux?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(20, 1, 2, 'What does the command \'pwd\' do?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(21, 1, 2, 'Which command is used to change directories in Linux?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(22, 1, 2, 'What is the root directory in Linux represented by?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(23, 1, 2, 'Which command is used to view the contents of a file?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(24, 1, 2, 'What does \'chmod\' command do?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(25, 1, 2, 'Which command is used to search for text within files?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(26, 1, 2, 'What does the \'ps\' command show?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(27, 1, 2, 'Which command is used to copy files in Linux?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(28, 1, 2, 'What does IP stand for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(29, 1, 2, 'Which layer of the OSI model does HTTP operate at?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(30, 1, 2, 'What is the default port for HTTP?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(31, 1, 2, 'What is the default port for HTTPS?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(32, 1, 2, 'What does DNS stand for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(33, 1, 2, 'Which protocol is used for secure shell access?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(34, 1, 2, 'What is a subnet mask used for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(35, 1, 2, 'Which command is used to test network connectivity in Linux?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:33:03'),
(36, 3, 4, 'What is the full form of IoT?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(37, 3, 4, 'What is IoT?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(38, 3, 4, 'What is the full form of IIOT?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(39, 3, 4, 'Which of the following is used to capture data from the physical world in IoT devices?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(40, 3, 4, 'Which of the following protocol is used to link all the devices in the IoT?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(41, 3, 4, 'Which programming language is used by Arduino IDE IoT software for writing codes?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(42, 3, 4, 'Arduino UNO is?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(43, 3, 4, 'I2C stands for?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(44, 3, 4, 'What is the full form of IDE in Arduino IDE IoT software?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(45, 3, 4, 'TCP stands for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(46, 3, 4, 'IP stands for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(47, 3, 4, '__________ is an open source electronic platform based on easy to use hardware and software.', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(48, 3, 4, 'RFID stands for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(49, 3, 4, 'A __________ tends to convert physical attribute to an electrical signal.', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(50, 3, 4, 'What is the primary goal of IoT?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(51, 3, 4, 'A __________ tends to convert electrical signal to physical action.', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:42:44'),
(52, 3, 4, 'IoT uses?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(53, 3, 4, 'Which of the following cannot be considered an IoT device?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(54, 3, 4, 'The storage is _____ in IoT.', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(55, 3, 4, 'Which of the following is not a short-range wireless network?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(56, 3, 4, 'A _____ is the component that executes a program in an IoT system.', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(57, 3, 4, 'Which layer is used for wireless connection in IoT devices?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(58, 3, 4, 'Which of the following is not a sensor in IoT?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(59, 3, 4, 'Which of the following is used to reprogram a Bootloader in IoT devices?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(60, 3, 4, 'What does IoT collect?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(61, 3, 4, 'Which of the following is not related to Arduino IDE IoT software?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(62, 3, 4, 'The collection of ____ that connects several devices is called the bus.', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(63, 3, 4, 'What is the main advantage of IIoT?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(64, 3, 4, 'Which communication protocol is widely used in IIoT?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(65, 3, 4, '____ is a security mechanism used in WiFi.', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:42:44'),
(66, 4, 5, 'A system that automates the drafting process with interactive computer graphics is called', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(67, 4, 5, 'Which simple machine converts rotational motion into linear motion?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(68, 4, 5, 'The SI unit of power is:', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(69, 4, 5, 'Which of the following is NOT a type of simple machine?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(70, 4, 5, 'Which tool is used to measure the diameter of a thin wire?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(71, 4, 5, 'Which is the best conductor of heat?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(72, 4, 5, 'Which of these operations is done on a lathe?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(73, 4, 5, 'Which instrument is used to measure internal diameter accurately?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(74, 4, 5, 'Which of these instruments is used to check alignment of machine parts?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(75, 4, 5, 'A micrometer is typically used to measure dimensions in:', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(76, 4, 5, 'What does CNC stand for?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(77, 4, 5, 'Which of the following tools is used for internal threading?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(78, 4, 5, 'Which tool is used to remove material by turning?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(79, 4, 5, 'What is ‘assembly modeling’ in CAD?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(80, 4, 5, 'What does CFD stand for?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(81, 4, 5, 'Why is continuous learning important in CAM?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(82, 4, 5, 'What is the correct formula for the area of a rectangle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(83, 4, 5, 'What is the circumference formula for a circle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 08:54:15'),
(84, 4, 5, 'The formula for the diagonal of a square with side a is:', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(85, 4, 5, 'The surface area of a sphere with radius r is:', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(86, 4, 5, 'The surface area of a cylinder is:', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(87, 4, 5, 'The lateral surface area of a cylinder (without top and bottom) is:', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 08:54:15'),
(88, 4, 5, 'Which tolerance controls the orientation of a feature relative to a datum?', 'multiple_choice', 1, NULL, 'hard', NULL, '2025-09-01 08:54:15'),
(89, 4, 5, 'What does the “RFS” condition mean in GD&T?', 'multiple_choice', 1, NULL, 'hard', NULL, '2025-09-01 08:54:15'),
(90, 4, 5, 'What does “LMC” stand for in GD&T?', 'multiple_choice', 1, NULL, 'hard', NULL, '2025-09-01 08:54:15'),
(91, 2, 3, 'What is the function of the alternator in a vehicle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(92, 2, 3, 'What type of fluid is used in the braking system?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(93, 2, 3, 'Which tool is used to measure the gap of a spark plug?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(94, 2, 3, 'What does OBD stand for in automotive diagnostics?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(95, 2, 3, 'What is the most common cause of an engine misfire?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(96, 2, 3, 'If a vehicle\'s Check Engine Light is on, what should be your first step?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(97, 2, 3, 'A bike is pulling to one side while driving. What could be a possible cause?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(98, 2, 3, 'When replacing brake pads, what should you always check and possibly replace as well?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(99, 2, 3, 'What is the correct order to remove a scooter battery?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(100, 2, 3, 'How often should an engine oil change typically be performed?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(101, 2, 3, 'What does a multimeter measure?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(102, 2, 3, 'What is the purpose of a vehicle\'s fuse box?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(103, 2, 3, 'What does the battery warning light on a vehicle\'s dashboard indicate?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(104, 2, 3, 'What is the purpose of the throttle body in an engine?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(105, 2, 3, 'What could cause a vehicle to have poor fuel efficiency?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(106, 2, 3, 'What is a common symptom of a failing catalytic converter?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(107, 2, 3, 'What is the function of shock absorbers in a vehicle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(108, 2, 3, 'What could be a sign of a worn-out ball joint?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(109, 2, 3, 'When should the wheel alignment be checked and adjusted?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(110, 2, 3, 'What is the main function of the radiator in a vehicle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(111, 2, 3, 'What can cause the engine to overheat?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(112, 2, 3, 'What is the purpose of the thermostat in the cooling system?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(113, 2, 3, 'What is the difference between automatic and manual transmissions?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(114, 2, 3, 'What is a common symptom of a failing transmission?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(115, 2, 3, 'What type of fluid is used in automatic transmissions?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(116, 2, 3, 'What is the purpose of the muffler in the exhaust system?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:08:20'),
(117, 2, 3, 'What could cause excessive exhaust smoke?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(118, 2, 3, 'What is the function of the oxygen sensor in the exhaust system?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(119, 2, 3, 'What is the purpose of ABS (Anti-lock Braking System)?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(120, 2, 3, 'What could cause a spongy feeling when pressing the brake pedal?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(121, 2, 3, 'When should brake pads typically be replaced?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:08:20'),
(122, 5, 6, 'Which battery type is most commonly used in modern electric vehicles?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(123, 5, 6, 'What does \"regenerative braking\" do in an EV?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(124, 5, 6, 'What is the main advantage of an electric vehicle over an internal combustion engine vehicle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(125, 5, 6, 'Which component controls the power flow between the battery and the motor in an EV?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(126, 5, 6, 'What does \"kWh\" stand for in EV specifications?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(127, 5, 6, 'Which of the following is a challenge in EV adoption?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(128, 5, 6, 'What is the function of a Battery Management System (BMS) in EVs?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(129, 5, 6, 'Which part of an electric vehicle converts AC to DC during charging?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(130, 5, 6, 'What is \"torque\" in the context of EV motors?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(131, 5, 6, 'Which country is currently leading in EV adoption worldwide?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(132, 5, 6, 'What is the full form of BEV?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(133, 5, 6, 'Which part stores the electrical energy in an EV?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(134, 5, 6, 'Which of the following is a zero-emission vehicle?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(135, 5, 6, 'What is the unit of resistance?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(136, 5, 6, 'What is the function of a diode?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(137, 5, 6, 'Which component is used to store electrical energy?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(138, 5, 6, 'Ohm\'s Law is:', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(139, 5, 6, 'Which of the following is a passive component?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(140, 5, 6, 'What does a transistor do?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(141, 5, 6, 'In a series circuit, the total resistance is:', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(142, 5, 6, 'A 12 V battery is connected to a 4 Ω resistor. What is the current?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(143, 5, 6, 'What voltage is required to produce a current of 0.5 A through a 20 Ω resistor?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(144, 5, 6, 'If a 9V battery is connected to a circuit with a total resistance of 3Ω, what is the current?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(145, 5, 6, 'You measure a current of 1.5 A through a 6 Ω resistor. What is the voltage drop?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(146, 5, 6, 'A circuit draws 0.25 A from a 5 V battery. What is the resistance of the circuit?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(147, 5, 6, 'In a series circuit, if the current is 2 A and total resistance is 8 Ω, what is the voltage?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(148, 5, 6, 'What is the voltage across a resistor of 10 Ω if the current is 0.1 A?', 'multiple_choice', 1, NULL, 'easy', NULL, '2025-09-01 09:21:01'),
(149, 5, 6, 'A circuit has a voltage of 24 V and a current of 3 A. What is the resistance?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01'),
(150, 5, 6, 'If a current of 4 A passes through a circuit with a resistance of 12 Ω, what is the voltage?', 'multiple_choice', 1, NULL, 'medium', NULL, '2025-09-01 09:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(1, 1, 'Elastic Cloud Computing', 0),
(2, 1, 'Elastic Compute Cloud', 1),
(3, 1, 'Enhanced Cloud Computing', 0),
(4, 1, 'Extended Compute Cloud', 0),
(5, 2, 'EBS', 0),
(6, 2, 'EFS', 0),
(7, 2, 'S3', 1),
(8, 2, 'RDS', 0),
(9, 3, '5 GB', 0),
(10, 3, '5 TB', 1),
(11, 3, '50 GB', 0),
(12, 3, '500 GB', 0),
(13, 4, 'DynamoDB', 0),
(14, 4, 'S3', 0),
(15, 4, 'RDS', 1),
(16, 4, 'ElastiCache', 0),
(17, 5, 'Network management', 0),
(18, 5, 'Identity and Access Management', 1),
(19, 5, 'Instance monitoring', 0),
(20, 5, 'Image management', 0),
(21, 6, 'Multiple data centers in a region', 0),
(22, 6, 'Isolated failure domains', 0),
(23, 6, 'High bandwidth, low latency networking', 0),
(24, 6, 'Shared storage across all zones', 1),
(25, 7, 'Cloud storage', 0),
(26, 7, 'Monitoring and observability', 1),
(27, 7, 'Content delivery', 0),
(28, 7, 'Database management', 0),
(29, 8, 'CloudFront', 1),
(30, 8, 'Route 53', 0),
(31, 8, 'ELB', 0),
(32, 8, 'VPC', 0),
(33, 9, 'Virtual Private Cloud', 1),
(34, 9, 'Virtual Public Cloud', 0),
(35, 9, 'Virtual Processing Center', 0),
(36, 9, 'Virtual Protocol Controller', 0),
(37, 10, 'Faster development only', 0),
(38, 10, 'Better operations only', 0),
(39, 10, 'Collaboration between development and operations', 1),
(40, 10, 'Reduced costs only', 0),
(41, 11, 'Jenkins', 0),
(42, 11, 'Docker', 0),
(43, 11, 'Git', 1),
(44, 11, 'Ansible', 0),
(45, 12, 'Continuous Integration/Continuous Deployment', 1),
(46, 12, 'Code Integration/Code Deployment', 0),
(47, 12, 'Cloud Integration/Cloud Deployment', 0),
(48, 12, 'Container Integration/Container Deployment', 0),
(49, 13, 'Jenkins', 0),
(50, 13, 'Docker', 1),
(51, 13, 'Ansible', 0),
(52, 13, 'Terraform', 0),
(53, 14, 'Writing code for applications', 0),
(54, 14, 'Managing infrastructure through code', 1),
(55, 14, 'Coding on cloud infrastructure', 0),
(56, 14, 'Infrastructure monitoring code', 0),
(57, 15, 'Docker', 0),
(58, 15, 'Kubernetes', 0),
(59, 15, 'Ansible', 1),
(60, 15, 'Git', 0),
(61, 16, 'Container orchestration', 0),
(62, 16, 'Configuration management', 0),
(63, 16, 'Continuous Integration/Continuous Delivery', 1),
(64, 16, 'Infrastructure provisioning', 0),
(65, 17, 'Waterfall deployment', 0),
(66, 17, 'Big bang deployment', 0),
(67, 17, 'Continuous deployment', 1),
(68, 17, 'Manual deployment', 0),
(69, 18, 'Larger application size', 0),
(70, 18, 'Environment consistency', 1),
(71, 18, 'Slower deployment', 0),
(72, 18, 'More complex configuration', 0),
(73, 19, 'dir', 0),
(74, 19, 'list', 0),
(75, 19, 'ls', 1),
(76, 19, 'show', 0),
(77, 20, 'Print working directory', 1),
(78, 20, 'Power down', 0),
(79, 20, 'Print word document', 0),
(80, 20, 'Process working data', 0),
(81, 21, 'change', 0),
(82, 21, 'cd', 1),
(83, 21, 'move', 0),
(84, 21, 'goto', 0),
(85, 22, '', 0),
(86, 22, '/', 1),
(87, 22, '~', 0),
(88, 22, '*', 0),
(89, 23, 'open', 0),
(90, 23, 'view', 0),
(91, 23, 'cat', 1),
(92, 23, 'read', 0),
(93, 24, 'Change mode/permissions', 1),
(94, 24, 'Change directory', 0),
(95, 24, 'Check modifications', 0),
(96, 24, 'Change ownership', 0),
(97, 25, 'find', 0),
(98, 25, 'search', 0),
(99, 25, 'grep', 1),
(100, 25, 'locate', 0),
(101, 26, 'Print statements', 0),
(102, 26, 'Process status', 1),
(103, 26, 'Program source', 0),
(104, 26, 'Port status', 0),
(105, 27, 'copy', 0),
(106, 27, 'cp', 1),
(107, 27, 'duplicate', 0),
(108, 27, 'clone', 0),
(109, 28, 'Internet Provider', 0),
(110, 28, 'Internet Protocol', 1),
(111, 28, 'Internal Protocol', 0),
(112, 28, 'International Protocol', 0),
(113, 29, 'Layer 3 (Network)', 0),
(114, 29, 'Layer 4 (Transport)', 0),
(115, 29, 'Layer 6 (Presentation)', 0),
(116, 29, 'Layer 7 (Application)', 1),
(117, 30, '21', 0),
(118, 30, '22', 0),
(119, 30, '80', 1),
(120, 30, '443', 0),
(121, 31, '80', 0),
(122, 31, '443', 1),
(123, 31, '22', 0),
(124, 31, '21', 0),
(125, 32, 'Dynamic Network Service', 0),
(126, 32, 'Domain Name System', 1),
(127, 32, 'Data Network Security', 0),
(128, 32, 'Distributed Network Service', 0),
(129, 33, 'FTP', 0),
(130, 33, 'HTTP', 0),
(131, 33, 'SSH', 1),
(132, 33, 'SMTP', 0),
(133, 34, 'Network security', 0),
(134, 34, 'Defining network and host portions of an IP address', 1),
(135, 34, 'Encrypting data', 0),
(136, 34, 'Load balancing', 0),
(137, 35, 'test', 0),
(138, 35, 'connect', 0),
(139, 35, 'ping', 1),
(140, 35, 'check', 0),
(141, 36, 'Internet of Technology', 0),
(142, 36, 'Incorporate of Things', 0),
(143, 36, 'Internet of Things', 1),
(144, 36, 'Incorporate of Technology', 0),
(145, 37, 'network of physical objects embedded with sensors', 1),
(146, 37, 'network of virtual objects', 0),
(147, 37, 'network of objects in the ring structure', 0),
(148, 37, 'network of sensors', 0),
(149, 38, 'Index Internet of Things', 0),
(150, 38, 'Incorporate Internet of Things', 0),
(151, 38, 'Industrial Internet of Things', 1),
(152, 38, 'Intense Internet of Things', 0),
(153, 39, 'Sensors', 1),
(154, 39, 'Actuators', 0),
(155, 39, 'Microprocessors', 0),
(156, 39, 'Microcontrollers', 0),
(157, 40, 'HTTP', 0),
(158, 40, 'UDP', 0),
(159, 40, 'Network', 0),
(160, 40, 'TCP/IP', 1),
(161, 41, 'Python', 0),
(162, 41, 'Java', 0),
(163, 41, 'C/C++', 1),
(164, 41, 'JavaScript', 0),
(165, 42, 'Protocol', 0),
(166, 42, 'Network', 0),
(167, 42, 'Software', 0),
(168, 42, 'Hardware device', 1),
(169, 43, 'Internet Integrated Communication', 0),
(170, 43, 'Inter Integrated Communication', 1),
(171, 43, 'Integrated Internet Communication', 0),
(172, 43, 'Internet Instigate Communication', 0),
(173, 44, 'Intra Defence Environment', 0),
(174, 44, 'Intra Development Environment', 0),
(175, 44, 'Integrated Development Environment', 1),
(176, 44, 'Integrated Deployed Environment', 0),
(177, 45, 'transmission control protocol', 1),
(178, 45, 'telecommunication control protocol', 0),
(179, 45, 'temperature control protocol', 0),
(180, 45, 'transmission and communication protocol', 0),
(181, 46, 'intelligent protocol', 0),
(182, 46, 'internet protocol', 1),
(183, 46, 'intercommunication protocol', 0),
(184, 46, 'ideal protocol', 0),
(185, 47, 'servo motor', 0),
(186, 47, 'Arduino', 1),
(187, 47, 'CPU', 0),
(188, 47, 'GPU', 0),
(189, 48, 'radio frequency identification', 1),
(190, 48, 'raspberry pi identification', 0),
(191, 48, 'radius frequency identification', 0),
(192, 48, 'radio flexible information', 0),
(193, 49, 'actuator', 0),
(194, 49, 'compiler', 0),
(195, 49, 'sensor', 1),
(196, 49, 'motors', 0),
(197, 50, 'To enhance global internet speed', 0),
(198, 50, 'To network physical devices', 1),
(199, 50, 'To increase smart phone usage', 0),
(200, 50, 'To improve social media platform', 0),
(201, 51, 'actuator', 1),
(202, 51, 'compiler', 0),
(203, 51, 'sensor', 0),
(204, 51, 'motors', 0),
(205, 52, 'Satellite', 0),
(206, 52, 'cable', 0),
(207, 52, 'Radio identification technology', 1),
(208, 52, 'Broadband', 0),
(209, 53, 'Smartwatch', 0),
(210, 53, 'Andriod Phone', 0),
(211, 53, 'Laptop', 0),
(212, 53, 'Tubelight', 1),
(213, 54, 'limited', 1),
(214, 54, 'unlimited', 0),
(215, 54, 'not available', 0),
(216, 54, 'All of these', 0),
(217, 55, 'Bluetooth', 0),
(218, 55, 'VPN', 1),
(219, 55, 'Wifi', 0),
(220, 55, 'Hotspot', 0),
(221, 56, 'micro converter', 0),
(222, 56, 'microcontroller', 1),
(223, 56, 'microsensor', 0),
(224, 56, 'None', 0),
(225, 57, 'Application layer', 0),
(226, 57, 'Network layer', 0),
(227, 57, 'Data link layer', 1),
(228, 57, 'Transport layer', 0),
(229, 58, 'BMP280', 0),
(230, 58, 'DHT11', 0),
(231, 58, 'Photoresistor', 0),
(232, 58, 'LED', 1),
(233, 59, 'VHDL programming', 0),
(234, 59, 'IDE', 0),
(235, 59, 'ICSP', 1),
(236, 59, 'MANET', 0),
(237, 60, 'Device data', 0),
(238, 60, 'Machine generated data', 1),
(239, 60, 'Sensor data', 0),
(240, 60, 'Human generated data', 0),
(241, 61, 'Serial monitor', 0),
(242, 61, 'Verify', 0),
(243, 61, 'Upload', 0),
(244, 61, 'Terminate', 1),
(245, 62, 'network', 0),
(246, 62, 'lines', 1),
(247, 62, 'cables', 0),
(248, 62, 'None of these', 0),
(249, 63, 'Increased manual labor', 0),
(250, 63, 'Reduced automation', 0),
(251, 63, 'Predictive maintenance and improved efficiency', 1),
(252, 63, 'Limited connectivity', 0),
(253, 64, 'SMTP', 0),
(254, 64, 'MQTT', 1),
(255, 64, 'HTTP', 0),
(256, 64, 'POP3', 0),
(257, 65, 'WPA', 1),
(258, 65, 'HTTP', 0),
(259, 65, 'MLA', 0),
(260, 65, 'None of these', 0),
(261, 66, 'Computer Aided Engineering (CAE)', 0),
(262, 66, 'Computer Aided Design (CAD)', 1),
(263, 66, 'Computer Aided Manufacturing (CAM)', 0),
(264, 66, 'Computer Aided Instruction (CAI)', 0),
(265, 67, 'Pulley', 0),
(266, 67, 'Gear', 0),
(267, 67, 'Screw', 1),
(268, 67, 'Lever', 0),
(269, 68, 'Joule', 0),
(270, 68, 'Newton', 0),
(271, 68, 'Watt', 1),
(272, 68, 'Pascal', 0),
(273, 69, 'Pulley', 0),
(274, 69, 'Screw', 0),
(275, 69, 'Gear', 1),
(276, 69, 'Lever', 0),
(277, 70, 'Ruler', 0),
(278, 70, 'Micrometer', 1),
(279, 70, 'Vernier caliper', 0),
(280, 70, 'Try square', 0),
(281, 71, 'Aluminum', 0),
(282, 71, 'Steel', 0),
(283, 71, 'Copper', 1),
(284, 71, 'Lead', 0),
(285, 72, 'Casting', 0),
(286, 72, 'Turning', 1),
(287, 72, 'Forging', 0),
(288, 72, 'Drilling', 0),
(289, 73, 'Micrometer', 0),
(290, 73, 'Screw gauge', 0),
(291, 73, 'Vernier caliper', 1),
(292, 73, 'Ruler', 0),
(293, 74, 'Micrometer', 0),
(294, 74, 'Spirit level', 1),
(295, 74, 'Try square', 0),
(296, 74, 'Vernier caliper', 0),
(297, 75, 'Meters', 0),
(298, 75, 'Centimeters', 0),
(299, 75, 'Millimeters', 0),
(300, 75, 'Microns', 1),
(301, 76, 'Central Numeric Control', 0),
(302, 76, 'Coded Numeric Command', 0),
(303, 76, 'Computerized Numerical Control', 1),
(304, 76, 'Control Numeric Computer', 0),
(305, 77, 'Die', 0),
(306, 77, 'Tap', 1),
(307, 77, 'File', 0),
(308, 77, 'Broach', 0),
(309, 78, 'Drill', 0),
(310, 78, 'Lathe', 1),
(311, 78, 'Milling machine', 0),
(312, 78, 'Grinder', 0),
(313, 79, 'Modeling a single part', 0),
(314, 79, 'Combining multiple parts to create a complete product', 1),
(315, 79, 'Creating 2D sketches', 0),
(316, 79, 'Simulating material properties', 0),
(317, 80, 'Computational Fluid Dynamics', 1),
(318, 80, 'Computerized Fabrication Design', 0),
(319, 80, 'Complex Finite Design', 0),
(320, 80, 'Computer Fluid Design', 0),
(321, 81, 'Because machining technologies and software keep evolving', 1),
(322, 81, 'Not important after basic training', 0),
(323, 81, 'Only needed for software developers', 0),
(324, 81, 'Not relevant for experienced users', 0),
(325, 82, 'A = l + w', 0),
(326, 82, 'A = l × w', 1),
(327, 82, 'A = 2(l + w)', 0),
(328, 82, 'A = 1/2 l w', 0),
(329, 83, 'C = πr^2', 0),
(330, 83, 'C = 2πr', 1),
(331, 83, 'C = πd^2', 0),
(332, 83, 'C = 1/2 πr', 0),
(333, 84, 'd = a', 0),
(334, 84, 'd = a√2', 1),
(335, 84, 'd = 2a', 0),
(336, 84, 'd = a/2', 0),
(337, 85, '4πr^2', 1),
(338, 85, '2πr^2', 0),
(339, 85, 'πr^2', 0),
(340, 85, '4/3 πr^3', 0),
(341, 86, '2πrh', 0),
(342, 86, '2πr^2 + 2πrh', 1),
(343, 86, 'πr^2h', 0),
(344, 86, '4πr^2', 0),
(345, 87, '2πr^2', 0),
(346, 87, '2πrh', 1),
(347, 87, 'πr^2h', 0),
(348, 87, 'πrh', 0),
(349, 88, 'Flatness', 0),
(350, 88, 'Parallelism', 1),
(351, 88, 'Position', 0),
(352, 88, 'Circularity', 0),
(353, 89, 'Regardless of Feature Size', 1),
(354, 89, 'Required Feature Size', 0),
(355, 89, 'Reference Feature Size', 0),
(356, 89, 'Radius Feature Size', 0),
(357, 90, 'Least Material Condition', 1),
(358, 90, 'Largest Material Condition', 0),
(359, 90, 'Linear Measurement Control', 0),
(360, 90, 'Limited Material Condition', 0),
(361, 91, 'To start the engine', 0),
(362, 91, 'To charge the battery and power the electrical system', 1),
(363, 91, 'To cool the engine', 0),
(364, 91, 'To provide fuel to the engine', 0),
(365, 92, 'Engine oil', 0),
(366, 92, 'Transmission fluid', 0),
(367, 92, 'Brake fluid', 1),
(368, 92, 'Antifreeze', 0),
(369, 93, 'Torque wrench', 0),
(370, 93, 'Feeler gauge', 1),
(371, 93, 'Micrometer', 0),
(372, 93, 'Caliper', 0),
(373, 94, 'Onboard Diagnostics', 1),
(374, 94, 'Overhead Drive', 0),
(375, 94, 'Open Brake Differential', 0),
(376, 94, 'Output Bearing Diameter', 0),
(377, 95, 'Faulty oxygen sensor', 0),
(378, 95, 'Worn spark plugs', 1),
(379, 95, 'Low engine oil', 0),
(380, 95, 'Blocked radiator', 0),
(381, 96, 'Replace the engine', 0),
(382, 96, 'Reset the battery', 0),
(383, 96, 'Use an OBD-II scanner to read the error codes', 1),
(384, 96, 'Replace the spark plugs', 0),
(385, 97, 'Low engine oil', 0),
(386, 97, 'Misaligned wheels', 1),
(387, 97, 'Faulty alternator', 0),
(388, 97, 'Worn spark plugs', 0),
(389, 98, 'Fuel injectors', 0),
(390, 98, 'Brake fluid', 0),
(391, 98, 'Brake rotors/discs', 1),
(392, 98, 'Alternator', 0),
(393, 99, 'Positive terminal first, then negative', 0),
(394, 99, 'Negative terminal first, then positive', 1),
(395, 99, 'Both terminals simultaneously', 0),
(396, 99, 'It doesn\'t matter which terminal is removed first', 0),
(397, 100, 'Every 1,000 miles', 0),
(398, 100, 'Every 3,000-5,000 miles', 1),
(399, 100, 'Every 10,000 miles', 0),
(400, 100, 'Once a year', 0),
(401, 101, 'Temperature', 0),
(402, 101, 'Electrical voltage, current, and resistance', 1),
(403, 101, 'Tire pressure', 0),
(404, 101, 'Fluid levels', 0),
(405, 102, 'To store spare parts', 0),
(406, 102, 'To regulate engine temperature', 0),
(407, 102, 'To protect the electrical circuits from overcurrent', 1),
(408, 102, 'To control the fuel injection system', 0),
(409, 103, 'Low fuel level', 0),
(410, 103, 'High engine temperature', 0),
(411, 103, 'An issue with the battery or charging system', 1),
(412, 103, 'Faulty brake system', 0),
(413, 104, 'To pump fuel to the engine', 0),
(414, 104, 'To control the amount of air entering the engine', 1),
(415, 104, 'To regulate engine temperature', 0),
(416, 104, 'To provide lubrication to engine components', 0),
(417, 105, 'Clean air filter', 0),
(418, 105, 'Properly inflated tires', 0),
(419, 105, 'Faulty oxygen sensor', 1),
(420, 105, 'Fresh engine oil', 0),
(421, 106, 'Low tire pressure', 0),
(422, 106, 'Engine overheating', 0),
(423, 106, 'Reduced engine performance and increased emissions', 1),
(424, 106, 'Faulty spark plugs', 0),
(425, 107, 'To provide power to the wheels', 0),
(426, 107, 'To absorb and dampen road impact', 1),
(427, 107, 'To control the fuel injection', 0),
(428, 107, 'To lubricate suspension components', 0),
(429, 108, 'Engine misfire', 0),
(430, 108, 'Steering wheel vibration or clunking noise', 1),
(431, 108, 'Low brake fluid', 0),
(432, 108, 'Overheating engine', 0),
(433, 109, 'Every 1,000 miles', 0),
(434, 109, 'Only when new tires are installed', 0),
(435, 109, 'If the vehicle pulls to one side or after hitting a large pothole', 1),
(436, 109, 'Every time the oil is changed', 0),
(437, 110, 'To heat the engine', 0),
(438, 110, 'To cool the engine by dissipating heat', 1),
(439, 110, 'To filter the engine oil', 0),
(440, 110, 'To increase fuel efficiency', 0),
(441, 111, 'Low coolant level', 1),
(442, 111, 'New spark plugs', 0),
(443, 111, 'Clean air filter', 0),
(444, 111, 'Properly inflated tires', 0),
(445, 112, 'To measure oil pressure', 0),
(446, 112, 'To control the flow of coolant to the engine', 1),
(447, 112, 'To regulate fuel injection', 0),
(448, 112, 'To monitor battery voltage', 0),
(449, 113, 'Automatic transmissions require the driver to change gears manually', 0),
(450, 113, 'Manual transmissions change gears automatically', 0),
(451, 113, 'Automatic transmissions change gears automatically, while manual transmissions require the driver to change gears manually', 1),
(452, 113, 'There is no difference', 0),
(453, 114, 'Engine misfire', 0),
(454, 114, 'Slipping gears or difficulty shifting', 1),
(455, 114, 'Low tire pressure', 0),
(456, 114, 'Faulty alternator', 0),
(457, 115, 'Engine oil', 0),
(458, 115, 'Brake fluid', 0),
(459, 115, 'Transmission fluid', 1),
(460, 115, 'Antifreeze', 0),
(461, 116, 'To increase engine power', 0),
(462, 116, 'To reduce exhaust noise', 1),
(463, 116, 'To cool the engine', 0),
(464, 116, 'To filter the air', 0),
(465, 117, 'Clean spark plugs', 0),
(466, 117, 'Proper fuel mixture', 0),
(467, 117, 'Burning oil or coolant entering the combustion chamber', 1),
(468, 117, 'Low tire pressure', 0),
(469, 118, 'To measure the amount of oxygen in the exhaust gases', 1),
(470, 118, 'To regulate the engine temperature', 0),
(471, 118, 'To control the brake system', 0),
(472, 118, 'To measure fuel level', 0),
(473, 119, 'To increase engine power', 0),
(474, 119, 'To prevent the wheels from locking during braking', 1),
(475, 119, 'To reduce fuel consumption', 0),
(476, 119, 'To improve tire pressure', 0),
(477, 120, 'Low engine oil', 0),
(478, 120, 'Air in the brake lines', 1),
(479, 120, 'Faulty spark plugs', 0),
(480, 120, 'Low coolant level', 0),
(481, 121, 'Every 1,000 miles', 0),
(482, 121, 'When they are worn to a specified thickness', 1),
(483, 121, 'Once a year', 0),
(484, 121, 'Every 50,000 miles', 0),
(485, 122, 'Lead-acid', 0),
(486, 122, 'Nickel-Cadmium (Ni-Cd)', 0),
(487, 122, 'Lithium-ion (Li-ion)', 1),
(488, 122, 'Alkaline', 0),
(489, 123, 'Increases speed', 0),
(490, 123, 'Charges the battery while braking', 1),
(491, 123, 'Warms the motor', 0),
(492, 123, 'Converts battery power to kinetic energy', 0),
(493, 124, 'Higher fuel cost', 0),
(494, 124, 'More CO₂ emissions', 0),
(495, 124, 'Lower maintenance and zero emissions', 1),
(496, 124, 'No need for charging', 0),
(497, 125, 'Alternator', 0),
(498, 125, 'Inverter/controller', 1),
(499, 125, 'Carburetor', 0),
(500, 125, 'Radiator', 0),
(501, 126, 'Kilowatt per hour', 0),
(502, 126, 'Kilowatt-hour', 1),
(503, 126, 'Kinetic Watt-hour', 0),
(504, 126, 'Kilo heat watt', 0),
(505, 127, 'Low fuel efficiency', 0),
(506, 127, 'High CO₂ emission', 0),
(507, 127, 'Limited charging infrastructure', 1),
(508, 127, 'Expensive petrol cost', 0),
(509, 128, 'Control vehicle speed', 0),
(510, 128, 'Monitor and protect the battery', 1),
(511, 128, 'Start the engine', 0),
(512, 128, 'Pump coolant', 0),
(513, 129, 'Inverter', 0),
(514, 129, 'Converter', 0),
(515, 129, 'On-board charger', 1),
(516, 129, 'Controller', 0),
(517, 130, 'Speed of the motor', 0),
(518, 130, 'Amount of fuel consumed', 0),
(519, 130, 'Rotational force produced by the motor', 1),
(520, 130, 'Battery charge level', 0),
(521, 131, 'USA', 0),
(522, 131, 'China', 1),
(523, 131, 'India', 0),
(524, 131, 'Germany', 0),
(525, 132, 'Battery Efficient Vehicle', 0),
(526, 132, 'Battery Emission Vehicle', 0),
(527, 132, 'Battery Electric Vehicle', 1),
(528, 132, 'Balanced Electric Vehicle', 0),
(529, 133, 'Alternator', 0),
(530, 133, 'Capacitor', 0),
(531, 133, 'Battery Pack', 1),
(532, 133, 'Fuel Tank', 0),
(533, 134, 'Petrol car', 0),
(534, 134, 'Diesel bus', 0),
(535, 134, 'Hybrid vehicle', 0),
(536, 134, 'Battery Electric Vehicle', 1),
(537, 135, 'Ampere', 0),
(538, 135, 'Volt', 0),
(539, 135, 'Ohm', 1),
(540, 135, 'Watt', 0),
(541, 136, 'Store charge', 0),
(542, 136, 'Block AC and pass DC', 0),
(543, 136, 'Allow current in one direction', 1),
(544, 136, 'Amplify signals', 0),
(545, 137, 'Resistor', 0),
(546, 137, 'Diode', 0),
(547, 137, 'Capacitor', 1),
(548, 137, 'Transistor', 0),
(549, 138, 'V=IR', 1),
(550, 138, 'P=IV', 0),
(551, 138, 'R=V/P', 0),
(552, 138, 'I=P×V', 0),
(553, 139, 'Transistor', 0),
(554, 139, 'Diode', 0),
(555, 139, 'Capacitor', 1),
(556, 139, 'Op-amp', 0),
(557, 140, 'Stores charge', 0),
(558, 140, 'Controls current flow or amplifies signals', 1),
(559, 140, 'Converts AC to DC', 0),
(560, 140, 'Measures resistance', 0),
(561, 141, 'Equal to the smallest resistor', 0),
(562, 141, 'The average of all resistors', 0),
(563, 141, 'The sum of all resistors', 1),
(564, 141, 'Zero', 0),
(565, 142, '2 A', 0),
(566, 142, '3 A', 1),
(567, 142, '4 A', 0),
(568, 142, '48 A', 0),
(569, 143, '10 V', 1),
(570, 143, '15 V', 0),
(571, 143, '20 V', 0),
(572, 143, '5 V', 0),
(573, 144, '3 A', 1),
(574, 144, '2 A', 0),
(575, 144, '6 A', 0),
(576, 144, '12 A', 0),
(577, 145, '6 V', 0),
(578, 145, '7.5 V', 0),
(579, 145, '9 V', 1),
(580, 145, '12 V', 0),
(581, 146, '20 Ω', 1),
(582, 146, '25 Ω', 0),
(583, 146, '30 Ω', 0),
(584, 146, '15 Ω', 0),
(585, 147, '10 V', 0),
(586, 147, '8 V', 0),
(587, 147, '16 V', 1),
(588, 147, '4 V', 0),
(589, 148, '1 V', 1),
(590, 148, '10 V', 0),
(591, 148, '0.1 V', 0),
(592, 148, '100 V', 0),
(593, 149, '8 Ω', 1),
(594, 149, '9 Ω', 0),
(595, 149, '7 Ω', 0),
(596, 149, '6 Ω', 0),
(597, 150, '48 V', 1),
(598, 150, '16 V', 0),
(599, 150, '8 V', 0),
(600, 150, '12 V', 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff_courses`
--

CREATE TABLE `staff_courses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_courses`
--

INSERT INTO `staff_courses` (`id`, `user_id`, `course_id`) VALUES
(1, 2, 1),
(3, 4, 3),
(5, 3, 2),
(6, 5, 4),
(7, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `email`, `password`, `status`, `created_at`) VALUES
(3, 'student1', 'student1@gmail.com', '$2y$10$VhtHFWUCaFZYT2TqlYrZ9OI9QREm0T8XKxY16ADtmKUN6jnFLjCNy', 'active', '2025-09-02 10:39:05'),
(4, 'student2', 'student2@gmail.com', '$2y$10$QcBV7jDeaurzbR1BHc.tYeUPZ73MiIEF46aJjhszgJfDi9GrqiB3q', 'active', '2025-09-02 10:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `marks_awarded` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `attempt_id`, `question_id`, `selected_option_id`, `answer_text`, `marks_awarded`) VALUES
(1, 1, 23, 91, NULL, 0),
(2, 1, 6, 23, NULL, 0),
(3, 1, 33, 131, NULL, 1),
(4, 1, 19, 76, NULL, 0),
(5, 1, 10, 40, NULL, 1),
(6, 1, 16, 63, NULL, 0),
(7, 1, 35, 137, NULL, 0),
(8, 1, 11, 43, NULL, 0),
(9, 1, 25, 99, NULL, 0),
(10, 1, 20, 79, NULL, 1),
(11, 1, 1, 3, NULL, 1),
(12, 1, 15, 58, NULL, 1),
(13, 1, 27, 107, NULL, 0),
(14, 1, 4, 14, NULL, 0),
(15, 1, 31, 123, NULL, 0),
(16, 1, 29, 116, NULL, 0),
(17, 1, 5, 19, NULL, 0),
(18, 1, 26, 103, NULL, 0),
(19, 1, 17, 67, NULL, 0),
(20, 1, 32, 127, NULL, 0),
(21, 1, 13, 51, NULL, 0),
(22, 1, 24, 94, NULL, 1),
(23, 1, 3, 11, NULL, 0),
(24, 1, 2, 6, NULL, 1),
(25, 1, 9, 34, NULL, 0),
(26, 1, 21, 81, NULL, 0),
(27, 1, 14, 54, NULL, 0),
(28, 1, 34, 135, NULL, 1),
(29, 1, 18, 71, NULL, 0),
(30, 1, 22, 87, NULL, 0),
(31, 1, 8, 30, NULL, 0),
(32, 1, 7, 26, NULL, 0),
(33, 1, 30, 118, NULL, 0),
(34, 1, 12, 46, NULL, 0),
(35, 1, 28, 109, NULL, 0),
(36, 2, 58, 230, NULL, 0),
(37, 2, 41, 163, NULL, 1),
(38, 2, 45, 178, NULL, 0),
(39, 2, 65, 258, NULL, 0),
(40, 2, 47, 187, NULL, 0),
(41, 2, 60, 239, NULL, 0),
(42, 2, 61, 243, NULL, 0),
(43, 2, 51, 202, NULL, 0),
(44, 2, 57, 227, NULL, 1),
(45, 2, 59, 236, NULL, 0),
(46, 2, 40, 157, NULL, 0),
(47, 2, 55, 217, NULL, 0),
(48, 2, 54, 213, NULL, 1),
(49, 2, 48, 191, NULL, 0),
(50, 2, 44, 174, NULL, 0),
(51, 2, 53, 212, NULL, 1),
(52, 2, 64, 254, NULL, 1),
(53, 2, 63, 250, NULL, 0),
(54, 2, 50, 198, NULL, 1),
(55, 2, 42, 168, NULL, 1),
(56, 2, 36, 144, NULL, 0),
(57, 2, 38, 152, NULL, 0),
(58, 2, 39, 153, NULL, 1),
(59, 2, 46, 181, NULL, 0),
(60, 2, 52, 207, NULL, 1),
(61, 2, 43, 171, NULL, 0),
(62, 2, 62, 248, NULL, 0),
(63, 2, 49, 195, NULL, 1),
(64, 2, 37, 148, NULL, 0),
(65, 2, 56, 221, NULL, 0),
(66, 3, 67, 267, NULL, 1),
(67, 3, 86, 341, NULL, 0),
(68, 3, 74, 293, NULL, 0),
(69, 3, 88, 350, NULL, 1),
(70, 3, 73, 289, NULL, 0),
(71, 3, 71, 281, NULL, 0),
(72, 3, 78, 310, NULL, 1),
(73, 3, 66, 262, NULL, 1),
(74, 3, 70, 279, NULL, 0),
(75, 3, 72, 287, NULL, 0),
(76, 3, 82, 327, NULL, 0),
(77, 3, 85, 338, NULL, 0),
(78, 3, 77, 306, NULL, 1),
(79, 3, 76, 303, NULL, 1),
(80, 3, 75, 298, NULL, 0),
(81, 3, 80, 319, NULL, 0),
(82, 3, 87, 347, NULL, 0),
(83, 3, 84, 335, NULL, 0),
(84, 3, 90, 359, NULL, 0),
(85, 3, 68, 271, NULL, 1),
(86, 3, 83, 329, NULL, 0),
(87, 3, 81, 321, NULL, 1),
(88, 3, 79, 315, NULL, 0),
(89, 3, 89, 354, NULL, 0),
(90, 3, 69, 275, NULL, 1),
(91, 4, 121, 482, NULL, 1),
(92, 4, 106, 422, NULL, 0),
(93, 4, 96, 384, NULL, 0),
(94, 4, 98, 391, NULL, 1),
(95, 4, 101, 402, NULL, 1),
(96, 4, 94, 375, NULL, 0),
(97, 4, 108, 430, NULL, 1),
(98, 4, 113, 451, NULL, 1),
(99, 4, 109, 434, NULL, 0),
(100, 4, 118, 471, NULL, 0),
(101, 4, 115, 459, NULL, 1),
(102, 4, 112, 446, NULL, 1),
(103, 4, 100, 398, NULL, 1),
(104, 4, 110, 438, NULL, 1),
(105, 4, 104, 414, NULL, 1),
(106, 4, 102, 406, NULL, 0),
(107, 4, 91, 362, NULL, 1),
(108, 4, 111, 443, NULL, 0),
(109, 4, 116, 464, NULL, 0),
(110, 4, 120, 479, NULL, 0),
(111, 4, 93, 372, NULL, 0),
(112, 4, 92, 366, NULL, 0),
(113, 4, 97, 386, NULL, 1),
(114, 4, 95, 379, NULL, 0),
(115, 4, 114, 455, NULL, 0),
(116, 4, 99, 393, NULL, 0),
(117, 4, 103, 412, NULL, 0),
(118, 4, 105, 420, NULL, 0),
(119, 4, 117, 468, NULL, 0),
(120, 4, 107, 428, NULL, 0),
(121, 4, 119, 476, NULL, 0),
(122, 5, 142, 566, NULL, 1),
(123, 5, 135, 540, NULL, 0),
(124, 5, 136, 542, NULL, 0),
(125, 5, 146, 581, NULL, 1),
(126, 5, 125, 497, NULL, 0),
(127, 5, 131, 523, NULL, 0),
(128, 5, 144, 573, NULL, 1),
(129, 5, 126, 501, NULL, 0),
(130, 5, 138, 552, NULL, 0),
(131, 5, 143, 571, NULL, 0),
(132, 5, 145, 577, NULL, 0),
(133, 5, 128, 512, NULL, 0),
(134, 5, 147, 588, NULL, 0),
(135, 5, 141, 563, NULL, 1),
(136, 5, 134, 536, NULL, 1),
(137, 5, 149, 593, NULL, 1),
(138, 5, 124, 494, NULL, 0),
(139, 5, 139, 555, NULL, 1),
(140, 5, 122, 487, NULL, 1),
(141, 5, 129, 515, NULL, 1),
(142, 5, 148, 591, NULL, 0),
(143, 5, 150, 599, NULL, 0),
(144, 5, 133, 530, NULL, 0),
(145, 5, 140, 559, NULL, 0),
(146, 5, 137, 547, NULL, 1),
(147, 5, 130, 519, NULL, 1),
(148, 5, 132, 527, NULL, 1),
(149, 5, 127, 507, NULL, 1),
(150, 5, 123, 489, NULL, 0),
(151, 1, 25, 98, NULL, 0),
(152, 1, 34, 134, NULL, 1),
(153, 1, 17, 66, NULL, 0),
(154, 1, 20, 77, NULL, 1),
(155, 1, 23, 90, NULL, 0),
(156, 1, 22, 87, NULL, 0),
(157, 1, 26, 103, NULL, 0),
(158, 1, 27, 107, NULL, 0),
(159, 1, 19, 76, NULL, 0),
(160, 1, 3, 11, NULL, 0),
(161, 1, 13, 51, NULL, 0),
(162, 1, 18, 71, NULL, 0),
(163, 1, 31, 121, NULL, 0),
(164, 1, 4, 13, NULL, 0),
(165, 1, 30, 118, NULL, 0),
(166, 1, 2, 7, NULL, 1),
(167, 1, 11, 44, NULL, 0),
(168, 1, 16, 64, NULL, 0),
(169, 1, 8, 32, NULL, 0),
(170, 1, 21, 84, NULL, 0),
(171, 1, 5, 19, NULL, 0),
(172, 1, 14, 55, NULL, 0),
(173, 1, 7, 25, NULL, 0),
(174, 1, 24, 93, NULL, 1),
(175, 1, 12, 46, NULL, 0),
(176, 1, 35, 137, NULL, 0),
(177, 1, 1, 2, NULL, 1),
(178, 1, 29, 113, NULL, 0),
(179, 1, 10, 39, NULL, 1),
(180, 1, 6, 22, NULL, 0),
(181, 1, 33, 131, NULL, 1),
(182, 1, 28, 111, NULL, 0),
(183, 1, 15, 59, NULL, 1),
(184, 1, 32, 127, NULL, 0),
(185, 1, 9, 34, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`id`, `student_id`, `course_id`) VALUES
(1, 3, 1),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_test_attempts`
--

CREATE TABLE `student_test_attempts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `status` enum('in_progress','completed','graded') NOT NULL,
  `graded_by` int(11) DEFAULT NULL,
  `is_resumable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_test_attempts`
--

INSERT INTO `student_test_attempts` (`id`, `student_id`, `test_id`, `start_time`, `end_time`, `score`, `status`, `graded_by`, `is_resumable`) VALUES
(1, 1, 1, '2025-09-02 10:24:28', '2025-09-02 10:26:08', 17, 'graded', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `test_title` varchar(255) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `course_id`, `staff_id`, `test_title`, `duration_minutes`, `settings`, `status`, `created_at`) VALUES
(1, 1, 2, 'Eligibility Test for Cloud Computing', 10, '{\"randomize_questions\":1,\"one_question_per_page\":0,\"enable_negative_marking\":0}', 'published', '2025-09-01 08:34:35'),
(2, 3, 4, 'Eligibility Test for IIoT', 20, '{\"randomize_questions\":1,\"one_question_per_page\":0,\"enable_negative_marking\":0}', 'published', '2025-09-01 08:45:31'),
(3, 4, 5, 'Eligibility Test for Designer Mechanical ', 10, '{\"randomize_questions\":1,\"one_question_per_page\":0,\"enable_negative_marking\":0}', 'published', '2025-09-01 08:57:39'),
(4, 2, 3, 'Eligibility Test for Automotive Service Technician', 10, '{\"randomize_questions\":1,\"one_question_per_page\":0,\"enable_negative_marking\":0}', 'published', '2025-09-01 09:09:46'),
(5, 5, 6, 'Eligibility Test for Electric Vehicle Technician', 10, '{\"randomize_questions\":1,\"one_question_per_page\":0,\"enable_negative_marking\":0}', 'published', '2025-09-01 09:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_questions`
--

INSERT INTO `test_questions` (`id`, `test_id`, `question_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 2, 36),
(37, 2, 37),
(38, 2, 38),
(39, 2, 39),
(40, 2, 40),
(41, 2, 41),
(42, 2, 42),
(43, 2, 43),
(44, 2, 44),
(45, 2, 45),
(46, 2, 46),
(47, 2, 47),
(48, 2, 48),
(49, 2, 49),
(50, 2, 50),
(51, 2, 51),
(52, 2, 52),
(53, 2, 53),
(54, 2, 54),
(55, 2, 55),
(56, 2, 56),
(57, 2, 57),
(58, 2, 58),
(59, 2, 59),
(60, 2, 60),
(61, 2, 61),
(62, 2, 62),
(63, 2, 63),
(64, 2, 64),
(65, 2, 65),
(66, 3, 66),
(67, 3, 67),
(68, 3, 68),
(69, 3, 69),
(70, 3, 70),
(71, 3, 71),
(72, 3, 72),
(73, 3, 73),
(74, 3, 74),
(75, 3, 75),
(76, 3, 76),
(77, 3, 77),
(78, 3, 78),
(79, 3, 79),
(80, 3, 80),
(81, 3, 81),
(82, 3, 82),
(83, 3, 83),
(84, 3, 84),
(85, 3, 85),
(86, 3, 86),
(87, 3, 87),
(88, 3, 88),
(89, 3, 89),
(90, 3, 90),
(91, 4, 91),
(92, 4, 92),
(93, 4, 93),
(94, 4, 94),
(95, 4, 95),
(96, 4, 96),
(97, 4, 97),
(98, 4, 98),
(99, 4, 99),
(100, 4, 100),
(101, 4, 101),
(102, 4, 102),
(103, 4, 103),
(104, 4, 104),
(105, 4, 105),
(106, 4, 106),
(107, 4, 107),
(108, 4, 108),
(109, 4, 109),
(110, 4, 110),
(111, 4, 111),
(112, 4, 112),
(113, 4, 113),
(114, 4, 114),
(115, 4, 115),
(116, 4, 116),
(117, 4, 117),
(118, 4, 118),
(119, 4, 119),
(120, 4, 120),
(121, 4, 121),
(122, 5, 122),
(123, 5, 123),
(124, 5, 124),
(125, 5, 125),
(126, 5, 126),
(127, 5, 127),
(128, 5, 128),
(129, 5, 129),
(130, 5, 130),
(131, 5, 131),
(132, 5, 132),
(133, 5, 133),
(134, 5, 134),
(135, 5, 135),
(136, 5, 136),
(137, 5, 137),
(138, 5, 138),
(139, 5, 139),
(140, 5, 140),
(141, 5, 141),
(142, 5, 142),
(143, 5, 143),
(144, 5, 144),
(145, 5, 145),
(146, 5, 146),
(147, 5, 147),
(148, 5, 148),
(149, 5, 149),
(150, 5, 150),
(151, 6, 36),
(152, 6, 37),
(153, 7, 36),
(154, 7, 37),
(155, 7, 38),
(156, 7, 39),
(157, 7, 40),
(158, 7, 41);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', '$2a$12$kXPJoihUM5XV7fSEIDwTFuEusjK04j6jyMuQkyx3EtNc8aCb75usq'(admin123), 'admin', '2025-08-22 17:54:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_courses`
--
ALTER TABLE `staff_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_course_unique` (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student_test_attempts`
--
ALTER TABLE `student_test_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=601;

--
-- AUTO_INCREMENT for table `staff_courses`
--
ALTER TABLE `staff_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_test_attempts`
--
ALTER TABLE `student_test_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_test_attempts`
--
ALTER TABLE `student_test_attempts`
  ADD CONSTRAINT `student_test_attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_test_attempts_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
