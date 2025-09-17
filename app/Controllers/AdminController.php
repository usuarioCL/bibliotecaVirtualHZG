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
            'personas' => [
                'filename' => 'plantilla_personas.xlsx',
                'headers' => [
                    'ID',
                    'Cód. Estudiante',
                    'numerodoc',
                    'nombres',
                    'apellidos',
                    'tipodoc',
                    'telefono',
                    'direccion',
                    'email',
                    'genero'
                ],
                'ejemplo' => [
                    '26710742',
                    '00000062892482',
                    '00000062892482',
                    'NICOLL ALEXANDRA',
                    'ABURTO MUÑOZ',
                    'DNI',
                    '999999999',
                    'Av. Principal 123',
                    'nicoll@example.com',
                    'Femenino'
                ]
            ],
            'usuarios' => [
                'filename' => 'plantilla_usuarios.xlsx',
                'headers' => ['nomuser', 'nombres', 'apellidos', 'email', 'nivelacceso', 'telefono', 'direccion'],
                'ejemplo' => ['jperez', 'Juan', 'Pérez', 'juan@example.com', 'estudiante', '123456789', 'Av. Principal 123']
            ],
            'recursos' => [
                'filename' => 'plantilla_recursos.xlsx',
                'headers' => ['titulo', 'subtitulo', 'isbn', 'autor', 'editorial', 'categoria', 'subcategoria', 'tipo_recurso', 'anio_publicacion'],
                'ejemplo' => ['El Quijote', 'Primera parte', '978-84-376-0494-7', 'Miguel de Cervantes', 'Planeta', 'Literatura', 'Clásicos', 'Libro', '1605']
            ],
            'autores' => [
                'filename' => 'plantilla_autores.xlsx',
                'headers' => ['nombre_completo', 'biografia', 'nacionalidad', 'fecha_nacimiento'],
                'ejemplo' => ['Gabriel García Márquez', 'Escritor colombiano, Premio Nobel de Literatura', 'Colombiana', '1927-03-06']
            ],
            'categorias' => [
                'filename' => 'plantilla_categorias.xlsx',
                'headers' => ['nombre_categoria', 'descripcion'],
                'ejemplo' => ['Ciencias', 'Libros relacionados con ciencias exactas y naturales']
            ],
            'editoriales' => [
                'filename' => 'plantilla_editoriales.xlsx',
                'headers' => ['nombre_editorial', 'pais', 'contacto'],
                'ejemplo' => ['Penguin Random House', 'España', 'info@penguinrandomhouse.com']
            ]
        ];

        if (!array_key_exists($tipo, $plantillas)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Tipo de plantilla no encontrado']);
        }

        $plantilla = $plantillas[$tipo];

        // Generar archivo Excel con PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($plantilla['headers'], NULL, 'A1');
        $sheet->fromArray($plantilla['ejemplo'], NULL, 'A2');

        // Configurar headers para descarga
        $filename = $plantilla['filename'];
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Vista previa del archivo Excel
    public function previewExcel()
    {
        // Agregar logs para debug
        log_message('debug', 'Método previewExcel llamado');
        
        $request = service('request');
        if (!$request->getFile('archivo_excel')) {
            log_message('error', 'No se encontró el archivo en la solicitud');
            return $this->response->setJSON(['success' => false, 'message' => 'No se ha enviado ningún archivo']);
        }

        $archivo = $request->getFile('archivo_excel');
        log_message('debug', 'Nombre del archivo: ' . $archivo->getName());

        if (!$archivo->isValid()) {
            log_message('error', 'El archivo no es válido: ' . $archivo->getErrorString());
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no es válido: ' . $archivo->getErrorString()]);
        }

        try {
            // Verificar que el archivo sea realmente un Excel
            $mimeType = $archivo->getMimeType();
            log_message('debug', 'MIME Type: ' . $mimeType);
            
            if (!in_array($mimeType, [
                'application/vnd.ms-excel', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/octet-stream' // Algunos navegadores pueden enviarlo así
            ])) {
                log_message('error', 'Tipo de archivo no válido: ' . $mimeType);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El archivo debe ser un Excel (.xlsx, .xls)'
                ]);
            }

            // Cargar PhpSpreadsheet con manejo de errores mejorado
            try {
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($archivo->getTempName());
                log_message('debug', 'Tipo de archivo identificado: ' . $inputFileType);
                
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($archivo->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true);
            } catch (\Exception $e) {
                log_message('error', 'Error al procesar el Excel: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al leer el archivo Excel: ' . $e->getMessage()
                ]);
            }

            // Limitar a primeras 10 filas para la vista previa
            $preview_data = [];
            $headers = [];
            
            // Si no hay filas
            if (empty($rows)) {
                log_message('warning', 'El Excel está vacío');
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'El archivo Excel está vacío'
                ]);
            }
            
            log_message('debug', 'Total de filas encontradas: ' . count($rows));
            
            // Obtener encabezados de la primera fila
            $firstRow = reset($rows);
            $headers = array_values($firstRow);
            
            // Normalizar encabezados para la vista previa
            $headersNormalizados = array_map(function($header) {
                return strtolower(trim($header));
            }, $headers);
            
            log_message('debug', 'Encabezados originales: ' . json_encode($headers));
            log_message('debug', 'Encabezados normalizados: ' . json_encode($headersNormalizados));
            
            // Crear vista previa con el resto de filas
            $rowCount = 0;
            foreach ($rows as $index => $row) {
                // Saltar la primera fila (encabezados)
                if ($index === array_key_first($rows)) {
                    continue;
                }
                
                // Convertir a array indexado para facilitar el manejo
                $row_array = array_values($row);
                
                // Convertir a objeto con propiedades según encabezados
                $row_data = [];
                foreach ($row_array as $key => $value) {
                    $header_key = isset($headers[$key]) ? $headers[$key] : "Columna " . ($key + 1);
                    // Eliminar espacios en blanco y asegurar valor string
                    $row_data[$header_key] = $value !== null ? (string)$value : '';
                }
                
                $preview_data[] = $row_data;
                $rowCount++;
                
                // Limitar a 10 filas
                if ($rowCount >= 10) {
                    break;
                }
            }
            
            log_message('debug', 'Vista previa generada con ' . count($preview_data) . ' filas');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $preview_data,
                'totalRows' => count($rows) - 1, // Excluir fila de encabezados
                'previewRows' => count($preview_data)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Excepción general: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar el archivo para vista previa: ' . $e->getMessage()
            ]);
        }
    }

    // Procesar importación desde Excel
    public function procesarImportacionExcel()
    {
        $request = service('request');
        if (!$request->getFile('archivo_excel')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se ha enviado ningún archivo']);
        }

        $archivo = $request->getFile('archivo_excel');
        $tipo_entidad = $request->getPost('tipo_entidad');

        if (!$archivo->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no es válido']);
        }

        try {
            // Cargar PhpSpreadsheet
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($archivo->getTempName());
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($archivo->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $registros_exitosos = 0;
            $registros_error = 0;
            $registros_duplicados = 0;
            $errores = [];
            $encabezados = [];
            $primera_fila_procesada = false;

            $this->db->transStart();

            foreach ($rows as $numero_linea => $fila) {
                // Saltar filas vacías
                if (empty(array_filter($fila, function($v){ return $v !== null && trim($v) !== ''; }))) continue;

                // Convertir fila a array indexado
                $fila_array = array_values($fila);

                // La primera fila no vacía son los encabezados
                if (!$primera_fila_procesada) {
                    $encabezados = $fila_array;
                    $primera_fila_procesada = true;
                    log_message('debug', 'Encabezados extraídos: ' . json_encode($encabezados));
                    continue;
                }

                try {
                    $resultado = $this->procesarFilaSegunTipo($tipo_entidad, $fila_array, $encabezados);
                    if ($resultado['success']) {
                        if (!empty($resultado['duplicado'])) {
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
                // Obtener detalles del error de la base de datos
                $error_info = $this->db->error();
                $error_message = 'Error en la transacción de base de datos';
                
                if (!empty($error_info['message'])) {
                    $error_message .= ': ' . $error_info['message'];
                    log_message('error', 'Error en importación Excel: ' . $error_info['message']);
                }
                
                log_message('error', 'Transacción fallida. Registros procesados antes del error: ' . ($registros_exitosos + $registros_error + $registros_duplicados));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $error_message,
                    'registros_procesados_antes_error' => $registros_exitosos + $registros_error + $registros_duplicados
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
            case 'personas':
                return $this->procesarFilaPersona($fila, $encabezados);
            default:
                return ['success' => false, 'mensaje' => 'Tipo de entidad no válido'];
        }
    }
    // Importar personas desde Excel
    private function procesarFilaPersona($fila, $encabezados)
    {
        // Normalizar encabezados (convertir a minúsculas y limpiar espacios)
        $encabezadosNormalizados = array_map(function($header) {
            return strtolower(trim($header ?? ''));
        }, $encabezados);
        
        // Limpiar datos de la fila para evitar valores nulos problemáticos
        $filaLimpia = array_map(function($valor) {
            return $valor === null ? '' : trim($valor);
        }, $fila);
        
        $datos = array_combine($encabezadosNormalizados, $filaLimpia);
        
        // Log para debugging
        log_message('debug', 'Encabezados normalizados: ' . json_encode($encabezadosNormalizados));
        log_message('debug', 'Datos combinados: ' . json_encode($datos));

        // Mapear posibles variaciones de nombres de campos
        $camposMapeados = [
            'apellidos' => $datos['apellidos'] ?? $datos['apellido'] ?? null,
            'nombres' => $datos['nombres'] ?? $datos['nombre'] ?? null,
            'tipodoc' => $datos['tipodoc'] ?? $datos['tipo_doc'] ?? $datos['tipo documento'] ?? null,
            'numerodoc' => $datos['numerodoc'] ?? $datos['numero_doc'] ?? $datos['documento'] ?? $datos['número documento'] ?? null
        ];

        // Validar campos requeridos
        if (empty($camposMapeados['apellidos']) || empty($camposMapeados['nombres']) || 
            empty($camposMapeados['tipodoc']) || empty($camposMapeados['numerodoc'])) {
            
            $camposFaltantes = [];
            if (empty($camposMapeados['apellidos'])) $camposFaltantes[] = 'apellidos';
            if (empty($camposMapeados['nombres'])) $camposFaltantes[] = 'nombres';
            if (empty($camposMapeados['tipodoc'])) $camposFaltantes[] = 'tipodoc';
            if (empty($camposMapeados['numerodoc'])) $camposFaltantes[] = 'numerodoc';
            
            return [
                'success' => false, 
                'mensaje' => 'Faltan campos obligatorios: ' . implode(', ', $camposFaltantes) . 
                           '. Encabezados encontrados: ' . implode(', ', $encabezadosNormalizados)
            ];
        }

        // Validar tipodoc
        $tiposDocValidos = ['DNI', 'CE', 'Pasaporte'];
        $tipodocOriginal = $camposMapeados['tipodoc'];
        
        // Normalizar tipodoc - manejar casos especiales para siglas
        $tipodocNormalizado = strtoupper(trim($tipodocOriginal));
        if ($tipodocNormalizado === 'PASAPORTE') {
            $tipodocNormalizado = 'Pasaporte';
        }
        
        if (!in_array($tipodocNormalizado, $tiposDocValidos)) {
            return [
                'success' => false, 
                'mensaje' => "Tipo de documento '{$tipodocOriginal}' no válido. Debe ser: " . implode(', ', $tiposDocValidos)
            ];
        }
        $camposMapeados['tipodoc'] = $tipodocNormalizado;

        // Verificar si la persona ya existe por documento
        $existe = $this->db->query("SELECT idpersona FROM personas WHERE numerodoc = ?", [$camposMapeados['numerodoc']])->getRow();
        if ($existe) {
            return ['success' => true, 'duplicado' => true, 'mensaje' => 'Persona ya existe'];
        }

        // Campos opcionales
        $telefono = $datos['telefono'] ?? $datos['teléfono'] ?? null;
        $direccion = $datos['direccion'] ?? $datos['dirección'] ?? null;
        $email = $datos['email'] ?? $datos['correo'] ?? null;
        $genero = $datos['genero'] ?? $datos['género'] ?? $datos['sexo'] ?? null;

        // Validar género si se proporciona
        if (!empty($genero)) {
            $generosValidos = ['Masculino', 'Femenino', 'Otro'];
            $generoNormalizado = ucfirst(strtolower($genero));
            if (!in_array($generoNormalizado, $generosValidos)) {
                return [
                    'success' => false, 
                    'mensaje' => "Género '{$genero}' no válido. Debe ser: " . implode(', ', $generosValidos)
                ];
            }
            $genero = $generoNormalizado;
        }

        // Verificar email duplicado si se proporciona
        if (!empty($email)) {
            $emailExiste = $this->db->query("SELECT idpersona FROM personas WHERE email = ?", [$email])->getRow();
            if ($emailExiste) {
                return ['success' => false, 'mensaje' => "El email '{$email}' ya está registrado"];
            }
        }

        try {
            $this->db->query(
                "INSERT INTO personas (apellidos, nombres, tipodoc, numerodoc, telefono, direccion, email, genero) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $camposMapeados['apellidos'],
                    $camposMapeados['nombres'],
                    $camposMapeados['tipodoc'],
                    $camposMapeados['numerodoc'],
                    $telefono,
                    $direccion,
                    $email,
                    $genero
                ]
            );
            return ['success' => true, 'duplicado' => false];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error al insertar persona: ' . $e->getMessage()];
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