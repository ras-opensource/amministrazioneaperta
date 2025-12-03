-- phpMyAdmin SQL Dump
-- version 5.2.3-1.fc43
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 03, 2025 alle 16:43
-- Versione del server: 10.11.13-MariaDB
-- Versione PHP: 8.4.15

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
-- Struttura della tabella `aa_sicar_data`
--

CREATE TABLE `aa_sicar_data` (
  `id` int(11) NOT NULL,
  `immobile` varchar(64) NOT NULL,
  `tipologia_utilizzo` varchar(64) NOT NULL,
  `stato_conservazione` varchar(64) NOT NULL,
  `anno_ristrutturazione` int(11) DEFAULT NULL,
  `condominio_misto` tinyint(1) NOT NULL DEFAULT 0,
  `superficie_parcheggi` decimal(10,2) NOT NULL DEFAULT 0.00,
  `superficie_utile_abitabile` decimal(10,2) NOT NULL DEFAULT 0.00,
  `piano` int(11) NOT NULL DEFAULT 0,
  `ascensore` tinyint(1) NOT NULL DEFAULT 0,
  `fruibile_dis` tinyint(1) NOT NULL DEFAULT 0,
  `gestione` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `proprieta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `stato` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `note` text DEFAULT NULL,
  `vani_abitabili` int(10) NOT NULL DEFAULT 0,
  `superficie_non_residenziale` float(10,2) NOT NULL DEFAULT 0.00,
  `occupazione` longtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Alloggi associati agli immobili';

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sicar_data`
--
ALTER TABLE `aa_sicar_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_immobile` (`immobile`),
  ADD KEY `idx_tipologia_utilizzo` (`tipologia_utilizzo`),
  ADD KEY `idx_stato_conservazione` (`stato_conservazione`),
  ADD KEY `idx_condominio_misto` (`condominio_misto`),
  ADD KEY `idx_piano` (`piano`),
  ADD KEY `idx_ascensore` (`ascensore`),
  ADD KEY `idx_fruibile_dis` (`fruibile_dis`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sicar_data`
--
ALTER TABLE `aa_sicar_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
