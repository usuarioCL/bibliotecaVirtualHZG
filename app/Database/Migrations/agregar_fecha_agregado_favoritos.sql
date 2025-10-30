-- Migración: Agregar campo fecha_agregado a la tabla favoritos
-- Fecha: 2025-10-30
-- Descripción: Agrega un campo TIMESTAMP para registrar cuándo se agregó un recurso a favoritos

-- Agregar columna fecha_agregado con valor por defecto CURRENT_TIMESTAMP
ALTER TABLE favoritos 
ADD COLUMN fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER idrecurso;

-- Actualizar registros existentes con la fecha actual (opcional)
-- UPDATE favoritos SET fecha_agregado = NOW() WHERE fecha_agregado IS NULL;
