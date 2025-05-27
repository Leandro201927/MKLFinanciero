DROP DATABASE IF EXISTS proyectointegrador;
CREATE DATABASE IF NOT EXISTS proyectointegrador;
USE proyectointegrador;

-- Set the character set and collation for the database
ALTER DATABASE proyectointegrador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Set the maximum key length
SET GLOBAL innodb_file_per_table = ON;

--
-- Estructura de tabla para la tabla `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
CREATE TABLE IF NOT EXISTS `movimiento` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Codigo` varchar(20) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `Fecha_Gasto` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=2;

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Cantidad` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=9;

--
-- Estructura de tabla para la tabla `producto_gasto`
--

DROP TABLE IF EXISTS `producto_gasto`;
CREATE TABLE IF NOT EXISTS `producto_gasto` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `MovimientoID` int NOT NULL,
  `ProductoID` int NOT NULL,
  `Cantidad_Productos` int NOT NULL,
  `Valor_Unitario` decimal(10,2) NOT NULL,
  `Valor_Total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MovimientoID` (`MovimientoID`),
  KEY `ProductoID` (`ProductoID`)
) AUTO_INCREMENT=3;

--
-- Estructura de tabla para la tabla `producto_venta`
--

DROP TABLE IF EXISTS `producto_venta`;
CREATE TABLE IF NOT EXISTS `producto_venta` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `VentaID` int NOT NULL,
  `ProductoID` int NOT NULL,
  `Cantidad_Productos` int NOT NULL,
  `Valor_Unitario` decimal(10,2) NOT NULL,
  `Valor_Total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `VentaID` (`VentaID`),
  KEY `ProductoID` (`ProductoID`)
) AUTO_INCREMENT=3;

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

-- INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
-- (1, 'Leandro', 'admin@admin.com', NULL, '$2y$10$YZMVk6ig.NZy0PqnsqdtYe4s5NO8I.cfrU./HreEBGTtgdGHJerDO', NULL, '2024-04-10 10:10:34', '2024-04-10 10:10:34');

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE IF NOT EXISTS `venta` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UsuarioID` int NOT NULL,
  `Codigo` varchar(20) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL,
  `Fecha_Venta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UsuarioID` (`UsuarioID`)
) AUTO_INCREMENT=4;

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;