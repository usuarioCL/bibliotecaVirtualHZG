<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class RecursoDigitalController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        // Consulta para obtener recursos digitales con información completa
        $query = $this->db->query("
            SELECT 
                r.idrecurso,
                r.titulo,
                r.anio,
                e.editorial,
                c.categoria,
                s.subcategoria,
                t.tiporecurso,
                d.archivo,
                d.portada
            FROM recursos r
            INNER JOIN recursos_digitales d ON r.idrecurso = d.idrecurso
            INNER JOIN editoriales e ON r.ideditorial = e.ideditorial
            INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria
            INNER JOIN categorias c ON s.idcategoria = c.idcategoria
            INNER JOIN tiporecursos t ON r.idtiporecurso = t.idtiporecurso
            ORDER BY r.idrecurso ASC
        ");

        $data['recursos_digitales'] = $query->getResult();

        // Si NO es AJAX, agregar layouts
        if (!$this->request->isAJAX()) {
            $data['navbar'] = view('layouts/navbar');
            $data['header'] = view('layouts/header');
            $data['footer'] = view('layouts/footer');
        }

        return view('recursos_digitales/listar', $data);
    }

    public function eliminar($idrecurso = null)
    {
        // Redirigir al controlador principal de recursos para eliminar
        // ya que la lógica de eliminación está centralizada ahí
        return redirect()->to(base_url('recursos/eliminar/' . $idrecurso));
    }
}
