<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AutorModel;
use App\Models\DetAutorModel;

class AutorController extends Controller
{
    public function index(): string
    {
        $model = new AutorModel();

        $q = trim($this->request->getGet('q') ?? '');
        if ($q !== '') {
            $model->groupStart()
                  ->like('nomautor', $q)
                  ->orLike('apeautor', $q)
                  ->orLike('nacionalidad', $q)
                  ->groupEnd();
        }

        $datos['q'] = $q;
        $datos['autores'] = $model->orderBy('idautor', 'ASC')->paginate(10, 'autores');
        $datos['pager']   = $model->pager;

        return view('autores/listar', $datos);
    }

    public function crear(): string
    {
        return view('autores/crear');
    }

    public function guardar()
    {
        $model = new AutorModel();
        $data = [
            'apeautor' => trim($this->request->getPost('apeautor') ?? ''),
            'nomautor' => trim($this->request->getPost('nomautor') ?? ''),
            'nacionalidad' => trim($this->request->getPost('nacionalidad') ?? ''),
        ];

        // Validación mínima
        if ($data['nomautor'] === '' && $data['apeautor'] === '') {
            return $this->response->setStatusCode(422)->setBody('Debe ingresar al menos nombre o apellido.');
        }

        $model->insert($data);
        return redirect()->to(base_url('autores'));
    }

    public function editar($id = null)
    {
        $model = new AutorModel();
        $autor = $model->find($id);
        if (!$autor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Autor no encontrado');
        }
        return view('autores/editar', ['autor' => $autor]);
    }

    public function actualizar($id)
    {
        $model = new AutorModel();
        $data = [
            'apeautor' => trim($this->request->getPost('apeautor') ?? ''),
            'nomautor' => trim($this->request->getPost('nomautor') ?? ''),
            'nacionalidad' => trim($this->request->getPost('nacionalidad') ?? ''),
        ];
        $model->update($id, $data);
        return redirect()->to(base_url('autores'));
    }

    public function eliminar($id)
    {
        $model = new AutorModel();
        $det = new DetAutorModel();
        try {
            // Eliminar relaciones autor-recurso primero
            $det->where('idautor', $id)->delete();
            // Luego eliminar autor
            $model->delete($id);
            return redirect()->to(base_url('autores'));
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(400)->setBody('No se pudo eliminar el autor.');
        }
    }

    public function buscar(): string
    {
        // Alias de index con filtro q, para compatibilidad con enlaces AJAX
        return $this->index();
    }
}
