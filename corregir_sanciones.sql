-- Script para verificar y corregir la estructura de las tablas de sanciones

-- 1. Verificar estructura actual de tiposancion
DESCRIBE tiposancion;

-- 2. Verificar estructura actual de sanciones
DESCRIBE sanciones;

-- 3. Si la tabla tiposancion existe pero sin la columna descripcion, agregarla
ALTER TABLE tiposancion 
ADD COLUMN IF NOT EXISTS descripcion TEXT NULL,
ADD COLUMN IF NOT EXISTS activo BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- 4. Si la tabla sanciones existe pero sin los nuevos campos, agregarlos
ALTER TABLE sanciones 
ADD COLUMN IF NOT EXISTS fecha_sancion DATE NOT NULL DEFAULT (CURRENT_DATE),
ADD COLUMN IF NOT EXISTS fecha_vencimiento DATE NULL,
ADD COLUMN IF NOT EXISTS estado_sancion ENUM('activa', 'cumplida', 'cancelada') DEFAULT 'activa',
ADD COLUMN IF NOT EXISTS usuario_registra INT NULL,
ADD COLUMN IF NOT EXISTS fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS observaciones TEXT NULL,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- 5. Agregar claves foráneas si no existen
ALTER TABLE sanciones 
ADD CONSTRAINT IF NOT EXISTS fk_sanciones_usuario_registra 
FOREIGN KEY (usuario_registra) REFERENCES usuarios(idusuario);

-- 6. Agregar índices si no existen
ALTER TABLE sanciones 
ADD INDEX IF NOT EXISTS idx_fecha_sancion (fecha_sancion),
ADD INDEX IF NOT EXISTS idx_estado_sancion (estado_sancion),
ADD INDEX IF NOT EXISTS idx_fecha_vencimiento (fecha_vencimiento),
ADD INDEX IF NOT EXISTS idx_idpersona (idpersona);

-- 7. Insertar tipos de sanción (solo si no existen)
INSERT IGNORE INTO tiposancion (tiposancion, descripcion) VALUES
('Retraso', 'Retraso en la devolución de materiales'),
('Pérdida', 'Pérdida de material bibliográfico'),
('Mal uso', 'Daño o mal uso del material'),
('Incumplimiento de normas', 'Violación de las normas de la biblioteca'),
('Comportamiento inadecuado', 'Conducta inapropiada en la biblioteca');

-- 8. Verificar que todo se creó correctamente
SELECT 'Verificación completada' as resultado;
SELECT COUNT(*) as total_tipos_sancion FROM tiposancion;
SELECT COUNT(*) as total_sanciones FROM sanciones;
