USE proyectointegrador;

DELIMITER $$

--
-- Procedimientos
--

DROP PROCEDURE IF EXISTS `ActualizarProductoGasto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarProductoGasto` (IN `pID` INT, IN `pMovimientoID` INT, IN `pProductoID` INT, IN `pImpuestoID` INT, IN `pCantidad_Productos` INT)   BEGIN
    DECLARE vPrecio DECIMAL(10, 2);
    DECLARE vIVA DECIMAL(5, 2);
    DECLARE vValor_Total DECIMAL(10, 2);

    -- Obtener el precio del producto
    SELECT Precio INTO vPrecio FROM Producto WHERE ID = pProductoID;

    -- Obtener el IVA del impuesto
    SELECT IVA INTO vIVA FROM Impuesto WHERE ID = pImpuestoID;

    -- Calcular el valor total
    SET vValor_Total = vPrecio * pCantidad_Productos * (1 + vIVA);

    -- Actualizar el producto_gasto
    UPDATE Producto_Gasto
    SET MovimientoID = pMovimientoID, ProductoID = pProductoID, ImpuestoID = pImpuestoID, Cantidad_Productos = pCantidad_Productos, Valor_Total = vValor_Total
    WHERE ID = pID;
END$$

DROP PROCEDURE IF EXISTS `ActualizarProductoVenta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarProductoVenta` (IN `pID` INT, IN `pVentaID` INT, IN `pProductoID` INT, IN `pImpuestoID` INT, IN `pCantidad_Productos` INT)   BEGIN
    DECLARE vPrecio DECIMAL(10, 2);
    DECLARE vIVA DECIMAL(5, 2);
    DECLARE vValor_Total DECIMAL(10, 2);

    -- Obtener el precio del producto
    SELECT Precio INTO vPrecio FROM Producto WHERE ID = pProductoID;

    -- Obtener el IVA del impuesto
    SELECT IVA INTO vIVA FROM Impuesto WHERE ID = pImpuestoID;

    -- Calcular el valor total
    SET vValor_Total = vPrecio * pCantidad_Productos * (1 + vIVA);

    -- Actualizar el producto_venta
    UPDATE Producto_Venta
    SET VentaID = pVentaID, ProductoID = pProductoID, ImpuestoID = pImpuestoID, Cantidad_Productos = pCantidad_Productos, Valor_Total = vValor_Total
    WHERE ID = pID;
END$$

DROP PROCEDURE IF EXISTS `InsertarProductoGasto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarProductoGasto` (IN `pMovimientoID` INT, IN `pProductoID` INT, IN `pImpuestoID` INT, IN `pCantidad_Productos` INT)   BEGIN
    DECLARE vPrecio DECIMAL(10, 2);
    DECLARE vIVA DECIMAL(5, 2);
    DECLARE vValor_Total DECIMAL(10, 2);

    -- Obtener el precio del producto
    SELECT Precio INTO vPrecio FROM Producto WHERE ID = pProductoID;

    -- Obtener el IVA del impuesto
    SELECT IVA INTO vIVA FROM Impuesto WHERE ID = pImpuestoID;

    -- Calcular el valor total
    SET vValor_Total = vPrecio * pCantidad_Productos * (1 + vIVA);

    -- Insertar el producto_gasto
    INSERT INTO Producto_Gasto(MovimientoID, ProductoID, ImpuestoID, Cantidad_Productos, Valor_Total)
    VALUES (pMovimientoID, pProductoID, pImpuestoID, pCantidad_Productos, vValor_Total);
END$$

DROP PROCEDURE IF EXISTS `InsertarProductoVenta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarProductoVenta` (IN `pVentaID` INT, IN `pProductoID` INT, IN `pImpuestoID` INT, IN `pCantidad_Productos` INT)   BEGIN
    DECLARE vPrecio DECIMAL(10, 2);
    DECLARE vIVA DECIMAL(5, 2);
    DECLARE vValor_Total DECIMAL(10, 2);

    -- Obtener el precio del producto
    SELECT Precio INTO vPrecio FROM Producto WHERE ID = pProductoID;

    -- Obtener el IVA del impuesto
    SELECT IVA INTO vIVA FROM Impuesto WHERE ID = pImpuestoID;

    -- Calcular el valor total
    SET vValor_Total = vPrecio * pCantidad_Productos * (1 + vIVA);

    -- Insertar el producto_venta
    INSERT INTO Producto_Venta(VentaID, ProductoID, ImpuestoID, Cantidad_Productos, Valor_Total)
    VALUES (pVentaID, pProductoID, pImpuestoID, pCantidad_Productos, vValor_Total);
END$$

DELIMITER ;

--
-- Estructura de tabla para la tabla `impuesto`
--

DROP TABLE IF EXISTS `impuesto`;
CREATE TABLE IF NOT EXISTS `impuesto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `IVA` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`)
) AUTO_INCREMENT=2;

--
-- Volcado de datos para la tabla `impuesto`
--

INSERT INTO `impuesto` (`ID`, `IVA`) VALUES
(1, 0.19);

--
-- Estructura de tabla para la tabla `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
CREATE TABLE IF NOT EXISTS `movimiento` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Fecha_Gasto` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=2;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`ID`, `UsuarioID`, `Descripcion`, `Fecha_Gasto`) VALUES
(1, 1, 'Gasto en material de oficina', '2023-11-15');

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=9;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID`, `UsuarioID`, `Nombre`, `Precio`) VALUES
(1, 1, 'Dorilocos', 12305.00);

--
-- Estructura de tabla para la tabla `producto_gasto`
--

DROP TABLE IF EXISTS `producto_gasto`;
CREATE TABLE IF NOT EXISTS `producto_gasto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `MovimientoID` int NOT NULL,
  `ProductoID` int NOT NULL,
  `ImpuestoID` int NOT NULL,
  `Cantidad_Productos` int NOT NULL,
  `Valor_Total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MovimientoID` (`MovimientoID`),
  KEY `ProductoID` (`ProductoID`),
  KEY `ImpuestoID` (`ImpuestoID`)
) AUTO_INCREMENT=3;

--
-- Volcado de datos para la tabla `producto_gasto`
--

INSERT INTO `producto_gasto` (`ID`, `MovimientoID`, `ProductoID`, `ImpuestoID`, `Cantidad_Productos`, `Valor_Total`) VALUES
(1, 1, 1, 1, 100, 1249.50),
(2, 1, 1, 1, 561, 7009.70);

--
-- Estructura de tabla para la tabla `producto_venta`
--

DROP TABLE IF EXISTS `producto_venta`;
CREATE TABLE IF NOT EXISTS `producto_venta` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `VentaID` int NOT NULL,
  `ProductoID` int NOT NULL,
  `ImpuestoID` int NOT NULL,
  `Cantidad_Productos` int NOT NULL,
  `Valor_Total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `VentaID` (`VentaID`),
  KEY `ProductoID` (`ProductoID`),
  KEY `ImpuestoID` (`ImpuestoID`)
) AUTO_INCREMENT=3;

--
-- Volcado de datos para la tabla `producto_venta`
--

INSERT INTO `producto_venta` (`ID`, `VentaID`, `ProductoID`, `ImpuestoID`, `Cantidad_Productos`, `Valor_Total`) VALUES
(1, 1, 2, 1, 300, 2052.75),
(2, 1, 2, 1, 700, 4789.75);

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) AUTO_INCREMENT=2;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Leandro', 'admin@admin.com', NULL, '$2y$10$YZMVk6ig.NZy0PqnsqdtYe4s5NO8I.cfrU./HreEBGTtgdGHJerDO', NULL, '2024-04-10 10:10:34', '2024-04-10 10:10:34');

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE IF NOT EXISTS `venta` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Fecha_Venta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=4;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`ID`, `UsuarioID`, `Descripcion`, `Fecha_Venta`) VALUES
(1, 1, 'Holaaaa', '2023-11-17 05:00:00'),
(3, 1, 'Ejemplo', '2024-04-10 07:00:12'); 

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;