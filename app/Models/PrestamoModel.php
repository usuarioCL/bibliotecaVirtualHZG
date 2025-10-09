<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestamoModel extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'idprestamo';
    protected $allowedFields = [
        'idmatricula', 'idusuario', 'idrecurso', 'fechaprestamo', 
        'fechadevolucion', 'fechahoravalidacion', 'fechahoraretorno'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Eliminar todos los préstamos de un recurso
     */
    public function deleteByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->delete();
    }

    /**
     * Obtener préstamos de un recurso específico
     */
    public function getPrestamosByRecurso($idrecurso)
    {
        return $this->where('idrecurso', $idrecurso)->findAll();
    }

    /**
     * Obtener préstamos activos de un usuario específico (por matrícula)
     * SOLO retorna préstamos del usuario cuya matrícula se pasa como parámetro
     */
    public function getPrestamosActivosByUsuario($idmatricula)
    {
        // Consulta que filtra estrictamente por matrícula del usuario
        $db = \Config\Database::connect();
        
        $sql = "SELECT p.*, r.titulo, r.anio, 
                       CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nomautor,
                       COALESCE(rf.portada, rd.portada) as portada
                FROM prestamos p
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                LEFT JOIN autores a ON a.idautor = da.idautor
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                WHERE p.idmatricula = ? 
                AND p.fechahoraretorno IS NULL
                ORDER BY p.fechaprestamo DESC";
        
        $query = $db->query($sql, [$idmatricula]);
        return $query->getResultArray();
    }

    /**
     * Obtener historial completo de préstamos de un usuario específico
     * SOLO retorna préstamos del usuario cuya matrícula se pasa como parámetro
     */
    public function getHistorialPrestamosByUsuario($idmatricula, $limit = null)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT p.*, r.titulo, r.anio, r.isbn,
                       CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nomautor,
                       COALESCE(rf.portada, rd.portada) as portada
                FROM prestamos p
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                LEFT JOIN autores a ON a.idautor = da.idautor
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                WHERE p.idmatricula = ?
                ORDER BY p.fechaprestamo DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $query = $db->query($sql, [$idmatricula]);
        return $query->getResultArray();
    }

    /**
     * Obtener matrícula del usuario logueado
     */
    public function getMatriculaByUsuario($idusuario)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('matriculas m')
                     ->select('m.idmatricula')
                     ->join('usuarios u', 'u.idpersona = m.idpersona')
                     ->where('u.idusuario', $idusuario)
                     ->where('m.estadomatricula', true);
        
        $result = $builder->get()->getRow();
        return $result ? $result->idmatricula : null;
    }

    /**
     * Obtener todos los préstamos activos para la vista de administrador
     */
    public function getPrestamosActivos()
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    p.idprestamo,
                    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    DATE(p.fechaprestamo) as fecha_prestamo,
                    DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY) as fecha_vencimiento,
                    DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE()) as dias_restantes,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL AND DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE()) >= 0 THEN 'Activo'
                        WHEN p.fechahoraretorno IS NULL AND DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE()) < 0 THEN 'Vencido'
                        ELSE 'Devuelto'
                    END as estado,
                    0 as renovaciones
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE p.fechahoraretorno IS NULL
                ORDER BY p.fechaprestamo DESC";
        
        $query = $db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Obtener estadísticas de préstamos para el dashboard
     */
    public function getEstadisticasPrestamos()
    {
        $db = \Config\Database::connect();
        
        // Total de préstamos activos
        $totalPrestamos = $this->where('fechahoraretorno IS NULL', null, false)->countAllResults();
        
        // Préstamos vencidos hoy
        $vencidosHoy = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY) = CURDATE()
        ")->getRow()->total;
        
        // Próximos a vencer (en los próximos 3 días)
        $proximosVencer = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE()) BETWEEN 0 AND 3
        ")->getRow()->total;
        
        // Renovaciones pendientes (simulado por ahora)
        $renovacionesPendientes = 0;
        
        return [
            'total_prestamos' => $totalPrestamos,
            'vencidos_hoy' => $vencidosHoy,
            'proximos_vencer' => $proximosVencer,
            'renovaciones_pendientes' => $renovacionesPendientes
        ];
    }

    /**
     * Obtener solicitudes pendientes
     */
    public function getSolicitudesPendientes()
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    s.idsolicitud as id,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    p.fechaprestamo as fecha_solicitud,
                    'Pendiente' as estado,
                    'Normal' as prioridad,
                    CASE WHEN r.stock > 0 THEN true ELSE false END as disponible
                FROM solicitud s
                JOIN prestamos p ON p.idprestamo = s.idprestamo
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE s.validado = false
                ORDER BY p.fechaprestamo DESC";
        
        $query = $db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Obtener devoluciones del día
     */
    public function getDevolucionesHoy()
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    p.idprestamo as id,
                    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    p.fechahoraretorno as fecha_devolucion,
                    DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY) as fecha_vencimiento,
                    DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) as dias_retraso,
                    'Bueno' as estado_ejemplar,
                    CASE 
                        WHEN DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) > 0 
                        THEN DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) * 2500 
                        ELSE 0 
                    END as multa,
                    '' as observaciones
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                WHERE DATE(p.fechahoraretorno) = CURDATE()
                ORDER BY p.fechahoraretorno DESC";
        
        $query = $db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Obtener historial completo de préstamos
     */
    public function getHistorialCompleto()
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    p.idprestamo as id,
                    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    DATE(p.fechaprestamo) as fecha_prestamo,
                    DATE(p.fechahoraretorno) as fecha_devolucion,
                    CASE 
                        WHEN p.fechahoraretorno IS NOT NULL THEN 'Devuelto'
                        WHEN DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE()) < 0 THEN 'Devuelto con retraso'
                        ELSE 'Activo'
                    END as estado_final,
                    DATEDIFF(COALESCE(DATE(p.fechahoraretorno), CURDATE()), DATE(p.fechaprestamo)) as dias_prestamo,
                    0 as renovaciones,
                    CASE 
                        WHEN p.fechahoraretorno IS NOT NULL AND DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) > 0 
                        THEN DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) * 2500 
                        ELSE 0 
                    END as multas
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                ORDER BY p.fechaprestamo DESC
                LIMIT 50";
        
        $query = $db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Obtener estadísticas para el módulo de devoluciones
     */
    public function getEstadisticasDevoluciones()
    {
        $db = \Config\Database::connect();
        
        // Devoluciones de hoy
        $devolucionesHoy = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos 
            WHERE DATE(fechahoraretorno) = CURDATE()
        ")->getRow()->total;
        
        // Devoluciones con retraso (hoy)
        $conRetraso = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p
            WHERE DATE(p.fechahoraretorno) = CURDATE()
            AND DATEDIFF(DATE(p.fechahoraretorno), DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)) > 0
        ")->getRow()->total;
        
        return [
            'devoluciones_hoy' => $devolucionesHoy,
            'con_retraso' => $conRetraso,
            'danos_reportados' => 0, // Por implementar
            'multas_generadas' => $conRetraso
        ];
    }

    /**
     * Obtener estadísticas para el historial
     */
    public function getEstadisticasHistorial()
    {
        $db = \Config\Database::connect();
        
        // Total de registros
        $totalRegistros = $this->countAllResults();
        
        // Préstamos de este mes
        $esteMes = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos 
            WHERE MONTH(fechaprestamo) = MONTH(CURDATE()) 
            AND YEAR(fechaprestamo) = YEAR(CURDATE())
        ")->getRow()->total;
        
        // Promedio mensual (últimos 6 meses)
        $promedioMensual = $db->query("
            SELECT AVG(monthly_count) as promedio
            FROM (
                SELECT COUNT(*) as monthly_count
                FROM prestamos
                WHERE fechaprestamo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(fechaprestamo), MONTH(fechaprestamo)
            ) as monthly_stats
        ")->getRow()->promedio ?? 0;
        
        // Tasa de devolución
        $totalDevueltos = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos 
            WHERE fechahoraretorno IS NOT NULL
        ")->getRow()->total;
        
        $tasaDevolucion = $totalRegistros > 0 ? ($totalDevueltos / $totalRegistros) * 100 : 0;
        
        return [
            'total_registros' => $totalRegistros,
            'este_mes' => $esteMes,
            'promedio_mensual' => round($promedioMensual),
            'tasa_devolucion' => round($tasaDevolucion, 1)
        ];
    }
}