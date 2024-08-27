-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2024 a las 06:36:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `nivel` enum('nacional','departamental') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `nombre`, `email`, `contrasena`, `departamento`, `nivel`) VALUES
(1, 'EIFAB Regional \"LA PAZ\"', 'lpeifab', '$2y$10$RynKnZRH4IMfMY9hLQtOC.wZi45OXniFtf7rp8dT2izWY2x9AanW6', 'La Paz', 'departamental'),
(2, 'TAMEP (Avenida Montes)', 'lptamep', '$2y$10$uzET0j4t2FPU4s/RZQrYbuiq10YdMUo3qqNgyNI921oPLbrpdALNy', 'La Paz', 'departamental'),
(3, 'PRIMERA BRIGADA AEREA', 'ea1brigae', '$2y$10$4mv6uxchaqpM2/xWHmfydekM8EiQ8eKqUzZ8DXx1LEhO15XdhnBGS', 'El Alto', 'departamental'),
(4, 'TRANSPORTE AEREOS BOLIVIANOS', 'lptransaebo', '$2y$10$wv5YvDN/OPAt6MqhYzsHPOeJ2QI6qh5v1Y9ojgG6PA.VB/cLEP.Me', 'La Paz', 'departamental'),
(5, 'EIFAB REGIONAL \"COCHABAMBA\"', 'cbbaeifab', '$2y$10$l4LlNzLShDf//M5oHAIIW.mDtlDg78vAZ0pM9PnWRTH62kZP4pFjy', 'Cochabamba', 'departamental'),
(6, 'SAR (Av. Aroma y Ayacucho)', 'cbbasar', '$2y$10$/UnICK1b8Z3RiIvyeiO5J.cmiGpQwXeBesDoNGGhzDQaoZzG1rsZK', 'Cochabamba', 'departamental'),
(7, 'POLMILAE', 'cbbapolmilae', '$2y$10$D5k3mmih.Nsi3uBY9yvE1ek9bCwVuWgnZKQ9dOdKNxQht/7dVyLmC', 'Cochabamba', 'departamental'),
(8, 'COLMILAV', 'sccolmilav', '$2y$10$GO2/b0N8C3mxDsflsKm4FedukZjrZSzj5r8RWqriSYYqB0UiHTxv6', 'Santa Cruz', 'departamental'),
(9, 'COLMILAV (Cine CENTER)', 'szcolmilavcc', '$2y$10$8rWsUDLun1YAAr2nmVKZH.bALVmJLbjbfKthbsfWRPuEEACsa9GeC', 'Santa Cruz', 'departamental'),
(10, 'EMMFAB (Grupo de Seguridad)', 'oremmfab', '$2y$10$6IgGFtDCDaVo8rWkOhT6h.FsLuAWIa3Zb26iE45cZgBqW0Nih3PSa', 'Oruro', 'departamental'),
(11, 'EIFAB REGIONAL', 'trieifabreg', '$2y$10$NIWiI8JhkzEJZmEGvCKc5erhMcWcV/179dHPM52qLnhjttO9EJEXS', 'Trinidad', 'departamental'),
(12, 'Agencia del TAMEP', 'tritamep', '$2y$10$ufPVgMqAZ/vVutAbG9NRBulUiNLJEsmZrFFI358cCBcLnP/WWq26a', 'Trinidad', 'departamental'),
(13, 'EIFAB REGIONAL \"TRINIDAD\"', 'cbeifabreg', '$2y$10$c8.MjKtME.e7QztatVpZRumb5DSTa9PM7rjVTPzMB6TeQR4nSdLzm', 'Cobija', 'departamental'),
(14, 'ECEMA - GRUPO AEREO \"67\"', 'scecemagae67', '$2y$10$zeUB6jPS9ozSsRDB3Dek5OeCydHSTx2jrkbI54sdf0jOH0dhIPSSa', 'Sucre', 'departamental'),
(15, 'TERMINAL (Oficinas de la Gobernacion)', 'scterminal', '$2y$10$Cm5qipTlu6GTwZxXC8sae.q2uIvE72ILH66Rt59yyk90lbnxxlYuG', 'Sucre', 'departamental'),
(16, 'CUARTA BRIGADA AEREA', 'tj4brigae', '$2y$10$UigO2OUxMPJcbSLUIUn6gexE6Ej2/NUR5eC9wf0gLnBJ2Mk7nJKPi', 'Tarija', 'departamental'),
(17, 'TAMEP (Plaza principal)', 'tjtamep', '$2y$10$pnjhMeqQu9YAs07IWG7hI.bV4iqv0GaZjLdrWfq.vfKzKD9C260su', 'Tarija', 'departamental'),
(18, 'GRUPO AEREO \"61\"', 'rbgae61', '$2y$10$f1ybCilUuOX6mecF.8iCBu7ONE3cdvILV8BZFHxKUKWh6gNF6Z7Ka', 'Robore', 'departamental'),
(19, 'GRUPO AEREO \"62\"', 'ribgae62', '$2y$10$tkvA5zaejJBzs2yk9xQjwOCppgUJOcgqmuLng5oSQ485N4Z7RKssy', 'Riberalta', 'departamental'),
(20, 'TAMEP (Plaza principal)', 'guatamep', '$2y$10$WHgh5MUKVtIq/aH5Js/r5OSSNI9M0r6et4IOFwH78iHQ.zeSdcAn6', 'Guayaramerin', 'departamental'),
(21, 'GRUPO AEREO \"65\"', 'uyngae65', '$2y$10$aJud6Cz2XxepPGPEHY6LIOEWtleoJtydYyydfWvAI2dvTpM1NDMVm', 'Uyuni', 'departamental'),
(22, 'TAMEP', 'yactamep', '$2y$10$RFjTeHcXZ1Y3myENCqMdBuzgVMiiR0zwP2FroEMFQVn6GuCyBYwU.', 'Yacuiba', 'departamental'),
(23, 'G.A.D.A. \"97\"', 'cnvgada97', '$2y$10$mWxv1itVdPN4/0a4rNRS4eGJp58d2eRDhxd89G4BqrgN0kUh6OjyO', 'Caranavi', 'departamental'),
(24, 'GRUPO AEREO \"63\"', 'vilgae63', '$2y$10$J584nbgNAcRIWvWG/2EfyOGa8ri.BT13UBHWpt67corQjciGMNJ2K', 'Villamontes', 'departamental'),
(25, 'GRUPO AEREO \"83\"', 'psgae83', '$2y$10$GTwmym096sfZfrBosFROfu2Rviro8rYt.kzqJnVkXuYPQxuDSBzhS', 'Puerto Suarez', 'departamental'),
(26, 'EIFAB Regional \"LA PAZ\"', 'jefedptov', '$2y$10$AkXM7Nf0NKfVxHo3MO8xauClEysPvW3.IcoGkMP6dvIBJnso/pjEa', NULL, 'nacional'),
(27, 'EIFAB Regional \"LA PAZ\"', 'cmdtfab', '$2y$10$V9RakI1NcjVO1oOYYM5KLO4UfSaqN2ETSN2H9kHQApp9g8vM81KfS', NULL, 'nacional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `ciudad_registro` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono_fijo` varchar(20) DEFAULT NULL,
  `numero_celular` varchar(20) NOT NULL,
  `contacto_tutor` varchar(20) DEFAULT NULL,
  `nombre_tutor` varchar(100) DEFAULT NULL,
  `sexo` varchar(10) NOT NULL,
  `complementarios` varchar(55) DEFAULT NULL,
  `cod_amitai` varchar(25) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `instituto` varchar(50) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `procedencia` varchar(100) NOT NULL,
  `cuenta_con_seguro` varchar(10) NOT NULL,
  `de_donde_es_seguro` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `estado` varchar(15) DEFAULT NULL,
  `grupo` varchar(10) DEFAULT NULL,
  `semana_presentacion` varchar(20) DEFAULT NULL,
  `pago_amitai` varchar(20) DEFAULT NULL,
  `monto_amitai` decimal(10,2) DEFAULT NULL,
  `fecha_amitai` date DEFAULT NULL,
  `pago_prospecto` varchar(20) DEFAULT NULL,
  `monto_prospecto` decimal(10,2) DEFAULT NULL,
  `fecha_prospecto` date DEFAULT NULL,
  `pago_medico` varchar(50) DEFAULT NULL,
  `monto_medico` decimal(10,2) DEFAULT NULL,
  `fecha_medico` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_ibfk_1` (`admin_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `administradores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
