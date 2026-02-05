-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Feb 05, 2026 alle 19:19
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
-- Struttura della tabella `aa_sicar_nuclei`
--

CREATE TABLE `aa_sicar_nuclei` (
  `id` int(11) UNSIGNED NOT NULL,
  `cf` varchar(20) NOT NULL DEFAULT '',
  `descrizione` varchar(255) NOT NULL DEFAULT '',
  `indirizzo` varchar(255) NOT NULL DEFAULT '',
  `componenti` varchar(4096) NOT NULL DEFAULT '',
  `isee` varchar(4096) NOT NULL DEFAULT '',
  `note` varchar(1024) NOT NULL DEFAULT '',
  `comune` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sicar_nuclei`
--
ALTER TABLE `aa_sicar_nuclei`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sicar_nuclei`
--
ALTER TABLE `aa_sicar_nuclei`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
