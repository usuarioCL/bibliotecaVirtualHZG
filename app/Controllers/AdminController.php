<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('nivelAcceso/Administradores/dashboard', $datos);
    }
}