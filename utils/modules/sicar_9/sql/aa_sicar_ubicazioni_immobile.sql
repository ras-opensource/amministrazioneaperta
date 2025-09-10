-- phpMyAdmin SQL Dump
-- version 5.2.2-1.fc42
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Set 10, 2025 alle 13:12
-- Versione del server: 10.11.11-MariaDB
-- Versione PHP: 8.4.12

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
-- Struttura della tabella `aa_sicar_ubicazioni_immobile`
--

DROP TABLE IF EXISTS `aa_sicar_ubicazioni_immobile`;
CREATE TABLE `aa_sicar_ubicazioni_immobile` (
  `id` int(10) UNSIGNED NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Dump dei dati per la tabella `aa_sicar_ubicazioni_immobile`
--

INSERT INTO `aa_sicar_ubicazioni_immobile` (`id`, `descrizione`) VALUES
(1, 'Centro'),
(3, 'Semi periferia'),
(4, 'Periferia'),
(5, 'Frazione');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
