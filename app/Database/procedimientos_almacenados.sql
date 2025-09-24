DELIMITER $$

CREATE PROCEDURE crear_recurso_con_ejemplares(
    IN p_titulo VARCHAR(150),
    IN p_anio SMALLINT,
    IN p_numpaginas SMALLINT,
    IN p_isbn CHAR(13),
    IN p_numedicion VARCHAR(50),
    IN p_stock SMALLINT,
    IN p_nivel ENUM('Inicial','Primaria','Secundaria'),
    IN p_idsubcategoria INT,
    IN p_ideditorial INT,
    IN p_idtiporecurso INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE nuevo_id INT;
    DECLARE codigo_base VARCHAR(10);

    -- Insertamos en recursos
    INSERT INTO recursos (
        titulo, anio, numpaginas, isbn, numedicion, estado, stock, nivel, 
        idsubcategoria, ideditorial, idtiporecurso
    )
    VALUES (
        p_titulo, p_anio, p_numpaginas, p_isbn, p_numedicion, 'disponible', p_stock, p_nivel,
        p_idsubcategoria, p_ideditorial, p_idtiporecurso
    );

    -- Guardamos el id generado
    SET nuevo_id = LAST_INSERT_ID();

    -- Creamos un código base a partir de las 2 primeras letras del título
    SET codigo_base = UPPER(LEFT(p_titulo, 4));

    -- Insertamos los ejemplares
    WHILE i <= p_stock DO
        INSERT INTO ejemplares (codigo, idrecurso, estado)
        VALUES (CONCAT(codigo_base, '-', LPAD(i, 3, '0')), nuevo_id, 'disponible');
        SET i = i + 1;
    END WHILE;
END $$

DELIMITER ;

-- Ejemplo de uso
CALL crear_recurso_con_ejemplares(
    'Java avanzado', 
    2021, 
    340, 
    '3781234567890', 
    '2da edición', 
    2, 
    'Secundaria', 
    3,  -- idsubcategoria (ej. Programación)
    3,  -- ideditorial (ej. O’Reilly Media)
    1   -- idtiporecurso (ej. Libro Físico)
);