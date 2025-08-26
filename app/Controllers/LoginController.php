<?php

namespace App\Controllers;
use App\Models\personaModel;
use App\Models\usuarioModel;

class LoginController extends BaseController
{
    public function loginInterfaz()
    {
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');


        return view('login_user/login', $datos);
    }

    public function login()
    {
       $usuarioModel = new usuarioModel();
       $nomuser = $this->request->getPost('nomuser');
       $passuser = $this->request->getPost('passuser');
       $nivelAcceso = $this->request->getPost('nivelAcceso');

       $usuario = $usuarioModel->where('nomuser', $nomuser)->first();


       //Verificacion de contraseña por Hash
       /*if ($usuario && password_verify($pass, $usuario['passuser'])) {
           session()->set('usuario', $usuario);
           return redirect()->to('/');
       }*/

        
         //Verificacion de contraseña sin Hash
        if ($usuario && $passuser == $usuario['passuser']) {
            session()->set('usuario', $usuario);
            return redirect()->to('/');
       }
       return redirect()->back()->with('error', 'Credenciales inválidas');
    }

    public function Registro()
    {
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');

        return view('login_user/registrar', $datos);
    }
    
}