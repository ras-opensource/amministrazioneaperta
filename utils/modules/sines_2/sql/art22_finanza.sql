-- phpMyAdmin SQL Dump
-- version 5.2.3-2.fc44
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Nov 04, 2026 alle 12:28
-- Versione del server: 11.8.8-MariaDB
-- Versione PHP: 8.5.12

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
  `oneri_totali` varchar(255) NOT NULL DEFAULT '',
  `risultati_bilancio` varchar(255) NOT NULL DEFAULT '',
  `tipo_bilancio` int(11) NOT NULL DEFAULT 0,
  `note` text NOT NULL DEFAULT '',
  `spesa_complessiva_personale` varchar(100) NOT NULL DEFAULT '',
  `spesa_lavoro_flessibile` varchar(100) NOT NULL DEFAULT '',
  `spesa_incarichi` varchar(100) NOT NULL DEFAULT '',
  `fatturato` varchar(100) NOT NULL DEFAULT '',
  `dotazione_organica_dir` varchar(100) NOT NULL,
  `dipendenti` varchar(100) NOT NULL DEFAULT '',
  `dipendenti_det` varchar(100) NOT NULL DEFAULT '',
  `dipendenti_dir` varchar(100) NOT NULL DEFAULT '',
  `dipendenti_det_dir` varchar(100) NOT NULL DEFAULT '',
  `gap` int(11) NOT NULL DEFAULT 0,
  `gbc` int(1) NOT NULL DEFAULT 0,
  `spesa_dotazione_organica_impegnato` varchar(255) NOT NULL DEFAULT '',
  `spesa_lavoro_flessibile_impegnato` varchar(255) NOT NULL DEFAULT '',
  `spesa_lavoro_flessibile_num` varchar(255) NOT NULL DEFAULT '',
  `spesa_incarichi_impegnato` varchar(255) NOT NULL DEFAULT '',
  `spesa_incarichi_num` varchar(255) NOT NULL DEFAULT '',
  `dotazione_organica_dip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
