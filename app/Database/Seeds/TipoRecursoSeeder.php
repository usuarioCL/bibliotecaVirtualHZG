<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoRecursoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['tiporecurso' => 'Libro físico'],
            ['tiporecurso' => 'Libro digital'],
            ['tiporecurso' => 'Revista física'],
            ['tiporecurso' => 'Revista digital'],
            ['tiporecurso' => 'Manual físico'],
            ['tiporecurso' => 'Manual digital'],
        ];

        $this->db->table('tiporecursos')->insertBatch($data);
    }
}
