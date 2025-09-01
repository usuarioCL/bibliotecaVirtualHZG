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

