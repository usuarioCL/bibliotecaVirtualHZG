-- Personas
INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES
('Pérez', 'Juan', 'DNI', '12345678', '987654321', 'Av. Lima 123', 'juanperez@mail.com', 'Masculino'),
('García', 'María', 'DNI', '23456789', '987111222', 'Jr. Arequipa 456', 'maria@mail.com', 'Femenino'),
('Lopez', 'Carlos', 'CE', '87654321', '965432198', 'Av. Grau 789', 'carlos@mail.com', 'Masculino'),
('Torres', 'Ana', 'Pasaporte', 'A1234567', '999888777', 'Calle Unión 321', 'ana@mail.com', 'Femenino');

-- Usuarios
INSERT INTO usuarios (nomuser, passuser, nivelacceso, idpersona) VALUES
('admin1', 'hashpass1', 'admin', 1),
('docente1', 'hashpass2', 'docente', 2),
('estu1', 'hashpass3', 'estudiante', 3),
('estu2', 'hashpass4', 'estudiante', 4);

-- Grupos
INSERT INTO grupos (aniolectivo, grado, seccion, nivel) VALUES
(2025, '1', 'A', 'Primaria'),
(2025, '2', 'B', 'Primaria'),
(2025, '3', 'C', 'Secundaria'),
(2025, '4', 'A', 'Secundaria');

-- Matriculas
INSERT INTO matriculas (idgrupo, idpersona, fechamatricula, estadomatricula) VALUES
(1, 1, '2025-03-01', TRUE),
(2, 2, '2025-03-02', TRUE),
(3, 3, '2025-03-03', TRUE),
(4, 4, '2025-03-04', TRUE);

-- TipoRecursos
INSERT INTO tiporecursos (tiporecurso) VALUES
('Libro Físico'),
('Libro Digital'),
('Revista'),
('Artículo Académico');

-- Categorias
INSERT INTO categorias (categoria) VALUES
('Literatura'),
('Matemáticas'),
('Informática'),
('Historia');

-- Subcategorias
INSERT INTO subcategorias (subcategoria, idcategoria) VALUES
('Novela', 1),
('Álgebra', 2),
('Programación', 3),
('Historia del Perú', 4);

-- Editoriales
INSERT INTO editoriales (editorial) VALUES
('Alfaguara'),
('Santillana'),
('O’Reilly Media'),
('UNMSM Press');

-- Recursos 
INSERT INTO recursos (titulo, subtitulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso) VALUES
('Cien Años de Soledad', 'Primera edición', 1967, 471, '1234567890123', '1ra', 'disponible', 5, 'Secundaria', 1, 1, 1),
('Matemáticas Básicas', 'Edición escolar', 2015, 320, '2345678901234', '2da', 'disponible', 10, 'Primaria', 2, 2, 1),
('Learning Python', 'Programming Guide', 2013, 1600, '3456789012345', '5ta', 'disponible', 3, 'Secundaria', 3, 3, 2),
('Historia del Perú', 'Desde la independencia', 2005, 520, '4567890123456', '1ra', 'disponible', 4, 'Secundaria', 4, 4, 2);

-- Recursos fisicos
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES
(1, 'portadas/cien_anos.jpg', 'Tapa dura'),
(2, 'portadas/matematicas.jpg', 'Tapa blanda');


-- Recursos digitales
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES
(3, 'portadas/python.jpg', 'archivos/python.pdf'),
(4, NULL, 'archivos/historia_peru.pdf');


-- Autores
INSERT INTO autores (apeautor, nomautor, nacionalidad) VALUES
('García Márquez', 'Gabriel', 'Colombiana'),
('Lutz', 'Mark', 'Estadounidense'),
('Contreras', 'Carlos', 'Peruana'),
('Smith', 'John', 'Británica');

-- Detalle Autores 
INSERT INTO detautores (idautor, idrecurso) VALUES
(1, 1),
(2, 3),
(3, 4),
(4, 2);

-- Prestamos 
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion) VALUES
(1, 1, 1, '2025-04-01 10:00:00', '2025-04-10 10:00:00'),
(2, 2, 2, '2025-04-02 11:00:00', '2025-04-11 11:00:00'),
(3, 3, 3, '2025-04-03 12:00:00', '2025-04-12 12:00:00'),
(4, 4, 4, '2025-04-04 13:00:00', '2025-04-13 13:00:00');

-- Solicitudes 
INSERT INTO solicitud (validado, idprestamo) VALUES
(TRUE, 1),
(FALSE, 2),
(TRUE, 3),
(FALSE, 4);

-- Tipo sanciones
INSERT INTO tiposancion (tiposancion) VALUES
('Retraso'),
('Pérdida'),
('Mal uso'),
('Incumplimiento de normas');

-- Sanciones
INSERT INTO sanciones (idtiposancion, idpersona, detallesancion) VALUES
(1, 1, 'Retraso de 3 días'),
(2, 2, 'Libro perdido'),
(3, 3, 'Páginas dañadas'),
(4, 4, 'Incumplió reglamento');

-- Ubicaciones 
INSERT INTO ubicaciones (ubicacion, idrecurso) VALUES
('Estante A1', 1),
('Estante B2', 2);

-- Comentarios 
INSERT INTO comentarios (comentario, idusuario, idrecurso) VALUES
('Excelente libro', 1, 1),
('Muy útil para clases', 2, 2),
('Recomendado para programadores', 3, 3),
('Gran aporte histórico', 4, 4);

-- Reacciones 
INSERT INTO reacciones (tiporeaccion, idusuario, idrecurso) VALUES
('like', 1, 1),
('estrella', 2, 2),
('like', 3, 3),
('dislike', 4, 4);

-- Compartidos
INSERT INTO compartidos (idusuario, idrecurso) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- Favoritos
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);
