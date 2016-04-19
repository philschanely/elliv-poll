-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 03, 2016 at 09:17 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `act-on-it`
--

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
`category_id` int(9) NOT NULL,
  `label` varchar(64) NOT NULL,
  `owner` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
`comment_id` int(15) NOT NULL,
  `details` text NOT NULL,
  `task` int(15) NOT NULL,
  `author` int(6) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE `Contact` (
  `user` int(6) NOT NULL,
  `contact` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Priority_Type`
--

CREATE TABLE `Priority_Type` (
`priority_type_id` int(1) NOT NULL,
  `name` varchar(16) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Shared_Category`
--

CREATE TABLE `Shared_Category` (
  `category` int(9) NOT NULL,
  `user` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Task`
--

CREATE TABLE `Task` (
`task_id` int(15) NOT NULL,
  `label` text NOT NULL,
  `creator` int(6) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `priority_type` int(1) NOT NULL DEFAULT '1',
  `due_date` date DEFAULT NULL,
  `assignee` int(6) DEFAULT NULL,
  `category` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `user_id` int(6) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `photo` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
 ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
 ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `Contact`
--
ALTER TABLE `Contact`
 ADD PRIMARY KEY (`user`,`contact`);

--
-- Indexes for table `Priority_Type`
--
ALTER TABLE `Priority_Type`
 ADD PRIMARY KEY (`priority_type_id`);

--
-- Indexes for table `Shared_Category`
--
ALTER TABLE `Shared_Category`
 ADD PRIMARY KEY (`category`,`user`);

--
-- Indexes for table `Task`
--
ALTER TABLE `Task`
 ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
MODIFY `category_id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
MODIFY `comment_id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Priority_Type`
--
ALTER TABLE `Priority_Type`
MODIFY `priority_type_id` int(1) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Task`
--
ALTER TABLE `Task`
MODIFY `task_id` int(15) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `user_id` int(6) NOT NULL AUTO_INCREMENT;



