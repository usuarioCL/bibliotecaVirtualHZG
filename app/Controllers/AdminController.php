<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('Administrador/flexy-bootstrap-lite-1.0.0/index');
    }
}