-- Script para permitir NULL en la columna archivo de recursos_digitales
-- Ejecutar este script si ya tienes la tabla creada

USE biblioteca_virtual;

-- Modificar la columna para permitir NULL
ALTER TABLE recursos_digitales 
MODIFY COLUMN archivo VARCHAR(200) NULL COMMENT 'Ruta del PDF/EPUB (opcional)';

-- Verificar el cambio
DESCRIBE recursos_digitales;

SELECT 'Columna archivo ahora permite valores NULL' AS Resultado;
