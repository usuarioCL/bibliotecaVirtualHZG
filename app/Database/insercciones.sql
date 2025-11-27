-- Personas
INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES
('Ramírez', 'Luis', 'DNI', '34567890', '987321654', 'Av. Los Olivos 234', 'luis.ramirez@mail.com', 'Masculino'),
('Fernández', 'Paola', 'DNI', '45678901', '987654123', 'Jr. Puno 567', 'paola.fernandez@mail.com', 'Femenino'),
('Rojas', 'Miguel', 'CE', '98765432', '965111222', 'Av. Perú 876', 'miguel.rojas@mail.com', 'Masculino'),
('Vargas', 'Lucía', 'Pasaporte', 'B2345678', '999123456', 'Calle Independencia 111', 'lucia.vargas@mail.com', 'Femenino'),
('Gómez', 'Andrés', 'DNI', '56789012', '954321876', 'Jr. Amazonas 765', 'andres.gomez@mail.com', 'Masculino'),
('Castillo', 'Roxana', 'DNI', '67890123', '987888999', 'Av. Pacífico 444', 'roxana.castillo@mail.com', 'Femenino'),
('Paredes', 'Jorge', 'CE', '11223344', '964555321', 'Calle Bolívar 222', 'jorge.paredes@mail.com', 'Masculino'),
('Delgado', 'Carla', 'DNI', '78901234', '987456123', 'Av. San Juan 369', 'carla.delgado@mail.com', 'Femenino'),
('Huamán', 'Kevin', 'DNI', '89012345', '989741236', 'Jr. Huánuco 777', 'kevin.huaman@mail.com', 'Masculino'),
('Salazar', 'Maribel', 'DNI', '90123456', '955741222', 'Calle Progreso 555', 'maribel.salazar@mail.com', 'Femenino'),
('Quispe', 'Edgar', 'CE', '55667788', '921456789', 'Av. Cultura 321', 'edgar.quispe@mail.com', 'Masculino'),
('Medina', 'Patricia', 'DNI', '12378945', '988741963', 'Jr. Tacna 258', 'patricia.medina@mail.com', 'Femenino'),
('Cruz', 'Fernando', 'DNI', '23456711', '956478123', 'Av. La Marina 852', 'fernando.cruz@mail.com', 'Masculino'),
('Navarro', 'Daniela', 'Pasaporte', 'C3456789', '999456789', 'Calle Miraflores 963', 'daniela.navarro@mail.com', 'Femenino'),
('Mendoza', 'Iván', 'DNI', '34567111', '954789123', 'Av. Los Portales 142', 'ivan.mendoza@mail.com', 'Masculino'),
('Ortega', 'Lisbeth', 'DNI', '45671234', '987321789', 'Jr. Moquegua 432', 'lisbeth.ortega@mail.com', 'Femenino'),
('Cárdenas', 'Diego', 'CE', '99887766', '964852367', 'Av. Grau 741', 'diego.cardenas@mail.com', 'Masculino'),
('Peña', 'Valeria', 'DNI', '56781234', '956789432', 'Calle Las Flores 918', 'valeria.pena@mail.com', 'Femenino'),
('Aguilar', 'Felipe', 'DNI', '67891234', '951753852', 'Jr. Ayacucho 654', 'felipe.aguilar@mail.com', 'Masculino'),
('Zapata', 'Renata', 'Pasaporte', 'D4567890', '999852741', 'Av. Primavera 147', 'renata.zapata@mail.com', 'Femenino');

-- Usuarios
INSERT INTO usuarios (nomuser, passuser, nivelacceso, idpersona) VALUES
('admin1', 'admin123', 'admin', 1),
('docente2', 'hashpass6', 'docente', 2),
('docente3', 'hashpass7', 'docente', 3),
('estu3', 'hashpass8', 'estudiante', 4),
('estu4', 'hashpass9', 'estudiante', 5),
('docente4', 'hashpass10', 'docente', 6),
('estu5', 'hashpass11', 'estudiante', 7),
('docente5', 'hashpass12', 'docente', 8),
('estu6', 'hashpass13', 'estudiante', 9),
('admin3', 'hashpass14', 'admin', 10),
('docente6', 'hashpass15', 'docente', 11),
('estu7', 'hashpass16', 'estudiante', 12),
('docente7', 'hashpass17', 'docente', 13),
('estu8', 'hashpass18', 'estudiante', 14),
('docente8', 'hashpass19', 'docente', 15),
('estu9', 'hashpass20', 'estudiante', 16),
('docente9', 'hashpass21', 'docente', 17),
('estu10', 'hashpass22', 'estudiante', 18),
('docente10', 'hashpass23', 'docente', 19),
('estu11', 'hashpass24', 'estudiante', 20);


-- Grupos
INSERT INTO grupos (aniolectivo, grado, seccion, nivel) VALUES
(2025, '1', 'B', 'Primaria'),
(2025, '1', 'C', 'Primaria'),
(2025, '2', 'A', 'Primaria'),
(2025, '2', 'C', 'Primaria'),
(2025, '3', 'A', 'Primaria'),
(2025, '3', 'B', 'Primaria'),
(2025, '4', 'B', 'Primaria'),
(2025, '4', 'C', 'Primaria'),
(2025, '5', 'A', 'Primaria'),
(2025, '5', 'B', 'Primaria'),
(2025, '6', 'A', 'Primaria'),
(2025, '6', 'B', 'Primaria'),
(2025, '1', 'A', 'Secundaria'),
(2025, '1', 'B', 'Secundaria'),
(2025, '2', 'A', 'Secundaria'),
(2025, '2', 'B', 'Secundaria'),
(2025, '3', 'A', 'Secundaria'),
(2025, '3', 'B', 'Secundaria'),
(2025, '4', 'B', 'Secundaria'),
(2025, '5', 'A', 'Secundaria');


-- Matriculas
INSERT INTO matriculas (idgrupo, idpersona, fechamatricula, estadomatricula) VALUES
(1, 1, '2025-03-01', TRUE),
(2, 2, '2025-03-01', TRUE),
(3, 3, '2025-03-02', TRUE),
(4, 4, '2025-03-02', TRUE),
(5, 5, '2025-03-03', TRUE),
(6, 6, '2025-03-03', TRUE),
(7, 7, '2025-03-04', TRUE),
(8, 8, '2025-03-04', TRUE),
(9, 9, '2025-03-05', TRUE),
(10, 10, '2025-03-05', TRUE),
(11, 11, '2025-03-06', TRUE),
(12, 12, '2025-03-06', TRUE),
(13, 13, '2025-03-07', TRUE),
(14, 14, '2025-03-07', TRUE),
(15, 15, '2025-03-08', TRUE),
(16, 16, '2025-03-08', TRUE),
(17, 17, '2025-03-09', TRUE),
(18, 18, '2025-03-09', TRUE),
(19, 19, '2025-03-10', TRUE),
(20, 20, '2025-03-10', TRUE);


-- TipoRecursos
INSERT INTO tiporecursos (tiporecurso) VALUES
('Libro Físico'),
('Libro Digital');

-- Categorias
INSERT INTO categorias (categoria) VALUES
('Literatura'),
('Matemáticas'),
('Informática'),
('Historia'),
('Comunicación'),
('Ciencias Naturales'),
('Física'),
('Química'),
('Biología'),
('Geografía'),
('Educación Cívica'),
('Filosofía'),
('Religión'),
('Arte'),
('Música');

-- Subcategorias
INSERT INTO subcategorias (subcategoria, idcategoria) VALUES
('Novela', 1),
('Cuento', 1),
('Álgebra', 2),
('Geometría', 2),
('Programación', 3),
('Ofimática', 3),
('Historia del Perú', 4),
('Historia Universal', 4),
('Comprensión Lectora', 5),
('Redacción', 5),
('Ciencias del Medio Ambiente', 6),
('Cuerpo Humano', 6),
('Mecánica', 7),
('Electricidad', 7),
('Química General', 8),
('Química Orgánica', 8),
('Anatomía', 9),
('Ecología', 9),
('Geografía del Perú', 10),
('Geografía Mundial', 10),
('Derechos Humanos', 11),
('Valores Cívicos', 11),
('Ética', 12),
('Pensamiento Filosófico', 12),
('Biblia', 13),
('Formación Espiritual', 13),
('Dibujo', 14),
('Pintura', 14),
('Teoría Musical', 15),
('Instrumentos Musicales', 15);


-- Editoriales
INSERT INTO editoriales (editorial) VALUES
('Alfaguara'),
('Santillana'),
('O’Reilly Media'),
('UNMSM Press'),
('Pearson'),
('McGraw-Hill Education'),
('Planeta'),
('Editorial Norma'),
('Anaya'),
('SM Ediciones');

-- Recursos 
INSERT INTO recursos 
(titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso) VALUES
('Don Quijote de la Mancha', 1605, 863, '9788432223451', 'Edición Escolar', 'disponible', 4, 'Secundaria', 1, 7, 1),
('El Principito', 1943, 98, '9788432223452', 'Edición Ilustrada', 'disponible', 6, 'Primaria', 2, 2, 1),
('Harry Potter y la piedra filosofal', 1997, 223, '9788432223453', 'Edición Juvenil', 'disponible', 5, 'Secundaria', 1, 7, 1),
('Mitos y Leyendas del Perú', 2008, 120, '9788432223454', 'Edición Cultural', 'disponible', 3, 'Primaria', 2, 4, 1),
('Álgebra Básica', 2016, 220, '9789706512341', '2da Edición', 'disponible', 5, 'Secundaria', 3, 5, 1),
('Geometría Moderna', 2017, 240, '9789706512342', '1ra Edición', 'disponible', 4, 'Secundaria', 4, 5, 1),
('Introducción a la Programación', 2019, 300, '9781491956781', '1ra Edición', 'disponible', 6, 'Secundaria', 5, 3, 1),
('Ofimática para Estudiantes', 2018, 180, '9788432223461', 'Edición Escolar', 'disponible', 3, 'Primaria', 6, 2, 1),
('Historia del Perú Escolar', 2015, 290, '9789972456781', '2da Edición', 'disponible', 5, 'Secundaria', 7, 4, 1),
('Historia Universal Resumida', 2014, 310, '9789972456782', '1ra Edición', 'disponible', 4, 'Secundaria', 8, 8, 1),
('Comprensión Lectora 1', 2016, 140, '9788491223451', 'Edición Escolar', 'disponible', 6, 'Primaria', 9, 9, 1),
('Redacción y Ortografía', 2017, 200, '9788491223452', '2da Edición', 'disponible', 5, 'Secundaria', 10, 9, 1),
('Ciencias Naturales Básicas', 2015, 210, '9788436012341', 'Edición Escolar', 'disponible', 4, 'Primaria', 11, 6, 1),
('El Cuerpo Humano', 2014, 195, '9788436012342', 'Edición Ilustrada', 'disponible', 3, 'Primaria', 12, 6, 1),
('Principios de Mecánica', 2019, 260, '9789706512351', '2da Edición', 'disponible', 4, 'Secundaria', 13, 5, 1),
('Electricidad y Magnetismo', 2018, 240, '9789706512352', '1ra Edición', 'disponible', 3, 'Secundaria', 14, 5, 1),
('Química General', 2017, 280, '9788437078901', 'Edición Académica', 'disponible', 4, 'Secundaria', 15, 6, 1),
('Introducción a la Química Orgánica', 2019, 250, '9788437078902', '1ra Edición', 'disponible', 3, 'Secundaria', 16, 6, 1),
('Anatomía Humana Escolar', 2016, 270, '9788436012351', 'Edición Escolar', 'disponible', 4, 'Secundaria', 17, 6, 1),
('Ecología y Medio Ambiente', 2018, 230, '9788436012352', 'Edición Educativa', 'disponible', 5, 'Secundaria', 18, 6, 1),
('Geografía del Perú', 2015, 211, '9789972456791', 'Edición Nacional', 'disponible', 4, 'Primaria', 19, 4, 1),
('Geografía Mundial', 2016, 240, '9789972456792', '1ra Edición', 'disponible', 4, 'Secundaria', 20, 4, 1),
('Derechos Humanos', 2019, 160, '9788491223461', 'Edición Escolar', 'disponible', 5, 'Secundaria', 21, 9, 1),
('Formación Ciudadana', 2018, 180, '9788491223462', 'Edición Educativa', 'disponible', 4, 'Primaria', 22, 9, 1),
('Ética para Jóvenes', 2017, 150, '9788432223471', '1ra Edición', 'disponible', 3, 'Secundaria', 23, 7, 1),
('Introducción a la Filosofía', 2019, 210, '9788432223472', 'Edición Escolar', 'disponible', 4, 'Secundaria', 24, 7, 1),
('La Biblia Juvenil', 2014, 350, '9788428523451', 'Edición Ilustrada', 'disponible', 3, 'Primaria', 25, 10, 1),
('Valores y Fe', 2016, 180, '9788428523452', 'Edición Educativa', 'disponible', 4, 'Primaria', 26, 10, 1),
('Dibujo para Escolares', 2018, 140, '9788491223471', 'Edición Práctica', 'disponible', 4, 'Primaria', 27, 9, 1),
('Pintura Básica', 2019, 160, '9788491223472', 'Edición Ilustrada', 'disponible', 3, 'Primaria', 28, 9, 1),
('Teoría Musical Básica', 2017, 190, '9788432223481', '1ra Edición', 'disponible', 4, 'Primaria', 29, 10, 1),
('Instrumentos Musicales', 2018, 170, '9788432223482', 'Edición Ilustrada', 'disponible', 3, 'Primaria', 30, 10, 1),
('Lectura Recreativa', 2020, 120, '9788432223483', 'Edición Escolar', 'disponible', 5, 'Primaria', 2, 2, 1),
('Programación en Python para Estudiantes', 2021, 320, '9781492056811', 'Edición Digital', 'disponible', 1, 'Secundaria', 5, 3, 2),
('Matemática Interactiva', 2020, 210, '9789706512361', 'Versión Digital', 'disponible', 1, 'Primaria', 4, 5, 2),
('Cuentos Digitales para Niños', 2019, 95, '9788432223491', 'Edición Digital', 'disponible', 1, 'Primaria', 2, 2, 2),
('Historia del Perú Multimedia', 2022, 180, '9789972456801', 'Edición Digital', 'disponible', 1, 'Secundaria', 7, 4, 2),
('Enciclopedia Digital Escolar', 2023, 500, '9788437078911', 'Edición Digital', 'disponible', 1, 'Primaria', 11, 6, 2),
('Laboratorio Virtual de Ciencias', 2021, 260, '9788436012361', 'Versión Digital', 'disponible', 1, 'Secundaria', 12, 6, 2),
('Educación Cívica en Línea', 2020, 170, '9788491223481', 'Edición Digital', 'disponible', 1, 'Secundaria', 21, 9, 2),
('Teoría Musical Interactiva', 2019, 150, '9788432223492', 'Versión Digital', 'disponible', 1, 'Primaria', 29, 10, 2),
('Introducción a Java', 2021, 340, '9781492056821', 'Edición Digital', 'disponible', 1, 'Secundaria', 5, 3, 2),
('Curso de Geometría Virtual', 2020, 240, '9789706512371', 'Versión Digital', 'disponible', 1, 'Secundaria', 4, 5, 2),
('Relatos Cortos Interactivos', 2019, 100, '9788432223501', 'Edición Digital', 'disponible', 1, 'Primaria', 2, 2, 2),
('Historia Universal Multimedia', 2022, 210, '9789972456811', 'Edición Digital', 'disponible', 1, 'Secundaria', 8, 8, 2),
('Enciclopedia Virtual de Biología', 2023, 420, '9788437078921', 'Edición Digital', 'disponible', 1, 'Secundaria', 17, 6, 2),
('Química Interactiva', 2020, 260, '9788436012371', 'Versión Digital', 'disponible', 1, 'Secundaria', 15, 6, 2),
('Curso Digital de Fotografía', 2021, 180, '9788491223491', 'Edición Digital', 'disponible', 1, 'Primaria', 27, 9, 2),
('Curso Virtual de Dibujo', 2019, 160, '9788432223511', 'Versión Digital', 'disponible', 1, 'Primaria', 27, 9, 2),
('Ciencias Naturales Interactivo', 2022, 230, '9788436012381', 'Edición Digital', 'disponible', 1, 'Primaria', 11, 6, 2),
('Manual Digital de Ética', 2021, 150, '9788432223521', 'Edición Digital', 'disponible', 1, 'Secundaria', 23, 7, 2),
('Valores Humanos Virtual', 2020, 120, '9788428523461', 'Versión Digital', 'disponible', 1, 'Primaria', 26, 10, 2);

-- Recursos fisicos
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES
(1,  'uploads/portadas/fisico/img1.jpg',  'Tapa dura'),
(2,  'uploads/portadas/fisico/img2.jpg',  'Tapa blanda'),
(3,  'uploads/portadas/fisico/img3.jpg',  'Tapa dura'),
(4,  'uploads/portadas/fisico/img4.jpg',  'Espiral'),
(5,  'uploads/portadas/fisico/img5.jpg',  'Tapa blanda'),
(6,  'uploads/portadas/fisico/img6.jpg',  'Tapa dura'),
(7,  'uploads/portadas/fisico/img7.jpg',  'Espiral'),
(8,  'uploads/portadas/fisico/img8.jpg',  'Tapa blanda'),
(9,  'uploads/portadas/fisico/img9.jpg',  'Espiral'),
(10, 'uploads/portadas/fisico/img10.jpg', 'Tapa dura'),
(11, 'uploads/portadas/fisico/img11.jpg', 'Tapa blanda'),
(12, 'uploads/portadas/fisico/img12.jpg', 'Anillado'),
(13, 'uploads/portadas/fisico/img13.jpg', 'Tapa dura'),
(14, 'uploads/portadas/fisico/img14.jpg', 'Espiral'),
(15, 'uploads/portadas/fisico/img15.jpg', 'Tapa blanda'),
(16, 'uploads/portadas/fisico/img16.jpg', 'Tapa dura'),
(17, 'uploads/portadas/fisico/img17.jpg', 'Espiral'),
(18, 'uploads/portadas/fisico/img18.jpg', 'Tapa blanda'),
(19, 'uploads/portadas/fisico/img19.jpg', 'Anillado'),
(20, 'uploads/portadas/fisico/img20.jpg', 'Tapa dura'),
(21, 'uploads/portadas/fisico/img21.jpg', 'Espiral'),
(22, 'uploads/portadas/fisico/img22.jpg', 'Tapa blanda'),
(23, 'uploads/portadas/fisico/img23.jpg', 'Anillado'),
(24, 'uploads/portadas/fisico/img24.jpg', 'Tapa dura'),
(25, 'uploads/portadas/fisico/img25.jpg', 'Espiral'),
(26, 'uploads/portadas/fisico/img26.jpg', 'Tapa blanda'),
(27, 'uploads/portadas/fisico/img27.jpg', 'Anillado'),
(28, 'uploads/portadas/fisico/img28.jpg', 'Tapa dura'),
(29, 'uploads/portadas/fisico/img29.jpg', 'Espiral'),
(30, 'uploads/portadas/fisico/img30.jpg', 'Tapa blanda'),
(31, 'uploads/portadas/fisico/img31.jpg', 'Tapa dura');

-- Recursos digitales
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES
(32, 'uploads/portadas/digital/img1.jpg',  'uploads/digitales/archivos/default.pdf'),
(33, 'uploads/portadas/digital/img2.jpg',  'uploads/digitales/archivos/default.pdf'),
(34, 'uploads/portadas/digital/img3.jpg',  'uploads/digitales/archivos/default.pdf'),
(35, 'uploads/portadas/digital/img4.jpg',  'uploads/digitales/archivos/default.pdf'),
(36, 'uploads/portadas/digital/img5.jpg',  'uploads/digitales/archivos/default.pdf'),
(37, 'uploads/portadas/digital/img6.jpg',  'uploads/digitales/archivos/default.pdf'),
(38, 'uploads/portadas/digital/img7.jpg',  'uploads/digitales/archivos/default.pdf'),
(39, 'uploads/portadas/digital/img8.jpg',  'uploads/digitales/archivos/default.pdf'),
(40, 'uploads/portadas/digital/img9.jpg',  'uploads/digitales/archivos/default.pdf'),
(41, 'uploads/portadas/digital/img10.jpg', 'uploads/digitales/archivos/default.pdf'),
(42, 'uploads/portadas/digital/img11.jpg', 'uploads/digitales/archivos/default.pdf'),
(43, 'uploads/portadas/digital/img12.jpg', 'uploads/digitales/archivos/default.pdf'),
(44, 'uploads/portadas/digital/img13.jpg', 'uploads/digitales/archivos/default.pdf'),
(45, 'uploads/portadas/digital/img14.jpg', 'uploads/digitales/archivos/default.pdf'),
(46, 'uploads/portadas/digital/img15.jpg', 'uploads/digitales/archivos/default.pdf'),
(47, 'uploads/portadas/digital/img16.jpg', 'uploads/digitales/archivos/default.pdf'),
(48, 'uploads/portadas/digital/img17.jpg', 'uploads/digitales/archivos/default.pdf'),
(49, 'uploads/portadas/digital/img18.jpg', 'uploads/digitales/archivos/default.pdf'),
(50, 'uploads/portadas/digital/img19.jpg', 'uploads/digitales/archivos/default.pdf'),
(51, 'uploads/portadas/digital/img20.jpg', 'uploads/digitales/archivos/default.pdf'),
(52, 'uploads/portadas/digital/img21.jpg', 'uploads/digitales/archivos/default.pdf');

-- Autores
INSERT INTO autores (apeautor, nomautor, nacionalidad) VALUES
('Cervantes', 'Miguel de', 'Española'),
('Saint-Exupéry', 'Antoine de', 'Francesa'),
('Rowling', 'J. K.', 'Británica'),
('Vargas Llosa', 'Mario', 'Peruana'),
('Arguedas', 'José María', 'Peruana'),
('Bryson', 'Bill', 'Estadounidense'),
('Asimov', 'Isaac', 'Rusa-estadounidense'),
('Orwell', 'George', 'Británica'),
('Tolstói', 'León', 'Rusa'),
('Paz', 'Octavio', 'Mexicana'),
('Allende', 'Isabel', 'Chilena'),
('Hemingway', 'Ernest', 'Estadounidense'),
('Borges', 'Jorge Luis', 'Argentina'),
('Galeano', 'Eduardo', 'Uruguaya'),
('Neruda', 'Pablo', 'Chilena');

-- Detalle Autores 
INSERT INTO detautores (idautor, idrecurso) VALUES
(5, 1), 
(6, 2),   
(7, 3),  
(4, 4),   
(11, 5),
(11, 6),
(2, 7),  
(2, 8),
(4, 9),
(10, 10), 
(9, 11), 
(8, 12),  
(6, 13),
(6, 14),
(12, 15), 
(12, 16),
(11, 17),
(11, 18),
(5, 19),  
(15, 20), 
(4, 21),
(10, 22),
(13, 23), 
(13, 24),
(9, 25),
(9, 26),
(5, 27), 
(8, 28),
(11, 29), 
(11, 30),
(11, 31),
(14, 32),
(12, 33),
(2, 34), 
(2, 35), 
(6, 36),
(4, 37),
(11, 38),
(11, 39),
(13, 40),
(10, 41),
(2, 42),
(1, 43),
(8, 44),
(4, 45),
(12, 46),
(9, 47),
(11, 48),
(14, 49),
(15, 50);


-- Prestamos 
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion) VALUES
(1, 1, 1, '2025-04-01 10:00:00', '2025-04-10 10:00:00'),
(2, 2, 2, '2025-04-02 11:00:00', '2025-04-11 11:00:00');

-- Solicitudes 
INSERT INTO solicitud (validado, idprestamo) VALUES
(TRUE, 1),
(FALSE, 2);


-- Tipo sanciones
INSERT INTO tiposancion (tiposancion) VALUES
('Retraso en devolución'),
('Pérdida de material'),
('Daño al material'),
('Incumplimiento de normas'),
('Comportamiento inadecuado');

-- Sanciones
INSERT INTO sanciones (idtiposancion, idpersona, detallesancion, fecha_sancion, fecha_inicio, fecha_vencimiento, estado_sancion, duracion_dias, usuario_registra, observaciones) VALUES
(1, 1, 'Retraso de 3 días en devolución de libro', '2025-01-15', '2025-01-15', '2025-02-15', 'activa', 31, 1, 'Sanción aplicada por retraso en devolución'),
(2, 2, 'Libro perdido - No devuelto', '2025-01-20', '2025-01-20', NULL, 'activa', NULL, 1, 'Material no devuelto en fecha límite'),
(3, 3, 'Páginas dañadas del libro', '2025-01-25', '2025-01-25', '2025-02-25', 'cumplida', 31, 1, 'Sanción cumplida - material reparado'),
(4, 4, 'Incumplió reglamento de biblioteca', '2025-02-01', '2025-02-01', '2025-03-01', 'activa', 28, 1, 'Violación de normas de la biblioteca');

-- Ubicaciones 
INSERT INTO ubicaciones (ubicacion, idrecurso) VALUES
('Estante A1', 1),
('Estante B2', 2);

-- Comentarios 
INSERT INTO comentarios (comentario, idusuario, idrecurso) VALUES
('Excelente libro', 1, 1),
('Muy útil para clases', 2, 2);

-- Reacciones 
INSERT INTO reacciones (tiporeaccion, idusuario, idrecurso) VALUES
('like', 1, 1),
('estrella', 2, 2);

-- Compartidos
INSERT INTO compartidos (idusuario, idrecurso) VALUES
(1, 1),
(2, 2);

-- Favoritos
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(1, 1),
(2, 2);

-- Datos de ejemplo para demostración
INSERT INTO historial_usuarios (accion, usuario_actor, usuario_afectado, tipo_usuario, detalles) VALUES
('Usuario creado', 'admin', 'juan.perez', 'estudiante', 'Nuevo estudiante registrado en el sistema'),
('Usuario creado', 'admin', 'maria.garcia', 'docente', 'Nuevo docente registrado en el sistema'),
('Usuario creado', 'carlos.lopez', 'carlos.lopez', 'docente', 'Información personal actualizada');