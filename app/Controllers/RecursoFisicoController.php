<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class RecursoFisicoController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        // Consulta para obtener recursos físicos con información completa
        $query = $this->db->query("
            SELECT 
                r.idrecurso,
                r.titulo,
                r.anio,
                r.numpaginas,
                r.isbn,
                r.numedicion,
                r.estado,
                r.stock,
                r.nivel,
                e.editorial,
                c.categoria,
                s.subcategoria,
                t.tiporecurso,
                rf.portada,
                rf.encuadernacion
            FROM recursos r
            INNER JOIN recursos_fisicos rf ON r.idrecurso = rf.idrecurso
            INNER JOIN editoriales e ON r.ideditorial = e.ideditorial
            INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria
            INNER JOIN categorias c ON s.idcategoria = c.idcategoria
            INNER JOIN tiporecursos t ON r.idtiporecurso = t.idtiporecurso
            ORDER BY r.idrecurso ASC
        ");

        $data['recursos_fisicos'] = $query->getResult();

        // Si NO es AJAX, agregar layouts
        if (!$this->request->isAJAX()) {
            $data['navbar'] = view('layouts/navbar');
            $data['header'] = view('layouts/header');
            $data['footer'] = view('layouts/footer');
        }

        return view('recursos_fisicos/listar', $data);
    }
}
