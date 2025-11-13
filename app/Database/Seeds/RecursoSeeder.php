<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RecursoSeeder extends Seeder
{
    public function run()
    {
        // ===============================================
        // LIBROS FÍSICOS
        // ===============================================
        
        $recursosFisicos = [
            // Literatura - Novelas
            [
                'titulo' => 'Cien Años de Soledad',
                'anio' => 1967,
                'numpaginas' => 471,
                'isbn' => '9788497592208',
                'numedicion' => '1era edición',
                'estado' => 'disponible',
                'stock' => 5,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 1, // Novela
                'ideditorial' => 4, // Penguin Random House
                'idtiporecurso' => 1, // Libro físico
                'idautor' => 1, // García Márquez
                'portada' => null,
                'encuadernacion' => 'Tapa dura',
            ],
            [
                'titulo' => 'La Casa Verde',
                'anio' => 1966,
                'numpaginas' => 392,
                'isbn' => '9788420482729',
                'numedicion' => '1era edición',
                'estado' => 'disponible',
                'stock' => 3,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 1,
                'ideditorial' => 5, // Alfaguara
                'idtiporecurso' => 1,
                'idautor' => 2, // Vargas Llosa
                'portada' => null,
                'encuadernacion' => 'Tapa blanda',
            ],
            [
                'titulo' => 'Don Quijote de la Mancha',
                'anio' => 1605,
                'numpaginas' => 863,
                'isbn' => '9788424116941',
                'numedicion' => '2da edición',
                'estado' => 'disponible',
                'stock' => 10,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 1,
                'ideditorial' => 1, // Santillana
                'idtiporecurso' => 1,
                'idautor' => 7, // Cervantes
                'portada' => null,
                'encuadernacion' => 'Tapa dura',
            ],
            
            // Ciencias
            [
                'titulo' => 'Biología General',
                'anio' => 2020,
                'numpaginas' => 524,
                'isbn' => '9788448612559',
                'numedicion' => '12va edición',
                'estado' => 'disponible',
                'stock' => 8,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 5, // Biología
                'ideditorial' => 8, // McGraw-Hill
                'idtiporecurso' => 1,
                'idautor' => 16, // Hawking
                'portada' => null,
                'encuadernacion' => 'Tapa dura',
            ],
            [
                'titulo' => 'Cosmos',
                'anio' => 1980,
                'numpaginas' => 366,
                'isbn' => '9788408093046',
                'numedicion' => '1era edición',
                'estado' => 'disponible',
                'stock' => 4,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 8, // Astronomía
                'ideditorial' => 6, // Planeta
                'idtiporecurso' => 1,
                'idautor' => 17, // Carl Sagan
                'portada' => null,
                'encuadernacion' => 'Tapa dura',
            ],
            
            // Matemáticas
            [
                'titulo' => 'Álgebra de Baldor',
                'anio' => 1941,
                'numpaginas' => 586,
                'isbn' => '9789708100106',
                'numedicion' => 'Edición renovada',
                'estado' => 'disponible',
                'stock' => 15,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 12, // Álgebra
                'ideditorial' => 9, // Pearson
                'idtiporecurso' => 1,
                'idautor' => 15, // Asimov
                'portada' => null,
                'encuadernacion' => 'Tapa dura',
            ],
            [
                'titulo' => 'Geometría y Trigonometría',
                'anio' => 2019,
                'numpaginas' => 432,
                'isbn' => '9786073238243',
                'numedicion' => '8va edición',
                'estado' => 'disponible',
                'stock' => 12,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 13, // Geometría
                'ideditorial' => 9, // Pearson
                'idtiporecurso' => 1,
                'idautor' => 15,
                'portada' => null,
                'encuadernacion' => 'Tapa blanda',
            ],
            
            // Historia
            [
                'titulo' => 'Sapiens: De animales a dioses',
                'anio' => 2014,
                'numpaginas' => 496,
                'isbn' => '9788499926223',
                'numedicion' => '1era edición',
                'estado' => 'disponible',
                'stock' => 6,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 9, // Historia Universal
                'ideditorial' => 6, // Planeta
                'idtiporecurso' => 1,
                'idautor' => 18, // Yuval Noah Harari
                'portada' => null,
                'encuadernacion' => 'Tapa blanda',
            ],
        ];
        
        // ===============================================
        // LIBROS DIGITALES
        // ===============================================
        
        $recursosDigitales = [
            [
                'titulo' => 'El Principito',
                'anio' => 1943,
                'numpaginas' => 96,
                'isbn' => '9788498381498',
                'numedicion' => 'Edición digital',
                'estado' => 'disponible',
                'stock' => 0, // Los digitales no tienen stock
                'nivel' => 'Primaria',
                'idsubcategoria' => 4, // Cuento
                'ideditorial' => 5, // Alfaguara
                'idtiporecurso' => 2, // Libro digital
                'idautor' => 3, // Isabel Allende (ejemplo)
                'portada' => null,
                'archivo' => null,
            ],
            [
                'titulo' => 'Introducción a la Programación con Python',
                'anio' => 2021,
                'numpaginas' => 320,
                'isbn' => '9781234567890',
                'numedicion' => '3era edición',
                'estado' => 'disponible',
                'stock' => 0,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 20, // Programación
                'ideditorial' => 10, // Oxford
                'idtiporecurso' => 2,
                'idautor' => 15, // Asimov
                'portada' => null,
                'archivo' => null,
            ],
            [
                'titulo' => 'Poemas Selectos de Pablo Neruda',
                'anio' => 1974,
                'numpaginas' => 215,
                'isbn' => '9789562391276',
                'numedicion' => 'Edición digital',
                'estado' => 'disponible',
                'stock' => 0,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 2, // Poesía
                'ideditorial' => 5, // Alfaguara
                'idtiporecurso' => 2,
                'idautor' => 5, // Pablo Neruda
                'portada' => null,
                'archivo' => null,
            ],
            [
                'titulo' => 'Inglés Básico - Guía Interactiva',
                'anio' => 2022,
                'numpaginas' => 180,
                'isbn' => '9781234567891',
                'numedicion' => '1era edición',
                'estado' => 'disponible',
                'stock' => 0,
                'nivel' => 'Primaria',
                'idsubcategoria' => 22, // Inglés
                'ideditorial' => 10, // Oxford
                'idtiporecurso' => 2,
                'idautor' => 9, // Jane Austen
                'portada' => null,
                'archivo' => null,
            ],
            [
                'titulo' => 'Historia del Arte Moderno',
                'anio' => 2020,
                'numpaginas' => 275,
                'isbn' => '9781234567892',
                'numedicion' => '2da edición',
                'estado' => 'disponible',
                'stock' => 0,
                'nivel' => 'Secundaria',
                'idsubcategoria' => 16, // Pintura
                'ideditorial' => 7, // Anaya
                'idtiporecurso' => 2,
                'idautor' => 14, // Agatha Christie
                'portada' => null,
                'archivo' => null,
            ],
        ];
        
        // Combinar todos los recursos
        $todosRecursos = array_merge($recursosFisicos, $recursosDigitales);
        
        // Insertar recursos y sus relaciones
        foreach ($todosRecursos as $recurso) {
            // Extraer datos específicos
            $idautor = $recurso['idautor'];
            $portada = $recurso['portada'] ?? null;
            $esDigital = $recurso['idtiporecurso'] == 2;
            
            // Datos para tabla recursos
            $dataRecurso = [
                'titulo' => $recurso['titulo'],
                'anio' => $recurso['anio'],
                'numpaginas' => $recurso['numpaginas'],
                'isbn' => $recurso['isbn'],
                'numedicion' => $recurso['numedicion'],
                'estado' => $recurso['estado'],
                'stock' => $recurso['stock'],
                'nivel' => $recurso['nivel'],
                'idsubcategoria' => $recurso['idsubcategoria'],
                'ideditorial' => $recurso['ideditorial'],
                'idtiporecurso' => $recurso['idtiporecurso'],
            ];
            
            // Insertar recurso
            $this->db->table('recursos')->insert($dataRecurso);
            $idrecurso = $this->db->insertID();
            
            // Insertar relación autor-recurso
            $this->db->table('detautores')->insert([
                'idautor' => $idautor,
                'idrecurso' => $idrecurso,
            ]);
            
            // Insertar en tabla específica
            if ($esDigital) {
                // Recurso digital
                $this->db->table('recursos_digitales')->insert([
                    'idrecurso' => $idrecurso,
                    'portada' => $portada,
                    'archivo' => $recurso['archivo'] ?? null,
                ]);
            } else {
                // Recurso físico
                $this->db->table('recursos_fisicos')->insert([
                    'idrecurso' => $idrecurso,
                    'portada' => $portada,
                    'encuadernacion' => $recurso['encuadernacion'],
                ]);
                
                // Crear ejemplares físicos automáticamente
                $stock = $recurso['stock'];
                for ($i = 1; $i <= $stock; $i++) {
                    $this->db->table('ejemplares_fisicos')->insert([
                        'idrecurso' => $idrecurso,
                        'codigo_ejemplar' => sprintf('EJ-%04d-%03d', $idrecurso, $i),
                        'estado_ejemplar' => 'disponible',
                    ]);
                }
            }
        }
    }
}
