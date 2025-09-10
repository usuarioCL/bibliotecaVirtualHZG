<?php

namespace App\Controllers;

use App\Models\SancionModel;
use App\Models\TiposancionModel;
use App\Models\personaModel;

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
            'sanciones' => $sanciones,
            'navbar' => view('layouts/navbar'),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer')
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/index', $datos);
        }

        return view('Administrador/sanciones/index', $datos);
    }

    /**
     * Mostrar formulario para crear nueva sanción
     */
    public function crear()
    {
        $datos = [
            'tiposSancion' => $this->tiposancionModel->getTiposSancionOrdenados(),
            'personas' => $this->personaModel->orderBy('apellidos', 'ASC')->findAll(),
            'navbar' => view('layouts/navbar'),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer')
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/crear', $datos);
        }

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
            'personas' => $this->personaModel->orderBy('apellidos', 'ASC')->findAll(),
            'navbar' => view('layouts/navbar'),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer')
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/editar', $datos);
        }

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
            'tipos' => $tipos,
            'navbar' => view('layouts/navbar'),
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer')
        ];

        if ($this->request->isAJAX()) {
            return view('Administrador/sanciones/tipos', $datos);
        }

        return view('Administrador/sanciones/tipos', $datos);
    }

    /**
     * Crear tipo de sanción
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
}
