-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generato il: Set 20, 2023 alle 15:01
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
-- Struttura della tabella `aa_patrimonio_data`
--

CREATE TABLE IF NOT EXISTS `aa_patrimonio_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codice_comune` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sezione_catasto` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `foglio_catasto` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `particella_catasto` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `indirizzo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rendita_catasto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `consistenza_catasto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `classe_catasto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `titolo` int(5) NOT NULL DEFAULT '0',
  `cespite` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subalterno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subcespite` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codice_comune` (`codice_comune`,`sezione_catasto`),
  KEY `foglio_catasto` (`foglio_catasto`),
  KEY `particella_catasto` (`particella_catasto`),
  KEY `titolo` (`titolo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4379 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
