-- ============================================
-- MIGRACIÓN: Crear tabla solicitudes_renovacion
-- FECHA: 2025-10-30
-- DESCRIPCIÓN: Tabla para gestionar solicitudes de renovación de préstamos
--              que los usuarios normales envían y que admin/docente aprueban
-- ============================================

USE biblioteca_virtual;

-- Verificar si la tabla existe antes de crearla
CREATE TABLE IF NOT EXISTS solicitudes_renovacion (
    idsolicitud_renovacion INT AUTO_INCREMENT PRIMARY KEY,
    idprestamo INT NOT NULL COMMENT 'Préstamo que se desea renovar',
    idusuario_solicita INT NOT NULL COMMENT 'Usuario que solicita la renovación',
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de la solicitud',
    fecha_vencimiento_actual DATETIME NOT NULL COMMENT 'Fecha de vencimiento actual del préstamo',
    nueva_fecha_inicio DATE NULL COMMENT 'Nueva fecha de inicio propuesta',
    nueva_fecha_devolucion DATE NOT NULL COMMENT 'Nueva fecha de devolución solicitada',
    motivo TEXT NULL COMMENT 'Motivo de la solicitud de renovación',
    estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado de la solicitud',
    idusuario_procesa INT NULL COMMENT 'Admin/docente que procesó la solicitud',
    fecha_procesado DATETIME NULL COMMENT 'Fecha y hora en que se procesó',
    motivo_rechazo TEXT NULL COMMENT 'Motivo del rechazo (si aplica)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo) ON DELETE CASCADE,
    FOREIGN KEY (idusuario_solicita) REFERENCES usuarios(idusuario) ON DELETE CASCADE,
    FOREIGN KEY (idusuario_procesa) REFERENCES usuarios(idusuario) ON DELETE SET NULL,
    
    INDEX idx_idprestamo (idprestamo),
    INDEX idx_estado (estado),
    INDEX idx_usuario_solicita (idusuario_solicita),
    INDEX idx_fecha_solicitud (fecha_solicitud)
) COMMENT='Solicitudes de renovación de préstamos pendientes de aprobación';

-- Mensaje de confirmación
SELECT 'Tabla solicitudes_renovacion creada exitosamente' AS mensaje;

-- Verificar la estructura de la tabla
DESCRIBE solicitudes_renovacion;
