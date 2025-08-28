<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('Administrador/dashboard/index');
    }
    public function login()
    {
        return view('Administrador/dashboard/authentication-login');
    }
    public function register()
    {
        return view('Administrador/dashboard/authentication-register');
    }
}