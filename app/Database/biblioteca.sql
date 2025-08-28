CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- TABLA personas
CREATE TABLE personas (
    idpersona 	INT AUTO_INCREMENT PRIMARY KEY,
    apellidos 	VARCHAR(50) NOT NULL,
    nombres 	VARCHAR(50) NOT NULL,
    tipodoc		VARCHAR(20) NOT NULL,
    numerodoc 	CHAR(7) NOT NULL,
    telefono 	CHAR(9),
    direccion 	VARCHAR(100),
    email 		VARCHAR(100) NOT NULL,
    genero 		VARCHAR(50)
)ENGINE = INNODB;

INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES
('Gonzalez','Maria','DNI','1234567','987654321','Av. Lima 123','maria@example.com','Femenino'),
('Ramirez','Juan','DNI','2345678','987654322','Jr. Peru 456','juan@example.com','Masculino'),
('Lopez','Ana','DNI','3456789','987654323','Av. Grau 789','ana@example.com','Femenino');

-- TABLA autores
CREATE TABLE autores (
    idautor 		INT AUTO_INCREMENT PRIMARY KEY,
    apeautor 		CHAR(18),
    nacionalidad 	CHAR(18),
    nomautor CHAR(18)
) ENGINE = INNODB;

INSERT INTO autores (apeautor, nacionalidad, nomautor) VALUES
('García', 'Colombiano', 'Gabriel'),
('Cervantes', 'Español', 'Miguel'),
('Allende', 'Chilena', 'Isabel');

-- TABLA grupos
CREATE TABLE grupos (
    idgrupo 	INT AUTO_INCREMENT PRIMARY KEY,
    anioinicio 	DATE NOT NULL,
    grado 		CHAR(3) NOT NULL,
    seccion 	CHAR(1) NOT NULL,
    nivel 		CHAR(20) NOT NULL
)ENGINE = INNODB;

INSERT INTO grupos (anioinicio, grado, seccion, nivel) VALUES
('2023-03-01','5','A','Primaria'),
('2023-03-01','2','B','Secundaria'),
('2023-03-01','1','C','Primaria');

-- TABLA matriculas
CREATE TABLE matriculas (
    idmatricula 	  INT AUTO_INCREMENT PRIMARY KEY,
    idgrupo		 	  INT NOT NULL,
    idpersona 		  INT NOT NULL,
    fechaMatricula  DATE NOT NULL,
    estadoMatricula BOOLEAN NOT NULL,
    FOREIGN KEY (idgrupo) REFERENCES grupos(idgrupo),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
)ENGINE = INNODB;

INSERT INTO matriculas (idgrupo, idpersona, fechaMatricula, estadoMatricula) VALUES
(1,1,'2023-03-05',1),
(2,2,'2023-03-06',1),
(3,3,'2023-03-07',0);

-- TABLA usuarios
CREATE TABLE usuarios (
    idusuario 	 INT AUTO_INCREMENT PRIMARY KEY,
    nomuser 	 VARCHAR(30) NOT NULL,
    passuser 	 CHAR(7) NOT NULL,
    nivelAcceso VARCHAR(15) NOT NULL,
    idpersona 	 INT NOT NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
)ENGINE = INNODB;

INSERT INTO usuarios (nomuser, passuser, nivelAcceso, idpersona) VALUES
('maria123','1234567','Alumno',1),
('juan456','7654321','Docente',2),
('ana789','1112223','Admin',3);

-- TABLA tipossancion
CREATE TABLE tipossancion (
    idtiposancion INT AUTO_INCREMENT PRIMARY KEY,
    tiposancion 	VARCHAR(80) NOT NULL
)ENGINE = INNODB;

INSERT INTO tipossancion (tiposancion) VALUES
('Retraso en devolución'),
('Pérdida de libro'),
('Maltrato de material');

-- TABLA sanciones
CREATE TABLE sanciones (
    idsancion 		 INT AUTO_INCREMENT PRIMARY KEY,
    idtiposancion  INT NOT NULL,
    detalleSancion VARCHAR(80) NOT NULL,
    FOREIGN KEY (idtiposancion) REFERENCES tipossancion(idtiposancion)
)ENGINE = INNODB;

INSERT INTO sanciones (idtiposancion, detalleSancion) VALUES
(1,'Devolución con 5 días de retraso'),
(2,'Libro extraviado'),
(3,'Libro con páginas rotas');

-- TABLA prestamos
CREATE TABLE prestamos (
    idprestamo   			INT AUTO_INCREMENT PRIMARY KEY,
    idmatricula  			INT NOT NULL,
    idusuario    			INT NOT NULL,
    fechaHoraPrestamo   DATETIME NOT NULL,
    fechaHoraValidacion DATETIME,
    fechaHoraRetorno    DATETIME,
    idsancion           INT NOT NULL,
    FOREIGN KEY (idmatricula) REFERENCES matriculas(idmatricula),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idsancion) REFERENCES sanciones(idsancion)
)ENGINE = INNODB;

INSERT INTO prestamos (idmatricula, idusuario, fechaHoraPrestamo, fechaHoraValidacion, fechaHoraRetorno, idsancion) VALUES
(1,1,'2023-04-01 10:00:00','2023-04-01 10:15:00','2023-04-10 09:00:00',1),
(2,2,'2023-04-05 11:00:00','2023-04-05 11:05:00','2023-04-12 15:00:00',2),
(3,3,'2023-04-07 09:30:00','2023-04-07 09:45:00','2023-04-09 12:00:00',3);

-- TABLA estadoprestamo
CREATE TABLE estadoprestamo (
    idestadoprestamo INT AUTO_INCREMENT PRIMARY KEY,
    estado 				VARCHAR(50) NOT NULL,
    idprestamo 		INT NOT NULL,
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo)
)ENGINE = INNODB;

INSERT INTO estadoprestamo (estado,idprestamo) VALUES
('Devuelto',1),
('Pendiente',2),
('Retrasado',3);

-- TABLA solicitud
CREATE TABLE solicitud (
    idestadoPrestamo INT AUTO_INCREMENT PRIMARY KEY,
    validado 			BOOLEAN NOT NULL,
    idprestamo       INT NOT NULL,
    FOREIGN KEY (idprestamo) REFERENCES prestamos(idprestamo)
)ENGINE = INNODB;

INSERT INTO solicitud (validado, idprestamo) VALUES
(1,1),
(0,2),
(1,3);

-- TABLA editoriales
CREATE TABLE editoriales (
    ideditorial INT AUTO_INCREMENT PRIMARY KEY,
    editorial   VARCHAR(30)
)ENGINE = INNODB;

INSERT INTO editoriales (editorial) VALUES
('Santillana'),
('SM'),
('Norma');

-- TABLA categorias
CREATE TABLE categorias (
    idcategoria INT AUTO_INCREMENT PRIMARY KEY,
    categoria   VARCHAR(30) NOT NULL
)ENGINE = INNODB;

INSERT INTO categorias (categoria) VALUES
('Matemáticas'),
('Historia'),
('Literatura');

-- TABLA subcategorias
CREATE TABLE subcategorias (
    idsubcategoria INT AUTO_INCREMENT PRIMARY KEY,
    subcategoria 	 VARCHAR(30) NOT NULL,
    idcategoria 	 INT NOT NULL,
    FOREIGN KEY (idcategoria) REFERENCES categorias(idcategoria)
)ENGINE = INNODB;

INSERT INTO subcategorias (subcategoria,idcategoria) VALUES
('Álgebra',1),
('Historia del Perú',2),
('Novela',3);

-- TABLA tiporecursos
CREATE TABLE tiporecursos (
    idtiporecurso INT AUTO_INCREMENT PRIMARY KEY,
    tiporecurso 	VARCHAR(50) NOT NULL
)ENGINE = INNODB;

INSERT INTO tiporecursos (tiporecurso) VALUES
('Libro físico'),
('Libro digital'),
('Revista');

-- TABLA recursos
CREATE TABLE recursos (
    idrecurso 			INT AUTO_INCREMENT PRIMARY KEY,
    titulo 				VARCHAR(50) NOT NULL,
    año 			    DATE NOT NULL,
    numeroPaginas 	    INT NOT NULL,
    encuadernacion 	    VARCHAR(50),
    isbn 				CHAR(13),
    numeroEdicion 	    DATE NOT NULL,
    rutaPortada 		VARCHAR(80),
    rutaIndice 		    VARCHAR(80),
    estado 				VARCHAR(30) NOT NULL,
    stock 				INT NOT NULL,
    idsubcategoria 	    INT NOT NULL,
    ideditorial 		INT NOT NULL,
    idtiporecurso 	    INT NOT NULL,
    urlLibro 			VARCHAR(80) NOT NULL,
    FOREIGN KEY (idsubcategoria) REFERENCES subcategorias(idsubcategoria),
    FOREIGN KEY (ideditorial) REFERENCES editoriales(ideditorial),
    FOREIGN KEY (idtiporecurso) REFERENCES tiporecursos(idtiporecurso)
)ENGINE = INNODB;

INSERT INTO recursos (titulo,año,numeroPaginas,encuadernacion,isbn,numeroEdicion,rutaPortada,rutaIndice,estado,stock,idsubcategoria,ideditorial,idtiporecurso,urlLibro) VALUES
('Álgebra Básica','2020-01-01',200,'Tapa dura','9781234567890','2020-01-01','/portadas/algebra.jpg','/indices/algebra.pdf','Disponible',10,1,1,1,'/libros/algebra.pdf'),
('Historia del Perú','2019-01-01',300,'Tapa blanda','9789876543210','2019-01-01','/portadas/historia.jpg','/indices/historia.pdf','Prestado',5,2,2,1,'/libros/historia.pdf'),
('Cien Años de Soledad','2018-01-01',400,'Tapa dura','9781122334455','2018-01-01','/portadas/soledad.jpg','/indices/soledad.pdf','Disponible',3,3,3,2,'/libros/soledad.pdf');

-- TABLA comentarios
CREATE TABLE comentarios (
    idcomentario			INT AUTO_INCREMENT PRIMARY KEY,
    comentario 				TEXT NOT NULL,
    idusuario 				INT NOT NULL,
    idtiporecurso 			INT NOT NULL,
    fechahoracomentario 	DATETIME NOT NULL,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario),
    FOREIGN KEY (idtiporecurso) REFERENCES tiporecursos(idtiporecurso)
) ENGINE = INNODB;

INSERT INTO comentarios (comentario, idusuario, idtiporecurso, fechahoracomentario) VALUES
('Muy buen libro de álgebra, me ayudó mucho.', 1, 1, '2023-04-15 10:30:00'),
('La historia está bien documentada y fácil de entender.', 2, 1, '2023-04-16 11:00:00'),
('Una novela clásica, realmente atrapante.', 3, 2, '2023-04-17 09:45:00');

-- TABLA compartidos
CREATE TABLE compartidos (
    idcompartido 			INT AUTO_INCREMENT PRIMARY KEY,
    idrecurso 				INT NOT NULL,
    idusuario 				INT NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario)
) ENGINE = INNODB;

INSERT INTO compartidos (idrecurso, idusuario) VALUES
(1, 1),
(2, 2),
(3, 3);

-- TABLA reacciones
CREATE TABLE reacciones (
    idreaccion 		INT AUTO_INCREMENT PRIMARY KEY,
    tiporeaccion 	VARCHAR(40) NOT NULL,
    idrecurso 		INT NOT NULL,
    numreacciones 	INT(4) NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
) ENGINE = INNODB;

INSERT INTO reacciones (tiporeaccion, idrecurso, numreacciones) VALUES
('Me gusta', 1, 15),
('No me gusta', 2, 3),
('Me encanta', 3, 20);

-- TABLA ubicaciones
CREATE TABLE ubicaciones (
    idubicacion INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion 	VARCHAR(20) NOT NULL,
    idrecurso 	INT NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
) ENGINE = INNODB;

INSERT INTO ubicaciones (ubicacion, idrecurso) VALUES
('Estante A1', 1),
('Estante B3', 2),
('Estante C2', 3);

-- TABLA favoritos
CREATE TABLE favoritos (
    idfavorito 	INT AUTO_INCREMENT PRIMARY KEY,
    idpersona 	INT NOT NULL,
    idrecurso 	INT NOT NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso)
) ENGINE = INNODB;

INSERT INTO favoritos (idpersona, idrecurso) VALUES
(1, 1),
(2, 3),
(3, 2);
