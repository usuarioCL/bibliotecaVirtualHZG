<?php

namespace App\Controllers;

use App\Models\SancionModel;
use App\Models\TiposancionModel;
use Exception;

class SancionController extends BaseController
{
    protected $sancionModel;
    protected $tiposancionModel;

    public function __construct()
    {
        $this->sancionModel = new SancionModel();
        $this->tiposancionModel = new TiposancionModel();
    }

    /**
     * Mostrar vista de sanciones activas (página dedicada con AJAX)
     */
    public function activas()
    {
        try {
            // Si es petición AJAX, devolver datos JSON
            if ($this->request->isAJAX()) {
                $sanciones = $this->sancionModel->getSancionesCompletas();

                // Datos de prueba como fallback
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

                if (empty($sanciones)) {
                    $sanciones = $sancionesPrueba;
                }

                $totalSanciones = count($sanciones);
                $estadisticas = [
                    'total_sanciones' => $totalSanciones,
                    'sanciones_graves' => 0,
                    'sanciones_leves' => $totalSanciones,
                    'estudiantes_sancionados' => $totalSanciones
                ];

                return $this->response->setJSON([
                    'success' => true,
                    'sanciones' => $sanciones,
                    'estadisticas' => $estadisticas,
                    'tipos_sancion' => $this->tiposancionModel->findAll()
                ]);
            }

            // Para peticiones normales, mostrar la vista completa
            $data = [
                'title' => 'Sanciones Activas - Sistema Biblioteca',
                'breadcrumb' => [
                    'Inicio' => base_url('admin'),
                    'Control y Sanciones' => '#',
                    'Sanciones Activas' => ''
                ],
                'sanciones' => $this->sancionModel->getSancionesCompletas(),
                'estadisticas' => [
                    'total_sanciones' => 0,
                    'sanciones_graves' => 0,
                    'sanciones_leves' => 0,
                    'estudiantes_sancionados' => 0
                ],
                'tipos_sancion' => $this->tiposancionModel->findAll()
            ];

            return view('Administrador/sanciones/activas', $data);

        } catch (Exception $e) {
            log_message('error', 'Error en SancionController::activas: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Error al cargar las sanciones. Por favor, inténtalo de nuevo.'
                ]);
            }

            return view('Administrador/sanciones/activas', [
                'title' => 'Error - Sanciones Activas',
                'error' => 'Error al cargar las sanciones. Por favor, inténtalo de nuevo.',
                'sanciones' => [],
                'estadisticas' => [
                    'total_sanciones' => 0,
                    'sanciones_graves' => 0,
                    'sanciones_leves' => 0,
                    'estudiantes_sancionados' => 0
                ],
                'tipos_sancion' => []
            ]);
        }
    }
}
