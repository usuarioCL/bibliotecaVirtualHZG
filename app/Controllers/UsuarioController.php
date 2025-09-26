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
                    'tipo_creado' => $datosUsuario['nivelacceso'] === 'estudiante' ? 'Usuario y Matrícula' : 'Usuario'
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

}  