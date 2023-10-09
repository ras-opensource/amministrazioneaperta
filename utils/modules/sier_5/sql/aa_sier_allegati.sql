-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ott 09, 2023 alle 10:26
-- Versione del server: 10.5.16-MariaDB
-- Versione PHP: 8.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitspese`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `aa_sier_allegati`
--

CREATE TABLE `aa_sier_allegati` (
  `id` int(11) UNSIGNED NOT NULL,
  `estremi` varchar(255) COLLATE utf8_bin NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `id_sier` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `file` varchar(255) COLLATE utf8_bin NOT NULL,
  `tipo` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `aggiornamento` varchar(30) COLLATE utf8_bin NOT NULL,
  `destinatari` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ordine` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sier_allegati`
--
ALTER TABLE `aa_sier_allegati`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sier_allegati`
--
ALTER TABLE `aa_sier_allegati`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
