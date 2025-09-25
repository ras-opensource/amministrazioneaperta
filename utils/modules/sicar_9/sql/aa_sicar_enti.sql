-- phpMyAdmin SQL Dump
-- version 5.2.2-1.fc42
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 25, 2025 alle 18:54
-- Versione del server: 10.11.11-MariaDB
-- Versione PHP: 8.4.12

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
-- Struttura della tabella `aa_sicar_enti`
--

CREATE TABLE `aa_sicar_enti` (
  `id` int(11) NOT NULL,
  `tipologia` int(11) NOT NULL DEFAULT 0 COMMENT 'Tipologia ente(tabellato)',
  `indirizzo` varchar(255) NOT NULL DEFAULT '' COMMENT 'Indirizzo completo con numero civico',
  `note` text DEFAULT NULL COMMENT 'Note aggiuntive',
  `geolocalizzazione` varchar(255) NOT NULL DEFAULT '',
  `denominazione` varchar(255) NOT NULL,
  `web` varchar(255) NOT NULL DEFAULT '',
  `operatori` text NOT NULL DEFAULT '',
  `pec` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabella dati per gli immobili del modulo SICAR';

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sicar_enti`
--
ALTER TABLE `aa_sicar_enti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tipologia` (`tipologia`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sicar_enti`
--
ALTER TABLE `aa_sicar_enti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
