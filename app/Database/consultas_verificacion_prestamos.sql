-- ============================================
-- CONSULTAS PARA VERIFICAR PRÉSTAMOS ACTIVOS
-- ============================================

-- 1. Ver todos los préstamos en la tabla (sin filtros)
SELECT * FROM prestamos;

-- 2. Ver préstamos activos (sin devolución)
SELECT 
    p.idprestamo,
    p.fechaprestamo,
    p.fechadevolucion,
    p.fechahoraretorno,
    p.idmatricula,
    p.idusuario,
    p.idrecurso
FROM prestamos p
WHERE p.fechahoraretorno IS NULL;

-- 3A. Ver la consulta SIMPLIFICADA (sin renovaciones primero)
SELECT 
    p.idprestamo,
    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
    per.numerodoc as documento,
    r.titulo as recurso,
    CASE 
        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
    END as codigo_ejemplar,
    DATE(p.fechaprestamo) as fecha_prestamo,
    CASE 
        WHEN p.fechadevolucion IS NOT NULL THEN DATE(p.fechadevolucion)
        ELSE DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)
    END as fecha_vencimiento,
    CASE 
        WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
        ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
    END as dias_restantes,
    CASE 
        WHEN p.fechahoraretorno IS NULL AND 
             CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
             END >= 0 THEN 'Activo'
        WHEN p.fechahoraretorno IS NULL AND 
             CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
             END < 0 THEN 'Vencido'
        ELSE 'Devuelto'
    END as estado,
    0 as renovaciones  -- Por ahora en 0 hasta crear la tabla
FROM prestamos p
JOIN matriculas m ON m.idmatricula = p.idmatricula
JOIN personas per ON per.idpersona = m.idpersona
JOIN recursos r ON r.idrecurso = p.idrecurso
LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
WHERE p.fechahoraretorno IS NULL
ORDER BY p.fechaprestamo DESC;

-- 3B. Ver la consulta COMPLETA (con renovaciones - EJECUTAR DESPUÉS DE CREAR LA TABLA)
-- DESCOMENTA ESTA CONSULTA DESPUÉS DE EJECUTAR crear_tabla_renovaciones.sql
/*
SELECT 
    p.idprestamo,
    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
    per.numerodoc as documento,
    r.titulo as recurso,
    CASE 
        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
    END as codigo_ejemplar,
    DATE(p.fechaprestamo) as fecha_prestamo,
    CASE 
        WHEN p.fechadevolucion IS NOT NULL THEN DATE(p.fechadevolucion)
        ELSE DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)
    END as fecha_vencimiento,
    CASE 
        WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
        ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
    END as dias_restantes,
    CASE 
        WHEN p.fechahoraretorno IS NULL AND 
             CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
             END >= 0 THEN 'Activo'
        WHEN p.fechahoraretorno IS NULL AND 
             CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
             END < 0 THEN 'Vencido'
        ELSE 'Devuelto'
    END as estado,
    COALESCE((SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo), 0) as renovaciones
FROM prestamos p
JOIN matriculas m ON m.idmatricula = p.idmatricula
JOIN personas per ON per.idpersona = m.idpersona
JOIN recursos r ON r.idrecurso = p.idrecurso
LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
WHERE p.fechahoraretorno IS NULL
ORDER BY p.fechaprestamo DESC;
*/

-- 4. Verificar si existen las tablas relacionadas
SELECT 'prestamos' as tabla, COUNT(*) as registros FROM prestamos
UNION ALL
SELECT 'matriculas' as tabla, COUNT(*) as registros FROM matriculas
UNION ALL
SELECT 'personas' as tabla, COUNT(*) as registros FROM personas
UNION ALL
SELECT 'recursos' as tabla, COUNT(*) as registros FROM recursos
UNION ALL
SELECT 'usuarios' as tabla, COUNT(*) as registros FROM usuarios;

-- 5. Verificar relaciones específicas de un préstamo (si existe idprestamo 1)
SELECT 
    p.idprestamo,
    p.idmatricula,
    m.idmatricula as 'matricula_existe',
    m.idpersona,
    per.nombres,
    per.apellidos,
    p.idrecurso,
    r.titulo,
    p.fechaprestamo,
    p.fechahoraretorno
FROM prestamos p
LEFT JOIN matriculas m ON m.idmatricula = p.idmatricula
LEFT JOIN personas per ON per.idpersona = m.idpersona
LEFT JOIN recursos r ON r.idrecurso = p.idrecurso
LIMIT 5;

-- 6. Ver préstamos con problemas de relación (JOIN falla)
SELECT 
    p.idprestamo,
    p.idmatricula,
    CASE WHEN m.idmatricula IS NULL THEN 'NO EXISTE' ELSE 'OK' END as estado_matricula,
    p.idusuario,
    CASE WHEN u.idusuario IS NULL THEN 'NO EXISTE' ELSE 'OK' END as estado_usuario,
    p.idrecurso,
    CASE WHEN r.idrecurso IS NULL THEN 'NO EXISTE' ELSE 'OK' END as estado_recurso
FROM prestamos p
LEFT JOIN matriculas m ON m.idmatricula = p.idmatricula
LEFT JOIN usuarios u ON u.idusuario = p.idusuario
LEFT JOIN recursos r ON r.idrecurso = p.idrecurso
WHERE p.fechahoraretorno IS NULL;

-- ============================================
-- SCRIPT PARA INSERTAR UN PRÉSTAMO DE PRUEBA
-- ============================================

-- IMPORTANTE: Ejecuta estas consultas SOLO si no tienes datos de prueba

-- 1. Verificar IDs disponibles
SELECT 
    (SELECT MIN(idmatricula) FROM matriculas WHERE estadomatricula = TRUE) as primera_matricula,
    (SELECT MIN(idusuario) FROM usuarios) as primer_usuario,
    (SELECT MIN(idrecurso) FROM recursos WHERE estado = 'disponible') as primer_recurso;

-- 2. Insertar préstamo de prueba (AJUSTA LOS IDs según lo que obtuviste arriba)
-- DESCOMENTA Y AJUSTA ESTA LÍNEA:
/*
INSERT INTO prestamos (idmatricula, idusuario, idrecurso, fechaprestamo, fechadevolucion, fechahoraretorno)
VALUES (
    1,  -- ID de una matrícula válida
    1,  -- ID de un usuario válido
    1,  -- ID de un recurso válido
    NOW(),  -- Fecha de préstamo actual
    NULL,   -- Sin fecha de devolución específica (se calcula +14 días)
    NULL    -- Sin retorno (préstamo activo)
);
*/

-- 3. Verificar que se insertó correctamente
SELECT 
    p.*,
    CONCAT(per.nombres, ' ', per.apellidos) as usuario_nombre,
    r.titulo as recurso_titulo
FROM prestamos p
JOIN matriculas m ON m.idmatricula = p.idmatricula
JOIN personas per ON per.idpersona = m.idpersona
JOIN recursos r ON r.idrecurso = p.idrecurso
WHERE p.idprestamo = LAST_INSERT_ID();

-- ============================================
-- VERIFICAR ESTADÍSTICAS
-- ============================================

-- Estadísticas de préstamos
SELECT 
    COUNT(*) as total_prestamos,
    SUM(CASE WHEN fechahoraretorno IS NULL THEN 1 ELSE 0 END) as prestamos_activos,
    SUM(CASE WHEN fechahoraretorno IS NOT NULL THEN 1 ELSE 0 END) as prestamos_devueltos
FROM prestamos;

-- Préstamos por estado
SELECT 
    CASE 
        WHEN p.fechahoraretorno IS NULL AND 
             DATEDIFF(COALESCE(DATE(p.fechadevolucion), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)), CURDATE()) >= 0 
        THEN 'Activo'
        WHEN p.fechahoraretorno IS NULL AND 
             DATEDIFF(COALESCE(DATE(p.fechadevolucion), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)), CURDATE()) < 0 
        THEN 'Vencido'
        ELSE 'Devuelto'
    END as estado,
    COUNT(*) as cantidad
FROM prestamos p
GROUP BY estado;
