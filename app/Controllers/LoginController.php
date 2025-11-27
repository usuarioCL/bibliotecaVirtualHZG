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

        // Capturar datos del formulario y limpiar espacios en blanco
        $nomuser   = trim($this->request->getPost('nomuser'));
        $passuser  = trim($this->request->getPost('passuser'));

        // LOG: Intento de login
        log_message('info', '=== INTENTO DE LOGIN ===');
        log_message('info', 'Usuario ingresado (limpio): ' . $nomuser);
        log_message('info', 'Contraseña ingresada (limpia): ' . $passuser);

        // Buscar usuario por nombre
        $usuario = $usuarioModel->where('nomuser', $nomuser)->first();

        if ($usuario) {
            // LOG: Usuario encontrado
            log_message('info', 'Usuario encontrado en BD: ' . $usuario['nomuser']);
            log_message('info', 'Hash almacenado en BD: ' . $usuario['passuser']);
            log_message('info', 'Nivel de acceso: ' . $usuario['nivelacceso']);
            
            // Verificar contraseña - primero intentar hash, luego texto plano
            $passwordHashMatch = password_verify($passuser, $usuario['passuser']);
            $passwordPlainMatch = ($passuser === $usuario['passuser']);
            $passwordMatch = $passwordHashMatch || $passwordPlainMatch;
            
            // LOG: Resultado de verificación
            log_message('info', 'Verificación con hash: ' . ($passwordHashMatch ? 'ÉXITO' : 'FALLÓ'));
            log_message('info', 'Verificación texto plano: ' . ($passwordPlainMatch ? 'ÉXITO' : 'FALLÓ'));
            log_message('info', 'Resultado final: ' . ($passwordMatch ? 'ACCESO PERMITIDO' : 'ACCESO DENEGADO'));
            
            if ($passwordMatch) {
                // LOG: Login exitoso
                log_message('info', '✅ LOGIN EXITOSO para usuario: ' . $usuario['nomuser']);

                // Guardamos en sesión
                session()->set([
                    'idusuario'  => $usuario['idusuario'],
                    'usuario'    => $usuario['nomuser'],
                    'nomuser'    => $usuario['nomuser'],
                    'nivel'      => $usuario['nivelacceso'],
                    'nivelacceso' => $usuario['nivelacceso'],
                    'logged_in'  => true
                ]);
                
                log_message('info', 'Sesión creada con ID: ' . $usuario['idusuario']);

            // Redirigir según el nivel de acceso
            if ($usuario['nivelacceso'] == 'admin') {
                // Administrador va al dashboard
                return redirect()->to('/admin');
            } else {
                // Otros usuarios van a la página principal
                return redirect()->to('/');
            }
            } else {
                log_message('warning', '❌ LOGIN FALLIDO: Contraseña incorrecta para usuario ' . $nomuser);
            }
        } else {
            log_message('warning', '❌ LOGIN FALLIDO: Usuario no encontrado: ' . $nomuser);
        }

        // Si no encontró usuario o contraseña incorrecta
        log_message('info', '=== FIN INTENTO DE LOGIN FALLIDO ===');
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