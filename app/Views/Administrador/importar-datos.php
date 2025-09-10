<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">
            <i class="ti ti-file-upload me-2"></i>
            Importar Datos desde CSV
          </h4>
        </div>
        <div class="card-body">
          
          <!-- Información sobre el proceso de importación -->
          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading">
              <i class="ti ti-info-circle me-2"></i>
              Información sobre la importación
            </h6>
            <p class="mb-2">Puedes importar datos desde archivos CSV para las siguientes entidades:</p>
            <ul class="mb-0">
              <li><strong>Usuarios:</strong> Código, nombre, apellido, email, rol, etc.</li>
              <li><strong>Recursos:</strong> Título, autor, editorial, categoría, etc.</li>
              <li><strong>Autores:</strong> Nombre completo, biografía, nacionalidad</li>
              <li><strong>Categorías:</strong> Nombre, descripción</li>
            </ul>
          </div>

          <!-- Formulario de importación -->
          <form id="form-importar" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="tipo_entidad" class="form-label">
                    <i class="ti ti-category me-1"></i>
                    Tipo de datos a importar
                  </label>
                  <select class="form-select" id="tipo_entidad" name="tipo_entidad" required>
                    <option value="">Selecciona el tipo de datos</option>
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
                  <label for="archivo_csv" class="form-label">
                    <i class="ti ti-file-csv me-1"></i>
                    Archivo CSV
                  </label>
                  <input type="file" class="form-control" id="archivo_csv" name="archivo_csv" 
                         accept=".csv" required>
                  <div class="form-text">
                    Solo archivos CSV. Tamaño máximo: 5MB
                  </div>
                </div>
              </div>
            </div>

            <!-- Opciones avanzadas -->
            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="separador" class="form-label">Separador</label>
                  <select class="form-select" id="separador" name="separador">
                    <option value="," selected>Coma (,)</option>
                    <option value=";">Punto y coma (;)</option>
                    <option value="|">Pipe (|)</option>
                    <option value="	">Tabulación</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="codificacion" class="form-label">Codificación</label>
                  <select class="form-select" id="codificacion" name="codificacion">
                    <option value="UTF-8" selected>UTF-8</option>
                    <option value="ISO-8859-1">ISO-8859-1</option>
                    <option value="Windows-1252">Windows-1252</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" id="primera_fila_encabezados" 
                           name="primera_fila_encabezados" checked>
                    <label class="form-check-label" for="primera_fila_encabezados">
                      La primera fila contiene encabezados
                    </label>
                  </div>
                </div>
              </div>
            </div>

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

<script>
$(document).ready(function() {
    
    // Descargar plantilla
    $('#btn-descargar-plantilla').click(function() {
        const tipoEntidad = $('#tipo_entidad').val();
        if (!tipoEntidad) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona el tipo de datos',
                text: 'Primero debes seleccionar qué tipo de datos quieres importar.'
            });
            return;
        }
        
        window.location.href = '<?= base_url("admin/descargar-plantilla/") ?>' + tipoEntidad;
    });

    // Vista previa del archivo
    $('#btn-preview').click(function() {
        const formData = new FormData();
        const archivo = $('#archivo_csv')[0].files[0];
        
        if (!archivo) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un archivo',
                text: 'Debes seleccionar un archivo CSV para la vista previa.'
            });
            return;
        }
        
        formData.append('archivo_csv', archivo);
        formData.append('separador', $('#separador').val());
        formData.append('codificacion', $('#codificacion').val());
        formData.append('primera_fila_encabezados', $('#primera_fila_encabezados').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: '<?= base_url("admin/preview-csv") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    mostrarPreview(response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la vista previa',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la vista previa del archivo.'
                });
            }
        });
    });

    // Enviar formulario de importación
    $('#form-importar').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        Swal.fire({
            title: '¿Confirmar importación?',
            text: 'Se procesarán todos los datos del archivo CSV. Esta acción no se puede deshacer.',
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
        const tabla = $('#preview-table');
        const thead = tabla.find('thead');
        const tbody = tabla.find('tbody');
        
        // Limpiar tabla
        thead.empty();
        tbody.empty();
        
        if (data.length > 0) {
            // Crear encabezados
            const headerRow = $('<tr></tr>');
            Object.keys(data[0]).forEach(key => {
                headerRow.append(`<th>${key}</th>`);
            });
            thead.append(headerRow);
            
            // Crear filas de datos
            data.forEach(row => {
                const dataRow = $('<tr></tr>');
                Object.values(row).forEach(value => {
                    dataRow.append(`<td>${value || ''}</td>`);
                });
                tbody.append(dataRow);
            });
        }
        
        $('#preview-container').show();
    }

    function realizarImportacion(formData) {
        const loadingSwal = Swal.fire({
            title: 'Importando datos...',
            text: 'Por favor espera mientras se procesan los datos.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url("admin/procesar-importacion") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                loadingSwal.close();
                
                if (response.success) {
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
                        text: response.message
                    });
                }
            },
            error: function() {
                loadingSwal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la importación.'
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
                        <h3 class="text-success">${response.registros_exitosos}</h3>
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
                        <h3 class="text-info">${response.total_procesados}</h3>
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
                        <table class="table table-sm">
                            <thead>
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
                        <td>${error.fila}</td>
                        <td>${error.mensaje}</td>
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
    }
});
</script>

<!-- Incluir SweetAlert2 para las alertas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
