<?php

namespace App\Controllers;
use App\Models\UsuarioModel;
use App\Models\PersonaModel;
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
        $this->personaModel = new PersonaModel();
        $this->matriculaModel = new MatriculaModel();
    }

    /**
     * Página principal de gestión de usuarios (para AJAX)
     */
    public function index()
    {
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

        $data['usuarios'] = $usuarios;
        return view('Administrador/usuarios/index', $data);
    }

    /**
     * Crear un nuevo usuario con validación de matrícula
     */
    public function crear()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'nomuser' => $this->request->getPost('nomuser'),
                'passuser' => $this->request->getPost('passuser'),
                'nivelacceso' => $this->request->getPost('nivelacceso'),
                'idpersona' => $this->request->getPost('idpersona')
            ];

            // Usar el método de validación del modelo
            $resultado = $this->usuarioModel->crearUsuarioConValidacion($data);

            if ($resultado['exito']) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => $resultado['mensaje'],
                    'user_id' => $resultado['id']
                ]);
            } else {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => $resultado['mensaje']
                ]);
            }
        }

        // Mostrar formulario de creación
        $data['personas'] = $this->personaModel->findAll();
        return view('usuario/crear', $data);
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

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new Exception('Error en la transacción de base de datos');
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Persona y usuario creados exitosamente',
                    'usuario' => $datosUsuario['nomuser'],
                    'email' => $datosPersona['email'],
                    'persona_id' => $idpersona,
                    'user_id' => $resultado['id']
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
     * Obtener información de matrícula de una persona
     */
    public function infoMatricula($idpersona)
    {
        $matricula = $this->matriculaModel->getMatriculaActiva($idpersona);
        
        if ($matricula) {
            return $this->response->setJSON([
                'status' => 'success',
                'matriculado' => true,
                'matricula' => $matricula
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'info',
                'matriculado' => false,
                'message' => 'La persona no tiene matrícula activa'
            ]);
        }
    }

    /**
     * Listar usuarios con información completa
     */
    public function listar()
    {
        $usuarios = $this->usuarioModel->select('usuarios.*, personas.apellidos, personas.nombres, personas.email')
                                     ->join('personas', 'personas.idpersona = usuarios.idpersona')
                                     ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Obtener usuario completo por ID
     */
    public function obtener($idusuario)
    {
        $usuario = $this->usuarioModel->getUsuarioCompleto($idusuario);
        
        if ($usuario) {
            // Si es estudiante, obtener también la matrícula
            if ($usuario['nivelacceso'] === 'estudiante') {
                $usuario['matricula'] = $this->usuarioModel->getMatriculaUsuario($idusuario);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'usuario' => $usuario
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }
}  