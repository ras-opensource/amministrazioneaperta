-- phpMyAdmin SQL Dump
-- version 5.2.1-1.fc38
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 21, 2023 alle 16:40
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
-- Struttura della tabella `art22_finanza`
--

CREATE TABLE `art22_finanza` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_organismo` int(11) NOT NULL,
  `anno` varchar(4) NOT NULL DEFAULT '2021',
  `oneri_totali` varchar(255) NOT NULL,
  `risultati_bilancio` varchar(255) NOT NULL,
  `tipo_bilancio` int(11) NOT NULL DEFAULT 0,
  `note` text NOT NULL,
  `spesa_complessiva_personale` varchar(100) NOT NULL,
  `spesa_lavoro_flessibile` varchar(100) NOT NULL,
  `spesa_incarichi` varchar(100) NOT NULL,
  `fatturato` varchar(100) NOT NULL,
  `dotazione_organica` varchar(100) NOT NULL,
  `dipendenti` varchar(100) NOT NULL,
  `dipendenti_det` varchar(100) NOT NULL,
  `dipendenti_dir` varchar(100) NOT NULL,
  `dipendenti_det_dir` varchar(100) NOT NULL,
  `gap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `art22_finanza`
--
ALTER TABLE `art22_finanza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pubblicazione` (`id_organismo`,`anno`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `art22_finanza`
--
ALTER TABLE `art22_finanza`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
