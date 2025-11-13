-- ============================================
-- SEEDER COMPLETO - BIBLIOTECA VIRTUAL HZG
-- Datos de prueba para desarrollo
-- ============================================

USE biblioteca_virtual;

-- Limpiar datos existentes (opcional - descomentar si necesitas limpiar)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE detautores;
-- TRUNCATE TABLE recursos_digitales;
-- TRUNCATE TABLE recursos_fisicos;
-- TRUNCATE TABLE ejemplares_fisicos;
-- TRUNCATE TABLE recursos;
-- TRUNCATE TABLE usuarios;
-- TRUNCATE TABLE personas;
-- TRUNCATE TABLE grupos;
-- TRUNCATE TABLE subcategorias;
-- TRUNCATE TABLE categorias;
-- TRUNCATE TABLE editoriales;
-- TRUNCATE TABLE autores;
-- TRUNCATE TABLE tiporecursos;
-- SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- TIPOS DE RECURSOS
-- ============================================
INSERT INTO tiporecursos (tiporecurso) VALUES
('Libro físico'),
('Libro digital'),
('Revista física'),
('Revista digital'),
('Manual físico'),
('Manual digital');

-- ============================================
-- CATEGORÍAS Y SUBCATEGORÍAS
-- ============================================
INSERT INTO categorias (categoria) VALUES
('Literatura'),
('Ciencias'),
('Historia'),
('Matemáticas'),
('Arte'),
('Tecnología'),
('Idiomas'),
('Filosofía');

INSERT INTO subcategorias (subcategoria, idcategoria) VALUES
-- Literatura (1)
('Novela', 1),
('Poesía', 1),
('Teatro', 1),
('Cuento', 1),
-- Ciencias (2)
('Biología', 2),
('Química', 2),
('Física', 2),
('Astronomía', 2),
-- Historia (3)
('Historia Universal', 3),
('Historia del Perú', 3),
('Historia de América', 3),
-- Matemáticas (4)
('Álgebra', 4),
('Geometría', 4),
('Trigonometría', 4),
('Cálculo', 4),
-- Arte (5)
('Pintura', 5),
('Música', 5),
('Escultura', 5),
-- Tecnología (6)
('Informática', 6),
('Programación', 6),
('Robótica', 6),
-- Idiomas (7)
('Inglés', 7),
('Francés', 7),
('Quechua', 7),
-- Filosofía (8)
('Ética', 8),
('Lógica', 8),
('Filosofía Antigua', 8);

-- ============================================
-- EDITORIALES
-- ============================================
INSERT INTO editoriales (editorial) VALUES
('Editorial Santillana'),
('Editorial Norma'),
('Editorial SM'),
('Penguin Random House'),
('Alfaguara'),
('Planeta'),
('Anaya'),
('McGraw-Hill'),
('Pearson'),
('Oxford University Press');

-- ============================================
-- AUTORES
-- ============================================
INSERT INTO autores (nomautor, apeautor) VALUES
('Gabriel', 'García Márquez'),
('Mario', 'Vargas Llosa'),
('Isabel', 'Allende'),
('Jorge Luis', 'Borges'),
('Pablo', 'Neruda'),
('Julio', 'Cortázar'),
('Miguel de', 'Cervantes'),
('William', 'Shakespeare'),
('Jane', 'Austen'),
('Charles', 'Dickens'),
('J.K.', 'Rowling'),
('Stephen', 'King'),
('Dan', 'Brown'),
('Agatha', 'Christie'),
('Isaac', 'Asimov'),
('Stephen', 'Hawking'),
('Carl', 'Sagan'),
('Yuval Noah', 'Harari');

-- ============================================
-- PERSONAS
-- ============================================
INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES
-- Administrador
('Rodríguez García', 'Carlos Alberto', 'DNI', '12345678', '987654321', 'Av. Principal 123, Lima', 'admin@biblioteca.com', 'Masculino'),
-- Docentes
('Fernández López', 'María Elena', 'DNI', '23456789', '987654322', 'Jr. Los Olivos 456, Lima', 'docente1@biblioteca.com', 'Femenino'),
('Torres Mendoza', 'Juan Carlos', 'DNI', '34567890', '987654323', 'Av. La Marina 789, Lima', 'docente2@biblioteca.com', 'Masculino'),
-- Estudiantes
('Ramírez Silva', 'Ana María', 'DNI', '45678901', '987654324', 'Calle Las Flores 321, Lima', 'estudiante1@biblioteca.com', 'Femenino'),
('Gonzales Pérez', 'Luis Miguel', 'DNI', '56789012', '987654325', 'Jr. San Martín 654, Lima', 'estudiante2@biblioteca.com', 'Masculino'),
('Chávez Rojas', 'Sofía Isabel', 'DNI', '67890123', '987654326', 'Av. Universitaria 987, Lima', 'estudiante3@biblioteca.com', 'Femenino'),
('Vega Castro', 'Diego Andrés', 'DNI', '78901234', '987654327', 'Calle Los Pinos 147, Lima', 'estudiante4@biblioteca.com', 'Masculino');

-- ============================================
-- USUARIOS (contraseña: admin123, docente123, estudiante123)
-- ============================================
INSERT INTO usuarios (nomuser, passuser, nivelacceso, idpersona) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('docente1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'docente', 2),
('docente2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'docente', 3),
('estudiante1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'estudiante', 4),
('estudiante2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'estudiante', 5),
('estudiante3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'estudiante', 6),
('estudiante4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'estudiante', 7);

-- ============================================
-- GRUPOS
-- ============================================
INSERT INTO grupos (aniolectivo, grado, seccion, nivel) VALUES
-- Primaria
(2025, '1', 'A', 'Primaria'),
(2025, '2', 'A', 'Primaria'),
(2025, '3', 'A', 'Primaria'),
(2025, '4', 'A', 'Primaria'),
(2025, '5', 'A', 'Primaria'),
(2025, '6', 'A', 'Primaria'),
-- Secundaria
(2025, '1', 'A', 'Secundaria'),
(2025, '2', 'A', 'Secundaria'),
(2025, '3', 'A', 'Secundaria'),
(2025, '4', 'A', 'Secundaria'),
(2025, '5', 'A', 'Secundaria');

-- ============================================
-- RECURSOS - LIBROS FÍSICOS
-- ============================================

-- Cien Años de Soledad
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Cien Años de Soledad', 1967, 471, '9788497592208', '1era edición', 'disponible', 5, 'Secundaria', 1, 4, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (1, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa dura');

-- La Casa Verde
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('La Casa Verde', 1966, 392, '9788420482729', '1era edición', 'disponible', 3, 'Secundaria', 1, 5, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (2, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa blanda');

-- Don Quijote de la Mancha
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Don Quijote de la Mancha', 1605, 863, '9788424116941', '2da edición', 'disponible', 10, 'Secundaria', 1, 1, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (7, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa dura');

-- Biología General
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Biología General', 2020, 524, '9788448612559', '12va edición', 'disponible', 8, 'Secundaria', 5, 8, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (16, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa dura');

-- Cosmos
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Cosmos', 1980, 366, '9788408093046', '1era edición', 'disponible', 4, 'Secundaria', 8, 6, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (17, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa dura');

-- Álgebra de Baldor
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Álgebra de Baldor', 1941, 586, '9789708100106', 'Edición renovada', 'disponible', 15, 'Secundaria', 12, 9, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (15, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa dura');

-- Geometría y Trigonometría
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Geometría y Trigonometría', 2019, 432, '9786073238243', '8va edición', 'disponible', 12, 'Secundaria', 13, 9, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (15, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa blanda');

-- Sapiens
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Sapiens: De animales a dioses', 2014, 496, '9788499926223', '1era edición', 'disponible', 6, 'Secundaria', 9, 6, 1);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (18, @idrecurso);
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES (@idrecurso, NULL, 'Tapa blanda');

-- ============================================
-- RECURSOS - LIBROS DIGITALES
-- ============================================

-- El Principito
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('El Principito', 1943, 96, '9788498381498', 'Edición digital', 'disponible', 0, 'Primaria', 4, 5, 2);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (3, @idrecurso);
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES (@idrecurso, NULL, NULL);

-- Introducción a la Programación con Python
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Introducción a la Programación con Python', 2021, 320, '9781234567890', '3era edición', 'disponible', 0, 'Secundaria', 20, 10, 2);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (15, @idrecurso);
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES (@idrecurso, NULL, NULL);

-- Poemas Selectos de Pablo Neruda
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Poemas Selectos de Pablo Neruda', 1974, 215, '9789562391276', 'Edición digital', 'disponible', 0, 'Secundaria', 2, 5, 2);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (5, @idrecurso);
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES (@idrecurso, NULL, NULL);

-- Inglés Básico
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Inglés Básico - Guía Interactiva', 2022, 180, '9781234567891', '1era edición', 'disponible', 0, 'Primaria', 22, 10, 2);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (9, @idrecurso);
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES (@idrecurso, NULL, NULL);

-- Historia del Arte
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso)
VALUES ('Historia del Arte Moderno', 2020, 275, '9781234567892', '2da edición', 'disponible', 0, 'Secundaria', 16, 7, 2);
SET @idrecurso = LAST_INSERT_ID();
INSERT INTO detautores (idautor, idrecurso) VALUES (14, @idrecurso);
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES (@idrecurso, NULL, NULL);

-- ============================================
-- MENSAJE FINAL
-- ============================================
SELECT 'Seeding completado exitosamente!' AS Resultado;
SELECT '✅ Usuarios creados: admin, docente1-2, estudiante1-4' AS Info;
SELECT '✅ Contraseñas: admin123, docente123, estudiante123' AS Info;
SELECT '✅ Libros físicos: 8' AS Info;
SELECT '✅ Libros digitales: 5' AS Info;
