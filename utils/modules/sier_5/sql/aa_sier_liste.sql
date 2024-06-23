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
-- Struttura della tabella `aa_sier_liste`
--

CREATE TABLE `aa_sier_liste` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_coalizione` int(11) UNSIGNED NOT NULL,
  `denominazione` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `ordine` int(2) NOT NULL DEFAULT 0,
  `ordine_1` int(2) NOT NULL DEFAULT 0,
  `ordine_2` int(2) NOT NULL DEFAULT 0,
  `ordine_4` int(2) NOT NULL DEFAULT 0,
  `ordine_8` int(2) NOT NULL DEFAULT 0,
  `ordine_16` int(2) NOT NULL DEFAULT 0,
  `ordine_32` int(2) NOT NULL DEFAULT 0,
  `ordine_64` int(2) NOT NULL DEFAULT 0,
  `ordine_128` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sier_liste`
--
ALTER TABLE `aa_sier_liste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_coalizione` (`id_coalizione`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sier_liste`
--
ALTER TABLE `aa_sier_liste`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
