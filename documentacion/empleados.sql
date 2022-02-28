-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-07-2018 a las 19:06:43
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
-- Estructura de tabla para la tabla `empleados`
--

DROP TABLE IF EXISTS `empleados`;
CREATE TABLE IF NOT EXISTS `empleados` (
  `ID_Empleado` int(6) NOT NULL,
  `ID_Proceso` int(2) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Apellidos` varchar(50) DEFAULT NULL,
  `Foto` varchar(150) DEFAULT NULL,
  `Administrador` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID_Empleado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`ID_Empleado`, `ID_Proceso`, `Nombre`, `Apellidos`, `Foto`, `Administrador`) VALUES
(123, 1, 'Yanira Belén', 'Corona Gutiérrez', 'NULL', 1),
(209, 2, 'Juan Pablo', 'Torres Martínez', '/evaluaciones/imagenes/Perfiles/209/209.jpg', 0),
(4153, 9, 'Jesús Fernando', 'Almeda Magallanes', 'NULL', 0),
(110, 7, 'Francisco Javier', 'Arévalo Torres', 'NULL', 0),
(4156, 5, 'Baltazar', 'Bañuelos Vicencio', 'NULL', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
