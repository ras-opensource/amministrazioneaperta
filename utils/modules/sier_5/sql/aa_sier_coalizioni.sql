-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 23, 2024 alle 23:13
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
-- Struttura della tabella `aa_sier_coalizioni`
--

CREATE TABLE `aa_sier_coalizioni` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_sier` int(11) UNSIGNED NOT NULL,
  `denominazione` varchar(255) NOT NULL,
  `nome_candidato` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `cv` varchar(255) NOT NULL DEFAULT '',
  `cg` varchar(255) NOT NULL DEFAULT '',
  `ordine` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_sier_coalizioni`
--
ALTER TABLE `aa_sier_coalizioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_elezioni` (`id_sier`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_sier_coalizioni`
--
ALTER TABLE `aa_sier_coalizioni`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
