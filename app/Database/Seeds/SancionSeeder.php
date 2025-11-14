<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SancionSeeder extends Seeder
{
    public function run()
    {
        // Primero insertar tipos de sanción
        $tiposSancion = [
            ['tiposancion' => 'Retraso en devolución'],
            ['tiposancion' => 'Pérdida de material'],
            ['tiposancion' => 'Daño al material'],
            ['tiposancion' => 'Incumplimiento de normas'],
            ['tiposancion' => 'Comportamiento inadecuado'],
        ];

        $this->db->table('tiposancion')->insertBatch($tiposSancion);

        // Insertar sanciones de prueba
        $sanciones = [
            [
                'idtiposancion' => 1,
                'idprestamo' => null,
                'idpersona' => 4, // Estudiante 1
                'detallesancion' => 'Retraso de 5 días en devolución de libro',
                'fecha_sancion' => date('Y-m-d', strtotime('-15 days')),
                'fecha_inicio' => date('Y-m-d', strtotime('-15 days')),
                'fecha_vencimiento' => date('Y-m-d', strtotime('+15 days')),
                'estado_sancion' => 'activa',
                'duracion_dias' => 30,
                'usuario_registra' => 1, // Admin
                'usuario_levanta' => null,
                'fecha_levantamiento' => null,
                'motivo_levantamiento' => null,
                'observaciones' => 'Primera sanción por retraso',
            ],
            [
                'idtiposancion' => 1,
                'idprestamo' => null,
                'idpersona' => 5, // Estudiante 2
                'detallesancion' => 'Retraso de 3 días en devolución',
                'fecha_sancion' => date('Y-m-d', strtotime('-45 days')),
                'fecha_inicio' => date('Y-m-d', strtotime('-45 days')),
                'fecha_vencimiento' => date('Y-m-d', strtotime('-15 days')),
                'estado_sancion' => 'cumplida',
                'duracion_dias' => 30,
                'usuario_registra' => 1,
                'usuario_levanta' => 1,
                'fecha_levantamiento' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'motivo_levantamiento' => 'Cumplió el periodo de sanción',
                'observaciones' => 'Sanción completada exitosamente',
            ],
            [
                'idtiposancion' => 3,
                'idprestamo' => null,
                'idpersona' => 6, // Estudiante 3
                'detallesancion' => 'Páginas rayadas y dañadas del libro',
                'fecha_sancion' => date('Y-m-d', strtotime('-10 days')),
                'fecha_inicio' => date('Y-m-d', strtotime('-10 days')),
                'fecha_vencimiento' => date('Y-m-d', strtotime('+50 days')),
                'estado_sancion' => 'activa',
                'duracion_dias' => 60,
                'usuario_registra' => 1,
                'usuario_levanta' => null,
                'fecha_levantamiento' => null,
                'motivo_levantamiento' => null,
                'observaciones' => 'Material dañado - debe pagar reparación',
            ],
            [
                'idtiposancion' => 4,
                'idprestamo' => null,
                'idpersona' => 7, // Estudiante 4
                'detallesancion' => 'Violación de normas: ingreso de alimentos',
                'fecha_sancion' => date('Y-m-d', strtotime('-5 days')),
                'fecha_inicio' => date('Y-m-d', strtotime('-5 days')),
                'fecha_vencimiento' => date('Y-m-d', strtotime('+9 days')),
                'estado_sancion' => 'activa',
                'duracion_dias' => 14,
                'usuario_registra' => 1,
                'usuario_levanta' => null,
                'fecha_levantamiento' => null,
                'motivo_levantamiento' => null,
                'observaciones' => 'Sanción leve por primera infracción',
            ],
            [
                'idtiposancion' => 2,
                'idprestamo' => null,
                'idpersona' => 4, // Estudiante 1
                'detallesancion' => 'Libro no devuelto - declarado perdido',
                'fecha_sancion' => date('Y-m-d', strtotime('-90 days')),
                'fecha_inicio' => date('Y-m-d', strtotime('-90 days')),
                'fecha_vencimiento' => null,
                'estado_sancion' => 'cancelada',
                'duracion_dias' => null,
                'usuario_registra' => 1,
                'usuario_levanta' => 1,
                'fecha_levantamiento' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'motivo_levantamiento' => 'Estudiante repuso el material',
                'observaciones' => 'Material repuesto - sanción cancelada',
            ],
        ];

        $this->db->table('sanciones')->insertBatch($sanciones);
    }
}
