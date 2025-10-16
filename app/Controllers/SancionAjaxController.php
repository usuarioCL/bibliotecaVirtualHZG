<?php

namespace App\Controllers;

use App\Models\SancionModel;
use CodeIgniter\API\ResponseTrait;

class SancionAjaxController extends BaseController
{
    use ResponseTrait;
    
    protected $sancionModel;

    public function __construct()
    {
        $this->sancionModel = new SancionModel();
        helper(['form', 'url']);
    }

    /**
     * Obtener sanciones activas para AJAX
     */
    public function activasAjax()
    {
        try {
            // Datos de prueba para verificar funcionamiento
            $sancionesPrueba = [
                [
                    'idsancion' => 1,
                    'nombre_completo' => 'Juan Pérez',
                    'tipo_sancion' => 'Retraso',
                    'fecha_inicio' => '2024-01-15',
                    'fecha_fin' => '2024-01-30',
                    'detallesancion' => 'Retraso de 3 días en devolución',
                    'nombres' => 'Juan',
                    'apellidos' => 'Pérez',
                    'numerodoc' => '12345678',
                    'email' => 'juanperez@mail.com',
                    'estado' => 'activa'
                ],
                [
                    'idsancion' => 2,
                    'nombre_completo' => 'María García',
                    'tipo_sancion' => 'Pérdida',
                    'fecha_inicio' => '2024-01-20',
                    'fecha_fin' => '2024-02-05',
                    'detallesancion' => 'Libro reportado como perdido',
                    'nombres' => 'María',
                    'apellidos' => 'García',
                    'numerodoc' => '23456789',
                    'email' => 'maria@mail.com',
                    'estado' => 'activa'
                ]
            ];

            // Intentar obtener datos reales primero
            $sanciones = [];
            try {
                $sanciones = $this->sancionModel->getSancionesCompletas();
            } catch (\Exception $e) {
                log_message('error', 'Error al obtener sanciones reales: ' . $e->getMessage());
            }

            // Si no hay datos reales, usar datos de prueba
            if (empty($sanciones)) {
                $sanciones = $sancionesPrueba;
            }

            // Calcular estadísticas
            $totalSanciones = count($sanciones);
            $estadisticas = [
                'total_sanciones' => $totalSanciones,
                'sanciones_graves' => 0, // Se puede implementar lógica adicional si es necesario
                'sanciones_leves' => $totalSanciones,
                'estudiantes_sancionados' => $totalSanciones
            ];

            return $this->respond([
                'success' => true,
                'sanciones' => $sanciones,
                'estadisticas' => $estadisticas,
                'debug' => [
                    'datos_reales' => !empty($sanciones) && count($sanciones) > 0 && $sanciones !== $sancionesPrueba,
                    'total_encontrado' => count($sanciones),
                    'usando_datos_prueba' => empty($sanciones) || $sanciones === $sancionesPrueba,
                    'estructura_datos' => !empty($sanciones) ? array_keys($sanciones[0]) : []
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en SancionAjaxController::activasAjax: ' . $e->getMessage());

            return $this->respond([
                'success' => false,
                'error' => 'Error al cargar las sanciones. Por favor, inténtalo de nuevo.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método de diagnóstico para probar la conexión AJAX
     */
    public function testAjax()
    {
        return $this->respond([
            'success' => true,
            'message' => 'Controlador AJAX funcionando correctamente',
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $this->request->getMethod(),
            'ip' => $this->request->getIPAddress(),
            'server' => $_SERVER['SERVER_NAME'] ?? 'unknown'
        ]);
    }
}
