<?= $header ?>
<?= $navbar; ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">
<div class="container">
    <!-- Hero section con buscador -->
    <div class="hero-section mt-4 mb-4">
        <!-- Carrusel de fondo -->
        <div id="heroCarousel" class="hero-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= base_url('img/portada_1.png') ?>" alt="Biblioteca 1">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('img/portada_2.png') ?>" alt="Biblioteca 2">
                </div>
            </div>
        </div>
        
        <!-- Overlay con gradiente -->
        <div class="hero-overlay"></div>
        
        <!-- Contenido del hero -->
        <div class="hero-content">
            <h1 class="display-4 mb-3">Biblioteca Virtual</h1>
            <p class="lead mb-4">Horacio Zeballos Gámez</p>
            <form action="<?= base_url('recursos/buscarRecursos') ?>" method="get" class="w-100 d-flex justify-content-center">
                <div class="input-group" style="max-width: 500px;">
                    <input 
                        type="search" 
                        name="query" 
                        class="form-control form-control-lg rounded-start-pill" 
                        placeholder="Buscar recursos educativos..." 
                        aria-label="Buscar" 
                        required>
                    <button type="submit" class="btn btn-danger btn-lg rounded-end-pill">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin Hero  -->
    <!-- Pestañas para alternar entre Niveles y Categorías -->
    <div class="py-4">
        <ul class="nav nav-tabs" id="exploreTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="niveles-tab" data-bs-toggle="tab" data-bs-target="#niveles" type="button" role="tab">
                    <i class="fas fa-graduation-cap me-2"></i>Niveles Educativos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="categorias-tab" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
                    <i class="fas fa-books me-2"></i>Categorías
                </button>
            </li>
        </ul>
        
        <div class="tab-content mt-4" id="exploreTabContent">
            <!-- Tab de Niveles -->
            <div class="tab-pane fade show active" id="niveles" role="tabpanel">
                <div class="row">
                    <?php if (!empty($niveles)): ?>
                        <?php foreach ($niveles as $nivel): ?>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center d-flex flex-column p-4">
                                        <?php 
                                        $icon = '';
                                        $descripcion = '';
                                        switch($nivel) {
                                            case 'Inicial':
                                                $icon = 'fas fa-baby';
                                                $descripcion = 'Recursos para los más pequeños';
                                                break;
                                            case 'Primaria':
                                                $icon = 'fas fa-child';
                                                $descripcion = 'Material didáctico primario';
                                                break;
                                            case 'Secundaria':
                                                $icon = 'fas fa-user-graduate';
                                                $descripcion = 'Recursos avanzados';
                                                break;
                                            default:
                                                $icon = 'fas fa-book';
                                                $descripcion = 'Recursos especializados';
                                        }
                                        ?>
                                        <i class="<?= $icon ?> fa-2x mb-3"></i>
                                        <h5 class="card-title"><?= esc($nivel) ?></h5>
                                        <p class="card-text flex-grow-1"><?= $descripcion ?></p>
                                        <a href="#" class="btn btn-outline-primary">
                                            Explorar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No se encontraron niveles educativos disponibles.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Tab de Categorías -->
            <div class="tab-pane fade" id="categorias" role="tabpanel">
                <div class="row">
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center d-flex flex-column p-3">
                                        <i class="fas fa-bookmark fa-lg mb-2"></i>
                                        <h6 class="card-title flex-grow-1"><?= esc($categoria['categoria']) ?></h6>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No se encontraron categorías disponibles.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de pestañas para alternar-->
    <!-- Sección de Libros Populares -->
    <div class="container mt-4 mb-5">
        <div class="card-body px-0">
            <div class="row">
                <div class="col-12 text-center mb-4 border-bottom pb-3">
                    <h2 class="text-primary mb-2">Nuestros Recursos</h2>
                    <p class="text-muted">
                        Todos los recursos disponibles en nuestra biblioteca
                        <?php if (!empty($librosPopulares)): ?>
                            <span class="badge badge-contador-recursos ms-2"><?= count($librosPopulares) ?> recursos</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="row g-3">
                <?php if (!empty($librosPopulares)): ?>
                    <?php foreach ($librosPopulares as $libro): ?>
                        <?= view('partials/libro_card', [
                            'libro' => $libro,
                            'imagenPrefix' => base_url(),
                            'colClasses' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6'
                        ]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay recursos disponibles en este momento.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?= site_url('catalogo') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Explorar Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
<!-- Fin de sección -->

<!-- Modal para detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
            <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles del recurso...</p>
                </div>
            </div>
        </div>
    </div>
</div>

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

</div>
<?= $footer ?>

<script>
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
    fetch(`<?= base_url('recurso/detalles/') ?>${idRecurso}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
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

function verPDF(url, titulo) {
    currentPDFUrl = url;
    
    // Mostrar loading y ocultar otros elementos
    document.getElementById('pdfLoading').style.display = 'block';
    document.getElementById('pdfError').style.display = 'none';
    document.getElementById('pdfViewer').style.display = 'none';
    
    // Usar la URL tal como viene (no forzar HTTPS en desarrollo local)
    var secureUrl = url;
    // Solo convertir a HTTPS si estamos en producción
    if (url.startsWith('http://') && !window.location.hostname.includes('localhost') && !window.location.hostname.includes('.test')) {
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
    
    // Detener cualquier lectura de voz activa
    stopVoiceReading();
    
    // Limpiar el iframe y resetear estados
    var iframe = document.getElementById('pdfViewer');
    iframe.src = '';
    iframe.onload = null;
    iframe.onerror = null;
    
    // Ocultar todos los elementos del modal
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfLoading').style.display = 'none';
    document.getElementById('pdfError').style.display = 'none';
    
    // Limpiar variables
    currentPDFUrl = '';
    isPdfLoaded = false;
    pdfTextContent = '';
    pdfDoc = null;
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

// ===== FUNCIONES DE VOZ (copiadas del administrador) =====

function toggleVoiceReading() {
    if (isVoiceReading) {
        pauseVoiceReading();
    } else {
        startVoiceReading();
    }
}

function startVoiceReading() {
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

function pauseVoiceReading() {
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

function stopVoiceReading() {
    speechSynthesis.cancel();
    isVoiceReading = false;
    isVoicePaused = false;
    currentUtterance = null;
    updateVoiceButtons();
}

function changeVoiceSpeed(speed) {
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

// Función para agregar a favoritos
function agregarFavorito(idRecurso) {
    Swal.fire({
        title: 'Agregar a Favoritos',
        text: '¿Deseas agregar este recurso a tus favoritos?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, agregar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la lógica para agregar a favoritos
            Swal.fire({
                title: 'Agregado a Favoritos',
                text: 'El recurso ha sido agregado a tus favoritos.',
                icon: 'success',
                confirmButtonText: 'Entendido'
            });
        }
    });
}

// Función para compartir recurso
function compartirRecurso(idRecurso) {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: 'Recurso de Biblioteca Virtual HZG',
            text: 'Mira este recurso de la Biblioteca Virtual HZG',
            url: url
        });
    } else {
        // Fallback: copiar al portapapeles
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                title: 'Enlace copiado',
                text: 'El enlace ha sido copiado al portapapeles.',
                icon: 'success',
                confirmButtonText: 'Entendido'
            });
        });
    }
}

// Función para solicitar préstamo de un recurso
function solicitarPrestamo(idRecurso) {
    // Cerrar el modal de detalles del libro si está abierto
    const libroModal = document.getElementById('libroModal');
    if (libroModal) {
        const modalInstance = bootstrap.Modal.getInstance(libroModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
    
    // Esperar a que termine la animación de cierre del modal anterior
    setTimeout(() => {
        // Cargar el formulario de solicitud de préstamo
        fetch(`<?= base_url('prestamo/formulario/') ?>${idRecurso}`)
            .then(response => response.text())
            .then(html => {
                // Mostrar el formulario en un modal
                Swal.fire({
                    title: 'Solicitud de Préstamo',
                    html: html,
                    width: '600px',
                    showConfirmButton: false,
                    showCloseButton: true
                });
            })
            .catch(error => {
                console.error('Error al cargar el formulario:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar el formulario de solicitud.',
                    icon: 'error'
                });
            });
    }, 100); // Esperar 300ms para que el modal termine de cerrarse
}
</script>
<script>
// Función para enviar la solicitud (definida globalmente)
function enviarSolicitudPrestamo() {
    const form = document.getElementById('formSolicitudPrestamo');
    
    // Validar el formulario antes de enviar
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
        const formData = new FormData(form);
        // Si el motivo es "Otro", reemplazamos el valor del motivo con el texto proporcionado
        if (document.getElementById('motivo').value === 'Otro') {
            formData.set('motivo', document.getElementById('otroMotivo').value);
        }
        

        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Enviando solicitud',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar solicitud mediante AJAX
        fetch('<?= base_url('prestamo/solicitar') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Solicitud Enviada!',
                    text: 'Tu solicitud de préstamo ha sido enviada. Te notificaremos cuando sea procesada.',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    showCancelButton: false,
                    confirmButtonText: 'Entendido'
                }).then((result) => {
                    // Cerrar el modal de SweetAlert (formulario)
                    if (result.isConfirmed) {
                        Swal.close();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al procesar tu solicitud',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ha ocurrido un error al enviar tu solicitud',
                icon: 'error'
            });
        });
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar el campo "otro motivo" cuando se selecciona "Otro"
    document.getElementById('motivo').addEventListener('change', function() {
        const otroMotivoContainer = document.getElementById('otroMotivoContainer');
        otroMotivoContainer.style.display = this.value === 'Otro' ? 'block' : 'none';
        // Solo requerido si está visible
        const otroMotivo = document.getElementById('otroMotivo');
        otroMotivo.required = this.value === 'Otro';
    });

    // Validar que fecha devolución sea posterior a fecha inicio
    document.getElementById('fechaInicio').addEventListener('change', function() {
        const fechaDevolucion = document.getElementById('fechaDevolucion');
        const fechaInicioValue = new Date(this.value);
        // Sumamos un día para la fecha mínima de devolución
        fechaInicioValue.setDate(fechaInicioValue.getDate() + 1);
        fechaDevolucion.min = fechaInicioValue.toISOString().split('T')[0];
        // Si la fecha actual es menor que la mínima, actualizar
        if (fechaDevolucion.value < fechaDevolucion.min) {
            fechaDevolucion.value = fechaDevolucion.min;
        }
    });
});
</script>