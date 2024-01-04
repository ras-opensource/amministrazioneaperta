-- phpMyAdmin SQL Dump
-- version 5.2.1-2.fc39
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Gen 04, 2024 alle 20:10
-- Versione del server: 10.5.23-MariaDB
-- Versione PHP: 8.2.14

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
-- Struttura della tabella `aa_sier_data`
--

CREATE TABLE `aa_sier_data` (
  `id` int(11) NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `flags` int(10) NOT NULL DEFAULT 0,
  `anno` varchar(10) NOT NULL DEFAULT '',
  `giornate` varchar(1024) NOT NULL DEFAULT '{}',
  `cp` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sier_data`
--
ALTER TABLE `aa_sier_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sier_data`
--
ALTER TABLE `aa_sier_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
