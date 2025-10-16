<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Verificar si el usuario tiene nivel de acceso de administrador
        $nivelAcceso = session()->get('nivelacceso');
        
        // Obtener la URI actual para verificar si es del sistema de préstamos
        $uri = $request->getUri()->getPath();
        $rutasPrestamos = ['prestamos', 'solicitudes', 'devoluciones', 'historial-prestamos'];
        
        // Para rutas del sistema de préstamos, permitir acceso a admin y docente
        $esRutaPrestamos = false;
        foreach ($rutasPrestamos as $ruta) {
            if (strpos($uri, $ruta) !== false) {
                $esRutaPrestamos = true;
                break;
            }
        }
        
        if ($esRutaPrestamos) {
            // Para rutas de préstamos, permitir admin y docente
            if (!in_array($nivelAcceso, ['admin', 'docente'])) {
                return redirect()->to('/')->with('error', 'No tienes permisos para acceder al sistema de préstamos');
            }
        } else {
            // Para otras rutas administrativas, solo admin
            if ($nivelAcceso !== 'admin') {
                // Si no es administrador, redirigir a la página principal con un mensaje de error
                return redirect()->to('/')->with('error', 'No tienes permisos para acceder a esta sección');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita procesar nada después
    }
}

