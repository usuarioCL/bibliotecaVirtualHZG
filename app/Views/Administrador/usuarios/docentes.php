<div class="">
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

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="ti ti-alert-circle"></i>
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Docentes</h4>
            <p class="text-muted mb-0">Docentes registrados en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFiltrarDocentes">
                <i class="ti ti-filter"></i> Filtrar
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDocente">
                <i class="ti ti-plus"></i> Registrar Docente
            </button>
        </div>
    </div>

    <!-- Tabla de docentes -->
    <div class="card mt-1">
        <div class="card-body">
            <!-- Estadísticas rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-primary"><?= isset($totalDocentes) ? $totalDocentes : 0 ?></h5>
                        <small class="text-muted">Total Docentes</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-success"><?= isset($docentesActivos) ? $docentesActivos : 0 ?></h5>
                        <small class="text-muted">Docentes Activos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-warning"><?= isset($docentesPrimaria) ? $docentesPrimaria : 0 ?></h5>
                        <small class="text-muted">Primaria</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light rounded p-3 text-center">
                        <h5 class="mb-1 text-info"><?= isset($docentesSecundaria) ? $docentesSecundaria : 0 ?></h5>
                        <small class="text-muted">Secundaria</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaDocentes">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Docente</th>
                            <th>Documento</th>
                            <th>Contacto</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($docentes)): ?>
                            <?php foreach ($docentes as $docente): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= str_pad($docente['idusuario'], 5, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <?= strtoupper(substr($docente['nombres'], 0, 1)) . strtoupper(substr($docente['apellidos'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= $docente['nombres'] . ' ' . $docente['apellidos'] ?></h6>
                                                <small class="text-muted"><?= $docente['email'] ?? 'Sin email registrado' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= $docente['numerodoc'] ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <?php if (!empty($docente['telefono'])): ?>
                                                <small class="text-muted">
                                                    <i class="ti ti-phone fs-6 me-1"></i><?= esc($docente['telefono']) ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">Sin teléfono</small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($docente['nomuser'])): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Sin usuario</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Ver detalles" onclick="verDetalleDocente(<?= $docente['idusuario'] ?>)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" title="Editar docente" onclick="editarDocente(<?= $docente['idusuario'] ?>)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-users fs-1 mb-3"></i>
                                        <h5>No hay docentes registrados</h5>
                                        <p class="mb-3">Comienza registrando tu primer docente</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoDocente">
                                            <i class="ti ti-plus"></i> Registrar Docente
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

<!-- Modales para gestión de docentes -->
<?php echo view('Administrador/modals/registrardocente'); ?>
<?php echo view('Administrador/modals/detalledocente'); ?>
<?php echo view('Administrador/modals/filtrardocentes'); ?>

<script>
// Función para ver detalles de un docente
function verDetalleDocente(iddocente) {
    console.log('Viendo detalles del docente:', iddocente);
    
    fetch(`<?= base_url('docentes/detalle') ?>/${iddocente}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Cargar datos en el modal de detalles
            cargarDetalleDocente(data.docente, data.estadisticas);
            const modal = new bootstrap.Modal(document.getElementById('modalDetalleDocente'));
            modal.show();
        } else {
            mostrarAlertaModal('Error al cargar detalles: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlertaModal('Error de conexión al cargar detalles', 'danger');
    });
}

// Función para editar un docente
function editarDocente(iddocente) {
    console.log('Editando docente:', iddocente);
    // Implementar lógica de edición
    mostrarAlertaModal('Función de edición en desarrollo', 'info');
}

// Función para cambiar estado de docente
function cambiarEstadoDocente(iddocente, nuevoEstado) {
    const accion = nuevoEstado ? 'activar' : 'desactivar';
    
    if (!confirm(`¿Está seguro de ${accion} este docente?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('iddocente', iddocente);
    formData.append('estado', nuevoEstado ? 1 : 0);
    
    fetch('<?= base_url('docentes/cambiar-estado') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            mostrarAlertaModal(`Docente ${nuevoEstado ? 'activado' : 'desactivado'} correctamente`, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            mostrarAlertaModal('Error: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlertaModal('Error de conexión', 'danger');
    });
}

// Función para registrar nuevo docente
function registrarDocente() {
    console.log('Iniciando proceso de registro de docente');
    
    const form = document.getElementById('formNuevoDocente');
    if (!form) {
        mostrarAlertaModal('Error: No se encontró el formulario', 'danger');
        return;
    }
    
    const formData = new FormData(form);
    
    // Verificar campos obligatorios
    const apellidos = document.getElementById('apellidos')?.value?.trim();
    const nombres = document.getElementById('nombres')?.value?.trim();
    const numerodoc = document.getElementById('numerodoc')?.value?.trim();
    
    if (!apellidos || !nombres || !numerodoc) {
        mostrarAlertaModal('Por favor complete todos los campos obligatorios', 'danger');
        return;
    }

    console.log('Enviando datos al servidor...');
    
    fetch('<?= base_url('docentes/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Datos de respuesta:', data);
        if (data.status === 'success') {
            mostrarAlertaModal(`¡Registro exitoso!<br>Docente registrado: <strong>${data.datos.nombres} ${data.datos.apellidos}</strong>`, 'success');
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoDocente')).hide();
                location.reload();
            }, 2500);
        } else {
            mostrarAlertaModal(data.message || 'Error al registrar docente', 'danger');
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

// Función para cargar detalles en el modal
function cargarDetalleDocente(docente, estadisticas) {
    // Implementar carga de detalles
    console.log('Cargando detalles:', docente, estadisticas);
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

// Función para buscar docente por DNI (similar a la funcionalidad de usuarios)
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
    if (infoBusqueda) {
        infoBusqueda.className = 'form-text text-info';
        infoBusqueda.innerHTML = '<i class="ti ti-search"></i> Buscando...';
        infoBusqueda.classList.remove('d-none');
    }
    
    fetch(`<?= base_url('docentes/buscar-por-dni') ?>?numerodoc=${encodeURIComponent(numerodoc)}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.encontrado) {
            // Autocompletar campos con los datos encontrados
            autocompletarCampos(data.datos);
            
            if (infoBusqueda) {
                infoBusqueda.className = 'form-text text-success';
                infoBusqueda.innerHTML = `<i class="ti ti-check"></i> Persona encontrada: ${data.datos.apellidos}, ${data.datos.nombres}`;
            }
            
            if (data.datos.es_docente) {
                mostrarAlertaModal(`Esta persona ya es docente en el sistema`, 'warning');
            } else {
                mostrarAlertaModal('Datos autocompletados correctamente. Puede proceder con el registro.', 'success');
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
        mostrarAlertaModal('Error de conexión al buscar persona', 'danger');
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