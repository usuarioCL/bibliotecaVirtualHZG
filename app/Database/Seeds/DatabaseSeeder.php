<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder principal que ejecuta todos los seeders
 * Uso: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Orden de ejecución (respetando dependencias)
        $seeders = [
            'TipoRecursoSeeder',
            'CategoriaSeeder',
            'EditorialSeeder',
            'AutorSeeder',
            'PersonaSeeder',
            'GrupoSeeder',           
            'UsuarioSeeder',
            'MatriculaSeeder',       
            'RecursoSeeder',
            'SancionSeeder',
        ];
        
        foreach ($seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
