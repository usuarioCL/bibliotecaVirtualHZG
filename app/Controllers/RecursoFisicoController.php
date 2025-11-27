<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class RecursoFisicoController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $perPage = 8;
        $page = (int) ($this->request->getGet('page') ?? 1);
        $page = $page > 0 ? $page : 1;

        $countBuilder = $this->db->table('recursos r')
            ->join('recursos_fisicos rf', 'r.idrecurso = rf.idrecurso')
            ->join('editoriales e', 'r.ideditorial = e.ideditorial')
            ->join('subcategorias s', 'r.idsubcategoria = s.idsubcategoria')
            ->join('categorias c', 's.idcategoria = c.idcategoria')
            ->join('tiporecursos t', 'r.idtiporecurso = t.idtiporecurso');

        $total = $countBuilder->countAllResults();

        $lastPage = $total > 0 ? (int) ceil($total / $perPage) : 1;
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $offset = ($page - 1) * $perPage;

        $dataBuilder = $this->db->table('recursos r')
            ->select('
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
            ')
            ->join('recursos_fisicos rf', 'r.idrecurso = rf.idrecurso')
            ->join('editoriales e', 'r.ideditorial = e.ideditorial')
            ->join('subcategorias s', 'r.idsubcategoria = s.idsubcategoria')
            ->join('categorias c', 's.idcategoria = c.idcategoria')
            ->join('tiporecursos t', 'r.idtiporecurso = t.idtiporecurso');

        $data['recursos_fisicos'] = $dataBuilder
            ->orderBy('r.idrecurso', 'ASC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        $pager = Services::pager();
        $data['pager_links'] = $pager->makeLinks($page, $perPage, $total);
        $data['total_recursos'] = $total;
        $data['pagina_actual'] = $page;
        $data['per_page'] = $perPage;

        if ($this->request->isAJAX()) {
            return view('recursos_fisicos/listar', $data);
        }

        $data['navbar'] = view('layouts/navbar');
        $data['header'] = view('layouts/header');
        $data['footer'] = view('layouts/footer');

        return view('recursos_fisicos/listar', $data);
    }
}
