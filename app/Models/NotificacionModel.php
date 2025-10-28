<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'idnotificacion';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'idusuario',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
        'idprestamo',
        'idsolicitud',
        'idsancion', // CAMBIO 2025-10-28: Agregado para soportar notificaciones de sanciones
        'leida_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'idusuario' => 'required|integer',
        // CAMBIO 2025-10-28: Agregado 'sancion' al listado de tipos válidos
        'tipo' => 'required|in_list[aprobacion,rechazo,vencimiento,renovacion,devolucion,sancion]',
        'titulo' => 'required|max_length[100]',
        'mensaje' => 'required',
        'leida' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'idusuario' => [
            'required' => 'El ID de usuario es obligatorio',
            'integer' => 'El ID de usuario debe ser un número'
        ],
        'tipo' => [
            'required' => 'El tipo de notificación es obligatorio',
            'in_list' => 'El tipo de notificación no es válido'
        ],
        'titulo' => [
            'required' => 'El título es obligatorio',
            'max_length' => 'El título no puede exceder 100 caracteres'
        ],
        'mensaje' => [
            'required' => 'El mensaje es obligatorio'
        ]
    ];

    /**
     * Crear una notificación
     */
    public function crearNotificacion($datos)
    {
        try {
            $notificacion = [
                'idusuario' => $datos['idusuario'],
                'tipo' => $datos['tipo'],
                'titulo' => $datos['titulo'],
                'mensaje' => $datos['mensaje'],
                'leida' => false,
                'idprestamo' => $datos['idprestamo'] ?? null,
                'idsolicitud' => $datos['idsolicitud'] ?? null,
                'idsancion' => $datos['idsancion'] ?? null // CAMBIO 2025-10-28: Agregado para vincular notificación con sanción
            ];

            if ($this->insert($notificacion)) {
                log_message('info', 'Notificación creada para usuario #' . $datos['idusuario'] . ' - Tipo: ' . $datos['tipo']);
                return $this->insertID();
            }
            
            return false;
        } catch (\Exception $e) {
            log_message('error', 'Error al crear notificación: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function obtenerNoLeidas($idusuario)
    {
        return $this->where('idusuario', $idusuario)
                    ->where('leida', false)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNoLeidas($idusuario)
    {
        return $this->where('idusuario', $idusuario)
                    ->where('leida', false)
                    ->countAllResults();
    }

    /**
     * Obtener todas las notificaciones de un usuario (con paginación)
     */
    public function obtenerPorUsuario($idusuario, $limite = 20, $offset = 0)
    {
        return $this->where('idusuario', $idusuario)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limite, $offset);
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarComoLeida($idnotificacion, $idusuario)
    {
        try {
            $notificacion = $this->where('idnotificacion', $idnotificacion)
                                 ->where('idusuario', $idusuario)
                                 ->first();

            if (!$notificacion) {
                return false;
            }

            return $this->update($idnotificacion, [
                'leida' => true,
                'leida_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al marcar notificación como leída: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas($idusuario)
    {
        try {
            return $this->where('idusuario', $idusuario)
                        ->where('leida', false)
                        ->set([
                            'leida' => true,
                            'leida_at' => date('Y-m-d H:i:s')
                        ])
                        ->update();
        } catch (\Exception $e) {
            log_message('error', 'Error al marcar todas las notificaciones como leídas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar notificaciones antiguas (más de X días)
     */
    public function eliminarAntiguas($dias = 30)
    {
        try {
            $fecha = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
            
            return $this->where('created_at <', $fecha)
                        ->where('leida', true)
                        ->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar notificaciones antiguas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener notificaciones recientes con información completa
     */
    public function obtenerNotificacionesCompletas($idusuario, $limite = 10)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                n.*,
                p.fechaprestamo,
                p.fechadevolucion,
                r.titulo as recurso_titulo,
                s.fecha_solicitud
            FROM notificaciones n
            LEFT JOIN prestamos p ON p.idprestamo = n.idprestamo
            LEFT JOIN recursos r ON r.idrecurso = p.idrecurso
            LEFT JOIN solicitud s ON s.idsolicitud = n.idsolicitud
            WHERE n.idusuario = ?
            ORDER BY n.created_at DESC
            LIMIT ?
        ";
        
        $query = $db->query($sql, [$idusuario, $limite]);
        return $query->getResultArray();
    }
}

