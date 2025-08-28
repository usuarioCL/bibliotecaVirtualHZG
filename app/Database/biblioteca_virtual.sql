-- ==========================================
-- BASE DE DATOS: Biblioteca Virtual Escolar
-- ==========================================
CREATE DATABASE biblioteca_virtual;
USE biblioteca_virtual;

-- ==============================
-- TABLA: Personas
-- ==============================
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

-- ==============================
-- TABLA: Usuarios
-- ==============================
CREATE TABLE usuarios (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nomuser VARCHAR(30) NOT NULL UNIQUE,
    passuser VARCHAR(255) NOT NULL, -- hash de contraseña
    nivelacceso ENUM('admin','docente','estudiante') NOT NULL,
    idpersona INT UNIQUE,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);

-- ==============================
-- TABLA: Grupos
-- ==============================
CREATE TABLE grupos (
    idgrupo INT AUTO_INCREMENT PRIMARY KEY,
    aniolectivo YEAR NOT NULL,
    grado ENUM('1','2','3','4','5','6') NOT NULL,
    seccion CHAR(1) NOT NULL,
    nivel ENUM('Inicial','Primaria','Secundaria') NOT NULL
);

-- ==============================
-- TABLA: Matriculas
-- ==============================
CREATE TABLE matriculas (
    idmatricula INT AUTO_INCREMENT PRIMARY KEY,
    idgrupo INT NOT NULL,
    idpersona INT NOT NULL,
    fechamatricula DATE NOT NULL,
    estadomatricula BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (idgrupo) REFERENCES grupos(idgrupo),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);

-- ==============================
-- TABLA: Tipos de Recurso
-- ==============================
CREATE TABLE tiporecursos (
    idtiporecurso INT AUTO_INCREMENT PRIMARY KEY,
    tiporecurso VARCHAR(50) NOT NULL -- ej: Libro físico, Libro digital
);

-- ==============================
-- TABLA: Categorías y Subcategorías
-- ==============================
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

-- ==============================
-- TABLA: Editoriales
-- ==============================
CREATE TABLE editoriales (
    ideditorial INT AUTO_INCREMENT PRIMARY KEY,
    editorial VARCHAR(100) NOT NULL
);

-- ==============================
-- TABLA: Recursos
-- ==============================
CREATE TABLE recursos (
    idrecurso INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    anio SMALLINT,
    numpaginas SMALLINT UNSIGNED NOT NULL,
    encuadernacion VARCHAR(50),
    isbn CHAR(13) UNIQUE,
    numedicion VARCHAR(50),
    rutaportada VARCHAR(200),
    estado ENUM('disponible','prestado','perdido') DEFAULT 'disponible',
    stock SMALLINT UNSIGNED NOT NULL,
    urlLibro VARCHAR(200), -- para libros digitales
    idsubcategoria INT,
    ideditorial INT,
    idtiporecurso INT,
    FOREIGN KEY (idsubcategoria) REFERENCES subcategorias(idsubcategoria),
    FOREIGN KEY (ideditorial) REFERENCES editoriales(ideditorial),
    FOREIGN KEY (idtiporecurso) REFERENCES tiporecursos(idtiporecurso)
);



-- ==============================
-- TABLAS: Autores y Detalle
-- ==============================
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

-- ==============================
-- TABLA: Prestamos
-- ==============================
CREATE TABLE prestamos (
    idprestamo INT AUTO_INCREMENT PRIMARY KEY,
    idmatricula INT NOT NULL,
    idusuario INT NOT NULL, -- quien registra el préstamo
    idrecurso INT NOT NULL, -- recurso prestado
    fechaprestamo DATETIME NOT NULL,
    fechadevolucion DATETIME,
    fechahoravalidacion DATETIME,
    fechahoraretorno DATETIME,
    FOREIGN KEY (idmatricula) REFERENCES matriculas(idmatricula),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

-- ==============================
-- TABLA: Solicitudes de Prestamo
-- ==============================
CREATE TABLE solicitud (
    idsolicitud INT AUTO_INCREMENT PRIMARY KEY,
    validado BOOLEAN DEFAULT FALSE,
    idprestamo INT NOT NULL,
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo)
);

-- ==============================
-- TABLAS: Sanciones y Tipos
-- ==============================
CREATE TABLE tiposancion (
    idtiposancion INT AUTO_INCREMENT PRIMARY KEY,
    tiposancion VARCHAR(80) NOT NULL
);

CREATE TABLE sanciones (
    idsancion INT AUTO_INCREMENT PRIMARY KEY,
    idtiposancion INT NOT NULL,
    idpersona INT NOT NULL,
    detallesancion VARCHAR(200),
    FOREIGN KEY (idtiposancion) REFERENCES tiposancion(idtiposancion),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);

-- ==============================
-- TABLA: Ubicaciones (para libros físicos)
-- ==============================
CREATE TABLE ubicaciones (
    idubicacion INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion VARCHAR(100) NOT NULL,
    idrecurso INT NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
);

-- ==============================
-- TABLAS: Interacciones Sociales
-- ==============================
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
