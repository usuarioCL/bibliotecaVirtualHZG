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
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Documento</th>
                            <th>Nivel de Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                                <?php if (!empty($usuarios)): ?>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <tr>
                                            <td><?= $usuario['idusuario'] ?></td>
                                            <td><?= $usuario['nomuser'] ?></td>
                                            <td><?= $usuario['apellidos'] . ', ' . $usuario['nombres'] ?></td>
                                            <td><?= $usuario['email'] ?? '<span class="text-muted">No registrado</span>' ?></td>
                                            <td>
                                                <?= $usuario['tipodoc'] ?> <?= $usuario['numerodoc'] ?>
                                            </td>
                                            <td>
                                                <?php if ($usuario['nivelacceso'] === 'admin'): ?>
                                                    <span class="badge bg-warning text-dark">Administrador</span>
                                                <?php elseif ($usuario['nivelacceso'] === 'docente'): ?>
                                                    <span class="badge bg-info">Docente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Estudiante</span>
                                                <?php endif; ?>
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

<!-- Modal para nuevo usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nueva Persona y Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoUsuario">
                    <!-- Datos de la Persona -->
                    <h6 class="text-primary mb-3">Datos Personales</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tipodoc" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="tipodoc" name="tipodoc" required>
                                    <option value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numerodoc" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="numerodoc" name="numerodoc" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" maxlength="100">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Usuario -->
                    <hr>
                    <h6 class="text-primary mb-3">Datos de Usuario</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nivelacceso" class="form-label">Nivel de Acceso</label>
                                <select class="form-select" id="nivelacceso" name="nivelacceso" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="estudiante">Estudiante</option>
                                    <option value="docente">Docente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="passuser" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="passuser" name="passuser" required minlength="6">
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos generados automáticamente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomuser_preview" class="form-label">Usuario (generado automáticamente)</label>
                                <input type="text" class="form-control" id="nomuser_preview" readonly placeholder="Se generará automáticamente">
                                <input type="hidden" id="nomuser" name="nomuser">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email_preview" class="form-label">Email (generado automáticamente)</label>
                                <input type="email" class="form-control" id="email_preview" readonly placeholder="Se generará automáticamente">
                                <input type="hidden" id="email" name="email">
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacion" class="alert d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="registrarPersonaYUsuario()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Generar usuario y email automáticamente
function generarUsuarioYEmail() {
    const nombres = document.getElementById('nombres').value.toLowerCase();
    const apellidos = document.getElementById('apellidos').value.toLowerCase();
    
    if (nombres && apellidos) {
        // Generar usuario: primer nombre + primer apellido
        const primerNombre = nombres.split(' ')[0];
        const primerApellido = apellidos.split(' ')[0];
        const usuario = primerNombre + '.' + primerApellido;
        
        // Generar email institucional
        const email = usuario + '@bibliotecavirtual.edu.pe';
        
        // Mostrar en los campos preview
        document.getElementById('nomuser_preview').value = usuario;
        document.getElementById('email_preview').value = email;
        
        // Establecer en los campos hidden
        document.getElementById('nomuser').value = usuario;
        document.getElementById('email').value = email;
    }
}

// Escuchar cambios en nombres y apellidos
document.getElementById('nombres').addEventListener('input', generarUsuarioYEmail);
document.getElementById('apellidos').addEventListener('input', generarUsuarioYEmail);

function registrarPersonaYUsuario() {
    const form = document.getElementById('formNuevoUsuario');
    const formData = new FormData(form);
    const alerta = document.getElementById('alertaValidacion');
    
    // Limpiar alertas previas
    alerta.classList.add('d-none');
    
    // Validar que se hayan generado usuario y email
    if (!document.getElementById('nomuser').value || !document.getElementById('email').value) {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Por favor complete nombres y apellidos para generar usuario y email';
        alerta.classList.remove('d-none');
        return;
    }
    
    fetch('<?= base_url('usuarios/crear-completo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alerta.className = 'alert alert-success';
            alerta.innerHTML = `
                <strong>¡Registro exitoso!</strong><br>
                Usuario creado: <strong>${data.usuario}</strong><br>
                Email: <strong>${data.email}</strong>
            `;
            alerta.classList.remove('d-none');
            
            // Cerrar modal después de 3 segundos y recargar
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario')).hide();
                location.reload();
            }, 3000);
        } else {
            alerta.className = 'alert alert-danger';
            alerta.textContent = data.message || 'Error al registrar persona y usuario';
            alerta.classList.remove('d-none');
        }
    })
    .catch(error => {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Error de conexión';
        alerta.classList.remove('d-none');
    });
}

// Validar eligibilidad cuando cambie el nivel de acceso
document.getElementById('nivelacceso').addEventListener('change', function() {
    const nivelacceso = this.value;
    const alerta = document.getElementById('alertaValidacion');
    
    if (nivelacceso) {
        let mensaje = '';
        let clase = 'alert-info';
        
        switch(nivelacceso) {
            case 'estudiante':
                mensaje = 'Los estudiantes deben estar matriculados en la institución';
                break;
            case 'docente':
                mensaje = 'Los docentes deben ser personal de la institución educativa';
                break;
            case 'admin':
                mensaje = 'Los administradores requieren permisos especiales';
                clase = 'alert-warning';
                break;
        }
        
        alerta.className = `alert ${clase}`;
        alerta.textContent = mensaje;
        alerta.classList.remove('d-none');
    } else {
        alerta.classList.add('d-none');
    }
});

// Limpiar formulario cuando se cierre el modal
document.getElementById('modalNuevoUsuario').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formNuevoUsuario').reset();
    document.getElementById('nomuser_preview').value = '';
    document.getElementById('email_preview').value = '';
    document.getElementById('nomuser').value = '';
    document.getElementById('email').value = '';
    document.getElementById('alertaValidacion').classList.add('d-none');
});
</script>
