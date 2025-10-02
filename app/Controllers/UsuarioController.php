<?php

namespace App\Controllers;
use App\Models\UsuarioModel;
use App\Models\personaModel;
use App\Models\MatriculaModel;
use CodeIgniter\Controller;
use Exception;

class UsuarioController extends Controller
{
    protected $usuarioModel;
    protected $personaModel;
    protected $matriculaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->personaModel = new personaModel();
        $this->matriculaModel = new MatriculaModel();
    }

    /**
     * Página principal de gestión de usuarios (para AJAX)
     */
    public function index()
    {
        try {
            // Obtener todos los usuarios con información de personas
            $usuarios = $this->usuarioModel->select('usuarios.*, personas.apellidos, personas.nombres, personas.email, personas.tipodoc, personas.numerodoc')
                                         ->join('personas', 'personas.idpersona = usuarios.idpersona')
                                         ->findAll();

            // Para cada estudiante, obtener información de matrícula
            foreach ($usuarios as &$usuario) {
                if ($usuario['nivelacceso'] === 'estudiante') {
                    $matricula = $this->matriculaModel->getMatriculaActiva($usuario['idpersona']);
                    $usuario['matricula'] = $matricula;
                }
            }

            // Calcular estadísticas
            $estadisticas = $this->calcularEstadisticasUsuarios($usuarios);

            $data = [
                'usuarios' => $usuarios,
                'totalUsuarios' => $estadisticas['total'],
                'administradores' => $estadisticas['administradores'],
                'docentes' => $estadisticas['docentes'],
                'estudiantes' => $estadisticas['estudiantes']
            ];

            return view('Administrador/usuarios/index', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en UsuarioController::index: ' . $e->getMessage());
            
            $data = [
                'usuarios' => [],
                'totalUsuarios' => 0,
                'administradores' => 0,
                'docentes' => 0,
                'estudiantes' => 0,
                'error_message' => 'Error al cargar usuarios: ' . $e->getMessage()
            ];

            return view('Administrador/usuarios/index', $data);
        }
    }

    /**
     * Calcular estadísticas de usuarios
     */
    private function calcularEstadisticasUsuarios($usuarios)
    {
        $estadisticas = [
            'total' => count($usuarios),
            'administradores' => 0,
            'docentes' => 0,
            'estudiantes' => 0
        ];

        foreach ($usuarios as $usuario) {
            switch ($usuario['nivelacceso']) {
                case 'admin':
                    $estadisticas['administradores']++;
                    break;
                case 'docente':
                    $estadisticas['docentes']++;
                    break;
                case 'estudiante':
                    $estadisticas['estudiantes']++;
                    break;
            }
        }

        return $estadisticas;
    }

    /**
     * Crear persona y usuario completo
     */
    public function crearCompleto()
    {
        if ($this->request->getMethod() === 'POST') {
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // 1. Crear la persona primero
                $datosPersona = [
                    'apellidos' => $this->request->getPost('apellidos'),
                    'nombres' => $this->request->getPost('nombres'),
                    'tipodoc' => $this->request->getPost('tipodoc'),
                    'numerodoc' => $this->request->getPost('numerodoc'),
                    'telefono' => $this->request->getPost('telefono'),
                    'direccion' => $this->request->getPost('direccion'),
                    'email' => $this->request->getPost('email'),
                    'genero' => $this->request->getPost('genero')
                ];

                // Insertar persona
                if (!$this->personaModel->insert($datosPersona)) {
                    throw new Exception('Error al crear la persona: ' . implode(', ', $this->personaModel->errors()));
                }

                $idpersona = $this->personaModel->getInsertID();

                // 2. Crear el usuario con la persona recién creada
                $datosUsuario = [
                    'nomuser' => $this->request->getPost('nomuser'),
                    'passuser' => $this->request->getPost('passuser'),
                    'nivelacceso' => $this->request->getPost('nivelacceso'),
                    'idpersona' => $idpersona
                ];

                // Usar el método de validación del modelo
                $resultado = $this->usuarioModel->crearUsuarioConValidacion($datosUsuario);

                if (!$resultado['exito']) {
                    throw new Exception($resultado['mensaje']);
                }

                // 3. Si es estudiante, crear matrícula automáticamente
                $crearMatricula = false;
                if ($datosUsuario['nivelacceso'] === 'estudiante') {
                    // Buscar un grupo por defecto o crear uno
                    $grupoModel = new \App\Models\GrupoModel();
                    $grupoDefecto = $grupoModel->where([
                        'grado' => 1,
                        'seccion' => 'A',
                        'nivel' => 'Primaria',
                        'aniolectivo' => date('Y')
                    ])->first();
                    
                    if (!$grupoDefecto) {
                        $idgrupo = $grupoModel->insert([
                            'grado' => 1,
                            'seccion' => 'A',
                            'nivel' => 'Primaria',
                            'aniolectivo' => date('Y')
                        ]);
                    } else {
                        $idgrupo = $grupoDefecto['idgrupo'];
                    }

                    // Crear matrícula
                    $matriculaData = [
                        'fechamatricula' => date('Y-m-d'),
                        'estadomatricula' => true,
                        'idpersona' => $idpersona,
                        'idgrupo' => $idgrupo
                    ];

                    if ($this->matriculaModel->insert($matriculaData)) {
                        $crearMatricula = true;
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new Exception('Error en la transacción de base de datos');
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Persona y usuario creados exitosamente' . ($crearMatricula ? ' (incluye matrícula automática)' : ''),
                    'usuario' => $datosUsuario['nomuser'],
                    'email' => $datosPersona['email'],
                    'persona_id' => $idpersona,
                    'user_id' => $resultado['id'],
                    'tipo_creado' => $datosUsuario['nivelacceso'] === 'estudiante' ? 'Usuario y Matrícula' : 'Usuario',
                    'nivel_acceso' => $datosUsuario['nivelacceso'],
                    'nombres_completos' => $datosPersona['nombres'] . ' ' . $datosPersona['apellidos'],
                    'numero_documento' => $datosPersona['numerodoc'],
                    'tiene_matricula' => $crearMatricula,
                    'redirect_url' => base_url('usuarios')
                ]);

            } catch (Exception $e) {
                $db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        return $this->response->setStatusCode(405)->setJSON([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
    }

    /**
     * Verificar si una persona puede crear un usuario
     */
    public function verificarElegibilidad()
    {
        $idpersona = $this->request->getGet('idpersona');
        $nivelacceso = $this->request->getGet('nivelacceso');

        if (!$idpersona || !$nivelacceso) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Parámetros insuficientes'
            ]);
        }

        $validacion = $this->usuarioModel->validarElegibilidadUsuario($idpersona, $nivelacceso);

        return $this->response->setJSON([
            'status' => $validacion['valido'] ? 'success' : 'error',
            'message' => $validacion['mensaje'],
            'elegible' => $validacion['valido']
        ]);
    }

    /**
     * Buscar estudiante matriculado por DNI para autocompletado
     */
    public function buscarPorDni()
    {
        $numerodoc = $this->request->getGet('numerodoc');

        if (!$numerodoc) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Número de documento es requerido'
            ]);
        }

        try {
            // Buscar persona con matrícula activa
            $estudiante = $this->matriculaModel->select('
                personas.*,
                matriculas.estadomatricula,
                grupos.nivel,
                grupos.grado,
                grupos.seccion,
                usuarios.nomuser
            ')
            ->join('personas', 'personas.idpersona = matriculas.idpersona')
            ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo')
            ->join('usuarios', 'usuarios.idpersona = personas.idpersona', 'left')
            ->where('personas.numerodoc', $numerodoc)
            ->where('matriculas.estadomatricula', true)
            ->first();

            if ($estudiante) {
                // Verificar si ya tiene usuario
                $tieneUsuario = !empty($estudiante['nomuser']);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'encontrado' => true,
                    'datos' => [
                        'apellidos' => $estudiante['apellidos'],
                        'nombres' => $estudiante['nombres'],
                        'tipodoc' => $estudiante['tipodoc'],
                        'numerodoc' => $estudiante['numerodoc'],
                        'telefono' => $estudiante['telefono'],
                        'direccion' => $estudiante['direccion'],
                        'email' => $estudiante['email'],
                        'genero' => $estudiante['genero'],
                        'nivel_academico' => $estudiante['nivel'] . ' - ' . $estudiante['grado'] . '° ' . $estudiante['seccion'],
                        'tiene_usuario' => $tieneUsuario,
                        'usuario_existente' => $estudiante['nomuser']
                    ]
                ]);
            } else {
                // Buscar solo en personas (sin matrícula)
                $persona = $this->personaModel->where('numerodoc', $numerodoc)->first();
                
                if ($persona) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'encontrado' => true,
                        'datos' => [
                            'apellidos' => $persona['apellidos'],
                            'nombres' => $persona['nombres'],
                            'tipodoc' => $persona['tipodoc'],
                            'numerodoc' => $persona['numerodoc'],
                            'telefono' => $persona['telefono'],
                            'direccion' => $persona['direccion'],
                            'email' => $persona['email'],
                            'genero' => $persona['genero'],
                            'nivel_academico' => 'Sin matrícula',
                            'tiene_usuario' => false,
                            'usuario_existente' => null
                        ]
                    ]);
                }
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'encontrado' => false,
                    'message' => 'No se encontró ninguna persona con ese DNI'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al buscar: ' . $e->getMessage()
            ]);
        }
    }



    /**
     * Obtener detalles de un usuario específico (para modal de detalles)
     */
    public function obtener($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de usuario requerido'
            ]);
        }

        try {
            // Debug: Log el ID recibido
            log_message('info', 'UsuarioController::obtener - Buscando usuario con ID: ' . $id);

            // Primero, obtener solo el usuario básico
            $usuarioBasico = $this->usuarioModel->find($id);
            
            if (!$usuarioBasico) {
                log_message('error', 'Usuario no encontrado con ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado con ID: ' . $id,
                    'debug_info' => [
                        'id_buscado' => $id,
                        'tabla' => 'usuarios',
                        'timestamp' => date('Y-m-d H:i:s')
                    ]
                ]);
            }

            log_message('info', 'Usuario encontrado: ' . json_encode($usuarioBasico));

            // Ahora obtener datos de persona
            $persona = $this->personaModel->find($usuarioBasico['idpersona']);
            
            if (!$persona) {
                log_message('error', 'Persona no encontrada con ID: ' . $usuarioBasico['idpersona']);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de persona no encontrados',
                    'debug_info' => [
                        'idpersona_buscado' => $usuarioBasico['idpersona'],
                        'usuario_encontrado' => $usuarioBasico,
                        'tabla' => 'personas'
                    ]
                ]);
            }

            // Combinar datos
            $usuario = array_merge($usuarioBasico, [
                'apellidos' => $persona['apellidos'],
                'nombres' => $persona['nombres'],
                'tipodoc' => $persona['tipodoc'],
                'dni' => $persona['numerodoc'],
                'telefono' => $persona['telefono'],
                'direccion' => $persona['direccion'],
                'email' => $persona['email'],
                'genero' => $persona['genero'],
                'estado' => 1, // Por defecto activo
                'fecha_creacion' => null,
                'fecha_actualizacion' => null
            ]);

            // Si es estudiante, obtener información académica
            if ($usuario['nivelacceso'] === 'estudiante') {
                $matricula = $this->matriculaModel->select('
                    matriculas.*,
                    grupos.nivel,
                    grupos.grado,
                    grupos.seccion,
                    grupos.aniolectivo
                ')
                ->join('grupos', 'grupos.idgrupo = matriculas.idgrupo')
                ->where('matriculas.idpersona', $usuario['idpersona'])
                ->where('matriculas.estadomatricula', true)
                ->first();

                if ($matricula) {
                    $usuario['nivel'] = $matricula['nivel'];
                    $usuario['grado'] = $matricula['grado'];
                    $usuario['seccion'] = $matricula['seccion'];
                    $usuario['anio_lectivo'] = $matricula['aniolectivo'];
                    log_message('info', 'Información académica encontrada para estudiante');
                } else {
                    log_message('info', 'Sin información académica para estudiante');
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en UsuarioController::obtener: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar usuario y persona
     */
    public function actualizar()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON([
                'status' => 'error',
                'message' => 'Método no permitido'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $idusuario = $this->request->getPost('idusuario');
            $idpersona = $this->request->getPost('idpersona');

            if (!$idusuario || !$idpersona) {
                throw new Exception('ID de usuario y persona son requeridos');
            }

            // Verificar que el usuario existe
            $usuarioExistente = $this->usuarioModel->find($idusuario);
            if (!$usuarioExistente) {
                throw new Exception('Usuario no encontrado');
            }

            // 1. Actualizar datos de persona
            $datosPersona = [
                'apellidos' => $this->request->getPost('apellidos'),
                'nombres' => $this->request->getPost('nombres'),
                'tipodoc' => $this->request->getPost('tipodoc'),
                'numerodoc' => $this->request->getPost('numerodoc'),
                'telefono' => $this->request->getPost('telefono'),
                'direccion' => $this->request->getPost('direccion'),
                'email' => $this->request->getPost('email'),
                'genero' => $this->request->getPost('genero')
            ];

            if (!$this->personaModel->update($idpersona, $datosPersona)) {
                throw new Exception('Error al actualizar datos personales: ' . implode(', ', $this->personaModel->errors()));
            }

            // 2. Actualizar datos de usuario
            $datosUsuario = [
                'nomuser' => $this->request->getPost('nomuser'),
                'nivelacceso' => $this->request->getPost('nivelacceso')
            ];

            // Solo actualizar contraseña si se proporcionó una nueva
            $nuevaPassword = $this->request->getPost('passuser');
            if (!empty($nuevaPassword)) {
                $datosUsuario['passuser'] = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            }

            if (!$this->usuarioModel->update($idusuario, $datosUsuario)) {
                throw new Exception('Error al actualizar datos de usuario: ' . implode(', ', $this->usuarioModel->errors()));
            }

            // 3. Si es estudiante, actualizar información académica
            $nivelAcceso = $this->request->getPost('nivelacceso');
            if ($nivelAcceso === 'estudiante') {
                $nivel = $this->request->getPost('nivel');
                $grado = $this->request->getPost('grado');
                $seccion = $this->request->getPost('seccion');
                $anioLectivo = $this->request->getPost('anio_lectivo');

                if ($nivel && $grado && $seccion && $anioLectivo) {
                    // Buscar o crear grupo
                    $grupoModel = new \App\Models\GrupoModel();
                    $grupo = $grupoModel->where([
                        'nivel' => $nivel,
                        'grado' => $grado,
                        'seccion' => $seccion,
                        'aniolectivo' => $anioLectivo
                    ])->first();

                    if (!$grupo) {
                        $idgrupo = $grupoModel->insert([
                            'nivel' => $nivel,
                            'grado' => $grado,
                            'seccion' => $seccion,
                            'aniolectivo' => $anioLectivo
                        ]);
                    } else {
                        $idgrupo = $grupo['idgrupo'];
                    }

                    // Actualizar o crear matrícula
                    $matriculaExistente = $this->matriculaModel->where([
                        'idpersona' => $idpersona,
                        'estadomatricula' => true
                    ])->first();

                    $datosMatricula = [
                        'idgrupo' => $idgrupo,
                        'fechamatricula' => date('Y-m-d'),
                        'estadomatricula' => true,
                        'idpersona' => $idpersona
                    ];

                    if ($matriculaExistente) {
                        $this->matriculaModel->update($matriculaExistente['idmatricula'], $datosMatricula);
                    } else {
                        $this->matriculaModel->insert($datosMatricula);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Error en la transacción de base de datos');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente',
                'usuario' => $datosUsuario['nomuser']
            ]);

        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Error en UsuarioController::actualizar: ' . $e->getMessage());
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar usuario y sus datos relacionados
     */
    public function eliminar($id = null)
    {
        // Verificar método HTTP
        if ($this->request->getMethod() !== 'DELETE') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Método no permitido. Use DELETE.'
            ]);
        }

        // Validar ID
        if (!$id || !is_numeric($id) || $id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'ID de usuario inválido'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            log_message('info', "Iniciando eliminación de usuario ID: {$id}");

            // 1. Verificar que el usuario existe
            $usuario = $this->usuarioModel->find($id);
            if (!$usuario) {
                log_message('error', "Usuario no encontrado con ID: {$id}");
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }

            $idpersona = $usuario['idpersona'];
            $nomuser = $usuario['nomuser'];
            $nivelacceso = $usuario['nivelacceso'];

            log_message('info', "Usuario encontrado: {$nomuser} (Nivel: {$nivelacceso})");

            // 2. Verificar si es el último administrador
            if ($nivelacceso === 'admin') {
                $totalAdmins = $this->usuarioModel->where('nivelacceso', 'admin')->countAllResults();
                if ($totalAdmins <= 1) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => 'No se puede eliminar el último administrador del sistema'
                    ]);
                }
            }

            // 3. Obtener información de la persona antes de eliminar
            $persona = $this->personaModel->find($idpersona);
            $nombreCompleto = $persona ? ($persona['nombres'] . ' ' . $persona['apellidos']) : $nomuser;

            // 4. Eliminar registros relacionados según el nivel de acceso

            // Si es estudiante, eliminar matrículas
            if ($nivelacceso === 'estudiante') {
                $matriculasEliminadas = $this->matriculaModel->where('idpersona', $idpersona)->delete();
                log_message('info', "Matrículas eliminadas para persona {$idpersona}: {$matriculasEliminadas}");
            }

            // Eliminar otros registros que puedan estar relacionados con el usuario
            // Estas tablas usan idusuario en lugar de idpersona
            $tablesWithUsuario = ['comentarios', 'favoritos', 'compartidos', 'reacciones']; 
            
            foreach ($tablesWithUsuario as $table) {
                try {
                    if ($db->tableExists($table)) {
                        $registrosEliminados = $db->table($table)->where('idusuario', $id)->delete();
                        log_message('info', "Registros eliminados de tabla {$table} para usuario {$id}: {$registrosEliminados}");
                    }
                } catch (\Exception $e) {
                    log_message('warning', "No se pudieron eliminar registros de tabla {$table}: " . $e->getMessage());
                    // Continuar con las demás tablas, no fallar por una tabla específica
                }
            }

            // Para préstamos, necesitamos manejar tanto el usuario que registra como la matrícula del estudiante
            if ($db->tableExists('prestamos')) {
                try {
                    // Eliminar préstamos donde este usuario fue quien los registró
                    $prestamosRegistrados = $db->table('prestamos')->where('idusuario', $id)->delete();
                    log_message('info', "Préstamos registrados por usuario {$id} eliminados: {$prestamosRegistrados}");
                    
                    // Si es estudiante, también eliminar préstamos donde él fue el beneficiario
                    if ($nivelacceso === 'estudiante') {
                        // Primero obtener las matrículas del estudiante
                        $matriculasUsuario = $db->table('matriculas')->where('idpersona', $idpersona)->get()->getResultArray();
                        
                        foreach ($matriculasUsuario as $matricula) {
                            $prestamosEstudiante = $db->table('prestamos')->where('idmatricula', $matricula['idmatricula'])->delete();
                            log_message('info', "Préstamos de estudiante matrícula {$matricula['idmatricula']} eliminados: {$prestamosEstudiante}");
                        }
                    }
                } catch (\Exception $e) {
                    log_message('warning', "No se pudieron eliminar préstamos: " . $e->getMessage());
                    // Continuar, no fallar por los préstamos
                }
            }

            // 5. Eliminar el usuario
            log_message('info', "Intentando eliminar usuario: ID {$id}");
            if (!$this->usuarioModel->delete($id)) {
                $errors = $this->usuarioModel->errors();
                $errorMsg = 'Error al eliminar el usuario: ' . (empty($errors) ? 'Error desconocido' : implode(', ', $errors));
                log_message('error', $errorMsg);
                throw new \Exception($errorMsg);
            }
            log_message('info', "Usuario eliminado exitosamente: ID {$id}");

            // 6. Eliminar la persona
            log_message('info', "Intentando eliminar persona: ID {$idpersona}");
            if (!$this->personaModel->delete($idpersona)) {
                $errors = $this->personaModel->errors();
                $errorMsg = 'Error al eliminar datos personales: ' . (empty($errors) ? 'Error desconocido' : implode(', ', $errors));
                log_message('error', $errorMsg);
                throw new \Exception($errorMsg);
            }
            log_message('info', "Persona eliminada exitosamente: ID {$idpersona}");

            // Completar la transacción
            $db->transComplete();

            // Verificar el estado de la transacción
            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception('Error en la transacción de base de datos - Transacción revertida');
            }

            log_message('info', "Eliminación completada exitosamente para usuario: {$nomuser}");

            return $this->response->setJSON([
                'success' => true,
                'message' => "Usuario '{$nombreCompleto}' eliminado correctamente",
                'data' => [
                    'usuario_eliminado' => $nomuser,
                    'nombre_completo' => $nombreCompleto,
                    'nivel_acceso' => $nivelacceso,
                    'id_usuario' => $id,
                    'id_persona' => $idpersona,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', "Error al eliminar usuario ID {$id}: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage(),
                'error_details' => [
                    'error_type' => get_class($e),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
        }
    }

    /**
     * Método de prueba para verificar conectividad
     */
    public function test($id = null)
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Conexión OK',
            'id_recibido' => $id,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

}  