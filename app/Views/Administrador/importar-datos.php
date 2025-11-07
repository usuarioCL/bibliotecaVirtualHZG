<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-file-upload text-primary me-2"></i>
                        Importar Datos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Administración de Datos</a></li>
                            <li class="breadcrumb-item active">Importar Datos</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Importa datos masivamente desde archivos Excel al sistema</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="mostrarGuiaImportacion()">
                        <i class="ti ti-help"></i> Guía de Importación
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="btn-descargar-plantilla">
                        <i class="ti ti-download"></i> Descargar Plantillas
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
                            <i class="ti ti-file-spreadsheet text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">6</h3>
                    <p class="text-muted mb-0 small">Tipos de Datos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-check-circle text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1">0</h3>
                    <p class="text-muted mb-0 small">Importaciones Exitosas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-alert-triangle text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">0</h3>
                    <p class="text-muted mb-0 small">Con Errores</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-database text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1">0</h3>
                    <p class="text-muted mb-0 small">Registros Procesados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de importación -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="ti ti-upload text-primary me-2"></i>
                Formulario de Importación
            </h5>
            <p class="text-muted small mb-0 mt-1">Selecciona el tipo de datos y el archivo Excel para importar</p>
        </div>
        <div class="card-body">
          
          <!-- Información sobre el proceso de importación -->
          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading">
              <i class="ti ti-info-circle me-2"></i>
              Información sobre la importación
            </h6>
            <p class="mb-2">Puedes importar datos desde archivos Excel (.xlsx, .xls) para las siguientes entidades:</p>
            <ul class="mb-2">
              <li><strong>Usuarios:</strong> nombres, apellidos, tipodoc, numerodoc, genero, nivelacceso (obligatorios), telefono, direccion (opcionales). <span class="text-muted">Incluye datos de persona automáticamente.</span></li>
              <li><strong>Recursos:</strong> titulo (obligatorio), isbn, autor, editorial, categoria, subcategoria, tipo_recurso, anio_publicacion (opcionales)</li>
              <li><strong>Autores:</strong> nombre_completo (obligatorio), nacionalidad (opcional)</li>
              <li><strong>Editoriales:</strong> nombre_editorial (obligatorio), pais, contacto (opcionales)</li>
            </ul>
            <p class="mb-0 text-muted"><small><strong>Nota:</strong> Los nombres de las columnas NO son sensibles a mayúsculas/minúsculas. Puedes usar "Nombres", "nombres", "NOMBRES", etc.</small></p>
          </div>

          <!-- Formulario de importación -->
          <form id="form-importar" enctype="multipart/form-data" method="post">
            <!-- Token CSRF para seguridad -->
            <?= csrf_field() ?>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="tipo_entidad" class="form-label">
                    <i class="ti ti-category me-1"></i>
                    Tipo de datos a importar
                  </label>
                  <select class="form-select" id="tipo_entidad" name="tipo_entidad" required>
                    <option value="">Selecciona el tipo de datos</option>
                    <option value="usuarios">Usuarios (incluye datos personales)</option>
                    <option value="recursos">Recursos</option>
                    <option value="autores">Autores</option>
                    <option value="editoriales">Editoriales</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="archivo_excel" class="form-label">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    Archivo Excel
                  </label>
                  <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" 
                         accept=".xlsx,.xls" required>
                  <div class="form-text">
                    Solo archivos Excel (.xlsx, .xls). Tamaño máximo: 5MB
                  </div>
                </div>
              </div>
            </div>

            <!-- Opciones avanzadas eliminadas para Excel -->

            <!-- Botones de acción -->
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-outline-secondary" id="btn-preview">
                <i class="ti ti-eye me-1"></i>
                Vista previa
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="ti ti-upload me-1"></i>
                Importar datos
              </button>
              <button type="button" class="btn btn-success" id="btn-descargar-plantilla">
                <i class="ti ti-download me-1"></i>
                Descargar plantilla
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Vista previa del archivo -->
  <div class="row mt-4" id="preview-container" style="display: none;">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="ti ti-eye me-2"></i>
            Vista previa del archivo
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="preview-table">
              <thead class="table-light">
                <!-- Los encabezados se cargarán dinámicamente -->
              </thead>
              <tbody>
                <!-- Los datos se cargarán dinámicamente -->
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            <div class="alert alert-warning" role="alert">
              <strong>Nota:</strong> Esta es solo una vista previa de las primeras 10 filas. 
              Revisa que los datos se vean correctos antes de proceder con la importación.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Resultados de la importación -->
  <div class="row mt-4" id="results-container" style="display: none;">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="ti ti-check-circle me-2"></i>
            Resultados de la importación
          </h5>
        </div>
        <div class="card-body" id="results-content">
          <!-- Los resultados se cargarán aquí -->
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    console.log('Sistema de importación inicializado');

    // Vista previa del archivo
    $('#btn-preview').on('click', function() {
        console.log('Botón vista previa clickeado');
        const archivo = $('#archivo_excel')[0].files[0];
        const tipoEntidad = $('#tipo_entidad').val();
        
        if (!tipoEntidad) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona el tipo de datos',
                text: 'Debes seleccionar qué tipo de datos quieres importar.'
            });
            return;
        }
        
        if (!archivo) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un archivo',
                text: 'Debes seleccionar un archivo Excel para la vista previa.'
            });
            return;
        }
        
        console.log('Archivo seleccionado:', archivo);
        console.log('Tipo entidad:', tipoEntidad);
        
        const formData = new FormData();
        formData.append('tipo_entidad', tipoEntidad);
        formData.append('archivo_excel', archivo);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        console.log('Enviando petición AJAX para vista previa');
        
        $.ajax({
            url: '<?= base_url('admin/preview-excel') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            timeout: 30000,
            beforeSend: function() {
                console.log('Iniciando envío de archivo para vista previa');
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Analizando el archivo Excel',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                console.log('Respuesta recibida:', response);
                Swal.close();
                
                if (response && response.success) {
                    mostrarPreview(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la vista previa',
                        text: response.message || 'No se pudo procesar el archivo'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                console.error('Detalles del error:', xhr.responseText);
                Swal.close();
                
                let errorMessage = 'Error al procesar la vista previa del archivo.';
                
                if (xhr.status === 413) {
                    errorMessage = 'El archivo es demasiado grande. Máximo permitido: 5MB';
                } else if (xhr.status === 500) {
                    errorMessage = 'Error interno del servidor. Revisa el formato del archivo.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Ruta no encontrada. Contacta al administrador.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de comunicación',
                    text: errorMessage + ' (' + xhr.status + ')'
                });
            }
        });
    });

    // Enviar formulario de importación
    $('#form-importar').on('submit', function(e) {
        e.preventDefault();
        console.log('Formulario de importación enviado');
        
        const formData = new FormData(this);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        Swal.fire({
            title: '¿Confirmar importación?',
            text: 'Se procesarán todos los datos del archivo Excel. Esta acción no se puede deshacer.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, importar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                realizarImportacion(formData);
            }
        });
    });

    function mostrarPreview(data) {
        console.log('Mostrando vista previa con datos:', data);
        const tabla = $('#preview-table');
        const thead = tabla.find('thead');
        const tbody = tabla.find('tbody');
        
        // Limpiar tabla
        thead.empty();
        tbody.empty();
        
        if (data && data.length > 0) {
            try {
                // Crear encabezados
                const headerRow = $('<tr></tr>');
                Object.keys(data[0]).forEach(key => {
                    headerRow.append(`<th>${key}</th>`);
                });
                thead.append(headerRow);
                
                // Crear filas de datos (solo las primeras 10)
                const maxRows = Math.min(data.length, 10);
                for (let i = 0; i < maxRows; i++) {
                    const row = data[i];
                    const dataRow = $('<tr></tr>');
                    Object.values(row).forEach(value => {
                        const displayValue = (value !== undefined && value !== null) ? value : '';
                        dataRow.append(`<td>${displayValue}</td>`);
                    });
                    tbody.append(dataRow);
                }
                
                // Mostrar el contenedor
                $('#preview-container').show();
                
                // Scroll suave hacia la vista previa
                $('html, body').animate({
                    scrollTop: $('#preview-container').offset().top - 100
                }, 500);
                
                // Mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Vista previa generada',
                    text: `Se muestran ${maxRows} de ${data.length} filas del archivo Excel`,
                    timer: 3000,
                    showConfirmButton: false
                });
                
            } catch (error) {
                console.error('Error al procesar datos de vista previa:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de procesamiento',
                    text: 'Ocurrió un error al procesar los datos para la vista previa'
                });
            }
        } else {
            console.error('No hay datos para mostrar en la vista previa');
            Swal.fire({
                icon: 'warning',
                title: 'Sin datos',
                text: 'No se encontraron datos para mostrar en la vista previa o el archivo está vacío'
            });
        }
    }

    function realizarImportacion(formData) {
        Swal.fire({
            title: 'Importando datos...',
            text: 'Por favor espera mientras se procesan los datos.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url('admin/procesar-importacion-excel') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 60000, // 1 minuto para importación
            success: function(response) {
                Swal.close();
                
                if (response && response.success) {
                    mostrarResultados(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Importación completada',
                        text: `Se importaron ${response.registros_exitosos} registros correctamente.`
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la importación',
                        text: response.message || 'Error desconocido en la importación'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en importación:', status, error);
                Swal.close();
                
                let errorMessage = 'Error al procesar la importación.';
                if (xhr.status === 413) {
                    errorMessage = 'El archivo es demasiado grande para procesar.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Error interno del servidor durante la importación.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    }

    function mostrarResultados(response) {
        const content = $('#results-content');
        
        let html = `
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-success">${response.registros_exitosos || 0}</h3>
                        <p class="text-muted">Registros importados</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-danger">${response.registros_error || 0}</h3>
                        <p class="text-muted">Errores</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-warning">${response.registros_duplicados || 0}</h3>
                        <p class="text-muted">Duplicados</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-info">${response.total_procesados || 0}</h3>
                        <p class="text-muted">Total procesados</p>
                    </div>
                </div>
            </div>
        `;
        
        if (response.errores && response.errores.length > 0) {
            html += `
                <div class="mt-4">
                    <h6>Detalles de errores:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fila</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            response.errores.forEach(error => {
                html += `
                    <tr>
                        <td>${error.fila || 'N/A'}</td>
                        <td>${error.mensaje || 'Error desconocido'}</td>
                    </tr>
                `;
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        content.html(html);
        $('#results-container').show();
        
        // Scroll hacia los resultados
        $('html, body').animate({
            scrollTop: $('#results-container').offset().top - 100
        }, 500);
    }
});

// BACKUP: Event listeners con JavaScript vanilla para asegurar funcionamiento
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CONTENT LOADED ===');
    
    // Backup para descargar plantilla
    const btnDescargar = document.getElementById('btn-descargar-plantilla');
    if (btnDescargar) {
        btnDescargar.addEventListener('click', function() {
            console.log('VANILLA JS: Botón descargar plantilla clickeado');
            const tipoEntidad = document.getElementById('tipo_entidad').value;
            
            if (!tipoEntidad) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selecciona el tipo de datos',
                        text: 'Primero debes seleccionar qué tipo de datos quieres importar.'
                    });
                } else {
                    alert('Primero debes seleccionar qué tipo de datos quieres importar.');
                }
                return;
            }
            
            console.log('Descargando:', tipoEntidad);
            window.location.href = '<?= base_url('admin/descargar-plantilla') ?>/' + tipoEntidad;
        });
    }
    
    // Backup para vista previa
    const btnPreview = document.getElementById('btn-preview');
    if (btnPreview) {
        btnPreview.addEventListener('click', function() {
            console.log('VANILLA JS: Botón vista previa clickeado');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Función en desarrollo',
                    text: 'La vista previa está siendo desarrollada.'
                });
            } else {
                alert('La vista previa está siendo desarrollada.');
            }
        });
    }
});
</script>
