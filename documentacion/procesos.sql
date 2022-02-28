-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-07-2018 a las 19:06:02
-- Versión del servidor: 5.7.21
-- Versión de PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `valcon`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

DROP TABLE IF EXISTS `procesos`;
CREATE TABLE IF NOT EXISTS `procesos` (
  `ID_Proceso` int(2) NOT NULL,
  `Nombre_Proceso` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Proceso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `procesos`
--

INSERT INTO `procesos` (`ID_Proceso`, `Nombre_Proceso`) VALUES
(1, 'Administración'),
(2, 'Almacén'),
(3, 'Calidad'),
(4, 'Detallado'),
(5, 'Fusión'),
(6, 'Mantenimiento'),
(7, 'Maquinado'),
(8, 'Modelos'),
(9, 'Moldeo'),
(10, 'Producción');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
