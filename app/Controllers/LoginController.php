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

    public function login()
    {
        $usuarioModel = new usuarioModel();

        // Capturar datos del formulario
        $nomuser   = $this->request->getPost('nomuser');
        $passuser  = $this->request->getPost('passuser');

        // Buscar usuario por nombre
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();

        if ($usuario) {
            // Si las contraseñas coinciden (aquí sin hash)
            if ($passuser === $usuario['passuser']) {

                // Guardamos en sesión
                session()->set([
                    'usuario'    => $usuario['nomuser'],
                    'nivel'      => $usuario['nivelacceso'],
                    'logged_in'  => true
                ]);

            // Redirigir todos los roles a la página principal
            return redirect()->to('/');
            }
        }

        // Si no encontró usuario o contraseña incorrecta
        return redirect()->back()->with('error', 'Nombre de usuario o contraseña incorrectos');
    }

    public function logout()
    {
        // Eliminar datos de sesión
        session()->destroy();

        // Redirigir a la página de inicio
        return redirect()->to('/');
    }
}