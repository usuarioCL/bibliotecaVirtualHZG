<!-- Modal para nuevo usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nueva Persona y Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoUsuario" autocomplete="off">
                    <!-- Datos de la Persona -->
                    <h6 class="text-primary mb-3">Datos Personales</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required autofocus placeholder="Ej: Pérez Gómez" oninput="generarUsuarioYEmailInline()" onkeyup="generarUsuarioYEmailInline()" onblur="generarUsuarioYEmailInline()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required placeholder="Ej: Juan Carlos" oninput="generarUsuarioYEmailInline()" onkeyup="generarUsuarioYEmailInline()" onblur="generarUsuarioYEmailInline()">
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
                                <div class="input-group">
                                    <input type="text" class="form-control" id="numerodoc" name="numerodoc" required maxlength="15" placeholder="Ej: 12345678" oninput="generarUsuarioYEmailInline()" onkeyup="generarUsuarioYEmailInline()" onblur="generarUsuarioYEmailInline()">
                                </div>
                                <div id="info-busqueda" class="form-text d-none"></div>
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
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15" placeholder="Ej: 999888777">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" maxlength="100" placeholder="Ej: Av. Siempre Viva 123">
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
                                <label for="passuser" class="form-label">Contraseña <small class="text-muted">(auto-generada)</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="passuser" name="passuser" required minlength="6" placeholder="Se generará automáticamente" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Mostrar/Ocultar contraseña" onclick="togglePasswordVisibility()">
                                        <i class="ti ti-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Se genera automáticamente con el DNI</div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomuser" class="form-label">Usuario <small class="text-muted">(editable)</small></label>
                                <input type="text" class="form-control" id="nomuser" name="nomuser" placeholder="Se generará automáticamente" title="Usuario del sistema - Se puede editar">
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <small class="text-muted">(editable)</small></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Se generará automáticamente" title="Email institucional - Se puede editar">
                                
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacion" class="alert d-none mt-2"></div>
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
// Función inline para generar usuario, email y contraseña con DNI
function generarUsuarioYEmailInline() {
    try {
        const nombres = document.getElementById('nombres');
        const apellidos = document.getElementById('apellidos');
        const numerodoc = document.getElementById('numerodoc');
        const nomuser = document.getElementById('nomuser');
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('passuser');
        
        if (!nombres || !apellidos || !numerodoc || !nomuser || !emailField || !passwordField) {
            console.warn('Elementos del formulario no encontrados');
            return;
        }
        
        const nombresValue = nombres.value.trim();
        const apellidosValue = apellidos.value.trim();
        const dniValue = numerodoc.value.trim();

        if (nombresValue && apellidosValue && dniValue) {
            // Limpiar caracteres especiales, mantener acentos y convertir a minúsculas
            const primerNombre = nombresValue.toLowerCase()
                .split(' ')[0]
                .replace(/[^a-záéíóúñ]/g, '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, ''); // Quitar acentos para usuario
                
            const primerApellido = apellidosValue.toLowerCase()
                .split(' ')[0]
                .replace(/[^a-záéíóúñ]/g, '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, ''); // Quitar acentos para usuario
            
            if (primerNombre && primerApellido && dniValue.length >= 8) {
                // Generar usuario con formato: nombre.apellido
                const usuario = `${primerNombre}.${primerApellido}`;
                
                // Generar email con DNI: dni@bibliohzg.pe
                const email = `${dniValue}@bibliohzg.pe`;
                
                // Generar contraseña con DNI: HZG + DNI (ej: HZG12345678)
                const password = `${dniValue}`;

                console.log('✅ Usuario generado:', usuario);
                console.log('✅ Email generado:', email);
                console.log('✅ Contraseña generada:', password);

                nomuser.value = usuario;
                emailField.value = email;
                passwordField.value = password;
                
                // Agregar visual feedback mejorado
                const campos = [nomuser, emailField, passwordField];
                campos.forEach(campo => {
                    campo.style.backgroundColor = '#d4edda';
                    campo.style.borderColor = '#28a745';
                });
                
                // Limpiar feedback después de 2 segundos
                setTimeout(() => {
                    campos.forEach(campo => {
                        campo.style.backgroundColor = '';
                        campo.style.borderColor = '';
                    });
                }, 2000);
            } else {
                console.warn('⚠️ No se pudieron procesar los datos o DNI incompleto');
            }
        } else if (!nombresValue || !apellidosValue || !dniValue) {
            // Limpiar campos si alguno está vacío, pero solo si fueron generados automáticamente
            if (emailField.value.includes('@bibliohzg.pe') && passwordField.value.includes('HZG')) {
                nomuser.value = '';
                emailField.value = '';
                passwordField.value = '';
                console.log('🧹 Campos auto-generados limpiados por falta de datos');
            }
        }
    } catch (error) {
        console.error('❌ Error en generación automática:', error);
    }
}
// Función para limpiar completamente el modal
function limpiarModalCompleto() {
    console.log('🧹 INICIANDO LIMPIEZA COMPLETA DEL MODAL');
    
    try {
        const form = document.getElementById('formNuevoUsuario');
        
        // 1. Resetear formulario si existe
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            console.log('✅ Formulario reseteado');
        }
        
        // 2. Lista completa de campos a limpiar FORZADAMENTE
        const todosLosCampos = [
            'apellidos', 'nombres', 'tipodoc', 'numerodoc', 'genero',
            'telefono', 'direccion', 'nivelacceso', 'passuser', 
            'nomuser', 'email'
        ];
        
        // 3. Limpiar cada campo con múltiples métodos
        todosLosCampos.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                // Método 1: value
                elemento.value = '';
                // Método 2: setAttribute
                elemento.setAttribute('value', '');
                // Método 3: removeAttribute si es necesario
                if (elemento.hasAttribute('value')) {
                    elemento.removeAttribute('value');
                    elemento.setAttribute('value', '');
                }
                // Método 4: limpiar estilos
                elemento.style.backgroundColor = '';
                elemento.style.borderColor = '';
                elemento.style.boxShadow = '';
                // Método 5: limpiar clases de validación
                elemento.classList.remove('is-valid', 'is-invalid');
                
                console.log('🔄 Campo FORZADAMENTE limpiado:', id, '- Valor actual:', elemento.value);
            }
        });
        
        // 4. Limpiar selects de manera especial
        const selects = ['tipodoc', 'genero', 'nivelacceso'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                select.selectedIndex = 0;
                select.value = '';
                if (select.options && select.options.length > 0) {
                    select.options[0].selected = true;
                }
                select.style.backgroundColor = '';
                select.classList.remove('is-valid', 'is-invalid');
                console.log('🔄 Select FORZADAMENTE limpiado:', selectId);
            }
        });
        
        // 5. Limpiar alertas y elementos de información
        const elementosAOcultar = ['info-busqueda', 'alertaValidacion'];
        elementosAOcultar.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.classList.add('d-none');
                elemento.innerHTML = '';
                elemento.className = elemento.className.replace(/alert-\w+/g, '');
                console.log('🔄 Elemento limpiado:', id);
            }
        });
        
        console.log('✅ LIMPIEZA COMPLETA EXITOSA - TODOS LOS CAMPOS DEBEN ESTAR VACÍOS');
        
        // 6. Verificación final después de un breve delay
        setTimeout(() => {
            console.log('🔍 VERIFICACIÓN FINAL DE LIMPIEZA:');
            todosLosCampos.forEach(id => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    if (elemento.value !== '') {
                        console.warn('⚠️ CAMPO AÚN TIENE VALOR:', id, '=', elemento.value);
                        elemento.value = '';
                        elemento.setAttribute('value', '');
                    } else {
                        console.log('✅ Campo verificado limpio:', id);
                    }
                }
            });
        }, 50);
        
    } catch (error) {
        console.error('❌ ERROR DURANTE LA LIMPIEZA:', error);
    }
}

// Event listeners para el modal - VERSION ROBUSTA
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando event listeners del modal');
    
    function configurarModal() {
        const modal = document.getElementById('modalNuevoUsuario');
        const form = document.getElementById('formNuevoUsuario');
        
        if (!modal) {
            console.warn('⚠️ Modal no encontrado, reintentando...');
            setTimeout(configurarModal, 500);
            return;
        }
        
        console.log('✅ Modal encontrado, configurando listeners');
        
        // 1. Listener principal para cuando se cierra el modal
        modal.addEventListener('hidden.bs.modal', function(e) {
            console.log('🔥 MODAL CERRADO - EVENTO hidden.bs.modal');
            limpiarModalCompleto();
        });
        
        // 2. Listener para cuando se muestra el modal
        modal.addEventListener('shown.bs.modal', function() {
            console.log('📖 Modal mostrado, verificando auto-generación');
            setTimeout(generarUsuarioYEmailInline, 100);
        });
        
        // 3. Listener adicional para botones de cerrar
        const botonesCerrar = modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-secondary');
        botonesCerrar.forEach(boton => {
            boton.addEventListener('click', function() {
                console.log('🔥 BOTÓN CERRAR CLICKEADO');
                // Dar tiempo a que Bootstrap cierre el modal
                setTimeout(limpiarModalCompleto, 200);
            });
        });
        
        // 4. Listener para click en backdrop
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                console.log('🔥 CLICK EN BACKDROP');
                setTimeout(limpiarModalCompleto, 200);
            }
        });
        
        // 5. Listener para tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                console.log('🔥 TECLA ESC PRESIONADA');
                setTimeout(limpiarModalCompleto, 200);
            }
        });
        
        // 6. Configurar botón de mostrar/ocultar contraseña
        const togglePasswordBtn = document.getElementById('togglePassword');
        if (togglePasswordBtn) {
            console.log('✅ Botón de contraseña encontrado, configurando listener');
            togglePasswordBtn.addEventListener('click', function(e) {
                console.log('🔘 Click detectado en botón de contraseña');
                e.preventDefault();
                e.stopPropagation();
                togglePasswordVisibility();
            });
        } else {
            console.warn('⚠️ Botón de contraseña no encontrado');
        }
        
        console.log('✅ Todos los event listeners configurados');
    }
    
    configurarModal();
});

// Listener adicional para contenido cargado vía AJAX
document.addEventListener('click', function(e) {
    // Listener para el botón de mostrar/ocultar contraseña (delegación de eventos)
    // Detecta clic en el botón o en el icono dentro del botón
    if (e.target && (e.target.id === 'togglePassword' || e.target.closest('#togglePassword'))) {
        console.log('🔄 Click en botón de contraseña detectado vía delegación');
        e.preventDefault();
        togglePasswordVisibility();
        return;
    }
    
    // Listener para abrir modal vía AJAX
    if (e.target && e.target.getAttribute('data-bs-target') === '#modalNuevoUsuario') {
        console.log('🔄 Modal abierto vía AJAX, reconfigurando...');
        setTimeout(() => {
            const modal = document.getElementById('modalNuevoUsuario');
            if (modal) {
                // Asegurar que los listeners estén activos
                modal.addEventListener('hidden.bs.modal', function(e) {
                    console.log('🔥 MODAL CERRADO VÍA AJAX');
                    limpiarModalCompleto();
                });
                
                // Reconfigurar botón de contraseña
                const togglePasswordBtn = document.getElementById('togglePassword');
                if (togglePasswordBtn) {
                    console.log('🔄 Reconfigurando botón de contraseña vía AJAX');
                    togglePasswordBtn.addEventListener('click', function(e) {
                        console.log('🔘 Click detectado en botón de contraseña (AJAX)');
                        e.preventDefault();
                        e.stopPropagation();
                        togglePasswordVisibility();
                    });
                }
            }
        }, 300);
    }
});

// Función para mostrar/ocultar contraseña
function togglePasswordVisibility() {
    console.log('🔍 Función togglePasswordVisibility ejecutada');
    
    const passwordField = document.getElementById('passuser');
    const eyeIcon = document.getElementById('eyeIcon');
    
    console.log('🔍 passwordField:', passwordField);
    console.log('🔍 eyeIcon:', eyeIcon);
    
    if (passwordField && eyeIcon) {
        const isPassword = passwordField.type === 'password';
        
        // Cambiar tipo de input
        passwordField.type = isPassword ? 'text' : 'password';
        
        // Cambiar icono
        eyeIcon.className = isPassword ? 'ti ti-eye-off' : 'ti ti-eye';
        
        // Log del cambio
        console.log(isPassword ? '👁️ Contraseña mostrada' : '🙈 Contraseña ocultada');
        
        // Agregar feedback visual temporal
        const toggleBtn = document.getElementById('togglePassword');
        if (toggleBtn) {
            toggleBtn.style.backgroundColor = isPassword ? '#e7f3ff' : '';
            setTimeout(() => {
                toggleBtn.style.backgroundColor = '';
            }, 150);
        }
    } else {
        console.error('❌ No se encontraron los elementos de la contraseña');
        console.error('passwordField existe:', !!passwordField);
        console.error('eyeIcon existe:', !!eyeIcon);
    }
}
// Función para registrar persona y usuario
function registrarPersonaYUsuario() {
    console.log('Iniciando proceso de registro');
    
    const form = document.getElementById('formNuevoUsuario');
    if (!form) {
        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'No se encontró el formulario. Por favor, recargue la página e intente nuevamente.',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    const formData = new FormData(form);
    
    // Verificar campos obligatorios
    const apellidos = document.getElementById('apellidos')?.value?.trim();
    const nombres = document.getElementById('nombres')?.value?.trim();
    const nomuser = document.getElementById('nomuser')?.value?.trim();
    const email = document.getElementById('email')?.value?.trim();
    
    console.log('Datos del formulario:');
    console.log('Apellidos:', apellidos);
    console.log('Nombres:', nombres);
    console.log('Usuario generado:', nomuser);
    console.log('Email generado:', email);

    // Validar que se hayan completado nombres y apellidos
    if (!apellidos || !nombres) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor complete nombres y apellidos',
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    // Validar usuario y email generados
    if (!nomuser || !email) {
        console.log('Intentando regenerar usuario y email...');
        generarUsuarioYEmailInline();
        
        // Verificar nuevamente después de intentar generar
        const nomuserRecheck = document.getElementById('nomuser')?.value?.trim();
        const emailRecheck = document.getElementById('email')?.value?.trim();
        
        if (!nomuserRecheck || !emailRecheck) {
            Swal.fire({
                icon: 'error',
                title: 'Error en la generación',
                text: 'Error al generar usuario y email. Por favor verifique que los datos de nombres, apellidos y DNI estén completos.',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Entendido'
            });
            return;
        }
    }

    // Mostrar loading mientras se procesa
    Swal.fire({
        title: 'Creando usuario...',
        html: 'Por favor espere mientras procesamos la información',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= base_url('usuarios/crear-completo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Cerrar el modal primero
            bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario')).hide();
            
            // Mostrar alerta de éxito con SweetAlert2
            const infoAdicional = data.tiene_matricula ? 
                '<br><span class="badge bg-success mt-2"><i class="ti ti-school me-1"></i>Incluye matrícula automática</span>' : '';
            
            Swal.fire({
                icon: 'success',
                title: '¡Usuario creado exitosamente!',
                html: `
                    <div class="text-start">
                        <p><strong>Usuario:</strong> ${data.usuario}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Contraseña:</strong> ${data.numero_documento} <small class="text-muted">(DNI)</small></p>
                        <p><strong>Nivel:</strong> <span class="badge bg-primary">${data.nivel_acceso}</span></p>
                        ${infoAdicional}
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#0d6efd',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 8000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal2-popup-large'
                }
            }).then((result) => {
                // Recargar la vista después de cerrar la alerta
                recargarVistaUsuarios();
            });
        } else {
            // Mostrar error con SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Error al crear usuario',
                text: data.message || 'Error al registrar persona y usuario',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Entendido'
            });
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Por favor, verifique su conexión e intente nuevamente.',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    });
}

// Función para recargar la vista de usuarios vía AJAX
function recargarVistaUsuarios() {
    console.log('🔄 Recargando vista de usuarios...');
    
    // Verificar si estamos en una vista AJAX (existe el contenedor principal)
    const contenedorPrincipal = document.getElementById('contenedor-principal');
    
    if (contenedorPrincipal) {
        // Vista AJAX - cargar solo el contenido de usuarios
        console.log('📄 Cargando vista de usuarios vía AJAX');
        contenedorPrincipal.innerHTML = '<div class="text-center py-5"><i class="ti ti-loader-2 spinner-border me-2"></i>Actualizando lista de usuarios...</div>';
        
        fetch('<?= base_url('usuarios') ?>')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.text();
            })
            .then(data => {
                contenedorPrincipal.innerHTML = data;
                
            })
            .catch(error => {
                console.error('❌ Error al recargar vista de usuarios:', error);
                
                // Mostrar error con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar',
                    text: 'No se pudo actualizar la lista de usuarios. ¿Desea recargar la página?',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Recargar página',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    } else {
                        // Mostrar mensaje de error en el contenedor
                        contenedorPrincipal.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <strong>Aviso:</strong> La lista podría no estar actualizada. 
                                <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="location.reload()">
                                    <i class="ti ti-refresh me-1"></i>Recargar página
                                </button>
                            </div>
                        `;
                    }
                });
            });
    } else {
        // Vista normal - recargar toda la página
        console.log('🔄 Recargando página completa');
        location.reload();
    }
}

// Hacer la función disponible globalmente
window.recargarVistaUsuarios = recargarVistaUsuarios;
</script>