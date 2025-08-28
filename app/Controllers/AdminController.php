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
    //Vusta para ususarios y roles
    public function usuariosRoles()
{
    $db = \Config\Database::connect();
    $query = $db->query("
        SELECT u.nomuser, u.nivelacceso, p.nombres, p.apellidos, p.email
        FROM usuarios u
        JOIN personas p ON u.idpersona = p.idpersona
    ");
    $data['usuarios'] = $query->getResult();
    return view('Administrador/vistas/UsuariosRoles', $data);
}
}