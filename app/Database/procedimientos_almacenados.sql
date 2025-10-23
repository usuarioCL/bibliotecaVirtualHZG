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
    
    SELECT titulo INTO v_titulo 
    FROM recursos 
    WHERE idrecurso = p_idrecurso;
    
    SET v_prefijo = LOWER(REPLACE(SUBSTRING(v_titulo, 1, 4), ' ', ''));
    
    IF LENGTH(v_prefijo) < 4 THEN
        SET v_prefijo = LOWER(REPLACE(v_titulo, ' ', ''));
    END IF;
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_ejemplar, LENGTH(v_prefijo) + 2) AS UNSIGNED)), 0) + 1
    INTO v_siguiente_numero
    FROM ejemplares_fisicos 
    WHERE codigo_ejemplar LIKE CONCAT(v_prefijo, '-%')
    AND idrecurso = p_idrecurso;
    
    SET v_codigo = CONCAT(v_prefijo, '-', LPAD(v_siguiente_numero, 3, '0'));
    
    SET p_codigo_ejemplar = v_codigo;
END //
DELIMITER ;

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
    
    IF NOT EXISTS (SELECT 1 FROM recursos WHERE idrecurso = p_idrecurso) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El recurso especificado no existe';
    END IF;
    
    WHILE v_contador <= p_cantidad AND v_exit_handler = 0 DO

        CALL GenerarCodigoEjemplar(p_idrecurso, v_codigo_ejemplar);
        
        INSERT INTO ejemplares_fisicos (idrecurso, codigo_ejemplar, estado_ejemplar, estado_fisico, fecha_ultima_revision)
        VALUES (p_idrecurso, v_codigo_ejemplar, 'disponible', 'excelente', CURRENT_DATE);
        
        SET v_contador = v_contador + 1;
    END WHILE;
    
    IF v_exit_handler = 1 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al crear ejemplares';
    END IF;
END //

DELIMITER ;