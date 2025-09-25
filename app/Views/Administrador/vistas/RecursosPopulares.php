<div class="container">
	<div class="card shadow-lg border-0 rounded-3 mb-4">
		<div class="card-header bg-primary text-white text-center">
			<h2 class="mb-0">Estadísticas de Recursos Populares</h2>
		</div>
		<div class="card-body">
			<canvas id="recursosChart" width="400" height="200"></canvas>
		</div>
	</div>

	<div class="card shadow-lg border-0 rounded-3">
		<div class="card-header bg-primary text-white text-center">
			<h2 class="mb-0">CRUD de Recursos Populares</h2>
		</div>
		<div class="card-body">
			<!-- Botón Agregar Recurso eliminado -->
			<div class="table-responsive">
				<table class="table table-bordered table-hover align-middle text-center">
					<thead class="table-dark">
						<tr>
							<th>Título</th>
							<th>Autor</th>
							<th>Categoría</th>
							<th>Veces Prestado</th>
							<!-- <th>Acciones</th> -->
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($recursosPopulares)): ?>
							<?php foreach ($recursosPopulares as $r): ?>
								<tr>
									<td><?= esc($r->titulo) ?></td>
									<td><?= esc($r->autor) ?></td>
									<td><?= esc($r->categoria) ?></td>
									<td><?= esc($r->veces_prestado) ?></td>
									<!-- Acciones eliminadas -->
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr><td colspan="4">No hay recursos populares para mostrar.</td></tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="/assets/js/chart.js-4.5.0/package/dist/chart.umd.min.js"></script>
	<script>
		(function() {
			// Datos para el gráfico (puedes ajustar según tu variable PHP)
			const recursos = [
				<?php if (!empty($recursosPopulares)): ?>
					<?php foreach ($recursosPopulares as $r): ?>
						{
							titulo: "<?= esc($r->titulo) ?>",
							veces: <?= (int) $r->veces_prestado ?>
						},
					<?php endforeach; ?>
				<?php endif; ?>
			];
			const labels = recursos.map(r => r.titulo);
			const data = recursos.map(r => r.veces);
			const ctx = document.getElementById('recursosChart').getContext('2d');
			if (window.recursosChartInstance) {
				window.recursosChartInstance.destroy();
			}
			window.recursosChartInstance = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Veces Prestado',
						data: data,
						backgroundColor: 'rgba(54, 162, 235, 0.5)',
						borderColor: 'rgba(54, 162, 235, 1)',
						borderWidth: 1
					}]
				},
				options: {
					responsive: true,
					plugins: {
						legend: { display: false }
					},
					scales: {
						y: {
							beginAtZero: true,
							title: { display: true, text: 'Veces Prestado' }
						},
						x: {
							title: { display: true, text: 'Recurso' }
						}
					}
				}
			});
		})();
	</script>
</div>
