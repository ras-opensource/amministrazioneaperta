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
-- Table structure for table `aa_geser_objects`
--

CREATE TABLE `aa_geser_objects` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_data` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `status` int(2) NOT NULL DEFAULT 1,
  `nome` varchar(255) NOT NULL,
  `descrizione` text NOT NULL,
  `struttura` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_assessorato` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_direzione` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_servizio` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `class` varchar(255) NOT NULL DEFAULT 'AA_Object_V2',
  `logs` longtext NOT NULL,
  `id_data_rev` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `aggiornamento` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aa_geser_objects`
--
ALTER TABLE `aa_geser_objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_object` (`id_data`,`status`,`nome`,`struttura`),
  ADD KEY `id_assessorato` (`id_assessorato`,`id_direzione`,`id_servizio`),
  ADD KEY `class` (`class`),
  ADD KEY `status` (`status`),
  ADD KEY `id_data_rev` (`id_data_rev`),
  ADD KEY `id_data` (`id_data`,`id_data_rev`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aa_geser_objects`
--
ALTER TABLE `aa_geser_objects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
