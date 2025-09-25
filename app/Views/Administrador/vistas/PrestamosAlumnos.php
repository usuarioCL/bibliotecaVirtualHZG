<div class="container">
<div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white text-center">
        <h2 class="mb-0">Estadistica</h2>
    </div>
    <div>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Préstamos con Información del Alumno</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha Préstamo</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Título del Recurso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $p): ?>
                        <tr>
                            <td><?= esc($p->fechaprestamo) ?></td>
                            <td><?= esc($p->nombres) ?></td>
                            <td><?= esc($p->apellidos) ?></td>
                            <td><?= esc($p->titulo) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<script src="/assets/js/chart.js-4.5.0/package/dist/chart.umd.min.js"></script>


<script>
    (function() {
        // Agrupar préstamos por alumno y por período (mes/año)
        const prestamos = [
            <?php foreach ($prestamos as $p): ?>
                {
                    alumno: "<?= esc($p->nombres) . ' ' . esc($p->apellidos) ?>",
                    fecha: "<?= esc($p->fechaprestamo) ?>"
                },
            <?php endforeach; ?>
        ];

        // Obtener todos los períodos únicos (mes/año)
        const periodosSet = new Set();
        prestamos.forEach(p => {
            const fecha = new Date(p.fecha);
            const periodo = fecha.getFullYear() + '-' + String(fecha.getMonth() + 1).padStart(2, '0');
            periodosSet.add(periodo);
            p.periodo = periodo;
        });
        const periodos = Array.from(periodosSet).sort();

        // Agrupar préstamos por alumno y período
        const alumnosSet = new Set(prestamos.map(p => p.alumno));
        const alumnos = Array.from(alumnosSet);
        const prestamosPorAlumnoPeriodo = {};
        alumnos.forEach(alumno => {
            prestamosPorAlumnoPeriodo[alumno] = {};
            periodos.forEach(periodo => {
                prestamosPorAlumnoPeriodo[alumno][periodo] = 0;
            });
        });
        prestamos.forEach(p => {
            prestamosPorAlumnoPeriodo[p.alumno][p.periodo]++;
        });

        // Preparar datasets para el gráfico
        const datasets = alumnos.map((alumno, idx) => {
            const color = `hsl(${(idx * 60) % 360}, 70%, 50%)`;
            return {
                label: alumno,
                data: periodos.map(periodo => prestamosPorAlumnoPeriodo[alumno][periodo]),
                borderColor: color,
                backgroundColor: color + '33',
                fill: false,
                tension: 0.3
            };
        });

        const ctx = document.getElementById('myChart').getContext('2d');
        // Destruir el gráfico anterior si existe
        if (window.myPrestamosChart) {
            window.myPrestamosChart.destroy();
        }
        window.myPrestamosChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: periodos,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Préstamos'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Período (Año-Mes)'
                        }
                    }
                }
            }
        });
    })();
 </script>

 
</div>

