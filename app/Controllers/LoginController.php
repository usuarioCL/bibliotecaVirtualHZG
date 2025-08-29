<?php

namespace App\Controllers;
use App\Models\personaModel;
use App\Models\usuarioModel;

class LoginController extends BaseController
{
    public function loginForm()
    {
        $datos['header'] = view('layouts/header');
        $datos['footer'] = view('layouts/footer');
        $datos['navbar'] = view('layouts/navbar');


        return view('login_user/login', $datos);
    }
    //Verificacion de contraseña por Hash(Primera version)
    /*if ($usuario && password_verify($pass, $usuario['passuser'])) {
        session()->set('usuario', $usuario);
        return redirect()->to('/');
    }*/

    public function login()
    {
        $usuarioModel = new usuarioModel();

        // Capturar datos del formulario
        $nomuser   = $this->request->getPost('nomuser');
        $passuser  = $this->request->getPost('passuser');

        // Buscar usuario por nombre
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();

        if ($usuario) {
            //if(password_verify($passuser, $usuario['passuser']))
            // Si las contraseñas coinciden (aquí sin hash)
            if ($passuser === $usuario['passuser']) {

                // Guardamos en sesión
                session()->set([
                    'usuario'    => $usuario['nomuser'],
                    'nivel'      => $usuario['nivelacceso'],
                    'logged_in'  => true
                ]);

                // Redirigir según el rol
                switch ($usuario['nivelacceso']) {
                    case 'Admin':
                        return redirect()->to('/admin');
                    case 'estudiante':
                        return redirect()->to('/');
                    case 'Docente':
                        return redirect()->to('/docente');
                    default:
                        return redirect()->to('/login');
                }
            }
        }

        // Si no encontró usuario o contraseña incorrecta
        return redirect()->back()->with('error', 'Nombre de usuario o contraseña incorrectos');
    }


    
    
}