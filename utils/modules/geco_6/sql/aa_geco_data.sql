-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 15, 2024 alle 12:10
-- Versione del server: 10.11.6-MariaDB
-- Versione PHP: 8.3.6

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
-- Struttura della tabella `aa_geco_data`
--

CREATE TABLE `aa_geco_data` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_geco` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `norma` varchar(1024) NOT NULL DEFAULT '',
  `anno` varchar(4) NOT NULL,
  `descrizione` varchar(1024) NOT NULL DEFAULT '',
  `note` varchar(4096) NOT NULL DEFAULT '',
  `modalita` int(11) NOT NULL DEFAULT 0,
  `revoca` varchar(4096) NOT NULL DEFAULT '',
  `responsabile` varchar(255) NOT NULL,
  `beneficiario` varchar(2048) NOT NULL,
  `importo_impegnato` varchar(127) NOT NULL DEFAULT '0',
  `importo_erogato` varchar(127) NOT NULL DEFAULT '0',
  `allegati` varchar(4096) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_geco_data`
--
ALTER TABLE `aa_geco_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_geco_data`
--
ALTER TABLE `aa_geco_data`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
