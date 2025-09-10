<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'idusuario';
    protected $allowedFields = [
        'nomuser',
        'passuser',
        'nivelacceso', // Corregido: debe ser nivelacceso (minúscula según la BD)
        'idpersona'
    ];

    protected $validationRules = [
        'nomuser' => 'required|min_length[3]|max_length[30]|is_unique[usuarios.nomuser]',
        'passuser' => 'required|min_length[6]',
        'nivelacceso' => 'required|in_list[admin,docente,estudiante]',
        'idpersona' => 'required|integer|is_unique[usuarios.idpersona]'
    ];

    protected $validationMessages = [
        'nomuser' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'max_length' => 'El nombre de usuario no puede exceder 30 caracteres',
            'is_unique' => 'Este nombre de usuario ya existe'
        ],
        'passuser' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'nivelacceso' => [
            'required' => 'El nivel de acceso es obligatorio',
            'in_list' => 'El nivel de acceso debe ser: admin, docente o estudiante'
        ],
        'idpersona' => [
            'required' => 'La persona es obligatoria',
            'integer' => 'El ID de persona debe ser un número entero',
            'is_unique' => 'Esta persona ya tiene un usuario asignado'
        ]
    ];

    /**
     * Valida si una persona puede crear un usuario según su matrícula y nivel de acceso
     * @param int $idpersona
     * @param string $nivelacceso
     * @return array ['valido' => bool, 'mensaje' => string]
     */
    public function validarElegibilidadUsuario($idpersona, $nivelacceso)
    {
        $personaModel = new \App\Models\PersonaModel();
        $matriculaModel = new \App\Models\MatriculaModel();

        // Verificar que la persona existe
        $persona = $personaModel->find($idpersona);
        if (!$persona) {
            return [
                'valido' => false,
                'mensaje' => 'La persona especificada no existe en el sistema'
            ];
        }

        // Verificar que la persona no tenga ya un usuario
        $usuarioExistente = $this->where('idpersona', $idpersona)->first();
        if ($usuarioExistente) {
            return [
                'valido' => false,
                'mensaje' => 'Esta persona ya tiene un usuario registrado'
            ];
        }

        switch ($nivelacceso) {
            case 'estudiante':
                // Los estudiantes deben estar matriculados
                if (!$matriculaModel->personaEstaMatriculada($idpersona)) {
                    return [
                        'valido' => false,
                        'mensaje' => 'Solo estudiantes matriculados pueden crear usuarios de tipo estudiante'
                    ];
                }
                break;

            case 'docente':
                // Los docentes deben existir en la tabla personas
                // Aquí podrías agregar validaciones adicionales como verificar en una tabla de empleados
                if (!$persona) {
                    return [
                        'valido' => false,
                        'mensaje' => 'Solo personal docente puede crear usuarios de tipo docente'
                    ];
                }
                break;

            case 'admin':
                // Los administradores requieren validación especial
                // Solo otros administradores deberían poder crear usuarios admin
                return [
                    'valido' => false,
                    'mensaje' => 'La creación de usuarios administradores requiere permisos especiales'
                ];

            default:
                return [
                    'valido' => false,
                    'mensaje' => 'Nivel de acceso no válido'
                ];
        }

        return [
            'valido' => true,
            'mensaje' => 'La persona es elegible para crear un usuario'
        ];
    }

    /**
     * Crea un usuario validando primero la elegibilidad
     * @param array $data
     * @return array ['exito' => bool, 'mensaje' => string, 'id' => int|null]
     */
    public function crearUsuarioConValidacion($data)
    {
        // Validar elegibilidad antes de crear
        $validacion = $this->validarElegibilidadUsuario($data['idpersona'], $data['nivelacceso']);
        
        if (!$validacion['valido']) {
            return [
                'exito' => false,
                'mensaje' => $validacion['mensaje'],
                'id' => null
            ];
        }

        // Encriptar contraseña
        if (isset($data['passuser'])) {
            $data['passuser'] = password_hash($data['passuser'], PASSWORD_DEFAULT);
        }

        // Intentar crear el usuario
        if ($this->insert($data)) {
            return [
                'exito' => true,
                'mensaje' => 'Usuario creado exitosamente',
                'id' => $this->getInsertID()
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'Error al crear el usuario: ' . implode(', ', $this->errors()),
                'id' => null
            ];
        }
    }

    /**
     * Obtiene información completa del usuario incluyendo datos de la persona
     * @param int $idusuario
     * @return array|null
     */
    public function getUsuarioCompleto($idusuario)
    {
        return $this->select('usuarios.*, personas.apellidos, personas.nombres, personas.email, personas.tipodoc, personas.numerodoc')
                    ->join('personas', 'personas.idpersona = usuarios.idpersona')
                    ->where('usuarios.idusuario', $idusuario)
                    ->first();
    }

    /**
     * Obtiene la matrícula de un usuario si es estudiante
     * @param int $idusuario
     * @return array|null
     */
    public function getMatriculaUsuario($idusuario)
    {
        $usuario = $this->find($idusuario);
        if (!$usuario || $usuario['nivelacceso'] !== 'estudiante') {
            return null;
        }

        $matriculaModel = new \App\Models\MatriculaModel();
        return $matriculaModel->getMatriculaActiva($usuario['idpersona']);
    }
}