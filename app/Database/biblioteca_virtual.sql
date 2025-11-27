-- BASE DE DATOS: Biblioteca Virtual Escolar

CREATE DATABASE biblioteca_virtual;
USE biblioteca_virtual;

-- TABLA: Personas
CREATE TABLE personas (
    idpersona INT AUTO_INCREMENT PRIMARY KEY,
    apellidos VARCHAR(50) NOT NULL,
    nombres VARCHAR(50) NOT NULL,
    tipodoc ENUM('DNI','CE','Pasaporte') NOT NULL,
    numerodoc VARCHAR(20) NOT NULL UNIQUE,
    telefono CHAR(15),
    direccion VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    genero ENUM('Masculino','Femenino','Otro')
);

-- TABLA: Usuarios
CREATE TABLE usuarios (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nomuser VARCHAR(30) NOT NULL UNIQUE,
    passuser VARCHAR(255) NOT NULL, -- hash de contraseña
    nivelacceso ENUM('admin','docente','estudiante') NOT NULL,
    idpersona INT UNIQUE,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);

-- TABLA: Grupos
CREATE TABLE grupos (
    idgrupo INT AUTO_INCREMENT PRIMARY KEY,
    aniolectivo YEAR NOT NULL,
    grado ENUM('1','2','3','4','5','6') NOT NULL,
    seccion CHAR(1) NOT NULL,
    nivel ENUM('Inicial','Primaria','Secundaria') NOT NULL
);

-- TABLA: Matriculas
CREATE TABLE matriculas (
    idmatricula INT AUTO_INCREMENT PRIMARY KEY,
    idgrupo INT NOT NULL,
    idpersona INT NOT NULL,
    fechamatricula DATE NOT NULL,
    estadomatricula BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (idgrupo) REFERENCES grupos(idgrupo),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);

-- TABLA: Tipos de Recurso
CREATE TABLE tiporecursos (
    idtiporecurso INT AUTO_INCREMENT PRIMARY KEY,
    tiporecurso VARCHAR(50) NOT NULL -- ej: Libro físico, Libro digital
);

-- TABLA: Categorías y Subcategorías
CREATE TABLE categorias (
    idcategoria INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(100) NOT NULL
);

CREATE TABLE subcategorias (
    idsubcategoria INT AUTO_INCREMENT PRIMARY KEY,
    subcategoria VARCHAR(100) NOT NULL,
    idcategoria INT NOT NULL,
    FOREIGN KEY (idcategoria) REFERENCES categorias(idcategoria)
);

-- TABLA: Editoriales
CREATE TABLE editoriales (
    ideditorial INT AUTO_INCREMENT PRIMARY KEY,
    editorial VARCHAR(100) NOT NULL
);

-- TABLA: Recursos (Base General)
CREATE TABLE recursos (
    idrecurso INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    anio SMALLINT,
    numpaginas SMALLINT UNSIGNED,
    isbn CHAR(13) UNIQUE,
    numedicion VARCHAR(50),
    estado ENUM('disponible','prestado','perdido') DEFAULT 'disponible',
    stock SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    nivel ENUM('Inicial','Primaria','Secundaria'),
    idsubcategoria INT,
    ideditorial INT,
    idtiporecurso INT,
    FOREIGN KEY (idsubcategoria) REFERENCES subcategorias(idsubcategoria),
    FOREIGN KEY (ideditorial) REFERENCES editoriales(ideditorial),
    FOREIGN KEY (idtiporecurso) REFERENCES tiporecursos(idtiporecurso)
);

-- TABLA: Recursos Físicos
CREATE TABLE recursos_fisicos (
    idrecurso INT PRIMARY KEY,
    portada VARCHAR(200), -- imagen de la portada
    encuadernacion VARCHAR(50),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

-- TABLA: Recursos Digitales
CREATE TABLE recursos_digitales (
    idrecurso INT PRIMARY KEY,
    portada VARCHAR(200), -- opcional: imagen de la carátula
    archivo VARCHAR(200) NULL, -- ruta del PDF/EPUB (opcional para permitir recursos sin archivo)
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

-- TABLAS: Autores y Detalle 
CREATE TABLE autores (
    idautor INT AUTO_INCREMENT PRIMARY KEY,
    apeautor VARCHAR(50),
    nomautor VARCHAR(50),
    nacionalidad VARCHAR(50)
);

CREATE TABLE detautores (
    iddetautor INT AUTO_INCREMENT PRIMARY KEY,
    idautor INT NOT NULL,
    idrecurso INT NOT NULL,
    FOREIGN KEY (idautor) REFERENCES autores(idautor),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

-- TABLA: Prestamos
CREATE TABLE prestamos (
    idprestamo INT AUTO_INCREMENT PRIMARY KEY,
    idmatricula INT NOT NULL,
    idusuario INT NOT NULL, -- quien registra el préstamo
    idrecurso INT NOT NULL, -- recurso prestado
    fechaprestamo DATETIME NOT NULL,
    fechadevolucion DATETIME,
    cantidad INT NOT NULL DEFAULT 1 COMMENT 'Cantidad de ejemplares prestados en este préstamo',
    observaciones_devolucion TEXT NULL COMMENT 'Observaciones registradas al momento de la devolución del préstamo',
    fechahoravalidacion DATETIME,
    fechahoraretorno DATETIME,
    FOREIGN KEY (idmatricula) REFERENCES matriculas(idmatricula),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

CREATE TABLE solicitud (
    idsolicitud INT AUTO_INCREMENT PRIMARY KEY,     -- ID único de la solicitud
    validado BOOLEAN DEFAULT FALSE,                 -- Si está aprobada/rechazada
    idprestamo INT NULL,                            -- Se asigna solo cuando se aprueba
    idmatricula INT,                                -- Matrícula del solicitante
    idusuario INT,                                  -- Usuario que solicita
    idrecurso INT,                                  -- Recurso solicitado
    fechaprestamo DATETIME,                         -- Fecha/hora inicio solicitada
    fechadevolucion DATETIME,                       -- Fecha/hora fin solicitada
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Cuándo se creó la solicitud
    motivo_rechazo TEXT NULL,                       -- Si se rechaza, el motivo
    fecha_procesado TIMESTAMP NULL,                 -- Cuándo se procesó (aprobó/rechazó)
    
    -- Claves foráneas
    FOREIGN KEY (idmatricula) REFERENCES matriculas(idmatricula),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso),
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo)
);
-- TABLA: Renovaciones de préstamos (historial de renovaciones aprobadas)
CREATE TABLE IF NOT EXISTS renovaciones_prestamo (
    idrenovacion INT AUTO_INCREMENT PRIMARY KEY,
    idprestamo INT NOT NULL,
    fecha_renovacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_vencimiento_anterior DATETIME NOT NULL,
    fecha_vencimiento_nueva DATETIME NOT NULL,
    motivo TEXT,
    usuario_renueva INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo) ON DELETE CASCADE,
    FOREIGN KEY (usuario_renueva) REFERENCES usuarios(idusuario),
    
    INDEX idx_idprestamo (idprestamo),
    INDEX idx_fecha_renovacion (fecha_renovacion)
);

-- TABLA: Solicitudes de renovación (para usuarios que piden renovar un préstamo)
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

-- TABLAS: Sanciones y Tipos
CREATE TABLE tiposancion (
    idtiposancion INT AUTO_INCREMENT PRIMARY KEY,
    tiposancion VARCHAR(80) NOT NULL
);

CREATE TABLE sanciones (
    idsancion INT AUTO_INCREMENT PRIMARY KEY,
    idtiposancion INT NOT NULL,
    idprestamo INT NULL,
    idpersona INT NOT NULL,
    detallesancion VARCHAR(200),
    fecha_sancion DATE NOT NULL DEFAULT (CURRENT_DATE),
    fecha_inicio DATE NULL,
    fecha_vencimiento DATE NULL,
    estado_sancion ENUM('activa', 'cumplida', 'cancelada', 'suspendida') NOT NULL DEFAULT 'activa',
    duracion_dias INT NULL,
    usuario_registra INT NULL,
    usuario_levanta INT NULL,
    fecha_levantamiento DATETIME NULL,
    motivo_levantamiento TEXT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idtiposancion) REFERENCES tiposancion(idtiposancion),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
    FOREIGN KEY (usuario_registra) REFERENCES usuarios(idusuario) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (usuario_levanta) REFERENCES usuarios(idusuario) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo) ON DELETE SET NULL ON UPDATE CASCADE,
    
    INDEX idx_sanciones_estado (estado_sancion),
    INDEX idx_sanciones_fecha_sancion (fecha_sancion),
    INDEX idx_sanciones_fecha_vencimiento (fecha_vencimiento),
    INDEX idx_sanciones_persona_estado (idpersona, estado_sancion),
    INDEX idx_sanciones_prestamo (idprestamo)
);



-- TABLA: Ubicaciones (para libros físicos)
CREATE TABLE ubicaciones (
    idubicacion INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion VARCHAR(100) NOT NULL,
    idrecurso INT NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);


-- TABLAS: Interacciones Sociales
CREATE TABLE comentarios (
    idcomentario INT AUTO_INCREMENT PRIMARY KEY,
    comentario TEXT NOT NULL,
    idusuario INT NOT NULL,
    idrecurso INT NOT NULL,
    fechahoracomentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

CREATE TABLE reacciones (
    idreaccion INT AUTO_INCREMENT PRIMARY KEY,
    tiporeaccion ENUM('like','dislike','estrella') NOT NULL,
    idusuario INT NOT NULL,
    idrecurso INT NOT NULL,
    UNIQUE (idusuario, idrecurso), -- evita reacciones duplicadas
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

CREATE TABLE compartidos (
    idcompartido INT AUTO_INCREMENT PRIMARY KEY,
    idusuario INT NOT NULL,
    idrecurso INT NOT NULL,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

CREATE TABLE favoritos (
    idfavorito INT AUTO_INCREMENT PRIMARY KEY,
    idusuario INT NOT NULL,
    idrecurso INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

CREATE TABLE ejemplares_fisicos (
    idejemplar INT AUTO_INCREMENT PRIMARY KEY,
    idrecurso INT NOT NULL,
    codigo_ejemplar VARCHAR(20) NOT NULL UNIQUE,
    estado_ejemplar ENUM('disponible','prestado','dañado','perdido','mantenimiento') DEFAULT 'disponible',
    ubicacion VARCHAR(100), 
    estado_fisico ENUM('excelente','bueno','regular','malo','muy_malo'),
    observaciones TEXT,
    fecha_ingreso DATE DEFAULT (CURRENT_DATE),
    fecha_ultima_revision DATE,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso) ON DELETE CASCADE,
    INDEX idx_codigo_ejemplar (codigo_ejemplar),
    INDEX idx_estado_ejemplar (estado_ejemplar),
    INDEX idx_activo (activo)
);

CREATE TABLE historial_usuarios (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    accion VARCHAR(100) NOT NULL,
    usuario_actor VARCHAR(50) NOT NULL,
    usuario_afectado VARCHAR(50),
    tipo_usuario ENUM('admin', 'docente', 'estudiante') NOT NULL,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    detalles TEXT,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA: notificaciones
-- DESCRIPCIÓN: Sistema de notificaciones para usuarios
-- ÚLTIMA MODIFICACIÓN: 2025-10-28
-- CAMBIOS RECIENTES:
--   - Agregado tipo 'devolucion' y 'sancion' al ENUM tipo
--   - Agregado campo idsancion para notificaciones de sanciones
--   - Agregado índice idx_sancion para mejorar consultas
-- ============================================
CREATE TABLE notificaciones (
    idnotificacion INT AUTO_INCREMENT PRIMARY KEY,
    idusuario INT NOT NULL,
    -- MODIFICADO 2025-10-28: Agregados tipos 'devolucion' y 'sancion'
    tipo ENUM('aprobacion', 'rechazo', 'vencimiento', 'renovacion', 'devolucion', 'sancion') NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    leida BOOLEAN DEFAULT FALSE,
    idprestamo INT NULL,
    idsolicitud INT NULL,
    -- AGREGADO 2025-10-28: Campo para relacionar notificaciones con sanciones
    idsancion INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida_at TIMESTAMP NULL,
    
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo),
    FOREIGN KEY (idsolicitud) REFERENCES solicitud(idsolicitud),
    -- AGREGADO 2025-10-28: Clave foránea para sanciones
    FOREIGN KEY (idsancion) REFERENCES sanciones(idsancion) ON DELETE CASCADE,
    
    INDEX idx_usuario_leida (idusuario, leida),
    INDEX idx_created_at (created_at),
    -- AGREGADO 2025-10-28: Índice para consultas de sanciones
    INDEX idx_sancion (idsancion)
);

-- Historial de acciones de usuarios
CREATE INDEX idx_historial_usuario_actor ON historial_usuarios(usuario_actor);
CREATE INDEX idx_historial_usuario_afectado ON historial_usuarios(usuario_afectado);
CREATE INDEX idx_historial_fecha ON historial_usuarios(fecha_accion);
CREATE INDEX idx_historial_accion ON historial_usuarios(accion);
CREATE INDEX idx_historial_tipo_usuario ON historial_usuarios(tipo_usuario);

DELIMITER //

CREATE PROCEDURE GenerarCodigoEjemplar(
    IN p_idrecurso INT,
    OUT p_codigo_ejemplar VARCHAR(20)
)
BEGIN
    DECLARE v_titulo VARCHAR(150);
    DECLARE v_prefijo VARCHAR(4);
    DECLARE v_siguiente_numero INT;
    DECLARE v_codigo VARCHAR(20);
    
    SELECT titulo INTO v_titulo 
    FROM recursos 
    WHERE idrecurso = p_idrecurso;
    
    SET v_prefijo = LOWER(REPLACE(SUBSTRING(v_titulo, 1, 4), ' ', ''));
    
    IF LENGTH(v_prefijo) < 4 THEN
        SET v_prefijo = LOWER(REPLACE(v_titulo, ' ', ''));
    END IF;
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_ejemplar, LENGTH(v_prefijo) + 2) AS UNSIGNED)), 0) + 1
    INTO v_siguiente_numero
    FROM ejemplares_fisicos 
    WHERE codigo_ejemplar LIKE CONCAT(v_prefijo, '-%')
    AND idrecurso = p_idrecurso;
    
    SET v_codigo = CONCAT(v_prefijo, '-', LPAD(v_siguiente_numero, 3, '0'));
    
    SET p_codigo_ejemplar = v_codigo;
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE CrearEjemplaresParaRecurso(
    IN p_idrecurso INT,
    IN p_cantidad INT
)
BEGIN
    DECLARE v_contador INT DEFAULT 1;
    DECLARE v_codigo_ejemplar VARCHAR(20);
    DECLARE v_exit_handler INT DEFAULT 0;
    
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET v_exit_handler = 1;
    
    IF NOT EXISTS (SELECT 1 FROM recursos WHERE idrecurso = p_idrecurso) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El recurso especificado no existe';
    END IF;
    
    WHILE v_contador <= p_cantidad AND v_exit_handler = 0 DO

        CALL GenerarCodigoEjemplar(p_idrecurso, v_codigo_ejemplar);
        
        INSERT INTO ejemplares_fisicos (idrecurso, codigo_ejemplar, estado_ejemplar, estado_fisico, fecha_ultima_revision)
        VALUES (p_idrecurso, v_codigo_ejemplar, 'disponible', 'excelente', CURRENT_DATE);
        
        SET v_contador = v_contador + 1;
    END WHILE;
    
    IF v_exit_handler = 1 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al crear ejemplares';
    END IF;
END //

DELIMITER ;


CREATE VIEW vista_ejemplares_completos AS
SELECT 
    e.idejemplar,
    e.idrecurso,
    e.codigo_ejemplar,
    e.estado_ejemplar,
    e.estado_fisico,
    e.ubicacion,
    e.observaciones,
    e.fecha_ingreso,
    e.fecha_ultima_revision,
    e.activo,
    r.titulo,
    r.anio,
    r.isbn,
    r.numedicion,
    ed.editorial,
    c.categoria,
    s.subcategoria,
    t.tiporecurso
FROM ejemplares_fisicos e
INNER JOIN recursos r ON e.idrecurso = r.idrecurso
INNER JOIN editoriales ed ON r.ideditorial = ed.ideditorial
INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria
INNER JOIN categorias c ON s.idcategoria = c.idcategoria
INNER JOIN tiporecursos t ON r.idtiporecurso = t.idtiporecurso
WHERE e.activo = TRUE;


DELIMITER //

CREATE TRIGGER tr_actualizar_stock_after_insert
AFTER INSERT ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = NEW.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = NEW.idrecurso;
END //

CREATE TRIGGER tr_actualizar_stock_after_update
AFTER UPDATE ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = NEW.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = NEW.idrecurso;
END //

CREATE TRIGGER tr_actualizar_stock_after_delete
AFTER DELETE ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = OLD.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = OLD.idrecurso;
END //

DELIMITER ;

