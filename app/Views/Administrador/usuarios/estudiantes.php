<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-school text-primary me-2"></i>
                        Gestión de Estudiantes
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('usuarios') ?>">Usuarios</a></li>
                            <li class="breadcrumb-item active">Estudiantes</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra las matrículas y estudiantes del sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltrarEstudiantes">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoEstudiante">
                        <i class="ti ti-plus"></i> Registrar Estudiante
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
                            <i class="ti ti-school text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($totalEstudiantes) ? number_format($totalEstudiantes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Estudiantes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-user-check text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estudiantesActivos) ? number_format($estudiantesActivos) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Matrículas Activas</p>
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
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estudiantesPrimaria) ? number_format($estudiantesPrimaria) : 0 ?></h3>
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
                    <h3 class="fw-bold text-info mb-1"><?= isset($estudiantesSecundaria) ? number_format($estudiantesSecundaria) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Secundaria</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de estudiantes con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Estudiantes Matriculados
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona las matrículas de estudiantes activos</p>
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
                <table class="table table-hover align-middle mb-0" id="tablaEstudiantes">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Código</th>
                            <th class="border-0 px-3 py-3">Estudiante</th>
                            <th class="border-0 px-3 py-3">Documento</th>
                            <th class="border-0 px-3 py-3">Grado y Sección</th>
                            <th class="border-0 text-center px-3 py-3">Nivel Académico</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estudiantes)): ?>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <span class="badge bg-light text-dark fs-6 fw-semibold px-3 py-2"><?= str_pad($estudiante['idmatricula'], 5, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar rounded-3 text-white d-flex align-items-center justify-content-center me-3 shadow-lg" style="width: 42px; height: 42px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <?= strtoupper(substr($estudiante['nombres'], 0, 1)) . strtoupper(substr($estudiante['apellidos'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark"><?= $estudiante['nombres'] . ' ' . $estudiante['apellidos'] ?></h6>
                                                <small class="text-muted d-flex align-items-center">
                                                    <i class="ti ti-mail me-1"></i><?= $estudiante['email'] ?? 'Sin email registrado' ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="ti ti-id-badge me-2 text-secondary"></i>
                                            <span class="fw-medium"><?= $estudiante['numerodoc'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="fw-semibold text-dark">
                                            <?= $estudiante['grado'] ?>° - Sección <?= $estudiante['seccion'] ?>
                                        </div>
                                        <small class="text-muted">Año Lectivo: <?= $estudiante['aniolectivo'] ?></small>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <?php 
                                        $nivelClass = match($estudiante['nivel']) {
                                            'Inicial' => 'bg-info text-white',
                                            'Primaria' => 'bg-warning text-dark',
                                            'Secundaria' => 'bg-success text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        $nivelIcon = match($estudiante['nivel']) {
                                            'Inicial' => 'ti-baby-carriage',
                                            'Primaria' => 'ti-users',
                                            'Secundaria' => 'ti-certificate',
                                            default => 'ti-school'
                                        };
                                        ?>
                                        <span class="badge <?= $nivelClass ?> px-3 py-2 fw-medium rounded-pill" title="<?= $estudiante['nivel'] ?>">
                                            <i class="ti <?= $nivelIcon ?> me-2"></i>
                                            <?= $estudiante['nivel'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <?php if ($estudiante['estadomatricula']): ?>
                                            <span class="badge bg-success text-white px-3 py-2 fw-medium rounded-pill">
                                                <i class="ti ti-check me-1"></i>Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger text-white px-3 py-2 fw-medium rounded-pill">
                                                <i class="ti ti-x me-1"></i>Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-outline-info btn-sm" type="button" onclick="verDetalleEstudiante(<?= $estudiante['idmatricula'] ?>)" title="Ver detalles">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="editarEstudiante(<?= $estudiante['idmatricula'] ?>)" title="Editar estudiante">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-warning btn-sm" type="button" onclick="cambiarEstadoMatricula(<?= $estudiante['idmatricula'] ?>, <?= $estudiante['estadomatricula'] ? 'false' : 'true' ?>)" title="Cambiar estado">
                                                <i class="ti ti-toggle-<?= $estudiante['estadomatricula'] ? 'left' : 'right' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state p-5">
                                        <div class="rounded-circle bg-light mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="ti ti-school text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="text-dark mb-2">No hay estudiantes matriculados</h4>
                                        <p class="text-muted mb-4 lead">Comienza matriculando tu primer estudiante en el sistema bibliotecario</p>
                                        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoEstudiante">
                                            <i class="ti ti-plus me-2"></i> Matricular Primer Estudiante
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
        <?php if (!empty($estudiantes)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <div>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($estudiantes) ?> estudiante(s) de <?= isset($totalEstudiantes) ? $totalEstudiantes : count($estudiantes) ?>
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

<!-- Modales para gestión de estudiantes -->
<?php echo view('Administrador/modals/matricularestudiante'); ?>
<?php echo view('Administrador/modals/detalleestudiante'); ?>
<?php echo view('Administrador/modals/filtrarestudiantes'); ?>

<script>
// Función para ver detalles de un estudiante
function verDetalleEstudiante(idmatricula) {
    console.log('Ver detalles estudiante:', idmatricula);
    
    // Verificar si el modal existe
    const modalElement = document.getElementById('modalDetalleEstudiante');
    if (!modalElement) {
        console.error('Modal no encontrado: modalDetalleEstudiante');
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
    
    fetch(`<?= base_url('matriculas/detalle') ?>/${idmatricula}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Llenar modal con datos del estudiante
                document.getElementById('detalleEstudianteContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="ti ti-user me-2"></i>Información Personal
                            </h6>
                            <div class="mb-2">
                                <strong>Nombre:</strong> 
                                <span class="text-muted">${data.estudiante.nombres} ${data.estudiante.apellidos}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Documento:</strong> 
                                <span class="text-muted">${data.estudiante.numerodoc}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> 
                                <span class="text-muted">${data.estudiante.email || 'No registrado'}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="ti ti-school me-2"></i>Información Académica
                            </h6>
                            <div class="mb-2">
                                <strong>Grado:</strong> 
                                <span class="text-muted">${data.estudiante.grado}° - ${data.estudiante.seccion}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Nivel:</strong> 
                                <span class="text-muted">${data.estudiante.nivel}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Año Lectivo:</strong> 
                                <span class="text-muted">${data.estudiante.aniolectivo}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Fecha Matrícula:</strong> 
                                <span class="text-muted">${data.estudiante.fechamatricula}</span>
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
                                        <h4 class="text-primary mb-1">${data.estadisticas.prestamos_activos || 0}</h4>
                                        <small class="text-muted">Préstamos Activos</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-success bg-opacity-10 rounded p-3">
                                        <h4 class="text-success mb-1">${data.estadisticas.total_prestamos || 0}</h4>
                                        <small class="text-muted">Total Préstamos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al cargar detalles del estudiante',
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
                text: 'No se pudieron cargar los detalles del estudiante',
                confirmButtonColor: '#dc3545'
            });
            modal.hide();
        });
}

// Función para editar un estudiante
function editarEstudiante(idmatricula) {
    console.log('Editar estudiante:', idmatricula);
    
    Swal.fire({
        icon: 'info',
        title: 'Función en desarrollo',
        text: 'La edición de estudiantes estará disponible próximamente',
        confirmButtonColor: '#0d6efd'
    });
}

// Función para cambiar estado de matrícula
function cambiarEstadoMatricula(idmatricula, nuevoEstado) {
    console.log('Cambiar estado matrícula:', idmatricula, nuevoEstado);
    
    const estadoTexto = nuevoEstado === 'true' ? 'activar' : 'desactivar';
    const estadoColor = nuevoEstado === 'true' ? 'success' : 'warning';
    
    Swal.fire({
        title: `¿${estadoTexto.charAt(0).toUpperCase() + estadoTexto.slice(1)} matrícula?`,
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="ti ti-toggle-${nuevoEstado === 'true' ? 'right' : 'left'} text-${estadoColor}" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-2">¿Está seguro de <strong>${estadoTexto}</strong> esta matrícula?</p>
                <p class="text-muted small mb-0">Esta acción afectará el acceso del estudiante al sistema.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: estadoColor === 'success' ? '#198754' : '#fd7e14',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${estadoTexto}`,
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('<?= base_url('matriculas/cambiar-estado') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    idmatricula: idmatricula,
                    estado: nuevoEstado
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
                    throw new Error(data.message || 'Error al actualizar estado');
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
                title: '¡Estado actualizado!',
                text: `La matrícula ha sido ${estadoTexto}da correctamente`,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

// Función para matricular nuevo estudiante
function matricularEstudiante() {
    const form = document.getElementById('formNuevoEstudiante');
    if (!form) {
        mostrarAlerta('Formulario no encontrado', 'danger');
        return;
    }

    // Validar campos requeridos
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const numerodoc = document.getElementById('numerodoc').value.trim();
    const nivel = document.getElementById('nivel').value;
    const grado = document.getElementById('grado').value;
    const seccion = document.getElementById('seccion').value;
    const aniolectivo = document.getElementById('aniolectivo').value;

    if (!nombres || !apellidos || !numerodoc || !nivel || !grado || !seccion || !aniolectivo) {
        mostrarAlertaModal('Por favor complete todos los campos requeridos', 'danger');
        return;
    }
    
    const formData = new FormData(form);

    fetch('<?= base_url('matriculas/crear') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            mostrarAlertaModal('¡Estudiante matriculado exitosamente!', 'success');
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoEstudiante')).hide();
                location.reload();
            }, 2000);
        } else {
            mostrarAlertaModal(data.message || 'Error al matricular estudiante', 'danger');
        }
    })
    .catch(() => mostrarAlertaModal('Error de conexión', 'danger'));
}

// Función para mostrar alertas en el modal
function mostrarAlertaModal(mensaje, tipo = 'info') {
    const alerta = document.getElementById('alertaMatricula');
    if (alerta) {
        alerta.className = `alert alert-${tipo}`;
        alerta.innerHTML = mensaje;
        alerta.classList.remove('d-none');
    }
}

// Función para aplicar filtros
function aplicarFiltros() {
    const form = document.getElementById('formFiltros');
    if (!form) return;
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    fetch(`<?= base_url('matriculas/filtrar') ?>?${params}`)
        .then(response => response.text())
        .then(html => {
            // Actualizar tabla con resultados filtrados
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('#tablaEstudiantes tbody');
            if (newTable) {
                document.querySelector('#tablaEstudiantes tbody').innerHTML = newTable.innerHTML;
            }
            bootstrap.Modal.getInstance(document.getElementById('modalFiltrarEstudiantes')).hide();
        })
        .catch(() => mostrarAlerta('Error al aplicar filtros', 'danger'));
}

// Función para limpiar filtros
function limpiarFiltros() {
    location.href = '<?= base_url('matriculas') ?>';
}

// Función genérica para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'info') {
    const alertaContainer = document.getElementById('alertaContainer') || document.body;
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertaContainer.appendChild(alerta);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips si están disponibles
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>