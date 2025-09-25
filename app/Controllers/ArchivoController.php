<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ArchivoController extends BaseController
{
    /**
     * Servir archivo PDF con cabeceras CORS
     */
    public function pdf($ruta = null)
    {
        // Log para debug
        log_message('info', 'ArchivoController::pdf - Ruta recibida: ' . $ruta);
        
        if (!$ruta) {
            log_message('error', 'ArchivoController::pdf - No se proporcionó ruta');
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }

        // Decodificar la ruta
        $rutaDecodificada = urldecode($ruta);
        log_message('info', 'ArchivoController::pdf - Ruta decodificada: ' . $rutaDecodificada);
        
        // Construir la ruta completa del archivo
        $rutaCompleta = FCPATH . $rutaDecodificada;
        log_message('info', 'ArchivoController::pdf - Ruta completa: ' . $rutaCompleta);
        
        // Verificar que el archivo existe
        if (!file_exists($rutaCompleta)) {
            log_message('error', 'ArchivoController::pdf - Archivo no existe: ' . $rutaCompleta);
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }
        
        // Verificar que es un archivo PDF
        $extension = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            return $this->response->setStatusCode(403)->setBody('Tipo de archivo no permitido');
        }
        
        // CORS se maneja en .htaccess
        
        // Configurar cabeceras para PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . basename($rutaDecodificada) . '"');
        $this->response->setHeader('Content-Length', filesize($rutaCompleta));
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        
        // Leer y enviar el archivo
        $contenido = file_get_contents($rutaCompleta);
        
        return $this->response->setBody($contenido);
    }
    
    /**
     * Servir archivo de imagen con cabeceras CORS
     */
    public function imagen($ruta = null)
    {
        if (!$ruta) {
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }

        // Decodificar la ruta
        $rutaDecodificada = urldecode($ruta);
        
        // Construir la ruta completa del archivo
        $rutaCompleta = FCPATH . $rutaDecodificada;
        
        // Verificar que el archivo existe
        if (!file_exists($rutaCompleta)) {
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }
        
        // Obtener el tipo MIME
        $tipoMime = mime_content_type($rutaCompleta);
        
        // CORS se maneja en .htaccess
        
        // Configurar cabeceras para imagen
        $this->response->setHeader('Content-Type', $tipoMime);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . basename($rutaDecodificada) . '"');
        $this->response->setHeader('Content-Length', filesize($rutaCompleta));
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        
        // Leer y enviar el archivo
        $contenido = file_get_contents($rutaCompleta);
        
        return $this->response->setBody($contenido);
    }
}
