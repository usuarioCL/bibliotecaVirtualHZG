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
