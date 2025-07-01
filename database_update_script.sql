-- ==============================================
-- Script para actualizar la base de datos
-- Sistema MKL Financiero - Nuevas funcionalidades
-- ==============================================

-- Agregar campo Tipo a la tabla producto
ALTER TABLE producto ADD COLUMN Tipo ENUM('producto', 'gasto', 'servicio') DEFAULT 'producto' AFTER Cantidad;

-- Agregar campo Clasificacion a la tabla producto (como JSON)
ALTER TABLE producto ADD COLUMN Clasificacion JSON NULL AFTER Tipo;

-- Agregar campo Descripcion para servicios y gastos
ALTER TABLE producto ADD COLUMN Descripcion TEXT NULL AFTER Clasificacion;

-- Actualizar registros existentes para tener tipo 'producto' por defecto
UPDATE producto SET Tipo = 'producto' WHERE Tipo IS NULL;

-- Opcional: Si quieres que el campo Tipo no sea NULL
-- ALTER TABLE producto MODIFY COLUMN Tipo ENUM('producto', 'gasto', 'servicio') NOT NULL DEFAULT 'producto';

-- Verificar que los cambios se aplicaron correctamente
-- SELECT * FROM producto LIMIT 5;

-- COMENTARIOS:
-- - El campo Tipo permite clasificar productos como: producto, gasto, servicio
-- - El campo Clasificacion (JSON) almacena etiquetas personalizadas solo para productos tipo 'producto'
-- - El campo Descripcion (TEXT) almacena descripciones detalladas solo para tipos 'gasto' y 'servicio'

-- NOTA: Este script debe ejecutarse después de implementar los cambios de código
-- y antes de usar las nuevas funcionalidades del sistema.

-- ==============================================
-- COMANDO ADICIONAL - CAMPO DESCRIPCIÓN
-- ==============================================

-- Agregar campo Descripción para tipos servicio y gasto (comando adicional al final)
ALTER TABLE producto ADD COLUMN Descripcion TEXT NULL AFTER Clasificacion; 