-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 04, 2019 at 06:33 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `rinhapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `rinhas`
--

CREATE TABLE `rinhas` (
  `_id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `team1` varchar(255) NOT NULL,
  `team2` varchar(255) NOT NULL,
  `endtime` datetime NOT NULL,
  `finished` tinyint(1) NOT NULL,
  `totalteam1` bigint(20) NOT NULL DEFAULT '0',
  `totalteam2` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`_id`, `nickname`, `password`, `token`) VALUES
(3, 'teste', '123', '');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `rinhaid` int(11) NOT NULL,
  `timevoted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `teamvoted` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`_id`, `userid`, `rinhaid`, `timevoted`, `teamvoted`) VALUES
(1, 2, 1, '2019-09-02 16:14:13', 'first'),
(2, 2, 1, '2019-09-03 15:32:40', 'first'),
(3, 2, 1, '2019-09-03 15:32:44', 'secnd'),
(4, 2, 1, '2019-09-03 15:32:47', 'secnd'),
(5, 2, 1, '2019-09-03 15:48:16', 'secnd'),
(6, 2, 1, '2019-09-03 15:51:06', 'first'),
(7, 2, 1, '2019-09-03 15:51:49', 'first'),
(8, 2, 1, '2019-09-03 15:52:26', 'first'),
(9, 2, 1, '2019-09-03 15:52:40', 'first'),
(10, 2, 1, '2019-09-03 15:52:57', 'first'),
(11, 2, 1, '2019-09-03 15:53:06', 'first'),
(12, 2, 1, '2019-09-03 15:53:46', 'first'),
(13, 2, 1, '2019-09-03 15:54:12', 'first'),
(14, 2, 1, '2019-09-03 15:54:28', 'first'),
(15, 2, 1, '2019-09-03 15:55:16', 'first'),
(16, 2, 1, '2019-09-03 15:55:18', 'first'),
(17, 2, 1, '2019-09-03 15:55:19', 'first'),
(18, 2, 1, '2019-09-03 15:55:20', 'secnd'),
(19, 2, 1, '2019-09-03 15:55:20', 'secnd'),
(20, 2, 1, '2019-09-03 15:55:21', 'secnd'),
(21, 2, 1, '2019-09-03 15:55:21', 'secnd'),
(22, 2, 1, '2019-09-03 15:56:03', 'secnd'),
(23, 2, 1, '2019-09-03 15:56:04', 'secnd'),
(24, 2, 1, '2019-09-03 15:56:05', 'secnd'),
(25, 2, 1, '2019-09-03 16:16:52', 'secnd'),
(26, 2, 1, '2019-09-03 16:16:52', 'secnd'),
(27, 2, 1, '2019-09-03 16:16:53', 'secnd'),
(28, 2, 1, '2019-09-03 16:16:54', 'secnd'),
(29, 2, 1, '2019-09-03 16:16:54', 'secnd'),
(30, 2, 1, '2019-09-03 16:16:54', 'secnd'),
(31, 2, 1, '2019-09-03 16:16:55', 'secnd'),
(32, 2, 1, '2019-09-03 16:17:30', 'secnd'),
(33, 2, 1, '2019-09-03 16:17:30', 'secnd'),
(34, 2, 1, '2019-09-03 16:17:31', 'secnd'),
(35, 2, 1, '2019-09-03 16:17:32', 'first'),
(36, 2, 1, '2019-09-03 16:17:32', 'first'),
(37, 2, 1, '2019-09-03 16:17:32', 'first'),
(38, 2, 1, '2019-09-03 16:17:32', 'first'),
(39, 2, 1, '2019-09-03 16:17:32', 'first');

--
-- Triggers `votes`
--
DELIMITER $$
CREATE TRIGGER `decVote` AFTER DELETE ON `votes` FOR EACH ROW IF OLD.teamvoted = "first" THEN
	UPDATE rinhapp.rinhas SET totalteam1 = totalteam1 - 1  
    WHERE OLD.rinhaid = rinhas._id;
ELSE
	UPDATE rinhapp.rinhas SET totalteam2 = totalteam2 - 1 
     WHERE OLD.rinhaid = rinhas._id;
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `incVote` AFTER INSERT ON `votes` FOR EACH ROW IF NEW.teamvoted = "first" THEN
	UPDATE rinhapp.rinhas SET totalteam1 = totalteam1 + 1  
    WHERE NEW.rinhaid = rinhas._id;
ELSE
	UPDATE rinhapp.rinhas SET totalteam2 = totalteam2 + 1 
     WHERE NEW.rinhaid = rinhas._id;
END IF
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rinhas`
--
ALTER TABLE `rinhas`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `fk_owner_userid` (`owner`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rinhas`
--
ALTER TABLE `rinhas`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rinhas`
--
ALTER TABLE `rinhas`
  ADD CONSTRAINT `fk_owner_userid` FOREIGN KEY (`owner`) REFERENCES `users` (`_id`) ON DELETE CASCADE ON UPDATE CASCADE;
