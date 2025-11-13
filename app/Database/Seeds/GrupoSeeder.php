<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Primaria
            ['aniolectivo' => 2025, 'grado' => '1', 'seccion' => 'A', 'nivel' => 'Primaria'],
            ['aniolectivo' => 2025, 'grado' => '2', 'seccion' => 'A', 'nivel' => 'Primaria'],
            ['aniolectivo' => 2025, 'grado' => '3', 'seccion' => 'A', 'nivel' => 'Primaria'],
            ['aniolectivo' => 2025, 'grado' => '4', 'seccion' => 'A', 'nivel' => 'Primaria'],
            ['aniolectivo' => 2025, 'grado' => '5', 'seccion' => 'A', 'nivel' => 'Primaria'],
            ['aniolectivo' => 2025, 'grado' => '6', 'seccion' => 'A', 'nivel' => 'Primaria'],
            
            // Secundaria
            ['aniolectivo' => 2025, 'grado' => '1', 'seccion' => 'A', 'nivel' => 'Secundaria'],
            ['aniolectivo' => 2025, 'grado' => '2', 'seccion' => 'A', 'nivel' => 'Secundaria'],
            ['aniolectivo' => 2025, 'grado' => '3', 'seccion' => 'A', 'nivel' => 'Secundaria'],
            ['aniolectivo' => 2025, 'grado' => '4', 'seccion' => 'A', 'nivel' => 'Secundaria'],
            ['aniolectivo' => 2025, 'grado' => '5', 'seccion' => 'A', 'nivel' => 'Secundaria'],
        ];

        $this->db->table('grupos')->insertBatch($data);
    }
}
