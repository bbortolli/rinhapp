-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 02, 2019 at 05:40 PM
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
  `team1` varchar(255) NOT NULL,
  `team2` varchar(255) NOT NULL,
  `endtime` datetime NOT NULL,
  `finished` tinyint(1) NOT NULL,
  `totalteam1` bigint(20) NOT NULL DEFAULT '0',
  `totalteam2` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rinhas`
--

INSERT INTO `rinhas` (`_id`, `team1`, `team2`, `endtime`, `finished`, `totalteam1`, `totalteam2`) VALUES
(1, 'palmeiras', 'corinthians', '2019-09-30 00:00:00', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`_id`, `nickname`, `password`) VALUES
(2, 'teste', '123');

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
(1, 2, 1, '2019-09-02 16:14:13', 'first');

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
  ADD PRIMARY KEY (`_id`);

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
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
