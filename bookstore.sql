-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2018 at 09:44 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `bookprices`
-- (See below for the actual view)
--
CREATE TABLE `bookprices` (
`serialNum` bigint(15)
,`title` varchar(100)
,`author` varchar(50)
,`costPrice` decimal(5,2)
,`markUp` decimal(5,2)
,`salePrice` decimal(5,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `serialNum` bigint(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `summary` varchar(800) NOT NULL,
  `pages` int(4) NOT NULL,
  `publisher` varchar(50) NOT NULL,
  `language` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`serialNum`, `title`, `author`, `summary`, `pages`, `publisher`, `language`) VALUES
(1, 'The Midnight Line', 'Child, Lee', ' Jack Reacher takes an aimless stroll past a pawn shop in a small Midwestern town. In the window he sees a West Point class ring from 2005. Reacher\'s a West Pointer too, and he knows what she went through to get it. Reacher tracks the ring back to its owner, step by step, down a criminal trail leading west. If she\'s OK, he\'ll walk away. ', 400, ' Bantam Press (GB) ', 'English'),
(2, 'Origin', 'Brown, Dan', ' Sunday Times #1 BestsellerNew York Times #1 BestellerThe spellbinding new Robert Langdon novel from the author of The Da Vinci Code. On a trail marked only by enigmatic symbols and elusive modern art, Langdon and Vidal uncover the clues that will bring them face-to-face with a world-shaking truth that has remained buried - until now. ', 480, ' Bantam Press (GB) ', 'English'),
(3, 'The Subtle Art of Not Giving a F*ck : A Counterintuitive Approach to Living a Good Life ', 'Manson, Mark', 'In this generation-defining self-help guide, a superstar blogger cuts through the crap to show us how to stop trying to be \"positive\" all the time so that we can truly become better, happier people. ', 304, 'Harperone (US) ', 'English'),
(4, 'Delete 1', 'Nah', '', 0, '', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `display`
-- (See below for the actual view)
--
CREATE TABLE `display` (
`serialNum` bigint(15)
,`title` varchar(100)
,`author` varchar(50)
,`summary` varchar(800)
,`pages` int(4)
,`publisher` varchar(50)
,`language` varchar(40)
,`salePrice` decimal(5,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logID` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `auditor_id` int(11) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logID`, `account_id`, `content`, `timestamp`, `auditor_id`, `comment`, `time`) VALUES
(177, 127, 'Administrator has logged in to the staff page.', '2018-02-02 13:54:04', 0, '', '2018-02-02 05:54:04'),
(178, 127, 'Administrator has logged out', '2018-02-02 13:55:23', 0, '', '2018-02-02 05:55:23'),
(179, 127, 'Administrator has logged in to the staff page.', '2018-02-02 13:57:00', 0, '', '2018-02-02 05:57:00'),
(180, 127, 'Administrator has logged out', '2018-02-02 13:58:13', 0, '', '2018-02-02 05:58:13'),
(181, 127, 'Administrator has logged in to the staff page.', '2018-02-02 13:58:47', 0, '', '2018-02-02 05:58:47'),
(182, 127, 'Administrator has logged out', '2018-02-02 13:58:50', 0, '', '2018-02-02 05:58:50'),
(183, 127, 'Administrator has logged in to the staff page.', '2018-02-02 14:01:05', 0, '', '2018-02-02 06:01:05'),
(184, 127, 'Administrator has Disabled the account Username: jenny .', '2018-02-02 14:01:15', 0, '', '2018-02-02 06:01:15'),
(185, 127, 'Administrator has created the account Username: hmmm Role: manager Status: Activated .', '2018-02-02 14:01:50', 0, '', '2018-02-02 06:01:50'),
(186, 127, 'Administrator has logged out', '2018-02-02 14:05:23', 0, '', '2018-02-02 06:05:23'),
(187, 127, 'Administrator has logged in to the staff page.', '2018-02-02 14:07:11', 0, '', '2018-02-02 06:07:11'),
(188, 127, 'Administrator has created the account Username: wwww Role: manager Status: Activated .', '2018-02-02 14:08:53', 0, '', '2018-02-02 06:08:53'),
(189, 127, 'Administrator has created the account Username: zzzz Role: auditor Status: Activated .', '2018-02-02 14:10:50', 0, '', '2018-02-02 06:10:50'),
(190, 127, 'Administrator has logged out', '2018-02-02 14:11:47', 0, '', '2018-02-02 06:11:47'),
(191, 138, 'tonyjs445 has logged in to the staff page.', '2018-02-02 14:12:14', 0, '', '2018-02-02 06:12:14'),
(192, 138, 'tonyjs445 has logged out', '2018-02-02 14:13:23', 0, '', '2018-02-02 06:13:23'),
(193, 138, 'tonyjs445 has logged in to the staff page.', '2018-02-02 14:15:50', 0, '', '2018-02-02 06:15:50'),
(194, 138, 'tonyjs445 has logged out', '2018-02-02 14:17:17', 0, '', '2018-02-02 06:17:17'),
(195, 138, 'tonyjs445 has logged in to the staff page.', '2018-02-02 14:18:00', 0, '', '2018-02-02 06:18:00'),
(196, 138, 'tonyjs445 has logged out', '2018-02-02 14:19:16', 0, '', '2018-02-02 06:19:16');

-- --------------------------------------------------------

--
-- Stand-in structure for view `logsview`
-- (See below for the actual view)
--
CREATE TABLE `logsview` (
`logID` int(11)
,`account_id` int(11)
,`name` varchar(100)
,`role` varchar(100)
,`status` varchar(30)
,`content` varchar(1000)
,`timestamp` datetime
,`auditor_id` int(11)
,`comment` varchar(500)
,`time` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `otp_expiry`
--

CREATE TABLE `otp_expiry` (
  `otp` int(6) NOT NULL,
  `expired` int(1) NOT NULL,
  `creation_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `otpID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otp_expiry`
--

INSERT INTO `otp_expiry` (`otp`, `expired`, `creation_time`, `otpID`) VALUES
(955873, 0, '2018-01-30 20:57:15', 123654789),
(336362, 1, '2018-01-30 21:02:57', 123654789),
(481009, 1, '2018-01-30 21:08:57', 123456789),
(881529, 1, '2018-01-30 21:17:10', 123456789),
(356679, 1, '2018-01-30 21:25:11', 123456789),
(207515, 1, '2018-01-30 22:32:20', 123654789),
(717104, 1, '2018-01-30 23:28:00', 123456789),
(149174, 1, '2018-01-31 12:43:23', 123654789),
(846096, 1, '2018-02-02 02:52:21', 123654789),
(200217, 1, '2018-02-02 02:54:13', 123654789),
(400995, 1, '2018-02-02 02:55:57', 123654789),
(932587, 1, '2018-02-02 03:03:39', 835313930),
(985866, 1, '2018-02-02 03:07:59', 835313930),
(911116, 1, '2018-02-02 03:08:40', 123654789),
(173527, 1, '2018-02-02 03:21:27', 123654789),
(959279, 1, '2018-02-02 03:45:59', 123654789),
(418629, 1, '2018-02-02 13:34:58', 123654789),
(127589, 1, '2018-02-02 13:50:48', 123654789),
(941091, 1, '2018-02-02 13:53:41', 123654789),
(713922, 1, '2018-02-02 13:56:47', 123654789),
(329283, 1, '2018-02-02 13:58:38', 123654789),
(438735, 1, '2018-02-02 14:00:45', 123654789),
(893930, 1, '2018-02-02 14:06:44', 123654789),
(693221, 1, '2018-02-02 14:12:05', 123456789),
(266276, 1, '2018-02-02 14:15:44', 123456789),
(519944, 1, '2018-02-02 14:17:35', 123456789);

-- --------------------------------------------------------

--
-- Table structure for table `pricingplan`
--

CREATE TABLE `pricingplan` (
  `serialNum` bigint(15) NOT NULL,
  `costPrice` decimal(5,2) NOT NULL,
  `markUp` decimal(5,2) NOT NULL,
  `salePrice` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pricingplan`
--

INSERT INTO `pricingplan` (`serialNum`, `costPrice`, `markUp`, `salePrice`) VALUES
(0, '20.00', '33.50', '40.00'),
(1, '20.50', '110.00', '43.05'),
(2, '12.00', '110.00', '25.20'),
(3, '23.45', '33.50', '31.31'),
(4, '12.30', '120.00', '27.06');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_no` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL,
  `otpID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `email`, `name`, `contact_no`, `role`, `status`, `otpID`) VALUES
(0, 'loginFailure', 'Tw2NaKZGL9l@%#@%DsHfGERL5MZmE5hrc<<!!>>4ed2dxb303w1pOVcInDfOE83a1X7NuZyuTtl4YyK#&%&#%&bHx3oZHnYpDI', 'none@failure.com', 'loginFailure', 'dDhhT0NyWUhGNm5SbXZDVWQ1Y080Zz09', 'LoginFailure Placeholder', 'Disabled', 0),
(127, 'Administrator', '$2y$10$R1hQeexF5zdEcMO1BaoJfeGM5r3qD1n1lXfqhM/CJ57OkydMFrRVS', 'testswaphere2@gmail.com', 'Admin', 'WDVDZXpCWVVqNWNkTHJVTUZQMnpmQT09', 'admin', 'Activated', 123654789),
(138, 'tonyjs445', '$2y$10$.f7b0WyXieFWBewJHrO8Our65YbD2dP7R.Oa6L9SkJAFv4nyO9HZ2', 'testmyswaphere@gmail.com', 'Jun', 'VkFSSWhsS3BudnpvbmFyNVJnUGRJUT09', 'auditor', 'Activated', 123456789),
(140, 'manager', '$2y$10$NOIXXCo3GFUXv0WOiv5fIe7bnZsGYVUryHhWYIyA6qIBM8Uv.UUXm', 'burner8889@gmail.com', 'tony', 'WHZHMFVsOGVYOGYzcnQ1TjRYVnZkdz09', 'manager', 'Activated', 835313930),
(143, 'gencry2', '$2y$10$m1SoqXLcJt/4t3QFjmGJt.DOlIHK08Wxz/.NwhwcK5WKTbdSFeNwC', 'testswaphere2@gmail.com', 'gencr', 'R0dmU3hnTEFIeEJDZytTNlRXMXpLdz09', 'auditor', 'Activated', 676198166),
(191, 'deleteme2', 'delete me', 'delete me', 'delete me', 'delete me', 'manager', 'delete me', 12345785),
(193, 'jenny', '$2y$10$6Og7KmR6LeQhdqZmRAe7e.9QQHyybtSNrsk.QwMw7D0c36ReDDAlS', 'jenyy@gmail.com', 'Jen', 'TERoRitXbFZNNG05T29pRkluZzRzZz09', 'auditor', 'Disabled', 571602746),
(196, 'hmmm', '$2y$10$Y4SuOZRJNIVL.6MexixzvujgJd4rIC7AozX3C98QyAfvG4Tq4liEm', 'wattte@gmail.com', 'awtwwaer', 'ME9kOE15WXNRc0EwREV5RDJWelN2dz09', 'manager', 'Activated', 765758948),
(197, 'wwww', '$2y$10$6FVbqU69Bl/gNj6kPdQMBehujOF5QHvyfHDHnuCqlv/AG5vX4nsIW', 'tonytan@gmail.com', 'tonyyyyy', 'N0FjS1crK0Y0WHpibVpqYzY3Z3JnUT09', 'manager', 'Activated', 591533647),
(199, 'zzzz', '$2y$10$LgLSXP4pi3NhaPr9mFdfA.5znWK47a6LyGPBqX0uy06yshvAWDjsG', 'twarwerawr@gmail.com', 'tawrwrwar', 'eXl4amtmUWs5MXE2V042dGhYOWdvQT09', 'auditor', 'Activated', 648866610);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `address`, `email`, `password`) VALUES
(166799227, 'Farry', 'Jr', 'Test Blk 413 # 18-99', 'burner988@gmail.com', '$2y$10$0IqQLJ2xABvW8VM4/4Me5e/moL0oE4/XDQQBUF/XN0voBPBsAqTeO'),
(457553488, 'Bob', 'Fam', '123 Drive', 'test@gmail.com', '$2y$10$dA3J8n7zoIq8oP1lvpMVPuZIBKoXyp5vDj9CMqd3Qdbhqae48ZFz.');

-- --------------------------------------------------------

--
-- Structure for view `bookprices`
--
DROP TABLE IF EXISTS `bookprices`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bookprices`  AS  select `books`.`serialNum` AS `serialNum`,`books`.`title` AS `title`,`books`.`author` AS `author`,`pricingplan`.`costPrice` AS `costPrice`,`pricingplan`.`markUp` AS `markUp`,`pricingplan`.`salePrice` AS `salePrice` from (`books` join `pricingplan` on((`books`.`serialNum` = `pricingplan`.`serialNum`))) ;

-- --------------------------------------------------------

--
-- Structure for view `display`
--
DROP TABLE IF EXISTS `display`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `display`  AS  select `books`.`serialNum` AS `serialNum`,`books`.`title` AS `title`,`books`.`author` AS `author`,`books`.`summary` AS `summary`,`books`.`pages` AS `pages`,`books`.`publisher` AS `publisher`,`books`.`language` AS `language`,`pricingplan`.`salePrice` AS `salePrice` from (`books` join `pricingplan` on((`books`.`serialNum` = `pricingplan`.`serialNum`))) ;

-- --------------------------------------------------------

--
-- Structure for view `logsview`
--
DROP TABLE IF EXISTS `logsview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `logsview`  AS  select `logs`.`logID` AS `logID`,`logs`.`account_id` AS `account_id`,`staff`.`name` AS `name`,`staff`.`role` AS `role`,`staff`.`status` AS `status`,`logs`.`content` AS `content`,`logs`.`timestamp` AS `timestamp`,`logs`.`auditor_id` AS `auditor_id`,`logs`.`comment` AS `comment`,`logs`.`time` AS `time` from (`logs` join `staff` on((`logs`.`account_id` = `staff`.`id`))) order by `logs`.`logID` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`serialNum`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `pricingplan`
--
ALTER TABLE `pricingplan`
  ADD PRIMARY KEY (`serialNum`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `otpID` (`otpID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
