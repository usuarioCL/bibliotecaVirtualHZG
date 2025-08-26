<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {   
        $data = ['header' => view('layouts/header'),
                 'footer' => view('layouts/footer')];
        return view('paginaPrincipal', $data);
    }
}
