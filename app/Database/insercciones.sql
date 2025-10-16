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
('Libro Digital');
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
INSERT INTO recursos (titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, idsubcategoria, ideditorial, idtiporecurso) VALUES
('Cien Años de Soledad', 1967, 471, '1234567890123', 'Primera edición', 'disponible', 5, 'Secundaria', 1, 1, 1),  -- físico
('Liebre y la tortuga', 2013, 1600, '3456789012345', 'Guide', 'disponible', 3, 'Inicial', 3, 3, 2);     -- digital

-- Recursos fisicos
INSERT INTO recursos_fisicos (idrecurso, portada, encuadernacion) VALUES
(1, 'uploads/portadas/fisico/100años.jpg', 'Tapa dura');


-- Recursos digitales
INSERT INTO recursos_digitales (idrecurso, portada, archivo) VALUES
(2, 'uploads/portadas/digital/large.jpg', 'uploads/digitales/archivos/liebretortuga.pdf');


-- Autores
INSERT INTO autores (apeautor, nomautor, nacionalidad) VALUES
('García Márquez', 'Gabriel', 'Colombiana'),
('Lutz', 'Mark', 'Estadounidense'),
('Contreras', 'Carlos', 'Peruana'),
('Smith', 'John', 'Británica');

-- Detalle Autores 
INSERT INTO detautores (idautor, idrecurso) VALUES
(1, 1),
(2, 2);

-- Prestamos 
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion) VALUES
(1, 1, 1, '2025-04-01 10:00:00', '2025-04-10 10:00:00'),
(2, 2, 2, '2025-04-02 11:00:00', '2025-04-11 11:00:00');

-- Solicitudes 
INSERT INTO solicitud (validado, idprestamo) VALUES
(TRUE, 1),
(FALSE, 2);


-- Tipo sanciones
INSERT INTO tiposancion (tiposancion, descripcion) VALUES
('Retraso', 'Retraso en la devolución de materiales'),
('Pérdida', 'Pérdida de material bibliográfico'),
('Mal uso', 'Daño o mal uso del material'),
('Incumplimiento de normas', 'Violación de las normas de la biblioteca'),
('Comportamiento inadecuado', 'Conducta inapropiada en la biblioteca');

-- Sanciones
INSERT INTO sanciones (idtiposancion, idpersona, detallesancion, fecha_sancion, fecha_vencimiento, estado_sancion, usuario_registra, observaciones) VALUES
(1, 1, 'Retraso de 3 días', '2025-01-15', '2025-02-15', 'activa', 1, 'Sanción aplicada por retraso en devolución'),
(2, 2, 'Libro perdido', '2025-01-20', NULL, 'activa', 1, 'Material no devuelto en fecha límite'),
(3, 3, 'Páginas dañadas', '2025-01-25', '2025-02-25', 'cumplida', 1, 'Sanción cumplida - material reparado'),
(4, 4, 'Incumplió reglamento', '2025-02-01', '2025-03-01', 'activa', 1, 'Violación de normas de la biblioteca');

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

-- Historial de acciones de usuarios
CREATE INDEX idx_historial_usuario_actor ON historial_usuarios(usuario_actor);
CREATE INDEX idx_historial_usuario_afectado ON historial_usuarios(usuario_afectado);
CREATE INDEX idx_historial_fecha ON historial_usuarios(fecha_accion);
CREATE INDEX idx_historial_accion ON historial_usuarios(accion);
CREATE INDEX idx_historial_tipo_usuario ON historial_usuarios(tipo_usuario);

-- Datos de ejemplo para demostración
INSERT INTO historial_usuarios (accion, usuario_actor, usuario_afectado, tipo_usuario, detalles) VALUES
('Usuario creado', 'admin', 'juan.perez', 'estudiante', 'Nuevo estudiante registrado en el sistema'),
('Usuario creado', 'admin', 'maria.garcia', 'docente', 'Nuevo docente registrado en el sistema'),
('Usuario creado', 'carlos.lopez', 'carlos.lopez', 'docente', 'Información personal actualizada');


