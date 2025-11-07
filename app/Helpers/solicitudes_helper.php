<?php

/**
 * Helper para vistas de Solicitudes de Préstamos
 * Funciones auxiliares para formateo y generación de HTML
 */

if (!function_exists('formatear_fecha')) {
    /**
     * Formatea una fecha en formato dd/mm/yyyy
     * @param string|null $fecha Fecha a formatear
     * @return string Fecha formateada
     */
    function formatear_fecha($fecha) {
        if (empty($fecha)) {
            return 'N/A';
        }
        
        try {
            $dt = new DateTime($fecha);
            return $dt->format('d/m/Y');
        } catch (Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('formatear_fecha_hora')) {
    /**
     * Formatea una fecha con hora en formato dd/mm/yyyy HH:mm
     * @param string|null $fecha Fecha a formatear
     * @return string Fecha y hora formateadas
     */
    function formatear_fecha_hora($fecha) {
        if (empty($fecha)) {
            return 'N/A';
        }
        
        try {
            $dt = new DateTime($fecha);
            return $dt->format('d/m/Y H:i');
        } catch (Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('calcular_dias_espera')) {
    /**
     * Calcula los días transcurridos desde una fecha
     * @param string $fecha_inicio Fecha de inicio
     * @return int Número de días
     */
    function calcular_dias_espera($fecha_inicio) {
        if (empty($fecha_inicio)) {
            return 0;
        }
        
        try {
            $inicio = new DateTime($fecha_inicio);
            $ahora = new DateTime();
            $diferencia = $ahora->diff($inicio);
            return $diferencia->days;
        } catch (Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('badge_prioridad')) {
    /**
     * Genera el HTML para el badge de prioridad
     * @param string $prioridad Nivel de prioridad
     * @return string HTML del badge
     */
    function badge_prioridad($prioridad) {
        $configs = [
            'Alta' => [
                'clase' => 'bg-danger',
                'icono' => 'ti-alert-circle'
            ],
            'Media' => [
                'clase' => 'bg-warning',
                'icono' => 'ti-alert-triangle'
            ],
            'Baja' => [
                'clase' => 'bg-info',
                'icono' => 'ti-info-circle'
            ]
        ];
        
        $config = $configs[$prioridad] ?? $configs['Baja'];
        
        return sprintf(
            '<span class="badge %s"><i class="ti %s me-1"></i>%s</span>',
            $config['clase'],
            $config['icono'],
            htmlspecialchars($prioridad)
        );
    }
}

if (!function_exists('badge_disponibilidad')) {
    /**
     * Genera el HTML para el badge de disponibilidad
     * @param bool|int $disponible Estado de disponibilidad
     * @return string HTML del badge
     */
    function badge_disponibilidad($disponible) {
        if ($disponible == 1 || $disponible === true) {
            return '<span class="badge bg-success-subtle text-success">
                <i class="ti ti-check-circle me-1"></i>Disponible
            </span>';
        } else {
            return '<span class="badge bg-danger-subtle text-danger">
                <i class="ti ti-x-circle me-1"></i>No Disponible
            </span>';
        }
    }
}

if (!function_exists('badge_tipo_solicitud')) {
    /**
     * Genera el HTML para el badge del tipo de solicitud
     * @param string $tipo Tipo de solicitud (Nuevo/Renovación)
     * @return string HTML del badge
     */
    function badge_tipo_solicitud($tipo) {
        if ($tipo === 'Renovación') {
            return '<span class="badge bg-warning-subtle text-warning">
                <i class="ti ti-refresh me-1"></i>Renovación
            </span>';
        } else {
            return '<span class="badge bg-primary-subtle text-primary">
                <i class="ti ti-new-section me-1"></i>Nuevo
            </span>';
        }
    }
}

if (!function_exists('badge_dias_espera')) {
    /**
     * Genera el badge de días de espera con color según urgencia
     * @param int $dias Número de días
     * @return string HTML del badge
     */
    function badge_dias_espera($dias) {
        $clase = 'bg-secondary';
        $icono = 'ti-clock';
        
        if ($dias >= 7) {
            $clase = 'bg-danger';
            $icono = 'ti-alert-circle';
        } elseif ($dias >= 3) {
            $clase = 'bg-warning';
            $icono = 'ti-alert-triangle';
        } elseif ($dias >= 1) {
            $clase = 'bg-info';
            $icono = 'ti-clock-hour-3';
        }
        
        $texto = $dias === 1 ? '1 día' : "$dias días";
        
        return sprintf(
            '<span class="badge %s"><i class="ti %s me-1"></i>%s</span>',
            $clase,
            $icono,
            $texto
        );
    }
}

if (!function_exists('boton_aprobar')) {
    /**
     * Genera botón de aprobar solicitud
     * @param int $id ID de la solicitud
     * @param string $tipo Tipo de solicitud (prestamo/renovacion)
     * @param int|null $idprestamo ID del préstamo (para renovaciones)
     * @return string HTML del botón
     */
    function boton_aprobar($id, $tipo = 'prestamo', $idprestamo = null) {
        if ($tipo === 'renovacion' && $idprestamo) {
            $onclick = "aprobarRenovacion($id, $idprestamo)";
        } else {
            $onclick = "aprobarSolicitud($id)";
        }
        
        return sprintf(
            '<button type="button" class="btn btn-success btn-sm" onclick="%s" data-bs-toggle="tooltip" title="Aprobar solicitud">
                <i class="ti ti-check"></i>
            </button>',
            htmlspecialchars($onclick)
        );
    }
}

if (!function_exists('boton_rechazar')) {
    /**
     * Genera botón de rechazar solicitud
     * @param int $id ID de la solicitud
     * @param string $tipo Tipo de solicitud (prestamo/renovacion)
     * @return string HTML del botón
     */
    function boton_rechazar($id, $tipo = 'prestamo') {
        if ($tipo === 'renovacion') {
            $onclick = "rechazarRenovacion($id)";
        } else {
            $onclick = "rechazarSolicitud($id)";
        }
        
        return sprintf(
            '<button type="button" class="btn btn-danger btn-sm" onclick="%s" data-bs-toggle="tooltip" title="Rechazar solicitud">
                <i class="ti ti-x"></i>
            </button>',
            htmlspecialchars($onclick)
        );
    }
}

if (!function_exists('boton_ver_detalle')) {
    /**
     * Genera botón de ver detalles
     * @param int $id ID de la solicitud
     * @return string HTML del botón
     */
    function boton_ver_detalle($id) {
        return sprintf(
            '<button type="button" class="btn btn-info btn-sm" onclick="verDetalleSolicitud(%d)" data-bs-toggle="tooltip" title="Ver detalles">
                <i class="ti ti-eye"></i>
            </button>',
            $id
        );
    }
}

if (!function_exists('contar_por_estado')) {
    /**
     * Cuenta solicitudes por diferentes criterios
     * @param array $solicitudes Array de solicitudes
     * @param string $campo Campo a filtrar
     * @param mixed $valor Valor a buscar
     * @return int Cantidad de coincidencias
     */
    function contar_por_estado($solicitudes, $campo, $valor) {
        if (!is_array($solicitudes)) {
            return 0;
        }
        
        return count(array_filter($solicitudes, function($s) use ($campo, $valor) {
            return isset($s[$campo]) && $s[$campo] == $valor;
        }));
    }
}

if (!function_exists('formatear_autores')) {
    /**
     * Formatea un array de autores a texto
     * @param array|null $autores Array de autores
     * @return string Texto formateado
     */
    function formatear_autores($autores) {
        if (empty($autores) || !is_array($autores)) {
            return 'No especificado';
        }
        
        $nombres = array_map(function($autor) {
            $texto = trim($autor['nombre_completo'] ?? $autor['nombre'] ?? '');
            if (!empty($autor['nacionalidad'])) {
                $texto .= ' (' . $autor['nacionalidad'] . ')';
            }
            return $texto;
        }, $autores);
        
        return implode(', ', array_filter($nombres));
    }
}

if (!function_exists('truncar_texto')) {
    /**
     * Trunca un texto a cierta longitud
     * @param string $texto Texto a truncar
     * @param int $longitud Longitud máxima
     * @param string $sufijo Sufijo a agregar
     * @return string Texto truncado
     */
    function truncar_texto($texto, $longitud = 50, $sufijo = '...') {
        if (empty($texto)) {
            return '';
        }
        
        $texto = strip_tags($texto);
        
        if (mb_strlen($texto) <= $longitud) {
            return $texto;
        }
        
        return mb_substr($texto, 0, $longitud) . $sufijo;
    }
}
