USE bibliotecavirtualhzg;

-- 1. Modificar el ENUM para agregar 'sancion'
ALTER TABLE notificaciones 
MODIFY COLUMN tipo ENUM('aprobacion', 'rechazo', 'vencimiento', 'renovacion', 'devolucion', 'sancion') NOT NULL;

-- 2. Agregar columna idsancion
ALTER TABLE notificaciones 
ADD COLUMN idsancion INT NULL AFTER idsolicitud;

-- 3. Agregar clave foránea
ALTER TABLE notificaciones 
ADD CONSTRAINT fk_notificaciones_sancion 
FOREIGN KEY (idsancion) REFERENCES sanciones(idsancion) ON DELETE CASCADE;

-- 4. Agregar índice
ALTER TABLE notificaciones 
ADD INDEX idx_sancion (idsancion);

-- Verificar estructura
DESCRIBE notificaciones;
