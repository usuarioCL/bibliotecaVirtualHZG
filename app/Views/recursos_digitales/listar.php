<?= $header ?>

<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Recursos Digitales</h4>
            <p class="text-muted mb-0">Lista de recursos digitales disponibles en la biblioteca</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/recurso-digital/pdf') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-file-type-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- Tabla de recursos digitales -->
    <div class="card mt-1">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Año</th>
                            <th>Editorial</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Tipo de Recurso</th>
                        <th>Archivo</th>
                        <th>Ver</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recursos_digitales)): ?>
                            <?php foreach($recursos_digitales as $recurso): ?>
                            <tr>
                                <td><?= $recurso->idrecurso ?></td>
                                <td>
                                    <strong><?= esc($recurso->titulo) ?></strong>
                                </td>
                                <td><?= $recurso->anio ?></td>
                                <td><?= esc($recurso->editorial) ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= esc($recurso->categoria) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= esc($recurso->subcategoria) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= esc($recurso->tiporecurso) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($recurso->archivo)): ?>
                                        <a href="<?= base_url('uploads/digitales/' . esc($recurso->archivo)) ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-download"></i> Descargar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Sin archivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($recurso->archivo)): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info" 
                                                onclick="verPDF('<?= base_url('uploads/digitales/' . esc($recurso->archivo)) ?>', '<?= esc($recurso->titulo) ?>')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="verDetalles(<?= $recurso->idrecurso ?>)">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="editarRecurso(<?= $recurso->idrecurso ?>)">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="eliminarRecurso(<?= $recurso->idrecurso ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                No hay recursos digitales registrados
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal personalizado para ver PDF -->
<div id="modalPDF" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="cerrarModalPDF()"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 id="modalPDFLabel">Visualizar PDF</h5>
            <button type="button" class="btn-close" onclick="cerrarModalPDF()" aria-label="Cerrar">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <div id="pdfContainer" style="height: 600px; position: relative;">
                <div id="pdfLoading" style="display: none; text-align: center; padding: 50px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando PDF...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando PDF...</p>
                </div>
                <iframe id="pdfViewer" 
                        src="" 
                        width="100%" 
                        height="100%" 
                        style="border: none; display: none;"
                        title="Visor de PDF"
                        allowfullscreen>
                </iframe>
                <div id="pdfError" style="display: none; text-align: center; padding: 50px;">
                    <i class="ti ti-file-text fs-1 text-muted mb-3" aria-hidden="true"></i>
                    <h5>No se puede mostrar el PDF en el visor</h5>
                    <p class="text-muted">El archivo se abrirá en una nueva pestaña</p>
                    <button class="btn btn-primary" onclick="abrirPDFEnNuevaPestana()" aria-label="Abrir PDF en nueva pestaña">
                        <i class="ti ti-external-link" aria-hidden="true"></i> Abrir PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="custom-modal-footer">
            <a id="descargarPDF" href="#" target="_blank" class="btn btn-primary" aria-label="Descargar PDF">
                <i class="ti ti-download" aria-hidden="true"></i> Descargar PDF
            </a>
            <button type="button" class="btn btn-secondary" onclick="cerrarModalPDF()" aria-label="Cerrar modal">Cerrar</button>
        </div>
    </div>
</div>

<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
}

.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal-content {
    position: relative;
    background: white;
    margin: 2% auto;
    width: 95%;
    max-width: 1200px;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
}

.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.custom-modal-header h5 {
    margin: 0;
    font-size: 1.25rem;
}

.custom-modal-header .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    color: #6c757d;
}

.custom-modal-header .btn-close:hover {
    color: #000;
}

.custom-modal-body {
    padding: 0;
    flex: 1;
    overflow: hidden;
}

.custom-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .custom-modal-content {
        width: 98%;
        margin: 1% auto;
        max-height: 95vh;
    }
}

/* Mejoras para el visor de PDF */
#pdfContainer {
    position: relative;
    overflow: hidden;
}

#pdfViewer {
    transition: opacity 0.3s ease-in-out;
}

#pdfViewer.loading {
    opacity: 0.5;
}

#pdfLoading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}

#pdfError {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}
</style>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles del Recurso Digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
                <!-- Contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
var currentPDFUrl = '';

function verPDF(url, titulo) {
    currentPDFUrl = url;
    
    // Mostrar loading y ocultar otros elementos
    document.getElementById('pdfLoading').style.display = 'block';
    document.getElementById('pdfError').style.display = 'none';
    document.getElementById('pdfViewer').style.display = 'none';
    
    // Convertir URL a HTTPS si es necesario
    var secureUrl = url;
    if (url.startsWith('http://')) {
        secureUrl = url.replace('http://', 'https://');
    }
    
    // Configurar el iframe con el PDF directamente
    var iframe = document.getElementById('pdfViewer');
    iframe.src = secureUrl;
    document.getElementById('modalPDFLabel').textContent = 'Visualizar: ' + titulo;
    document.getElementById('descargarPDF').href = secureUrl;
    
    // Mostrar el modal personalizado
    var modal = document.getElementById('modalPDF');
    modal.style.display = 'block';
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    // Enfocar el primer botón
    var firstButton = modal.querySelector('.btn-close');
    if (firstButton) {
        firstButton.focus();
    }
    
    // Manejar la carga del iframe
    iframe.onload = function() {
        // Ocultar loading y mostrar iframe
        document.getElementById('pdfLoading').style.display = 'none';
        document.getElementById('pdfViewer').style.display = 'block';
        
        // Verificar si realmente se cargó el PDF
        setTimeout(function() {
            try {
                // Intentar acceder al contenido del iframe
                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (!iframeDoc || iframeDoc.body === null || iframeDoc.body.innerHTML.trim() === '') {
                    mostrarErrorPDF();
                }
            } catch (e) {
                // Si hay error de CORS, asumir que se cargó correctamente
                console.log('PDF cargado (CORS bloqueado, pero asumimos éxito)');
            }
        }, 1000);
    };
    
    iframe.onerror = function() {
        mostrarErrorPDF();
    };
    
    // Timeout de seguridad después de 10 segundos
    setTimeout(function() {
        if (document.getElementById('pdfLoading').style.display !== 'none') {
            mostrarErrorPDF();
        }
    }, 10000);
}

function cerrarModalPDF() {
    var modal = document.getElementById('modalPDF');
    modal.style.display = 'none';
    
    // Restaurar scroll del body
    document.body.style.overflow = 'auto';
    
    // Limpiar el iframe y resetear estados
    var iframe = document.getElementById('pdfViewer');
    iframe.src = '';
    iframe.onload = null;
    iframe.onerror = null;
    
    // Ocultar todos los elementos del modal
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfError').style.display = 'none';
    document.getElementById('pdfLoading').style.display = 'none';
    
    // Limpiar URL actual
    currentPDFUrl = '';
}

function mostrarErrorPDF() {
    document.getElementById('pdfLoading').style.display = 'none';
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfError').style.display = 'block';
}

function abrirPDFEnNuevaPestana() {
    if (currentPDFUrl) {
        window.open(currentPDFUrl, '_blank');
    }
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        var modal = document.getElementById('modalPDF');
        if (modal && modal.style.display === 'block') {
            cerrarModalPDF();
        }
    }
});

function verDetalles(id) {
    // Aquí puedes implementar la lógica para cargar los detalles
    document.getElementById('contenidoDetalles').innerHTML = '<p>Cargando detalles del recurso #' + id + '...</p>';
    var modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}

function editarRecurso(id) {
    // Aquí puedes implementar la lógica para editar
    alert('Editar recurso #' + id);
}

function eliminarRecurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este recurso digital?')) {
        // Aquí puedes implementar la lógica para eliminar
        alert('Eliminar recurso #' + id);
    }
}
</script>

<?= $footer ?>
