<?php

if (!function_exists('obtenerTipoUsuario')) {
    /**
     * Obtener el tipo de usuario basándose en el nomuser
     * 
     * @param string $nomuser Nombre de usuario
     * @return string Tipo de usuario (admin, docente, estudiante, desconocido)
     */
    function obtenerTipoUsuario($nomuser)
    {
        try {
            $usuarioModel = new \App\Models\usuarioModel();
            $usuario = $usuarioModel->where('nomuser', $nomuser)->first();
            
            if ($usuario) {
                $tipo = $usuario['nivelacceso'] ?? 'estudiante';
                // Debug temporal
                log_message('debug', "Tipo de usuario para {$nomuser}: {$tipo}");
                return $tipo;
            }
            
            // Si no se encuentra, intentar inferir por el nombre
            if (strpos($nomuser, 'admin') === 0) {
                return 'admin';
            }
            
            // Fallback a estudiante en lugar de desconocido (que no está en el ENUM)
            return 'estudiante';
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener tipo de usuario: ' . $e->getMessage());
            return 'estudiante'; // Fallback a estudiante
        }
    }
}

if (!function_exists('registrarAccionHistorial')) {
    /**
     * Registrar una acción en el historial de usuarios
     * 
     * @param string $accion La acción realizada
     * @param string $usuarioActor Usuario que realizó la acción
     * @param string|null $usuarioAfectado Usuario afectado por la acción
     * @param string $tipoUsuario Tipo de usuario (admin, docente, estudiante)
     * @param string|null $detalles Detalles adicionales de la acción
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarAccionHistorial($accion, $usuarioActor, $usuarioAfectado = null, $detalles = null)
    {
        try {
            // Debug: log de entrada
            log_message('debug', "Intentando registrar acción: {$accion} por {$usuarioActor} afectando {$usuarioAfectado}");
            
            $historialModel = new \App\Models\HistorialUsuarioModel();
            
            // Obtener el tipo del usuario afectado automáticamente
            $tipoUsuarioAfectado = $usuarioAfectado ? obtenerTipoUsuario($usuarioAfectado) : 'admin';
            
            // Debug: log del tipo detectado
            log_message('debug', "Tipo detectado para {$usuarioAfectado}: {$tipoUsuarioAfectado}");
            
            $data = [
                'accion' => $accion,
                'usuario_actor' => $usuarioActor,
                'usuario_afectado' => $usuarioAfectado,
                'tipo_usuario' => $tipoUsuarioAfectado,
                'detalles' => $detalles
            ];

            // Debug: log de los datos a insertar
            log_message('debug', "Datos a insertar: " . json_encode($data));

            $resultado = $historialModel->registrarAccion($data);
            
            // Debug: log de resultado
            log_message('debug', "Resultado del registro: " . ($resultado ? "Éxito ID {$resultado}" : "Falló"));
            
            return $resultado;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar acción en historial: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('obtenerUsuarioActual')) {
    /**
     * Obtener información del usuario actual
     * 
     * @return array Información del usuario actual
     */
    function obtenerUsuarioActual()
    {
        $session = \Config\Services::session();
        
        // Intentar obtener el usuario de CodeIgniter primero
        $nomuser = $session->get('nomuser') ?? $session->get('usuario') ?? $session->get('username');
        
        // Si no se encuentra, intentar con la sesión nativa de PHP
        if (!$nomuser && session_status() === PHP_SESSION_ACTIVE) {
            $nomuser = $_SESSION['nomuser'] ?? $_SESSION['usuario'] ?? $_SESSION['username'] ?? null;
        }
        
        // Si no se encuentra usuario, usar 'sistema' como fallback
        if (!$nomuser) {
            $nomuser = 'sistema';
        }
        
        $nivelacceso = $session->get('nivelacceso') ?? $session->get('nivel') ?? 'admin';
        
        // Fallback a sesión nativa si no se encuentra
        if ($nivelacceso === 'admin' && session_status() === PHP_SESSION_ACTIVE) {
            $nivelacceso = $_SESSION['nivelacceso'] ?? $_SESSION['nivel'] ?? 'admin';
        }
        
        return [
            'usuario' => $nomuser,
            'tipo' => $nivelacceso
        ];
    }
}

if (!function_exists('registrarAccionAutomatica')) {
    /**
     * Registrar una acción automáticamente con el usuario actual
     * 
     * @param string $accion La acción realizada
     * @param string|null $usuarioAfectado Usuario afectado por la acción
     * @param string|null $detalles Detalles adicionales de la acción
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarAccionAutomatica($accion, $usuarioAfectado = null, $detalles = null)
    {
        $usuarioActual = obtenerUsuarioActual();
        
        // Debug: log del usuario actual
        log_message('debug', "Usuario actual detectado: " . json_encode($usuarioActual));
        
        return registrarAccionHistorial(
            $accion,
            $usuarioActual['usuario'],
            $usuarioAfectado,
            $detalles
        );
    }
}

if (!function_exists('registrarCreacionUsuario')) {
    /**
     * Registrar la creación de un usuario
     * 
     * @param string $usuarioCreado Usuario que fue creado
     * @param string $tipoUsuario Tipo del usuario creado
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarCreacionUsuario($usuarioCreado, $tipoUsuario)
    {
        return registrarAccionAutomatica(
            'Usuario creado',
            $usuarioCreado,
            "Nuevo {$tipoUsuario} registrado en el sistema"
        );
    }
}

if (!function_exists('registrarEliminacionUsuario')) {
    /**
     * Registrar la eliminación de un usuario
     * 
     * @param string $usuarioEliminado Usuario que fue eliminado
     * @param string $tipoUsuario Tipo del usuario eliminado
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarEliminacionUsuario($usuarioEliminado, $tipoUsuario)
    {
        return registrarAccionAutomatica(
            'Usuario eliminado',
            $usuarioEliminado,
            "Usuario {$tipoUsuario} eliminado del sistema"
        );
    }
}

if (!function_exists('registrarActualizacionUsuario')) {
    /**
     * Registrar la actualización de un usuario
     * 
     * @param string $usuarioActualizado Usuario que fue actualizado
     * @param string $tipoUsuario Tipo del usuario actualizado
     * @param string $detalles Detalles de la actualización
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarActualizacionUsuario($usuarioActualizado, $tipoUsuario, $detalles = null)
    {
        return registrarAccionAutomatica(
            'Perfil actualizado',
            $usuarioActualizado,
            $detalles ?? "Información de {$tipoUsuario} actualizada"
        );
    }
}

if (!function_exists('registrarCambioContraseña')) {
    /**
     * Registrar el cambio de contraseña de un usuario
     * 
     * @param string $usuario Usuario que cambió su contraseña
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarCambioContraseña($usuario)
    {
        return registrarAccionAutomatica(
            'Contraseña cambiada',
            $usuario,
            'Contraseña de usuario actualizada'
        );
    }
}

if (!function_exists('registrarSuspensionUsuario')) {
    /**
     * Registrar la suspensión de un usuario
     * 
     * @param string $usuarioSuspendido Usuario que fue suspendido
     * @param string $tipoUsuario Tipo del usuario suspendido
     * @param string $motivo Motivo de la suspensión
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarSuspensionUsuario($usuarioSuspendido, $tipoUsuario, $motivo = null)
    {
        return registrarAccionAutomatica(
            'Usuario suspendido',
            $usuarioSuspendido,
            $motivo ?? "Usuario {$tipoUsuario} suspendido"
        );
    }
}

if (!function_exists('registrarReactivacionUsuario')) {
    /**
     * Registrar la reactivación de un usuario
     * 
     * @param string $usuarioReactivado Usuario que fue reactivado
     * @param string $tipoUsuario Tipo del usuario reactivado
     * @return bool|int ID del registro creado o false si hay error
     */
    function registrarReactivacionUsuario($usuarioReactivado, $tipoUsuario)
    {
        return registrarAccionAutomatica(
            'Usuario reactivado',
            $usuarioReactivado,
            "Usuario {$tipoUsuario} reactivado"
        );
    }
}
