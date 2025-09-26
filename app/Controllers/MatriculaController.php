<?php

namespace App\Controllers;

use App\Models\MatriculaModel;
use App\Models\personaModel;
use App\Models\GrupoModel;

class MatriculaController extends BaseController
{
    protected $matriculaModel;
    protected $personaModel;
    protected $grupoModel;
    protected $db;

    public function __construct()
    {
        $this->matriculaModel = new MatriculaModel();
        $this->personaModel = new personaModel();
        $this->grupoModel = new GrupoModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Vista principal de estudiantes matriculados
     */
    public function index()
    {
        try {
            // Usar los nuevos métodos del modelo para obtener datos completos
            $estudiantes = $this->matriculaModel->getMatriculasCompletas();
            
            // Calcular estadísticas
            $estadisticas = $this->matriculaModel->contarPorEstado();
            $por_nivel = $this->matriculaModel->contarPorNivel();
            
            // Procesar estadísticas por nivel
            $estudiantesPrimaria = 0;
            $estudiantesSecundaria = 0;
            
            foreach ($por_nivel as $nivel) {
                switch ($nivel['nivel']) {
                    case 'Primaria':
                        $estudiantesPrimaria = $nivel['cantidad'];
                        break;
                    case 'Secundaria':
                        $estudiantesSecundaria = $nivel['cantidad'];
                        break;
                }
            }
            
            $data = [
                'estudiantes' => $estudiantes,
                'totalEstudiantes' => $estadisticas['total'],
                'estudiantesActivos' => $estadisticas['activas'],
                'estudiantesPrimaria' => $estudiantesPrimaria,
                'estudiantesSecundaria' => $estudiantesSecundaria
            ];

            return view('Administrador/usuarios/estudiantes', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error en MatriculaController::index: ' . $e->getMessage());
            
            $data = [
                'error_message' => 'Error al cargar estudiantes: ' . $e->getMessage(),
                'estudiantes' => [],
                'totalEstudiantes' => 0,
                'estudiantesActivos' => 0,
                'estudiantesPrimaria' => 0,
                'estudiantesSecundaria' => 0
            ];

            return view('Administrador/usuarios/estudiantes', $data);
        }
    }

    /**
     * Obtener detalles de un estudiante
     */
    public function detalle($idmatricula)
    {
        try {
            // Usar el método del modelo para obtener información completa
            $estudiante = $this->matriculaModel->getMatriculaCompleta($idmatricula);

            if (!$estudiante) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado'
                ]);
            }

            // Estadísticas de biblioteca del estudiante
            $estadisticas = $this->obtenerEstadisticasEstudiante($idmatricula);

            return $this->response->setJSON([
                'status' => 'success',
                'estudiante' => $estudiante,
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
     * Obtener estadísticas de biblioteca de un estudiante
     */
    private function obtenerEstadisticasEstudiante($idmatricula)
    {
        $prestamosActivos = $this->db->query("
            SELECT COUNT(*) as count
            FROM prestamos p
            WHERE p.idmatricula = ? AND p.fechadevolucion IS NULL
        ", [$idmatricula])->getRow()->count ?? 0;

        $totalPrestamos = $this->db->query("
            SELECT COUNT(*) as count
            FROM prestamos p
            WHERE p.idmatricula = ?
        ", [$idmatricula])->getRow()->count ?? 0;

        $sancionesActivas = $this->db->query("
            SELECT COUNT(*) as count
            FROM sanciones s
            WHERE s.idmatricula = ? AND s.activa = 1
        ", [$idmatricula])->getRow()->count ?? 0;

        return [
            'prestamos_activos' => $prestamosActivos,
            'total_prestamos' => $totalPrestamos,
            'sanciones_activas' => $sancionesActivas
        ];
    }

    /**
     * Cambiar estado de una matrícula
     */
    public function cambiarEstado()
    {
        try {
            $json = $this->request->getJSON();
            $idmatricula = $json->idmatricula;
            $nuevoEstado = $json->estado === 'true' ? 1 : 0;

            $result = $this->matriculaModel->update($idmatricula, [
                'estadomatricula' => $nuevoEstado
            ]);

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Estado actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar el estado'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Crear nueva matrícula
     */
    public function crear()
    {
        // Obtener grupos disponibles para el formulario
        $grupos = $this->grupoModel->findAll();
        
        return view('Administrador/modals/matricularestudiante', [
            'grupos' => $grupos
        ]);
    }

    /**
     * Guardar nueva matrícula
     */
    public function guardar()
    {
        $this->db->transStart();
        
        try {
            // Verificar si ya existe una persona con el mismo documento
            $numerodoc = $this->request->getPost('numerodoc');
            $personaExistente = $this->personaModel->where('numerodoc', $numerodoc)->first();
            
            if ($personaExistente) {
                // Verificar si ya tiene matrícula activa
                $matriculaExistente = $this->matriculaModel
                    ->where('idpersona', $personaExistente['idpersona'])
                    ->where('estadomatricula', true)
                    ->first();
                    
                if ($matriculaExistente) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Esta persona ya tiene una matrícula activa'
                    ]);
                }
                
                $idpersona = $personaExistente['idpersona'];
            } else {
                // Crear nueva persona
                $personaData = [
                    'nombres' => $this->request->getPost('nombres'),
                    'apellidos' => $this->request->getPost('apellidos'),
                    'numerodoc' => $numerodoc,
                    'email' => $this->request->getPost('email'),
                    'tipodoc' => $this->request->getPost('tipodoc') ?? 'DNI',
                    'genero' => $this->request->getPost('genero') ?? 'No especificado',
                    'telefono' => $this->request->getPost('telefono'),
                    'direccion' => $this->request->getPost('direccion')
                ];

                $idpersona = $this->personaModel->insert($personaData);

                if (!$idpersona) {
                    throw new \Exception('Error al registrar la persona');
                }

                // Crear usuario automáticamente
                $usuarioModel = new \App\Models\UsuarioModel();
                $usuarioExistente = $usuarioModel->where('idpersona', $idpersona)->first();
                
                if (!$usuarioExistente) {
                    // Generar usuario y email automáticamente
                    $nombres_clean = strtolower(trim($this->request->getPost('nombres')));
                    $apellidos_clean = strtolower(trim($this->request->getPost('apellidos')));
                    $primerNombre = explode(' ', $nombres_clean)[0];
                    $primerApellido = explode(' ', $apellidos_clean)[0];
                    $usuario_generado = preg_replace('/[^a-z]/', '', $primerNombre) . '.' . preg_replace('/[^a-z]/', '', $primerApellido);
                    $email_generado = $usuario_generado . '@bibliotecavirtual.edu.pe';
                    
                    $usuarioData = [
                        'nomuser' => $usuario_generado,
                        'passuser' => password_hash('123456', PASSWORD_DEFAULT), // Password temporal
                        'nivelacceso' => 'estudiante',
                        'idpersona' => $idpersona
                    ];

                    if (!$usuarioModel->insert($usuarioData)) {
                        throw new \Exception('Error al crear usuario automático');
                    }
                }
            }

            // Buscar o crear el grupo
            $nivel = $this->request->getPost('nivel');
            $grado = $this->request->getPost('grado');
            $seccion = $this->request->getPost('seccion');
            $aniolectivo = $this->request->getPost('aniolectivo');

            $grupo = $this->grupoModel->where([
                'nivel' => $nivel,
                'grado' => $grado,
                'seccion' => $seccion,
                'aniolectivo' => $aniolectivo
            ])->first();

            // Si no existe el grupo, crearlo
            if (!$grupo) {
                $grupoData = [
                    'nivel' => $nivel,
                    'grado' => $grado,
                    'seccion' => $seccion,
                    'aniolectivo' => $aniolectivo
                ];
                $idgrupo = $this->grupoModel->insert($grupoData);
                if (!$idgrupo) {
                    throw new \Exception('Error al crear el grupo académico');
                }
            } else {
                $idgrupo = $grupo['idgrupo'];
            }

            // Crear la matrícula
            $matriculaData = [
                'idgrupo' => $idgrupo,
                'idpersona' => $idpersona,
                'fechamatricula' => date('Y-m-d'),
                'estadomatricula' => 1
            ];

            $idmatricula = $this->matriculaModel->insert($matriculaData);

            if (!$idmatricula) {
                throw new \Exception('Error al crear la matrícula');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Estudiante matriculado exitosamente (incluye usuario automático)',
                'data' => [
                    'idmatricula' => $idmatricula,
                    'codigo_matricula' => str_pad($idmatricula, 5, '0', STR_PAD_LEFT),
                    'usuario_creado' => !$personaExistente
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
     * Filtrar estudiantes
     */
    public function filtrar()
    {
        $filtros = [
            'nivel' => $this->request->getGet('nivel'),
            'grado' => $this->request->getGet('grado'),
            'seccion' => $this->request->getGet('seccion'),
            'aniolectivo' => $this->request->getGet('aniolectivo'),
            'estado' => $this->request->getGet('estado')
        ];

        $query = "
            SELECT 
                m.idmatricula,
                m.fechamatricula,
                m.estadomatricula,
                p.nombres,
                p.apellidos,
                p.email,
                p.numerodoc,
                g.grado,
                g.seccion,
                g.nivel,
                g.aniolectivo
            FROM matriculas m
            INNER JOIN personas p ON m.idpersona = p.idpersona
            INNER JOIN grupos g ON m.idgrupo = g.idgrupo
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filtros['nivel'])) {
            $query .= " AND g.nivel = ?";
            $params[] = $filtros['nivel'];
        }

        if (!empty($filtros['grado'])) {
            $query .= " AND g.grado = ?";
            $params[] = $filtros['grado'];
        }

        if (!empty($filtros['seccion'])) {
            $query .= " AND g.seccion = ?";
            $params[] = $filtros['seccion'];
        }

        if (!empty($filtros['aniolectivo'])) {
            $query .= " AND g.aniolectivo = ?";
            $params[] = $filtros['aniolectivo'];
        }

        if ($filtros['estado'] !== null && $filtros['estado'] !== '') {
            $query .= " AND m.estadomatricula = ?";
            $params[] = $filtros['estado'];
        }

        $query .= " ORDER BY g.nivel, g.grado, g.seccion, p.apellidos, p.nombres";

        $result = $this->db->query($query, $params);
        $estudiantes = $result->getResultArray();

        $data = ['estudiantes' => $estudiantes];
        
        return view('Administrador/usuarios/estudiantes', $data);
    }

    /**
     * Exportar lista de estudiantes
     */
    public function exportar()
    {
        // Implementar exportación a Excel/PDF
        // Por ahora retornar mensaje informativo
        return $this->response->setJSON([
            'status' => 'info',
            'message' => 'Función de exportación en desarrollo'
        ]);
    }
}