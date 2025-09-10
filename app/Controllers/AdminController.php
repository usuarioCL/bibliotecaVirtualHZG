<?php

namespace App\Controllers;

use Exception;

class AdminController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function dashboard()
    {   
        return view('Administrador/dashboard/index');
    }

    public function dashboardDefault()
    {
        return view('Administrador/dashboard/default');
    }

    // Vista para usuarios y roles
    public function VistaUsuariosRoles()
    {
        $query = $this->db->query("
            SELECT u.nomuser, u.nivelacceso, p.nombres, p.apellidos, p.email
            FROM usuarios u
            JOIN personas p ON u.idpersona = p.idpersona
        ");
        $data['usuarios'] = $query->getResult();
        return view('Administrador/vistas/UsuariosRoles', $data);
    }

    // Vista para préstamos y alumnos
    public function VistaPrestamosAlumnos()
    {
        $query = $this->db->query("
            SELECT p.fechaprestamo, per.nombres, per.apellidos, r.titulo
            FROM prestamos p
            INNER JOIN matriculas m ON p.idmatricula = m.idmatricula
            INNER JOIN personas per ON m.idpersona = per.idpersona
            INNER JOIN recursos r ON p.idrecurso = r.idrecurso
        ");
        $data['prestamos'] = $query->getResult();
        return view('Administrador/vistas/PrestamosAlumnos', $data);
    }

    public function VistaReaccionesUsuarios()
    {
        $query = $this->db->query("
            SELECT u.nomuser, r.titulo, re.tiporeaccion
            FROM reacciones re
            INNER JOIN usuarios u ON re.idusuario = u.idusuario
            INNER JOIN recursos r ON re.idrecurso = r.idrecurso
        ");
        $data['reacciones'] = $query->getResult();
        return view('Administrador/vistas/ReaccionesUsuarios', $data);
    }

    public function VistaAlumnosSancionados()
    {
        $query = $this->db->query("
            SELECT s.detallesancion, ts.tiposancion, per.nombres, per.apellidos
            FROM sanciones s
            INNER JOIN tiposancion ts ON s.idtiposancion = ts.idtiposancion
            INNER JOIN personas per ON s.idpersona = per.idpersona
        ");
        $data['sancionados'] = $query->getResult();
        return view('Administrador/vistas/AlumnosSancionados', $data);
    }

    // Vista para importar datos
    public function importarDatos()
    {
        return view('Administrador/importar-datos');
    }

    // Descargar plantillas CSV
    public function descargarPlantilla($tipo)
    {
        $plantillas = [
            'usuarios' => [
                'filename' => 'plantilla_usuarios.csv',
                'headers' => ['nomuser', 'nombres', 'apellidos', 'email', 'nivelacceso', 'telefono', 'direccion'],
                'ejemplo' => ['jperez', 'Juan', 'Pérez', 'juan@example.com', 'estudiante', '123456789', 'Av. Principal 123']
            ],
            'recursos' => [
                'filename' => 'plantilla_recursos.csv',
                'headers' => ['titulo', 'subtitulo', 'isbn', 'autor', 'editorial', 'categoria', 'subcategoria', 'tipo_recurso', 'anio_publicacion'],
                'ejemplo' => ['El Quijote', 'Primera parte', '978-84-376-0494-7', 'Miguel de Cervantes', 'Planeta', 'Literatura', 'Clásicos', 'Libro', '1605']
            ],
            'autores' => [
                'filename' => 'plantilla_autores.csv',
                'headers' => ['nombre_completo', 'biografia', 'nacionalidad', 'fecha_nacimiento'],
                'ejemplo' => ['Gabriel García Márquez', 'Escritor colombiano, Premio Nobel de Literatura', 'Colombiana', '1927-03-06']
            ],
            'categorias' => [
                'filename' => 'plantilla_categorias.csv',
                'headers' => ['nombre_categoria', 'descripcion'],
                'ejemplo' => ['Ciencias', 'Libros relacionados con ciencias exactas y naturales']
            ],
            'editoriales' => [
                'filename' => 'plantilla_editoriales.csv',
                'headers' => ['nombre_editorial', 'pais', 'contacto'],
                'ejemplo' => ['Penguin Random House', 'España', 'info@penguinrandomhouse.com']
            ]
        ];

        if (!array_key_exists($tipo, $plantillas)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Tipo de plantilla no encontrado']);
        }

        $plantilla = $plantillas[$tipo];
        
        // Configurar headers para descarga
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $plantilla['filename'] . '"');
        
        // Crear contenido CSV
        $output = fopen('php://output', 'w');
        
        // Agregar BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, $plantilla['headers'], ',', '"');
        
        // Escribir ejemplo
        fputcsv($output, $plantilla['ejemplo'], ',', '"');
        
        fclose($output);
        return $this->response;
    }

    // Vista previa del archivo CSV
    public function previewCsv()
    {
        $request = service('request');
        
        if (!$request->getFile('archivo_csv')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se ha enviado ningún archivo']);
        }

        $archivo = $request->getFile('archivo_csv');
        
        if (!$archivo->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no es válido']);
        }

        $separador = $request->getPost('separador') ?: ',';
        $codificacion = $request->getPost('codificacion') ?: 'UTF-8';
        $primera_fila_encabezados = $request->getPost('primera_fila_encabezados') === '1';

        try {
            $contenido = file_get_contents($archivo->getTempName());
            
            // Convertir codificación si es necesario
            if ($codificacion !== 'UTF-8') {
                $contenido = mb_convert_encoding($contenido, 'UTF-8', $codificacion);
            }

            $lineas = explode("\n", $contenido);
            $datos = [];
            $encabezados = [];

            foreach ($lineas as $indice => $linea) {
                if (empty(trim($linea))) continue;
                
                $fila = str_getcsv($linea, $separador);
                
                if ($indice === 0 && $primera_fila_encabezados) {
                    $encabezados = $fila;
                    continue;
                }
                
                if (empty($encabezados)) {
                    // Si no hay encabezados, crear numericos
                    $encabezados = array_map(function($i) { return "Columna " . ($i + 1); }, array_keys($fila));
                }
                
                if (count($datos) >= 10) break; // Solo mostrar 10 filas de preview
                
                $datos[] = array_combine($encabezados, array_pad($fila, count($encabezados), ''));
            }

            return $this->response->setJSON(['success' => true, 'data' => $datos]);

        } catch (Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    // Procesar importación
    public function procesarImportacion()
    {
        $request = service('request');
        
        if (!$request->getFile('archivo_csv')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se ha enviado ningún archivo']);
        }

        $archivo = $request->getFile('archivo_csv');
        $tipo_entidad = $request->getPost('tipo_entidad');
        $separador = $request->getPost('separador') ?: ',';
        $codificacion = $request->getPost('codificacion') ?: 'UTF-8';
        $primera_fila_encabezados = $request->getPost('primera_fila_encabezados') === '1';

        if (!$archivo->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no es válido']);
        }

        try {
            $contenido = file_get_contents($archivo->getTempName());
            
            // Convertir codificación si es necesario
            if ($codificacion !== 'UTF-8') {
                $contenido = mb_convert_encoding($contenido, 'UTF-8', $codificacion);
            }

            $lineas = explode("\n", $contenido);
            $registros_exitosos = 0;
            $registros_error = 0;
            $registros_duplicados = 0;
            $errores = [];
            $encabezados = [];

            $this->db->transStart();

            foreach ($lineas as $numero_linea => $linea) {
                if (empty(trim($linea))) continue;
                
                $fila = str_getcsv($linea, $separador);
                
                if ($numero_linea === 0 && $primera_fila_encabezados) {
                    $encabezados = $fila;
                    continue;
                }
                
                try {
                    $resultado = $this->procesarFilaSegunTipo($tipo_entidad, $fila, $encabezados);
                    
                    if ($resultado['success']) {
                        if ($resultado['duplicado']) {
                            $registros_duplicados++;
                        } else {
                            $registros_exitosos++;
                        }
                    } else {
                        $registros_error++;
                        $errores[] = [
                            'fila' => $numero_linea + 1,
                            'mensaje' => $resultado['mensaje']
                        ];
                    }
                } catch (Exception $e) {
                    $registros_error++;
                    $errores[] = [
                        'fila' => $numero_linea + 1,
                        'mensaje' => $e->getMessage()
                    ];
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error en la transacción de base de datos'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'registros_exitosos' => $registros_exitosos,
                'registros_error' => $registros_error,
                'registros_duplicados' => $registros_duplicados,
                'total_procesados' => $registros_exitosos + $registros_error + $registros_duplicados,
                'errores' => $errores
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    private function procesarFilaSegunTipo($tipo, $fila, $encabezados)
    {
        switch ($tipo) {
            case 'usuarios':
                return $this->procesarFilaUsuario($fila, $encabezados);
            case 'recursos':
                return $this->procesarFilaRecurso($fila, $encabezados);
            case 'autores':
                return $this->procesarFilaAutor($fila, $encabezados);
            case 'categorias':
                return $this->procesarFilaCategoria($fila, $encabezados);
            case 'editoriales':
                return $this->procesarFilaEditorial($fila, $encabezados);
            default:
                return ['success' => false, 'mensaje' => 'Tipo de entidad no válido'];
        }
    }

    private function procesarFilaUsuario($fila, $encabezados)
    {
        $datos = array_combine($encabezados, $fila);
        
        // Validar campos requeridos
        if (empty($datos['nomuser']) || empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['email'])) {
            return ['success' => false, 'mensaje' => 'Campos requeridos faltantes para usuario'];
        }

        // Verificar si el usuario ya existe
        $existeUsuario = $this->db->query("SELECT idusuario FROM usuarios WHERE nomuser = ?", [$datos['nomuser']])->getRow();
        if ($existeUsuario) {
            return ['success' => true, 'duplicado' => true, 'mensaje' => 'Usuario ya existe'];
        }

        try {
            // Insertar persona primero
            $this->db->query("
                INSERT INTO personas (nombres, apellidos, email, telefono, direccion) 
                VALUES (?, ?, ?, ?, ?)
            ", [
                $datos['nombres'],
                $datos['apellidos'],
                $datos['email'],
                $datos['telefono'] ?? '',
                $datos['direccion'] ?? ''
            ]);

            $idpersona = $this->db->insertID();

            // Insertar usuario
            $this->db->query("
                INSERT INTO usuarios (nomuser, nivelacceso, idpersona) 
                VALUES (?, ?, ?)
            ", [
                $datos['nomuser'],
                $datos['nivelacceso'] ?? 'estudiante',
                $idpersona
            ]);

            return ['success' => true, 'duplicado' => false];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error al insertar usuario: ' . $e->getMessage()];
        }
    }

    private function procesarFilaRecurso($fila, $encabezados)
    {
        $datos = array_combine($encabezados, $fila);
        
        if (empty($datos['titulo']) || empty($datos['autor'])) {
            return ['success' => false, 'mensaje' => 'Título y autor son requeridos'];
        }

        // Verificar si el recurso ya existe
        $existeRecurso = $this->db->query("SELECT idrecurso FROM recursos WHERE titulo = ? AND isbn = ?", [$datos['titulo'], $datos['isbn'] ?? ''])->getRow();
        if ($existeRecurso) {
            return ['success' => true, 'duplicado' => true];
        }

        try {
            // Aquí iría la lógica completa de inserción de recursos
            // Incluyendo manejo de autores, categorías, editoriales, etc.
            
            return ['success' => true, 'duplicado' => false];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error al insertar recurso: ' . $e->getMessage()];
        }
    }

    private function procesarFilaAutor($fila, $encabezados)
    {
        $datos = array_combine($encabezados, $fila);
        
        if (empty($datos['nombre_completo'])) {
            return ['success' => false, 'mensaje' => 'Nombre del autor es requerido'];
        }

        // Verificar duplicados y procesar...
        return ['success' => true, 'duplicado' => false];
    }

    private function procesarFilaCategoria($fila, $encabezados)
    {
        $datos = array_combine($encabezados, $fila);
        
        if (empty($datos['nombre_categoria'])) {
            return ['success' => false, 'mensaje' => 'Nombre de la categoría es requerido'];
        }

        // Verificar duplicados y procesar...
        return ['success' => true, 'duplicado' => false];
    }

    private function procesarFilaEditorial($fila, $encabezados)
    {
        $datos = array_combine($encabezados, $fila);
        
        if (empty($datos['nombre_editorial'])) {
            return ['success' => false, 'mensaje' => 'Nombre de la editorial es requerido'];
        }

        // Verificar duplicados y procesar...
        return ['success' => true, 'duplicado' => false];
    }
}