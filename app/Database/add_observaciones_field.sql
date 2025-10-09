-- Script para añadir campo observaciones a la tabla préstamos
-- Este script comprueba si la columna ya existe antes de agregarla

USE biblioteca_virtual;

-- Verificar si la columna observaciones existe en la tabla préstamos
SET @exists := (
    SELECT COUNT(*)
    FROM information_schema.columns
    WHERE table_name = 'prestamos'
    AND column_name = 'observaciones'
    AND table_schema = 'biblioteca_virtual'
);

-- Si la columna no existe, la añadimos
SET @query = IF(@exists = 0,
    'ALTER TABLE prestamos ADD COLUMN observaciones TEXT NULL AFTER fechahoraretorno',
    'SELECT "La columna observaciones ya existe en la tabla prestamos."'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
