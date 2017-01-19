-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 19 jan 2017 kl 09:33
-- Serverversion: 10.1.16-MariaDB
-- PHP-version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `ymdb`
--
CREATE DATABASE IF NOT EXISTS `ymdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ymdb`;

-- --------------------------------------------------------

--
-- Tabellstruktur `format`
--

CREATE TABLE `format` (
  `format_key` int(11) NOT NULL DEFAULT '0',
  `format` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `format`
--

INSERT INTO `format` (`format_key`, `format`) VALUES
(0, 'HD');

-- --------------------------------------------------------

--
-- Tabellstruktur `imdb_info`
--

CREATE TABLE `imdb_info` (
  `imdb_info_id` int(11) NOT NULL DEFAULT '0',
  `lastupdated` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `votes` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `genre` text,
  `tagline` text,
  `poster` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `movies_ny`
--

CREATE TABLE `movies_ny` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `imdb` varchar(255) DEFAULT NULL,
  `format` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  `cds` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `places`
--

CREATE TABLE `places` (
  `places_id` int(11) NOT NULL,
  `places_name` varchar(200) NOT NULL,
  `places_show` int(1) NOT NULL,
  `places_userid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_passw` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_passw`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `format`
--
ALTER TABLE `format`
  ADD PRIMARY KEY (`format_key`);

--
-- Index för tabell `imdb_info`
--
ALTER TABLE `imdb_info`
  ADD PRIMARY KEY (`imdb_info_id`);

--
-- Index för tabell `movies_ny`
--
ALTER TABLE `movies_ny`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `imdb` (`imdb`),
  ADD KEY `format` (`format`),
  ADD KEY `added` (`added`),
  ADD KEY `cds` (`cds`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location` (`location`);

--
-- Index för tabell `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`places_id`),
  ADD KEY `places_userid` (`places_userid`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `movies_ny`
--
ALTER TABLE `movies_ny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9957;
--
-- AUTO_INCREMENT för tabell `places`
--
ALTER TABLE `places`
  MODIFY `places_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
