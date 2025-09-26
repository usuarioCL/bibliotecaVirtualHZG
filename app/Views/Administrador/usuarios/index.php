<div class="container">
    <!-- Estilos para la funcionalidad de búsqueda -->
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .btn:disabled {
            opacity: 0.6;
        }
    </style>

    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Usuarios</h4>
            <p class="text-muted mb-0">Usuarios registrados en el sistema bibliotecario</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFiltrarUsuarios">
                <i class="ti ti-filter"></i> Filtrar
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                <i class="ti ti-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card mt-1">
        <div class="card-body">
            <!-- Estadísticas rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-primary"><?= isset($totalUsuarios) ? $totalUsuarios : 0 ?></h5>
                        <small class="text-muted">Total Usuarios</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-warning"><?= isset($administradores) ? $administradores : 0 ?></h5>
                        <small class="text-muted">Administradores</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-info"><?= isset($docentes) ? $docentes : 0 ?></h5>
                        <small class="text-muted">Docentes</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-success"><?= isset($estudiantes) ? $estudiantes : 0 ?></h5>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Usuario</th>
                            <th>Información Personal</th>
                            <th>Email</th>
                            <th>Nivel de Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= str_pad($usuario['idusuario'], 4, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <?= strtoupper(substr($usuario['nomuser'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= $usuario['nomuser'] ?></h6>
                                                <small class="text-muted">@<?= $usuario['nomuser'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?php if (isset($usuario['nombres']) && isset($usuario['apellidos'])): ?>
                                                <strong><?= $usuario['nombres'] . ' ' . $usuario['apellidos'] ?></strong><br>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <i class="ti ti-id"></i> <?= $usuario['numerodoc'] ?? 'Sin documento' ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($usuario['email'])): ?>
                                            <span class="text-primary"><?= $usuario['email'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="ti ti-mail-off"></i> Sin email</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $badgeClass = match($usuario['nivelacceso']) {
                                            'admin' => 'bg-warning text-dark',
                                            'docente' => 'bg-info',
                                            'estudiante' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        $icon = match($usuario['nivelacceso']) {
                                            'admin' => 'ti-shield-star',
                                            'docente' => 'ti-user-check',
                                            'estudiante' => 'ti-school',
                                            default => 'ti-user'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>" title="<?= ucfirst($usuario['nivelacceso']) ?>">
                                            <i class="<?= $icon ?> me-1"></i>
                                            <?= ucfirst($usuario['nivelacceso']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Ver perfil" onclick="verPerfilUsuario(<?= $usuario['idusuario'] ?>)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" title="Editar usuario" onclick="editarUsuario(<?= $usuario['idusuario'] ?>)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <?php if ($usuario['nivelacceso'] !== 'admin'): ?>
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar usuario" onclick="eliminarUsuario(<?= $usuario['idusuario'] ?>)">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-users fs-1 mb-3"></i>
                                        <h5>No hay usuarios registrados</h5>
                                        <p class="mb-3">Comienza creando tu primer usuario del sistema</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                                            <i class="ti ti-plus"></i> Crear Usuario
                                        </button>
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

<!-- Modales para gestión de usuarios -->
<?php echo view('Administrador/modals/registrarusuario'); ?>

<script>
// Función para ver perfil de usuario
function verPerfilUsuario(idusuario) {
    fetch(`<?= base_url('usuarios/obtener') ?>/${idusuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Crear modal dinámico para mostrar perfil
                mostrarPerfilUsuario(data.usuario);
            } else {
                mostrarAlerta('Error al cargar perfil del usuario', 'danger');
            }
        })
        .catch(() => mostrarAlerta('Error de conexión', 'danger'));
}

// Función para mostrar perfil de usuario en modal
function mostrarPerfilUsuario(usuario) {
    const modalContent = `
        <div class="modal fade" id="modalPerfilUsuario" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ti ti-user-circle me-2"></i>Perfil de Usuario
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Información de Usuario</h6>
                                <p><strong>Usuario:</strong> ${usuario.nomuser}</p>
                                <p><strong>Nivel:</strong> 
                                    <span class="badge bg-primary">${usuario.nivelacceso}</span>
                                </p>
                                <p><strong>Email:</strong> ${usuario.email || 'No registrado'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Información Personal</h6>
                                <p><strong>Nombres:</strong> ${usuario.nombres || 'No registrado'}</p>
                                <p><strong>Apellidos:</strong> ${usuario.apellidos || 'No registrado'}</p>
                                <p><strong>Documento:</strong> ${usuario.numerodoc || 'No registrado'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior si existe
    const existingModal = document.getElementById('modalPerfilUsuario');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Agregar nuevo modal
    document.body.insertAdjacentHTML('beforeend', modalContent);
    const modal = new bootstrap.Modal(document.getElementById('modalPerfilUsuario'));
    modal.show();
}

// Función para editar usuario
function editarUsuario(idusuario) {
    // Implementar funcionalidad de edición
    mostrarAlerta('Función de edición en desarrollo', 'info');
}

// Función para eliminar usuario
function eliminarUsuario(idusuario) {
    if (confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
        fetch(`<?= base_url('usuarios/eliminar') ?>/${idusuario}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                mostrarAlerta('Usuario eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Error al eliminar usuario', 'danger');
            }
        })
        .catch(() => mostrarAlerta('Error de conexión', 'danger'));
    }
}
// Generar usuario y email automáticamente
function generarUsuarioYEmail() {
    const nombres = document.getElementById('nombres');
    const apellidos = document.getElementById('apellidos');
    
    if (!nombres || !apellidos) {
        console.error('No se encontraron los campos nombres o apellidos');
        return;
    }
    
    const nombresValue = nombres.value.trim();
    const apellidosValue = apellidos.value.trim();

    console.log('Generando usuario con:', nombresValue, apellidosValue);

    if (nombresValue && apellidosValue) {
        // Limpiar caracteres especiales y convertir a minúsculas
        const primerNombre = nombresValue.toLowerCase().split(' ')[0].replace(/[^a-z]/g, '');
        const primerApellido = apellidosValue.toLowerCase().split(' ')[0].replace(/[^a-z]/g, '');
        
        if (primerNombre && primerApellido) {
            const usuario = primerNombre + '.' + primerApellido;
            const email = usuario + '@bibliotecavirtual.edu.pe';

            console.log('Usuario generado:', usuario);
            console.log('Email generado:', email);

            const nomuser_preview = document.getElementById('nomuser_preview');
            const email_preview = document.getElementById('email_preview');
            const nomuser = document.getElementById('nomuser');
            const emailField = document.getElementById('email');

            if (nomuser_preview) nomuser_preview.value = usuario;
            if (email_preview) email_preview.value = email;
            if (nomuser) nomuser.value = usuario;
            if (emailField) emailField.value = email;
        }
    } else {
        // Limpiar campos si están vacíos
        const nomuser_preview = document.getElementById('nomuser_preview');
        const email_preview = document.getElementById('email_preview');
        const nomuser = document.getElementById('nomuser');
        const emailField = document.getElementById('email');

        if (nomuser_preview) nomuser_preview.value = '';
        if (email_preview) email_preview.value = '';
        if (nomuser) nomuser.value = '';
        if (emailField) emailField.value = '';
    }
}

// Event listeners para generación automática
function configurarEventListeners() {
    const nombres = document.getElementById('nombres');
    const apellidos = document.getElementById('apellidos');
    
    if (nombres && apellidos) {
        console.log('Configurando event listeners para generación automática');
        
        // Remover listeners existentes para evitar duplicados
        nombres.removeEventListener('input', generarUsuarioYEmail);
        apellidos.removeEventListener('input', generarUsuarioYEmail);
        
        // Agregar nuevos listeners
        nombres.addEventListener('input', generarUsuarioYEmail);
        apellidos.addEventListener('input', generarUsuarioYEmail);
        
        // También configurar evento keyup para mayor responsividad
        nombres.addEventListener('keyup', generarUsuarioYEmail);
        apellidos.addEventListener('keyup', generarUsuarioYEmail);
        
        console.log('Event listeners configurados correctamente');
    } else {
        console.error('No se pudieron encontrar los campos nombres o apellidos para configurar listeners');
    }
}

// Event listeners para generación automática
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, configurando listeners iniciales');
    configurarEventListeners();
    
    // También configurar listeners cuando se abra el modal
    const modalElement = document.getElementById('modalNuevoUsuario');
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function() {
            console.log('Modal abierto, reconfigurando listeners');
            setTimeout(configurarEventListeners, 100);
        });
    }
});

function registrarPersonaYUsuario() {
    console.log('Iniciando proceso de registro');
    
    const form = document.getElementById('formNuevoUsuario');
    if (!form) {
        mostrarAlertaModal('Error: No se encontró el formulario', 'danger');
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
        mostrarAlertaModal('Por favor complete nombres y apellidos', 'danger');
        return;
    }
    
    // Validar usuario y email generados
    if (!nomuser || !email) {
        console.log('Intentando regenerar usuario y email...');
        generarUsuarioYEmail();
        
        // Verificar nuevamente después de intentar generar
        const nomuserRecheck = document.getElementById('nomuser')?.value?.trim();
        const emailRecheck = document.getElementById('email')?.value?.trim();
        
        if (!nomuserRecheck || !emailRecheck) {
            mostrarAlertaModal('Error al generar usuario y email. Por favor verifique nombres y apellidos', 'danger');
            return;
        }
    }

    console.log('Enviando datos al servidor...');
    
    fetch('<?= base_url('usuarios/crear-completo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Respuesta del servidor recibida:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Datos de respuesta:', data);
        if (data.status === 'success') {
            mostrarAlertaModal(`¡Registro exitoso!<br>Usuario creado: <strong>${data.usuario}</strong><br>Email: <strong>${data.email}</strong>`, 'success');
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario')).hide();
                location.reload();
            }, 2500);
        } else {
            mostrarAlertaModal(data.message || 'Error al registrar persona y usuario', 'danger');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        mostrarAlertaModal('Error de conexión', 'danger');
    });
}

// Función para mostrar alertas en modales
function mostrarAlertaModal(mensaje, tipo = 'info') {
    const alerta = document.getElementById('alertaValidacion');
    if (alerta) {
        alerta.className = `alert alert-${tipo} mt-2`;
        alerta.innerHTML = mensaje;
        alerta.classList.remove('d-none');
    }
}

// Limpiar formulario cuando se cierra el modal
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('modalNuevoUsuario')) {
        document.getElementById('modalNuevoUsuario').addEventListener('hidden.bs.modal', function() {
            if (document.getElementById('formNuevoUsuario')) {
                document.getElementById('formNuevoUsuario').reset();
                if (document.getElementById('nomuser_preview')) document.getElementById('nomuser_preview').value = '';
                if (document.getElementById('email_preview')) document.getElementById('email_preview').value = '';
                if (document.getElementById('nomuser')) document.getElementById('nomuser').value = '';
                if (document.getElementById('email')) document.getElementById('email').value = '';
                if (document.getElementById('alertaValidacion')) document.getElementById('alertaValidacion').classList.add('d-none');
            }
        });
    }

    // Inicializar tooltips si están disponibles
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Función para buscar estudiante por DNI
function buscarPorDni() {
    const numerodoc = document.getElementById('numerodoc')?.value?.trim();
    
    if (!numerodoc) {
        mostrarAlertaModal('Por favor ingrese un número de documento', 'warning');
        return;
    }
    
    if (numerodoc.length < 8) {
        mostrarAlertaModal('El número de documento debe tener al menos 8 dígitos', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    const botonBuscar = event.target;
    const textoOriginal = botonBuscar.innerHTML;
    botonBuscar.innerHTML = '<i class="icon tabler-loader-2 fs-6 spin"></i>';
    botonBuscar.disabled = true;
    
    const infoBusqueda = document.getElementById('info-busqueda');
    infoBusqueda.className = 'form-text text-info';
    infoBusqueda.innerHTML = '<i class="ti ti-search"></i> Buscando...';
    infoBusqueda.classList.remove('d-none');
    
    fetch(`<?= base_url('usuarios/buscar-por-dni') ?>?numerodoc=${encodeURIComponent(numerodoc)}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.encontrado) {
            // Autocompletar campos con los datos encontrados
            autocompletarCampos(data.datos);
            
            infoBusqueda.className = 'form-text text-success';
            infoBusqueda.innerHTML = `<i class="ti ti-check"></i> Estudiante encontrado: ${data.datos.apellidos}, ${data.datos.nombres}`;
            
            if (data.datos.tiene_usuario) {
                mostrarAlertaModal(`Este estudiante ya tiene un usuario: <strong>${data.datos.usuario_existente}</strong>`, 'warning');
            } else {
                mostrarAlertaModal('Datos autocompletados correctamente. Puede proceder con el registro.', 'success');
            }
        } else {
            infoBusqueda.className = 'form-text text-muted';
            infoBusqueda.innerHTML = '<i class="ti ti-info-circle"></i> No se encontró un estudiante con este DNI. Puede registrar manualmente.';
            
            // Limpiar campos por si tenían datos anteriores
            limpiarFormulario();
        }
    })
    .catch(error => {
        console.error('Error en búsqueda:', error);
        infoBusqueda.className = 'form-text text-danger';
        infoBusqueda.innerHTML = '<i class="ti ti-alert-circle"></i> Error al buscar. Intente nuevamente.';
        mostrarAlertaModal('Error de conexión al buscar estudiante', 'danger');
    })
    .finally(() => {
        // Restaurar botón
        botonBuscar.innerHTML = textoOriginal;
        botonBuscar.disabled = false;
    });
}

// Función para autocompletar campos con datos encontrados
function autocompletarCampos(datos) {
    console.log('Autocompletando con datos:', datos);
    
    // Campos básicos
    if (datos.apellidos) document.getElementById('apellidos').value = datos.apellidos;
    if (datos.nombres) document.getElementById('nombres').value = datos.nombres;
    if (datos.tipodoc) document.getElementById('tipodoc').value = datos.tipodoc;
    if (datos.telefono) document.getElementById('telefono').value = datos.telefono;
    if (datos.direccion) document.getElementById('direccion').value = datos.direccion;
    if (datos.genero) document.getElementById('genero').value = datos.genero;
    
    // Generar usuario y email automáticamente después de autocompletar nombres
    setTimeout(() => {
        generarUsuarioYEmail();
    }, 100);
    
    // Mostrar información adicional si existe
    if (datos.nivel_academico && datos.nivel_academico !== 'Sin matrícula') {
        const infoBusqueda = document.getElementById('info-busqueda');
        infoBusqueda.innerHTML += `<br><small>Nivel académico: ${datos.nivel_academico}</small>`;
    }
}

// Función para limpiar formulario
function limpiarFormulario() {
    const campos = ['apellidos', 'nombres', 'telefono', 'direccion', 'genero', 'nomuser_preview', 'email_preview', 'nomuser', 'email'];
    campos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) elemento.value = '';
    });
    
    // También limpiar los selects
    const selects = ['tipodoc', 'genero'];
    selects.forEach(select => {
        const elemento = document.getElementById(select);
        if (elemento) elemento.selectedIndex = 0;
    });
}

// Agregar evento para búsqueda automática al escribir DNI (opcional)
document.addEventListener('DOMContentLoaded', function() {
    const numerodocField = document.getElementById('numerodoc');
    if (numerodocField) {
        numerodocField.addEventListener('blur', function() {
            const valor = this.value.trim();
            if (valor.length >= 8) {
                // Auto-buscar después de 500ms de inactividad
                setTimeout(() => {
                    if (this.value.trim() === valor && valor.length >= 8) {
                        buscarPorDni();
                    }
                }, 500);
            }
        });
    }
});

</script>
