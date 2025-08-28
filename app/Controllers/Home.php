<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class Home extends Controller
{
    public function index(): string
    {   

        $categoriaModel = new CategoriaModel();

        //Mostrar niveles
        $niveles = ['Inicial','Primaria', 'Secundaria'];
        //Obtener categorias
        $categorias = $categoriaModel->findAll();

        $data = ['header' => view('layouts/header'),
                 'footer' => view('layouts/footer'),
                 'niveles' => $niveles,
                 'categorias' => $categorias];
        return view('paginaPrincipal', $data);
    }
}
