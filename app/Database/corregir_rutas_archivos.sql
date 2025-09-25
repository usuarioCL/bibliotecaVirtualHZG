-- ==========================================
-- CORREGIR RUTAS DUPLICADAS EN RECURSOS DIGITALES
-- ==========================================

-- Verificar las rutas actuales
SELECT idrecurso, archivo FROM recursos_digitales WHERE archivo LIKE 'uploads/digitales/uploads/digitales/%';

-- Corregir las rutas duplicadas
UPDATE recursos_digitales 
SET archivo = REPLACE(archivo, 'uploads/digitales/uploads/digitales/', 'uploads/digitales/')
WHERE archivo LIKE 'uploads/digitales/uploads/digitales/%';

-- Verificar que se corrigieron
SELECT idrecurso, archivo FROM recursos_digitales WHERE archivo LIKE 'uploads/digitales/%';

-- También corregir las portadas si tienen el mismo problema
UPDATE recursos_digitales 
SET portada = REPLACE(portada, 'uploads/portadas/uploads/portadas/', 'uploads/portadas/')
WHERE portada LIKE 'uploads/portadas/uploads/portadas/%';

-- Verificar portadas
SELECT idrecurso, portada FROM recursos_digitales WHERE portada IS NOT NULL;
