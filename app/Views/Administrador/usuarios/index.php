<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Listado de Usuarios</h4>
            <p class="text-muted mb-0">Usuarios registrados en el sistema</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
            <i class="ti ti-plus"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card mt-1">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Documento</th>
                            <th>Nivel de Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['idusuario'] ?></td>
                                    <td><?= $usuario['nomuser'] ?></td>
                                    <td><?= $usuario['email'] ?? '<span class="text-muted">No registrado</span>' ?></td>
                                    <td><?= $usuario['numerodoc'] ?></td>
                                    <td>
                                        <?php if ($usuario['nivelacceso'] === 'admin'): ?>
                                            <span class="badge bg-warning text-dark" title="Administrador">Administrador</span>
                                        <?php elseif ($usuario['nivelacceso'] === 'docente'): ?>
                                            <span class="badge bg-info" title="Docente">Docente</span>
                                        <?php else: ?>
                                            <span class="badge bg-success" title="Estudiante">Estudiante</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" title="Editar usuario">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar usuario">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <p class="mt-2">No hay usuarios registrados</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- referencia al modal de nuevo usuario -->
<?php echo view('Administrador/modals/registrarusuario'); ?>

<script>
// Generar usuario y email automáticamente
function generarUsuarioYEmail() {
    const nombres = document.getElementById('nombres').value.trim().toLowerCase();
    const apellidos = document.getElementById('apellidos').value.trim().toLowerCase();

    if (nombres && apellidos) {
        const primerNombre = nombres.split(' ')[0];
        const primerApellido = apellidos.split(' ')[0];
        const usuario = primerNombre + '.' + primerApellido;
        const email = usuario + '@bibliotecavirtual.edu.pe';

        document.getElementById('nomuser_preview').value = usuario;
        document.getElementById('email_preview').value = email;
        document.getElementById('nomuser').value = usuario;
        document.getElementById('email').value = email;
    } else {
        document.getElementById('nomuser_preview').value = '';
        document.getElementById('email_preview').value = '';
        document.getElementById('nomuser').value = '';
        document.getElementById('email').value = '';
    }
}

document.getElementById('nombres').addEventListener('input', generarUsuarioYEmail);
document.getElementById('apellidos').addEventListener('input', generarUsuarioYEmail);

function mostrarAlerta(mensaje, tipo = 'info') {
    const alerta = document.getElementById('alertaValidacion');
    alerta.className = `alert alert-${tipo} mt-2`;
    alerta.innerHTML = mensaje;
    alerta.classList.remove('d-none');
}

function registrarPersonaYUsuario() {
    const form = document.getElementById('formNuevoUsuario');
    const formData = new FormData(form);

    // Validar usuario y email generados
    if (!document.getElementById('nomuser').value || !document.getElementById('email').value) {
        mostrarAlerta('Por favor complete nombres y apellidos para generar usuario y email', 'danger');
        return;
    }

    fetch('<?= base_url('usuarios/crear-completo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            mostrarAlerta(`¡Registro exitoso!<br>Usuario creado: <strong>${data.usuario}</strong><br>Email: <strong>${data.email}</strong>`, 'success');
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario')).hide();
                // Recargar la tabla mediante AJAX
                fetch('<?= base_url('usuarios') ?>')
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('.table-responsive');
                        document.querySelector('.table-responsive').innerHTML = newTable.innerHTML;
                    });
            }, 2500);
        } else {
            mostrarAlerta(data.message || 'Error al registrar persona y usuario', 'danger');
        }
    })
    .catch(() => {
        mostrarAlerta('Error de conexión', 'danger');
    });
}

document.getElementById('modalNuevoUsuario').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formNuevoUsuario').reset();
    document.getElementById('nomuser_preview').value = '';
    document.getElementById('email_preview').value = '';
    document.getElementById('nomuser').value = '';
    document.getElementById('email').value = '';
    document.getElementById('alertaValidacion').classList.add('d-none');
});
</script>

</script>
