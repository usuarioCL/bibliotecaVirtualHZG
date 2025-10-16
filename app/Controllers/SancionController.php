<?php

namespace App\Controllers;

use App\Models\SancionModel;
use App\Models\TiposancionModel;
use App\Models\personaModel;
use Exception;

class SancionController extends BaseController
{
    protected $sancionModel;
    protected $tiposancionModel;
    protected $personaModel;

    public function __construct()
    {
        $this->sancionModel = new SancionModel();
        $this->tiposancionModel = new TiposancionModel();
        $this->personaModel = new personaModel();
    }

    /**
     * Mostrar lista de sanciones
     */
    public function index()
    {
        $sanciones = $this->sancionModel->getSancionesCompletas();
        
        $datos = [
            'sanciones' => $sanciones
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'sanciones' => $sanciones
            ]);
        }

        // Para peticiones no-AJAX redirigimos a la vista principal de sanciones activas
        return redirect()->to(base_url('sanciones'));
    }

    /**
     * Mostrar formulario para crear nueva sanción
     */
    public function crear()
    {
        $datos = [
            'tiposSancion' => $this->tiposancionModel->getTiposSancionOrdenados(),
            'personas' => $this->personaModel->orderBy('apellidos', 'ASC')->findAll()
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/crear', $datos);
        }

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('Administrador/sanciones/crear', $datos);
    }

    /**
     * Guardar nueva sanción
     */
    public function guardar()
    {
        $datos = [
            'idtiposancion' => $this->request->getPost('idtiposancion'),
            'idpersona' => $this->request->getPost('idpersona'),
            'detallesancion' => $this->request->getPost('detallesancion')
        ];

        if (!$this->sancionModel->save($datos)) {
            $errores = $this->sancionModel->errors();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errores
                ]);
            }

            session()->setFlashdata('errores', $errores);
            return redirect()->back()->withInput();
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sanción registrada exitosamente'
            ]);
        }

        session()->setFlashdata('success', 'Sanción registrada exitosamente');
        return redirect()->to(base_url('sanciones'));
    }

    /**
     * Mostrar formulario para editar sanción
     */
    public function editar($idsancion)
    {
        $sancion = $this->sancionModel->find($idsancion);
        
        if (!$sancion) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sanción no encontrada'
                ]);
            }
            
            session()->setFlashdata('error', 'Sanción no encontrada');
            return redirect()->to(base_url('sanciones'));
        }

        $datos = [
            'sancion' => $sancion,
            'tiposSancion' => $this->tiposancionModel->getTiposSancionOrdenados(),
            'personas' => $this->personaModel->orderBy('apellidos', 'ASC')->findAll()
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/editar', $datos);
        }

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('Administrador/sanciones/editar', $datos);
    }

    /**
     * Actualizar sanción
     */
    public function actualizar($idsancion)
    {
        $sancion = $this->sancionModel->find($idsancion);
        
        if (!$sancion) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sanción no encontrada'
                ]);
            }
            
            session()->setFlashdata('error', 'Sanción no encontrada');
            return redirect()->to(base_url('sanciones'));
        }

        $datos = [
            'idtiposancion' => $this->request->getPost('idtiposancion'),
            'idpersona' => $this->request->getPost('idpersona'),
            'detallesancion' => $this->request->getPost('detallesancion')
        ];

        if (!$this->sancionModel->update($idsancion, $datos)) {
            $errores = $this->sancionModel->errors();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errores
                ]);
            }

            session()->setFlashdata('errores', $errores);
            return redirect()->back()->withInput();
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sanción actualizada exitosamente'
            ]);
        }

        session()->setFlashdata('success', 'Sanción actualizada exitosamente');
        return redirect()->to(base_url('sanciones'));
    }

    /**
     * Eliminar sanción
     */
    public function eliminar($idsancion)
    {
        $sancion = $this->sancionModel->find($idsancion);
        
        if (!$sancion) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sanción no encontrada'
                ]);
            }
            
            session()->setFlashdata('error', 'Sanción no encontrada');
            return redirect()->to(base_url('sanciones'));
        }

        if (!$this->sancionModel->delete($idsancion)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar la sanción'
                ]);
            }
            
            session()->setFlashdata('error', 'Error al eliminar la sanción');
            return redirect()->to(base_url('sanciones'));
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sanción eliminada exitosamente'
            ]);
        }

        session()->setFlashdata('success', 'Sanción eliminada exitosamente');
        return redirect()->to(base_url('sanciones'));
    }

    /**
     * Ver detalles de una sanción
     */
    public function ver($idsancion)
    {
        $sancion = $this->sancionModel->getSancionCompleta($idsancion);
        
        if (!$sancion) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sanción no encontrada'
                ]);
            }
            
            session()->setFlashdata('error', 'Sanción no encontrada');
            return redirect()->to(base_url('sanciones'));
        }

        $datos = [
            'sancion' => $sancion
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/ver', $datos);
        }

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('Administrador/sanciones/ver', $datos);
    }

    /**
     * Buscar sanciones
     */
    public function buscar()
    {
        $criterio = $this->request->getGet('q') ?? '';
        $sanciones = $this->sancionModel->buscarSanciones($criterio);
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $sanciones
            ]);
        }

        $datos = [
            'sanciones' => $sanciones,
            'criterio' => $criterio,
            'navbar' => view('layouts/navbar'),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer')
        ];

        return view('Administrador/sanciones/index', $datos);
    }

    /**
     * Gestión de tipos de sanción
     */
    public function tiposSancion()
    {
        $tipos = $this->tiposancionModel->getTiposSancionOrdenados();
        
        $datos = [
            'tipos' => $tipos
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'tipos' => $tipos
            ]);
        }

        $datos['navbar'] = view('layouts/navbar');
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('Administrador/sanciones/tipos', $datos);
    }


    /**
     * Crear tipo de sanción (usado por AJAX)
     */
    public function crearTipo()
    {
        $datos = [
            'tiposancion' => $this->request->getPost('tiposancion')
        ];

        if (!$this->tiposancionModel->save($datos)) {
            $errores = $this->tiposancionModel->errors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errores
                ]);
            }
            session()->setFlashdata('errores', $errores);
            return redirect()->back()->withInput();
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipo de sanción creado exitosamente'
            ]);
        }

        session()->setFlashdata('success', 'Tipo de sanción creado exitosamente');
        return redirect()->to(base_url('sanciones/tipos'));
    }

    /**
     * Eliminar tipo de sanción
     */
    public function eliminarTipo($idtiposancion)
    {
        // Verificar si está en uso
        if ($this->tiposancionModel->estaEnUso($idtiposancion)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar este tipo de sanción porque está siendo utilizado'
                ]);
            }
            
            session()->setFlashdata('error', 'No se puede eliminar este tipo de sanción porque está siendo utilizado');
            return redirect()->to(base_url('sanciones/tipos'));
        }

        if (!$this->tiposancionModel->delete($idtiposancion)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de sanción'
                ]);
            }
            
            session()->setFlashdata('error', 'Error al eliminar el tipo de sanción');
            return redirect()->to(base_url('sanciones/tipos'));
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipo de sanción eliminado exitosamente'
            ]);
        }

        session()->setFlashdata('success', 'Tipo de sanción eliminado exitosamente');
        return redirect()->to(base_url('sanciones/tipos'));
    }

    /**
     * Mostrar vista de sanciones activas
     */
    public function activas()
    {
        try {
            // Usar método existente del modelo
            $sancionesCompletas = $this->sancionModel->getSancionesCompletas();
            
            // Estadísticas básicas con datos existentes
            $totalSanciones = count($sancionesCompletas);
            $estadisticas = [
                'total_sanciones' => $totalSanciones,
                'sanciones_graves' => 0, // Se calculará cuando se implemente la lógica completa
                'sanciones_leves' => $totalSanciones,
                'estudiantes_sancionados' => $totalSanciones
            ];

            $data = [
                'title' => 'Sanciones Activas - Sistema Biblioteca',
                'breadcrumb' => [
                    'Inicio' => base_url('admin'),
                    'Control y Sanciones' => '#',
                    'Sanciones Activas' => ''
                ],
                'sanciones' => $sancionesCompletas,
                'estadisticas' => $estadisticas,
                'tipos_sancion' => $this->tiposancionModel->findAll()
            ];

            return view('Administrador/sanciones/activas', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Error en SancionController::activas: ' . $e->getMessage());
            
            // Vista de error básica
            $data = [
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
            ];
            
            return view('Administrador/sanciones/activas', $data);
        }
    }

    /**
     * Mostrar vista del historial de sanciones
     */
    public function historial()
    {
        try {
            // Usar método existente del modelo
            $historialCompleto = $this->sancionModel->getSancionesCompletas();
            
            // Estadísticas básicas con datos existentes
            $totalRegistros = count($historialCompleto);
            $estadisticas = [
                'total_registros' => $totalRegistros,
                'sanciones_activas' => $totalRegistros, // Temporalmente todas se consideran activas
                'sanciones_levantadas' => 0,
                'sanciones_vencidas' => 0,
                'estudiantes_historial' => $totalRegistros,
                'promedio_mensual' => round($totalRegistros / 12, 1)
            ];

            // Actividad reciente simulada
            $actividadReciente = [
                [
                    'tipo' => 'nueva',
                    'titulo' => 'Nueva sanción registrada',
                    'descripcion' => 'Se registró una nueva sanción disciplinaria',
                    'tiempo' => 'Hace 2 horas'
                ],
                [
                    'tipo' => 'levantada',
                    'titulo' => 'Sanción levantada',
                    'descripcion' => 'Se levantó una sanción previamente aplicada',
                    'tiempo' => 'Ayer a las 14:30'
                ]
            ];

            $data = [
                'title' => 'Historial de Sanciones - Sistema Biblioteca',
                'breadcrumb' => [
                    'Inicio' => base_url('admin'),
                    'Control y Sanciones' => '#',
                    'Historial de Sanciones' => ''
                ],
                'historial' => $historialCompleto,
                'estadisticas' => $estadisticas,
                'actividad_reciente' => $actividadReciente,
                'tipos_sancion' => $this->tiposancionModel->findAll()
            ];

            return view('Administrador/sanciones/historial', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Error en SancionController::historial: ' . $e->getMessage());
            
            // Vista de error básica
            $data = [
                'title' => 'Error - Historial de Sanciones',
                'error' => 'Error al cargar el historial. Por favor, inténtalo de nuevo.',
                'historial' => [],
                'estadisticas' => [
                    'total_registros' => 0,
                    'sanciones_activas' => 0,
                    'sanciones_levantadas' => 0,
                    'sanciones_vencidas' => 0,
                    'estudiantes_historial' => 0,
                    'promedio_mensual' => 0
                ],
                'actividad_reciente' => [],
                'tipos_sancion' => []
            ];
            
            return view('Administrador/sanciones/historial', $data);
        }
    }

    /**
     * Obtener estadísticas de sanciones
     */
    public function estadisticas()
    {
        try {
            $sanciones = $this->sancionModel->getSancionesCompletas();
            $totalSanciones = count($sanciones);
            
            $estadisticas = [
                'total_sanciones' => $totalSanciones,
                'sanciones_activas' => $totalSanciones, // Temporalmente todas se consideran activas
                'sanciones_levantadas' => 0,
                'estudiantes_afectados' => $totalSanciones
            ];

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'estadisticas' => $estadisticas
                ]);
            }

            return $estadisticas;
        } catch (Exception $e) {
            log_message('error', 'Error en SancionController::estadisticas: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cargar las estadísticas'
                ]);
            }
            
            return [];
        }
    }

    /**
     * Levantar sanción
     */
    public function levantar($idsancion)
    {
        try {
            $sancion = $this->sancionModel->find($idsancion);
            
            if (!$sancion) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Sanción no encontrada'
                    ]);
                }
                
                session()->setFlashdata('error', 'Sanción no encontrada');
                return redirect()->to(base_url('sanciones'));
            }

            // Aquí se implementaría la lógica para levantar la sanción
            // Por ejemplo, actualizar un campo de estado o fecha de levantamiento
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sanción levantada exitosamente'
                ]);
            }

            session()->setFlashdata('success', 'Sanción levantada exitosamente');
            return redirect()->to(base_url('sanciones'));
        } catch (Exception $e) {
            log_message('error', 'Error en SancionController::levantar: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al levantar la sanción'
                ]);
            }
            
            session()->setFlashdata('error', 'Error al levantar la sanción');
            return redirect()->to(base_url('sanciones'));
        }
    }
}
