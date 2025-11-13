<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        // Insertar categorías
        $categorias = [
            'Literatura',
            'Ciencias',
            'Historia',
            'Matemáticas',
            'Arte',
            'Tecnología',
            'Idiomas',
            'Filosofía',
        ];

        foreach ($categorias as $categoria) {
            $this->db->table('categorias')->insert(['categoria' => $categoria]);
        }

        // Insertar subcategorías
        $subcategorias = [
            // Literatura
            ['subcategoria' => 'Novela', 'idcategoria' => 1],
            ['subcategoria' => 'Poesía', 'idcategoria' => 1],
            ['subcategoria' => 'Teatro', 'idcategoria' => 1],
            ['subcategoria' => 'Cuento', 'idcategoria' => 1],
            
            // Ciencias
            ['subcategoria' => 'Biología', 'idcategoria' => 2],
            ['subcategoria' => 'Química', 'idcategoria' => 2],
            ['subcategoria' => 'Física', 'idcategoria' => 2],
            ['subcategoria' => 'Astronomía', 'idcategoria' => 2],
            
            // Historia
            ['subcategoria' => 'Historia Universal', 'idcategoria' => 3],
            ['subcategoria' => 'Historia del Perú', 'idcategoria' => 3],
            ['subcategoria' => 'Historia de América', 'idcategoria' => 3],
            
            // Matemáticas
            ['subcategoria' => 'Álgebra', 'idcategoria' => 4],
            ['subcategoria' => 'Geometría', 'idcategoria' => 4],
            ['subcategoria' => 'Trigonometría', 'idcategoria' => 4],
            ['subcategoria' => 'Cálculo', 'idcategoria' => 4],
            
            // Arte
            ['subcategoria' => 'Pintura', 'idcategoria' => 5],
            ['subcategoria' => 'Música', 'idcategoria' => 5],
            ['subcategoria' => 'Escultura', 'idcategoria' => 5],
            
            // Tecnología
            ['subcategoria' => 'Informática', 'idcategoria' => 6],
            ['subcategoria' => 'Programación', 'idcategoria' => 6],
            ['subcategoria' => 'Robótica', 'idcategoria' => 6],
            
            // Idiomas
            ['subcategoria' => 'Inglés', 'idcategoria' => 7],
            ['subcategoria' => 'Francés', 'idcategoria' => 7],
            ['subcategoria' => 'Quechua', 'idcategoria' => 7],
            
            // Filosofía
            ['subcategoria' => 'Ética', 'idcategoria' => 8],
            ['subcategoria' => 'Lógica', 'idcategoria' => 8],
            ['subcategoria' => 'Filosofía Antigua', 'idcategoria' => 8],
        ];

        $this->db->table('subcategorias')->insertBatch($subcategorias);
    }
}
