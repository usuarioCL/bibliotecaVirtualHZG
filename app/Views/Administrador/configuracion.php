<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-settings text-primary me-2"></i>
                        Configuración del Sistema
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Configuración</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra todas las configuraciones del sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportarConfiguracion()">
                        <i class="ti ti-download"></i> Exportar Config
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="guardarTodasConfiguraciones()">
                        <i class="ti ti-device-floppy"></i> Guardar Todo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerta de información -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle me-3 fs-4"></i>
                <div>
                    <h6 class="alert-heading mb-1">Información Importante</h6>
                    <p class="mb-0">Los cambios en la configuración se aplicarán inmediatamente. Asegúrate de revisar los valores antes de guardar.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de configuración -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-adjustments text-primary me-2"></i>
                        Configuraciones del Sistema
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona todas las configuraciones organizadas por categorías</p>
                </div>
                <div class="card-body p-0">
                    <!-- Navigation tabs -->
                    <ul class="nav nav-tabs" id="configTabs" role="tablist">
                        <?php if (!empty($configuraciones)): ?>
                            <?php $isFirst = true; ?>
                            <?php foreach ($configuraciones as $clave => $seccion): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= $isFirst ? 'active' : '' ?>" 
                                            id="<?= $clave ?>-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#<?= $clave ?>" 
                                            type="button" 
                                            role="tab">
                                        <i class="<?= $seccion['icono'] ?> me-2"></i>
                                        <?= $seccion['nombre'] ?>
                                    </button>
                                </li>
                                <?php $isFirst = false; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content p-4" id="configTabContent">
                        <?php if (!empty($configuraciones)): ?>
                            <?php $isFirst = true; ?>
                            <?php foreach ($configuraciones as $clave => $seccion): ?>
                                <div class="tab-pane fade <?= $isFirst ? 'show active' : '' ?>" 
                                     id="<?= $clave ?>" 
                                     role="tabpanel">
                                    
                                    <!-- Header de la sección -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">
                                                <i class="<?= $seccion['icono'] ?> text-primary me-2"></i>
                                                <?= $seccion['nombre'] ?>
                                            </h6>
                                            <p class="text-muted small mb-0">Configura los parámetros de esta sección</p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-warning btn-sm" onclick="restaurarSeccion('<?= $clave ?>')">
                                                <i class="ti ti-restore me-1"></i>Restaurar
                                            </button>
                                            <button class="btn btn-primary btn-sm" onclick="guardarSeccion('<?= $clave ?>')">
                                                <i class="ti ti-device-floppy me-1"></i>Guardar
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Formulario de configuraciones -->
                                    <form id="form-<?= $clave ?>" class="configuracion-form">
                                        <div class="row g-4">
                                            <?php foreach ($seccion['configuraciones'] as $config): ?>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="<?= $config['clave'] ?>" class="form-label fw-semibold">
                                                            <?= $config['nombre'] ?>
                                                            <?php if ($config['requerido']): ?>
                                                                <span class="text-danger">*</span>
                                                            <?php endif; ?>
                                                        </label>
                                                        
                                                        <?php if ($config['tipo'] === 'boolean'): ?>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       id="<?= $config['clave'] ?>" 
                                                                       name="<?= $config['clave'] ?>"
                                                                       <?= $config['valor'] == '1' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="<?= $config['clave'] ?>">
                                                                    <?= $config['valor'] == '1' ? 'Habilitado' : 'Deshabilitado' ?>
                                                                </label>
                                                            </div>
                                                        <?php elseif ($config['tipo'] === 'number'): ?>
                                                            <input type="number" 
                                                                   class="form-control" 
                                                                   id="<?= $config['clave'] ?>" 
                                                                   name="<?= $config['clave'] ?>"
                                                                   value="<?= $config['valor'] ?>"
                                                                   <?= isset($config['min']) ? 'min="' . $config['min'] . '"' : '' ?>
                                                                   <?= isset($config['max']) ? 'max="' . $config['max'] . '"' : '' ?>
                                                                   <?= $config['requerido'] ? 'required' : '' ?>>
                                                        <?php else: ?>
                                                            <input type="<?= $config['tipo'] ?>" 
                                                                   class="form-control" 
                                                                   id="<?= $config['clave'] ?>" 
                                                                   name="<?= $config['clave'] ?>"
                                                                   value="<?= esc($config['valor']) ?>"
                                                                   <?= $config['requerido'] ? 'required' : '' ?>>
                                                        <?php endif; ?>
                                                        
                                                        <div class="form-text text-muted">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            <?= $config['descripcion'] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </form>
                                </div>
                                <?php $isFirst = false; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="ti ti-settings-off" style="font-size: 3rem; color: #6c757d;"></i>
                                    </div>
                                    <h5 class="empty-state-title text-muted">No hay configuraciones disponibles</h5>
                                    <p class="empty-state-description text-muted mb-0">
                                        Las configuraciones del sistema no están disponibles
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para guardar una sección específica
    function guardarSeccion(seccion) {
        const form = document.getElementById(`form-${seccion}`);
        const formData = new FormData(form);
        
        // Convertir FormData a objeto
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        // Manejar checkboxes no marcados
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                data[checkbox.name] = '0';
            } else {
                data[checkbox.name] = '1';
            }
        });

        Swal.fire({
            title: '¿Guardar Configuración?',
            text: `¿Estás seguro de que deseas guardar los cambios de ${seccion}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor espera mientras se guardan las configuraciones',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Realizar petición AJAX
                fetch('<?= base_url("admin/guardar-configuracion") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        seccion: seccion,
                        configuraciones: data
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Configuración Guardada',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Error al guardar la configuración',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Función para restaurar una sección a valores por defecto
    function restaurarSeccion(seccion) {
        Swal.fire({
            title: '⚠️ ¿Restaurar Configuración?',
            html: `
                <div class="alert alert-warning">
                    <strong>¡Advertencia!</strong><br>
                    Esta acción restaurará todas las configuraciones de <strong>${seccion}</strong> a sus valores por defecto.
                </div>
                <p>Los cambios actuales se perderán.</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url("admin/restaurar-configuracion") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ seccion: seccion })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Configuración Restaurada',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Error al restaurar la configuración',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Función para guardar todas las configuraciones
    function guardarTodasConfiguraciones() {
        const forms = document.querySelectorAll('.configuracion-form');
        const todasConfiguraciones = {};
        
        forms.forEach(form => {
            const formData = new FormData(form);
            const seccion = form.id.replace('form-', '');
            todasConfiguraciones[seccion] = {};
            
            for (let [key, value] of formData.entries()) {
                todasConfiguraciones[seccion][key] = value;
            }
            
            // Manejar checkboxes no marcados
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    todasConfiguraciones[seccion][checkbox.name] = '0';
                } else {
                    todasConfiguraciones[seccion][checkbox.name] = '1';
                }
            });
        });

        Swal.fire({
            title: '¿Guardar Todas las Configuraciones?',
            text: 'Se guardarán todas las configuraciones del sistema',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar todo',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Guardando Configuraciones...',
                    text: 'Por favor espera mientras se procesan todos los cambios',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('<?= base_url("admin/guardar-configuracion") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(todasConfiguraciones)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Todas las Configuraciones Guardadas',
                            text: 'Todas las configuraciones han sido actualizadas exitosamente',
                            icon: 'success'
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Error al guardar las configuraciones',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Función para exportar configuración
    function exportarConfiguracion() {
        Swal.fire({
            title: 'Exportar Configuración',
            html: `
                <div class="text-start">
                    <p>Selecciona el formato de exportación:</p>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="formatoExportar" id="json" value="json" checked>
                            <label class="form-check-label" for="json">
                                <i class="ti ti-file-code me-2"></i>JSON
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="formatoExportar" id="excel" value="excel">
                            <label class="form-check-label" for="excel">
                                <i class="ti ti-file-spreadsheet me-2"></i>Excel
                            </label>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="incluirValoresDefecto">
                        <label class="form-check-label" for="incluirValoresDefecto">
                            Incluir valores por defecto
                        </label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Exportar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formato = document.querySelector('input[name="formatoExportar"]:checked').value;
                const incluirDefecto = document.getElementById('incluirValoresDefecto').checked;
                
                // TODO: Implementar exportación real
                Swal.fire({
                    title: 'Exportando...',
                    text: `Generando archivo de configuración en formato ${formato.toUpperCase()}`,
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Actualizar etiquetas de switches cuando cambian
    document.addEventListener('DOMContentLoaded', function() {
        const switches = document.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switch_ => {
            if (switch_.closest('.form-check-label')) return; // Skip if it's not a switch
            
            switch_.addEventListener('change', function() {
                const label = this.closest('.form-check').querySelector('.form-check-label');
                if (label && label.textContent.includes('Habilitado') || label.textContent.includes('Deshabilitado')) {
                    label.textContent = this.checked ? 'Habilitado' : 'Deshabilitado';
                }
            });
        });
    });
</script>