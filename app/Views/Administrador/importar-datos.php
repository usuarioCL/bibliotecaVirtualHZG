<!-- Incluir SweetAlert2 y jQuery al principio -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script de debugging -->
<script>
// Debug inmediato
console.log('=== DEBUGGING INICIAL ===');
console.log('jQuery disponible:', typeof jQuery !== 'undefined');
console.log('$ disponible:', typeof $ !== 'undefined');
console.log('SweetAlert disponible:', typeof Swal !== 'undefined');
console.log('DOM ready:', document.readyState);

// Verificar cuando se carga todo
window.addEventListener('load', function() {
    console.log('=== WINDOW LOADED ===');
    console.log('Botones encontrados:');
    console.log('- btn-descargar-plantilla:', document.getElementById('btn-descargar-plantilla') !== null);
    console.log('- btn-preview:', document.getElementById('btn-preview') !== null);
    console.log('- form-importar:', document.getElementById('form-importar') !== null);
});
</script>

<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">
            <i class="ti ti-file-spreadsheet me-2"></i>
            Importar Datos desde Excel
          </h4>
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
              <li><strong>Personas:</strong> nombres, apellidos, tipodoc, numerodoc (obligatorios), telefono, direccion, email, genero (opcionales)</li>
              <li><strong>Usuarios:</strong> nomuser, nombres, apellidos, email, nivelacceso (obligatorios), telefono, direccion (opcionales)</li>
              <li><strong>Recursos:</strong> titulo, autor, editorial, categoria (obligatorios), subtitulo, isbn, subcategoria, tipo_recurso, anio_publicacion (opcionales)</li>
              <li><strong>Autores:</strong> nombre_completo (obligatorio), biografia, nacionalidad, fecha_nacimiento (opcionales)</li>
              <li><strong>Categorías:</strong> nombre_categoria (obligatorio), descripcion (opcional)</li>
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
                    <option value="personas">Personas</option>
                    <option value="usuarios">Usuarios</option>
                    <option value="recursos">Recursos</option>
                    <option value="autores">Autores</option>
                    <option value="categorias">Categorías</option>
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
    console.log('Página cargada y jQuery listo');
    
    // Prueba de funcionamiento de botones
    console.log('Botón descargar plantilla:', $('#btn-descargar-plantilla').length);
    console.log('Botón vista previa:', $('#btn-preview').length);
    console.log('Formulario importar:', $('#form-importar').length);
    
    // Agregar eventos de prueba
    $('#btn-descargar-plantilla').on('click', function() {
        console.log('¡CLICK DETECTADO EN DESCARGAR PLANTILLA!');
    });
    
    $('#btn-preview').on('click', function() {
        console.log('¡CLICK DETECTADO EN VISTA PREVIA!');
    });
    
    // Test de SweetAlert
    if (typeof Swal !== 'undefined') {
        console.log('SweetAlert2 está disponible');
    } else {
        console.error('SweetAlert2 NO está disponible');
    }
    
    // Descargar plantilla
    $('#btn-descargar-plantilla').on('click', function() {
        console.log('Botón descargar plantilla clickeado');
        const tipoEntidad = $('#tipo_entidad').val();
        
        if (!tipoEntidad) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona el tipo de datos',
                text: 'Primero debes seleccionar qué tipo de datos quieres importar.'
            });
            return;
        }
        
        console.log('Tipo entidad seleccionado:', tipoEntidad);
        
        // Crear URL para descarga
        const downloadUrl = '<?= base_url('admin/descargar-plantilla') ?>/' + tipoEntidad;
        console.log('URL de descarga:', downloadUrl);
        
        // Mostrar mensaje de inicio de descarga
        Swal.fire({
            icon: 'info',
            title: 'Descargando plantilla...',
            text: 'La descarga iniciará en un momento.',
            timer: 2000,
            showConfirmButton: false
        });
        
        // Crear un enlace temporal y activarlo para forzar la descarga
        const tempLink = document.createElement('a');
        tempLink.href = downloadUrl;
        tempLink.download = 'plantilla_' + tipoEntidad + '.xlsx';
        document.body.appendChild(tempLink);
        tempLink.click();
        document.body.removeChild(tempLink);
    });

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
