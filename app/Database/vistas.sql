-- Vista de usuarios y roles
SELECT u.nomuser, u.nivelacceso, p.nombres, p.apellidos, p.email
FROM usuarios u
JOIN personas p ON u.idpersona = p.idpersona;

-- Vista prestamos con infor del alumno
SELECT p.fechaprestamo, per.nombres, per.apellidos, r.titulo
FROM prestamos p
INNER JOIN matriculas m ON p.idmatricula = m.idmatricula
INNER JOIN personas per ON m.idpersona = per.idpersona
INNER JOIN recursos r ON p.idrecurso = r.idrecurso;

-- Vista reaccion de los usuarios
SELECT u.nomuser, r.titulo, re.tiporeaccion
FROM reacciones re
INNER JOIN usuarios u ON re.idusuario = u.idusuario
INNER JOIN recursos r ON re.idrecurso = r.idrecurso;

--Vista alumnos sancionados
SELECT s.detallesancion, ts.tiposancion, per.nombres, per.apellidos
FROM sanciones s
INNER JOIN tiposancion ts ON s.idtiposancion = ts.idtiposancion
INNER JOIN personas per ON s.idpersona = per.idpersona;

-- Vista recursos digitales
SELECT 
    r.idrecurso,
    r.titulo,
    r.anio,
    e.editorial,
    c.categoria,
    s.subcategoria,
    t.tiporecurso,
    d.archivo
FROM recursos r
INNER JOIN recursos_digitales d ON r.idrecurso = d.idrecurso
INNER JOIN editoriales e ON r.ideditorial = e.ideditorial
INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria
INNER JOIN categorias c ON s.idcategoria = c.idcategoria
INNER JOIN tiporecursos t ON r.idtiporecurso = t.idtiporecurso;