-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Nov 08, 2023 alle 07:50
-- Versione del server: 10.5.16-MariaDB
-- Versione PHP: 8.0.27

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
-- Struttura della tabella `aa_groups`
--

CREATE TABLE IF NOT EXISTS `aa_groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) COLLATE utf8_bin NOT NULL,
  `id_parent` int(11) NOT NULL DEFAULT 0,
  `system` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `parent` (`id_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `aa_groups`
--

INSERT INTO `aa_groups` (`id`, `descr`, `id_parent`, `system`) VALUES
(1, 'Super User', 0, 1),
(2, 'Amministratori', 1, 1),
(3, 'Operatori', 2, 1),
(4, 'Utenti', 3, 1),
(5, 'Operatori di sistema', 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
