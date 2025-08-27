<?php

namespace App\Controllers;

class RegistroController extends BaseController
{
    public function RegistroForm()
    {
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('login_user/registrar', $datos);
    }
}