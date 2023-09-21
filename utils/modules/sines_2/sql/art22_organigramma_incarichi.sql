-- phpMyAdmin SQL Dump
-- version 5.2.1-1.fc38
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 21, 2023 alle 16:42
-- Versione del server: 10.5.21-MariaDB
-- Versione PHP: 8.2.10

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
-- Struttura della tabella `art22_organigramma_incarichi`
--

CREATE TABLE `art22_organigramma_incarichi` (
  `id` int(11) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT 0,
  `id_organigramma` int(11) NOT NULL DEFAULT 0,
  `ras` int(1) NOT NULL DEFAULT 1,
  `ordine` int(2) NOT NULL DEFAULT 50,
  `opzionale` int(1) NOT NULL DEFAULT 0,
  `note` varchar(1024) NOT NULL,
  `forza_scadenzario` int(11) NOT NULL,
  `compenso_spettante` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `art22_organigramma_incarichi`
--
ALTER TABLE `art22_organigramma_incarichi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo` (`tipo`,`id_organigramma`,`ras`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `art22_organigramma_incarichi`
--
ALTER TABLE `art22_organigramma_incarichi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
