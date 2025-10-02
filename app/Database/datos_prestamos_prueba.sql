-- Datos adicionales para probar préstamos
-- Ejecutar después de insercciones.sql

-- Préstamos adicionales para el usuario 'estu1' (id=3, matrícula=3)
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion, fechahoravalidacion) VALUES
-- Préstamo activo normal
(3, 1, 1, '2025-09-15 10:00:00', '2025-10-15 10:00:00', '2025-09-15 10:30:00'),
-- Préstamo vencido
(3, 1, 2, '2025-09-01 11:00:00', '2025-09-20 11:00:00', '2025-09-01 11:30:00'),
-- Préstamo por vencer (3 días)
(3, 1, 3, '2025-09-25 12:00:00', '2025-10-05 12:00:00', '2025-09-25 12:30:00');

-- Préstamo ya devuelto (para historial)
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion, fechahoravalidacion, fechahoraretorno) VALUES
(3, 1, 4, '2025-08-01 09:00:00', '2025-08-15 09:00:00', '2025-08-01 09:30:00', '2025-08-14 16:00:00');

-- Préstamos para el usuario 'estu2' (id=4, matrícula=4)
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion, fechahoravalidacion) VALUES
-- Préstamo activo
(4, 1, 1, '2025-09-20 14:00:00', '2025-10-20 14:00:00', '2025-09-20 14:30:00');

-- Actualizar fechas de los préstamos existentes para que sean más realistas
UPDATE prestamos SET 
    fechaprestamo = '2025-09-10 08:00:00',
    fechadevolucion = '2025-10-10 08:00:00',
    fechahoravalidacion = '2025-09-10 08:30:00'
WHERE idprestamo = 1;

UPDATE prestamos SET 
    fechaprestamo = '2025-09-12 09:00:00', 
    fechadevolucion = '2025-10-12 09:00:00',
    fechahoravalidacion = '2025-09-12 09:30:00'
WHERE idprestamo = 2;

UPDATE prestamos SET 
    fechaprestamo = '2025-09-14 10:00:00',
    fechadevolucion = '2025-10-14 10:00:00', 
    fechahoravalidacion = '2025-09-14 10:30:00'
WHERE idprestamo = 3;

UPDATE prestamos SET 
    fechaprestamo = '2025-09-16 11:00:00',
    fechadevolucion = '2025-10-16 11:00:00',
    fechahoravalidacion = '2025-09-16 11:30:00'
WHERE idprestamo = 4;
