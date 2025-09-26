<?php

namespace App\Controllers;
use App\Models\personaModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;
use Exception;

class DocenteController extends Controller
{
    protected $personaModel;
    protected $usuarioModel;
    protected $db;

    public function __construct()
    {
        $this->personaModel = new personaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Vista principal de docentes
     */
    public function index()
    {
        try {
            // Obtener docentes (usuarios con nivelacceso = 'docente')
            $docentes = $this->usuarioModel->getUsuariosCompletos('docente');
            
            // Calcular estadísticas
            $totalDocentes = count($docentes);
            $docentesActivos = count($docentes); // Todos los docentes están activos por defecto
            
            // Para estadísticas por nivel, por ahora usaremos datos simulados
            // ya que no tenemos campo de especialidad en las tablas actuales
            $docentesPrimaria = intval($totalDocentes * 0.6); // 60% aprox
            $docentesSecundaria = $totalDocentes - $docentesPrimaria;
            
            $data = [
                'docentes' => $docentes,
                'totalDocentes' => $totalDocentes,
                'docentesActivos' => $docentesActivos,
                'docentesPrimaria' => $docentesPrimaria,
                'docentesSecundaria' => $docentesSecundaria
            ];

            return view('Administrador/usuarios/docentes', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en DocenteController::index: ' . $e->getMessage());
            
            $data = [
                'error_message' => 'Error al cargar docentes: ' . $e->getMessage(),
                'docentes' => [],
                'totalDocentes' => 0,
                'docentesActivos' => 0,
                'docentesPrimaria' => 0,
                'docentesSecundaria' => 0
            ];

            return view('Administrador/usuarios/docentes', $data);
        }
    }

    /**
     * Obtener detalles de un docente
     */
    public function detalle($idusuario)
    {
        try {
            // Obtener información del usuario docente
            $docente = $this->usuarioModel->getUsuarioCompleto($idusuario);

            if (!$docente || $docente['nivelacceso'] !== 'docente') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Docente no encontrado'
                ]);
            }

            // Estadísticas de biblioteca del docente
            $estadisticas = $this->obtenerEstadisticasDocente($idusuario);

            return $this->response->setJSON([
                'status' => 'success',
                'docente' => $docente,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al obtener detalles: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar nuevo docente
     */
    public function guardar()
    {
        $this->db->transStart();
        
        try {
            // Verificar si ya existe una persona con el mismo documento
            $numerodoc = $this->request->getPost('numerodoc');
            $personaExistente = $this->personaModel->where('numerodoc', $numerodoc)->first();
            
            if ($personaExistente) {
                // Verificar si ya es docente (tiene usuario con nivelacceso = 'docente')
                $docenteExistente = $this->usuarioModel
                    ->where('idpersona', $personaExistente['idpersona'])
                    ->where('nivelacceso', 'docente')
                    ->first();
                    
                if ($docenteExistente) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Esta persona ya está registrada como docente'
                    ]);
                }
                
                $idpersona = $personaExistente['idpersona'];
            } else {
                // Crear nueva persona
                $personaData = [
                    'nombres' => $this->request->getPost('nombres'),
                    'apellidos' => $this->request->getPost('apellidos'),
                    'numerodoc' => $numerodoc,
                    'tipodoc' => $this->request->getPost('tipodoc') ?? 'DNI',
                    'genero' => $this->request->getPost('genero'),
                    'telefono' => $this->request->getPost('telefono'),
                    'direccion' => $this->request->getPost('direccion'),
                    'email' => $this->request->getPost('email')
                ];

                $idpersona = $this->personaModel->insert($personaData);

                if (!$idpersona) {
                    throw new \Exception('Error al registrar la persona');
                }
            }

            // Generar usuario automáticamente
            $nombres_clean = strtolower(trim($this->request->getPost('nombres')));
            $apellidos_clean = strtolower(trim($this->request->getPost('apellidos')));
            $primerNombre = explode(' ', $nombres_clean)[0];
            $primerApellido = explode(' ', $apellidos_clean)[0];
            $usuario_generado = preg_replace('/[^a-z]/', '', $primerNombre) . '.' . preg_replace('/[^a-z]/', '', $primerApellido);
            
            // Verificar si el usuario ya existe
            $contador = 1;
            $usuario_final = $usuario_generado;
            while ($this->usuarioModel->where('nomuser', $usuario_final)->first()) {
                $usuario_final = $usuario_generado . $contador;
                $contador++;
            }

            // Crear usuario docente
            $usuarioData = [
                'nomuser' => $usuario_final,
                'passuser' => password_hash('123456', PASSWORD_DEFAULT), // Password temporal
                'nivelacceso' => 'docente',
                'idpersona' => $idpersona
            ];

            $idusuario = $this->usuarioModel->insert($usuarioData);

            if (!$idusuario) {
                throw new \Exception('Error al crear el usuario docente');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Docente registrado exitosamente',
                'datos' => [
                    'idusuario' => $idusuario,
                    'nombres' => $this->request->getPost('nombres'),
                    'apellidos' => $this->request->getPost('apellidos'),
                    'usuario' => $usuario_final
                ]
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar estado de docente - No implementado (sin campo estado)
     */
    public function cambiarEstado()
    {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Funcionalidad de estado no disponible'
        ]);
    }

    /**
     * Buscar persona por DNI para autocompletado
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
            // Buscar persona
            $persona = $this->personaModel->where('numerodoc', $numerodoc)->first();
            
            if ($persona) {
                // Verificar si ya es docente
                $esDocente = $this->usuarioModel
                    ->where('idpersona', $persona['idpersona'])
                    ->where('nivelacceso', 'docente')
                    ->first();
                
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
                        'es_docente' => (bool)$esDocente
                    ]
                ]);
            } else {
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
     * Filtrar docentes
     */
    public function filtrar()
    {
        try {
            $filtros = [
                'buscar' => $this->request->getGet('buscar'),
                'estado' => $this->request->getGet('estado')
            ];

            // Obtener todos los docentes y filtrarlos
            $docentes = $this->usuarioModel->getUsuariosCompletos('docente');
            
            // Aplicar filtros
            if (!empty($filtros['buscar'])) {
                $termino = strtolower($filtros['buscar']);
                $docentes = array_filter($docentes, function($docente) use ($termino) {
                    return strpos(strtolower($docente['nombres']), $termino) !== false ||
                           strpos(strtolower($docente['apellidos']), $termino) !== false ||
                           strpos(strtolower($docente['numerodoc']), $termino) !== false ||
                           strpos(strtolower($docente['nomuser'] ?? ''), $termino) !== false;
                });
            }

            return $this->response->setJSON([
                'status' => 'success',
                'docentes' => array_values($docentes) // Reindexar el array
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al filtrar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas de biblioteca de un docente
     */
    private function obtenerEstadisticasDocente($idusuario)
    {
        try {
            // Por ahora retornamos datos de ejemplo
            // Más adelante se puede conectar con las tablas de préstamos
            return [
                'prestamos_activos' => 0,
                'total_prestamos' => 0,
                'recursos_favoritos' => 0,
                'comentarios' => 0
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error calculando estadísticas de docente: ' . $e->getMessage());
            return [
                'prestamos_activos' => 0,
                'total_prestamos' => 0,
                'recursos_favoritos' => 0,
                'comentarios' => 0
            ];
        }
    }
}