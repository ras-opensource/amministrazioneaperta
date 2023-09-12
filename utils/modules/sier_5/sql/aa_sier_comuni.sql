-- phpMyAdmin SQL Dump
-- version 5.2.1-1.fc38
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 12, 2023 alle 09:56
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
-- Struttura della tabella `aa_sier_comuni`
--

CREATE TABLE IF NOT EXISTS `aa_sier_comuni` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_elezioni` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `contatti` varchar(255) NOT NULL,
  `risultati` text NOT NULL,
  `affluenza` text NOT NULL,
  `operatori` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;