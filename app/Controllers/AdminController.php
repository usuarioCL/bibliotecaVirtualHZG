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

    public function VistaRecursosPopulares()
    {
        $query = $this->db->query("
            SELECT r.titulo,
                   GROUP_CONCAT(DISTINCT a.nomautor SEPARATOR ', ') AS autor,
                   e.editorial,
                   c.categoria,
                   COUNT(p.idprestamo) AS veces_prestado
            FROM recursos r
            LEFT JOIN prestamos p ON r.idrecurso = p.idrecurso
            LEFT JOIN detautores da ON da.idrecurso = r.idrecurso
            LEFT JOIN autores a ON a.idautor = da.idautor
            LEFT JOIN editoriales e ON e.ideditorial = r.ideditorial
            LEFT JOIN subcategorias sc ON sc.idsubcategoria = r.idsubcategoria
            LEFT JOIN categorias c ON c.idcategoria = sc.idcategoria
            GROUP BY r.idrecurso
            ORDER BY veces_prestado DESC
            LIMIT 10
        ");
        $data['recursosPopulares'] = $query->getResult();
        return view('Administrador/vistas/RecursosPopulares', $data);
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
        log_message('debug', 'Iniciando descarga de plantilla para tipo: ' . $tipo);
        try {
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

            // Limpiar cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }
            log_message('debug', 'Buffer de salida limpiado');

            // Configurar headers para descarga antes de cualquier salida
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $plantilla['filename'] . '"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');

            log_message('debug', 'Iniciando generación de archivo Excel');
            // Generar archivo Excel con PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Dar formato a las columnas
            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Agregar encabezados con estilo
            $sheet->fromArray($plantilla['headers'], NULL, 'A1');
            $headerStyle = $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1');
            $headerStyle->getFont()->setBold(true);
            
            // Agregar datos de ejemplo
            $sheet->fromArray([$plantilla['ejemplo']], NULL, 'A2');

            // Guardar primero en un archivo temporal
            $tempFile = WRITEPATH . 'temp/' . uniqid() . '_' . $plantilla['filename'];
            if (!is_dir(WRITEPATH . 'temp')) {
                mkdir(WRITEPATH . 'temp', 0777, true);
            }
            
            log_message('debug', 'Guardando archivo temporal en: ' . $tempFile);
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($tempFile);
            
            log_message('debug', 'Archivo temporal guardado correctamente');
            
            // Leer y enviar el archivo
            if (file_exists($tempFile)) {
                $content = file_get_contents($tempFile);
                unlink($tempFile); // Eliminar el archivo temporal
                
                // Enviar el contenido al navegador
                echo $content;
                exit();
            } else {
                throw new \Exception('No se pudo crear el archivo temporal');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al generar plantilla Excel: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Error al generar la plantilla',
                'message' => $e->getMessage()
            ]);
        }
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

    /**
     * Vista principal de gestión de respaldos
     */
    public function backup()
    {
        $data = [
            'title' => 'Gestión de Respaldos',
            'backups' => $this->getDatosPruebaBackups(),
            'estadisticas' => [
                'total_backups' => 8,
                'espacio_utilizado' => '2.3 GB',
                'ultimo_backup' => '2024-10-07 08:30:00',
                'backups_automaticos' => 5
            ]
        ];

        return view('Administrador/backup', $data);
    }

    /**
     * Crear nuevo backup
     */
    public function crearBackup()
    {
        try {
            // TODO: Implementar creación real de backup
            $nombreBackup = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Simular tiempo de procesamiento
            sleep(2);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Backup creado exitosamente',
                'backup' => $nombreBackup
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Restaurar backup
     */
    public function restaurarBackup()
    {
        try {
            $backupFile = $this->request->getPost('backup_file');
            
            if (!$backupFile) {
                throw new Exception('Archivo de backup no especificado');
            }

            // TODO: Implementar restauración real
            sleep(3);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Base de datos restaurada exitosamente desde: ' . $backupFile
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al restaurar backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Descargar backup
     */
    public function descargarBackup($nombreArchivo)
    {
        try {
            // TODO: Implementar descarga real del archivo
            $rutaArchivo = WRITEPATH . 'backups/' . $nombreArchivo;
            
            if (!file_exists($rutaArchivo)) {
                throw new Exception('Archivo de backup no encontrado');
            }

            return $this->response->download($rutaArchivo, null);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar backup: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar backup
     */
    public function eliminarBackup($nombreArchivo)
    {
        try {
            // TODO: Implementar eliminación real del archivo
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Backup eliminado exitosamente'
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Datos de prueba para backups
     */
    private function getDatosPruebaBackups()
    {
        return [
            [
                'id' => 1,
                'nombre' => 'backup_2024-10-07_08-30-00.sql',
                'fecha_creacion' => '2024-10-07 08:30:00',
                'tamaño' => '342.5 MB',
                'tipo' => 'Automático',
                'estado' => 'Completado',
                'tablas' => 15,
                'registros' => 2847,
                'compresion' => true
            ],
            [
                'id' => 2,
                'nombre' => 'backup_2024-10-06_08-30-00.sql',
                'fecha_creacion' => '2024-10-06 08:30:00',
                'tamaño' => '338.2 MB',
                'tipo' => 'Automático',
                'estado' => 'Completado',
                'tablas' => 15,
                'registros' => 2821,
                'compresion' => true
            ],
            [
                'id' => 3,
                'nombre' => 'backup_manual_2024-10-05_14-22-15.sql',
                'fecha_creacion' => '2024-10-05 14:22:15',
                'tamaño' => '335.8 MB',
                'tipo' => 'Manual',
                'estado' => 'Completado',
                'tablas' => 15,
                'registros' => 2795,
                'compresion' => true
            ],
            [
                'id' => 4,
                'nombre' => 'backup_2024-10-05_08-30-00.sql',
                'fecha_creacion' => '2024-10-05 08:30:00',
                'tamaño' => '333.1 MB',
                'tipo' => 'Automático',
                'estado' => 'Completado',
                'tablas' => 15,
                'registros' => 2773,
                'compresion' => true
            ],
            [
                'id' => 5,
                'nombre' => 'backup_2024-10-04_08-30-00.sql',
                'fecha_creacion' => '2024-10-04 08:30:00',
                'tamaño' => '329.7 MB',
                'tipo' => 'Automático',
                'estado' => 'Completado',
                'tablas' => 15,
                'registros' => 2751,
                'compresion' => true
            ]
        ];
    }

    /**
     * Vista principal de configuración del sistema
     */
    public function configuracion()
    {
        $data = [
            'title' => 'Configuración del Sistema',
            'configuraciones' => $this->getDatosPruebaConfiguracion(),
            'estadisticas' => [
                'total_configuraciones' => 12,
                'configuraciones_activas' => 10,
                'ultima_modificacion' => '2024-10-07 10:15:00',
                'configuraciones_por_defecto' => 8
            ]
        ];

        return view('Administrador/configuracion', $data);
    }

    /**
     * Guardar configuración
     */
    public function guardarConfiguracion()
    {
        try {
            $configuraciones = $this->request->getPost();
            
            // TODO: Implementar guardado real de configuraciones
            foreach ($configuraciones as $clave => $valor) {
                // Guardar en base de datos o archivo de configuración
                log_message('info', "Configuración actualizada: {$clave} = {$valor}");
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuraciones guardadas exitosamente'
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar configuraciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Restaurar configuración por defecto
     */
    public function restaurarConfiguracion()
    {
        try {
            $seccion = $this->request->getPost('seccion');
            
            // TODO: Implementar restauración real
            log_message('info', "Configuración restaurada para sección: {$seccion}");
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuraciones restauradas a valores por defecto'
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al restaurar configuraciones: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Datos de prueba para configuraciones
     */
    private function getDatosPruebaConfiguracion()
    {
        return [
            'general' => [
                'nombre' => 'Configuración General',
                'icono' => 'ti-settings',
                'configuraciones' => [
                    [
                        'clave' => 'nombre_biblioteca',
                        'nombre' => 'Nombre de la Biblioteca',
                        'valor' => 'Biblioteca Virtual HZG',
                        'tipo' => 'text',
                        'descripcion' => 'Nombre que aparece en el sistema',
                        'requerido' => true
                    ],
                    [
                        'clave' => 'email_administrador',
                        'nombre' => 'Email del Administrador',
                        'valor' => 'admin@bibliotecahzg.edu.co',
                        'tipo' => 'email',
                        'descripcion' => 'Correo principal del administrador',
                        'requerido' => true
                    ],
                    [
                        'clave' => 'telefono_biblioteca',
                        'nombre' => 'Teléfono de Contacto',
                        'valor' => '+57 123 456 7890',
                        'tipo' => 'tel',
                        'descripcion' => 'Número de contacto principal',
                        'requerido' => false
                    ],
                    [
                        'clave' => 'direccion',
                        'nombre' => 'Dirección',
                        'valor' => 'Calle 123 #45-67, Ciudad, País',
                        'tipo' => 'text',
                        'descripcion' => 'Dirección física de la biblioteca',
                        'requerido' => false
                    ]
                ]
            ],
            'prestamos' => [
                'nombre' => 'Configuración de Préstamos',
                'icono' => 'ti-bookmark',
                'configuraciones' => [
                    [
                        'clave' => 'dias_prestamo_estudiante',
                        'nombre' => 'Días de Préstamo - Estudiantes',
                        'valor' => '14',
                        'tipo' => 'number',
                        'descripcion' => 'Número de días para préstamos de estudiantes',
                        'requerido' => true,
                        'min' => 1,
                        'max' => 90
                    ],
                    [
                        'clave' => 'dias_prestamo_docente',
                        'nombre' => 'Días de Préstamo - Docentes',
                        'valor' => '30',
                        'tipo' => 'number',
                        'descripcion' => 'Número de días para préstamos de docentes',
                        'requerido' => true,
                        'min' => 1,
                        'max' => 180
                    ],
                    [
                        'clave' => 'max_renovaciones',
                        'nombre' => 'Máximo de Renovaciones',
                        'valor' => '2',
                        'tipo' => 'number',
                        'descripcion' => 'Número máximo de renovaciones permitidas',
                        'requerido' => true,
                        'min' => 0,
                        'max' => 5
                    ],
                    [
                        'clave' => 'permitir_reservas',
                        'nombre' => 'Permitir Reservas',
                        'valor' => '1',
                        'tipo' => 'boolean',
                        'descripcion' => 'Habilitar sistema de reservas',
                        'requerido' => true
                    ]
                ]
            ],
            'multas' => [
                'nombre' => 'Configuración de Multas',
                'icono' => 'ti-currency-dollar',
                'configuraciones' => [
                    [
                        'clave' => 'multa_por_dia',
                        'nombre' => 'Multa por Día de Retraso',
                        'valor' => '2500',
                        'tipo' => 'number',
                        'descripcion' => 'Valor en pesos por día de retraso',
                        'requerido' => true,
                        'min' => 0
                    ],
                    [
                        'clave' => 'multa_maxima',
                        'nombre' => 'Multa Máxima',
                        'valor' => '50000',
                        'tipo' => 'number',
                        'descripcion' => 'Valor máximo de multa por préstamo',
                        'requerido' => true,
                        'min' => 0
                    ],
                    [
                        'clave' => 'dias_gracia',
                        'nombre' => 'Días de Gracia',
                        'valor' => '3',
                        'tipo' => 'number',
                        'descripcion' => 'Días sin multa después del vencimiento',
                        'requerido' => true,
                        'min' => 0,
                        'max' => 7
                    ]
                ]
            ],
            'notificaciones' => [
                'nombre' => 'Configuración de Notificaciones',
                'icono' => 'ti-bell',
                'configuraciones' => [
                    [
                        'clave' => 'notificar_vencimiento',
                        'nombre' => 'Notificar Vencimientos',
                        'valor' => '1',
                        'tipo' => 'boolean',
                        'descripcion' => 'Enviar recordatorios de vencimiento',
                        'requerido' => true
                    ],
                    [
                        'clave' => 'dias_aviso_vencimiento',
                        'nombre' => 'Días de Aviso Previo',
                        'valor' => '3',
                        'tipo' => 'number',
                        'descripcion' => 'Días antes del vencimiento para avisar',
                        'requerido' => true,
                        'min' => 1,
                        'max' => 10
                    ],
                    [
                        'clave' => 'email_notificaciones',
                        'nombre' => 'Email para Notificaciones',
                        'valor' => 'noreply@bibliotecahzg.edu.co',
                        'tipo' => 'email',
                        'descripcion' => 'Email remitente de notificaciones',
                        'requerido' => true
                    ]
                ]
            ]
        ];
    }

    // =====================================
    // MÉTODOS PARA MODALES DEL SISTEMA
    // =====================================

    /**
     * Modal: Mi Perfil
     * Muestra el modal con la información del perfil del usuario actual
     */
    public function miPerfil()
    {
        // Datos simulados del perfil del usuario actual
        $data['usuario'] = [
            'nombres' => 'María Elena',
            'apellidos' => 'González Martínez',
            'email' => 'admin@bibliotecahzg.edu.pe',
            'telefono' => '+51 987 654 321',
            'tipo_documento' => 'DNI',
            'numero_documento' => '12345678',
            'rol' => 'Administrador',
            'direccion' => 'Av. Principal 123, Lima, Perú',
            'foto_perfil' => base_url('./assets/images/profile/user-1.jpg'),
            'fecha_registro' => '2024-01-15',
            'ultimo_acceso' => date('Y-m-d H:i:s'),
            'estado' => 'Activo'
        ];

        // Configuraciones de seguridad
        $data['seguridad'] = [
            'autenticacion_2fa_sms' => true,
            'autenticacion_2fa_email' => false,
            'cambio_password_requerido' => false,
            'sesiones_simultaneas' => 3
        ];

        // Preferencias del usuario
        $data['preferencias'] = [
            'notificaciones_email' => true,
            'notificaciones_sistema' => true,
            'notificaciones_prestamos' => true,
            'notificaciones_reportes' => false,
            'tema_interfaz' => 'light',
            'idioma' => 'es',
            'elementos_por_pagina' => 25,
            'formato_fecha' => 'dd/mm/yyyy',
            'perfil_publico' => false,
            'mostrar_actividad' => true,
            'recopilacion_datos' => true
        ];

        return view('Administrador/modals/mi-perfil', $data);
    }

    /**
     * Modal: Mis Tareas
     * Muestra el modal con las tareas y actividades del usuario
     */
    public function misTareas()
    {
        // Estadísticas de tareas
        $data['estadisticas'] = [
            'pendientes' => 8,
            'completadas' => 25,
            'vencidas' => 3,
            'en_progreso' => 5
        ];

        // Tareas pendientes
        $data['tareas_pendientes'] = [
            [
                'id' => 1,
                'titulo' => 'Revisar reportes mensuales de préstamos',
                'descripcion' => 'Análisis de estadísticas y tendencias de préstamos del mes actual',
                'prioridad' => 'alta',
                'fecha_vencimiento' => '2025-10-15',
                'asignado_por' => 'Director',
                'categoria' => 'reportes',
                'fecha_creacion' => '2025-10-01'
            ],
            [
                'id' => 2,
                'titulo' => 'Actualizar catálogo de nuevos libros',
                'descripcion' => 'Catalogar e ingresar al sistema los 45 libros recibidos esta semana',
                'prioridad' => 'media',
                'fecha_vencimiento' => '2025-10-20',
                'asignado_por' => 'Coordinador',
                'categoria' => 'catalogacion',
                'fecha_creacion' => '2025-10-02'
            ],
            [
                'id' => 3,
                'titulo' => 'Organizar evento "Semana del Libro"',
                'descripcion' => 'Coordinar actividades y logística para la semana cultural',
                'prioridad' => 'baja',
                'fecha_vencimiento' => '2025-10-30',
                'asignado_por' => 'Auto-asignada',
                'categoria' => 'administracion',
                'fecha_creacion' => '2025-10-01'
            ]
        ];

        // Tareas en progreso
        $data['tareas_progreso'] = [
            [
                'id' => 4,
                'titulo' => 'Migración de datos históricos',
                'descripcion' => 'Transferir registros del sistema anterior al nuevo',
                'progreso' => 65,
                'fecha_inicio' => '2025-10-01',
                'estimado_finalizacion' => '2025-10-12'
            ],
            [
                'id' => 5,
                'titulo' => 'Capacitación del personal',
                'descripcion' => 'Entrenar al equipo en el uso del nuevo sistema',
                'progreso' => 40,
                'fecha_inicio' => '2025-10-05',
                'estimado_finalizacion' => '2025-10-18'
            ]
        ];

        // Tareas completadas recientes
        $data['tareas_completadas'] = [
            [
                'id' => 6,
                'titulo' => 'Configuración inicial del sistema',
                'fecha_completado' => '2025-10-06'
            ],
            [
                'id' => 7,
                'titulo' => 'Backup de datos mensuales',
                'fecha_completado' => '2025-10-05'
            ],
            [
                'id' => 8,
                'titulo' => 'Inventario de libros de octubre',
                'fecha_completado' => '2025-10-04'
            ]
        ];

        // Tareas vencidas
        $data['tareas_vencidas'] = [
            [
                'id' => 9,
                'titulo' => 'Revisión de multas pendientes',
                'descripcion' => 'Revisar y gestionar las multas acumuladas del mes',
                'fecha_vencimiento' => '2025-10-03',
                'dias_retraso' => 4,
                'prioridad' => 'critica'
            ],
            [
                'id' => 10,
                'titulo' => 'Actualización de políticas',
                'descripcion' => 'Revisar y actualizar las políticas de préstamo',
                'fecha_vencimiento' => '2025-10-05',
                'dias_retraso' => 2,
                'prioridad' => 'alta'
            ]
        ];

        return view('Administrador/modals/mis-tareas', $data);
    }

    /**
     * Modal: Ayuda del Sistema
     * Muestra el modal con documentación, FAQs y soporte
     */
    public function ayuda()
    {
        // Preguntas frecuentes organizadas por categorías
        $data['faq'] = [
            'general' => [
                'categoria' => 'General',
                'icono' => 'ti ti-settings',
                'color' => 'primary',
                'total' => 8,
                'preguntas' => [
                    [
                        'pregunta' => '¿Cómo accedo al sistema por primera vez?',
                        'respuesta' => 'Para acceder por primera vez, utiliza las credenciales proporcionadas por el administrador. Una vez dentro, se recomienda cambiar la contraseña desde el perfil de usuario.'
                    ],
                    [
                        'pregunta' => '¿Cómo recupero mi contraseña?',
                        'respuesta' => 'En la pantalla de login, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo electrónico. Recibirás un enlace para restablecer tu contraseña.'
                    ],
                    [
                        'pregunta' => '¿El sistema funciona en dispositivos móviles?',
                        'respuesta' => 'Sí, el sistema está diseñado para ser completamente responsivo y funciona correctamente en tablets y smartphones.'
                    ],
                    [
                        'pregunta' => '¿Puedo personalizar la interfaz?',
                        'respuesta' => 'Desde tu perfil puedes cambiar el tema (claro/oscuro), idioma, y otras preferencias de visualización.'
                    ]
                ]
            ],
            'prestamos' => [
                'categoria' => 'Préstamos',
                'icono' => 'ti ti-book',
                'color' => 'success',
                'total' => 12,
                'preguntas' => [
                    [
                        'pregunta' => '¿Cómo registro un nuevo préstamo?',
                        'respuesta' => 'Ve a la sección "Préstamos" → "Solicitudes" → "Nuevo Préstamo". Busca el usuario y selecciona los recursos a prestar.'
                    ],
                    [
                        'pregunta' => '¿Cómo proceso una devolución?',
                        'respuesta' => 'En "Préstamos" → "Devoluciones", busca el préstamo y haz clic en "Procesar Devolución". Verifica el estado del material.'
                    ],
                    [
                        'pregunta' => '¿Qué hago si un libro se devuelve dañado?',
                        'respuesta' => 'Registra el daño en el sistema durante la devolución y genera una sanción si es necesario según las políticas de la biblioteca.'
                    ]
                ]
            ]
        ];

        // Tutoriales disponibles
        $data['tutoriales'] = [
            [
                'id' => 'intro',
                'titulo' => 'Introducción al Sistema',
                'duracion' => '15 minutos',
                'nivel' => 'Básico',
                'descripcion' => 'Aprende los conceptos básicos y navegación del sistema de biblioteca virtual.',
                'icono' => 'ti ti-play',
                'color' => 'primary'
            ],
            [
                'id' => 'prestamos',
                'titulo' => 'Gestión de Préstamos',
                'duracion' => '25 minutos',
                'nivel' => 'Intermedio',
                'descripcion' => 'Tutorial completo sobre cómo gestionar préstamos, devoluciones y renovaciones.',
                'icono' => 'ti ti-book',
                'color' => 'success'
            ],
            [
                'id' => 'usuarios',
                'titulo' => 'Administración de Usuarios',
                'duracion' => '20 minutos',
                'nivel' => 'Intermedio',
                'descripcion' => 'Cómo crear, editar y gestionar usuarios del sistema bibliotecario.',
                'icono' => 'ti ti-users',
                'color' => 'info'
            ],
            [
                'id' => 'reportes',
                'titulo' => 'Reportes y Estadísticas',
                'duracion' => '18 minutos',
                'nivel' => 'Avanzado',
                'descripcion' => 'Genera reportes detallados y comprende las estadísticas del sistema.',
                'icono' => 'ti ti-chart-bar',
                'color' => 'warning'
            ]
        ];

        // Manuales disponibles
        $data['manuales'] = [
            [
                'tipo' => 'usuario',
                'titulo' => 'Manual de Usuario',
                'descripcion' => 'Guía completa para usuarios del sistema',
                'paginas' => 45,
                'version' => '2.1',
                'icono' => 'ti ti-file-text',
                'color' => 'primary'
            ],
            [
                'tipo' => 'admin',
                'titulo' => 'Manual de Administrador',
                'descripcion' => 'Configuración y gestión avanzada del sistema',
                'paginas' => 78,
                'version' => '2.1',
                'icono' => 'ti ti-settings',
                'color' => 'success'
            ],
            [
                'tipo' => 'tecnico',
                'titulo' => 'Manual Técnico',
                'descripcion' => 'Documentación técnica y API del sistema',
                'paginas' => 124,
                'version' => '2.1',
                'icono' => 'ti ti-code',
                'color' => 'info'
            ]
        ];

        // Información de contacto
        $data['contacto'] = [
            'email_soporte' => 'soporte@bibliotecahzg.edu.pe',
            'telefono' => '+51 (01) 234-5678',
            'horarios' => [
                'lunes_viernes' => '8:00 AM - 6:00 PM',
                'sabado' => '9:00 AM - 2:00 PM'
            ],
            'direccion' => 'Biblioteca Central HZG, Av. Universitaria 123, Lima, Perú',
            'tiempos_respuesta' => [
                'baja' => '2-3 días hábiles',
                'media' => '1-2 días hábiles',
                'alta' => '4-6 horas',
                'critica' => '1-2 horas'
            ]
        ];

        return view('Administrador/modals/ayuda', $data);
    }

    // =====================================
    // MÉTODOS PARA GESTIÓN DE CATEGORÍAS
    // =====================================

    /**
     * Vista principal de gestión de categorías
     */
    public function categorias()
    {
        try {
            $categoriaModel = new \App\Models\CategoriaModel();
            $subcategoriaModel = new \App\Models\SubcategoriaModel();

            // Obtener todas las categorías con sus subcategorías
            $categorias = $categoriaModel->findAll();
            $categoriasConSubcategorias = [];

            foreach ($categorias as $categoria) {
                $subcategorias = $subcategoriaModel->where('idcategoria', $categoria['idcategoria'])->findAll();
                $categoria['subcategorias'] = $subcategorias;
                $categoria['total_subcategorias'] = count($subcategorias);
                $categoriasConSubcategorias[] = $categoria;
            }

            $data = [
                'title' => 'Gestión de Categorías',
                'categorias' => $categoriasConSubcategorias,
                'estadisticas' => [
                    'total_categorias' => count($categorias),
                    'total_subcategorias' => $subcategoriaModel->countAll(),
                    'categorias_con_subcategorias' => count(array_filter($categoriasConSubcategorias, function($c) { return $c['total_subcategorias'] > 0; })),
                    'categorias_sin_subcategorias' => count(array_filter($categoriasConSubcategorias, function($c) { return $c['total_subcategorias'] == 0; }))
                ]
            ];

            return view('Administrador/categorias/basic', $data);
        } catch (\Exception $e) {
            // Si hay error, devolver vista de error
            return view('Administrador/categorias/basic', [
                'title' => 'Gestión de Categorías',
                'categorias' => [],
                'estadisticas' => [
                    'total_categorias' => 0,
                    'total_subcategorias' => 0,
                    'categorias_con_subcategorias' => 0,
                    'categorias_sin_subcategorias' => 0
                ],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crear nueva categoría
     */
    public function crearCategoria()
    {
        try {
            $categoriaModel = new \App\Models\CategoriaModel();
            
            $categoria = $this->request->getPost('categoria');
            
            if (empty($categoria)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El nombre de la categoría es requerido'
                ]);
            }

            // Verificar si ya existe
            $existe = $categoriaModel->where('categoria', $categoria)->first();
            if ($existe) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya existe una categoría con ese nombre'
                ]);
            }

            $idCategoria = $categoriaModel->insert(['categoria' => $categoria]);
            
            if ($idCategoria) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría creada exitosamente',
                    'categoria' => [
                        'idcategoria' => $idCategoria,
                        'categoria' => $categoria,
                        'subcategorias' => [],
                        'total_subcategorias' => 0
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la categoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Editar categoría
     */
    public function editarCategoria($id)
    {
        try {
            $categoriaModel = new \App\Models\CategoriaModel();
            
            $categoria = $this->request->getPost('categoria');
            
            if (empty($categoria)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El nombre de la categoría es requerido'
                ]);
            }

            // Verificar si ya existe (excluyendo la categoría actual)
            $existe = $categoriaModel->where('categoria', $categoria)
                                   ->where('idcategoria !=', $id)
                                   ->first();
            if ($existe) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya existe una categoría con ese nombre'
                ]);
            }

            $actualizado = $categoriaModel->update($id, ['categoria' => $categoria]);
            
            if ($actualizado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría actualizada exitosamente',
                    'categoria' => [
                        'idcategoria' => $id,
                        'categoria' => $categoria
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar la categoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar categoría
     */
    public function eliminarCategoria($id)
    {
        try {
            $categoriaModel = new \App\Models\CategoriaModel();
            $subcategoriaModel = new \App\Models\SubcategoriaModel();
            
            // Verificar si la categoría tiene subcategorías
            $subcategorias = $subcategoriaModel->where('idcategoria', $id)->findAll();
            if (!empty($subcategorias)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar la categoría porque tiene subcategorías asociadas'
                ]);
            }

            // Verificar si hay recursos asociados
            $recursos = $this->db->query("
                SELECT COUNT(*) as total 
                FROM recursos r 
                INNER JOIN subcategorias s ON r.idsubcategoria = s.idsubcategoria 
                WHERE s.idcategoria = ?
            ", [$id])->getRow();
            
            if ($recursos && $recursos->total > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar la categoría porque tiene recursos asociados'
                ]);
            }

            $eliminado = $categoriaModel->delete($id);
            
            if ($eliminado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría eliminada exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Crear nueva subcategoría
     */
    public function crearSubcategoria()
    {
        try {
            $subcategoriaModel = new \App\Models\SubcategoriaModel();
            
            $subcategoria = $this->request->getPost('subcategoria');
            $idcategoria = $this->request->getPost('idcategoria');
            
            if (empty($subcategoria) || empty($idcategoria)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El nombre de la subcategoría y la categoría son requeridos'
                ]);
            }

            // Verificar si ya existe en esta categoría
            $existe = $subcategoriaModel->where('subcategoria', $subcategoria)
                                      ->where('idcategoria', $idcategoria)
                                      ->first();
            if ($existe) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya existe una subcategoría con ese nombre en esta categoría'
                ]);
            }

            $idSubcategoria = $subcategoriaModel->insert([
                'subcategoria' => $subcategoria,
                'idcategoria' => $idcategoria
            ]);
            
            if ($idSubcategoria) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Subcategoría creada exitosamente',
                    'subcategoria' => [
                        'idsubcategoria' => $idSubcategoria,
                        'subcategoria' => $subcategoria,
                        'idcategoria' => $idcategoria
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la subcategoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear la subcategoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Editar subcategoría
     */
    public function editarSubcategoria($id)
    {
        try {
            $subcategoriaModel = new \App\Models\SubcategoriaModel();
            
            $subcategoria = $this->request->getPost('subcategoria');
            $idcategoria = $this->request->getPost('idcategoria');
            
            if (empty($subcategoria) || empty($idcategoria)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El nombre de la subcategoría y la categoría son requeridos'
                ]);
            }

            // Verificar si ya existe (excluyendo la subcategoría actual)
            $existe = $subcategoriaModel->where('subcategoria', $subcategoria)
                                      ->where('idcategoria', $idcategoria)
                                      ->where('idsubcategoria !=', $id)
                                      ->first();
            if ($existe) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya existe una subcategoría con ese nombre en esta categoría'
                ]);
            }

            $actualizado = $subcategoriaModel->update($id, [
                'subcategoria' => $subcategoria,
                'idcategoria' => $idcategoria
            ]);
            
            if ($actualizado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Subcategoría actualizada exitosamente',
                    'subcategoria' => [
                        'idsubcategoria' => $id,
                        'subcategoria' => $subcategoria,
                        'idcategoria' => $idcategoria
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar la subcategoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar la subcategoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar subcategoría
     */
    public function eliminarSubcategoria($id)
    {
        try {
            $subcategoriaModel = new \App\Models\SubcategoriaModel();
            
            // Verificar si hay recursos asociados
            $recursos = $this->db->query("
                SELECT COUNT(*) as total 
                FROM recursos 
                WHERE idsubcategoria = ?
            ", [$id])->getRow();
            
            if ($recursos && $recursos->total > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar la subcategoría porque tiene recursos asociados'
                ]);
            }

            $eliminado = $subcategoriaModel->delete($id);
            
            if ($eliminado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Subcategoría eliminada exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar la subcategoría'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar la subcategoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener subcategorías de una categoría específica
     */
    public function obtenerSubcategorias($idcategoria)
    {
        try {
            $subcategoriaModel = new \App\Models\SubcategoriaModel();
            $subcategorias = $subcategoriaModel->where('idcategoria', $idcategoria)->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'subcategorias' => $subcategorias
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las subcategorías: ' . $e->getMessage()
            ]);
        }
    }
}