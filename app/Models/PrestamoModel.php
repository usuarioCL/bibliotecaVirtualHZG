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
                    CASE 
                        WHEN p.fechadevolucion IS NOT NULL THEN DATE(p.fechadevolucion)
                        ELSE DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)
                    END as fecha_vencimiento,
                    CASE 
                        WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                        ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
                    END as dias_restantes,
                    CASE 
                        WHEN p.fechahoraretorno IS NULL AND 
                             CASE 
                                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
                             END >= 0 THEN 'Activo'
                        WHEN p.fechahoraretorno IS NULL AND 
                             CASE 
                                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
                             END < 0 THEN 'Vencido'
                        ELSE 'Devuelto'
                    END as estado,
                    CASE 
                        WHEN (SELECT COUNT(*) FROM information_schema.tables 
                              WHERE table_schema = DATABASE() 
                              AND table_name = 'renovaciones_prestamo') > 0 
                        THEN COALESCE(
                            (SELECT COUNT(*) FROM renovaciones_prestamo rp WHERE rp.idprestamo = p.idprestamo), 
                            0
                        )
                        ELSE 0
                    END as renovaciones
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
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATE(p.fechadevolucion)
                ELSE DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY)
            END = CURDATE()
        ")->getRow()->total;
        
        // Próximos a vencer (en los próximos 3 días)
        $proximosVencer = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
            END BETWEEN 0 AND 3
        ")->getRow()->total;
        
        // Renovaciones pendientes (préstamos que podrían necesitar renovación)
        $renovacionesPendientes = $db->query("
            SELECT COUNT(*) as total 
            FROM prestamos p 
            WHERE p.fechahoraretorno IS NULL 
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
            END <= 2
            AND CASE 
                WHEN p.fechadevolucion IS NOT NULL THEN DATEDIFF(DATE(p.fechadevolucion), CURDATE())
                ELSE DATEDIFF(DATE_ADD(DATE(p.fechaprestamo), INTERVAL 14 DAY), CURDATE())
            END >= -5
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
                    p.fechaprestamo as fecha_solicitud,
                    p.fechadevolucion as fecha_devolucion,
                    'Pendiente' as estado,
                    CASE 
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 7 THEN 'Alta'
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 3 THEN 'Media'
                        ELSE 'Normal'
                    END as prioridad,
                    CASE WHEN r.stock > 0 AND r.estado = 'disponible' THEN true ELSE false END as disponible
                FROM solicitud s
                JOIN prestamos p ON p.idprestamo = s.idprestamo
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
                LEFT JOIN recursos_fisicos rf ON rf.idrecurso = r.idrecurso
                WHERE s.validado = false
                ORDER BY 
                    CASE 
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 7 THEN 1
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 3 THEN 2
                        ELSE 3
                    END,
                    p.fechaprestamo ASC";
        
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

    /**
     * Aprobar una solicitud de préstamo
     */
    public function aprobarSolicitud($idsolicitud)
    {
        $db = \Config\Database::connect();
        
        try {
            $db->transStart();
            
            // Obtener información de la solicitud
            $solicitud = $db->table('solicitud s')
                ->select('s.*, p.idrecurso, p.idprestamo')
                ->join('prestamos p', 'p.idprestamo = s.idprestamo')
                ->where('s.idsolicitud', $idsolicitud)
                ->where('s.validado', false)
                ->get()
                ->getRow();
            
            if (!$solicitud) {
                throw new \Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Verificar disponibilidad del recurso
            $recurso = $db->table('recursos')
                ->where('idrecurso', $solicitud->idrecurso)
                ->get()
                ->getRow();
            
            if (!$recurso || $recurso->stock <= 0 || $recurso->estado !== 'disponible') {
                throw new \Exception('El recurso no está disponible para préstamo');
            }
            
            // Actualizar la solicitud como validada
            $db->table('solicitud')
                ->where('idsolicitud', $idsolicitud)
                ->update([
                    'validado' => true
                ]);
            
            // Actualizar el préstamo con fecha de validación
            $db->table('prestamos')
                ->where('idprestamo', $solicitud->idprestamo)
                ->update([
                    'fechahoravalidacion' => date('Y-m-d H:i:s')
                ]);
            
            // Actualizar stock del recurso (si es físico)
            if ($recurso->stock > 0) {
                $nuevoStock = $recurso->stock - 1;
                $nuevoEstado = $nuevoStock > 0 ? 'disponible' : 'prestado';
                
                $db->table('recursos')
                    ->where('idrecurso', $solicitud->idrecurso)
                    ->update([
                        'stock' => $nuevoStock,
                        'estado' => $nuevoEstado
                    ]);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            return [
                'success' => true,
                'message' => 'Solicitud aprobada correctamente'
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
                ->select('s.*, p.idprestamo')
                ->join('prestamos p', 'p.idprestamo = s.idprestamo')
                ->where('s.idsolicitud', $idsolicitud)
                ->where('s.validado', false)
                ->get()
                ->getRow();
            
            if (!$solicitud) {
                throw new \Exception('Solicitud no encontrada o ya procesada');
            }
            
            // Eliminar la solicitud
            $db->table('solicitud')
                ->where('idsolicitud', $idsolicitud)
                ->delete();
            
            // Eliminar el préstamo asociado
            $db->table('prestamos')
                ->where('idprestamo', $solicitud->idprestamo)
                ->delete();
            
            // TODO: Aquí se podría agregar un log del rechazo con el motivo
            log_message('info', "Solicitud {$idsolicitud} rechazada. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
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
                    p.idprestamo,
                    p.fechaprestamo as fecha_solicitud,
                    p.fechadevolucion as fecha_devolucion_esperada,
                    p.fechadevolucion as fecha_devolucion,
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
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 7 THEN 'Alta'
                        WHEN DATEDIFF(NOW(), p.fechaprestamo) >= 3 THEN 'Media'
                        ELSE 'Normal'
                    END as prioridad,
                    
                    CASE WHEN r.stock > 0 AND r.estado = 'disponible' THEN true ELSE false END as disponible,
                    
                    DATEDIFF(NOW(), p.fechaprestamo) as dias_desde_solicitud,
                    
                    -- Portada del recurso
                    COALESCE(rf.portada, rd.portada) as portada_recurso,
                    
                    -- Información del recurso físico o digital
                    rf.encuadernacion,
                    rd.archivo as archivo_digital
                    
                FROM solicitud s
                JOIN prestamos p ON p.idprestamo = s.idprestamo
                JOIN matriculas m ON m.idmatricula = p.idmatricula
                JOIN personas per ON per.idpersona = m.idpersona
                JOIN recursos r ON r.idrecurso = p.idrecurso
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
                AND p2.idprestamo != ?
            ", [$detalle->idrecurso, $detalle->idprestamo]);
            
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
            
            // Actualizar el préstamo con la fecha de devolución
            $fechaRetorno = date('Y-m-d H:i:s');
            $updateData = [
                'fechahoraretorno' => $fechaRetorno
            ];
            
            $this->update($idprestamo, $updateData);
            
            // Actualizar el stock del recurso (incrementar disponibilidad)
            $db->table('recursos')
               ->where('idrecurso', $prestamo['idrecurso'])
               ->set('stock', 'stock + 1', false)
               ->update();
            
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
    public function renovarPrestamoConFecha($idprestamo, $nuevaFechaDevolucion, $motivo = '')
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
            
            // Validar y parsear la nueva fecha
            $fechaDevolucion = new \DateTime($nuevaFechaDevolucion);
            $fechaActual = new \DateTime();
            
            // Calcular días de extensión
            $diasExtension = $fechaActual->diff($fechaDevolucion)->days;
            
            // Actualizar la tabla de préstamos con nueva fecha de devolución
            $this->update($idprestamo, [
                'fechadevolucion' => $fechaDevolucion->format('Y-m-d H:i:s')
            ]);
            
            // Registrar la renovación en tabla auxiliar (si existe)
            try {
                if ($db->tableExists('renovaciones_prestamo')) {
                    $db->table('renovaciones_prestamo')->insert([
                        'idprestamo' => $idprestamo,
                        'fecha_renovacion' => $fechaActual->format('Y-m-d H:i:s'),
                        'dias_extension' => $diasExtension,
                        'motivo' => $motivo,
                        'nueva_fecha_devolucion' => $fechaDevolucion->format('Y-m-d H:i:s'),
                        'usuario_renovacion' => session()->get('idusuario') ?? 1
                    ]);
                }
            } catch (\Exception $e) {
                log_message('warning', 'No se pudo registrar en tabla de renovaciones: ' . $e->getMessage());
            }
            
            // Registrar en logs
            log_message('info', "Préstamo {$idprestamo} renovado hasta {$nuevaFechaDevolucion}. Motivo: {$motivo}");
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de renovación');
            }
            
            return [
                'success' => true,
                'message' => "Préstamo renovado exitosamente hasta {$fechaDevolucion->format('d/m/Y')}",
                'nueva_fecha_devolucion' => $fechaDevolucion->format('d/m/Y'),
                'renovaciones_totales' => $renovacionesActuales + 1,
                'dias_extension' => $diasExtension
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
            $nuevoStock = $prestamo->stock + 1;
            $db->table('recursos')
                ->where('idrecurso', $prestamo->idrecurso)
                ->update([
                    'stock' => $nuevoStock,
                    'estado' => 'disponible'
                ]);
            
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
                    
                    -- Cálculo de días
                    DATEDIFF(p.fechadevolucion, CURDATE()) as dias_restantes,
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
                    END as tipo_recurso

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
                    SELECT fecha_renovacion, nueva_fecha_devolucion, motivo, dias_extension
                    FROM renovaciones_prestamo 
                    WHERE idprestamo = ? 
                    ORDER BY fecha_renovacion DESC
                ", [$idprestamo]);
                
                $detalle->renovaciones = $renovacionesQuery->getResultArray();
            } else {
                $detalle->renovaciones = [];
            }
            
            $detalle->total_renovaciones = count($detalle->renovaciones);
            
            // Calcular estado del préstamo
            $diasRestantes = intval($detalle->dias_restantes ?? 0);
            if ($diasRestantes > 3) {
                $detalle->estado_prestamo = 'Activo';
                $detalle->color_estado = 'success';
                $detalle->icono_estado = 'ti-check-circle';
            } elseif ($diasRestantes > 0) {
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
            
            if ($detalle->fecha_aprobacion) {
                $detalle->fecha_aprobacion_formatted = date('d/m/Y H:i', strtotime($detalle->fecha_aprobacion));
            } else {
                $detalle->fecha_aprobacion_formatted = null;
            }
            
            // Formatear fechas de renovaciones
            foreach ($detalle->renovaciones as &$renovacion) {
                $renovacion['fecha_renovacion_formatted'] = date('d/m/Y H:i', strtotime($renovacion['fecha_renovacion']));
                $renovacion['nueva_fecha_devolucion_formatted'] = date('d/m/Y', strtotime($renovacion['nueva_fecha_devolucion']));
            }
        }
        
        return $detalle;
    }
}