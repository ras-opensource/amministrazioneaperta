-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 25, 2024 alle 16:55
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
-- Struttura della tabella `aa_platform_modules`
--

DROP TABLE IF EXISTS `aa_platform_modules`;
CREATE TABLE `aa_platform_modules` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_modulo` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tooltip` varchar(255) NOT NULL,
  `id_sidebar` varchar(255) NOT NULL,
  `admins` varchar(255) NOT NULL COMMENT 'utenti amministratori',
  `enable` int(1) NOT NULL DEFAULT 0,
  `descrizione` text NOT NULL,
  `flags` text NOT NULL,
  `ordine` int(2) NOT NULL DEFAULT 0,
  `visible` int(2) NOT NULL DEFAULT 0,
  `path` varchar(1024) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aa_platform_modules`
--
ALTER TABLE `aa_platform_modules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aa_platform_modules`
--
ALTER TABLE `aa_platform_modules`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
