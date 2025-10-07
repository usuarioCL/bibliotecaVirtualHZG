<!-- Modal para Mi Perfil -->
<div class="modal fade" id="modalMiPerfil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-user text-primary me-2"></i>
                    Mi Perfil de Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs de navegación -->
                <ul class="nav nav-tabs" id="perfilTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button" role="tab">
                            <i class="ti ti-user me-2"></i>Datos Personales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seguridad-tab" data-bs-toggle="tab" data-bs-target="#seguridad" type="button" role="tab">
                            <i class="ti ti-shield-lock me-2"></i>Seguridad
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="preferencias-tab" data-bs-toggle="tab" data-bs-target="#preferencias" type="button" role="tab">
                            <i class="ti ti-settings me-2"></i>Preferencias
                        </button>
                    </li>
                </ul>

                <!-- Contenido de los tabs -->
                <div class="tab-content pt-4" id="perfilTabContent">
                    <!-- Tab Datos Personales -->
                    <div class="tab-pane fade show active" id="datos" role="tabpanel">
                        <form id="formDatosPerfil" autocomplete="off">
                            <div class="row mb-4">
                                <div class="col-12 text-center">
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= base_url('./assets/images/profile/user-1.jpg') ?>" 
                                             alt="Foto de perfil" 
                                             class="rounded-circle"
                                             style="width: 120px; height: 120px; object-fit: cover;"
                                             id="fotoPerfil">
                                        <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle" 
                                                style="width: 35px; height: 35px;" onclick="cambiarFotoPerfil()">
                                            <i class="ti ti-camera" style="font-size: 16px;"></i>
                                        </button>
                                    </div>
                                    <input type="file" id="inputFotoPerfil" accept="image/*" style="display: none;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellidos_perfil" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos_perfil" name="apellidos" 
                                               value="González Martínez" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombres_perfil" class="form-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombres_perfil" name="nombres" 
                                               value="María Elena" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_perfil" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email_perfil" name="email" 
                                               value="admin@bibliotecahzg.edu.pe" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono_perfil" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono_perfil" name="telefono" 
                                               value="+51 987 654 321">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tipodoc_perfil" class="form-label">Tipo de Documento</label>
                                        <select class="form-select" id="tipodoc_perfil" name="tipodoc" disabled>
                                            <option value="DNI" selected>DNI</option>
                                            <option value="CE">CE</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="numerodoc_perfil" class="form-label">Número de Documento</label>
                                        <input type="text" class="form-control" id="numerodoc_perfil" name="numerodoc" 
                                               value="12345678" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="rol_perfil" class="form-label">Rol del Sistema</label>
                                        <input type="text" class="form-control" id="rol_perfil" name="rol" 
                                               value="Administrador" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="direccion_perfil" class="form-label">Dirección</label>
                                        <textarea class="form-control" id="direccion_perfil" name="direccion" rows="2" 
                                                  placeholder="Ingrese su dirección completa">Av. Principal 123, Lima, Perú</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Seguridad -->
                    <div class="tab-pane fade" id="seguridad" role="tabpanel">
                        <form id="formSeguridadPerfil" autocomplete="off">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                Por tu seguridad, necesitarás confirmar tu contraseña actual para realizar cambios.
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="contrasena_actual" 
                                                   name="contrasena_actual" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('contrasena_actual')">
                                                <i class="ti ti-eye" id="icon_contrasena_actual"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="nueva_contrasena" 
                                                   name="nueva_contrasena" required minlength="8">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('nueva_contrasena')">
                                                <i class="ti ti-eye" id="icon_nueva_contrasena"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">La contraseña debe tener al menos 8 caracteres</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmar_contrasena" 
                                                   name="confirmar_contrasena" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirmar_contrasena')">
                                                <i class="ti ti-eye" id="icon_confirmar_contrasena"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medidor de seguridad de contraseña -->
                            <div class="mb-3">
                                <label class="form-label">Seguridad de la Contraseña</label>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" id="password-strength-bar" style="width: 0%"></div>
                                </div>
                                <div class="form-text" id="password-strength-text">Ingrese una nueva contraseña</div>
                            </div>

                            <!-- Configuración de autenticación en dos pasos -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ti ti-shield-check text-success me-2"></i>
                                        Autenticación en Dos Pasos
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Verificación SMS</h6>
                                            <p class="text-muted small mb-0">Recibe códigos de verificación por SMS</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="sms_2fa" checked>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Verificación por Email</h6>
                                            <p class="text-muted small mb-0">Recibe códigos de verificación por correo</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_2fa">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Preferencias -->
                    <div class="tab-pane fade" id="preferencias" role="tabpanel">
                        <form id="formPreferenciasPerfil" autocomplete="off">
                            <!-- Configuración de notificaciones -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ti ti-bell text-primary me-2"></i>
                                        Notificaciones
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0">Notificaciones por Email</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="notif_email" checked>
                                                    </div>
                                                </div>
                                                <div class="form-text">Recibir notificaciones importantes por correo</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0">Notificaciones del Sistema</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="notif_sistema" checked>
                                                    </div>
                                                </div>
                                                <div class="form-text">Mostrar notificaciones en el sistema</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0">Recordatorios de Préstamos</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="notif_prestamos" checked>
                                                    </div>
                                                </div>
                                                <div class="form-text">Alertas sobre vencimientos de préstamos</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0">Reportes Semanales</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="notif_reportes">
                                                    </div>
                                                </div>
                                                <div class="form-text">Resumen semanal de actividades</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuración de interfaz -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ti ti-palette text-info me-2"></i>
                                        Apariencia e Interfaz
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tema_interfaz" class="form-label">Tema de la Interfaz</label>
                                                <select class="form-select" id="tema_interfaz" name="tema">
                                                    <option value="light" selected>Claro</option>
                                                    <option value="dark">Oscuro</option>
                                                    <option value="auto">Automático</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="idioma_interfaz" class="form-label">Idioma</label>
                                                <select class="form-select" id="idioma_interfaz" name="idioma">
                                                    <option value="es" selected>Español</option>
                                                    <option value="en">English</option>
                                                    <option value="pt">Português</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="elementos_pagina" class="form-label">Elementos por Página</label>
                                                <select class="form-select" id="elementos_pagina" name="elementos_pagina">
                                                    <option value="10">10 elementos</option>
                                                    <option value="25" selected>25 elementos</option>
                                                    <option value="50">50 elementos</option>
                                                    <option value="100">100 elementos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="formato_fecha" class="form-label">Formato de Fecha</label>
                                                <select class="form-select" id="formato_fecha" name="formato_fecha">
                                                    <option value="dd/mm/yyyy" selected>DD/MM/YYYY</option>
                                                    <option value="mm/dd/yyyy">MM/DD/YYYY</option>
                                                    <option value="yyyy-mm-dd">YYYY-MM-DD</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuración de privacidad -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ti ti-shield-lock text-warning me-2"></i>
                                        Privacidad y Datos
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <label class="form-label mb-0">Perfil Público</label>
                                                <div class="form-text">Permitir que otros usuarios vean tu perfil</div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="perfil_publico">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <label class="form-label mb-0">Actividad Reciente</label>
                                                <div class="form-text">Mostrar tu actividad reciente en el sistema</div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mostrar_actividad" checked>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <label class="form-label mb-0">Recopilación de Datos</label>
                                                <div class="form-text">Permitir recopilación de datos para mejorar el servicio</div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="recopilacion_datos" checked>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarPerfil()">
                    <i class="ti ti-device-floppy me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para cambiar foto de perfil
function cambiarFotoPerfil() {
    document.getElementById('inputFotoPerfil').click();
}

// Manejar cambio de foto
document.addEventListener('DOMContentLoaded', function() {
    const inputFoto = document.getElementById('inputFotoPerfil');
    if (inputFoto) {
        inputFoto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoPerfil').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Función para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('icon_' + inputId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
    }
}

// Función para evaluar seguridad de contraseña
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('nueva_contrasena');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 8) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                feedback = 'Débil';
            } else if (strength < 60) {
                strengthBar.className = 'progress-bar bg-warning';
                feedback = 'Regular';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-info';
                feedback = 'Buena';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                feedback = 'Excelente';
            }
            
            strengthBar.style.width = strength + '%';
            strengthText.textContent = feedback;
        });
    }
});

// Función para guardar perfil
function guardarPerfil() {
    // Determinar qué tab está activo
    const activeTab = document.querySelector('#perfilTabs .nav-link.active').id;
    let formId = '';
    let titulo = '';
    
    switch(activeTab) {
        case 'datos-tab':
            formId = 'formDatosPerfil';
            titulo = 'datos personales';
            break;
        case 'seguridad-tab':
            formId = 'formSeguridadPerfil';
            titulo = 'configuración de seguridad';
            break;
        case 'preferencias-tab':
            formId = 'formPreferenciasPerfil';
            titulo = 'preferencias';
            break;
    }
    
    const form = document.getElementById(formId);
    if (form && form.checkValidity()) {
        Swal.fire({
            title: '¿Guardar Cambios?',
            text: `¿Estás seguro de que deseas guardar los cambios en tu ${titulo}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simular guardado exitoso
                Swal.fire({
                    title: 'Perfil Actualizado',
                    text: `Tu ${titulo} ha sido actualizada exitosamente`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalMiPerfil'));
                    modal.hide();
                });
            }
        });
    } else {
        form.reportValidity();
    }
}
</script>

<style>
/* Estilos específicos para el modal de perfil */
#modalMiPerfil .nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

#modalMiPerfil .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 12px 20px;
    font-weight: 500;
}

#modalMiPerfil .nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    background: none;
}

#modalMiPerfil .card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
}

#modalMiPerfil .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

#modalMiPerfil .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#modalMiPerfil .progress {
    background-color: #e9ecef;
}

/* Z-index fixes */
#modalMiPerfil {
    z-index: 99999 !important;
}

#modalMiPerfil .modal-backdrop {
    z-index: 99998 !important;
}

#modalMiPerfil .modal-content {
    z-index: 100001 !important;
    position: relative !important;
}

#modalMiPerfil .modal-header,
#modalMiPerfil .modal-body,
#modalMiPerfil .modal-footer {
    z-index: 100002 !important;
    position: relative !important;
}

/* Reglas específicas con máxima especificidad */
body .modal#modalMiPerfil {
    z-index: 99999 !important;
}

body .modal#modalMiPerfil.show {
    z-index: 99999 !important;
    display: block !important;
}

html body .modal#modalMiPerfil {
    z-index: 99999 !important;
}

/* Fix específico para el contenedor principal */
#contenedor-principal .modal#modalMiPerfil {
    z-index: 99999 !important;
}

/* Asegurar que funcione en el contexto del dashboard */
.page-wrapper .modal#modalMiPerfil,
.body-wrapper .modal#modalMiPerfil {
    z-index: 99999 !important;
}
</style>