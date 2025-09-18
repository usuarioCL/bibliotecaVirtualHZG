    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-success text-white text-center">
            <h2 class="mb-0">Gráfico de Reacciones por Recurso</h2>
        </div>
        <div class="card-body">
            <canvas id="reaccionesChart" width="400" height="200"></canvas>
        </div>
    </div>
    <script src="/assets/js/chart.js-4.5.0/package/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        // Construir estructura: recurso -> {like, dislike, estrella}
        const reacciones = [
            <?php foreach ($reacciones as $r): ?>
                {
                    recurso: "<?= esc($r->titulo) ?>",
                    tipo: "<?= esc($r->tiporeaccion) ?>"
                },
            <?php endforeach; ?>
        ];
        // Obtener recursos únicos
        const recursosSet = new Set(reacciones.map(r => r.recurso));
        const recursos = Array.from(recursosSet);
        // Inicializar conteos
        const tipos = ['like', 'dislike', 'estrella'];
        const dataPorTipo = {
            like: [],
            dislike: [],
            estrella: []
        };
        recursos.forEach(recurso => {
            tipos.forEach(tipo => {
                const count = reacciones.filter(r => r.recurso === recurso && r.tipo === tipo).length;
                dataPorTipo[tipo].push(count);
            });
        });
        const ctx = document.getElementById('reaccionesChart').getContext('2d');
        if (window.reaccionesChartInstance) {
            window.reaccionesChartInstance.destroy();
        }
        window.reaccionesChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: recursos,
                datasets: [
                    {
                        label: 'Like',
                        data: dataPorTipo['like'],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Dislike',
                        data: dataPorTipo['dislike'],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Estrella',
                        data: dataPorTipo['estrella'],
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad de Reacciones' }
                    },
                    x: {
                        title: { display: true, text: 'Recurso' }
                    }
                }
            }
        });
    })();
    </script>
<div class="container">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-success text-white text-center">
            <h2 class="mb-0">Reacciones de los Usuarios</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Título del Recurso</th>
                            <th>Tipo de Reacción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reacciones as $r): ?>
                        <tr>
                            <td><?= esc($r->nomuser) ?></td>
                            <td><?= esc($r->titulo) ?></td>
                            <td><?= esc($r->tiporeaccion) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

