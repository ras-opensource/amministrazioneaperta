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
-- Struttura della tabella `aa_objects`
--

CREATE TABLE IF NOT EXISTS `aa_objects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_data` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  `nome` varchar(255) NOT NULL,
  `descrizione` text NOT NULL,
  `struttura` int(11) unsigned NOT NULL DEFAULT '0',
  `id_assessorato` int(11) unsigned NOT NULL DEFAULT '0',
  `id_direzione` int(11) unsigned NOT NULL DEFAULT '0',
  `id_servizio` int(11) unsigned NOT NULL DEFAULT '0',
  `class` varchar(255) NOT NULL DEFAULT 'AA_Object_V2',
  `logs` text NOT NULL,
  `id_data_rev` int(11) unsigned NOT NULL DEFAULT '0',
  `aggiornamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_object` (`id_data`,`status`,`nome`,`struttura`),
  KEY `id_assessorato` (`id_assessorato`,`id_direzione`,`id_servizio`),
  KEY `class` (`class`),
  KEY `status` (`status`),
  KEY `id_data_rev` (`id_data_rev`),
  KEY `id_data` (`id_data`,`id_data_rev`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4746 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
