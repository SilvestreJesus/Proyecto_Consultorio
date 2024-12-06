-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-12-2024 a las 00:12:22
-- Versión del servidor: 10.11.10-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u570776304_consultorio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `curp_pac` varchar(19) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `hora_fin` time DEFAULT NULL,
  `sintomas` varchar(500) DEFAULT NULL,
  `diagnostico` varchar(500) DEFAULT NULL,
  `medicamentos` varchar(500) DEFAULT NULL,
  `curp_med` varchar(19) NOT NULL
) ;

--
-- Disparadores `cita`
--
DELIMITER $$
CREATE TRIGGER `set_hora_fin_trigger` BEFORE INSERT ON `cita` FOR EACH ROW BEGIN
  SET NEW.hora_fin = DATE_ADD(NEW.hora_cita, INTERVAL 20 MINUTE);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `curp` varchar(19) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `contraseña` varchar(50) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `curp` varchar(19) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido_p` varchar(50) DEFAULT NULL,
  `apellido_m` varchar(50) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `contraseña` varchar(32) DEFAULT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `especialidad` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `unique_cita` (`fecha_cita`,`hora_cita`,`curp_med`),
  ADD KEY `fk_paciente` (`curp_pac`),
  ADD KEY `fk_medico` (`curp_med`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `curp` (`curp`,`correo`,`contraseña`,`rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`curp`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `fk_medico` FOREIGN KEY (`curp_med`) REFERENCES `usuario` (`curp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_paciente` FOREIGN KEY (`curp_pac`) REFERENCES `usuario` (`curp`) ON DELETE CASCADE;

--
-- Filtros para la tabla `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`curp`) REFERENCES `usuario` (`curp`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
