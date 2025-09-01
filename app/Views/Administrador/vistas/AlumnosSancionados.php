
<div class="container">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-danger text-white text-center">
            <h2 class="mb-0">Alumnos Sancionados</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Detalle Sanción</th>
                            <th>Tipo de Sanción</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sancionados as $s): ?>
                        <tr>
                            <td><?= esc($s->detallesancion) ?></td>
                            <td><?= esc($s->tiposancion) ?></td>
                            <td><?= esc($s->nombres) ?></td>
                            <td><?= esc($s->apellidos) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

