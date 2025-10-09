
<?php if (!empty($recursos)): ?>
    <div class="mb-4">
        <?php foreach($recursos as $recurso): ?>
        <div class="card mb-3 shadow-sm border-0 libro-item" 
            style="cursor: pointer; transition: all 0.3s ease;" 
            data-bs-toggle="modal" 
            data-bs-target="#libroModal"
            data-libro-id="<?= $recurso['idrecurso'] ?>"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
            <div class="card-body d-flex align-items-center p-3">
                <div class="me-3" style="width: 80px;">
                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 100px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-2 text-primary fw-bold"><?= esc($recurso['titulo']) ?></h5>
                    <div class="mb-1 text-muted">
                        <i class="fas fa-user me-1"></i><span class="fw-bold">Autores:</span> <?= esc($recurso['nomautor'] ?? 'Sin autor') ?> |
                        <i class="fas fa-folder me-1"></i><span class="fw-bold">Categoría:</span> <?= esc($recurso['categoria'] ?? 'Sin categoría') ?> |
                        <i class="fas fa-layer-group me-1"></i><span class="fw-bold">Subcategoría:</span> <?= esc($recurso['subcategoria'] ?? 'Sin subcategoría') ?> |
                        <i class="fas fa-calendar me-1"></i><span class="fw-bold">Año:</span> <?= esc($recurso['anio']) ?>
                    </div>
                </div>
                <div class="ms-3">
                    <?php 
                    // Determinar si es un recurso digital
                    $esDigital = false;
                    
                    if (isset($recurso['tiporecurso']) && stripos($recurso['tiporecurso'], 'digital') !== false) {
                        $esDigital = true;
                    } elseif (isset($recurso['idtiporecurso']) && $recurso['idtiporecurso'] == 2) {
                        $esDigital = true;
                    } elseif (isset($recurso['rutaarchivo']) && !empty($recurso['rutaarchivo'])) {
                        $esDigital = true;
                    } elseif (isset($recurso['archivo']) && !empty($recurso['archivo'])) {
                        $esDigital = true;
                    }
                    ?>
                    

                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal para detalles del libro -->
    <div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="libroModalLabel">
                       Detalles del Libro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="libroModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-2">Cargando detalles del libro...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-primary mb-2">No se encontraron recursos</h5>
            <p class="text-muted mb-0">No hay recursos que coincidan con los criterios de búsqueda.</p>
        </div>
    </div>
    
<?php endif; ?>
<!-- Modal personalizado para ver PDF (copiado del administrador) -->
<div id="modalPDF" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="cerrarModalPDF()"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 id="modalPDFLabel">Visualizar PDF</h5>
            <button type="button" class="btn-close" onclick="cerrarModalPDF()" aria-label="Cerrar">
                <i class="fas fa-times"></i>
            </button>
        </div>
                <div class="custom-modal-body">
                    <div id="pdfContainer" style="height: 700px; position: relative;">
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
                    <i class="fas fa-file-pdf fs-1 text-muted mb-3" aria-hidden="true"></i>
                    <h5>No se puede mostrar el PDF en el visor</h5>
                    <p class="text-muted">El archivo se abrirá en una nueva pestaña</p>
                    <button class="btn btn-primary" onclick="abrirPDFEnNuevaPestana()" aria-label="Abrir PDF en nueva pestaña">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i> Abrir PDF
                    </button>
                </div>
                <!-- Canvas oculto para PDF.js -->
                <canvas id="pdfCanvas" style="display: none;"></canvas>
            </div>
        </div>
        <div class="custom-modal-footer">
            <!-- Controles de voz amigables para niños -->
            <div class="voice-controls child-friendly">
                <div class="voice-buttons">
                    <button id="btnVoicePlay" type="button" class="btn btn-success voice-btn" onclick="toggleVoiceReading()" aria-label="Reproducir voz">
                        <i class="fas fa-play" aria-hidden="true"></i> <span id="voiceText">Leer</span>
                    </button>
                    <button id="btnVoicePause" type="button" class="btn btn-warning voice-btn" onclick="pauseVoiceReading()" style="display: none;" aria-label="Pausar voz">
                        <i class="fas fa-pause" aria-hidden="true"></i> Pausar
                    </button>
                    <button id="btnVoiceStop" type="button" class="btn btn-danger voice-btn" onclick="stopVoiceReading()" style="display: none;" aria-label="Detener voz">
                        <i class="fas fa-stop" aria-hidden="true"></i> Detener
                    </button>
                </div>
                <div class="voice-speed-control">
                    <label for="voiceSpeed" class="form-label">Velocidad:</label>
                    <div class="speed-container">
                        <span class="speed-label">Lento</span>
                        <input type="range" id="voiceSpeed" class="form-range speed-slider" min="0.5" max="1.5" step="0.1" value="0.8" onchange="changeVoiceSpeed(this.value)">
                        <span class="speed-label">Rápido</span>
                    </div>
                    <span id="speedValue" class="speed-value">0.8x</span>
                </div>
            </div>
            
            <!-- Botones principales -->
            <div class="main-controls">
                <a id="descargarPDF" href="#" target="_blank" class="btn btn-primary" aria-label="Descargar PDF">
                    <i class="fas fa-download" aria-hidden="true"></i> Descargar PDF
                </a>
                <button type="button" class="btn btn-secondary" onclick="cerrarModalPDF()" aria-label="Cerrar modal">Cerrar</button>
            </div>
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
    z-index: 1060;
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
    margin: 1% auto;
    width: 98%;
    max-width: 1400px;
    max-height: 95vh;
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
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 0 0 8px 8px;
}

.voice-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Estilos minimalistas para controles */
.child-friendly {
    background: white;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.voice-buttons {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.voice-btn {
    border-radius: 6px !important;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem !important;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    box-shadow: none;
}

.voice-btn:hover {
    transform: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.voice-btn:active {
    transform: none;
}

/* Botón de reproducir/leer */
.btn-success.voice-btn {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-success.voice-btn:hover {
    background-color: #157347;
    border-color: #146c43;
}

/* Botón de pausa */
.btn-warning.voice-btn {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-warning.voice-btn:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
}

/* Botón de detener */
.btn-danger.voice-btn {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-danger.voice-btn:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}

.speed-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.speed-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6c757d;
}

.speed-slider {
    flex: 1;
    height: 4px;
    border-radius: 2px;
    background: #e9ecef;
    outline: none;
    appearance: none;
}

.speed-slider::-webkit-slider-thumb {
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6c757d;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.speed-slider::-moz-range-thumb {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6c757d;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.speed-value {
    font-weight: 500;
    color: #495057;
    font-size: 0.75rem;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
    min-width: 35px;
    text-align: center;
}

.voice-speed-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: 0.75rem;
}

.voice-speed-control label {
    margin: 0;
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.voice-speed-control input[type="range"] {
    width: 80px;
}

.voice-speed-control span {
    font-size: 0.75rem;
    font-weight: 500;
    color: #495057;
    min-width: 35px;
}

.main-controls {
    display: flex;
    gap: 0.5rem;
}

.main-controls .btn {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.main-controls .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.main-controls .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.main-controls .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.main-controls .btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .custom-modal-content {
        width: 98%;
        margin: 1% auto;
        max-height: 95vh;
    }
    
    .custom-modal-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .voice-controls {
        width: 100%;
        justify-content: center;
    }
    
    .main-controls {
        width: 100%;
        justify-content: center;
    }
    
    .voice-speed-control {
        margin-left: 0;
        margin-top: 0.5rem;
        width: 100%;
        justify-content: center;
    }
    
    #pdfContainer {
        height: 500px !important;
    }
}

/* Mejoras para el visor de PDF */
#pdfContainer {
    position: relative;
    overflow: hidden;
}
</style>
<script>
    // AJAX para el buscador principal (solo actualiza resultados)
    document.querySelector('form[action*="buscarRecursos"]').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        fetch('<?= base_url('recursos/filtrosBusqueda') ?>?query=' + encodeURIComponent(formData.get('query')), {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados-busqueda').innerHTML = html;
            // Opcional: limpiar los filtros si quieres que el usuario vea todos los resultados del query
            // document.getElementById('filtros-form').reset();
        });
    });

    // AJAX para filtros
    document.getElementById('filtros-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        fetch(form.action + '?' + params, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados-busqueda').innerHTML = html;
        });
    });

    // Borrar filtros y recargar resultados sin filtros
    document.getElementById('reset-filtros').addEventListener('click', function(e) {
        setTimeout(function() {
            const form = document.getElementById('filtros-form');
            // Limpiar todos los campos manualmente por si el reset no lo hace
            form.reset();
            // Recargar resultados sin filtros
            fetch(form.action, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('resultados-busqueda').innerHTML = html;
            });
        }, 50);
    });

    // Manejar clic en libros para mostrar modal con detalles
    document.addEventListener('click', function(e) {
        const libroItem = e.target.closest('.libro-item');
        if (libroItem) {
            const libroId = libroItem.getAttribute('data-libro-id');
            
            // Mostrar loading en el modal
            document.getElementById('libroModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `;
            
            // Cargar detalles del libro
            fetch('<?= base_url('recursos/detalles') ?>/' + libroId, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('libroModalBody').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('libroModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar los detalles del libro.
                    </div>
                `;
            });
        }
    });


 // Función para cargar detalles del libro
function cargarDetallesLibro(idRecurso) {
    const modalBody = document.getElementById('libroModalBody');
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del recurso...</p>
        </div>
    `;
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('recursos/detalles/') ?>${idRecurso}`, {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => response.text())
    .then(html => {
        modalBody.innerHTML = html;
    })
    .catch(error => {
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error al cargar los detalles del recurso.
            </div>
        `;
    });
}

// Limpiar modal cuando se cierre
document.getElementById('libroModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('libroModalBody').innerHTML = '';
});

// Variables globales para el visor de PDF (copiado del administrador)
var currentPDFUrl = '';
var speechSynthesis = window.speechSynthesis;
var currentUtterance = null;
var isVoiceReading = false;
var isVoicePaused = false;
var currentVoiceSpeed = 0.8;

// Variables para PDF.js
var pdfDoc = null;
var pdfTextContent = '';
var isPdfLoaded = false;
var pdfjsLibLoaded = false;

/**
 * Leer PDF directamente - función principal (copiado del administrador)
 */
function leerPDFDirecto(url, titulo) {
    verPDF(url, titulo);
}
window.leerPDFDirecto = leerPDFDirecto;

function verPDF(url, titulo) {
    currentPDFUrl = url;
    
    // Verificar que los elementos existen antes de acceder a ellos
    const pdfLoading = document.getElementById('pdfLoading');
    const pdfError = document.getElementById('pdfError');
    const pdfViewer = document.getElementById('pdfViewer');
    
    if (!pdfLoading || !pdfError || !pdfViewer) {
        console.error('Elementos del modal PDF no encontrados:', {
            pdfLoading: !!pdfLoading,
            pdfError: !!pdfError,
            pdfViewer: !!pdfViewer
        });
        alert('Error: No se puede mostrar el modal PDF. Elementos no encontrados.');
        return;
    }
    
    // Mostrar loading y ocultar otros elementos
    pdfLoading.style.display = 'block';
    pdfError.style.display = 'none';
    pdfViewer.style.display = 'none';
    
    // Usar la URL tal como viene (no forzar HTTPS en desarrollo local)
    var secureUrl = url;
    // Solo convertir a HTTPS si estamos en producción
    if (url.startsWith('http://') && !window.location.hostname.includes('localhost') && !window.location.hostname.includes('.test')) {
        secureUrl = url.replace('http://', 'https://');
    }
    
    // Configurar el iframe con el PDF directamente
    pdfViewer.src = secureUrl;
    
    // Verificar otros elementos del modal
    const modalPDFLabel = document.getElementById('modalPDFLabel');
    const descargarPDF = document.getElementById('descargarPDF');
    const modal = document.getElementById('modalPDF');
    
    if (!modalPDFLabel || !descargarPDF || !modal) {
        console.error('Elementos adicionales del modal no encontrados:', {
            modalPDFLabel: !!modalPDFLabel,
            descargarPDF: !!descargarPDF,
            modal: !!modal
        });
        alert('Error: No se puede mostrar el modal PDF. Elementos del modal no encontrados.');
        return;
    }
    
    modalPDFLabel.textContent = 'Visualizar: ' + titulo;
    descargarPDF.href = secureUrl;
    
    // Mostrar el modal personalizado
    modal.style.display = 'block';
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    // Enfocar el primer botón
    var firstButton = modal.querySelector('.btn-close');
    if (firstButton) {
        firstButton.focus();
    }
    
    // Cargar PDF.js y extraer texto para el lector de voz
    console.log('Iniciando carga de PDF.js para extracción de texto...');
    loadPDFJSLibrary().then(function() {
        console.log('PDF.js cargado exitosamente, iniciando extracción de texto...');
        loadPDFForTextExtraction(secureUrl);
    }).catch(function(error) {
        console.error('Error cargando PDF.js:', error);
        pdfTextContent = 'No se pudo cargar PDF.js. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        isPdfLoaded = true;
    });
    
    // Manejar la carga del iframe
    pdfViewer.onload = function() {
        // Ocultar loading y mostrar iframe
        pdfLoading.style.display = 'none';
        pdfViewer.style.display = 'block';
        
        // Verificar si realmente se cargó el PDF
        setTimeout(function() {
            try {
                // Intentar acceder al contenido del iframe
                var iframeDoc = pdfViewer.contentDocument || pdfViewer.contentWindow.document;
                if (!iframeDoc || iframeDoc.body === null || iframeDoc.body.innerHTML.trim() === '') {
                    mostrarErrorPDF();
                }
            } catch (e) {
                // Si hay error de CORS, asumir que se cargó correctamente
                console.log('PDF cargado (CORS bloqueado, pero asumimos éxito)');
            }
        }, 1000);
    };
    
    pdfViewer.onerror = function() {
        mostrarErrorPDF();
    };
    
    // Timeout de seguridad después de 10 segundos
    setTimeout(function() {
        if (document.getElementById('pdfLoading').style.display !== 'none') {
            mostrarErrorPDF();
        }
    }, 10000);
}
window.verPDF = verPDF;

function cerrarModalPDF() {
    var modal = document.getElementById('modalPDF');
    if (!modal) {
        console.error('Modal PDF no encontrado');
        return;
    }
    
    modal.style.display = 'none';
    
    // Restaurar scroll del body
    document.body.style.overflow = 'auto';
    
    // Detener cualquier lectura de voz activa
    if (typeof stopVoiceReading === 'function') {
        stopVoiceReading();
    }
    
    // Limpiar el iframe y resetear estados
    var iframe = document.getElementById('pdfViewer');
    if (iframe) {
        iframe.src = '';
        iframe.onload = null;
        iframe.onerror = null;
    }
    
    // Ocultar todos los elementos del modal (con verificaciones)
    const pdfViewer = document.getElementById('pdfViewer');
    const pdfLoading = document.getElementById('pdfLoading');
    const pdfError = document.getElementById('pdfError');
    
    if (pdfViewer) pdfViewer.style.display = 'none';
    if (pdfLoading) pdfLoading.style.display = 'none';
    if (pdfError) pdfError.style.display = 'none';
    
    // Limpiar variables
    currentPDFUrl = '';
    isPdfLoaded = false;
    pdfTextContent = '';
    pdfDoc = null;
}
window.cerrarModalPDF = cerrarModalPDF;

function mostrarErrorPDF() {
    const pdfLoading = document.getElementById('pdfLoading');
    const pdfViewer = document.getElementById('pdfViewer');
    const pdfError = document.getElementById('pdfError');
    
    if (pdfLoading) pdfLoading.style.display = 'none';
    if (pdfViewer) pdfViewer.style.display = 'none';
    if (pdfError) pdfError.style.display = 'block';
}
window.mostrarErrorPDF = mostrarErrorPDF;

function abrirPDFEnNuevaPestana() {
    if (currentPDFUrl) {
        window.open(currentPDFUrl, '_blank');
    }
}
window.abrirPDFEnNuevaPestana = abrirPDFEnNuevaPestana;

// ===== FUNCIONES DE VOZ (copiadas del administrador) =====

window.toggleVoiceReading = function() {
    if (isVoiceReading) {
        pauseVoiceReading();
    } else {
        startVoiceReading();
    }
}

window.startVoiceReading = function() {
    // Detener cualquier lectura anterior
    stopVoiceReading();
    
    // Verificar si el PDF aún se está cargando
    if (!isPdfLoaded) {
        alert('El PDF aún se está cargando. Por favor espera un momento e intenta nuevamente.');
        return;
    }
    
    // Obtener el texto del PDF
    var pdfText = extractTextFromPDF();
    
    if (!pdfText || pdfText.trim() === '') {
        alert('No se pudo extraer texto del PDF para la lectura de voz.');
        return;
    }
    
    // Verificar si el texto es muy corto (posible error)
    if (pdfText.length < 10) {
        alert('El texto extraído del PDF es muy corto. Es posible que el PDF esté protegido o no contenga texto.');
        return;
    }
    
    // Crear utterance con configuración amigable para niños
    currentUtterance = new SpeechSynthesisUtterance(pdfText);
    
    // Configuración optimizada para niños
    currentUtterance.rate = Math.max(0.6, currentVoiceSpeed * 0.8); // Más lento para niños
    currentUtterance.pitch = 1.3; // Pitch más alto, más amigable
    currentUtterance.volume = 0.9; // Volumen más alto para mejor audición
    
    // Configurar idioma (español)
    currentUtterance.lang = 'es-ES';
    
    // Intentar seleccionar una voz más amigable para niños
    selectChildFriendlyVoice();
    
    // Eventos
    currentUtterance.onstart = function() {
        isVoiceReading = true;
        isVoicePaused = false;
        updateVoiceButtons();
        console.log('Iniciando lectura de voz del PDF...');
    };
    
    currentUtterance.onend = function() {
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
        console.log('Lectura de voz completada.');
    };
    
    currentUtterance.onerror = function(event) {
        console.error('Error en speech synthesis:', event.error);
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
        alert('Error al reproducir la voz: ' + event.error);
    };
    
    // Iniciar lectura
    speechSynthesis.speak(currentUtterance);
}

window.pauseVoiceReading = function() {
    if (isVoiceReading && !isVoicePaused) {
        speechSynthesis.pause();
        isVoicePaused = true;
        updateVoiceButtons();
    } else if (isVoicePaused) {
        speechSynthesis.resume();
        isVoicePaused = false;
        updateVoiceButtons();
    }
}

window.stopVoiceReading = function() {
    speechSynthesis.cancel();
    isVoiceReading = false;
    isVoicePaused = false;
    currentUtterance = null;
    updateVoiceButtons();
}

window.changeVoiceSpeed = function(speed) {
    currentVoiceSpeed = parseFloat(speed);
    document.getElementById('speedValue').textContent = speed + 'x';
    
    if (currentUtterance) {
        // Aplicar velocidad más lenta para niños
        currentUtterance.rate = Math.max(0.6, currentVoiceSpeed * 0.8);
    }
}

// Función para seleccionar una voz amigable para niños
function selectChildFriendlyVoice() {
    if (!currentUtterance) return;
    
    // Obtener todas las voces disponibles
    const voices = speechSynthesis.getVoices();
    
    // Voces preferidas para niños (más amigables)
    const childFriendlyVoices = [
        'Microsoft Sabina Desktop - Spanish (Mexico)',
        'Microsoft Helena Desktop - Spanish (Spain)', 
        'Google español',
        'Microsoft Laura Desktop - Spanish (Spain)',
        'Microsoft Monica Desktop - Spanish (Spain)',
        'Microsoft Paulina Desktop - Spanish (Mexico)',
        'Microsoft Teresa Desktop - Spanish (Spain)'
    ];
    
    // Buscar una voz amigable para niños
    let selectedVoice = null;
    
    // Primero intentar con las voces preferidas
    for (const preferredVoice of childFriendlyVoices) {
        selectedVoice = voices.find(voice => 
            voice.name.includes('Sabina') || 
            voice.name.includes('Helena') ||
            voice.name.includes('Laura') ||
            voice.name.includes('Monica') ||
            voice.name.includes('Paulina') ||
            voice.name.includes('Teresa') ||
            (voice.name.toLowerCase().includes('google') && voice.lang.startsWith('es'))
        );
        if (selectedVoice) break;
    }
    
    // Si no se encuentra una voz preferida, buscar cualquier voz femenina en español
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => 
            voice.lang.startsWith('es') && 
            (voice.name.toLowerCase().includes('female') || 
             voice.name.toLowerCase().includes('woman') ||
             voice.name.toLowerCase().includes('mujer') ||
             voice.name.toLowerCase().includes('femenina'))
        );
    }
    
    // Si aún no se encuentra, usar cualquier voz en español
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => voice.lang.startsWith('es'));
    }
    
    // Aplicar la voz seleccionada
    if (selectedVoice) {
        currentUtterance.voice = selectedVoice;
        console.log('Voz seleccionada para niños:', selectedVoice.name);
    }
}

function updateVoiceButtons() {
    var playBtn = document.getElementById('btnVoicePlay');
    var pauseBtn = document.getElementById('btnVoicePause');
    var stopBtn = document.getElementById('btnVoiceStop');
    var voiceText = document.getElementById('voiceText');
    
    if (isVoiceReading) {
        if (isVoicePaused) {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            stopBtn.style.display = 'inline-block';
            voiceText.textContent = 'Continuar';
            pauseBtn.innerHTML = '<i class="fas fa-play"></i> Continuar';
        } else {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            stopBtn.style.display = 'inline-block';
            voiceText.textContent = 'Leyendo...';
            pauseBtn.innerHTML = '<i class="fas fa-pause"></i> Pausar';
        }
    } else {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
        stopBtn.style.display = 'none';
        voiceText.textContent = 'Leer';
    }
}

function extractTextFromPDF() {
    // Usar el texto extraído del PDF con PDF.js
    if (pdfTextContent && pdfTextContent.length > 0) {
        return pdfTextContent;
    }
    
    // Texto de ejemplo si no se puede extraer del PDF
    return 'Bienvenido a la lectura de este cuento. El sistema de voz está funcionando correctamente. Este es un texto de ejemplo para demostrar la funcionalidad de lectura de voz.';
}

// ===== FUNCIONES DE PDF.js (copiadas del administrador) =====

// Función para cargar PDF.js dinámicamente con múltiples CDNs
function loadPDFJSLibrary() {
    return new Promise(function(resolve, reject) {
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLibLoaded = true;
            resolve();
            return;
        }
        
        // Lista de CDNs alternativos (incluyendo versiones más estables)
        var cdnUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.4.168/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@4.4.168/build/pdf.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.min.js'
        ];
        
        var workerUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.4.168/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@4.4.168/build/pdf.worker.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.worker.min.js'
        ];
        
        var currentIndex = 0;
        
        function tryLoadScript() {
            if (currentIndex >= cdnUrls.length) {
                reject(new Error('No se pudo cargar PDF.js desde ningún CDN'));
                return;
            }
            
            var script = document.createElement('script');
            script.src = cdnUrls[currentIndex];
            
            script.onload = function() {
                console.log('PDF.js cargado desde:', cdnUrls[currentIndex]);
                
                // Configurar worker
                if (typeof pdfjsLib !== 'undefined' && pdfjsLib.GlobalWorkerOptions) {
                    pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrls[currentIndex];
                    console.log('Worker configurado:', workerUrls[currentIndex]);
                }
                
                pdfjsLibLoaded = true;
                resolve();
            };
            
            script.onerror = function() {
                console.warn('Error cargando PDF.js desde:', cdnUrls[currentIndex]);
                currentIndex++;
                tryLoadScript();
            };
            
            document.head.appendChild(script);
        }
        
        tryLoadScript();
    });
}

function loadPDFForTextExtraction(url) {
    // Verificar que PDF.js esté cargado
    if (!pdfjsLibLoaded || typeof pdfjsLib === 'undefined') {
        console.error('PDF.js no está cargado');
        pdfTextContent = 'PDF.js no está disponible. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        isPdfLoaded = true;
        return;
    }
    
    // Resetear estado
    pdfDoc = null;
    pdfTextContent = '';
    isPdfLoaded = false;
    
    console.log('Iniciando carga de PDF con PDF.js...');
    
    // Cargar PDF con PDF.js
    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        pdfDoc = pdf;
        console.log('PDF cargado con PDF.js:', pdf.numPages, 'páginas');
        
        // Extraer texto de todas las páginas
        extractTextFromAllPages();
        
    }).catch(function(error) {
        console.error('Error cargando PDF con PDF.js:', error);
        
        // Verificar si es un error de CORS
        if (error.name === 'UnknownErrorException' || error.message.includes('CORS') || error.message.includes('fetch')) {
            pdfTextContent = 'No se pudo acceder al PDF debido a restricciones de CORS. Esto es normal en algunos servidores. La funcionalidad de voz está disponible con texto de ejemplo. Para una experiencia completa, el administrador debe configurar los headers CORS en el servidor.';
        } else {
            pdfTextContent = 'No se pudo extraer texto del PDF. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        }
        
        isPdfLoaded = true;
        console.log('Usando texto de ejemplo debido a error:', error.name || error.message);
    });
}

function extractTextFromAllPages() {
    if (!pdfDoc) {
        console.error('PDF no cargado');
        return;
    }
    
    var totalPages = pdfDoc.numPages;
    var allText = '';
    var pagesProcessed = 0;
    
    console.log('Extrayendo texto de', totalPages, 'páginas...');
    
    // Procesar cada página
    for (var pageNum = 1; pageNum <= totalPages; pageNum++) {
        pdfDoc.getPage(pageNum).then(function(page) {
            return page.getTextContent();
        }).then(function(textContent) {
            // Extraer texto de la página
            var pageText = '';
            for (var i = 0; i < textContent.items.length; i++) {
                pageText += textContent.items[i].str + ' ';
            }
            
            allText += pageText + '\n\n';
            pagesProcessed++;
            
            // Si hemos procesado todas las páginas
            if (pagesProcessed === totalPages) {
                pdfTextContent = allText.trim();
                isPdfLoaded = true;
                console.log('Texto extraído del PDF:', pdfTextContent.length, 'caracteres');
                
                // Limpiar texto (remover espacios excesivos, saltos de línea múltiples)
                pdfTextContent = pdfTextContent.replace(/\s+/g, ' ').trim();
                
                if (pdfTextContent.length === 0) {
                    pdfTextContent = 'Este PDF no contiene texto extraíble o está protegido.';
                }
            }
        }).catch(function(error) {
            console.error('Error extrayendo texto de página:', error);
            pagesProcessed++;
            
            // Si hemos procesado todas las páginas (incluso con errores)
            if (pagesProcessed === totalPages) {
                if (allText.trim().length === 0) {
                    pdfTextContent = 'No se pudo extraer texto del PDF. Puede estar protegido o no contener texto.';
                } else {
                    pdfTextContent = allText.trim();
                    pdfTextContent = pdfTextContent.replace(/\s+/g, ' ').trim();
                }
                isPdfLoaded = true;
                console.log('Extracción completada con algunos errores');
            }
        });
    }
}

// Manejar teclas de acceso rápido en el visor de PDF
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('modalPDF');
    if (modal.style.display === 'block') {
        switch(e.key) {
            case 'Escape':
                cerrarModalPDF();
                break;
        }
    }
});  

// ===== FUNCIÓN PARA SOLICITAR PRÉSTAMOS =====

/**
 * Solicitar préstamo de un recurso físico
 */
function solicitarPrestamo(idRecurso) {
    Swal.fire({
        title: '¿Solicitar Préstamo?',
        text: 'Se enviará una solicitud de préstamo para este recurso',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la llamada AJAX para solicitar el préstamo
            // Por ahora mostramos un mensaje de éxito
            Swal.fire({
                title: '¡Solicitud Enviada!',
                text: 'Tu solicitud de préstamo ha sido enviada. Te notificaremos cuando sea procesada.',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }
    });
}
</script>