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
}

