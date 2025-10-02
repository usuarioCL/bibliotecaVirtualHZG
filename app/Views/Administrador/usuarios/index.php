<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-users text-primary me-2"></i>
                        Gestión de Usuarios
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra los usuarios registrados en el sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltrarUsuarios">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                        <i class="ti ti-plus"></i> Nuevo Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card primary h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="ti ti-users text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($totalUsuarios) ? number_format($totalUsuarios) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Usuarios</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-shield-lock text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($administradores) ? number_format($administradores) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Administradores</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-user-check text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($docentes) ? number_format($docentes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Docentes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-school text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estudiantes) ? number_format($estudiantes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Estudiantes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Usuarios
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona todos los usuarios del sistema</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="ti ti-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Información Personal</th>
                            <th class="border-0 px-3 py-3">Email</th>
                            <th class="border-0 text-center px-3 py-3">Nivel de Acceso</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr class="border-bottom">
                                    
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar rounded-3 text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 42px; height: 42px; font-weight: 600;">
                                                <?= strtoupper(substr($usuario['nomuser'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark"><?= $usuario['nomuser'] ?></h6>
                                                <small class="text-muted d-flex align-items-center">
                                                    <i class="ti ti-at me-1"></i><?= $usuario['nomuser'] ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <?php if (isset($usuario['nombres']) && isset($usuario['apellidos'])): ?>
                                                <div class="fw-semibold text-dark mb-1"><?= $usuario['nombres'] . ' ' . $usuario['apellidos'] ?></div>
                                            <?php endif; ?>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="ti ti-id-badge me-1 text-secondary"></i>
                                                <span><?= $usuario['numerodoc'] ?? 'Sin documento' ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if (!empty($usuario['email'])): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-mail me-2 text-primary"></i>
                                                <span class="text-primary fw-medium"><?= $usuario['email'] ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="ti ti-mail-off me-2"></i>
                                                <span class="small">Sin email registrado</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <?php 
                                        $badgeClass = match($usuario['nivelacceso']) {
                                            'admin' => 'bg-warning text-dark',
                                            'docente' => 'bg-info text-white',
                                            'estudiante' => 'bg-success text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        $icon = match($usuario['nivelacceso']) {
                                            'admin' => 'ti-shield-lock',
                                            'docente' => 'ti-user-check',
                                            'estudiante' => 'ti-school',
                                            default => 'ti-user'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> px-3 py-2 fw-medium rounded-pill" title="<?= ucfirst($usuario['nivelacceso']) ?>">
                                            <i class="ti <?= $icon ?> me-2"></i>
                                            <?= ucfirst($usuario['nivelacceso']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-outline-info btn-sm" type="button" onclick="verPerfilUsuario(<?= $usuario['idusuario'] ?>)" title="Ver perfil">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="editarUsuario(<?= $usuario['idusuario'] ?>)" title="Editar usuario">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" type="button" onclick="eliminarUsuario(<?= $usuario['idusuario'] ?>)" title="Eliminar usuario">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state p-5">
                                        <div class="rounded-circle bg-light mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="text-dark mb-2">No hay usuarios registrados</h4>
                                        <p class="text-muted mb-4 lead">Comienza creando tu primer usuario del sistema bibliotecario</p>
                                        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                                            <i class="ti ti-plus me-2"></i> Crear Primer Usuario
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer de la tarjeta con información adicional -->
        <?php if (!empty($usuarios)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <div>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($usuarios) ?> usuario(s) de <?= isset($totalUsuarios) ? $totalUsuarios : count($usuarios) ?>
                </div>
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="ti ti-clock me-1"></i>
                        Actualizado hace pocos minutos
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
