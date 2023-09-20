-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generato il: Set 20, 2023 alle 15:02
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
-- Struttura della tabella `aa_provvedimenti_data`
--

CREATE TABLE IF NOT EXISTS `aa_provvedimenti_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(255) NOT NULL,
  `estremi_atto` varchar(255) NOT NULL,
  `anno_rif` int(4) NOT NULL DEFAULT '0',
  `tipo` int(1) NOT NULL DEFAULT '0',
  `modalita` int(2) NOT NULL DEFAULT '0',
  `contraente` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anno_rif` (`anno_rif`),
  KEY `tipo` (`tipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=356 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
