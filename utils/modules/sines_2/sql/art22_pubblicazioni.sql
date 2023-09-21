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
-- Struttura della tabella `art22_pubblicazioni`
--

CREATE TABLE `art22_pubblicazioni` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_assessorato` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_direzione` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_servizio` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `status` int(2) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `denominazione` varchar(255) NOT NULL DEFAULT '',
  `tipo` int(2) NOT NULL DEFAULT 0,
  `funzioni` text NOT NULL,
  `partecipazione` varchar(255) NOT NULL,
  `sito_web` varchar(255) NOT NULL,
  `data_inizio_impegno` date NOT NULL,
  `data_fine_impegno` date NOT NULL,
  `aggiornamento` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `note` text NOT NULL,
  `piva_cf` varchar(255) NOT NULL,
  `sede_legale` varchar(255) NOT NULL,
  `pec` varchar(255) NOT NULL,
  `forma_societaria` int(11) NOT NULL DEFAULT 0,
  `inhouse` int(1) NOT NULL DEFAULT 0,
  `tusp` int(1) NOT NULL DEFAULT 0,
  `stato_organismo` int(1) NOT NULL DEFAULT 0,
  `log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `art22_pubblicazioni`
--
ALTER TABLE `art22_pubblicazioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_assessorato` (`id_assessorato`,`id_direzione`,`id_servizio`,`status`,`id_user`,`denominazione`,`tipo`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `art22_pubblicazioni`
--
ALTER TABLE `art22_pubblicazioni`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
