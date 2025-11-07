<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestamoModel extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'idprestamo';
    protected $allowedFields = [
        'idmatricula', 'idusuario', 'idrecurso', 'fechaprestamo', 
        'fechadevolucion', 'fechahoravalidacion', 'fechahoraretorno',
        'observaciones_devolucion', 'cantidad'
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
                       COALESCE(rf.portada, rd.portada) as portada,
                       COALESCE(
                           (SELECT COUNT(*) 
                            FROM renovaciones_prestamo rp 
                            WHERE rp.idprestamo = p.idprestamo), 
                           0
                       ) as renovaciones,
                       CASE 
                           WHEN (SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo) > 0 THEN 1
                           ELSE 0
                       END as fue_renovado,
                       COALESCE(
                           (SELECT COUNT(*) 
                            FROM renovaciones_prestamo rp 
                            WHERE rp.idprestamo = p.idprestamo), 
                           0
                       ) as renovaciones_count,
                       CASE 
                           WHEN p.fechahoraretorno IS NULL THEN 'Activo'
                           WHEN p.fechahoraretorno IS NOT NULL AND p.fechahoravalidacion IS NOT NULL THEN 'Devuelto'
                           WHEN p.fechahoravalidacion IS NULL THEN 'Pendiente'
                           ELSE 'Completado'
                       END as estado_final,
                       CASE 
                           WHEN p.fechahoraretorno IS NULL THEN 0
                           WHEN p.fechahoraretorno <= COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) THEN 0
                           ELSE FLOOR(TIMESTAMPDIFF(HOUR, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)), p.fechahoraretorno) / 24)
                       END as dias_retraso,
                       CASE 
                           WHEN p.fechahoraretorno IS NULL THEN 0
                           WHEN p.fechahoraretorno <= COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) THEN 0
                           ELSE TIMESTAMPDIFF(HOUR, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)), p.fechahoraretorno)
                       END as horas_retraso_total
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
                    p.fechaprestamo as fecha_prestamo,
                    DATE(p.fechaprestamo) as fecha_prestamo_solo,
                    TIME_FORMAT(p.fechaprestamo, '%H:%i') as hora_inicio,
                    p.fechadevolucion as fecha_devolucion,
                    TIME_FORMAT(p.fechadevolucion, '%H:%i') as hora_fin,
                    p.cantidad,
                    CASE 
                        WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion
                        ELSE DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)
                    END as fecha_vencimiento,
                    CASE 
                        WHEN p.fechadevolucion IS NOT NULL THEN TIMESTAMPDIFF(HOUR, NOW(), p.fechadevolucion) / 24.0
                        ELSE TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) / 24.0
                    END as dias_restantes,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL AND 
                             CASE 
                                WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion > NOW()
                                ELSE DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY) > NOW()
                             END THEN 'Activo'
                        WHEN p.fechahoraretorno IS NULL AND 
                             CASE 
                                WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion <= NOW()
                                ELSE DATE_ADD(p.fechadevolucion, INTERVAL 14 DAY) <= NOW()
                             END THEN 'Vencido'
                        ELSE 'Devuelto'
                    END as estado,
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM renovaciones_prestamo rp 
                         WHERE rp.idprestamo = p.idprestamo), 
                        0
                    ) as renovaciones
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
        
        // Préstamos vencidos hoy (que ya pasaron su hora de vencimiento)
        $vencidosHoy = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion < NOW() AND DATE(p.fechadevolucion) = CURDATE()
                ELSE DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY) < NOW() AND DATE(DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) = CURDATE()
            END
        ")->getRow()->total;
        
        // Próximos a vencer (en los próximos 3 días, considerando hora)
        $proximosVencer = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN 
                    FLOOR(TIMESTAMPDIFF(HOUR, NOW(), p.fechadevolucion) / 24) BETWEEN 0 AND 3
                ELSE 
                    FLOOR(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) / 24) BETWEEN 0 AND 3
            END
        ")->getRow()->total;
        
        // Renovaciones pendientes (préstamos que podrían necesitar renovación)
        $renovacionesPendientes = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN 
                    FLOOR(TIMESTAMPDIFF(HOUR, NOW(), p.fechadevolucion) / 24) <= 2
                    AND FLOOR(TIMESTAMPDIFF(HOUR, NOW(), p.fechadevolucion) / 24) >= -5
                ELSE 
                    FLOOR(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) / 24) <= 2
                    AND FLOOR(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) / 24) >= -5
            END
        ")->getRow()->total;
        
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
                    s.fechaprestamo as fecha_solicitud,
                    s.fechadevolucion as fecha_devolucion,
                    s.fecha_solicitud as fecha_creacion,
                    'Pendiente' as estado,
                    CASE 
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 7 THEN 'Alta'
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 3 THEN 'Media'
                        ELSE 'Normal'
                    END as prioridad,
                    CASE 
                        WHEN r.stock > 0 AND r.estado = 'disponible' AND 
                             r.stock >= CASE 
                                WHEN s.motivo_rechazo LIKE 'Cantidad solicitada:%' THEN 
                                    CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(s.motivo_rechazo, ': ', -1), ' ', 1) AS UNSIGNED)
                                ELSE 1 
                             END
                        THEN true 
                        ELSE false 
                    END as disponible,
                    CASE 
                        WHEN s.motivo_rechazo LIKE 'Cantidad solicitada:%' THEN 
                            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(s.motivo_rechazo, ': ', -1), ' ', 1) AS UNSIGNED)
                        ELSE 1 
                    END as cantidad_solicitada
                FROM solicitud s
                JOIN matriculas m ON m.idmatricula = s.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = s.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE s.validado = false
                ORDER BY 
                    CASE 
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 7 THEN 1
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 3 THEN 2
                        ELSE 3
                    END,
                    s.fecha_solicitud ASC";
        
        $query = $db->query($sql);
        $result = $query->getResultArray();
        
        // Log para debug de cantidades
        foreach ($result as $solicitud) {
            if (isset($solicitud['cantidad_solicitada'])) {
                log_message('info', "Solicitud #{$solicitud['id']}: cantidad_solicitada = {$solicitud['cantidad_solicitada']}");
            }
        }
        
        return $result;
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
                    p.fechadevolucion as fecha_vencimiento,
                    CASE 
                        WHEN p.fechahoraretorno <= p.fechadevolucion THEN 0
                        ELSE CEIL(TIMESTAMPDIFF(HOUR, p.fechadevolucion, p.fechahoraretorno) / 24.0)
                    END as dias_retraso,
                    CASE 
                        WHEN p.fechahoraretorno <= p.fechadevolucion THEN 0
                        ELSE TIMESTAMPDIFF(HOUR, p.fechadevolucion, p.fechahoraretorno)
                    END as horas_retraso,
                    'Bueno' as estado_ejemplar
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                WHERE DATE(p.fechahoraretorno) = CURDATE()
                ORDER BY p.fechahoraretorno DESC";
        
        $query = $db->query($sql);
        $devoluciones = $query->getResultArray();
        
        // Agregar observaciones desde logs
        foreach ($devoluciones as &$devolucion) {
            $observaciones = $this->obtenerObservacionesDesdeLog($devolucion['id']);
            $devolucion['observaciones'] = $observaciones ?: 'Sin observaciones';
            $devolucion['tiene_observaciones'] = !empty($observaciones);
        }
        
        return $devoluciones;
    }
    
    /**
     * Verificar si un préstamo tiene observaciones en los logs
     */
    public function tieneObservaciones($idprestamo)
    {
        $observaciones = $this->obtenerObservacionesDesdeLog($idprestamo);
        return !empty($observaciones);
    }
    
    /**
     * Obtener observaciones de un préstamo desde los logs
     */
    public function obtenerObservacionesDesdeLog($idprestamo)
    {
        $logPath = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.log';
        
        if (!file_exists($logPath)) {
            return '';
        }
        
        $logContent = file_get_contents($logPath);
        $pattern = "/Devolución préstamo {$idprestamo}\. Observaciones: (.+)/";
        
        if (preg_match($pattern, $logContent, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    /**
     * Obtener historial completo de préstamos (incluye devueltos y rechazados)
     */
    public function getHistorialCompleto()
    {
        $db = \Config\Database::connect();
        
        log_message('info', 'PrestamoModel::getHistorialCompleto - Iniciando consulta');
        
        // Consulta para préstamos devueltos (con información de sanciones/incidencias)
        $sqlDevueltos = "SELECT 
                    p.idprestamo as id,
                    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    p.fechaprestamo as fecha_prestamo,
                    p.fechahoraretorno as fecha_devolucion,
                    p.fechadevolucion as fecha_vencimiento,
                    p.cantidad,
                    p.fechahoravalidacion,
                    NULL as motivo_cancelacion,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL THEN 'Activo'
                        WHEN p.fechahoraretorno <= COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) THEN 'Devuelto'
                        ELSE 'Devuelto con retraso'
                    END as estado_final,
                    DATEDIFF(COALESCE(DATE(p.fechahoraretorno), CURDATE()), DATE(p.fechaprestamo)) as dias_prestamo,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL THEN 0
                        WHEN p.fechahoraretorno <= COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) THEN 0
                        ELSE FLOOR(TIMESTAMPDIFF(HOUR, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)), p.fechahoraretorno) / 24)
                    END as dias_retraso,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL THEN 0
                        WHEN p.fechahoraretorno <= COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) THEN 0
                        ELSE TIMESTAMPDIFF(HOUR, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)), p.fechahoraretorno)
                    END as horas_retraso_total,
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM renovaciones_prestamo rp 
                         WHERE rp.idprestamo = p.idprestamo), 
                        0
                    ) as renovaciones,
                    CASE 
                        WHEN (SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo) > 0 THEN 1
                        ELSE 0
                    END as fue_renovado,
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM renovaciones_prestamo rp 
                         WHERE rp.idprestamo = p.idprestamo), 
                        0
                    ) as renovaciones_count,
                    'Bueno' as estado_ejemplar,
                    p.observaciones_devolucion as observaciones,
                    p.fechahoraretorno as fecha_registro,
                    ts.tiposancion as tipo_incidencia,
                    s.detallesancion as detalle_incidencia,
                    s.observaciones as observaciones_incidencia,
                    s.fecha_sancion,
                    s.estado_sancion,
                    CASE 
                        WHEN s.idsancion IS NOT NULL THEN 1
                        ELSE 0
                    END as tiene_incidencia
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN sanciones s ON s.idprestamo = p.idprestamo 
                    AND s.idtiposancion IN (2, 3)
                LEFT JOIN tiposancion ts ON ts.idtiposancion = s.idtiposancion
                WHERE p.fechahoraretorno IS NOT NULL";
        
        // Consulta para solicitudes rechazadas
        $sqlRechazadas = "SELECT 
                    s.idsolicitud as id,
                    CONCAT('SOL-', YEAR(s.fecha_solicitud), '-', LPAD(s.idsolicitud, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    s.fechaprestamo as fecha_prestamo,
                    NULL as fecha_devolucion,
                    s.fechadevolucion as fecha_vencimiento,
                    CASE 
                        WHEN s.motivo_rechazo LIKE 'Cantidad solicitada:%' THEN 
                            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(s.motivo_rechazo, ': ', -1), ' ', 1) AS UNSIGNED)
                        ELSE 1 
                    END as cantidad,
                    NULL as fechahoravalidacion,
                    NULL as motivo_cancelacion,
                    'Rechazado' as estado_final,
                    0 as dias_prestamo,
                    0 as dias_retraso,
                    0 as horas_retraso_total,
                    0 as renovaciones,
                    0 as fue_renovado,
                    0 as renovaciones_count,
                    'N/A' as estado_ejemplar,
                    s.motivo_rechazo as observaciones,
                    s.fecha_procesado as fecha_registro,
                    NULL as tipo_incidencia,
                    NULL as detalle_incidencia,
                    NULL as observaciones_incidencia,
                    NULL as fecha_sancion,
                    NULL as estado_sancion,
                    0 as tiene_incidencia
                FROM solicitud s
                JOIN matriculas m ON m.idmatricula = s.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = s.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE s.validado = true 
                    AND s.idprestamo IS NULL 
                    AND (s.motivo_rechazo IS NOT NULL AND s.motivo_rechazo NOT LIKE 'PRESTAMO_ELIMINADO_HISTORIAL:%')";
        
        // Consulta para préstamos aprobados que están activos
        $sqlAprobados = "SELECT 
                    p.idprestamo as id,
                    CONCAT('PREST-', YEAR(p.fechaprestamo), '-', LPAD(p.idprestamo, 3, '0')) as codigo_prestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    p.fechaprestamo as fecha_prestamo,
                    NULL as fecha_devolucion,
                    p.fechadevolucion as fecha_vencimiento,
                    p.cantidad,
                    p.fechahoravalidacion,
                    NULL as motivo_cancelacion,
                    CASE 
                        WHEN (SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo) > 0 THEN 'Renovado'
                        ELSE 'Aprobado'
                    END as estado_final,
                    DATEDIFF(CURDATE(), DATE(p.fechaprestamo)) as dias_prestamo,
                    0 as dias_retraso,
                    0 as horas_retraso_total,
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM renovaciones_prestamo rp 
                         WHERE rp.idprestamo = p.idprestamo), 
                        0
                    ) as renovaciones,
                    CASE 
                        WHEN (SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo) > 0 THEN 1
                        ELSE 0
                    END as fue_renovado,
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM renovaciones_prestamo rp 
                         WHERE rp.idprestamo = p.idprestamo), 
                        0
                    ) as renovaciones_count,
                    'Activo' as estado_ejemplar,
                    CONCAT('Préstamo aprobado el ', DATE_FORMAT(p.fechahoravalidacion, '%d/%m/%Y %H:%i')) as observaciones,
                    p.fechahoravalidacion as fecha_registro,
                    NULL as tipo_incidencia,
                    NULL as detalle_incidencia,
                    NULL as observaciones_incidencia,
                    NULL as fecha_sancion,
                    NULL as estado_sancion,
                    0 as tiene_incidencia
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE p.fechahoraretorno IS NULL 
                    AND p.fechahoravalidacion IS NOT NULL";
        
        // Unir las tres consultas
        $sql = "({$sqlDevueltos}) UNION ALL ({$sqlRechazadas}) UNION ALL ({$sqlAprobados}) ORDER BY fecha_registro DESC LIMIT 100";
        
        log_message('info', 'PrestamoModel::getHistorialCompleto - Ejecutando consulta SQL');
        log_message('debug', 'SQL: ' . $sql);
        
        $query = $db->query($sql);
        $historial = $query->getResultArray();
        
        log_message('info', 'PrestamoModel::getHistorialCompleto - Consulta ejecutada, registros: ' . count($historial));
        
        // Procesar observaciones del historial
        foreach ($historial as &$registro) {
            // Obtener las observaciones directamente de la BD
            $observacionesDevolucion = $registro['observaciones'] ?? null;
            
            // Limpiar y normalizar las observaciones
            $observacionesDevolucion = trim($observacionesDevolucion ?? '');
            if ($observacionesDevolucion === '' || $observacionesDevolucion === 'NULL' || $observacionesDevolucion === 'Sin observaciones') {
                $observacionesDevolucion = null;
            }
            
            // Establecer las observaciones finales (SOLO de la BD, sin mezclar con logs)
            if (!empty($observacionesDevolucion)) {
                $registro['observaciones'] = $observacionesDevolucion;
                $registro['tiene_observaciones'] = true;
            } else {
                $registro['observaciones'] = null;
                $registro['tiene_observaciones'] = false;
            }
        }
        
        return $historial;
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
     * Obtener estadísticas para el historial (incluye devueltos y rechazados)
     */
    public function getEstadisticasHistorial()
    {
        $db = \Config\Database::connect();
        
        // Total de préstamos
        $totalPrestamos = $this->countAllResults();
        
        // Total de solicitudes rechazadas
        $totalRechazadas = $db->query("
            SELECT COUNT(*) as total 
            FROM solicitud 
            WHERE validado = true AND idprestamo IS NULL
        ")->getRow()->total;
        
        // Total de registros (préstamos + rechazados)
        $totalRegistros = $totalPrestamos + $totalRechazadas;
        
        // Préstamos de este mes (incluye rechazados)
        $esteMes = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos 
            WHERE MONTH(fechaprestamo) = MONTH(CURDATE()) 
            AND YEAR(fechaprestamo) = YEAR(CURDATE())
        ")->getRow()->total;
        
        $esteMesRechazados = $db->query("
            SELECT COUNT(*) as total 
            FROM solicitud 
            WHERE validado = true AND idprestamo IS NULL
            AND MONTH(fecha_solicitud) = MONTH(CURDATE()) 
            AND YEAR(fecha_solicitud) = YEAR(CURDATE())
        ")->getRow()->total;
        
        $esteMesTotal = $esteMes + $esteMesRechazados;
        
        // Promedio mensual (últimos 6 meses) - solo préstamos
        $promedioMensual = $db->query("
            SELECT AVG(monthly_count) as promedio
            FROM (
                SELECT COUNT(*) as monthly_count
                FROM prestamos
                WHERE fechaprestamo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(fechaprestamo), MONTH(fechaprestamo)
            ) as monthly_stats
        ")->getRow()->promedio ?? 0;
        
        // Tasa de devolución (de los préstamos aprobados)
        $totalDevueltos = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos 
            WHERE fechahoraretorno IS NOT NULL
        ")->getRow()->total;
        
        $tasaDevolucion = $totalPrestamos > 0 ? ($totalDevueltos / $totalPrestamos) * 100 : 0;
        
        return [
            'total_registros' => $totalRegistros,
            'este_mes' => $esteMesTotal,
            'promedio_mensual' => round($promedioMensual),
            'tasa_devolucion' => round($tasaDevolucion, 1),
            'total_rechazados' => $totalRechazadas
        ];
    }

    /**
     * Aprobar una solicitud de préstamo
     */
    public function aprobarSolicitud($idsolicitud)
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Obtener información de la solicitud (ahora sin JOIN con prestamos)
            $solicitud = $db->table('solicitud s')
                ->select('s.*')
                ->where('s.idsolicitud', $idsolicitud)
                ->where('s.validado', false)
                ->get()
                ->getRow();
            
            if (!$solicitud) {
                throw new \Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Extraer cantidad solicitada del motivo_rechazo
            $cantidadSolicitada = 1;
            if ($solicitud->motivo_rechazo && strpos($solicitud->motivo_rechazo, 'Cantidad solicitada:') !== false) {
                // Buscar el patrón "Cantidad solicitada: X ejemplares"
                if (preg_match('/Cantidad solicitada:\s*(\d+)/', $solicitud->motivo_rechazo, $matches)) {
                    $cantidadSolicitada = (int)$matches[1];
                } else {
                    // Fallback: buscar cualquier número en el string
                    if (preg_match('/\d+/', $solicitud->motivo_rechazo, $matches)) {
                        $cantidadSolicitada = (int)$matches[0];
                    }
                }
            }
            
            // Log para debugging
            log_message('info', "Aprobando solicitud #{$idsolicitud} - Cantidad solicitada: {$cantidadSolicitada}");
            
            // Verificar disponibilidad del recurso para la cantidad solicitada
            $recurso = $db->table('recursos')
                ->where('idrecurso', $solicitud->idrecurso)
                ->get()
                ->getRow();
            
            if (!$recurso || $recurso->stock < $cantidadSolicitada || $recurso->estado !== 'disponible') {
                throw new \Exception("No hay suficiente stock disponible. Stock actual: {$recurso->stock}, solicitado: {$cantidadSolicitada}");
            }
            
            // Crear un solo préstamo con la cantidad solicitada
            log_message('info', "Creando préstamo de {$cantidadSolicitada} ejemplares para solicitud #{$idsolicitud}");
            
            $prestamo = [
                'idmatricula' => $solicitud->idmatricula,
                'idusuario' => $solicitud->idusuario,
                'idrecurso' => $solicitud->idrecurso,
                'fechaprestamo' => $solicitud->fechaprestamo,
                'fechadevolucion' => $solicitud->fechadevolucion,
                'fechahoravalidacion' => date('Y-m-d H:i:s'),
                'cantidad' => $cantidadSolicitada
            ];
            
            $db->table('prestamos')->insert($prestamo);
            $idPrestamo = $db->insertID();
            
            log_message('info', "Préstamo #{$idPrestamo} creado para solicitud de {$cantidadSolicitada} ejemplares");
            
            // Actualizar la solicitud como validada y asociar con el préstamo creado
            $db->table('solicitud')
                ->where('idsolicitud', $idsolicitud)
                ->update([
                    'validado' => true,
                    'fecha_procesado' => date('Y-m-d H:i:s'),
                    'idprestamo' => $idPrestamo,
                    'motivo_rechazo' => null  // Limpiar el campo ahora que se procesó
                ]);
            
            // Actualizar stock del recurso (descontar la cantidad solicitada)
            if ($recurso->stock >= $cantidadSolicitada) {
                $stockAnterior = $recurso->stock;
                $nuevoStock = $recurso->stock - $cantidadSolicitada;
                $nuevoEstado = $nuevoStock > 0 ? 'disponible' : 'prestado';
                
                log_message('info', "Actualizando stock del recurso #{$solicitud->idrecurso}: {$stockAnterior} -> {$nuevoStock}, estado: {$nuevoEstado}");
                
                $db->table('recursos')
                    ->where('idrecurso', $solicitud->idrecurso)
                    ->update([
                        'stock' => $nuevoStock,
                        'estado' => $nuevoEstado
                    ]);
                    
                log_message('info', "Stock actualizado correctamente para recurso #{$solicitud->idrecurso}");
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            // Crear notificación para el usuario
            try {
                $notificacionModel = new \App\Models\NotificacionModel();
                $fechaDevolucion = date('d/m/Y', strtotime($solicitud->fechadevolucion));
                
                $tituloRecurso = $recurso->titulo ?? 'el recurso solicitado';
                $mensajeNotificacion = $cantidadSolicitada === 1
                    ? "Tu solicitud de préstamo de '{$tituloRecurso}' ha sido aprobada. Puedes recogerlo en la biblioteca. Fecha de devolución: {$fechaDevolucion}."
                    : "Tu solicitud de préstamo de {$cantidadSolicitada} ejemplares de '{$tituloRecurso}' ha sido aprobada. Puedes recogerlos en la biblioteca. Fecha de devolución: {$fechaDevolucion}.";
                
                $notificacionModel->crearNotificacion([
                    'idusuario' => $solicitud->idusuario,
                    'tipo' => 'aprobacion',
                    'titulo' => '¡Préstamo Aprobado! 📚',
                    'mensaje' => $mensajeNotificacion,
                    'idprestamo' => $idPrestamo,
                    'idsolicitud' => $idsolicitud
                ]);
                
                log_message('info', "Notificación de aprobación creada para usuario #{$solicitud->idusuario}");
            } catch (\Exception $e) {
                // No fallar si hay error en la notificación, solo log
                log_message('error', 'Error al crear notificación de aprobación: ' . $e->getMessage());
            }
            
            $mensaje = $cantidadSolicitada === 1 
                ? 'Solicitud aprobada correctamente y préstamo creado'
                : "Solicitud aprobada correctamente. Préstamo creado con {$cantidadSolicitada} ejemplares";
            
            return [
                'success' => true,
                'message' => $mensaje,
                'prestamo_id' => $idPrestamo,
                'cantidad_solicitada' => $cantidadSolicitada
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al aprobar solicitud ' . $idsolicitud . ': ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Aprobar múltiples solicitudes disponibles
     */
    public function aprobarSolicitudesDisponibles($idsolicitudes = [])
    {
        $db = \Config\Database::connect();
        $resultados = [
            'aprobadas' => 0,
            'rechazadas' => 0,
            'errores' => []
        ];
        
        // Log para debugging
        log_message('info', 'Iniciando aprobarSolicitudesDisponibles con IDs: ' . json_encode($idsolicitudes));
        
        // Si no se proporcionan IDs específicos, obtener todas las disponibles
        if (empty($idsolicitudes)) {
            $solicitudesDisponibles = $db->query("
                SELECT s.idsolicitud
                FROM solicitud s
                JOIN prestamos p ON p.idprestamo = s.idprestamo
                JOIN recursos r ON r.idrecurso = p.idrecurso
                WHERE s.validado = false 
                AND r.stock > 0 
                AND r.estado = 'disponible'
            ")->getResultArray();
            
            $idsolicitudes = array_column($solicitudesDisponibles, 'idsolicitud');
        }
        
        foreach ($idsolicitudes as $idsolicitud) {
            // Validar que el ID sea válido
            if (!is_numeric($idsolicitud) || $idsolicitud <= 0) {
                $resultados['rechazadas']++;
                $resultados['errores'][] = "ID de solicitud inválido: {$idsolicitud}";
                continue;
            }
            
            $resultado = $this->aprobarSolicitud($idsolicitud);
            
            if ($resultado['success']) {
                $resultados['aprobadas']++;
            } else {
                $resultados['rechazadas']++;
                $resultados['errores'][] = "Solicitud {$idsolicitud}: " . $resultado['message'];
            }
        }
        
        // Log final de resultados
        log_message('info', 'Resultados finales de aprobarSolicitudesDisponibles: ' . json_encode($resultados));
        
        return $resultados;
    }

    /**
     * Rechazar una solicitud de préstamo
     */
    public function rechazarSolicitud($idsolicitud, $motivo = '')
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Verificar que la solicitud existe y no está procesada
            $solicitud = $db->table('solicitud s')
                ->select('s.*')
                ->where('s.idsolicitud', $idsolicitud)
                ->where('s.validado', false)
                ->get()
                ->getRow();
            
            if (!$solicitud) {
                throw new \Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Extraer SOLO la cantidad del campo motivo_rechazo (limpiando cualquier otro contenido)
            $cantidadSolicitada = 1;
            if ($solicitud->motivo_rechazo) {
                // Buscar el patrón de cantidad al inicio del string
                if (preg_match('/^Cantidad solicitada:\s*(\d+)/', $solicitud->motivo_rechazo, $matches)) {
                    $cantidadSolicitada = (int)$matches[1];
                }
            }
            
            // Construir el motivo de rechazo NUEVO (reemplazando completamente el anterior)
            $motivoCompleto = $motivo;
            if ($cantidadSolicitada > 1) {
                $motivoCompleto = "Cantidad solicitada: {$cantidadSolicitada} ejemplares. " . $motivo;
            }
            
            log_message('info', "Solicitud {$idsolicitud}: Cantidad extraída = {$cantidadSolicitada}, Motivo original = '{$solicitud->motivo_rechazo}', Motivo nuevo = '{$motivoCompleto}'");
            
            // Marcar la solicitud como rechazada en lugar de eliminarla (para historial)
            $db->table('solicitud')
                ->where('idsolicitud', $idsolicitud)
                ->update([
                    'validado' => true,  // Marcada como procesada
                    'motivo_rechazo' => $motivoCompleto,
                    'fecha_procesado' => date('Y-m-d H:i:s'),
                    'idprestamo' => null  // No se crea préstamo
                ]);
            
            // Registrar en log el rechazo con el motivo
            log_message('info', "Solicitud {$idsolicitud} rechazada. Cantidad: {$cantidadSolicitada}. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            // Crear notificación de rechazo
            try {
                $notificacionModel = new \App\Models\NotificacionModel();
                
                // Obtener información del recurso
                $recurso = $db->table('recursos')
                    ->where('idrecurso', $solicitud->idrecurso)
                    ->get()
                    ->getRow();
                
                $tituloRecurso = $recurso->titulo ?? 'el recurso solicitado';
                $mensajeNotificacion = !empty($motivo)
                    ? "Tu solicitud de préstamo de '{$tituloRecurso}' ha sido rechazada. Motivo: {$motivo}"
                    : "Tu solicitud de préstamo de '{$tituloRecurso}' ha sido rechazada.";
                
                $notificacionModel->crearNotificacion([
                    'idusuario' => $solicitud->idusuario,
                    'tipo' => 'rechazo',
                    'titulo' => 'Solicitud Rechazada',
                    'mensaje' => $mensajeNotificacion,
                    'idprestamo' => null,
                    'idsolicitud' => $idsolicitud
                ]);
                
                log_message('info', "Notificación de rechazo creada para usuario #{$solicitud->idusuario}");
            } catch (\Exception $e) {
                log_message('error', 'Error al crear notificación de rechazo: ' . $e->getMessage());
            }
            
            return [
                'success' => true,
                'message' => 'Solicitud rechazada correctamente'
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al rechazar solicitud: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener detalles completos de una solicitud
     */
    public function getDetalleSolicitud($idsolicitud)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    s.idsolicitud,
                    s.validado,
                    s.idprestamo,
                    s.fechaprestamo as fecha_solicitud,
                    s.fechadevolucion as fecha_devolucion_esperada,
                    s.fechadevolucion as fecha_devolucion,
                    s.fecha_solicitud as fecha_creacion,
                    s.motivo_rechazo,
                    s.fecha_procesado,
                    
                    -- Si existe préstamo asociado, obtener su info
                    p.fechahoravalidacion,
                    
                    -- Información del usuario
                    per.idpersona,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario_completo,
                    per.nombres as usuario_nombres,
                    per.apellidos as usuario_apellidos,
                    per.numerodoc as documento,
                    per.tipodoc as tipo_documento,
                    per.telefono,
                    per.email,
                    per.direccion,
                    
                    -- Información del recurso
                    r.idrecurso,
                    r.titulo as recurso_titulo,
                    r.isbn,
                    r.anio as anio_publicacion,
                    r.numpaginas,
                    r.numedicion,
                    r.estado as estado_recurso,
                    r.stock,
                    r.nivel as nivel_educativo,
                    
                    -- Información de la editorial
                    e.editorial,
                    
                    -- Información de la categoría
                    c.categoria,
                    sc.subcategoria,
                    
                    -- Información del tipo de recurso
                    tr.tiporecurso,
                    
                    -- Información del autor principal
                    CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as autor_principal,
                    a.nacionalidad as autor_nacionalidad,
                    
                    -- Código del ejemplar
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    
                    -- Información de la matrícula
                    g.aniolectivo,
                    g.grado,
                    g.seccion,
                    g.nivel as nivel_estudiante,
                    
                    -- Información adicional de la solicitud
                    CASE 
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 7 THEN 'Alta'
                        WHEN DATEDIFF(NOW(), s.fecha_solicitud) >= 3 THEN 'Media'
                        ELSE 'Normal'
                    END as prioridad,
                    
                    CASE WHEN r.stock > 0 AND r.estado = 'disponible' THEN true ELSE false END as disponible,
                    
                    DATEDIFF(NOW(), s.fecha_solicitud) as dias_desde_solicitud,
                    
                    -- Portada del recurso
                    COALESCE(rf.portada, rd.portada) as portada_recurso,
                    
                    -- Información del recurso físico o digital
                    rf.encuadernacion,
                    rd.archivo as archivo_digital
                    
                FROM solicitud s
                JOIN matriculas m ON m.idmatricula = s.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = s.idrecurso
                LEFT JOIN prestamos p ON p.idprestamo = s.idprestamo
                LEFT JOIN grupos g ON g.idgrupo = m.idgrupo
                LEFT JOIN editoriales e ON e.ideditorial = r.ideditorial
                LEFT JOIN subcategorias sc ON sc.idsubcategoria = r.idsubcategoria
                LEFT JOIN categorias c ON c.idcategoria = sc.idcategoria
                LEFT JOIN tiporecursos tr ON tr.idtiporecurso = r.idtiporecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                LEFT JOIN autores a ON a.idautor = da.idautor
                WHERE s.idsolicitud = ?
                ORDER BY da.iddetautor ASC
                LIMIT 1";
        
        $query = $db->query($sql, [$idsolicitud]);
        $detalle = $query->getRow();
        
        if ($detalle) {
            // Obtener todos los autores del recurso
            $autoresQuery = $db->query("
                SELECT CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nombre_completo,
                       a.nacionalidad
                FROM detautores da
                JOIN autores a ON a.idautor = da.idautor
                WHERE da.idrecurso = ?
            ", [$detalle->idrecurso]);
            
            $detalle->autores = $autoresQuery->getResultArray();
            
            // Verificar si hay otros préstamos activos del mismo recurso
            $otrosPrestamosQuery = $db->query("
                SELECT COUNT(*) as total
                FROM prestamos p2
                WHERE p2.idrecurso = ?
                AND p2.fechahoraretorno IS NULL
                " . ($detalle->idprestamo ? "AND p2.idprestamo != ?" : ""), 
                $detalle->idprestamo ? [$detalle->idrecurso, $detalle->idprestamo] : [$detalle->idrecurso]);
            
            $detalle->otros_prestamos_activos = $otrosPrestamosQuery->getRow()->total;
            
            // Obtener historial previo del usuario con este tipo de recursos
            $historialQuery = $db->query("
                SELECT COUNT(*) as total_prestamos,
                       COUNT(CASE WHEN p.fechahoraretorno IS NOT NULL THEN 1 END) as prestamos_devueltos,
                       COUNT(CASE WHEN p.fechahoraretorno IS NULL AND DATEDIFF(NOW(), DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)) > 0 THEN 1 END) as prestamos_vencidos
                FROM prestamos p
                JOIN matriculas m2 ON m2.idmatricula = p.idmatricula
                WHERE m2.idpersona = ?
            ", [$detalle->idpersona ?? 0]);
            
            $historial = $historialQuery->getRow();
            $detalle->historial_usuario = $historial;
        }
        
        return $detalle;
    }

    /**
     * Procesar devolución de un préstamo
     */
    public function procesarDevolucion($idprestamo, $observaciones = '')
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Verificar que el préstamo existe y está activo
            $prestamo = $this->find($idprestamo);
            if (!$prestamo) {
                throw new \Exception('El préstamo no existe');
            }
            
            if ($prestamo['fechahoraretorno'] !== null) {
                throw new \Exception('Este préstamo ya ha sido devuelto');
            }
            
            // Actualizar el préstamo con la fecha de devolución y observaciones
            $fechaRetorno = date('Y-m-d H:i:s');
            $updateData = [
                'fechahoraretorno' => $fechaRetorno,
                'observaciones_devolucion' => !empty($observaciones) ? $observaciones : null
            ];
            
            $this->update($idprestamo, $updateData);
            
            // Actualizar el stock del recurso (incrementar disponibilidad)
            $cantidadDevuelta = $prestamo['cantidad'] ?? 1;
            $db->table('recursos')
               ->where('idrecurso', $prestamo['idrecurso'])
               ->set('stock', "stock + {$cantidadDevuelta}", false)
               ->update();
            
            log_message('info', "Devolución procesada: Préstamo #{$idprestamo}, devolviendo {$cantidadDevuelta} ejemplares al stock del recurso #{$prestamo['idrecurso']}");
            
            // Si el recurso estaba marcado como 'prestado' y no hay más préstamos activos, 
            // cambiar el estado a 'disponible'
            $prestamosActivos = $this->where('idrecurso', $prestamo['idrecurso'])
                                    ->where('fechahoraretorno IS NULL', null, false)
                                    ->countAllResults();
            
            if ($prestamosActivos == 0) {
                $db->table('recursos')
                   ->where('idrecurso', $prestamo['idrecurso'])
                   ->update(['estado' => 'disponible']);
            }
            
            // Registrar las observaciones en logs si se proporcionaron
            if (!empty($observaciones)) {
                log_message('info', "Devolución préstamo {$idprestamo}. Observaciones: {$observaciones}");
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de devolución');
            }
            
            // Calcular si hubo retraso en la devolución
            $fechaPrestamo = new \DateTime($prestamo['fechaprestamo']);
            $fechaDevolucion = new \DateTime($fechaRetorno);
            $fechaLimiteDevolucion = clone $fechaPrestamo;
            $fechaLimiteDevolucion->add(new \DateInterval('P14D')); // 14 días de préstamo
            
            $diasRetraso = 0;
            $conRetraso = false;
            if ($fechaDevolucion > $fechaLimiteDevolucion) {
                $diasRetraso = $fechaDevolucion->diff($fechaLimiteDevolucion)->days;
                $conRetraso = true;
            }
            
            $mensaje = 'Devolución procesada correctamente';
            if ($conRetraso) {
                $mensaje .= " (Entregado con {$diasRetraso} días de retraso)";
            }
            
            return [
                'success' => true,
                'message' => $mensaje,
                'con_retraso' => $conRetraso,
                'dias_retraso' => $diasRetraso,
                'fecha_devolucion' => $fechaRetorno
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al procesar devolución: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Renovar un préstamo activo
     */
    public function renovarPrestamo($idprestamo, $diasRenovacion = 14, $motivo = '')
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Verificar que el préstamo existe y está activo
            $prestamo = $this->find($idprestamo);
            if (!$prestamo) {
                throw new \Exception('El préstamo no existe');
            }
            
            if ($prestamo['fechahoraretorno'] !== null) {
                throw new \Exception('No se puede renovar un préstamo ya devuelto');
            }
            
            // Crear tabla de renovaciones si no existe
            $this->crearTablaRenovacionesSiNoExiste($db);
            
            // Verificar límite de renovaciones (máximo 3 renovaciones)
            $renovacionesActuales = 0;
            try {
                if ($db->tableExists('renovaciones_prestamo')) {
                    $query = $db->query("
                        SELECT COUNT(*) as total_renovaciones 
                        FROM renovaciones_prestamo 
                        WHERE idprestamo = ?", [$idprestamo]);
                    
                    $result = $query->getRow();
                    $renovacionesActuales = $result ? $result->total_renovaciones : 0;
                }
            } catch (\Exception $e) {
                log_message('warning', 'No se pudo verificar renovaciones anteriores: ' . $e->getMessage());
                $renovacionesActuales = 0; // Asumimos 0 renovaciones si hay error
            }
            
            // Calcular nueva fecha de devolución
            $fechaActual = new \DateTime();
            $nuevaFechaDevolucion = clone $fechaActual;
            $nuevaFechaDevolucion->add(new \DateInterval("P{$diasRenovacion}D"));
            
            // Actualizar la tabla de préstamos con nueva fecha de devolución
            $this->update($idprestamo, [
                'fechadevolucion' => $nuevaFechaDevolucion->format('Y-m-d H:i:s')
            ]);
            
            // Registrar la renovación en tabla auxiliar (si existe)
            try {
                if ($db->tableExists('renovaciones_prestamo')) {
                    $db->table('renovaciones_prestamo')->insert([
                        'idprestamo' => $idprestamo,
                        'fecha_renovacion' => $fechaActual->format('Y-m-d H:i:s'),
                        'dias_extension' => $diasRenovacion,
                        'motivo' => $motivo,
                        'nueva_fecha_devolucion' => $nuevaFechaDevolucion->format('Y-m-d H:i:s'),
                        'usuario_renovacion' => session()->get('idusuario') ?? 1
                    ]);
                }
            } catch (\Exception $e) {
                log_message('warning', 'No se pudo registrar en tabla de renovaciones: ' . $e->getMessage());
                // Continuamos con el proceso aunque no se registre en la tabla auxiliar
            }
            
            // Registrar en logs
            log_message('info', "Préstamo {$idprestamo} renovado por {$diasRenovacion} días. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de renovación');
            }
            
            return [
                'success' => true,
                'message' => "Préstamo renovado exitosamente por {$diasRenovacion} días",
                'nueva_fecha_devolucion' => $nuevaFechaDevolucion->format('d/m/Y'),
                'renovaciones_totales' => $renovacionesActuales + 1,
                'dias_extension' => $diasRenovacion
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al renovar préstamo: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Crear tabla de renovaciones si no existe
     */
    private function crearTablaRenovacionesSiNoExiste($db)
    {
        try {
            if (!$db->tableExists('renovaciones_prestamo')) {
                // Crear tabla usando SQL directo para evitar problemas con el forge
                $sql = "
                CREATE TABLE renovaciones_prestamo (
                    id_renovacion INT AUTO_INCREMENT PRIMARY KEY,
                    idprestamo INT NOT NULL,
                    fecha_renovacion DATETIME NOT NULL,
                    dias_extension INT NOT NULL,
                    motivo TEXT,
                    nueva_fecha_devolucion DATETIME NOT NULL,
                    usuario_renovacion INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_idprestamo (idprestamo)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ";
                
                $db->query($sql);
                log_message('info', 'Tabla renovaciones_prestamo creada exitosamente');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear tabla renovaciones_prestamo: ' . $e->getMessage());
            // No lanzamos excepción para que el proceso continúe
        }
    }

    /**
     * Renovar un préstamo activo con fecha específica
     */
    public function renovarPrestamoConFecha($idprestamo, $nuevaFechaDevolucion, $motivo = '', $nuevaFechaPrestamo = null)
    {
        $db = \Config\Database::connect();
        
        try {
            log_message('info', 'Iniciando renovación de préstamo ' . $idprestamo);
            
            $db->transStart();
            
            // Verificar que el préstamo existe y está activo
            $prestamo = $this->find($idprestamo);
            if (!$prestamo) {
                log_message('error', 'Préstamo no encontrado: ' . $idprestamo);
                throw new \Exception('El préstamo no existe');
            }
            
            log_message('info', 'Préstamo encontrado: ' . json_encode($prestamo));
            
            if ($prestamo['fechahoraretorno'] !== null) {
                log_message('error', 'Intento de renovar préstamo ya devuelto: ' . $idprestamo);
                throw new \Exception('No se puede renovar un préstamo ya devuelto');
            }
            
            // Crear tabla de renovaciones si no existe
            $this->crearTablaRenovacionesSiNoExiste($db);
            
            // Contar renovaciones actuales
            $renovacionesActuales = 0;
            try {
                if ($db->tableExists('renovaciones_prestamo')) {
                    $query = $db->query("
                        SELECT COUNT(*) as total_renovaciones 
                        FROM renovaciones_prestamo 
                        WHERE idprestamo = ?", [$idprestamo]);
                    
                    $result = $query->getRow();
                    $renovacionesActuales = $result ? $result->total_renovaciones : 0;
                }
            } catch (\Exception $e) {
                log_message('warning', 'No se pudo verificar renovaciones anteriores: ' . $e->getMessage());
                $renovacionesActuales = 0;
            }
            
            // Validar y parsear las nuevas fechas
            $fechaDevolucion = new \DateTime($nuevaFechaDevolucion);
            $fechaActual = new \DateTime();
            
            // Si se proporciona nueva fecha de préstamo, también actualizarla
            $datosActualizacion = [
                'fechadevolucion' => $fechaDevolucion->format('Y-m-d H:i:s')
            ];
            
            if ($nuevaFechaPrestamo) {
                $fechaPrestamo = new \DateTime($nuevaFechaPrestamo);
                $datosActualizacion['fechaprestamo'] = $fechaPrestamo->format('Y-m-d H:i:s');
            }
            
            // Calcular días de extensión
            $diasExtension = $fechaActual->diff($fechaDevolucion)->days;
            
            // Log de datos a actualizar
            log_message('info', 'Datos a actualizar: ' . json_encode($datosActualizacion));
            
            // Actualizar la tabla de préstamos con las nuevas fechas
            $updateResult = $this->update($idprestamo, $datosActualizacion);
            
            if ($updateResult === false) {
                log_message('error', 'Fallo al actualizar préstamo ' . $idprestamo);
                throw new \Exception('No se pudo actualizar el préstamo');
            }
            
            log_message('info', 'Préstamo actualizado correctamente');
            
            // Registrar la renovación en tabla auxiliar (si existe)
            try {
                if ($db->tableExists('renovaciones_prestamo')) {
                    // Obtener la fecha de vencimiento anterior del préstamo
                    $fechaVencimientoAnterior = $prestamo['fechadevolucion'] ?? $fechaActual->format('Y-m-d H:i:s');
                    
                    $insertData = [
                        'idprestamo' => $idprestamo,
                        'fecha_renovacion' => $fechaActual->format('Y-m-d H:i:s'),
                        'fecha_vencimiento_anterior' => $fechaVencimientoAnterior,
                        'fecha_vencimiento_nueva' => $fechaDevolucion->format('Y-m-d H:i:s'),
                        'motivo' => $motivo,
                        'usuario_renueva' => session()->get('idusuario') ?? 1
                    ];
                    
                    log_message('info', 'Insertando en renovaciones_prestamo: ' . json_encode($insertData));
                    $db->table('renovaciones_prestamo')->insert($insertData);
                }
            } catch (\Exception $e) {
                log_message('warning', 'No se pudo registrar en tabla de renovaciones: ' . $e->getMessage());
                log_message('warning', 'Error detallado: ' . $e->getTraceAsString());
            }
            
            // Registrar en logs
            log_message('info', "Préstamo {$idprestamo} renovado hasta {$nuevaFechaDevolucion}. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                log_message('error', 'Transacción fallida para préstamo ' . $idprestamo);
                throw new \Exception('Error en la transacción de renovación');
            }
            
            log_message('info', 'Transacción completada exitosamente');
            
            return [
                'success' => true,
                'message' => "Préstamo renovado exitosamente hasta {$fechaDevolucion->format('d/m/Y H:i')}",
                'nueva_fecha_devolucion' => $fechaDevolucion->format('d/m/Y H:i'),
                'renovaciones_totales' => $renovacionesActuales + 1,
                'dias_extension' => $diasExtension
            ];
            
        } catch (\Exception $e) {
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            log_message('error', 'Error al renovar préstamo: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancelar un préstamo activo
     */
    public function cancelarPrestamo($idprestamo, $motivo = '')
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Obtener información del préstamo
            $prestamo = $db->table('prestamos p')
                ->select('p.*, r.stock')
                ->join('recursos r', 'r.idrecurso = p.idrecurso')
                ->where('p.idprestamo', $idprestamo)
                ->where('p.fechahoraretorno IS NULL', null, false) // Solo préstamos activos
                ->get()
                ->getRow();
            
            if (!$prestamo) {
                throw new \Exception('Préstamo no encontrado o ya finalizado');
            }
            
            // Marcar el préstamo como devuelto/cancelado
            $db->table('prestamos')
                ->where('idprestamo', $idprestamo)
                ->update([
                    'fechahoraretorno' => date('Y-m-d H:i:s')
                ]);
            
            // Restaurar stock del recurso
            $cantidadCancelada = $prestamo->cantidad ?? 1;
            $nuevoStock = $prestamo->stock + $cantidadCancelada;
            $db->table('recursos')
                ->where('idrecurso', $prestamo->idrecurso)
                ->update([
                    'stock' => $nuevoStock,
                    'estado' => 'disponible'
                ]);
            
            log_message('info', "Préstamo cancelado: Préstamo #{$idprestamo}, devolviendo {$cantidadCancelada} ejemplares al stock del recurso #{$prestamo->idrecurso}");
            
            // Eliminar solicitudes relacionadas si existen
            $db->table('solicitud')
                ->where('idprestamo', $idprestamo)
                ->delete();
            
            // Registrar el motivo en logs
            log_message('info', "Préstamo {$idprestamo} cancelado. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            return [
                'success' => true,
                'message' => 'Préstamo cancelado correctamente'
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al cancelar préstamo: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener detalles completos de un préstamo activo
     */
    public function obtenerDetallePrestamo($idprestamo)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    p.idprestamo,
                    p.fechaprestamo as fecha_prestamo,
                    p.fechadevolucion as fecha_vencimiento,
                    p.fechahoravalidacion as fecha_aprobacion,
                    p.fechahoraretorno as fecha_devolucion_real,
                    
                    -- Cálculo de días (con horas incluidas)
                    TIMESTAMPDIFF(HOUR, NOW(), p.fechadevolucion) / 24.0 as dias_restantes,
                    DATEDIFF(CURDATE(), DATE(p.fechaprestamo)) as dias_transcurridos,
                    
                    -- Información del usuario
                    per.idpersona,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario_completo,
                    per.nombres as usuario_nombres,
                    per.apellidos as usuario_apellidos,
                    per.numerodoc as documento,
                    per.tipodoc as tipo_documento,
                    per.telefono,
                    per.email,
                    per.direccion,
                    
                    -- Información del usuario (login)
                    u.idusuario,
                    u.nomuser as nombre_usuario,
                    u.nivelacceso as nivel_acceso,
                    
                    -- Información de matrícula y grupo
                    m.idmatricula,
                    m.fechamatricula as fecha_matricula,
                    m.estadomatricula as estado_matricula,
                    g.grado,
                    g.seccion,
                    g.nivel as nivel_grupo,
                    
                    -- Información del recurso
                    r.idrecurso,
                    r.titulo as recurso_titulo,
                    r.isbn,
                    r.anio as anio_publicacion,
                    r.numpaginas,
                    r.numedicion,
                    r.estado as estado_recurso,
                    r.stock,
                    r.nivel as nivel_educativo,
                    
                    -- Información de editorial
                    e.editorial,
                    
                    -- Información de categorías
                    c.categoria,
                    sc.subcategoria,
                    
                    -- Información del tipo de recurso
                    tr.tiporecurso,
                    
                    -- Portada del recurso
                    COALESCE(rf.portada, rd.portada) as portada,
                    
                    -- Tipo de recurso
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN 'Físico'
                        WHEN rd.idrecurso IS NOT NULL THEN 'Digital'
                        ELSE 'Desconocido'
                    END as tipo_recurso,
                    
                    -- Observaciones de devolución
                    p.observaciones_devolucion as observaciones_devolucion,
                    
                    -- Cantidad de ejemplares
                    p.cantidad

                FROM prestamos p
                LEFT JOIN matriculas m ON m.idmatricula = p.idmatricula
                LEFT JOIN personas per ON per.idpersona = m.idpersona
                LEFT JOIN usuarios u ON u.idusuario = p.idusuario
                LEFT JOIN grupos g ON g.idgrupo = m.idgrupo
                LEFT JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN editoriales e ON e.ideditorial = r.ideditorial
                LEFT JOIN subcategorias sc ON sc.idsubcategoria = r.idsubcategoria
                LEFT JOIN categorias c ON c.idcategoria = sc.idcategoria
                LEFT JOIN tiporecursos tr ON tr.idtiporecurso = r.idtiporecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                WHERE p.idprestamo = ? AND p.fechahoraretorno IS NULL
                LIMIT 1";
        
        $query = $db->query($sql, [$idprestamo]);
        $detalle = $query->getRow();
        
        if ($detalle) {
            // Obtener todos los autores del recurso
            $autoresQuery = $db->query("
                SELECT CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as autor_completo,
                       a.nacionalidad
                FROM detautores da
                JOIN autores a ON a.idautor = da.idautor
                WHERE da.idrecurso = ?
            ", [$detalle->idrecurso]);
            
            $detalle->autores = $autoresQuery->getResultArray();
            
            // Verificar si existe la tabla de renovaciones
            $tablesQuery = $db->query("SHOW TABLES LIKE 'renovaciones_prestamo'");
            if ($tablesQuery->getNumRows() > 0) {
                // Obtener historial de renovaciones
                $renovacionesQuery = $db->query("
                    SELECT 
                        fecha_renovacion, 
                        fecha_vencimiento_anterior,
                        fecha_vencimiento_nueva, 
                        motivo,
                        DATEDIFF(fecha_vencimiento_nueva, fecha_vencimiento_anterior) as dias_extension
                    FROM renovaciones_prestamo 
                    WHERE idprestamo = ? 
                    ORDER BY fecha_renovacion DESC
                ", [$idprestamo]);
                
                $detalle->renovaciones = $renovacionesQuery->getResultArray();
            } else {
                $detalle->renovaciones = [];
            }
            
            $detalle->total_renovaciones = count($detalle->renovaciones);
            
            // Calcular estado del préstamo (considerando horas)
            $diasRestantes = floatval($detalle->dias_restantes ?? 0);
            if ($diasRestantes > 3) {
                $detalle->estado_prestamo = 'Activo';
                $detalle->color_estado = 'success';
                $detalle->icono_estado = 'ti-check-circle';
            } elseif ($diasRestantes >= 0) {
                $detalle->estado_prestamo = 'Por Vencer';
                $detalle->color_estado = 'warning';
                $detalle->icono_estado = 'ti-alert-triangle';
            } else {
                $detalle->estado_prestamo = 'Vencido';
                $detalle->color_estado = 'danger';
                $detalle->icono_estado = 'ti-x-circle';
            }
            
            // Formatear fechas para mejor presentación
            $detalle->fecha_prestamo_formatted = $detalle->fecha_prestamo ? 
                date('d/m/Y H:i', strtotime($detalle->fecha_prestamo)) : 'No disponible';
            $detalle->fecha_vencimiento_formatted = $detalle->fecha_vencimiento ? 
                date('d/m/Y', strtotime($detalle->fecha_vencimiento)) : 'No disponible';
            
            // Formatear hora de inicio y fin
            $detalle->hora_inicio = $detalle->fecha_prestamo ? 
                date('H:i', strtotime($detalle->fecha_prestamo)) : 'No disponible';
            $detalle->hora_fin = $detalle->fecha_vencimiento ? 
                date('H:i', strtotime($detalle->fecha_vencimiento)) : 'No especificada';
            $detalle->fecha_prestamo_solo = $detalle->fecha_prestamo ? 
                date('d/m/Y', strtotime($detalle->fecha_prestamo)) : 'No disponible';
            
            if ($detalle->fecha_aprobacion) {
                $detalle->fecha_aprobacion_formatted = date('d/m/Y H:i', strtotime($detalle->fecha_aprobacion));
            } else {
                $detalle->fecha_aprobacion_formatted = null;
            }
            
            // Formatear fechas de renovaciones
            foreach ($detalle->renovaciones as &$renovacion) {
                $renovacion['fecha_renovacion_formatted'] = date('d/m/Y H:i', strtotime($renovacion['fecha_renovacion']));
                $renovacion['fecha_vencimiento_anterior_formatted'] = date('d/m/Y', strtotime($renovacion['fecha_vencimiento_anterior']));
                $renovacion['fecha_vencimiento_nueva_formatted'] = date('d/m/Y', strtotime($renovacion['fecha_vencimiento_nueva']));
            }
        }
        
        return $detalle;
    }

    /**
     * Rechazar múltiples solicitudes de préstamo
     */
    public function rechazarSolicitudesMultiples($idsolicitudes = [], $motivo = '')
    {
        $db = \Config\Database::connect();
        
        if (empty($idsolicitudes)) {
            return [
                'rechazadas' => 0,
                'errores' => ['No se proporcionaron IDs de solicitudes']
            ];
        }
        
        log_message('info', 'Iniciando rechazarSolicitudesMultiples con IDs: ' . json_encode($idsolicitudes));
        log_message('info', 'Motivo: ' . $motivo);
        
        $resultados = [
            'rechazadas' => 0,
            'errores' => []
        ];
        
        foreach ($idsolicitudes as $idsolicitud) {
            if (!is_numeric($idsolicitud)) {
                $resultados['errores'][] = "ID de solicitud inválido: {$idsolicitud}";
                continue;
            }
            
            try {
                $db->transStart();
                
                // Verificar que la solicitud existe y no está procesada
                $solicitud = $db->table('solicitud s')
                    ->select('s.*')
                    ->where('s.idsolicitud', $idsolicitud)
                    ->where('s.validado', false)
                    ->get()
                    ->getRow();
                
                if (!$solicitud) {
                    $resultados['errores'][] = "Solicitud #{$idsolicitud} no encontrada o ya procesada";
                    $db->transRollback();
                    continue;
                }
                
                // Extraer SOLO la cantidad del campo motivo_rechazo (limpiando cualquier otro contenido)
                $cantidadSolicitada = 1;
                if ($solicitud->motivo_rechazo) {
                    // Buscar el patrón de cantidad al inicio del string
                    if (preg_match('/^Cantidad solicitada:\s*(\d+)/', $solicitud->motivo_rechazo, $matches)) {
                        $cantidadSolicitada = (int)$matches[1];
                    }
                }
                
                // Construir el motivo de rechazo NUEVO (reemplazando completamente el anterior)
                $motivoCompleto = $motivo;
                if ($cantidadSolicitada > 1) {
                    $motivoCompleto = "Cantidad solicitada: {$cantidadSolicitada} ejemplares. " . $motivo;
                }
                
                log_message('info', "Solicitud {$idsolicitud}: Cantidad extraída = {$cantidadSolicitada}, Motivo original = '{$solicitud->motivo_rechazo}', Motivo nuevo = '{$motivoCompleto}'");
                
                // Marcar la solicitud como rechazada (igual que el rechazo individual)
                $db->table('solicitud')
                    ->where('idsolicitud', $idsolicitud)
                    ->update([
                        'validado' => true,  // Marcada como procesada
                        'motivo_rechazo' => $motivoCompleto,
                        'fecha_procesado' => date('Y-m-d H:i:s'),
                        'idprestamo' => null  // No se crea préstamo
                    ]);
                
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    $resultados['errores'][] = "Error en la transacción para solicitud #{$idsolicitud}";
                    continue;
                }
                
                $resultados['rechazadas']++;
                $motivoTexto = !empty($motivo) ? $motivo : 'Sin motivo especificado';
                log_message('info', "Solicitud {$idsolicitud} rechazada masivamente. Cantidad: {$cantidadSolicitada}. Motivo: {$motivoTexto}");
                
            } catch (\Exception $e) {
                $db->transRollback();
                $resultados['errores'][] = "Error al rechazar solicitud #{$idsolicitud}: " . $e->getMessage();
                log_message('error', 'Error al rechazar solicitud ' . $idsolicitud . ': ' . $e->getMessage());
            }
        }
        
        log_message('info', 'Resultados finales de rechazarSolicitudesMultiples: ' . json_encode($resultados));
        
        return $resultados;
    }

    /**
     * Procesar devolución con estado del recurso y generar sanción si hay retraso
     */
    public function procesarDevolucionCompleta($idprestamo, $estadoRecurso = 'bueno', $observaciones = '')
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Verificar que el préstamo existe y está activo
            $prestamo = $this->find($idprestamo);
            if (!$prestamo) {
                throw new \Exception('El préstamo no existe');
            }
            
            if ($prestamo['fechahoraretorno'] !== null) {
                throw new \Exception('Este préstamo ya ha sido devuelto');
            }
            
            // Actualizar el préstamo con la fecha de devolución y observaciones
            $fechaRetorno = date('Y-m-d H:i:s');
            $updateData = [
                'fechahoraretorno' => $fechaRetorno,
                'observaciones_devolucion' => !empty($observaciones) ? $observaciones : null
            ];
            $this->update($idprestamo, $updateData);
            
            // Actualizar el stock del recurso
            $cantidadDevuelta = $prestamo['cantidad'] ?? 1;
            $db->table('recursos')
               ->where('idrecurso', $prestamo['idrecurso'])
               ->set('stock', "stock + {$cantidadDevuelta}", false)
               ->update();
            
            log_message('info', "Devolución completa procesada: Préstamo #{$idprestamo}, devolviendo {$cantidadDevuelta} ejemplares al stock del recurso #{$prestamo['idrecurso']}");
            
            // Registrar las observaciones en logs si se proporcionaron
            if (!empty($observaciones)) {
                log_message('info', "Devolución préstamo {$idprestamo}. Observaciones: {$observaciones}");
            }
            
            // Verificar si hay más préstamos activos del mismo recurso
            $prestamosActivos = $this->where('idrecurso', $prestamo['idrecurso'])
                                    ->where('fechahoraretorno IS NULL', null, false)
                                    ->countAllResults();
            
            if ($prestamosActivos == 0) {
                $db->table('recursos')
                   ->where('idrecurso', $prestamo['idrecurso'])
                   ->update(['estado' => 'disponible']);
            }
            
            // Calcular si hubo retraso
            $fechaPrestamo = new \DateTime($prestamo['fechaprestamo']);
            $fechaDevolucion = new \DateTime($fechaRetorno);
            $fechaLimite = new \DateTime($prestamo['fechadevolucion']);
            
            $diasRetraso = 0;
            $multaGenerada = 0;
            $sancionCreada = false;
            
            if ($fechaDevolucion > $fechaLimite) {
                $diasRetraso = $fechaDevolucion->diff($fechaLimite)->days;
                $multaGenerada = $diasRetraso * 2500; // $2500 por día de retraso
                
                // Obtener la persona asociada al préstamo
                $matricula = $db->table('matriculas')
                    ->where('idmatricula', $prestamo['idmatricula'])
                    ->get()->getRow();
                
                if ($matricula) {
                    // Buscar o usar tipo de sanción por defecto para retrasos
                    $tipoSancion = $db->table('tiposancion')
                        ->where('tiposancion', 'Retraso en devolución')
                        ->get()->getRow();
                    
                    if (!$tipoSancion) {
                        // Si no existe, crear uno por defecto
                        $db->table('tiposancion')->insert([
                            'tiposancion' => 'Retraso en devolución',
                            'descripcion' => 'Sanción por retraso en la devolución de recursos'
                        ]);
                        $idtiposancion = $db->insertID();
                    } else {
                        $idtiposancion = $tipoSancion->idtiposancion;
                    }
                    
                    // Crear la sanción
                    $detalleSancion = "Retraso de {$diasRetraso} días. Multa: $" . number_format($multaGenerada);
                    if (!empty($observaciones)) {
                        $detalleSancion .= ". Obs: " . $observaciones;
                    }
                    
                    $db->table('sanciones')->insert([
                        'idtiposancion' => $idtiposancion,
                        'idpersona' => $matricula->idpersona,
                        'detallesancion' => $detalleSancion
                    ]);
                    
                    $sancionCreada = true;
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de devolución');
            }
            
            $mensaje = 'Devolución procesada correctamente';
            if ($sancionCreada) {
                $mensaje .= ". Se generó una sanción por {$diasRetraso} días de retraso (Multa: $" . number_format($multaGenerada) . ")";
            }
            
            return [
                'success' => true,
                'message' => $mensaje,
                'con_retraso' => $diasRetraso > 0,
                'dias_retraso' => $diasRetraso,
                'multa' => $multaGenerada,
                'sancion_creada' => $sancionCreada,
                'fecha_devolucion' => $fechaRetorno
            ];
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al procesar devolución completa: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener detalle completo de una devolución
     */
    public function getDetalleDevolucion($idprestamo)
    {
        $db = \Config\Database::connect();
        
        try {
            // Consulta básica con solo las tablas y campos esenciales
            $sql = "SELECT 
                        p.idprestamo as id,
                        p.idprestamo,
                        p.idrecurso,
                        per.idpersona,
                        CONCAT('PREST-', LPAD(p.idprestamo, 6, '0')) as codigo_prestamo,
                        CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                        per.numerodoc as documento,
                        per.telefono,
                        per.email,
                        r.titulo as recurso,
                        r.isbn,
                        r.anio as anio_publicacion,
                        p.fechaprestamo,
                        p.fechadevolucion as fecha_limite,
                        p.fechahoraretorno as fecha_devolucion_real,
                        p.observaciones_devolucion as observaciones,
                        DATEDIFF(p.fechahoraretorno, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY))) as dias_retraso,
                        TIMESTAMPDIFF(HOUR, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY)), p.fechahoraretorno) as horas_retraso_total,
                        DATEDIFF(p.fechahoraretorno, p.fechaprestamo) as dias_prestamo,
                        CASE 
                            WHEN DATEDIFF(p.fechahoraretorno, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY))) > 0 
                            THEN DATEDIFF(p.fechahoraretorno, COALESCE(p.fechadevolucion, DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY))) * 2500 
                            ELSE 0 
                        END as multa
                    FROM prestamos p
                    JOIN matriculas m ON m.idmatricula = p.idmatricula
                    JOIN personas per ON per.idpersona = m.idpersona
                    JOIN recursos r ON r.idrecurso = p.idrecurso
                    WHERE p.idprestamo = ?";
            
            $query = $db->query($sql, [$idprestamo]);
            $result = $query->getRowArray();
            
            if (!$result) {
                return null;
            }
            
            // Intentar obtener autores de forma segura
            try {
                $sqlAutores = "SELECT GROUP_CONCAT(DISTINCT CONCAT(a.nomautor, ' ', a.apeautor) SEPARATOR ', ') as autor
                              FROM detautores da 
                              JOIN autores a ON a.idautor = da.idautor 
                              WHERE da.idrecurso = ?";
                $queryAutores = $db->query($sqlAutores, [$result['idrecurso']]);
                $autoresInfo = $queryAutores->getRowArray();
                if ($autoresInfo && $autoresInfo['autor']) {
                    $result['autor'] = $autoresInfo['autor'];
                }
            } catch (\Exception $e) {
                log_message('debug', 'No se pudo obtener información de autores: ' . $e->getMessage());
            }
            
            // Intentar obtener sanciones de forma segura
            try {
                // Obtener sanciones recientes de la persona (últimos 6 meses)
                $sqlSanciones = "SELECT 
                                    GROUP_CONCAT(DISTINCT CONCAT(ts.tiposancion, ': ', s.detallesancion) SEPARATOR '; ') as sanciones,
                                    COUNT(*) as total_sanciones
                                FROM sanciones s 
                                LEFT JOIN tiposancion ts ON ts.idtiposancion = s.idtiposancion
                                WHERE s.idpersona = ? 
                                AND (s.fecha_sancion IS NULL OR s.fecha_sancion >= DATE_SUB(NOW(), INTERVAL 6 MONTH))
                                ORDER BY s.idsancion DESC";
                $querySanciones = $db->query($sqlSanciones, [$result['idpersona']]);
                $sancionesInfo = $querySanciones->getRowArray();
                if ($sancionesInfo && $sancionesInfo['sanciones']) {
                    $result['sanciones'] = $sancionesInfo['sanciones'];
                    $result['total_sanciones'] = $sancionesInfo['total_sanciones'];
                }
            } catch (\Exception $e) {
                log_message('debug', 'No se pudo obtener información de sanciones: ' . $e->getMessage());
                // Fallback a consulta simple si falla la compleja
                try {
                    $sqlSancionesSimple = "SELECT GROUP_CONCAT(DISTINCT s.detallesancion SEPARATOR '; ') as sanciones
                                          FROM sanciones s WHERE s.idpersona = ? LIMIT 5";
                    $querySancionesSimple = $db->query($sqlSancionesSimple, [$result['idpersona']]);
                    $sancionesSimple = $querySancionesSimple->getRowArray();
                    if ($sancionesSimple && $sancionesSimple['sanciones']) {
                        $result['sanciones'] = $sancionesSimple['sanciones'];
                    }
                } catch (\Exception $e2) {
                    log_message('debug', 'No se pudieron obtener sanciones: ' . $e2->getMessage());
                }
            }
            
            // Intentar obtener observaciones del historial de devolución
            try {
                $sqlHistorial = "SELECT 
                                    detalles as observaciones_devolucion,
                                    fecha_accion as fecha_registro_devolucion
                                 FROM historial_usuarios 
                                 WHERE (accion LIKE '%Devolución%' OR accion LIKE '%devolucion%')
                                 AND detalles LIKE ?
                                 ORDER BY fecha_accion DESC
                                 LIMIT 1";
                $queryHistorial = $db->query($sqlHistorial, ["%Préstamo #{$idprestamo}%"]);
                $historialInfo = $queryHistorial->getRowArray();
                
                if ($historialInfo && $historialInfo['observaciones_devolucion']) {
                    // Extraer las observaciones del texto de detalles
                    $detalles = $historialInfo['observaciones_devolucion'];
                    if (preg_match('/Observaciones:\s*(.+)$/', $detalles, $matches)) {
                        $result['observaciones_devolucion'] = trim($matches[1]);
                        $result['fecha_observaciones_devolucion'] = $historialInfo['fecha_registro_devolucion'];
                    }
                }
            } catch (\Exception $e) {
                log_message('debug', 'No se pudo obtener información del historial de devolución: ' . $e->getMessage());
            }

            // Intentar obtener información del ejemplar físico si existe
            try {
                $sqlEjemplar = "SELECT 
                                    ef.codigo_ejemplar,
                                    ef.estado_ejemplar,
                                    ef.ubicacion,
                                    ef.observaciones as observaciones_ejemplar
                                FROM ejemplares_fisicos ef
                                WHERE ef.idrecurso = ?
                                LIMIT 1";
                $queryEjemplar = $db->query($sqlEjemplar, [$result['idrecurso']]);
                $ejemplar = $queryEjemplar->getRowArray();
                
                if ($ejemplar) {
                    $result = array_merge($result, $ejemplar);
                    // Crear observaciones generales combinando diferentes fuentes
                    $observacionesCombinadas = [];
                    
                    // Priorizar observaciones de devolución de la BD (ya están en $result['observaciones'])
                    if (!empty($result['observaciones'])) {
                        $observacionesCombinadas[] = $result['observaciones'];
                    }
                    
                    if (!empty($ejemplar['observaciones_ejemplar'])) {
                        $observacionesCombinadas[] = "Ejemplar: " . $ejemplar['observaciones_ejemplar'];
                    }
                    
                    if (!empty($result['observaciones_devolucion']) && $result['observaciones_devolucion'] !== $result['observaciones']) {
                        $observacionesCombinadas[] = "Historial: " . $result['observaciones_devolucion'];
                    }
                    
                    if ($result['dias_retraso'] > 0) {
                        $observacionesCombinadas[] = "Devolución con retraso de " . $result['dias_retraso'] . " día(s)";
                    }
                    
                    // Actualizar las observaciones finales manteniendo las originales separadas
                    if (!empty($observacionesCombinadas)) {
                        $result['observaciones_combinadas'] = implode(' | ', $observacionesCombinadas);
                    }
                }
            } catch (\Exception $e) {
                log_message('debug', 'No se pudo obtener información del ejemplar físico: ' . $e->getMessage());
            }
            
            // Intentar obtener información de categorías y editoriales si existen
            try {
                $sqlExtra = "SELECT 
                                c.categoria,
                                ed.editorial
                             FROM recursos r
                             LEFT JOIN subcategorias sc ON sc.idsubcategoria = r.idsubcategoria
                             LEFT JOIN categorias c ON c.idcategoria = sc.idcategoria
                             LEFT JOIN editoriales ed ON ed.ideditorial = r.ideditorial
                             WHERE r.idrecurso = ?";
                $queryExtra = $db->query($sqlExtra, [$result['idrecurso']]);
                $extra = $queryExtra->getRowArray();
                
                if ($extra) {
                    $result = array_merge($result, $extra);
                }
            } catch (\Exception $e) {
                log_message('debug', 'No se pudo obtener información adicional: ' . $e->getMessage());
            }
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getDetalleDevolucion: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener estadísticas generales para el reporte de préstamos por usuario
     */
    public function getEstadisticasGeneralesUsuarios()
    {
        $db = \Config\Database::connect();
        
        // Total de usuarios activos (con matrícula activa)
        $totalUsuarios = $db->query("
            SELECT COUNT(DISTINCT m.idpersona) as total
            FROM matriculas m
            WHERE m.estadomatricula = true
        ")->getRow()->total;
        
        // Total de préstamos
        $totalPrestamos = $this->countAllResults();
        
        // Préstamos pendientes (activos)
        $prestamosPendientes = $this->where('fechahoraretorno IS NULL', null, false)->countAllResults();
        
        // Préstamos vencidos
        $prestamosVencidos = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion < NOW()
                ELSE DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY) < NOW()
            END
        ")->getRow()->total;
        
        // Promedio de préstamos por usuario (mensual)
        $promedioMensual = $db->query("
            SELECT AVG(prestamos_por_usuario) as promedio
            FROM (
                SELECT COUNT(*) as prestamos_por_usuario
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                WHERE p.fechaprestamo >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                GROUP BY m.idpersona
            ) as stats
        ")->getRow()->promedio ?? 0;
        
        // Crecimiento mensual (comparar mes actual vs anterior)
        $prestamosEsteMes = $db->query("
            SELECT COUNT(*) as total
            FROM prestamos
            WHERE MONTH(fechaprestamo) = MONTH(CURDATE()) 
            AND YEAR(fechaprestamo) = YEAR(CURDATE())
        ")->getRow()->total;
        
        $prestamosMesAnterior = $db->query("
            SELECT COUNT(*) as total
            FROM prestamos
            WHERE MONTH(fechaprestamo) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
            AND YEAR(fechaprestamo) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
        ")->getRow()->total;
        
        $crecimiento = 0;
        if ($prestamosMesAnterior > 0) {
            $crecimiento = (($prestamosEsteMes - $prestamosMesAnterior) / $prestamosMesAnterior) * 100;
        }
        
        return [
            'total_usuarios' => $totalUsuarios,
            'total_prestamos' => $totalPrestamos,
            'prestamos_pendientes' => $prestamosPendientes,
            'prestamos_vencidos' => $prestamosVencidos,
            'promedio_mensual' => round($promedioMensual, 1),
            'crecimiento_mensual' => ($crecimiento >= 0 ? '+' : '') . round($crecimiento, 1) . '%'
        ];
    }

    /**
     * Obtener top usuarios más activos
     */
    public function getTopUsuariosActivos($limit = 5)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    CONCAT(per.nombres, ' ', per.apellidos) as nombre,
                    CONCAT(g.grado, '° ', g.nivel, ' ', g.seccion) as grado,
                    COUNT(p.idprestamo) as total_prestamos,
                    m.idpersona
                FROM prestamos p
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN grupos g ON g.idgrupo = m.idgrupo
                WHERE m.estadomatricula = true
                GROUP BY m.idpersona, per.nombres, per.apellidos, g.grado, g.nivel, g.seccion
                ORDER BY total_prestamos DESC
                LIMIT ?";
        
        $query = $db->query($sql, [$limit]);
        return $query->getResultArray();
    }

    /**
     * Obtener estadísticas detalladas de préstamos por usuario
     */
    public function getEstadisticasDetalladasUsuarios($filtros = [])
    {
        $db = \Config\Database::connect();
        
        $whereConditions = ['m.estadomatricula = true'];
        $params = [];
        
        // Aplicar filtros
        if (!empty($filtros['fecha_desde'])) {
            $whereConditions[] = 'p.fechaprestamo >= ?';
            $params[] = $filtros['fecha_desde'];
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $whereConditions[] = 'p.fechaprestamo <= ?';
            $params[] = $filtros['fecha_hasta'] . ' 23:59:59';
        }
        
        if (!empty($filtros['nivel'])) {
            $whereConditions[] = 'g.nivel = ?';
            $params[] = $filtros['nivel'];
        }
        
        if (!empty($filtros['grado'])) {
            $whereConditions[] = 'g.grado = ?';
            $params[] = $filtros['grado'];
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $sql = "SELECT 
                    m.idpersona,
                    CONCAT(per.nombres, ' ', per.apellidos) as nombre_completo,
                    per.email,
                    CONCAT(g.grado, '° ', g.nivel, ' ', g.seccion) as nivel_grado,
                    COUNT(p.idprestamo) as total_prestamos,
                    SUM(CASE WHEN p.fechahoraretorno IS NULL THEN 1 ELSE 0 END) as prestamos_activos,
                    SUM(CASE WHEN p.fechahoraretorno IS NOT NULL THEN 1 ELSE 0 END) as prestamos_completados,
                    SUM(CASE 
                        WHEN p.fechahoraretorno IS NULL 
                        AND CASE 
                            WHEN p.fechadevolucion IS NOT NULL THEN p.fechadevolucion < NOW()
                            ELSE DATE_ADD(p.fechaprestamo, INTERVAL 14 DAY) < NOW()
                        END THEN 1 ELSE 0 
                    END) as prestamos_vencidos,
                    MAX(p.fechaprestamo) as ultimo_prestamo,
                    ROUND(COUNT(p.idprestamo) / 
                        GREATEST(1, TIMESTAMPDIFF(MONTH, MIN(p.fechaprestamo), NOW())), 1
                    ) as promedio_mensual
                FROM matriculas m
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN grupos g ON g.idgrupo = m.idgrupo
                LEFT JOIN prestamos p ON p.idmatricula = m.idmatricula
                WHERE {$whereClause}
                GROUP BY m.idpersona, per.nombres, per.apellidos, per.email, g.grado, g.nivel, g.seccion
                HAVING total_prestamos > 0
                ORDER BY total_prestamos DESC";
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtener datos para gráfico de tendencias mensuales
     */
    public function getTendenciasMensuales($meses = 12)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT 
                    DATE_FORMAT(fechaprestamo, '%Y-%m') as mes,
                    DATE_FORMAT(fechaprestamo, '%M %Y') as mes_nombre,
                    COUNT(*) as total_prestamos
                FROM prestamos
                WHERE fechaprestamo >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(fechaprestamo, '%Y-%m')
                ORDER BY mes ASC";
        
        $query = $db->query($sql, [$meses]);
        return $query->getResultArray();
    }

    /**
     * Obtener detalle completo de un usuario específico
     */
    public function getDetalleCompletoUsuario($idpersona)
    {
        $db = \Config\Database::connect();
        
        // Información básica del usuario
        $infoUsuario = $db->query("
            SELECT 
                CONCAT(per.nombres, ' ', per.apellidos) as nombre_completo,
                per.email,
                per.telefono,
                per.numerodoc,
                CONCAT(g.grado, '° ', g.nivel, ' ', g.seccion) as nivel_grado,
                m.fechamatricula,
                m.idmatricula
            FROM personas per
            JOIN matriculas m ON m.idpersona = per.idpersona
            JOIN grupos g ON g.idgrupo = m.idgrupo
            WHERE per.idpersona = ? AND m.estadomatricula = true
        ", [$idpersona])->getRow();
        
        if (!$infoUsuario) {
            return null;
        }
        
        // Estadísticas del usuario
        $estadisticas = $db->query("
            SELECT 
                COUNT(*) as total_prestamos,
                SUM(CASE WHEN fechahoraretorno IS NULL THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN fechahoraretorno IS NOT NULL THEN 1 ELSE 0 END) as completados,
                SUM(CASE 
                    WHEN fechahoraretorno IS NULL 
                    AND CASE 
                        WHEN fechadevolucion IS NOT NULL THEN fechadevolucion < NOW()
                        ELSE DATE_ADD(fechaprestamo, INTERVAL 14 DAY) < NOW()
                    END THEN 1 ELSE 0 
                END) as vencidos,
                MAX(fechaprestamo) as ultimo_prestamo,
                MIN(fechaprestamo) as primer_prestamo
            FROM prestamos p
            WHERE p.idmatricula = ?
        ", [$infoUsuario->idmatricula])->getRow();
        
        // Historial de préstamos
        $historial = $this->getHistorialPrestamosByUsuario($infoUsuario->idmatricula, 20);
        
        return [
            'info_usuario' => $infoUsuario,
            'estadisticas' => $estadisticas,
            'historial' => $historial
        ];
    }

    /**
     * Obtener detalles completos de un préstamo específico
     */
    public function getDetallePrestamo($idprestamo)
    {
        try {
            $db = \Config\Database::connect();
            
            $sql = "SELECT 
                        p.*,
                        r.titulo,
                        r.anio,
                        r.isbn,
                        CONCAT(COALESCE(a.nomautor, ''), ' ', COALESCE(a.apeautor, '')) as nomautor,
                        e.editorial as nomeditorial,
                        COALESCE(rf.portada, rd.portada) as portada,
                        CONCAT(per.nombres, ' ', per.apellidos) as nombre_usuario,
                        per.email,
                        per.numerodoc,
                        CONCAT(g.grado, '° ', g.nivel, ' ', g.seccion) as nivel_grado,
                        (SELECT COUNT(*) FROM renovaciones_prestamo WHERE idprestamo = p.idprestamo) as num_renovaciones
                    FROM prestamos p
                    JOIN matriculas m ON m.idmatricula = p.idmatricula
                    JOIN personas per ON per.idpersona = m.idpersona
                    JOIN grupos g ON g.idgrupo = m.idgrupo
                    JOIN recursos r ON r.idrecurso = p.idrecurso
                    LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
                    LEFT JOIN autores a ON a.idautor = da.idautor
                    LEFT JOIN editoriales e ON e.ideditorial = r.ideditorial
                    LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                    LEFT JOIN recursos_digitales rd ON rd.idrecurso = r.idrecurso
                    WHERE p.idprestamo = ?
                    LIMIT 1";
            
            log_message('info', "Ejecutando consulta getDetallePrestamo para ID: {$idprestamo}");
            $query = $db->query($sql, [$idprestamo]);
            $result = $query->getRowArray();
            
            if ($result) {
                log_message('info', "Préstamo {$idprestamo} encontrado: " . $result['titulo']);
            } else {
                log_message('warning', "No se encontraron resultados para préstamo ID: {$idprestamo}");
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoModel::getDetallePrestamo(): ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener solicitudes de renovación pendientes
     */
    public function getSolicitudesRenovacionPendientes()
    {
        $db = \Config\Database::connect();
        
        // Verificar si la tabla existe
        if (!$db->tableExists('solicitudes_renovacion')) {
            log_message('warning', 'Tabla solicitudes_renovacion no existe');
            return [];
        }
        
        $sql = "SELECT 
                    sr.idsolicitud_renovacion as id,
                    sr.idprestamo,
                    CONCAT(per.nombres, ' ', per.apellidos) as usuario,
                    per.numerodoc as documento,
                    r.titulo as recurso,
                    CASE 
                        WHEN rf.idrecurso IS NOT NULL THEN CONCAT('LIB-FIS-', LPAD(r.idrecurso, 3, '0'))
                        ELSE CONCAT('LIB-DIG-', LPAD(r.idrecurso, 3, '0'))
                    END as codigo_ejemplar,
                    sr.fecha_solicitud,
                    sr.fecha_vencimiento_actual,
                    sr.nueva_fecha_inicio,
                    sr.nueva_fecha_devolucion,
                    sr.motivo,
                    sr.estado,
                    'Renovación' as tipo_solicitud,
                    CASE 
                        WHEN DATEDIFF(NOW(), sr.fecha_solicitud) >= 7 THEN 'Alta'
                        WHEN DATEDIFF(NOW(), sr.fecha_solicitud) >= 3 THEN 'Media'
                        ELSE 'Normal'
                    END as prioridad,
                    DATEDIFF(sr.nueva_fecha_devolucion, sr.nueva_fecha_inicio) as dias_extension,
                    true as disponible -- Las renovaciones siempre están disponibles (el libro ya está prestado)
                FROM solicitudes_renovacion sr
                JOIN prestamos p ON p.idprestamo = sr.idprestamo
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE sr.estado = 'pendiente'
                  AND p.fechahoraretorno IS NULL
                ORDER BY 
                    CASE 
                        WHEN DATEDIFF(NOW(), sr.fecha_solicitud) >= 7 THEN 1
                        WHEN DATEDIFF(NOW(), sr.fecha_solicitud) >= 3 THEN 2
                        ELSE 3
                    END,
                    sr.fecha_solicitud ASC";
        
        try {
            $query = $db->query($sql);
            $result = $query->getResultArray();
            
            log_message('info', 'Solicitudes de renovación pendientes obtenidas: ' . count($result));
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoModel::getSolicitudesRenovacionPendientes(): ' . $e->getMessage());
            return [];
        }
    }
}
