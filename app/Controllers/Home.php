<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\RecursoModel;
use CodeIgniter\Controller;

class Home extends Controller
{
    public function index(): string
    {   

        $categoriaModel = new CategoriaModel();
        $recursoModel = new RecursoModel();

        //Mostrar niveles
        $niveles = ['Inicial','Primaria', 'Secundaria'];
        //Obtener categorias
        $categorias = $categoriaModel->findAll();
        //Obtener recursos destacados (los más recientes por ahora)
        $recursosDestacados = $recursoModel->obtenerRecursosDestacados(8);
        //Obtener todos los recursos disponibles
        $librosPopulares = $recursoModel->obtenerTodosLosRecursos();

        $data = ['header' => view('layouts/header'),
                 'footer' => view('layouts/footer'),
                 'navbar' => view('layouts/navbar'),
                 'niveles' => $niveles,
                 'categorias' => $categorias,
                 'recursosDestacados' => $recursosDestacados,
                 'librosPopulares' => $librosPopulares];
        return view('paginaPrincipal', $data);
    }


    public function sobrePlataforma(): string
    {
        $data = [
            'header' => view('layouts/header'),
            'footer' => view('layouts/footer'),
            'navbar' => view('layouts/navbar')
        ];
        
        return view('sobrePlataforma', $data);
    }
}
