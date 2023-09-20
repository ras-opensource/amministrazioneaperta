-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generato il: Set 20, 2023 alle 15:00
-- Versione del server: 5.5.21
-- Versione PHP: 5.3.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `monitspese`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `aa_patrimonio_canoni`
--

CREATE TABLE IF NOT EXISTS `aa_patrimonio_canoni` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_patrimonio` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `conduttore` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `repertorio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data_inizio` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `data_fine` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `importo` varchar(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipologia` int(1) unsigned NOT NULL DEFAULT '0',
  `serial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_patrimonio` (`id_patrimonio`,`conduttore`),
  KEY `tipologia` (`tipologia`),
  KEY `serial` (`serial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=565 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
