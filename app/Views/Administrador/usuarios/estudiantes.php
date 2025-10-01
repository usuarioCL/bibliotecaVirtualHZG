<div class="">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="ti ti-alert-circle"></i>
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Estudiantes</h4>
            <p class="text-muted mb-0">Estudiantes matriculados en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFiltrarEstudiantes">
                <i class="ti ti-filter"></i> Filtrar
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoEstudiante">
                <i class="ti ti-plus"></i> Matricular Estudiante
            </button>
        </div>
    </div>

    <!-- Tabla de estudiantes -->
    <div class="card mt-1">
        <div class="card-body">
            <!-- Estadísticas rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-primary"><?= isset($totalEstudiantes) ? $totalEstudiantes : 0 ?></h5>
                        <small class="text-muted">Total Estudiantes</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-success"><?= isset($estudiantesActivos) ? $estudiantesActivos : 0 ?></h5>
                        <small class="text-muted">Matrículas Activas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-warning"><?= isset($estudiantesPrimaria) ? $estudiantesPrimaria : 0 ?></h5>
                        <small class="text-muted">Primaria</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-info"><?= isset($estudiantesSecundaria) ? $estudiantesSecundaria : 0 ?></h5>
                        <small class="text-muted">Secundaria</small>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaEstudiantes">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Grado y Sección</th>
                            <th>Nivel</th>
                            <th>Año Lectivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estudiantes)): ?>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= str_pad($estudiante['idmatricula'], 5, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <?= strtoupper(substr($estudiante['nombres'], 0, 1)) . strtoupper(substr($estudiante['apellidos'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= $estudiante['nombres'] . ' ' . $estudiante['apellidos'] ?></h6>
                                                <small class="text-muted"><?= $estudiante['email'] ?? 'Sin email registrado' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= $estudiante['numerodoc'] ?></span>
                                    </td>
                                    <td>
                                        <strong><?= $estudiante['grado'] ?>° - <?= $estudiante['seccion'] ?></strong>
                                    </td>
                                    <td>
                                        <?php 
                                        $nivelColor = match($estudiante['nivel']) {
                                            'Inicial' => 'bg-info',
                                            'Primaria' => 'bg-warning text-dark',
                                            'Secundaria' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $nivelColor ?>"><?= $estudiante['nivel'] ?></span>
                                    </td>
                                    <td><?= $estudiante['aniolectivo'] ?></td>
                                    <td>
                                        <?php if ($estudiante['estadomatricula']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Ver detalles" onclick="verDetalleEstudiante(<?= $estudiante['idmatricula'] ?>)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" title="Editar estudiante" onclick="editarEstudiante(<?= $estudiante['idmatricula'] ?>)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Cambiar estado" onclick="cambiarEstadoMatricula(<?= $estudiante['idmatricula'] ?>, <?= $estudiante['estadomatricula'] ? 'false' : 'true' ?>)">
                                                <i class="ti ti-toggle-<?= $estudiante['estadomatricula'] ? 'left' : 'right' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-users fs-1 mb-3"></i>
                                        <h5>No hay estudiantes matriculados</h5>
                                        <p class="mb-3">Comienza matriculando tu primer estudiante</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoEstudiante">
                                            <i class="ti ti-plus"></i> Matricular Estudiante
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

<!-- Modales para gestión de estudiantes -->
<?php echo view('Administrador/modals/matricularestudiante'); ?>
<?php echo view('Administrador/modals/detalleestudiante'); ?>
<?php echo view('Administrador/modals/filtrarestudiantes'); ?>

<script>
// Función para ver detalles de un estudiante
function verDetalleEstudiante(idmatricula) {
    fetch(`<?= base_url('matriculas/detalle') ?>/${idmatricula}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Llenar modal con datos del estudiante
                document.getElementById('detalleEstudianteContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Información Personal</h6>
                            <p><strong>Nombre:</strong> ${data.estudiante.nombres} ${data.estudiante.apellidos}</p>
                            <p><strong>Documento:</strong> ${data.estudiante.numerodoc}</p>
                            <p><strong>Email:</strong> ${data.estudiante.email || 'No registrado'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Información Académica</h6>
                            <p><strong>Grado:</strong> ${data.estudiante.grado}° - ${data.estudiante.seccion}</p>
                            <p><strong>Nivel:</strong> ${data.estudiante.nivel}</p>
                            <p><strong>Año Lectivo:</strong> ${data.estudiante.aniolectivo}</p>
                            <p><strong>Fecha Matrícula:</strong> ${data.estudiante.fechamatricula}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">Estadísticas de Biblioteca</h6>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-primary">${data.estadisticas.prestamos_activos || 0}</h4>
                                    <small>Préstamos Activos</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-success">${data.estadisticas.total_prestamos || 0}</h4>
                                    <small>Total Préstamos</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-warning">${data.estadisticas.sanciones_activas || 0}</h4>
                                    <small>Sanciones</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('modalDetalleEstudiante')).show();
            } else {
                mostrarAlerta('Error al cargar detalles del estudiante', 'danger');
            }
        })
        .catch(() => mostrarAlerta('Error de conexión', 'danger'));
}

// Función para editar un estudiante
function editarEstudiante(idmatricula) {
    // Implementar modal de edición
    mostrarAlerta('Función de edición en desarrollo', 'info');
}

// Función para cambiar estado de matrícula
function cambiarEstadoMatricula(idmatricula, nuevoEstado) {
    if (confirm('¿Está seguro de cambiar el estado de la matrícula?')) {
        fetch('<?= base_url('matriculas/cambiar-estado') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                idmatricula: idmatricula,
                estado: nuevoEstado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                mostrarAlerta('Estado actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Error al actualizar estado', 'danger');
            }
        })
        .catch(() => mostrarAlerta('Error de conexión', 'danger'));
    }
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