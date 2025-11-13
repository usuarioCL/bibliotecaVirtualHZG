<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        // Primero verificar que existen grupos
        $grupos = $this->db->table('grupos')->get()->getResult();
        if (empty($grupos)) {
            return;
        }

        // Obtener el número de grupos disponibles
        $numGrupos = count($grupos);

        $data = [
            // Matrícula para admin (idpersona = 1)
            [
                'idgrupo' => 1,
                'idpersona' => 1,
                'fechamatricula' => '2025-03-01',
                'estadomatricula' => true,
            ],
            
            // Matrículas para docentes
            [
                'idgrupo' => min(2, $numGrupos),
                'idpersona' => 2,
                'fechamatricula' => '2025-03-02',
                'estadomatricula' => true,
            ],
            [
                'idgrupo' => min(3, $numGrupos),
                'idpersona' => 3,
                'fechamatricula' => '2025-03-03',
                'estadomatricula' => true,
            ],
            
            // Matrículas para estudiantes (idpersona 4-7)
            [
                'idgrupo' => min(4, $numGrupos),
                'idpersona' => 4,
                'fechamatricula' => '2025-03-04',
                'estadomatricula' => true,
            ],
            [
                'idgrupo' => min(1, $numGrupos), // Ciclo de grupos
                'idpersona' => 5,
                'fechamatricula' => '2025-03-05',
                'estadomatricula' => true,
            ],
            [
                'idgrupo' => min(2, $numGrupos),
                'idpersona' => 6,
                'fechamatricula' => '2025-03-06',
                'estadomatricula' => true,
            ],
            [
                'idgrupo' => min(3, $numGrupos),
                'idpersona' => 7,
                'fechamatricula' => '2025-03-07',
                'estadomatricula' => true,
            ],
        ];

        // Limpiar matrículas existentes (opcional, comentar si no deseas esto)
        // $this->db->table('matriculas')->truncate();

        // Insertar o actualizar matrículas
        foreach ($data as $matricula) {
            // Verificar si ya existe una matrícula para esta persona
            $existe = $this->db->table('matriculas')
                ->where('idpersona', $matricula['idpersona'])
                ->get()
                ->getRow();

            if ($existe) {
                // Actualizar la matrícula existente
                $this->db->table('matriculas')
                    ->where('idpersona', $matricula['idpersona'])
                    ->update([
                        'idgrupo' => $matricula['idgrupo'],
                        'fechamatricula' => $matricula['fechamatricula'],
                        'estadomatricula' => $matricula['estadomatricula']
                    ]);
                
            } else {
                // Insertar nueva matrícula
                $this->db->table('matriculas')->insert($matricula);
                
            }
        }

        
    }
}
