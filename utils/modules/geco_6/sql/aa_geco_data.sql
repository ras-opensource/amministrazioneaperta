-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 16, 2024 at 04:18 PM
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
-- Table structure for table `aa_geco_data`
--

CREATE TABLE `aa_geco_data` (
  `id` int(11) UNSIGNED NOT NULL,
  `norma` varchar(1024) NOT NULL DEFAULT '',
  `anno` varchar(4) NOT NULL,
  `descrizione` varchar(1024) NOT NULL DEFAULT '',
  `note` varchar(4096) NOT NULL DEFAULT '',
  `modalita` varchar(1024) NOT NULL DEFAULT '',
  `revoca` varchar(4096) NOT NULL DEFAULT '',
  `responsabile` varchar(255) NOT NULL,
  `beneficiario` varchar(2048) NOT NULL,
  `importo_impegnato` varchar(127) NOT NULL DEFAULT '0',
  `importo_erogato` varchar(127) NOT NULL DEFAULT '0',
  `allegati` varchar(4096) NOT NULL DEFAULT '',
  `revisione` longtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aa_geco_data`
--
ALTER TABLE `aa_geco_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aa_geco_data`
--
ALTER TABLE `aa_geco_data`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
