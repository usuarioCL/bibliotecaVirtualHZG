<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AutorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nomautor' => 'Gabriel', 'apeautor' => 'García Márquez'],
            ['nomautor' => 'Mario', 'apeautor' => 'Vargas Llosa'],
            ['nomautor' => 'Isabel', 'apeautor' => 'Allende'],
            ['nomautor' => 'Jorge Luis', 'apeautor' => 'Borges'],
            ['nomautor' => 'Pablo', 'apeautor' => 'Neruda'],
            ['nomautor' => 'Julio', 'apeautor' => 'Cortázar'],
            ['nomautor' => 'Miguel de', 'apeautor' => 'Cervantes'],
            ['nomautor' => 'William', 'apeautor' => 'Shakespeare'],
            ['nomautor' => 'Jane', 'apeautor' => 'Austen'],
            ['nomautor' => 'Charles', 'apeautor' => 'Dickens'],
            ['nomautor' => 'J.K.', 'apeautor' => 'Rowling'],
            ['nomautor' => 'Stephen', 'apeautor' => 'King'],
            ['nomautor' => 'Dan', 'apeautor' => 'Brown'],
            ['nomautor' => 'Agatha', 'apeautor' => 'Christie'],
            ['nomautor' => 'Isaac', 'apeautor' => 'Asimov'],
            ['nomautor' => 'Stephen', 'apeautor' => 'Hawking'],
            ['nomautor' => 'Carl', 'apeautor' => 'Sagan'],
            ['nomautor' => 'Yuval Noah', 'apeautor' => 'Harari'],
        ];

        $this->db->table('autores')->insertBatch($data);
    }
}
