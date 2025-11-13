<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Administrador
            [
                'nomuser' => 'admin',
                'passuser' => password_hash('admin123', PASSWORD_BCRYPT),
                'nivelacceso' => 'admin',
                'idpersona' => 1,
            ],
            
            // Docentes
            [
                'nomuser' => 'docente1',
                'passuser' => password_hash('docente123', PASSWORD_BCRYPT),
                'nivelacceso' => 'docente',
                'idpersona' => 2,
            ],
            [
                'nomuser' => 'docente2',
                'passuser' => password_hash('docente123', PASSWORD_BCRYPT),
                'nivelacceso' => 'docente',
                'idpersona' => 3,
            ],
            
            // Estudiantes
            [
                'nomuser' => 'estudiante1',
                'passuser' => password_hash('estudiante123', PASSWORD_BCRYPT),
                'nivelacceso' => 'estudiante',
                'idpersona' => 4,
            ],
            [
                'nomuser' => 'estudiante2',
                'passuser' => password_hash('estudiante123', PASSWORD_BCRYPT),
                'nivelacceso' => 'estudiante',
                'idpersona' => 5,
            ],
            [
                'nomuser' => 'estudiante3',
                'passuser' => password_hash('estudiante123', PASSWORD_BCRYPT),
                'nivelacceso' => 'estudiante',
                'idpersona' => 6,
            ],
            [
                'nomuser' => 'estudiante4',
                'passuser' => password_hash('estudiante123', PASSWORD_BCRYPT),
                'nivelacceso' => 'estudiante',
                'idpersona' => 7,
            ],
        ];

        $this->db->table('usuarios')->insertBatch($data);
    }
}
