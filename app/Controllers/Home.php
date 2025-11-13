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
        //Obtener recursos agregados recientemente para el carrusel (últimos 12)
        $recursosRecientes = $recursoModel->obtenerRecursosRecientes(12);
        //Obtener recursos populares para el carrusel (últimos 12 ordenados por año)
        $recursosPopulares = $recursoModel->obtenerLibrosPopulares(12);

        $data = ['header' => view('layouts/header'),
                 'footer' => view('layouts/footer'),
                 'navbar' => view('layouts/navbar'),
                 'niveles' => $niveles,
                 'categorias' => $categorias,
                 'recursosDestacados' => $recursosDestacados,
                 'recursosRecientes' => $recursosRecientes,
                 'recursosPopulares' => $recursosPopulares];
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
