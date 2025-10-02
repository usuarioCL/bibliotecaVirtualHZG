<!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario" autocomplete="off">
                    <input type="hidden" id="editar-idusuario" name="idusuario">
                    <input type="hidden" id="editar-idpersona" name="idpersona">
                    
                    <!-- Datos de la Persona -->
                    <h6 class="text-primary mb-3">Datos Personales</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="editar-apellidos" name="apellidos" required placeholder="Ej: Pérez Gómez">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="editar-nombres" name="nombres" required placeholder="Ej: Juan Carlos">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editar-tipodoc" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="editar-tipodoc" name="tipodoc" required>
                                    <option value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editar-numerodoc" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="editar-numerodoc" name="numerodoc" required maxlength="15" placeholder="Ej: 12345678">
                                <div class="form-text">No se puede modificar si ya está en uso</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editar-genero" class="form-label">Género</label>
                                <select class="form-select" id="editar-genero" name="genero" required>
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
                                <label for="editar-telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="editar-telefono" name="telefono" maxlength="15" placeholder="Ej: 999888777">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="editar-direccion" name="direccion" maxlength="100" placeholder="Ej: Av. Siempre Viva 123">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Usuario -->
                    <hr>
                    <h6 class="text-primary mb-3">Datos de Usuario</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-nivelacceso" class="form-label">Nivel de Acceso</label>
                                <select class="form-select" id="editar-nivelacceso" name="nivelacceso" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="estudiante">Estudiante</option>
                                    <option value="docente">Docente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-passuser" class="form-label">Nueva Contraseña <small class="text-muted">(opcional)</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="editar-passuser" name="passuser" minlength="6" placeholder="Dejar vacío para mantener actual">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordEditar" title="Mostrar/Ocultar contraseña" onclick="togglePasswordVisibilityEditar()">
                                        <i class="ti ti-eye" id="eyeIconEditar"></i>
                                    </button>
                                </div>
                                <div class="form-text">Dejar vacío si no desea cambiar la contraseña</div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-nomuser" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="editar-nomuser" name="nomuser" required placeholder="Nombre de usuario" title="Usuario del sistema">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editar-email" name="email" placeholder="correo@ejemplo.com" title="Email del usuario">
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacionEditar" class="alert d-none mt-2"></div>
                    
                    <!-- Loading state -->
                    <div id="loading-editar" class="text-center py-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando datos...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando información del usuario...</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarUsuario()" id="btnActualizarUsuario">
                    <i class="ti ti-device-floppy me-2"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar contraseña en modal de edición
function togglePasswordVisibilityEditar() {
    console.log('🔍 Función togglePasswordVisibilityEditar ejecutada');
    
    const passwordField = document.getElementById('editar-passuser');
    const eyeIcon = document.getElementById('eyeIconEditar');
    
    if (passwordField && eyeIcon) {
        const isPassword = passwordField.type === 'password';
        
        // Cambiar tipo de input
        passwordField.type = isPassword ? 'text' : 'password';
        
        // Cambiar icono
        eyeIcon.className = isPassword ? 'ti ti-eye-off' : 'ti ti-eye';
        
        console.log(isPassword ? '👁️ Contraseña mostrada (editar)' : '🙈 Contraseña ocultada (editar)');
    }
}

// Listener para mostrar/ocultar sección académica según nivel de acceso
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'editar-nivelacceso') {
        const seccionAcademica = document.getElementById('seccion-academica-editar');
        if (seccionAcademica) {
            if (e.target.value === 'estudiante') {
                seccionAcademica.classList.remove('d-none');
            } else {
                seccionAcademica.classList.add('d-none');
            }
        }
    }
});

// Función para limpiar el modal de edición
function limpiarModalEdicion() {
    console.log('🧹 Limpiando modal de edición');
    
    const form = document.getElementById('formEditarUsuario');
    if (form) {
        form.reset();
        form.classList.remove('was-validated');
    }
    
    // Ocultar sección académica
    const seccionAcademica = document.getElementById('seccion-academica-editar');
    if (seccionAcademica) {
        seccionAcademica.classList.add('d-none');
    }
    
    // Limpiar alertas
    const alerta = document.getElementById('alertaValidacionEditar');
    if (alerta) {
        alerta.classList.add('d-none');
        alerta.innerHTML = '';
    }
    
    // Ocultar loading
    const loading = document.getElementById('loading-editar');
    if (loading) {
        loading.style.display = 'none';
    }
}

// Event listeners para el modal de edición
document.addEventListener('DOMContentLoaded', function() {
    const modalEditar = document.getElementById('modalEditarUsuario');
    if (modalEditar) {
        modalEditar.addEventListener('hidden.bs.modal', function() {
            console.log('🔥 Modal de edición cerrado');
            limpiarModalEdicion();
        });
    }
});

// Función para actualizar usuario
function actualizarUsuario() {
    console.log('Iniciando actualización de usuario');
    
    const form = document.getElementById('formEditarUsuario');
    if (!form) {
        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'No se encontró el formulario de edición.',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    const formData = new FormData(form);
    
    // Verificar campos obligatorios
    const apellidos = document.getElementById('editar-apellidos')?.value?.trim();
    const nombres = document.getElementById('editar-nombres')?.value?.trim();
    const nomuser = document.getElementById('editar-nomuser')?.value?.trim();
    const idusuario = document.getElementById('editar-idusuario')?.value;
    
    if (!apellidos || !nombres || !nomuser || !idusuario) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor complete todos los campos obligatorios',
            confirmButtonColor: '#ffc107'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Actualizando usuario...',
        html: 'Por favor espere mientras procesamos los cambios',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Realizar petición
    fetch('<?= base_url('usuarios/actualizar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();
            
            // Mostrar éxito
            Swal.fire({
                icon: 'success',
                title: '¡Usuario actualizado!',
                text: data.message || 'Los datos del usuario han sido actualizados correctamente',
                confirmButtonColor: '#0d6efd',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // Recargar vista
                recargarVistaUsuarios();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: data.message || 'No se pudo actualizar el usuario',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545'
        });
    });
}
</script>