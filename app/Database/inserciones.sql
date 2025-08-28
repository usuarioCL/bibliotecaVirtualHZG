-- ==========================================
-- INSERCIONES DE EJEMPLO PARA BIBLIOTECA VIRTUAL ESCOLAR
-- ==========================================

-- Personas (3 ejemplos)
INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES
('García', 'Ana', 'DNI', '12345678', '987654321', 'Calle 1', 'ana.garcia@mail.com', 'Femenino'),
('Pérez', 'Luis', 'CE', '87654321', '912345678', 'Calle 2', 'luis.perez@mail.com', 'Masculino'),
('Torres', 'Elena', 'Pasaporte', 'AB1234567', '934567890', 'Calle 3', 'elena.torres@mail.com', 'Femenino');

-- Usuarios (3 ejemplos, uno por persona)
INSERT INTO usuarios (nomuser, passuser, nivelacceso, idpersona) VALUES
('anagarcia', 'passhash1', 'admin', 1),
('luisp', 'passhash2', 'docente', 2),
('elenat', 'passhash3', 'estudiante', 3);

-- Grupos (3 ejemplos)
INSERT INTO grupos (aniolectivo, grado, seccion, nivel) VALUES
(2025, '1', 'A', 'Primaria'),
(2025, '2', 'B', 'Primaria'),
(2025, '1', 'C', 'Secundaria');

-- Matriculas (3 ejemplos)
INSERT INTO matriculas (idgrupo, idpersona, fechamatricula, estadomatricula) VALUES
(1, 1, '2025-03-01', TRUE),
(2, 2, '2025-03-02', TRUE),
(3, 3, '2025-03-03', TRUE);

-- Tipos de Recurso (2 ejemplos)
INSERT INTO tiporecursos (tiporecurso) VALUES
('Libro físico'),
('Libro digital');

-- Categorías (3 ejemplos)
INSERT INTO categorias (categoria) VALUES
('Ciencias'),
('Literatura'),
('Matemática');

-- Subcategorías (6 ejemplos)
INSERT INTO subcategorias (subcategoria, idcategoria) VALUES
('Física', 1),
('Química', 1),
('Novela', 2),
('Poesía', 2),
('Álgebra', 3),
('Geometría', 3);

-- Editoriales (3 ejemplos)
INSERT INTO editoriales (editorial) VALUES
('Santillana'),
('Norma'),
('SM');

-- Autores (20 ejemplos)
INSERT INTO autores (apeautor, nomautor, nacionalidad) VALUES
('Asimov', 'Isaac', 'Rusa'),
('Rowling', 'J.K.', 'Británica'),
('Cortázar', 'Julio', 'Argentina'),
('Hawking', 'Stephen', 'Británica'),
('Allende', 'Isabel', 'Chilena'),
('King', 'Stephen', 'Estadounidense'),
('Dickens', 'Charles', 'Británica'),
('García Márquez', 'Gabriel', 'Colombiana'),
('Sagan', 'Carl', 'Estadounidense'),
('Poe', 'Edgar Allan', 'Estadounidense'),
('Borges', 'Jorge Luis', 'Argentina'),
('Galeano', 'Eduardo', 'Uruguaya'),
('Eco', 'Umberto', 'Italiana'),
('Nietzsche', 'Friedrich', 'Alemana'),
('Joyce', 'James', 'Irlandesa'),
('Tolstói', 'León', 'Rusa'),
('Kafka', 'Franz', 'Austrohúngara'),
('Austen', 'Jane', 'Británica'),
('Dostoievski', 'Fiódor', 'Rusa'),
('Cervantes', 'Miguel', 'Española');

-- Recursos (20 ejemplos)
INSERT INTO recursos (titulo, anio, numpaginas, encuadernacion, isbn, numedicion, rutaportada, estado, stock, urlLibro, idsubcategoria, ideditorial, idtiporecurso)
VALUES
('Fundamentos de Física', 2015, 800, 'Tapa dura', '9788408000001', '3ra', 'fotos/fisica.jpg', 'disponible', 7, NULL, 1, 1, 1),
('Química Básica', 2018, 600, 'Tapa blanda', '9788408000002', '2da', 'fotos/quimica.jpg', 'disponible', 4, NULL, 2, 2, 1),
('Harry Potter y la piedra filosofal', 2001, 320, 'Tapa dura', '9788408000003', '1ra', 'fotos/hp1.jpg', 'disponible', 10, 'libros/hp1.pdf', 3, 3, 2),
('Rayuela', 1963, 480, 'Tapa blanda', '9788408000004', '1ra', 'fotos/rayuela.jpg', 'disponible', 3, NULL, 3, 1, 1),
('Breve historia del tiempo', 1988, 256, 'Tapa dura', '9788408000005', '5ta', 'fotos/historia_tiempo.jpg', 'disponible', 2, NULL, 1, 2, 1),
('La casa de los espíritus', 1982, 400, 'Tapa dura', '9788408000006', '2da', 'fotos/casa_espiritus.jpg', 'disponible', 5, NULL, 3, 1, 1),
('It', 1986, 1138, 'Tapa blanda', '9788408000007', '1ra', 'fotos/it.jpg', 'disponible', 2, NULL, 3, 3, 1),
('Oliver Twist', 1839, 352, 'Tapa blanda', '9788408000008', '1ra', 'fotos/oliver.jpg', 'disponible', 3, NULL, 3, 2, 1),
('Cien años de soledad', 1967, 471, 'Tapa dura', '9788408000009', '2da', 'fotos/soledad.jpg', 'disponible', 8, NULL, 3, 1, 1),
('Cosmos', 1980, 365, 'Tapa dura', '9788408000010', '3ra', 'fotos/cosmos.jpg', 'disponible', 4, NULL, 1, 3, 1),
('El cuervo', 1845, 64, 'Tapa blanda', '9788408000011', '1ra', 'fotos/cuervo.jpg', 'disponible', 1, NULL, 4, 2, 1),
('Fervor de Buenos Aires', 1923, 74, 'Tapa blanda', '9788408000012', '1ra', 'fotos/fervor.jpg', 'disponible', 2, NULL, 4, 1, 1),
('El libro de los abrazos', 1989, 232, 'Tapa dura', '9788408000013', '1ra', 'fotos/abrazos.jpg', 'disponible', 3, NULL, 4, 2, 1),
('El nombre de la rosa', 1980, 512, 'Tapa dura', '9788408000014', '3ra', 'fotos/rosa.jpg', 'disponible', 2, NULL, 3, 3, 1),
('Así habló Zaratustra', 1883, 288, 'Tapa blanda', '9788408000015', '1ra', 'fotos/zaratustra.jpg', 'disponible', 2, NULL, 5, 1, 1),
('Ulises', 1922, 730, 'Tapa dura', '9788408000016', '2da', 'fotos/ulises.jpg', 'disponible', 1, NULL, 5, 2, 1),
('Guerra y paz', 1869, 1225, 'Tapa dura', '9788408000017', '1ra', 'fotos/guerra_paz.jpg', 'disponible', 2, NULL, 5, 3, 1),
('La metamorfosis', 1915, 74, 'Tapa blanda', '9788408000018', '1ra', 'fotos/metamorfosis.jpg', 'disponible', 3, NULL, 5, 1, 1),
('Orgullo y prejuicio', 1813, 432, 'Tapa dura', '9788408000019', '2da', 'fotos/orgullo.jpg', 'disponible', 2, NULL, 5, 2, 1),
('Don Quijote de la Mancha', 1605, 863, 'Tapa dura', '9788408000020', '4ta', 'fotos/quijote.jpg', 'disponible', 6, NULL, 5, 3, 1);

-- Detalle de autores (varias relaciones)
INSERT INTO detautores (idautor, idrecurso) VALUES
(1, 1),    -- Asimov - Fundamentos de Física
(2, 3),    -- Rowling - Harry Potter
(3, 4),    -- Cortázar - Rayuela
(4, 5),    -- Hawking - Breve historia del tiempo
(5, 6),    -- Allende - La casa de los espíritus
(6, 7),    -- King - It
(7, 8),    -- Dickens - Oliver Twist
(8, 9),    -- García Márquez - Cien años de soledad
(9, 10),   -- Sagan - Cosmos
(10, 11),  -- Poe - El cuervo
(11, 12),  -- Borges - Fervor de Buenos Aires
(12, 13),  -- Galeano - El libro de los abrazos
(13, 14),  -- Eco - El nombre de la rosa
(14, 15),  -- Nietzsche - Así habló Zaratustra
(15, 16),  -- Joyce - Ulises
(16, 17),  -- Tolstói - Guerra y paz
(17, 18),  -- Kafka - La metamorfosis
(18, 19),  -- Austen - Orgullo y prejuicio
(19, 20),  -- Dostoievski - Don Quijote de la Mancha
(20, 20);  -- Cervantes - Don Quijote de la Mancha

-- Ubicaciones (para algunos recursos físicos)
INSERT INTO ubicaciones (ubicacion, idrecurso) VALUES
('Estante A1', 1),
('Estante A2', 2),
('Estante B1', 4),
('Estante C1', 9),
('Estante D1', 20);

-- Tipos de sanción (2 ejemplos)
INSERT INTO tiposancion (tiposancion) VALUES
('Retraso en devolución'),
('Libro perdido');

-- Sanciones
INSERT INTO sanciones (idtiposancion, idpersona, detallesancion) VALUES
(1, 1, 'Devolvió el libro con 5 días de retraso'),
(2, 2, 'Perdió el libro prestado');

-- Préstamos (3 ejemplos)
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion) VALUES
(1, 1, 1, '2025-04-01 10:00:00', '2025-04-15 10:00:00'),
(2, 2, 3, '2025-04-02 11:00:00', '2025-04-16 11:00:00'),
(3, 3, 5, '2025-04-03 09:30:00', '2025-04-17 09:30:00');

-- Solicitudes de préstamo
INSERT INTO solicitud (validado, idprestamo) VALUES
(TRUE, 1),
(FALSE, 2);

-- Comentarios (3 ejemplos)
INSERT INTO comentarios (comentario, idusuario, idrecurso) VALUES
('Excelente libro, muy recomendable.', 1, 3),
('La narración es un poco lenta.', 2, 4),
('Perfecto para estudiantes.', 3, 1);

-- Reacciones (3 ejemplos)
INSERT INTO reacciones (tiporeaccion, idusuario, idrecurso) VALUES
('like', 1, 3),
('estrella', 2, 4),
('dislike', 3, 1);

-- Compartidos (3 ejemplos)
INSERT INTO compartidos (idusuario, idrecurso) VALUES
(1, 3),
(2, 4),
(3, 1);

-- Favoritos (3 ejemplos)
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(1, 3),
(2, 4),
(3, 1);