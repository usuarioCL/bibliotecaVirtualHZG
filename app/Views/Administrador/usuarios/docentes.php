<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-user-check text-primary me-2"></i>
                        Gestión de Docentes
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
                            <li class="breadcrumb-item active">Docentes</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra los docentes registrados en el sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltrarDocentes">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoDocente">
                        <i class="ti ti-plus"></i> Registrar Docente
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
                            <i class="ti ti-user-check text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($totalDocentes) ? number_format($totalDocentes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Docentes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-user-star text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($docentesActivos) ? number_format($docentesActivos) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Docentes Activos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-users text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($docentesPrimaria) ? number_format($docentesPrimaria) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Primaria</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-certificate text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($docentesSecundaria) ? number_format($docentesSecundaria) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Secundaria</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de docentes con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Docentes Registrados
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona los docentes del sistema educativo</p>
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
                <table class="table table-hover align-middle mb-0" id="tablaDocentes">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Código</th>
                            <th class="border-0 px-3 py-3">Docente</th>
                            <th class="border-0 px-3 py-3">Documento</th>
                            <th class="border-0 px-3 py-3">Información de Contacto</th>
                            <th class="border-0 text-center px-3 py-3">Estado de Usuario</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($docentes)): ?>
                            <?php foreach ($docentes as $docente): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <span class="badge bg-light text-dark fs-6 fw-semibold px-3 py-2"><?= str_pad($docente['idusuario'], 5, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar rounded-3 text-white d-flex align-items-center justify-content-center me-3 shadow-lg" style="width: 42px; height: 42px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                <?= strtoupper(substr($docente['nombres'], 0, 1)) . strtoupper(substr($docente['apellidos'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark"><?= $docente['nombres'] . ' ' . $docente['apellidos'] ?></h6>
                                                <small class="text-muted d-flex align-items-center">
                                                    <i class="ti ti-mail me-1"></i><?= $docente['email'] ?? 'Sin email registrado' ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="ti ti-id-badge me-2 text-secondary"></i>
                                            <span class="fw-medium"><?= $docente['numerodoc'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <?php if (!empty($docente['telefono'])): ?>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="ti ti-phone me-2 text-primary"></i>
                                                    <span class="text-primary fw-medium"><?= esc($docente['telefono']) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center mb-1 text-muted">
                                                    <i class="ti ti-phone-off me-2"></i>
                                                    <span class="small">Sin teléfono</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($docente['direccion'])): ?>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="ti ti-map-pin me-2"></i>
                                                    <span class="text-truncate" style="max-width: 200px;" title="<?= esc($docente['direccion']) ?>">
                                                        <?= esc($docente['direccion']) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <?php if (!empty($docente['nomuser'])): ?>
                                            <span class="badge bg-success text-white px-3 py-2 fw-medium rounded-pill">
                                                <i class="ti ti-check me-1"></i>Usuario Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark px-3 py-2 fw-medium rounded-pill">
                                                <i class="ti ti-alert-triangle me-1"></i>Sin Usuario
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-outline-info btn-sm" type="button" onclick="verDetalleDocente(<?= $docente['idusuario'] ?>)" title="Ver detalles">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="editarDocente(<?= $docente['idusuario'] ?>)" title="Editar docente">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <?php if (empty($docente['nomuser'])): ?>
                                                <button class="btn btn-outline-success btn-sm" type="button" onclick="crearUsuarioDocente(<?= $docente['idusuario'] ?>)" title="Crear usuario">
                                                    <i class="ti ti-user-plus"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state p-5">
                                        <div class="rounded-circle bg-light mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="ti ti-user-check text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="text-dark mb-2">No hay docentes registrados</h4>
                                        <p class="text-muted mb-4 lead">Comienza registrando tu primer docente en el sistema bibliotecario</p>
                                        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoDocente">
                                            <i class="ti ti-plus me-2"></i> Registrar Primer Docente
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
        <?php if (!empty($docentes)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <div>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($docentes) ?> docente(s) de <?= isset($totalDocentes) ? $totalDocentes : count($docentes) ?>
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

<!-- Modales para gestión de docentes -->
<?php echo view('Administrador/modals/registrardocente'); ?>
<?php echo view('Administrador/modals/detalledocente'); ?>
<?php echo view('Administrador/modals/filtrardocentes'); ?>

<script>
// Función para ver detalles de un docente
function verDetalleDocente(iddocente) {
    console.log('Ver detalles docente:', iddocente);
    
    // Verificar si el modal existe
    const modalElement = document.getElementById('modalDetalleDocente');
    if (!modalElement) {
        console.error('Modal no encontrado: modalDetalleDocente');
        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'Modal de detalles no encontrado',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    // Mostrar modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    fetch(`<?= base_url('docentes/detalle') ?>/${iddocente}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Cargar datos en el modal de detalles
                cargarDetalleDocente(data.docente, data.estadisticas);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al cargar detalles del docente',
                    confirmButtonColor: '#dc3545'
                });
                modal.hide();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudieron cargar los detalles del docente',
                confirmButtonColor: '#dc3545'
            });
            modal.hide();
        });
}

// Función para editar un docente
function editarDocente(iddocente) {
    console.log('Editar docente:', iddocente);
    
    Swal.fire({
        icon: 'info',
        title: 'Función en desarrollo',
        text: 'La edición de docentes estará disponible próximamente',
        confirmButtonColor: '#0d6efd'
    });
}

// Función para crear usuario para un docente
function crearUsuarioDocente(iddocente) {
    console.log('Crear usuario para docente:', iddocente);
    
    Swal.fire({
        title: '¿Crear usuario para este docente?',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="ti ti-user-plus text-primary" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-2">Se creará un usuario de acceso para este docente</p>
                <p class="text-muted small mb-0">El docente podrá acceder al sistema con sus credenciales.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, crear usuario',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('<?= base_url('docentes/crear-usuario') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    iddocente: iddocente
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data.status || data.status !== 'success') {
                    throw new Error(data.message || 'Error al crear usuario');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.showValidationMessage(`Error: ${error.message}`);
                throw error;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: '¡Usuario creado!',
                text: 'El usuario para el docente ha sido creado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

// Función para registrar nuevo docente
function registrarDocente() {
    console.log('Iniciando proceso de registro de docente');
    
    const form = document.getElementById('formNuevoDocente');
    if (!form) {
        Swal.fire({
            icon: 'error',
            title: 'Error del formulario',
            text: 'No se encontró el formulario de registro',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    const formData = new FormData(form);
    
    // Verificar campos obligatorios
    const apellidos = document.getElementById('apellidos')?.value?.trim();
    const nombres = document.getElementById('nombres')?.value?.trim();
    const numerodoc = document.getElementById('numerodoc')?.value?.trim();
    
    if (!apellidos || !nombres || !numerodoc) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor complete todos los campos obligatorios',
            confirmButtonColor: '#fd7e14'
        });
        return;
    }

    console.log('Enviando datos al servidor...');
    
    // Mostrar loading
    Swal.fire({
        title: 'Registrando docente...',
        html: 'Por favor espere mientras se procesa la información',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('<?= base_url('docentes/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos de respuesta:', data);
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="ti ti-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <p class="mb-2">Docente registrado correctamente:</p>
                        <p class="fw-bold text-primary">${data.datos.nombres} ${data.datos.apellidos}</p>
                    </div>
                `,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoDocente')).hide();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error en el registro',
                text: data.message || 'Error al registrar docente',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Función para cargar detalles en el modal
function cargarDetalleDocente(docente, estadisticas) {
    console.log('Cargando detalles:', docente, estadisticas);
    
    // Llenar modal con datos del docente
    const detalleContent = document.getElementById('detalleDocenteContent');
    if (detalleContent) {
        detalleContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">
                        <i class="ti ti-user me-2"></i>Información Personal
                    </h6>
                    <div class="mb-2">
                        <strong>Nombre:</strong> 
                        <span class="text-muted">${docente.nombres} ${docente.apellidos}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Documento:</strong> 
                        <span class="text-muted">${docente.numerodoc}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong> 
                        <span class="text-muted">${docente.email || 'No registrado'}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Teléfono:</strong> 
                        <span class="text-muted">${docente.telefono || 'No registrado'}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">
                        <i class="ti ti-briefcase me-2"></i>Información Profesional
                    </h6>
                    <div class="mb-2">
                        <strong>Especialidad:</strong> 
                        <span class="text-muted">${docente.especialidad || 'No especificada'}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Usuario del Sistema:</strong> 
                        <span class="text-muted">${docente.nomuser || 'Sin usuario asignado'}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Fecha de Registro:</strong> 
                        <span class="text-muted">${docente.fecharegistro || 'No disponible'}</span>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="text-muted mb-3">
                        <i class="ti ti-chart-bar me-2"></i>Estadísticas de Biblioteca
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <h4 class="text-primary mb-1">${estadisticas?.prestamos_realizados || 0}</h4>
                                <small class="text-muted">Préstamos Realizados</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="text-success mb-1">${estadisticas?.recursos_utilizados || 0}</h4>
                                <small class="text-muted">Recursos Utilizados</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="text-info mb-1">${estadisticas?.actividad_mensual || 0}</h4>
                                <small class="text-muted">Actividad Mensual</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

// Función para aplicar filtros
function aplicarFiltros() {
    console.log('Aplicando filtros');
    // Implementar lógica de filtros
}

// Función para limpiar formulario cuando se cierra el modal
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('modalNuevoDocente')) {
        document.getElementById('modalNuevoDocente').addEventListener('hidden.bs.modal', function() {
            if (document.getElementById('formNuevoDocente')) {
                document.getElementById('formNuevoDocente').reset();
                if (document.getElementById('alertaValidacion')) {
                    document.getElementById('alertaValidacion').classList.add('d-none');
                }
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

// Función para buscar docente por DNI
function buscarPorDni() {
    const numerodoc = document.getElementById('numerodoc')?.value?.trim();
    
    if (!numerodoc) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo requerido',
            text: 'Por favor ingrese un número de documento',
            confirmButtonColor: '#fd7e14'
        });
        return;
    }
    
    if (numerodoc.length < 8) {
        Swal.fire({
            icon: 'warning',
            title: 'Documento inválido',
            text: 'El número de documento debe tener al menos 8 dígitos',
            confirmButtonColor: '#fd7e14'
        });
        return;
    }
    
    // Mostrar indicador de carga
    const botonBuscar = event.target;
    const textoOriginal = botonBuscar.innerHTML;
    botonBuscar.innerHTML = '<i class="ti ti-loader-2 spin"></i>';
    botonBuscar.disabled = true;
    
    const infoBusqueda = document.getElementById('info-busqueda');
    if (infoBusqueda) {
        infoBusqueda.className = 'form-text text-info';
        infoBusqueda.innerHTML = '<i class="ti ti-search"></i> Buscando...';
        infoBusqueda.classList.remove('d-none');
    }
    
    fetch(`<?= base_url('docentes/buscar-por-dni') ?>?numerodoc=${encodeURIComponent(numerodoc)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success' && data.encontrado) {
            // Autocompletar campos con los datos encontrados
            autocompletarCampos(data.datos);
            
            if (infoBusqueda) {
                infoBusqueda.className = 'form-text text-success';
                infoBusqueda.innerHTML = `<i class="ti ti-check"></i> Persona encontrada: ${data.datos.apellidos}, ${data.datos.nombres}`;
            }
            
            if (data.datos.es_docente) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Docente ya registrado',
                    text: 'Esta persona ya es docente en el sistema',
                    confirmButtonColor: '#fd7e14'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Datos encontrados',
                    text: 'Datos autocompletados correctamente. Puede proceder con el registro.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        } else {
            if (infoBusqueda) {
                infoBusqueda.className = 'form-text text-muted';
                infoBusqueda.innerHTML = '<i class="ti ti-info-circle"></i> No se encontró una persona con este DNI. Puede registrar manualmente.';
            }
            
            // Limpiar campos por si tenían datos anteriores
            limpiarFormulario();
        }
    })
    .catch(error => {
        console.error('Error en búsqueda:', error);
        if (infoBusqueda) {
            infoBusqueda.className = 'form-text text-danger';
            infoBusqueda.innerHTML = '<i class="ti ti-alert-circle"></i> Error al buscar. Intente nuevamente.';
        }
        Swal.fire({
            icon: 'error',
            title: 'Error de búsqueda',
            text: 'Error de conexión al buscar persona',
            confirmButtonColor: '#dc3545'
        });
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
    if (datos.email) document.getElementById('email').value = datos.email;
}

// Función para limpiar formulario
function limpiarFormulario() {
    const campos = ['apellidos', 'nombres', 'telefono', 'direccion', 'genero', 'email', 'especialidad'];
    campos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) elemento.value = '';
    });
    
    // También limpiar los selects
    const selects = ['tipodoc', 'genero', 'nivel_asignado'];
    selects.forEach(select => {
        const elemento = document.getElementById(select);
        if (elemento) elemento.selectedIndex = 0;
    });
}

</script>