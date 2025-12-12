-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2025 a las 05:24:31
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
-- Base de datos: `korina_pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes_caja`
--

CREATE TABLE `cortes_caja` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `total_ventas` decimal(10,2) NOT NULL,
  `total_efectivo` decimal(10,2) NOT NULL,
  `total_tarjeta` decimal(10,2) NOT NULL,
  `ventas_desde` datetime NOT NULL,
  `ventas_hasta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cortes_caja`
--

INSERT INTO `cortes_caja` (`id`, `fecha`, `usuario_id`, `total_ventas`, `total_efectivo`, `total_tarjeta`, `ventas_desde`, `ventas_hasta`) VALUES
(1, '2025-12-11 18:57:13', 1, 627.00, 318.00, 309.00, '2025-12-10 21:36:03', '2025-12-11 18:54:31'),
(2, '2025-12-11 18:59:36', 1, 140.04, 0.00, 140.04, '2025-12-11 18:59:19', '2025-12-11 18:59:26'),
(3, '2025-12-11 22:09:50', 1, 45.00, 45.00, 0.00, '2025-12-11 21:54:20', '2025-12-11 21:54:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1, 1, 35.00, 35.00),
(2, 2, 1, 1, 35.00, 35.00),
(3, 3, 7, 1, 28.00, 28.00),
(4, 4, 5, 1, 50.00, 50.00),
(5, 5, 5, 1, 50.00, 50.00),
(6, 6, 12, 1, 55.00, 55.00),
(7, 7, 5, 1, 50.00, 50.00),
(8, 8, 5, 1, 50.00, 50.00),
(9, 9, 1, 1, 35.00, 35.00),
(10, 10, 22, 1, 45.00, 45.00),
(11, 11, 16, 1, 55.00, 55.00),
(12, 12, 2, 1, 42.00, 42.00),
(13, 13, 11, 1, 15.00, 15.00),
(14, 14, 2, 1, 42.00, 42.00),
(15, 15, 3, 1, 40.00, 40.00),
(16, 16, 1, 1, 35.01, 35.01),
(17, 17, 1, 3, 35.01, 105.03),
(18, 18, 22, 1, 45.00, 45.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `categoria` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(120) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `categoria`, `precio`, `imagen`, `disponible`) VALUES
(1, 'Café Americano', 'Café clásico', 'Cafés Calientes', 35.01, 'cafe-caliente.png', 1),
(2, 'Capuchino', 'Café con leche y espuma', 'Cafés Calientes', 42.00, 'Café Descafeinado.png', 1),
(3, 'Latte', 'Café con leche suave', 'Cafés Calientes', 40.00, 'Latte.png', 1),
(4, 'Mocha', 'Café con chocolate', 'Cafés Calientes', 45.00, 'Mocha.png', 1),
(5, 'Chilaquiles Verdes', 'Con pollo', 'Comidas', 65.00, 'Chilaquiles Verdes.png', 1),
(6, 'Chilaquiles Rojos', 'Con queso', 'Comidas', 60.00, 'Chilaquiles Rojos.png', 1),
(7, 'Molletes', 'Con pico de gallo', 'Comidas', 50.00, 'Molletes.png', 1),
(8, 'Sandwich', 'Jamón y queso', 'Comidas', 45.00, 'Sandwich.png', 1),
(9, 'Brownie', 'Chocolate', 'Postres', 30.00, 'Brownie.png', 1),
(10, 'Pay de Queso', 'Rebanada', 'Postres', 38.00, 'Pay de Queso.png', 1),
(11, 'Galleta', 'Chispas de chocolate', 'Postres', 15.00, 'Galleta.png', 1),
(12, 'Té Negro', 'Caliente', 'Tés', 28.00, 'Té Negro.png', 1),
(13, 'Té Verde', 'Caliente', 'Tés', 28.00, 'Té Verde.png', 1),
(14, 'Matcha Latte', 'Caliente', 'Tés', 45.00, 'Matcha Latte.png', 1),
(15, 'Agua Embotellada', '600 ml', 'Bebidas', 18.00, 'Agua Embotellada.png', 1),
(16, 'Frappe Mocha', 'Granizado', 'Frappes', 55.00, 'Frappe Mocha.png', 1),
(17, 'Panqué de Limón', 'Rebanada', 'Postres', 32.00, 'Panqué de Limón.png', 1),
(18, 'Croissant', 'Mantequilla', 'Alimentos', 35.00, 'Croissant.png', 1),
(19, 'Café Descafeinado', 'Suave', 'Cafés Calientes', 38.00, 'Café Descafeinado.png', 1),
(20, 'Ensalada', 'Verduras frescas', 'Comidas', 55.00, 'Ensalada.png', 1),
(21, 'Cold Brew', 'Café frío preparado en frío', 'Cafés Fríos', 50.00, 'Cold Brew.png', 1),
(22, 'Café Helado', 'Café servido con hielo', 'Cafés Fríos', 45.00, 'Café Helado.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `rol` enum('admin','cajero') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin', '1', 'admin'),
(2, 'Cajero Principal', 'cajero', '123', 'cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta') NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `total`, `metodo_pago`, `usuario_id`) VALUES
(1, '2025-12-10 21:36:03', 35.00, 'efectivo', 2),
(2, '2025-12-10 21:37:47', 35.00, 'tarjeta', 2),
(3, '2025-12-10 21:38:05', 28.00, 'efectivo', 2),
(4, '2025-12-10 21:38:15', 50.00, 'efectivo', 2),
(5, '2025-12-10 21:40:17', 50.00, 'efectivo', 2),
(6, '2025-12-10 21:40:52', 55.00, 'efectivo', 2),
(7, '2025-12-10 21:43:51', 50.00, 'tarjeta', 2),
(8, '2025-12-10 21:44:11', 50.00, 'tarjeta', 2),
(9, '2025-12-10 23:18:50', 35.00, 'tarjeta', 2),
(10, '2025-12-11 18:39:04', 45.00, 'efectivo', 2),
(11, '2025-12-11 18:39:09', 55.00, 'tarjeta', 2),
(12, '2025-12-11 18:42:27', 42.00, 'tarjeta', 2),
(13, '2025-12-11 18:42:33', 15.00, 'efectivo', 2),
(14, '2025-12-11 18:50:00', 42.00, 'tarjeta', 2),
(15, '2025-12-11 18:54:31', 40.00, 'efectivo', 2),
(16, '2025-12-11 18:59:19', 35.01, 'tarjeta', 2),
(17, '2025-12-11 18:59:26', 105.03, 'tarjeta', 2),
(18, '2025-12-11 21:54:20', 45.00, 'efectivo', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD CONSTRAINT `cortes_caja_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
