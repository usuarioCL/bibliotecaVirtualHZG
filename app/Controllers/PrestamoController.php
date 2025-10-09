<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PrestamoModel;

class PrestamoController extends Controller
{
    protected $prestamoModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->prestamoModel = new PrestamoModel();
    }

    /**
     * Página principal - Préstamos Activos
     */
    public function index()
    {
        try {
            $prestamos = $this->prestamoModel->getPrestamosActivos();
            $estadisticas = $this->prestamoModel->getEstadisticasPrestamos();

            $data = [
                'title' => 'Préstamos Activos',
                'prestamos' => $prestamos,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/index', $data);
        } catch (\Exception $e) {
            // En caso de error, mostrar datos de fallback
            log_message('error', 'Error en PrestamoController::index(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Préstamos Activos',
                'prestamos' => $this->getDatosPruebaPrestamos(),
                'estadisticas' => [
                    'total_prestamos' => 0,
                    'vencidos_hoy' => 0,
                    'proximos_vencer' => 0,
                    'renovaciones_pendientes' => 0
                ]
            ];

            return view('Administrador/prestamos/index', $data);
        }
    }

    /**
     * Solicitudes Pendientes
     */
    public function solicitudes()
    {
        try {
            $solicitudes = $this->prestamoModel->getSolicitudesPendientes();
            
            $estadisticas = [
                'total_solicitudes' => count($solicitudes),
                'hoy' => count(array_filter($solicitudes, function($s) {
                    return date('Y-m-d', strtotime($s['fecha_solicitud'])) == date('Y-m-d');
                })),
                'esta_semana' => count(array_filter($solicitudes, function($s) {
                    return date('Y-W', strtotime($s['fecha_solicitud'])) == date('Y-W');
                })),
                'esperando_aprobacion' => count($solicitudes)
            ];

            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => $solicitudes,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::solicitudes(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Solicitudes Pendientes',
                'solicitudes' => $this->getDatosPruebaSolicitudes(),
                'estadisticas' => [
                    'total_solicitudes' => 0,
                    'hoy' => 0,
                    'esta_semana' => 0,
                    'esperando_aprobacion' => 0
                ]
            ];

            return view('Administrador/prestamos/solicitudes', $data);
        }
    }

    /**
     * Devoluciones
     */
    public function devoluciones()
    {
        try {
            $devoluciones = $this->prestamoModel->getDevolucionesHoy();
            $estadisticas = $this->prestamoModel->getEstadisticasDevoluciones();

            $data = [
                'title' => 'Devoluciones',
                'devoluciones' => $devoluciones,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/devoluciones', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::devoluciones(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Devoluciones',
                'devoluciones' => $this->getDatosPruebaDevoluciones(),
                'estadisticas' => [
                    'devoluciones_hoy' => 0,
                    'con_retraso' => 0,
                    'danos_reportados' => 0,
                    'multas_generadas' => 0
                ]
            ];

            return view('Administrador/prestamos/devoluciones', $data);
        }
    }

    /**
     * Historial Completo
     */
    public function historial()
    {
        try {
            $historial = $this->prestamoModel->getHistorialCompleto();
            $estadisticas = $this->prestamoModel->getEstadisticasHistorial();

            $data = [
                'title' => 'Historial de Préstamos',
                'historial' => $historial,
                'estadisticas' => $estadisticas
            ];

            return view('Administrador/prestamos/historial', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en PrestamoController::historial(): ' . $e->getMessage());
            
            $data = [
                'title' => 'Historial de Préstamos',
                'historial' => $this->getDatosPruebaHistorial(),
                'estadisticas' => [
                    'total_registros' => 0,
                    'este_mes' => 0,
                    'promedio_mensual' => 0,
                    'tasa_devolucion' => 0
                ]
            ];

            return view('Administrador/prestamos/historial', $data);
        }
    }

}
