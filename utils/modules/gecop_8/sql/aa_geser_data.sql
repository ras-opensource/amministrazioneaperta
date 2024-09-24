-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 31, 2024 at 09:01 PM
-- Server version: 10.11.8-MariaDB
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amministrazioneaperta`
--

-- --------------------------------------------------------

--
-- Table structure for table `aa_geser_data`
--

CREATE TABLE `aa_geser_data` (
  `id` int(11) UNSIGNED NOT NULL,
  `tipologia` int(10) NOT NULL DEFAULT 0,
  `stato` int(11) NOT NULL DEFAULT 0,
  `anno_autorizzazione` varchar(4) NOT NULL,
  `anno_entrata_esercizio` varchar(4) NOT NULL,
  `anno_dismissione` varchar(4) NOT NULL,
  `note` varchar(4096) NOT NULL DEFAULT '',
  `potenza` varchar(20) NOT NULL DEFAULT '0',
  `geolocalizzazione` varchar(1024) NOT NULL,
  `pratiche` longtext NOT NULL,
  `anno_costruzione` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aa_geser_data`
--
ALTER TABLE `aa_geser_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aa_geser_data`
--
ALTER TABLE `aa_geser_data`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
