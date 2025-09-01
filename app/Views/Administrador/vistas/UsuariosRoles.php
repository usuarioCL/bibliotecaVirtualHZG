<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h2>Usuarios y Roles</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Usuario</th>
                        <th>Nivel de Acceso</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($u->nomuser) ?></td>
                        <td><?= htmlspecialchars($u->nivelacceso) ?></td>
                        <td><?= htmlspecialchars($u->nombres) ?></td>
                        <td><?= htmlspecialchars($u->apellidos) ?></td>
                        <td><?= htmlspecialchars($u->email) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

