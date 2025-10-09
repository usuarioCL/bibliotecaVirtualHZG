<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HistorialUsuarioModel;

class HistorialUsuarioController extends BaseController
{
    protected $historialModel;

    public function __construct()
    {
        $this->historialModel = new HistorialUsuarioModel();
    }

    /**
     * Vista principal del historial de usuarios
     */
    public function index()
    {
        // Verificar si es una petición AJAX
        if ($this->request->isAJAX()) {
            return $this->ajaxIndex();
        }

        $data = [
            'title' => 'Historial de Usuarios'
        ];

        return view('Administrador/historial/index', $data);
    }

    /**
     * Vista AJAX para el panel de administración
     */
    public function ajaxIndex()
    {
        $data = [
            'title' => 'Historial de Usuarios'
        ];

        return view('Administrador/historial/ajax_index', $data);
    }

    /**
     * Obtener historial de usuarios via AJAX
     */
    public function getHistorialAjax()
    {
        try {
            // Obtener parámetros de filtrado
            $busqueda = $this->request->getGet('busqueda');
            $tipoAccion = $this->request->getGet('tipo_accion');
            $tipoUsuario = $this->request->getGet('tipo_usuario');
            $fecha = $this->request->getGet('fecha');
            $limite = $this->request->getGet('limite') ?? 50;

            // Preparar filtros
            $filtros = [];
            if ($tipoAccion) $filtros['tipo_accion'] = $tipoAccion;
            if ($tipoUsuario) $filtros['tipo_usuario'] = $tipoUsuario;
            if ($fecha) {
                switch ($fecha) {
                    case 'hoy':
                        $filtros['fecha_desde'] = date('Y-m-d');
                        break;
                    case 'ayer':
                        $filtros['fecha_desde'] = date('Y-m-d', strtotime('-1 day'));
                        break;
                    case 'semana':
                        $filtros['fecha_desde'] = date('Y-m-d', strtotime('-7 days'));
                        break;
                    case 'mes':
                        $filtros['fecha_desde'] = date('Y-m-d', strtotime('-30 days'));
                        break;
                }
            }

            // Obtener historial
            if ($busqueda) {
                $historial = $this->historialModel->buscarHistorial($busqueda, $filtros);
            } else {
                $historial = $this->historialModel->getHistorialConPaginacion($limite);
            }

            // Formatear datos para la respuesta
            $historialFormateado = [];
            foreach ($historial as $registro) {
                $historialFormateado[] = [
                    'id' => $registro['id_historial'],
                    'accion' => $registro['accion'],
                    'usuario_actor' => $registro['usuario_actor'],
                    'usuario_afectado' => $registro['usuario_afectado'],
                    'tipo_usuario' => $registro['tipo_usuario'],
                    'fecha' => $registro['fecha_accion'],
                    'detalles' => $registro['detalles']
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $historialFormateado,
                'total' => count($historialFormateado)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener historial: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener el historial'
            ]);
        }
    }

    /**
     * Obtener estadísticas del historial
     */
    public function estadisticas()
    {
        try {
            $estadisticas = $this->historialModel->getEstadisticas();
            
            // Obtener usuarios suspendidos (usuarios que fueron suspendidos pero no reactivados)
            $usuariosSuspendidos = $this->historialModel
                ->select('usuario_afectado')
                ->where('accion', 'Usuario suspendido')
                ->groupBy('usuario_afectado')
                ->findAll();
            
            $usuariosReactivados = $this->historialModel
                ->select('usuario_afectado')
                ->where('accion', 'Usuario reactivado')
                ->groupBy('usuario_afectado')
                ->findAll();
            
            // Contar usuarios suspendidos que no han sido reactivados
            $suspendidosActivos = 0;
            foreach ($usuariosSuspendidos as $suspendido) {
                $fueReactivado = false;
                foreach ($usuariosReactivados as $reactivado) {
                    if ($suspendido['usuario_afectado'] === $reactivado['usuario_afectado']) {
                        $fueReactivado = true;
                        break;
                    }
                }
                if (!$fueReactivado) {
                    $suspendidosActivos++;
                }
            }
            
            $estadisticas['usuarios_suspendidos'] = $suspendidosActivos;
            
            // Obtener última actividad
            $ultimaActividad = $this->historialModel->select('fecha_accion')
                                                   ->orderBy('fecha_accion', 'DESC')
                                                   ->first();
            
            $estadisticas['ultima_actividad_fecha'] = $ultimaActividad ? $ultimaActividad['fecha_accion'] : null;

            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }

    /**
     * Calcular tiempo transcurrido desde una fecha
     */
    private function calcularTiempoTranscurrido($fecha)
    {
        $ahora = new \DateTime();
        $fechaAccion = new \DateTime($fecha);
        $diferencia = $ahora->diff($fechaAccion);

        if ($diferencia->d > 0) {
            return $diferencia->d . ' día(s)';
        } elseif ($diferencia->h > 0) {
            return $diferencia->h . ' hora(s)';
        } elseif ($diferencia->i > 0) {
            return $diferencia->i . ' minuto(s)';
        } else {
            return 'Hace un momento';
        }
    }

    /**
     * Registrar una nueva acción en el historial
     */
    public function registrarAccion()
    {
        try {
            $data = [
                'accion' => $this->request->getPost('accion'),
                'usuario_actor' => $this->request->getPost('usuario_actor'),
                'usuario_afectado' => $this->request->getPost('usuario_afectado'),
                'tipo_usuario' => $this->request->getPost('tipo_usuario'),
                'detalles' => $this->request->getPost('detalles')
            ];

            $id = $this->historialModel->registrarAccion($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Acción registrada correctamente',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al registrar acción: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al registrar la acción'
            ]);
        }
    }

}
