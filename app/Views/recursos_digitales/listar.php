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
                            <th>Portada</th>
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
                                    <?php if (!empty($recurso->portada)): ?>
                                        <img src="<?= base_url(esc($recurso->portada)) ?>" 
                                             alt="Portada" 
                                             style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                             onerror="this.onerror=null;this.src='<?= base_url('img/portada_default.png') ?>';">
                                    <?php else: ?>
                                        <img src="<?= base_url('img/portada_default.png') ?>" 
                                             alt="Sin portada" 
                                             style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;">
                                    <?php endif; ?>
                                </td>
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
                                        <a href="<?= base_url($recurso->archivo) ?>" 
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
                                                onclick="verPDF('<?= base_url($recurso->archivo) ?>', '<?= esc($recurso->titulo) ?>')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
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
                            <td colspan="11" class="text-center text-muted py-4">
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
                <!-- Canvas oculto para PDF.js -->
                <canvas id="pdfCanvas" style="display: none;"></canvas>
            </div>
        </div>
        <div class="custom-modal-footer">
            <!-- Controles de voz amigables para niños -->
            <div class="voice-controls child-friendly">
                <div class="voice-buttons">
                    <button id="btnVoicePlay" type="button" class="btn btn-success btn-lg voice-btn" onclick="toggleVoiceReading()" aria-label="Reproducir voz">
                        <i class="ti ti-speakerphone" aria-hidden="true"></i> <span id="voiceText">🎤 Leer Cuento</span>
                    </button>
                    <button id="btnVoicePause" type="button" class="btn btn-warning btn-lg voice-btn" onclick="pauseVoiceReading()" style="display: none;" aria-label="Pausar voz">
                        <i class="ti ti-player-pause" aria-hidden="true"></i> ⏸️ Pausar
                    </button>
                    <button id="btnVoiceStop" type="button" class="btn btn-danger btn-lg voice-btn" onclick="stopVoiceReading()" style="display: none;" aria-label="Detener voz">
                        <i class="ti ti-player-stop" aria-hidden="true"></i> ⏹️ Detener
                    </button>
                </div>
                <div class="voice-speed-control">
                    <label for="voiceSpeed" class="form-label">🐌 Velocidad de lectura:</label>
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
                    <i class="ti ti-download" aria-hidden="true"></i> Descargar PDF
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
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    border-top: 3px solid #ff6b6b;
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
    border-radius: 0 0 15px 15px;
}

.voice-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Estilos amigables para niños */
.child-friendly {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.voice-buttons {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.voice-btn {
    border-radius: 25px !important;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.voice-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

.voice-btn:active {
    transform: translateY(0);
}

.speed-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
}

.speed-label {
    font-size: 0.9rem;
    font-weight: bold;
    color: #ff6b6b;
}

.speed-slider {
    flex: 1;
    height: 8px;
    border-radius: 5px;
    background: linear-gradient(to right, #ff6b6b, #4ecdc4);
    outline: none;
}

.speed-slider::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff6b6b;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.speed-value {
    font-weight: bold;
    color: #ff6b6b;
    font-size: 1.1rem;
    background: rgba(255, 255, 255, 0.8);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    border: 2px solid #ff6b6b;
}

.voice-speed-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: 1rem;
}

.voice-speed-control label {
    margin: 0;
    font-size: 0.875rem;
    color: #6c757d;
}

.voice-speed-control input[type="range"] {
    width: 80px;
}

.voice-speed-control span {
    font-size: 0.875rem;
    font-weight: 500;
    color: #495057;
    min-width: 30px;
}

.main-controls {
    display: flex;
    gap: 0.5rem;
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
var speechSynthesis = window.speechSynthesis;
var currentUtterance = null;
var isVoiceReading = false;
var isVoicePaused = false;
var currentVoiceSpeed = 1;

// Variables para PDF.js
var pdfDoc = null;
var pdfTextContent = '';
var isPdfLoaded = false;
var pdfjsLibLoaded = false;

// Función para cargar PDF.js dinámicamente con múltiples CDNs
function loadPDFJSLibrary() {
    return new Promise(function(resolve, reject) {
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLibLoaded = true;
            resolve();
            return;
        }
        
        var cdnUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js'
        ];
        
        var workerUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js'
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
                pdfjsLibLoaded = true;
                pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrls[currentIndex];
                resolve();
            };
            
            script.onerror = function() {
                currentIndex++;
                tryLoadScript();
            };
            
            document.head.appendChild(script);
        }
        
        tryLoadScript();
    });
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
    
    loadPDFJSLibrary().then(function() {
        loadPDFForTextExtraction(secureUrl);
    }).catch(function(error) {
        pdfTextContent = 'No se pudo cargar la librería PDF.js. La funcionalidad de voz no está disponible.';
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
                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (!iframeDoc || iframeDoc.body === null || iframeDoc.body.innerHTML.trim() === '') {
                    mostrarErrorPDF();
                }
            } catch (e) {
                // Error de CORS esperado
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
    stopVoiceReading();
    
    var modal = document.getElementById('modalPDF');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    var iframe = document.getElementById('pdfViewer');
    iframe.src = '';
    iframe.onload = null;
    iframe.onerror = null;
    
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfError').style.display = 'none';
    document.getElementById('pdfLoading').style.display = 'none';
    
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

function editarRecurso(id) {
    window.location.href = '<?= base_url('recursos/editar') ?>/' + id;
}

function eliminarRecurso(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar petición AJAX para eliminar
            fetch('<?= base_url('recursos/eliminar') ?>/' + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message || 'El recurso digital ha sido eliminado correctamente.',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(function() {
                        // Recargar la página para actualizar la lista
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al eliminar',
                        text: data.message || 'No se pudo eliminar el recurso digital.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar',
                    text: 'No se pudo eliminar el recurso digital. Por favor, inténtalo de nuevo.'
                });
            });
        }
    });
}

// ===== FUNCIONES DE VOZ =====

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
    
    currentUtterance.onstart = function() {
        isVoiceReading = true;
        isVoicePaused = false;
        updateVoiceButtons();
    };
    
    currentUtterance.onend = function() {
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
    };
    
    currentUtterance.onerror = function(event) {
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
        alert('Error al reproducir la voz.');
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
    
    const voices = speechSynthesis.getVoices();
    let selectedVoice = null;
    
    selectedVoice = voices.find(voice => 
        voice.name.includes('Sabina') || 
        voice.name.includes('Helena') ||
        voice.name.includes('Laura') ||
        voice.name.includes('Monica') ||
        voice.name.includes('Paulina') ||
        voice.name.includes('Teresa') ||
        (voice.name.toLowerCase().includes('google') && voice.lang.startsWith('es'))
    );
    
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => 
            voice.lang.startsWith('es') && 
            (voice.name.toLowerCase().includes('female') || 
             voice.name.toLowerCase().includes('mujer'))
        );
    }
    
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => voice.lang.startsWith('es'));
    }
    
    if (selectedVoice) {
        currentUtterance.voice = selectedVoice;
    }
}

function updateVoiceButtons() {
    var playBtn = document.getElementById('btnVoicePlay');
    var pauseBtn = document.getElementById('btnVoicePause');
    var stopBtn = document.getElementById('btnVoiceStop');
    var voiceText = document.getElementById('voiceText');
    
    if (isVoiceReading) {
        playBtn.style.display = 'none';
        pauseBtn.style.display = 'inline-block';
        stopBtn.style.display = 'inline-block';
        voiceText.textContent = isVoicePaused ? '▶️ Continuar' : '⏸️ Pausar';
    } else {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
        stopBtn.style.display = 'none';
        voiceText.textContent = '🎤 Leer Cuento';
    }
}

// ===== FUNCIONES DE PDF.js =====

function loadPDFForTextExtraction(url) {
    if (!pdfjsLibLoaded || typeof pdfjsLib === 'undefined') {
        pdfTextContent = 'PDF.js no está disponible.';
        isPdfLoaded = true;
        return;
    }
    
    pdfDoc = null;
    pdfTextContent = '';
    isPdfLoaded = false;
    
    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        pdfDoc = pdf;
        extractTextFromAllPages();
    }).catch(function(error) {
        if (error.name === 'UnknownErrorException' || error.message.includes('CORS') || error.message.includes('fetch')) {
            pdfTextContent = 'No se pudo acceder al PDF debido a restricciones de CORS.';
        } else {
            pdfTextContent = 'No se pudo extraer texto del PDF.';
        }
        isPdfLoaded = true;
    });
}

function extractTextFromAllPages() {
    if (!pdfDoc) return;
    
    var totalPages = pdfDoc.numPages;
    var allText = '';
    var pagesProcessed = 0;
    
    for (var pageNum = 1; pageNum <= totalPages; pageNum++) {
        pdfDoc.getPage(pageNum).then(function(page) {
            return page.getTextContent();
        }).then(function(textContent) {
            var pageText = '';
            for (var i = 0; i < textContent.items.length; i++) {
                pageText += textContent.items[i].str + ' ';
            }
            
            allText += pageText + '\n\n';
            pagesProcessed++;
            
            if (pagesProcessed === totalPages) {
                pdfTextContent = allText.replace(/\s+/g, ' ').trim();
                isPdfLoaded = true;
                
                if (pdfTextContent.length === 0) {
                    pdfTextContent = 'Este PDF no contiene texto extraíble.';
                }
            }
        }).catch(function(error) {
            pagesProcessed++;
            if (pagesProcessed === totalPages) {
                pdfTextContent = 'Error extrayendo texto del PDF.';
                isPdfLoaded = true;
            }
        });
    }
}

function extractTextFromPDF() {
    if (isPdfLoaded && pdfTextContent) {
        return pdfTextContent;
    } else if (isPdfLoaded && !pdfTextContent) {
        return 'Este PDF no contiene texto extraíble.';
    } else {
        return 'Cargando texto del PDF... Por favor espera un momento.';
    }
}

</script>

<?= $footer ?>
