CREATE VIEW vista_ejemplares_completos AS
SELECT 
    e.idejemplar,
    e.idrecurso,
    e.codigo_ejemplar,
    e.estado_ejemplar,
    e.estado_fisico,
    e.ubicacion,
    e.observaciones,
    e.fecha_ingreso,
    e.fecha_ultima_revision,
    e.activo,
    r.titulo,
    r.anio,
    r.isbn,
    r.numedicion,
    ed.editorial,
    c.categoria,
    s.subcategoria,
    t.tiporecurso
FROM ejemplares_fisicos e
INNER JOIN recursos r ON e.idrecurso = r.idrecurso
INNER JOIN editoriales ed ON r.ideditorial = ed.ideditorial
INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria
INNER JOIN categorias c ON s.idcategoria = c.idcategoria
INNER JOIN tiporecursos t ON r.idtiporecurso = t.idtiporecurso
WHERE e.activo = TRUE;
