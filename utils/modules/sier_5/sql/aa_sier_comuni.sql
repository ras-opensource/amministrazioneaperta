-- phpMyAdmin SQL Dump
-- version 5.2.1-1.fc38
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ott 24, 2023 alle 09:33
-- Versione del server: 10.5.22-MariaDB
-- Versione PHP: 8.2.11

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
-- Struttura della tabella `aa_sier_comuni`
--

CREATE TABLE `aa_sier_comuni` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_sier` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `denominazione` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL DEFAULT '',
  `contatti` varchar(255) NOT NULL DEFAULT '',
  `risultati` longtext NOT NULL DEFAULT '',
  `affluenza` longtext NOT NULL DEFAULT '',
  `operatori` longtext NOT NULL DEFAULT '',
  `sezioni` int(8) NOT NULL DEFAULT 0,
  `elettori_m` int(8) NOT NULL DEFAULT 0,
  `elettori_f` int(8) NOT NULL DEFAULT 0,
  `id_circoscrizione` int(5) NOT NULL DEFAULT 0,
  `rendiconti` longtext NOT NULL DEFAULT '',
  `pec` varchar(255) NOT NULL DEFAULT '',
  `lastupdate` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sier_comuni`
--
ALTER TABLE `aa_sier_comuni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sier` (`id_sier`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sier_comuni`
--
ALTER TABLE `aa_sier_comuni`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
