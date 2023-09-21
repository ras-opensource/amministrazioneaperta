-- phpMyAdmin SQL Dump
-- version 5.2.1-1.fc38
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 21, 2023 alle 08:54
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
-- Struttura della tabella `art22_nomine`
--

CREATE TABLE `art22_nomine` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_organismo` int(11) NOT NULL DEFAULT 0,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `data_inizio` date NOT NULL,
  `data_fine` date NOT NULL,
  `tipo_incarico` int(11) NOT NULL DEFAULT 0,
  `compenso_spettante` varchar(255) NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `codice_fiscale` varchar(100) NOT NULL,
  `nomina_ras` int(11) NOT NULL DEFAULT 0,
  `id_nominato` int(11) UNSIGNED NOT NULL,
  `estremi_provvedimento` varchar(255) NOT NULL,
  `storico` int(1) NOT NULL DEFAULT 0,
  `facente_funzione` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `art22_nomine`
--
ALTER TABLE `art22_nomine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pubblicazione` (`id_organismo`,`nome`,`cognome`),
  ADD KEY `data_inizio` (`data_inizio`,`data_fine`,`tipo_incarico`),
  ADD KEY `codice_fiscale` (`codice_fiscale`),
  ADD KEY `id_nominato` (`id_nominato`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `art22_nomine`
--
ALTER TABLE `art22_nomine`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
