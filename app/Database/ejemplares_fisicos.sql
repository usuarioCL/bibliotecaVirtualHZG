-- ==========================================
-- TABLA: Ejemplares Físicos
-- ==========================================
-- Esta tabla maneja cada ejemplar individual de un recurso físico
-- Ejemplo: Si "Programación para principiantes" tiene stock=3,
-- tendrá 3 registros: prog-001, prog-002, prog-003

CREATE TABLE ejemplares_fisicos (
    idejemplar INT AUTO_INCREMENT PRIMARY KEY,
    idrecurso INT NOT NULL,
    codigo_ejemplar VARCHAR(20) NOT NULL UNIQUE, -- ej: "prog-001", "prog-002"
    estado_ejemplar ENUM('disponible','prestado','dañado','perdido','mantenimiento') DEFAULT 'disponible',
    ubicacion VARCHAR(100), -- ej: "Estante A-1", "Sección Programación"
    observaciones TEXT, -- notas sobre el estado físico del ejemplar
    fecha_ingreso DATE DEFAULT (CURRENT_DATE),
    fecha_ultima_revision DATE,
    activo BOOLEAN DEFAULT TRUE, -- para soft delete
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso) ON DELETE CASCADE,
    INDEX idx_codigo_ejemplar (codigo_ejemplar),
    INDEX idx_estado_ejemplar (estado_ejemplar),
    INDEX idx_activo (activo)
);

-- ==========================================
-- PROCEDIMIENTO ALMACENADO: Generar Código de Ejemplar
-- ==========================================
DELIMITER //

CREATE PROCEDURE GenerarCodigoEjemplar(
    IN p_idrecurso INT,
    OUT p_codigo_ejemplar VARCHAR(20)
)
BEGIN
    DECLARE v_titulo VARCHAR(150);
    DECLARE v_prefijo VARCHAR(4);
    DECLARE v_siguiente_numero INT;
    DECLARE v_codigo VARCHAR(20);
    
    -- Obtener el título del recurso
    SELECT titulo INTO v_titulo 
    FROM recursos 
    WHERE idrecurso = p_idrecurso;
    
    -- Generar prefijo con las primeras 4 letras del título (sin espacios, en minúsculas)
    SET v_prefijo = LOWER(REPLACE(SUBSTRING(v_titulo, 1, 4), ' ', ''));
    
    -- Si el título tiene menos de 4 caracteres, usar el título completo
    IF LENGTH(v_prefijo) < 4 THEN
        SET v_prefijo = LOWER(REPLACE(v_titulo, ' ', ''));
    END IF;
    
    -- Obtener el siguiente número para este prefijo
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_ejemplar, LENGTH(v_prefijo) + 2) AS UNSIGNED)), 0) + 1
    INTO v_siguiente_numero
    FROM ejemplares_fisicos 
    WHERE codigo_ejemplar LIKE CONCAT(v_prefijo, '-%')
    AND idrecurso = p_idrecurso;
    
    -- Generar el código final
    SET v_codigo = CONCAT(v_prefijo, '-', LPAD(v_siguiente_numero, 3, '0'));
    
    SET p_codigo_ejemplar = v_codigo;
END //

DELIMITER ;

-- ==========================================
-- PROCEDIMIENTO ALMACENADO: Crear Ejemplares para un Recurso
-- ==========================================
DELIMITER //

CREATE PROCEDURE CrearEjemplaresParaRecurso(
    IN p_idrecurso INT,
    IN p_cantidad INT
)
BEGIN
    DECLARE v_contador INT DEFAULT 1;
    DECLARE v_codigo_ejemplar VARCHAR(20);
    DECLARE v_exit_handler INT DEFAULT 0;
    
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET v_exit_handler = 1;
    
    -- Validar que el recurso existe
    IF NOT EXISTS (SELECT 1 FROM recursos WHERE idrecurso = p_idrecurso) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El recurso especificado no existe';
    END IF;
    
    -- Crear los ejemplares
    WHILE v_contador <= p_cantidad AND v_exit_handler = 0 DO
        -- Generar código único para el ejemplar
        CALL GenerarCodigoEjemplar(p_idrecurso, v_codigo_ejemplar);
        
        -- Insertar el ejemplar
        INSERT INTO ejemplares_fisicos (idrecurso, codigo_ejemplar, estado_ejemplar)
        VALUES (p_idrecurso, v_codigo_ejemplar, 'disponible');
        
        SET v_contador = v_contador + 1;
    END WHILE;
    
    -- Si hubo error, hacer rollback
    IF v_exit_handler = 1 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al crear ejemplares';
    END IF;
END //

DELIMITER ;

-- ==========================================
-- TRIGGER: Actualizar Stock Automáticamente
-- ==========================================
DELIMITER //

CREATE TRIGGER tr_actualizar_stock_after_insert
AFTER INSERT ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = NEW.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = NEW.idrecurso;
END //

CREATE TRIGGER tr_actualizar_stock_after_update
AFTER UPDATE ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = NEW.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = NEW.idrecurso;
END //

CREATE TRIGGER tr_actualizar_stock_after_delete
AFTER DELETE ON ejemplares_fisicos
FOR EACH ROW
BEGIN
    UPDATE recursos 
    SET stock = (
        SELECT COUNT(*) 
        FROM ejemplares_fisicos 
        WHERE idrecurso = OLD.idrecurso 
        AND activo = TRUE
    )
    WHERE idrecurso = OLD.idrecurso;
END //

DELIMITER ;

-- ==========================================
-- VISTA: Ejemplares con Información del Recurso
-- ==========================================
CREATE VIEW vista_ejemplares_completos AS
SELECT 
    e.idejemplar,
    e.idrecurso,
    e.codigo_ejemplar,
    e.estado_ejemplar,
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
