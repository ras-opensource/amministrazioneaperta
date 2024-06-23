-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 23, 2024 alle 23:14
-- Versione del server: 10.5.22-MariaDB
-- Versione PHP: 8.0.30

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
-- Struttura della tabella `aa_sier_comuni`
--

CREATE TABLE `aa_sier_comuni` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_sier` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `denominazione` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL DEFAULT '',
  `contatti` varchar(2048) NOT NULL,
  `risultati` longtext NOT NULL DEFAULT '',
  `affluenza` longtext NOT NULL DEFAULT '',
  `operatori` longtext NOT NULL DEFAULT '',
  `sezioni` int(8) NOT NULL DEFAULT 0,
  `elettori_m` int(8) NOT NULL DEFAULT 0,
  `elettori_f` int(8) NOT NULL DEFAULT 0,
  `id_circoscrizione` int(5) NOT NULL DEFAULT 0,
  `rendiconti` longtext NOT NULL DEFAULT '',
  `pec` varchar(255) NOT NULL DEFAULT '',
  `lastupdate` varchar(255) NOT NULL DEFAULT '',
  `comunicazioni` longtext NOT NULL DEFAULT '',
  `sezioni_ospedaliere` int(10) NOT NULL DEFAULT 0,
  `sezioni_ordinarie` int(10) NOT NULL DEFAULT 0,
  `luoghi_cura_sub100` int(10) NOT NULL DEFAULT 0,
  `luoghi_cura_over100` int(10) NOT NULL DEFAULT 0,
  `luoghi_detenzione` int(10) NOT NULL DEFAULT 0,
  `elettori_esteri_m` int(10) NOT NULL DEFAULT 0,
  `elettori_esteri_f` int(10) NOT NULL DEFAULT 0,
  `feed_risultati` longtext NOT NULL DEFAULT '',
  `logs` longtext NOT NULL DEFAULT '',
  `analisi_risultati` varchar(4096) NOT NULL DEFAULT ''
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
