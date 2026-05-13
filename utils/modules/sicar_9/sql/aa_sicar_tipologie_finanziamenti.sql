-- phpMyAdmin SQL Dump
-- version 5.2.3-1.fc43
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 11, 2026 alle 13:46
-- Versione del server: 10.11.16-MariaDB
-- Versione PHP: 8.4.20

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
-- Struttura della tabella `aa_sicar_tipologie_finanziamenti`
--

CREATE TABLE `aa_sicar_tipologie_finanziamenti` (
  `id` int(11) UNSIGNED NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dump dei dati per la tabella `aa_sicar_tipologie_finanziamenti`
--

INSERT INTO `aa_sicar_tipologie_finanziamenti` (`id`, `descrizione`) VALUES
(1, 'Nessuno'),
(2, 'Pruacs'),
(3, 'CQII'),
(4, 'PNEA'),
(5, 'FSC 2021/2027'),
(6, 'Ex Gescal'),
(7, 'Rinnovarea');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sicar_tipologie_finanziamenti`
--
ALTER TABLE `aa_sicar_tipologie_finanziamenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sicar_tipologie_finanziamenti`
--
ALTER TABLE `aa_sicar_tipologie_finanziamenti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
