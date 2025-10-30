<?php

namespace App\Controllers;

use App\Models\NotificacionModel;

class NotificacionController extends BaseController
{
    protected $notificacionModel;

    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
    }

    /**
     * Obtener el contador de notificaciones no leídas (AJAX)
     */
    public function contarNoLeidas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idusuario = session()->get('idusuario');
            $contador = $this->notificacionModel->contarNoLeidas($idusuario);

            return $this->response->setJSON([
                'success' => true,
                'contador' => $contador
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al contar notificaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener notificaciones'
            ]);
        }
    }

    /**
     * Obtener listado de notificaciones (AJAX)
     */
    public function obtenerNotificaciones()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idusuario = session()->get('idusuario');
            $limite = $this->request->getGet('limite') ?? 10;
            
            $notificaciones = $this->notificacionModel->obtenerNotificacionesCompletas($idusuario, $limite);
            $contador = $this->notificacionModel->contarNoLeidas($idusuario);

            return $this->response->setJSON([
                'success' => true,
                'notificaciones' => $notificaciones,
                'contador' => $contador
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener notificaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener notificaciones'
            ]);
        }
    }

    /**
     * Marcar una notificación como leída (AJAX)
     */
    public function marcarLeida()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idnotificacion = $this->request->getPost('idnotificacion');
            $idusuario = session()->get('idusuario');

            if (!$idnotificacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de notificación requerido'
                ]);
            }

            $resultado = $this->notificacionModel->marcarComoLeida($idnotificacion, $idusuario);

            if ($resultado) {
                $contador = $this->notificacionModel->contarNoLeidas($idusuario);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notificación marcada como leída',
                    'contador' => $contador
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo marcar la notificación'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al marcar notificación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas (AJAX)
     */
    public function marcarTodasLeidas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idusuario = session()->get('idusuario');
            $resultado = $this->notificacionModel->marcarTodasComoLeidas($idusuario);

            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Todas las notificaciones marcadas como leídas',
                    'contador' => 0
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudieron marcar las notificaciones'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al marcar todas las notificaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }

    // CAMBIO 2025-10-30: Elimina una notificación individual
    // Endpoint: POST /notificaciones/eliminar
    public function eliminarNotificacion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idnotificacion = $this->request->getPost('idnotificacion');
            $idusuario = session()->get('idusuario');

            if (!$idnotificacion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de notificación requerido'
                ]);
            }

            $resultado = $this->notificacionModel->eliminarNotificacion($idnotificacion, $idusuario);

            if ($resultado) {
                $contador = $this->notificacionModel->contarNoLeidas($idusuario);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notificación eliminada',
                    'contador' => $contador
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo eliminar la notificación'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar notificación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }

    // CAMBIO 2025-10-30: Elimina solo las notificaciones marcadas como leídas
    // Endpoint: POST /notificaciones/eliminar-todas-leidas
    public function eliminarTodasLeidas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idusuario = session()->get('idusuario');
            $resultado = $this->notificacionModel->eliminarTodasLeidas($idusuario);

            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notificaciones leídas eliminadas'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudieron eliminar las notificaciones'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar notificaciones leídas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }

    // CAMBIO 2025-10-30: Elimina TODAS las notificaciones del usuario
    // Endpoint: POST /notificaciones/eliminar-todas
    public function eliminarTodas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autenticado'
            ]);
        }

        try {
            $idusuario = session()->get('idusuario');
            $resultado = $this->notificacionModel->eliminarTodas($idusuario);

            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Todas las notificaciones eliminadas'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudieron eliminar las notificaciones'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar todas las notificaciones: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }

    // CAMBIO 2025-10-30: Vista del historial completo de notificaciones
    // Muestra hasta 50 notificaciones con opciones de eliminación
    public function historial()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $idusuario = session()->get('idusuario');
        $notificaciones = $this->notificacionModel->obtenerNotificacionesCompletas($idusuario, 50);
        $contador = $this->notificacionModel->contarNoLeidas($idusuario);

        $datos = [
            'notificaciones' => $notificaciones,
            'contador' => $contador,
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'navbar' => view('layouts/navbar')
        ];

        return view('notificaciones/historial', $datos);
    }
}

