-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `carnet_expedido`;
CREATE TABLE `carnet_expedido` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) DEFAULT NULL,
  `expedido` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `carnet_expedido` (`id`, `codigo`, `expedido`) VALUES
(1, 'LP', 'La Paz'),
(2, 'OR', 'Oruro'),
(3, 'PT', 'Potosi'),
(4, 'PN', 'Pando'),
(5, 'BN', 'Beni'),
(6, 'SC', 'Santa Cruz'),
(7, 'CBB',  'Cochabamba'),
(8, 'CH', 'Chuquisaca'),
(9, 'TJ', 'Tarija'),
(10,  'otro', 'Otro');

DROP TABLE IF EXISTS `ingreso_fallidos`;
CREATE TABLE `ingreso_fallidos` (
  `ip` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `count` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ingreso_sistema`;
CREATE TABLE `ingreso_sistema` (
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL,
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `ingreso_sistema_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `mensajeria_sistema`;
CREATE TABLE `mensajeria_sistema` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mensaje` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `mensajeria_sistema_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clase` varchar(100) DEFAULT NULL,
  `funcion` varchar(100) DEFAULT NULL,
  `descripcion` tinytext,
  `roles` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) DEFAULT NULL,
  `descripcion` tinytext,
  `status` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) DEFAULT NULL,
  `ap_paterno` varchar(100) DEFAULT NULL,
  `ap_materno` varchar(100) DEFAULT NULL,
  `ci` varchar(100) DEFAULT NULL,
  `carnet_expedido_id` tinyint(4) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fecha_reg` timestamp NULL DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `zona` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `estado` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carnet_expedido_id` (`carnet_expedido_id`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`carnet_expedido_id`) REFERENCES `carnet_expedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `usuarios` (`id`, `nombres`, `ap_paterno`, `ap_materno`, `ci`, `carnet_expedido_id`, `usuario`, `password`, `fecha_reg`, `email`, `celular`, `telefono`, `zona`, `direccion`, `sexo`, `fecha_nac`, `imagen`, `estado`) VALUES
(1, 'Admin',  'Super',  'Administrador',  '1',  1,  'admin',  '$2y$10$GsxJodBnxH0gxjZim1tIEuNoXCT/2kGjZoPxS4agOVPmNbFxf6Mf2', '2016-11-21 03:00:50',  'webmaster@example.com', '', '', '',  '', 'Masculino',  '2000-01-01', '', 1);


DROP TABLE IF EXISTS `usuarios_reiniciar`;
CREATE TABLE `usuarios_reiniciar` (
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) DEFAULT NULL,
  `used` tinyint(1) DEFAULT '0',
  KEY `user_id` (`user_id`),
  CONSTRAINT `usuarios_reiniciar_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `usuarios_roles`;
CREATE TABLE `usuarios_roles` (
  `usuario_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  KEY `usuario_id` (`usuario_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_roles_ibfk_4` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuarios_roles_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
