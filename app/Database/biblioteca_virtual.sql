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
    archivo VARCHAR(200) NOT NULL, -- ruta del PDF/EPUB
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
-- TABLA:renovaciones
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

CREATE TABLE notificaciones (
    idnotificacion INT AUTO_INCREMENT PRIMARY KEY,
    idusuario INT NOT NULL,
    tipo ENUM('aprobacion', 'rechazo', 'vencimiento', 'renovacion') NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    leida BOOLEAN DEFAULT FALSE,
    idprestamo INT NULL,
    idsolicitud INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida_at TIMESTAMP NULL,
    
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo),
    FOREIGN KEY (idsolicitud) REFERENCES solicitud(idsolicitud),
    
    INDEX idx_usuario_leida (idusuario, leida),
    INDEX idx_created_at (created_at)
);