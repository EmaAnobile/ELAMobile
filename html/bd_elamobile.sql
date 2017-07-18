-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2017 at 11:47 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bd_elamobile`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `dowhile`()
BEGIN
  DECLARE v1 INT DEFAULT 65;
  WHILE v1 < 91 DO
    INSERT letras (texto, idioma_id) values( char(v1),1) ;
    SET v1 = v1 + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `institucion_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `aprobado` tinyint(4) NOT NULL DEFAULT '0',
  `borrador` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `institucion_id` (`institucion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `fecha`, `institucion_id`, `usuario_id`, `aprobado`, `borrador`) VALUES
(5, '2017-07-13 01:01:21', 1, 2, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `compras_detalles`
--

CREATE TABLE IF NOT EXISTS `compras_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) unsigned NOT NULL,
  `tipo_lic_id` int(11) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `DETALLE_UNICO` (`compra_id`,`tipo_lic_id`),
  KEY `compra_id` (`compra_id`,`tipo_lic_id`),
  KEY `tipo_lic_id` (`tipo_lic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `compras_detalles`
--

INSERT INTO `compras_detalles` (`id`, `compra_id`, `tipo_lic_id`, `cantidad`, `precio`) VALUES
(43, 5, 5, 20, '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id`, `color`) VALUES
(1, 'rosa'),
(2, 'rojo'),
(3, 'verde'),
(4, 'amarillo'),
(5, 'naranja'),
(6, 'celeste');

-- --------------------------------------------------------

--
-- Table structure for table `idiomas`
--

CREATE TABLE IF NOT EXISTS `idiomas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `idiomas`
--

INSERT INTO `idiomas` (`id`, `nombre`, `codigo`) VALUES
(1, 'Español', 'es');

-- --------------------------------------------------------

--
-- Table structure for table `instituciones`
--

CREATE TABLE IF NOT EXISTS `instituciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(250) NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `instituciones`
--

INSERT INTO `instituciones` (`id`, `razon_social`, `borrado`) VALUES
(1, 'Clinica San Nicolas', 0),
(2, 'Fulanito & cia', 0);

-- --------------------------------------------------------

--
-- Table structure for table `letras`
--

CREATE TABLE IF NOT EXISTS `letras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texto` varchar(250) NOT NULL,
  `idioma_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idioma_id` (`idioma_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `letras`
--

INSERT INTO `letras` (`id`, `texto`, `idioma_id`) VALUES
(1, 'A', 1),
(2, 'B', 1),
(3, 'C', 1),
(4, 'D', 1),
(5, 'E', 1),
(6, 'F', 1),
(7, 'G', 1),
(8, 'H', 1),
(9, 'I', 1),
(10, 'J', 1),
(11, 'K', 1),
(12, 'L', 1),
(13, 'M', 1),
(14, 'N', 1),
(15, 'O', 1),
(16, 'P', 1),
(17, 'Q', 1),
(18, 'R', 1),
(19, 'S', 1),
(20, 'T', 1),
(21, 'U', 1),
(22, 'V', 1),
(23, 'W', 1),
(24, 'X', 1),
(25, 'Y', 1),
(26, 'Z', 1),
(27, '0', 1),
(28, '1', 1),
(29, '2', 1),
(30, '3', 1),
(31, '4', 1),
(32, '5', 1),
(33, '6', 1),
(34, '7', 1),
(35, '8', 1),
(36, '9', 1);

-- --------------------------------------------------------

--
-- Table structure for table `palabras`
--

CREATE TABLE IF NOT EXISTS `palabras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texto` varchar(250) DEFAULT NULL,
  `idioma_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idioma_id` (`idioma_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `palabras`
--

INSERT INTO `palabras` (`id`, `texto`, `idioma_id`) VALUES
(1, 'CASA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
--

CREATE TABLE IF NOT EXISTS `perfiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`) VALUES
(1, 'Administrar Usuario '),
(2, 'Administrar Licencias'),
(3, 'Administrar institucion');

-- --------------------------------------------------------

--
-- Table structure for table `perfiles_permisos`
--

CREATE TABLE IF NOT EXISTS `perfiles_permisos` (
  `perfil_id` int(10) unsigned NOT NULL,
  `recurso` varchar(50) NOT NULL,
  `privilegio` varchar(50) NOT NULL,
  UNIQUE KEY `permiso_unico` (`perfil_id`,`recurso`,`privilegio`),
  KEY `perfil_id` (`perfil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `perfiles_permisos`
--

INSERT INTO `perfiles_permisos` (`perfil_id`, `recurso`, `privilegio`) VALUES
(1, 'instituciones', 'administrar'),
(1, 'perfiles', 'alta'),
(1, 'perfiles', 'baja'),
(1, 'perfiles', 'listado'),
(1, 'usuarios', 'alta'),
(1, 'usuarios', 'baja'),
(1, 'usuarios', 'listado'),
(2, 'compras', 'alta'),
(2, 'compras', 'baja'),
(2, 'compras', 'listado'),
(2, 'compras', 'modificacion'),
(2, 'licencias', 'alta'),
(2, 'licencias', 'baja'),
(2, 'licencias', 'listado'),
(2, 'licencias', 'modificacion'),
(2, 'usuarios', 'alta'),
(2, 'usuarios', 'baja'),
(2, 'usuarios', 'listado'),
(3, 'instituciones', 'administrar'),
(3, 'usuarios', 'alta'),
(3, 'usuarios', 'baja'),
(3, 'usuarios', 'listado'),
(3, 'usuarios', 'modificacion');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `sistema` tinyint(1) NOT NULL DEFAULT '0',
  `admin_general` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ROL_NOMBRE` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `sistema`, `admin_general`) VALUES
(1, 'Admin', 1, 1),
(2, 'Moderador', 0, 0),
(4, 'Hola', 0, 0),
(5, 'Admin institucion', 0, 0),
(6, 'Paciente', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles_perfiles`
--

CREATE TABLE IF NOT EXISTS `roles_perfiles` (
  `rol_id` int(10) unsigned NOT NULL,
  `perfil_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`rol_id`,`perfil_id`),
  KEY `perfil_id` (`perfil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_perfiles`
--

INSERT INTO `roles_perfiles` (`rol_id`, `perfil_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(2, 2),
(4, 2),
(5, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `subgrupos`
--

CREATE TABLE IF NOT EXISTS `subgrupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grupo_id` int(10) unsigned NOT NULL,
  `letra_id` int(10) unsigned DEFAULT NULL,
  `palabra_id` int(10) unsigned DEFAULT NULL,
  `texto` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupo_id` (`grupo_id`,`letra_id`,`palabra_id`),
  KEY `letra_id` (`letra_id`),
  KEY `palabra_id` (`palabra_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `subgrupos`
--

INSERT INTO `subgrupos` (`id`, `grupo_id`, `letra_id`, `palabra_id`, `texto`) VALUES
(1, 1, 1, NULL, 'A'),
(2, 1, 2, NULL, 'B'),
(3, 1, 3, NULL, 'C'),
(4, 1, 4, NULL, 'D'),
(5, 1, 5, NULL, 'E'),
(6, 1, 6, NULL, 'F'),
(7, 2, 7, NULL, 'G'),
(8, 2, 8, NULL, 'H'),
(9, 2, 9, NULL, 'I'),
(10, 2, 10, NULL, 'J'),
(11, 2, 11, NULL, 'K'),
(12, 2, 12, NULL, 'L'),
(13, 3, 13, NULL, 'M'),
(14, 3, 14, NULL, 'N'),
(15, 3, 15, NULL, 'O'),
(16, 3, 16, NULL, 'P'),
(17, 3, 17, NULL, 'Q'),
(18, 3, 18, NULL, 'R'),
(19, 4, 19, NULL, 'S'),
(20, 4, 20, NULL, 'T'),
(21, 4, 21, NULL, 'U'),
(22, 4, 22, NULL, 'V'),
(23, 4, 23, NULL, 'W'),
(24, 4, 24, NULL, 'X'),
(25, 5, 25, NULL, 'Y'),
(26, 5, 26, NULL, 'Z'),
(27, 5, 27, NULL, '0'),
(28, 5, 28, NULL, '1'),
(29, 5, 29, NULL, '2'),
(30, 5, 30, NULL, '3'),
(31, 6, 31, NULL, '4'),
(32, 6, 32, NULL, '5'),
(33, 6, 33, NULL, '6'),
(34, 6, 34, NULL, '7'),
(35, 6, 35, NULL, '8'),
(36, 6, 36, NULL, '9');

-- --------------------------------------------------------

--
-- Table structure for table `tableros`
--

CREATE TABLE IF NOT EXISTS `tableros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tableros`
--

INSERT INTO `tableros` (`id`, `codigo`) VALUES
(2, 'lateral_relacionados'),
(3, 'lateral_sugeridos'),
(1, 'principal');

-- --------------------------------------------------------

--
-- Table structure for table `tableros_grupos`
--

CREATE TABLE IF NOT EXISTS `tableros_grupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablero_id` int(10) unsigned NOT NULL,
  `grupo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tablero_id` (`tablero_id`,`grupo_id`),
  KEY `grupo_id` (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tableros_grupos`
--

INSERT INTO `tableros_grupos` (`id`, `tablero_id`, `grupo_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(7, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tipos_licencias`
--

CREATE TABLE IF NOT EXISTS `tipos_licencias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tipos_licencias`
--

INSERT INTO `tipos_licencias` (`id`, `nombre`, `precio`) VALUES
(4, 'Infinia', '300.00'),
(5, 'Basica', '100.00'),
(7, 'Avanzada', '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` int(10) unsigned NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `idioma_id` int(10) unsigned DEFAULT NULL,
  `institucion_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idioma_id` (`idioma_id`),
  KEY `rol_id` (`rol_id`),
  KEY `institucion_id` (`institucion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `usuario`, `password`, `idioma_id`, `institucion_id`) VALUES
(1, 1, 'admin', 'admin', 1, NULL),
(2, 5, 'pablo', 'paciente', 1, 1),
(3, 6, 'paciente', 'paciente', NULL, 1),
(4, 6, 'ema', '', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_tableros`
--

CREATE TABLE IF NOT EXISTS `usuarios_tableros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) unsigned NOT NULL,
  `tablero_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tablero_id` (`tablero_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `usuarios_tableros`
--

INSERT INTO `usuarios_tableros` (`id`, `usuario_id`, `tablero_id`) VALUES
(1, 1, 1),
(3, 1, 2),
(10, 2, 3),
(11, 2, 3),
(12, 2, 3),
(13, 2, 3),
(14, 2, 3),
(16, 3, 1),
(19, 3, 1),
(20, 3, 1),
(21, 3, 1),
(24, 4, 1),
(25, 3, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`);

--
-- Constraints for table `compras_detalles`
--
ALTER TABLE `compras_detalles`
  ADD CONSTRAINT `compras_detalles_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `compras_detalles_ibfk_2` FOREIGN KEY (`tipo_lic_id`) REFERENCES `tipos_licencias` (`id`);

--
-- Constraints for table `letras`
--
ALTER TABLE `letras`
  ADD CONSTRAINT `letras_ibfk_1` FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`);

--
-- Constraints for table `palabras`
--
ALTER TABLE `palabras`
  ADD CONSTRAINT `palabras_ibfk_1` FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`);

--
-- Constraints for table `perfiles_permisos`
--
ALTER TABLE `perfiles_permisos`
  ADD CONSTRAINT `perfiles_permisos_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles` (`id`);

--
-- Constraints for table `roles_perfiles`
--
ALTER TABLE `roles_perfiles`
  ADD CONSTRAINT `roles_perfiles_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `roles_perfiles_ibfk_2` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles` (`id`);

--
-- Constraints for table `subgrupos`
--
ALTER TABLE `subgrupos`
  ADD CONSTRAINT `subgrupos_ibfk_1` FOREIGN KEY (`letra_id`) REFERENCES `letras` (`id`),
  ADD CONSTRAINT `subgrupos_ibfk_2` FOREIGN KEY (`palabra_id`) REFERENCES `palabras` (`id`),
  ADD CONSTRAINT `subgrupos_ibfk_3` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Constraints for table `tableros_grupos`
--
ALTER TABLE `tableros_grupos`
  ADD CONSTRAINT `tableros_grupos_ibfk_1` FOREIGN KEY (`tablero_id`) REFERENCES `tableros` (`id`),
  ADD CONSTRAINT `tableros_grupos_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idioma_id`) REFERENCES `idiomas` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`);

--
-- Constraints for table `usuarios_tableros`
--
ALTER TABLE `usuarios_tableros`
  ADD CONSTRAINT `usuarios_tableros_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuarios_tableros_ibfk_2` FOREIGN KEY (`tablero_id`) REFERENCES `tableros` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
