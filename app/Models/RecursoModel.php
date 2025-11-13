<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Node\Expr\FuncCall;

class RecursoModel extends Model
{
    protected $table            = 'recursos';
    protected $primaryKey       = 'idrecurso';

    protected $allowedFields    = [
        'titulo',
        'anio',
        'numpaginas',
        'isbn',
        'numedicion',
        'estado',
        'stock',
        'nivel',
        'idsubcategoria',
        'ideditorial',
        'idtiporecurso'
    ];

    public function buscarRecursos($query)
    {
        // Primero obtener los recursos sin autores para evitar duplicados
        $recursos = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            ->groupStart()
                ->like('recursos.titulo', $query)
                ->orLike('subcategorias.subcategoria', $query)
                ->orLike('categorias.categoria', $query)
            ->groupEnd()
            ->findAll();
        
        // Filtrar por autores si es necesario
        $recursosFiltrados = [];
        foreach ($recursos as $recurso) {
            // Buscar si el query coincide con algún autor del recurso
            $autores = $this->db->table('detautores da')
                ->select('a.nomautor, a.apeautor')
                ->join('autores a', 'a.idautor = da.idautor')
                ->where('da.idrecurso', $recurso['idrecurso'])
                ->get()
                ->getResultArray();
            
            $coincideAutor = false;
            foreach ($autores as $autor) {
                $nombreCompleto = trim($autor['apeautor'] . ' ' . $autor['nomautor']);
                if (stripos($nombreCompleto, $query) !== false) {
                    $coincideAutor = true;
                    break;
                }
            }
            
            // Si coincide con título, categoría, subcategoría o autor, incluir el recurso
            if ($coincideAutor || 
                stripos($recurso['titulo'], $query) !== false ||
                stripos($recurso['subcategoria'], $query) !== false ||
                stripos($recurso['categoria'], $query) !== false) {
                $recursosFiltrados[] = $recurso;
            }
        }
        
        // Agregar autores y eliminar duplicados
        $recursosFiltrados = $this->agregarAutoresARecursos($recursosFiltrados);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursosFiltrados);
        
        return $recursosUnicos;
    }

    public function filtrosBusqueda($filtros)
    {
        // Primero obtener los recursos sin autores para evitar duplicados
        $builder = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left');

        // Aplicar filtros básicos
        if (!empty($filtros['titulo'])) {
            $builder->like('recursos.titulo', $filtros['titulo']);
        }
        if (!empty($filtros['categoria'])) {
            $builder->where('categorias.idcategoria', $filtros['categoria']);
        }
        if (!empty($filtros['subcategoria'])) {
            $builder->where('subcategorias.idsubcategoria', $filtros['subcategoria']);
        }
        if (!empty($filtros['editorial'])) {
            $builder->where('editoriales.ideditorial', $filtros['editorial']);
        }
        if (!empty($filtros['anio'])) {
            $builder->where('recursos.anio', $filtros['anio']);
        }
        if (!empty($filtros['tiporecurso'])) {
            $builder->where('tiporecursos.idtiporecurso', $filtros['tiporecurso']);
        }
        if (!empty($filtros['estado'])) {
            $builder->where('recursos.estado', $filtros['estado']);
        }

        // Si viene un query global, buscar por título, subcategoría o categoría
        if (!empty($filtros['query'])) {
            $builder->groupStart()
                ->like('recursos.titulo', $filtros['query'])
                ->orLike('subcategorias.subcategoria', $filtros['query'])
                ->orLike('categorias.categoria', $filtros['query'])
            ->groupEnd();
        }

        $recursos = $builder->findAll();
        
        // Filtrar por autor si es necesario
        if (!empty($filtros['autor'])) {
            $recursosFiltrados = [];
            foreach ($recursos as $recurso) {
                $autores = $this->db->table('detautores da')
                    ->select('a.idautor')
                    ->join('autores a', 'a.idautor = da.idautor')
                    ->where('da.idrecurso', $recurso['idrecurso'])
                    ->get()
                    ->getResultArray();
                
                foreach ($autores as $autor) {
                    if ($autor['idautor'] == $filtros['autor']) {
                        $recursosFiltrados[] = $recurso;
                        break;
                    }
                }
            }
            $recursos = $recursosFiltrados;
        }
        
        // Filtrar por query global en autores si es necesario
        if (!empty($filtros['query'])) {
            $recursosFiltrados = [];
            foreach ($recursos as $recurso) {
                $autores = $this->db->table('detautores da')
                    ->select('a.nomautor, a.apeautor')
                    ->join('autores a', 'a.idautor = da.idautor')
                    ->where('da.idrecurso', $recurso['idrecurso'])
                    ->get()
                    ->getResultArray();
                
                $coincideAutor = false;
                foreach ($autores as $autor) {
                    $nombreCompleto = trim($autor['apeautor'] . ' ' . $autor['nomautor']);
                    if (stripos($nombreCompleto, $filtros['query']) !== false) {
                        $coincideAutor = true;
                        break;
                    }
                }
                
                // Si coincide con título, categoría, subcategoría o autor, incluir el recurso
                if ($coincideAutor || 
                    stripos($recurso['titulo'], $filtros['query']) !== false ||
                    stripos($recurso['subcategoria'], $filtros['query']) !== false ||
                    stripos($recurso['categoria'], $filtros['query']) !== false) {
                    $recursosFiltrados[] = $recurso;
                }
            }
            $recursos = $recursosFiltrados;
        }
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursosUnicos;
    }

    public function obtenerDetallesCompletos($id)
    {
        // Primero obtener los datos básicos del recurso sin autores, incluyendo portada
        $recurso = $this->select('recursos.*, subcategorias.subcategoria, categorias.categoria, editoriales.editorial, tiporecursos.tiporecurso, COALESCE(rf.portada, rd.portada) as portada, rd.archivo')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            ->where('recursos.idrecurso', $id)
            ->first();
        
        if (!$recurso) {
            return null;
        }
        
        // Obtener todos los autores del recurso
        $autores = $this->db->table('detautores da')
            ->select('a.nomautor, a.apeautor')
            ->join('autores a', 'a.idautor = da.idautor')
            ->where('da.idrecurso', $id)
            ->get()
            ->getResultArray();
        
        // Concatenar todos los autores
        $nombresAutores = [];
        foreach ($autores as $autor) {
            $nombreCompleto = trim($autor['apeautor'] . ' ' . $autor['nomautor']);
            if (!empty($nombreCompleto)) {
                $nombresAutores[] = $nombreCompleto;
            }
        }
        
        $recurso['nomautor'] = implode(', ', $nombresAutores);
        
        return $recurso;
    }

    public function obtenerRecursosDestacados($limite = 8)
    {
        // Primero obtener los recursos sin autores para evitar duplicados
        $recursos = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            ->where('recursos.estado', 'disponible')
            ->orderBy('recursos.idrecurso', 'DESC')
            ->limit($limite)
            ->findAll();
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursosUnicos;
    }

    public function obtenerRecursosRecientes($limite = 12)
    {
        // Obtener los recursos agregados recientemente ordenados por ID descendente
        $recursos = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            ->orderBy('recursos.idrecurso', 'DESC')
            ->limit($limite)
            ->findAll();
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursosUnicos;
    }

    public function obtenerLibrosPopulares($limite = 6)
    {
        // Obtener recursos con conteo de préstamos y favoritos
        $recursos = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo,
            (COALESCE(COUNT(DISTINCT p.idprestamo), 0) + COALESCE(COUNT(DISTINCT f.idfavorito), 0)) as popularidad
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            ->join('prestamos p', 'p.idrecurso = recursos.idrecurso', 'left')
            ->join('favoritos f', 'f.idrecurso = recursos.idrecurso', 'left')
            ->groupBy('recursos.idrecurso, recursos.titulo, recursos.anio, recursos.numpaginas, recursos.isbn, recursos.numedicion, recursos.estado, recursos.stock, recursos.nivel, recursos.idsubcategoria, recursos.ideditorial, recursos.idtiporecurso, subcategorias.subcategoria, categorias.categoria, editoriales.editorial, tiporecursos.tiporecurso, rf.portada, rd.portada, rd.archivo')
            ->orderBy('popularidad', 'DESC')
            ->orderBy('recursos.anio', 'DESC')
            ->limit($limite)
            ->findAll();
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursosUnicos;
    }

    public function obtenerTodosLosRecursos()
    {
        // Primero obtener los recursos sin autores para evitar duplicados
        $recursos = $this->select('
            recursos.idrecurso,
            recursos.titulo,
            recursos.anio,
            recursos.numpaginas,
            recursos.isbn,
            recursos.numedicion,
            recursos.estado,
            recursos.stock,
            recursos.nivel,
            recursos.idsubcategoria,
            recursos.ideditorial,
            recursos.idtiporecurso,
            subcategorias.subcategoria, 
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
            ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
            // Sin filtro de estado para mostrar todos los recursos
            ->orderBy('recursos.anio', 'DESC')
            ->orderBy('recursos.idrecurso', 'DESC')
            ->findAll();
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursosUnicos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursosUnicos;
    }

    /**
     * Método auxiliar para agregar autores a los recursos
     */
    private function agregarAutoresARecursos($recursos)
    {
        foreach ($recursos as &$recurso) {
            $autores = $this->db->table('detautores da')
                ->select('a.nomautor, a.apeautor')
                ->join('autores a', 'a.idautor = da.idautor')
                ->where('da.idrecurso', $recurso['idrecurso'])
                ->get()
                ->getResultArray();
            
            // Concatenar todos los autores en un solo string
            $nombresAutores = [];
            foreach ($autores as $autor) {
                $nombreCompleto = trim($autor['apeautor'] . ' ' . $autor['nomautor']);
                if (!empty($nombreCompleto)) {
                    $nombresAutores[] = $nombreCompleto;
                }
            }
            $recurso['nomautor'] = implode(', ', $nombresAutores);
            // No asignar apeautor para evitar duplicados en la vista
        }
        return $recursos;
    }

    /**
     * Método auxiliar para eliminar duplicados por ID
     */
    private function eliminarDuplicadosPorId($recursos)
    {
        $recursosUnicos = [];
        $idsVistos = [];
        foreach ($recursos as $recurso) {
            if (!in_array($recurso['idrecurso'], $idsVistos)) {
                $recursosUnicos[] = $recurso;
                $idsVistos[] = $recurso['idrecurso'];
            }
        }
        return $recursosUnicos;
    }

    public function obtenerRecursosCompletos()
    {
        // Primero obtener los recursos sin autores para evitar duplicados
        $recursos = $this->select('
            recursos.*,
            subcategorias.subcategoria,
            categorias.categoria,
            editoriales.editorial,
            tiporecursos.tiporecurso,
            rf.encuadernacion,
            COALESCE(rf.portada, rd.portada) as portada,
            rd.archivo
        ')
        ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
        ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
        ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
        ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
        ->join('recursos_fisicos rf', 'rf.idrecurso = recursos.idrecurso', 'left')
        ->join('recursos_digitales rd', 'rd.idrecurso = recursos.idrecurso', 'left')
        ->orderBy('recursos.idrecurso', 'ASC')
        ->findAll();
        
        // Agregar autores y eliminar duplicados
        $recursos = $this->agregarAutoresARecursos($recursos);
        $recursos = $this->eliminarDuplicadosPorId($recursos);
        
        return $recursos;
    }
}