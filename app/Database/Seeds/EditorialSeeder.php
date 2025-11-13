<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EditorialSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['editorial' => 'Editorial Santillana'],
            ['editorial' => 'Editorial Norma'],
            ['editorial' => 'Editorial SM'],
            ['editorial' => 'Penguin Random House'],
            ['editorial' => 'Alfaguara'],
            ['editorial' => 'Planeta'],
            ['editorial' => 'Anaya'],
            ['editorial' => 'McGraw-Hill'],
            ['editorial' => 'Pearson'],
            ['editorial' => 'Oxford University Press'],
        ];

        $this->db->table('editoriales')->insertBatch($data);
    }
}
