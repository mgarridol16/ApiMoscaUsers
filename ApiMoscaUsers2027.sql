-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-10-2026 a las 21:15:30
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ApiMoscaUsers2027`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `tablero` varchar(500) NOT NULL,
  `intentos` int(50) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `fecha_create` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id`, `idUser`, `tablero`, `intentos`, `activo`, `fecha_create`) VALUES
(1, 4, '0,0,0,0,1,1,0,0,0,0', 1, 0, '2026-10-15'),
(2, 4, '0,0,1,0,0,0,0,0,1,0', 1, 0, '2026-10-15'),
(3, 4, '1,1,0,0,0,0,1,0,0,0', -1, 0, '2026-10-15'),
(4, 2, '0,0,0,1,0,0,0,1,0,0', 2, 0, '2026-10-15'),
(5, 2, '0,1,0,0,0,1,0,0,0,0', 3, 0, '2026-10-15'),
(6, 2, '0,0,0,0,1,1,0,1,1,1', 0, 0, '2026-10-15'),
(7, 2, '0,0,0,0,1,0,0,0,0,0', 5, 0, '2026-10-15'),
(8, 6, '1,0,0,0,1,1,0,0,0,0', 2, 0, '2026-10-16'),
(9, 6, '0,0,0,0,0,0,0,1,0,0', 5, 0, '2026-10-16'),
(10, 7, '0,0,1,0,1,0,0,0,0,0', 4, 1, '2026-10-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` varchar(100) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `edad` int(50) NOT NULL,
  `autenticado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `email`, `rol`, `pass`, `edad`, `autenticado`) VALUES
(1, 'miguel', 'garridoleonmiguel2003@gmail.com', 'ADMIN', '81dc9bdb52d04dc20036dbd8313ed055', 22, 1),
(6, 'miguel_nuevo', 'example@mail.com', 'JUGADOR', '674f3c2c1a8a6f90461e8a66fb5550ba', 29, 1),
(7, 'Manolito', 'examplexito@mail.com', 'JUGADOR', 'e10adc3949ba59abbe56e057f20f883e', 40, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
