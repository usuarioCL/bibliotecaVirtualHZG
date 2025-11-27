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
        'nivelacceso',
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
        $personaModel = new \App\Models\personaModel();
        $matriculaModel = new \App\Models\MatriculaModel();

        // Verificar que la persona existe
        $persona = $personaModel->find($idpersona);
        // Verificar que la persona no tenga ya un usuario
        $usuarioExistente = $this->where('idpersona', $idpersona)->first();
        if ($usuarioExistente) {
            return [
                'valido' => false,
                'mensaje' => 'Esta persona ya tiene un usuario registrado'
            ];
        }

        // Si todas las validaciones pasan, retornar éxito
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
            $passwordOriginal = $data['passuser'];
            $data['passuser'] = password_hash($data['passuser'], PASSWORD_DEFAULT);
            
            // LOG: Contraseña hasheada
            log_message('info', '=== HASHEO DE CONTRASEÑA ===');
            log_message('info', 'Contraseña original: ' . $passwordOriginal);
            log_message('info', 'Contraseña hasheada: ' . $data['passuser']);
            log_message('info', 'Algoritmo: PASSWORD_DEFAULT');
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
        return $this->select('usuarios.*, personas.apellidos, personas.nombres, personas.email, personas.tipodoc, personas.numerodoc, personas.telefono, personas.direccion, personas.genero')
                    ->join('personas', 'personas.idpersona = usuarios.idpersona')
                    ->where('usuarios.idusuario', $idusuario)
                    ->first();
    }

    /**
     * Obtiene todos los usuarios por nivel de acceso con información completa
     * @param string $nivelacceso
     * @return array
     */
    public function getUsuariosCompletos($nivelacceso = null)
    {
        $builder = $this->select('usuarios.*, personas.apellidos, personas.nombres, personas.email, personas.tipodoc, personas.numerodoc, personas.telefono, personas.direccion, personas.genero')
                        ->join('personas', 'personas.idpersona = usuarios.idpersona');
                        
        if ($nivelacceso) {
            $builder->where('usuarios.nivelacceso', $nivelacceso);
        }
        
        return $builder->orderBy('personas.apellidos', 'ASC')
                      ->findAll();
    }

}