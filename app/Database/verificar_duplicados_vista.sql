-- Script para verificar duplicados en la vista (causados por JOIN con autores)
-- Ejecutar para identificar el problema

-- 1. Ver recursos con múltiples autores (esto causa duplicados en la vista)
SELECT 
    r.idrecurso,
    r.titulo,
    COUNT(da.idautor) as cantidad_autores,
    GROUP_CONCAT(CONCAT(a.apeautor, ' ', a.nomautor) SEPARATOR ', ') as autores
FROM recursos r
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
LEFT JOIN autores a ON da.idautor = a.idautor
GROUP BY r.idrecurso, r.titulo
HAVING COUNT(da.idautor) > 1
ORDER BY cantidad_autores DESC;

-- 2. Ver todos los recursos con sus autores (para ver el problema del JOIN)
SELECT 
    r.idrecurso,
    r.titulo,
    CONCAT(a.apeautor, ' ', a.nomautor) as autor,
    sc.subcategoria,
    c.categoria,
    e.editorial,
    tr.tiporecurso
FROM recursos r
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
LEFT JOIN autores a ON da.idautor = a.idautor
LEFT JOIN subcategorias sc ON r.idsubcategoria = sc.idsubcategoria
LEFT JOIN categorias c ON sc.idcategoria = c.idcategoria
LEFT JOIN editoriales e ON r.ideditorial = e.ideditorial
LEFT JOIN tiporecursos tr ON r.idtiporecurso = tr.idtiporecurso
ORDER BY r.idrecurso, a.nomautor;

-- 3. Contar cuántos registros se mostrarían en la vista (con JOIN)
SELECT 
    COUNT(*) as total_registros_vista,
    COUNT(DISTINCT r.idrecurso) as recursos_unicos
FROM recursos r
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
LEFT JOIN autores a ON da.idautor = a.idautor;

-- 4. Ver recursos sin autores
SELECT 
    r.idrecurso,
    r.titulo,
    'Sin autor' as autor
FROM recursos r
LEFT JOIN detautores da ON r.idrecurso = da.idrecurso
WHERE da.idautor IS NULL
ORDER BY r.idrecurso;
