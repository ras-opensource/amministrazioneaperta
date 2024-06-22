-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 22, 2024 alle 12:45
-- Versione del server: 10.11.8-MariaDB
-- Versione PHP: 8.3.8

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
-- Struttura della tabella `aa_geco_criteri`
--

CREATE TABLE `aa_geco_criteri` (
  `id` int(11) UNSIGNED NOT NULL,
  `estremi` varchar(1024) NOT NULL,
  `categorie` int(11) NOT NULL DEFAULT 0,
  `anno` varchar(4) NOT NULL DEFAULT '',
  `struttura` varchar(12) NOT NULL DEFAULT '0',
  `url` varchar(1024) NOT NULL DEFAULT '',
  `file` varchar(1024) NOT NULL DEFAULT '',
  `tipo` int(11) NOT NULL DEFAULT 0,
  `descrizione` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_geco_criteri`
--
ALTER TABLE `aa_geco_criteri`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_geco_criteri`
--
ALTER TABLE `aa_geco_criteri`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
