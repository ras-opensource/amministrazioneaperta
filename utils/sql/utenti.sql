-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3307
-- Generato il: Set 20, 2023 alle 15:07
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
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_assessorato` int(11) unsigned DEFAULT '0',
  `id_direzione` int(11) unsigned DEFAULT '0',
  `id_servizio` int(11) unsigned DEFAULT '0',
  `id_settore` int(11) unsigned DEFAULT '0',
  `user` varchar(100) DEFAULT NULL,
  `passwd` varchar(100) DEFAULT '',
  `home` varchar(255) DEFAULT 'reserved/index.php',
  `livello` int(1) DEFAULT '0',
  `disable` int(1) NOT NULL DEFAULT '0',
  `eliminato` int(1) NOT NULL DEFAULT '0',
  `flags` text NOT NULL,
  `sibar_username` varchar(255) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cognome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tipo_incarico` int(1) NOT NULL DEFAULT '0',
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_cessazione` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `lastlogin` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `user_2` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3978 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
