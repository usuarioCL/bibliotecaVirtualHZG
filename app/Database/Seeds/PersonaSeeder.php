<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Administrador
            [
                'apellidos' => 'Rodríguez García',
                'nombres' => 'Carlos Alberto',
                'tipodoc' => 'DNI',
                'numerodoc' => '12345678',
                'telefono' => '987654321',
                'direccion' => 'Av. Principal 123, Lima',
                'email' => 'admin@biblioteca.com',
                'genero' => 'Masculino',
            ],
            
            // Docentes
            [
                'apellidos' => 'Fernández López',
                'nombres' => 'María Elena',
                'tipodoc' => 'DNI',
                'numerodoc' => '23456789',
                'telefono' => '987654322',
                'direccion' => 'Jr. Los Olivos 456, Lima',
                'email' => 'docente1@biblioteca.com',
                'genero' => 'Femenino',
            ],
            [
                'apellidos' => 'Torres Mendoza',
                'nombres' => 'Juan Carlos',
                'tipodoc' => 'DNI',
                'numerodoc' => '34567890',
                'telefono' => '987654323',
                'direccion' => 'Av. La Marina 789, Lima',
                'email' => 'docente2@biblioteca.com',
                'genero' => 'Masculino',
            ],
            
            // Estudiantes
            [
                'apellidos' => 'Ramírez Silva',
                'nombres' => 'Ana María',
                'tipodoc' => 'DNI',
                'numerodoc' => '45678901',
                'telefono' => '987654324',
                'direccion' => 'Calle Las Flores 321, Lima',
                'email' => 'estudiante1@biblioteca.com',
                'genero' => 'Femenino',
            ],
            [
                'apellidos' => 'Gonzales Pérez',
                'nombres' => 'Luis Miguel',
                'tipodoc' => 'DNI',
                'numerodoc' => '56789012',
                'telefono' => '987654325',
                'direccion' => 'Jr. San Martín 654, Lima',
                'email' => 'estudiante2@biblioteca.com',
                'genero' => 'Masculino',
            ],
            [
                'apellidos' => 'Chávez Rojas',
                'nombres' => 'Sofía Isabel',
                'tipodoc' => 'DNI',
                'numerodoc' => '67890123',
                'telefono' => '987654326',
                'direccion' => 'Av. Universitaria 987, Lima',
                'email' => 'estudiante3@biblioteca.com',
                'genero' => 'Femenino',
            ],
            [
                'apellidos' => 'Vega Castro',
                'nombres' => 'Diego Andrés',
                'tipodoc' => 'DNI',
                'numerodoc' => '78901234',
                'telefono' => '987654327',
                'direccion' => 'Calle Los Pinos 147, Lima',
                'email' => 'estudiante4@biblioteca.com',
                'genero' => 'Masculino',
            ],
        ];

        $this->db->table('personas')->insertBatch($data);
    }
}
