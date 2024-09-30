-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 30, 2024 alle 10:40
-- Versione del server: 10.11.9-MariaDB
-- Versione PHP: 8.3.11

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
-- Struttura della tabella `aa_gecop_data`
--

DROP TABLE IF EXISTS `aa_gecop_data`;
CREATE TABLE `aa_gecop_data` (
  `id` int(11) UNSIGNED NOT NULL,
  `cig` varchar(20) NOT NULL,
  `anno` varchar(4) NOT NULL,
  `note` varchar(4096) NOT NULL,
  `aggiudicatario` varchar(200) NOT NULL DEFAULT '',
  `links` varchar(2048) NOT NULL DEFAULT '',
  `gestione_finanziaria` varchar(4096) NOT NULL DEFAULT '',
  `commissione` text NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_gecop_data`
--
ALTER TABLE `aa_gecop_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_gecop_data`
--
ALTER TABLE `aa_gecop_data`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
