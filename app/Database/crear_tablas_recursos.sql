-- ==========================================
-- CREAR TABLAS PARA RECURSOS FÍSICOS Y DIGITALES
-- ==========================================

-- Tabla para recursos físicos
CREATE TABLE IF NOT EXISTS recursos_fisicos (
    idrecurso INT PRIMARY KEY,
    portada VARCHAR(200),
    encuadernacion VARCHAR(50),
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso) ON DELETE CASCADE
);

-- Tabla para recursos digitales  
CREATE TABLE IF NOT EXISTS recursos_digitales (
    idrecurso INT PRIMARY KEY,
    portada VARCHAR(200),
    archivo VARCHAR(200) NOT NULL,
    FOREIGN KEY (idrecurso) REFERENCES recursos(idrecurso) ON DELETE CASCADE
);

-- Verificar que las tablas se crearon correctamente
SHOW TABLES LIKE 'recursos%';
