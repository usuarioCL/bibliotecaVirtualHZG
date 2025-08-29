<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reacciones</title>

    <!-- Bootstrap 5.3.0 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
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

<!-- Bootstrap 5.3.0 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
