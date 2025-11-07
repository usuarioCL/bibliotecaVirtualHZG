<?php

if (!function_exists('calcularEstadoPrestamo')) {
    /**
     * Calcular el estado de un préstamo
     * 
     * @param array $prestamo Datos del préstamo
     * @return array ['estado' => string, 'clase' => string, 'icono' => string, 'texto' => string]
     */
    function calcularEstadoPrestamo($prestamo)
    {
        // Préstamo devuelto
        if (!empty($prestamo['fechahoraretorno'])) {
            return [
                'estado' => 'devuelto',
                'clase' => 'success',
                'icono' => 'check',
                'texto' => 'Devuelto'
            ];
        }

        // Préstamo sin fecha de devolución
        if (empty($prestamo['fechadevolucion'])) {
            return [
                'estado' => 'activo',
                'clase' => 'primary',
                'icono' => 'clock',
                'texto' => 'Activo'
            ];
        }

        $fechaVencimiento = strtotime($prestamo['fechadevolucion']);
        $hoy = time();

        // Préstamo vencido
        if ($fechaVencimiento < $hoy) {
            return [
                'estado' => 'vencido',
                'clase' => 'danger',
                'icono' => 'exclamation-triangle',
                'texto' => 'Vencido'
            ];
        }

        // Préstamo por vencer (menos de 3 días)
        $diasRestantes = ceil(($fechaVencimiento - $hoy) / (60 * 60 * 24));
        if ($diasRestantes <= 3) {
            return [
                'estado' => 'por_vencer',
                'clase' => 'warning',
                'icono' => 'exclamation-circle',
                'texto' => 'Por Vencer'
            ];
        }

        // Préstamo activo normal
        return [
            'estado' => 'activo',
            'clase' => 'success',
            'icono' => 'clock',
            'texto' => 'Activo'
        ];
    }
}

if (!function_exists('formatearFechaPrestamo')) {
    /**
     * Formatear fecha de préstamo de forma amigable
     * 
     * @param string $fecha Fecha en formato SQL
     * @param string $formato Formato de salida (default: 'd/M/Y')
     * @return string Fecha formateada
     */
    function formatearFechaPrestamo($fecha, $formato = 'd/M/Y')
    {
        if (empty($fecha)) {
            return 'Sin fecha';
        }

        try {
            return date($formato, strtotime($fecha));
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('obtenerInfoFechaVencimiento')) {
    /**
     * Obtener información de la fecha de vencimiento
     * 
     * @param array $prestamo Datos del préstamo
     * @return array ['texto' => string, 'clase' => string, 'icono' => string]
     */
    function obtenerInfoFechaVencimiento($prestamo)
    {
        if (!empty($prestamo['fechahoraretorno'])) {
            return [
                'texto' => formatearFechaPrestamo($prestamo['fechahoraretorno']),
                'clase' => 'success',
                'icono' => 'calendar-check'
            ];
        }

        if (empty($prestamo['fechadevolucion'])) {
            return [
                'texto' => 'Sin fecha',
                'clase' => 'muted',
                'icono' => 'calendar'
            ];
        }

        $fechaVencimiento = strtotime($prestamo['fechadevolucion']);
        $hoy = time();
        $esVencido = $fechaVencimiento < $hoy;

        return [
            'texto' => formatearFechaPrestamo($prestamo['fechadevolucion']),
            'clase' => $esVencido ? 'danger' : 'success',
            'icono' => $esVencido ? 'exclamation-triangle' : 'clock'
        ];
    }
}

if (!function_exists('renderBadgeEstado')) {
    /**
     * Renderizar badge de estado del préstamo
     * 
     * @param array $prestamo Datos del préstamo
     * @return string HTML del badge
     */
    function renderBadgeEstado($prestamo)
    {
        $estado = calcularEstadoPrestamo($prestamo);
        
        return sprintf(
            '<span class="badge bg-%s"><i class="fas fa-%s me-1"></i>%s</span>',
            esc($estado['clase']),
            esc($estado['icono']),
            esc($estado['texto'])
        );
    }
}

if (!function_exists('esPrestamoVencido')) {
    /**
     * Verificar si un préstamo está vencido
     * 
     * @param array $prestamo Datos del préstamo
     * @return bool
     */
    function esPrestamoVencido($prestamo)
    {
        if (!empty($prestamo['fechahoraretorno'])) {
            return false;
        }

        if (empty($prestamo['fechadevolucion'])) {
            return false;
        }

        return strtotime($prestamo['fechadevolucion']) < time();
    }
}

if (!function_exists('calcularDiasRestantes')) {
    /**
     * Calcular días restantes para la devolución
     * 
     * @param array $prestamo Datos del préstamo
     * @return int|null Días restantes (null si no aplica)
     */
    function calcularDiasRestantes($prestamo)
    {
        if (!empty($prestamo['fechahoraretorno']) || empty($prestamo['fechadevolucion'])) {
            return null;
        }

        $fechaVencimiento = strtotime($prestamo['fechadevolucion']);
        $hoy = time();
        
        return ceil(($fechaVencimiento - $hoy) / (60 * 60 * 24));
    }
}

if (!function_exists('obtenerNombreAutor')) {
    /**
     * Obtener el nombre del autor de forma segura
     * 
     * @param array $prestamo Datos del préstamo
     * @return string Nombre del autor o texto por defecto
     */
    function obtenerNombreAutor($prestamo)
    {
        return $prestamo['nomautor'] ?? 'Sin autor';
    }
}

if (!function_exists('validarFechaRenovacion')) {
    /**
     * Validar que la fecha de renovación esté dentro del rango permitido
     * 
     * @param string $fechaInicio Fecha de inicio del préstamo
     * @param string $fechaDevolucion Nueva fecha de devolución
     * @param int $maxDias Máximo de días permitidos (default: 7)
     * @return array ['valido' => bool, 'mensaje' => string, 'dias' => int]
     */
    function validarFechaRenovacion($fechaInicio, $fechaDevolucion, $maxDias = 7)
    {
        $inicio = strtotime($fechaInicio);
        $fin = strtotime($fechaDevolucion);
        
        if ($inicio === false || $fin === false) {
            return [
                'valido' => false,
                'mensaje' => 'Fechas inválidas',
                'dias' => 0
            ];
        }

        $diffDias = ceil(($fin - $inicio) / (60 * 60 * 24));

        if ($diffDias < 0) {
            return [
                'valido' => false,
                'mensaje' => 'La fecha de devolución no puede ser anterior a la fecha de inicio',
                'dias' => $diffDias
            ];
        }

        if ($diffDias > $maxDias) {
            return [
                'valido' => false,
                'mensaje' => "No puede extender el préstamo por más de {$maxDias} días",
                'dias' => $diffDias
            ];
        }

        return [
            'valido' => true,
            'mensaje' => "Renovación válida por {$diffDias} día(s)",
            'dias' => $diffDias
        ];
    }
}

if (!function_exists('obtenerMensajeExtension')) {
    /**
     * Obtener mensaje de extensión de préstamo
     * 
     * @param int $dias Número de días de extensión
     * @return string Mensaje formateado
     */
    function obtenerMensajeExtension($dias)
    {
        if ($dias === 1) {
            return 'El préstamo se extenderá por 1 día más';
        }
        
        return "El préstamo se extenderá por {$dias} días más";
    }
}
