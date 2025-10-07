<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PrestamoController extends Controller
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    /**
     * Página principal - Préstamos Activos
     */
    public function index()
    {
        $data = [
            'title' => 'Préstamos Activos',
            'prestamos' => $this->getDatosPruebaPrestamos(),
            'estadisticas' => [
                'total_prestamos' => 25,
                'vencidos_hoy' => 3,
                'proximos_vencer' => 8,
                'renovaciones_pendientes' => 2
            ]
        ];

        return view('Administrador/prestamos/index', $data);
    }

    /**
     * Solicitudes Pendientes
     */
    public function solicitudes()
    {
        $data = [
            'title' => 'Solicitudes Pendientes',
            'solicitudes' => $this->getDatosPruebaSolicitudes(),
            'estadisticas' => [
                'total_solicitudes' => 12,
                'hoy' => 4,
                'esta_semana' => 8,
                'esperando_aprobacion' => 12
            ]
        ];

        return view('Administrador/prestamos/solicitudes', $data);
    }

    /**
     * Devoluciones
     */
    public function devoluciones()
    {
        $data = [
            'title' => 'Devoluciones',
            'devoluciones' => $this->getDatosPruebaDevoluciones(),
            'estadisticas' => [
                'devoluciones_hoy' => 5,
                'con_retraso' => 2,
                'danos_reportados' => 1,
                'multas_generadas' => 3
            ]
        ];

        return view('Administrador/prestamos/devoluciones', $data);
    }

    /**
     * Historial Completo
     */
    public function historial()
    {
        $data = [
            'title' => 'Historial de Préstamos',
            'historial' => $this->getDatosPruebaHistorial(),
            'estadisticas' => [
                'total_registros' => 156,
                'este_mes' => 28,
                'promedio_mensual' => 35,
                'tasa_devolucion' => 98.5
            ]
        ];

        return view('Administrador/prestamos/historial', $data);
    }

    /**
     * Datos de prueba para préstamos activos
     */
    private function getDatosPruebaPrestamos()
    {
        return [
            [
                'id' => 1,
                'codigo_prestamo' => 'PREST-2024-001',
                'usuario' => 'Juan Carlos Pérez',
                'documento' => '12345678',
                'recurso' => 'Cálculo Diferencial e Integral',
                'codigo_ejemplar' => 'LIB-MAT-001',
                'fecha_prestamo' => '2024-10-01',
                'fecha_vencimiento' => '2024-10-15',
                'dias_restantes' => 8,
                'estado' => 'Activo',
                'renovaciones' => 0
            ],
            [
                'id' => 2,
                'codigo_prestamo' => 'PREST-2024-002',
                'usuario' => 'María González',
                'documento' => '87654321',
                'recurso' => 'Física General',
                'codigo_ejemplar' => 'LIB-FIS-002',
                'fecha_prestamo' => '2024-09-28',
                'fecha_vencimiento' => '2024-10-07',
                'dias_restantes' => 0,
                'estado' => 'Vencido',
                'renovaciones' => 1
            ],
            [
                'id' => 3,
                'codigo_prestamo' => 'PREST-2024-003',
                'usuario' => 'Carlos Rodriguez',
                'documento' => '11223344',
                'recurso' => 'Química Orgánica',
                'codigo_ejemplar' => 'LIB-QUI-003',
                'fecha_prestamo' => '2024-10-05',
                'fecha_vencimiento' => '2024-10-19',
                'dias_restantes' => 12,
                'estado' => 'Activo',
                'renovaciones' => 0
            ]
        ];
    }

    /**
     * Datos de prueba para solicitudes
     */
    private function getDatosPruebaSolicitudes()
    {
        return [
            [
                'id' => 1,
                'usuario' => 'Ana López',
                'documento' => '55667788',
                'recurso' => 'Álgebra Lineal',
                'codigo_ejemplar' => 'LIB-MAT-004',
                'fecha_solicitud' => '2024-10-07 09:30:00',
                'estado' => 'Pendiente',
                'prioridad' => 'Normal',
                'disponible' => true
            ],
            [
                'id' => 2,
                'usuario' => 'Pedro Martínez',
                'documento' => '99887766',
                'recurso' => 'Base de Datos',
                'codigo_ejemplar' => 'LIB-INF-001',
                'fecha_solicitud' => '2024-10-07 10:15:00',
                'estado' => 'Pendiente',
                'prioridad' => 'Alta',
                'disponible' => false
            ],
            [
                'id' => 3,
                'usuario' => 'Lucía Fernández',
                'documento' => '44556677',
                'recurso' => 'Estadística Aplicada',
                'codigo_ejemplar' => 'LIB-EST-001',
                'fecha_solicitud' => '2024-10-06 16:45:00',
                'estado' => 'Pendiente',
                'prioridad' => 'Normal',
                'disponible' => true
            ]
        ];
    }

    /**
     * Datos de prueba para devoluciones
     */
    private function getDatosPruebaDevoluciones()
    {
        return [
            [
                'id' => 1,
                'codigo_prestamo' => 'PREST-2024-010',
                'usuario' => 'Roberto Silva',
                'documento' => '33445566',
                'recurso' => 'Programación en Java',
                'fecha_devolucion' => '2024-10-07 14:30:00',
                'fecha_vencimiento' => '2024-10-05',
                'dias_retraso' => 2,
                'estado_ejemplar' => 'Bueno',
                'multa' => 5000,
                'observaciones' => ''
            ],
            [
                'id' => 2,
                'codigo_prestamo' => 'PREST-2024-011',
                'usuario' => 'Sandra Castro',
                'documento' => '77889900',
                'recurso' => 'Historia de Colombia',
                'fecha_devolucion' => '2024-10-07 11:15:00',
                'fecha_vencimiento' => '2024-10-08',
                'dias_retraso' => 0,
                'estado_ejemplar' => 'Bueno',
                'multa' => 0,
                'observaciones' => 'Devolución temprana'
            ]
        ];
    }

    /**
     * Datos de prueba para historial
     */
    private function getDatosPruebaHistorial()
    {
        return [
            [
                'id' => 1,
                'codigo_prestamo' => 'PREST-2024-008',
                'usuario' => 'Miguel Torres',
                'documento' => '66778899',
                'recurso' => 'Contabilidad Básica',
                'fecha_prestamo' => '2024-09-15',
                'fecha_devolucion' => '2024-09-28',
                'estado_final' => 'Devuelto',
                'dias_prestamo' => 13,
                'renovaciones' => 1,
                'multas' => 0
            ],
            [
                'id' => 2,
                'codigo_prestamo' => 'PREST-2024-009',
                'usuario' => 'Elena Vargas',
                'documento' => '22334455',
                'recurso' => 'Derecho Civil',
                'fecha_prestamo' => '2024-09-20',
                'fecha_devolucion' => '2024-10-02',
                'estado_final' => 'Devuelto con retraso',
                'dias_prestamo' => 12,
                'renovaciones' => 0,
                'multas' => 7500
            ]
        ];
    }
}
