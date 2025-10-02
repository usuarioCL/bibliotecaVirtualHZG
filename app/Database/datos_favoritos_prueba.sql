-- Datos de prueba para favoritos
-- Ejecutar después de insercciones.sql

-- Favoritos para el usuario 'estu1' (id=3)
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(3, 1), -- Cien Años de Soledad
(3, 2), -- Matemáticas Básicas
(3, 4); -- Historia del Perú

-- Favoritos para el usuario 'estu2' (id=4)
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(4, 1), -- Cien Años de Soledad
(4, 3); -- Learning Python

-- Favoritos para el usuario 'admin1' (id=1)
INSERT INTO favoritos (idusuario, idrecurso) VALUES
(1, 2), -- Matemáticas Básicas
(1, 3), -- Learning Python
(1, 4); -- Historia del Perú
