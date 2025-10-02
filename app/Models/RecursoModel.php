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
        return $this->select('recursos.*, autores.nomautor, subcategorias.subcategoria, categorias.categoria')
            ->join('detautores', 'detautores.idrecurso = recursos.idrecurso', 'left')
            ->join('autores', 'autores.idautor = detautores.idautor', 'left')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->groupStart()
                ->like('recursos.titulo', $query)
                ->orLike('autores.nomautor', $query)
                ->orLike('subcategorias.subcategoria', $query)
                ->orLike('categorias.categoria', $query)
            ->groupEnd()
            ->findAll();
    }

    public function filtrosBusqueda($filtros)
    {
        $builder = $this->select('recursos.*, autores.nomautor, subcategorias.subcategoria, categorias.categoria, editoriales.editorial, tiporecursos.tiporecurso')
            ->join('detautores', 'detautores.idrecurso = recursos.idrecurso', 'left')
            ->join('autores', 'autores.idautor = detautores.idautor', 'left')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left');

        // Si viene un query global, buscar por título, autor, subcategoría o categoría
        if (!empty($filtros['query'])) {
            $builder->groupStart()
                ->like('recursos.titulo', $filtros['query'])
                ->orLike('autores.nomautor', $filtros['query'])
                ->orLike('subcategorias.subcategoria', $filtros['query'])
                ->orLike('categorias.categoria', $filtros['query'])
            ->groupEnd();
        }
        if (!empty($filtros['titulo'])) {
            $builder->like('recursos.titulo', $filtros['titulo']);
        }
        if (!empty($filtros['autor'])) {
            $builder->where('autores.idautor', $filtros['autor']);
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

        return $builder->findAll();
    }

    public function obtenerDetallesCompletos($id)
    {
        return $this->select('recursos.*, autores.nomautor, subcategorias.subcategoria, categorias.categoria, editoriales.editorial, tiporecursos.tiporecurso')
            ->join('detautores', 'detautores.idrecurso = recursos.idrecurso', 'left')
            ->join('autores', 'autores.idautor = detautores.idautor', 'left')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->where('recursos.idrecurso', $id)
            ->first();
    }

    public function obtenerRecursosDestacados($limite = 8)
    {
        return $this->select('recursos.*, autores.nomautor, subcategorias.subcategoria, categorias.categoria, editoriales.editorial, tiporecursos.tiporecurso')
            ->join('detautores', 'detautores.idrecurso = recursos.idrecurso', 'left')
            ->join('autores', 'autores.idautor = detautores.idautor', 'left')
            ->join('subcategorias', 'subcategorias.idsubcategoria = recursos.idsubcategoria', 'left')
            ->join('categorias', 'categorias.idcategoria = subcategorias.idcategoria', 'left')
            ->join('editoriales', 'editoriales.ideditorial = recursos.ideditorial', 'left')
            ->join('tiporecursos', 'tiporecursos.idtiporecurso = recursos.idtiporecurso', 'left')
            ->where('recursos.estado', 'disponible')
            ->orderBy('recursos.idrecurso', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    public function obtenerLibrosPopulares($limite = 6)
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
            rf.portada as rutaportada,
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
            rf.portada as rutaportada,
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
            $recurso['apeautor'] = !empty($nombresAutores) ? $nombresAutores[0] : '';
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
            rf.portada,
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