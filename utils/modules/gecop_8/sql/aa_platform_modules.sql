-- phpMyAdmin SQL Dump
-- version 5.2.1-4.fc40
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 31, 2024 at 09:02 PM
-- Server version: 10.11.8-MariaDB
-- PHP Version: 8.3.9

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

--
-- Dumping data for table `aa_platform_modules`
--

INSERT INTO `aa_platform_modules` (`id`, `id_modulo`, `class`, `icon`, `name`, `tooltip`, `id_sidebar`, `admins`, `enable`, `descrizione`, `flags`, `ordine`) VALUES
(8, 'AA_MODULE_GECOP', 'AA_Gecop', 'mdi mdi-briefcase', 'Gestionale Contratti pubblici', 'GECOP - Gestionale contratti pubblici', 'gecop', '1', 0, 'Modulo per la gestione delle pubblicazioni ai sensi dell\'art.37/2013', '{\"gecop\":\"GECOP\"}',0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
