-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2021 at 03:46 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio_2024_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_types`
--

CREATE TABLE `project_types` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project_types`
--

INSERT INTO `project_types` (`id`, `name`, `description`) VALUES
(1, 'Art', 'Graphic art, traditional and CG animation projects'),
(2, 'Design', 'UI/UX Design projects'),
(3, 'Development', 'Front end and backend projects');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `project_url` varchar(256) NULL,
  `image_path_1` varchar(256) NULL,
  `image_path_2` varchar(256) NULL,
  `type_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `project_url`, `type_id`, `created`, `modified`) VALUES
(1, 'International Code Council', 'Frontend and backend development on Wordpress', 'https://www.iccsafe.org', 3, '2024-03-01 01:12:26', '2024-03-01 17:12:26'),
(2, 'Preferred Provider', 'Frontend and backend development on Joomla', 'https://ppp.iccsafe.org', 3, '2024-03-01 01:12:34', '2024-03-01 17:13:26'),
(3, 'National Louis University', 'Frontend and backend development.', 'https://www.nl.edu', 3, '2024-03-01 01:18:09', '2024-03-01 01:18:09'),
(4, 'Chipotle', 'Frontend and backend development.', 'NULL', 3, '2024-03-08 11:23:33', '2024-03-08 11:34:42'),
(5, 'ICC Digital Codes App', 'UI/UX Design', 'NULL', 2, '2024-03-09 12:09:08', '2024-03-09 12:09:08'),
(6, 'San Diego Soccer Referees', 'UI/UX Design and theme design', 'https://sdsrarefs.org', 2, '2024-03-09 12:11:18', '2024-03-09 12:11:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_types`
--
ALTER TABLE `project_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_types`
--
ALTER TABLE `project_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
