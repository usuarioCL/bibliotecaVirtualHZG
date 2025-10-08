-- =====================================================================
-- CONSULTA OPTIMIZADA PARA CARDS DE LIBROS: Datos completos para la vista
-- Compatible con el helper JavaScript mejorado
-- Biblioteca Virtual HZG - Estado actual de los libros
-- =====================================================================

-- CONSULTA PRINCIPAL PARA CARDS DE LIBROS
SELECT 
    r.idrecurso,
    r.titulo,
    r.isbn,
    r.anio,
    r.numpaginas,
    r.numedicion,
    r.estado,
    r.stock,
    r.nivel,
    
    -- Información del tipo de recurso
    tr.tiporecurso,
    
    -- Información de categorías
    c.categoria,
    sc.subcategoria,
    
    -- Editorial
    e.editorial,
    
    -- Información de portadas y archivos
    CASE 
        WHEN tr.tiporecurso LIKE '%físico%' OR tr.tiporecurso LIKE '%Físico%' THEN rf.portada
        WHEN tr.tiporecurso LIKE '%digital%' OR tr.tiporecurso LIKE '%Digital%' THEN rd.portada
        ELSE NULL
    END AS rutaportada,
    
    -- Archivo para recursos digitales
    rd.archivo,
    
    -- Información de ejemplares físicos para estado real
    COALESCE(ef.total_ejemplares, 0) AS total_ejemplares,
    COALESCE(ef.disponibles, 0) AS ejemplares_disponibles,
    COALESCE(ef.prestados, 0) AS ejemplares_prestados,
    COALESCE(ef.dañados, 0) AS ejemplares_dañados,
    COALESCE(ef.perdidos, 0) AS ejemplares_perdidos,
    COALESCE(ef.mantenimiento, 0) AS ejemplares_mantenimiento,
    
    -- Préstamos activos
    COALESCE(pa.prestamos_activos, 0) AS prestamos_activos,
    
    -- Ubicación principal
    u.ubicacion,
    
    -- Autores concatenados (compatible con el helper JS)
    GROUP_CONCAT(
        CONCAT(a.nomautor, ' ', a.apeautor) 
        SEPARATOR ', '
    ) AS autores,
    
    -- Primer autor por separado (para compatibilidad)
    MAX(a.nomautor) AS nomautor,
    MAX(a.apeautor) AS apeautor,
    
    -- Estado calculado más preciso para las cards
    CASE 
        WHEN tr.tiporecurso LIKE '%digital%' OR tr.tiporecurso LIKE '%Digital%' THEN 'disponible'
        WHEN COALESCE(ef.disponibles, 0) > 0 THEN 'disponible'
        WHEN COALESCE(ef.prestados, 0) > 0 AND COALESCE(ef.disponibles, 0) = 0 THEN 'prestado'
        WHEN r.estado = 'perdido' THEN 'perdido'
        WHEN COALESCE(ef.total_ejemplares, 0) = 0 THEN 'sin_ejemplares'
        ELSE r.estado
    END AS estado_calculado,
    
    -- URL de detalle (se puede personalizar según tu routing)
    CONCAT('/recursos/detalle/', r.idrecurso) AS detalle_url,
    
    -- Información adicional útil
    CASE 
        WHEN tr.tiporecurso LIKE '%digital%' OR tr.tiporecurso LIKE '%Digital%' THEN 'Siempre disponible'
        WHEN COALESCE(ef.disponibles, 0) > 0 THEN CONCAT(ef.disponibles, ' de ', ef.total_ejemplares, ' disponibles')
        WHEN COALESCE(ef.prestados, 0) > 0 THEN CONCAT('Todos prestados (', ef.prestados, ')')
        ELSE 'Sin stock'
    END AS info_disponibilidad
    
FROM recursos r
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
LEFT JOIN subcategorias sc ON r.idsubcategoria = sc.idsubcategoria
LEFT JOIN categorias c ON sc.idcategoria = c.idcategoria
LEFT JOIN editoriales e ON r.ideditorial = e.ideditorial
LEFT JOIN recursos_fisicos rf ON r.idrecurso = rf.idrecurso
LEFT JOIN recursos_digitales rd ON r.idrecurso = rd.idrecurso
LEFT JOIN ubicaciones u ON r.idrecurso = u.idrecurso

-- Subquery para contar ejemplares físicos por estado
LEFT JOIN (
    SELECT 
        idrecurso,
        COUNT(*) as total_ejemplares,
        SUM(CASE WHEN estado_ejemplar = 'disponible' AND activo = TRUE THEN 1 ELSE 0 END) as disponibles,
        SUM(CASE WHEN estado_ejemplar = 'prestado' AND activo = TRUE THEN 1 ELSE 0 END) as prestados,
        SUM(CASE WHEN estado_ejemplar = 'dañado' AND activo = TRUE THEN 1 ELSE 0 END) as dañados,
        SUM(CASE WHEN estado_ejemplar = 'perdido' AND activo = TRUE THEN 1 ELSE 0 END) as perdidos,
        SUM(CASE WHEN estado_ejemplar = 'mantenimiento' AND activo = TRUE THEN 1 ELSE 0 END) as mantenimiento
    FROM ejemplares_fisicos 
    WHERE activo = TRUE
    GROUP BY idrecurso
) ef ON r.idrecurso = ef.idrecurso

-- Subquery para contar préstamos activos
LEFT JOIN (
    SELECT 
        idrecurso,
        COUNT(*) as prestamos_activos
    FROM prestamos 
    WHERE fechahoraretorno IS NULL
    GROUP BY idrecurso
) pa ON r.idrecurso = pa.idrecurso

-- Join con autores
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
LEFT JOIN autores a ON da.idautor = a.idautor

GROUP BY 
    r.idrecurso, r.titulo, r.isbn, r.anio, r.numpaginas, r.numedicion,
    r.estado, r.stock, r.nivel, tr.tiporecurso, c.categoria, 
    sc.subcategoria, e.editorial, rf.portada, rd.portada, rd.archivo,
    ef.total_ejemplares, ef.disponibles, ef.prestados, ef.dañados, 
    ef.perdidos, ef.mantenimiento, pa.prestamos_activos, u.ubicacion

ORDER BY r.titulo;

-- =====================================================================
-- CONSULTA ESPECÍFICA: Solo libros disponibles para mostrar en catálogo
-- =====================================================================
SELECT 
    r.idrecurso,
    r.titulo,
    r.isbn,
    r.anio,
    r.numedicion,
    tr.tiporecurso,
    c.categoria,
    sc.subcategoria,
    e.editorial,
    CASE 
        WHEN tr.tiporecurso LIKE '%físico%' THEN rf.portada
        WHEN tr.tiporecurso LIKE '%digital%' THEN rd.portada
        ELSE NULL
    END AS rutaportada,
    rd.archivo,
    u.ubicacion,
    GROUP_CONCAT(CONCAT(a.nomautor, ' ', a.apeautor) SEPARATOR ', ') AS autores,
    CONCAT('/recursos/detalle/', r.idrecurso) AS detalle_url,
    
    -- Stock disponible real
    CASE 
        WHEN tr.tiporecurso LIKE '%digital%' THEN 999 -- Digital siempre disponible
        ELSE COALESCE(ef.disponibles, 0)
    END AS stock_disponible
    
FROM recursos r
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
LEFT JOIN subcategorias sc ON r.idsubcategoria = sc.idsubcategoria
LEFT JOIN categorias c ON sc.idcategoria = c.idcategoria
LEFT JOIN editoriales e ON r.ideditorial = e.ideditorial
LEFT JOIN recursos_fisicos rf ON r.idrecurso = rf.idrecurso
LEFT JOIN recursos_digitales rd ON r.idrecurso = rd.idrecurso
LEFT JOIN ubicaciones u ON r.idrecurso = u.idrecurso
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
LEFT JOIN autores a ON da.idautor = a.idautor

-- Solo ejemplares disponibles
LEFT JOIN (
    SELECT 
        idrecurso,
        SUM(CASE WHEN estado_ejemplar = 'disponible' AND activo = TRUE THEN 1 ELSE 0 END) as disponibles
    FROM ejemplares_fisicos 
    WHERE activo = TRUE
    GROUP BY idrecurso
) ef ON r.idrecurso = ef.idrecurso

WHERE 
    -- Solo mostrar recursos que están disponibles
    (tr.tiporecurso LIKE '%digital%' OR COALESCE(ef.disponibles, 0) > 0)
    AND r.estado != 'perdido'

GROUP BY 
    r.idrecurso, r.titulo, r.isbn, r.anio, r.numedicion,
    tr.tiporecurso, c.categoria, sc.subcategoria, e.editorial,
    rf.portada, rd.portada, rd.archivo, u.ubicacion, ef.disponibles

ORDER BY r.titulo;

-- =====================================================================
-- CONSULTA PARA ADMINISTRACIÓN: Libros que requieren atención
-- =====================================================================
SELECT 
    r.idrecurso,
    r.titulo,
    r.isbn,
    tr.tiporecurso,
    r.estado as estado_general,
    
    -- Información del problema
    CASE 
        WHEN pa.prestamos_activos > 0 THEN CONCAT(pa.prestamos_activos, ' préstamos activos')
        WHEN ef.prestados > ef.disponibles AND ef.disponibles = 0 THEN 'Todos los ejemplares prestados'
        WHEN ef.dañados > 0 THEN CONCAT(ef.dañados, ' ejemplares dañados')
        WHEN ef.perdidos > 0 THEN CONCAT(ef.perdidos, ' ejemplares perdidos')
        WHEN ef.mantenimiento > 0 THEN CONCAT(ef.mantenimiento, ' en mantenimiento')
        WHEN r.estado = 'perdido' THEN 'Recurso marcado como perdido'
        ELSE 'Requiere revisión'
    END AS problema_detectado,
    
    -- Detalles de préstamos activos
    prestamos_info.detalles_prestamos,
    
    -- Días desde el último movimiento
    DATEDIFF(CURRENT_DATE, COALESCE(ultimo_prestamo.fecha_ultimo, r.stock)) AS dias_sin_movimiento,
    
    -- Prioridad de atención
    CASE 
        WHEN r.estado = 'perdido' THEN 'ALTA'
        WHEN pa.prestamos_activos > 0 THEN 'MEDIA'
        WHEN ef.dañados > 0 OR ef.perdidos > 0 THEN 'ALTA'
        ELSE 'BAJA'
    END AS prioridad
    
FROM recursos r
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso

-- Ejemplares por estado
LEFT JOIN (
    SELECT 
        idrecurso,
        COUNT(*) as total,
        SUM(CASE WHEN estado_ejemplar = 'disponible' AND activo = TRUE THEN 1 ELSE 0 END) as disponibles,
        SUM(CASE WHEN estado_ejemplar = 'prestado' AND activo = TRUE THEN 1 ELSE 0 END) as prestados,
        SUM(CASE WHEN estado_ejemplar = 'dañado' AND activo = TRUE THEN 1 ELSE 0 END) as dañados,
        SUM(CASE WHEN estado_ejemplar = 'perdido' AND activo = TRUE THEN 1 ELSE 0 END) as perdidos,
        SUM(CASE WHEN estado_ejemplar = 'mantenimiento' AND activo = TRUE THEN 1 ELSE 0 END) as mantenimiento
    FROM ejemplares_fisicos 
    WHERE activo = TRUE
    GROUP BY idrecurso
) ef ON r.idrecurso = ef.idrecurso

-- Préstamos activos
LEFT JOIN (
    SELECT 
        idrecurso,
        COUNT(*) as prestamos_activos
    FROM prestamos 
    WHERE fechahoraretorno IS NULL
    GROUP BY idrecurso
) pa ON r.idrecurso = pa.idrecurso

-- Información detallada de préstamos
LEFT JOIN (
    SELECT 
        p.idrecurso,
        GROUP_CONCAT(
            CONCAT(
                per.nombres, ' ', per.apellidos, 
                ' - Prestado: ', DATE(p.fechaprestamo),
                CASE 
                    WHEN p.fechadevolucion < CURRENT_DATE THEN ' (VENCIDO)'
                    ELSE CONCAT(' (Vence: ', DATE(p.fechadevolucion), ')')
                END
            ) SEPARATOR '; '
        ) as detalles_prestamos
    FROM prestamos p
    JOIN matriculas m ON p.idmatricula = m.idmatricula
    JOIN personas per ON m.idpersona = per.idpersona
    WHERE p.fechahoraretorno IS NULL
    GROUP BY p.idrecurso
) prestamos_info ON r.idrecurso = prestamos_info.idrecurso

-- Último préstamo
LEFT JOIN (
    SELECT 
        idrecurso,
        MAX(fechaprestamo) as fecha_ultimo
    FROM prestamos
    GROUP BY idrecurso
) ultimo_prestamo ON r.idrecurso = ultimo_prestamo.idrecurso

WHERE 
    -- Solo recursos que requieren atención
    pa.prestamos_activos > 0 
    OR r.estado IN ('prestado', 'perdido')
    OR ef.dañados > 0 
    OR ef.perdidos > 0
    OR ef.mantenimiento > 0
    OR (ef.disponibles = 0 AND ef.prestados > 0)

ORDER BY 
    CASE 
        WHEN r.estado = 'perdido' THEN 1
        WHEN ef.perdidos > 0 THEN 2
        WHEN ef.dañados > 0 THEN 3
        WHEN pa.prestamos_activos > 0 THEN 4
        ELSE 5
    END,
    r.titulo;

-- =====================================================================
-- CONSULTA RESUMEN PARA DASHBOARD
-- =====================================================================
SELECT 
    'ESTADÍSTICAS GENERALES' AS seccion,
    'Total de recursos' AS concepto,
    COUNT(*) AS cantidad
FROM recursos
UNION ALL
SELECT 
    'ESTADÍSTICAS GENERALES',
    'Recursos físicos',
    COUNT(*)
FROM recursos r
JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
WHERE tr.tiporecurso LIKE '%físico%'
UNION ALL
SELECT 
    'ESTADÍSTICAS GENERALES',
    'Recursos digitales',
    COUNT(*)
FROM recursos r
JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
WHERE tr.tiporecurso LIKE '%digital%'
UNION ALL
SELECT 
    'DISPONIBILIDAD',
    'Recursos disponibles',
    COUNT(*)
FROM recursos r
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
LEFT JOIN (
    SELECT idrecurso, SUM(CASE WHEN estado_ejemplar = 'disponible' AND activo = TRUE THEN 1 ELSE 0 END) as disponibles
    FROM ejemplares_fisicos GROUP BY idrecurso
) ef ON r.idrecurso = ef.idrecurso
WHERE (tr.tiporecurso LIKE '%digital%' OR COALESCE(ef.disponibles, 0) > 0) AND r.estado != 'perdido'
UNION ALL
SELECT 
    'DISPONIBILIDAD',
    'Recursos no disponibles',
    COUNT(*)
FROM recursos r
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
LEFT JOIN (
    SELECT idrecurso, SUM(CASE WHEN estado_ejemplar = 'disponible' AND activo = TRUE THEN 1 ELSE 0 END) as disponibles
    FROM ejemplares_fisicos GROUP BY idrecurso
) ef ON r.idrecurso = ef.idrecurso
WHERE NOT (tr.tiporecurso LIKE '%digital%' OR COALESCE(ef.disponibles, 0) > 0) OR r.estado = 'perdido'
UNION ALL
SELECT 
    'PRÉSTAMOS',
    'Préstamos activos',
    COUNT(*)
FROM prestamos
WHERE fechahoraretorno IS NULL
UNION ALL
SELECT 
    'EJEMPLARES FÍSICOS',
    'Total de ejemplares',
    COUNT(*)
FROM ejemplares_fisicos
WHERE activo = TRUE
UNION ALL
SELECT 
    'EJEMPLARES FÍSICOS',
    'Ejemplares disponibles',
    COUNT(*)
FROM ejemplares_fisicos
WHERE estado_ejemplar = 'disponible' AND activo = TRUE
UNION ALL
SELECT 
    'EJEMPLARES FÍSICOS',
    'Ejemplares prestados',
    COUNT(*)
FROM ejemplares_fisicos
WHERE estado_ejemplar = 'prestado' AND activo = TRUE

ORDER BY 
    CASE seccion
        WHEN 'ESTADÍSTICAS GENERALES' THEN 1
        WHEN 'DISPONIBILIDAD' THEN 2
        WHEN 'PRÉSTAMOS' THEN 3
        WHEN 'EJEMPLARES FÍSICOS' THEN 4
        ELSE 5
    END,
    concepto;