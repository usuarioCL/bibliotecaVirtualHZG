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
            <!-- Controles de voz -->
            <div class="voice-controls">
                <button id="btnVoicePlay" type="button" class="btn btn-success btn-sm" onclick="toggleVoiceReading()" aria-label="Reproducir voz">
                    <i class="ti ti-speakerphone" aria-hidden="true"></i> <span id="voiceText">Leer PDF</span>
                </button>
                <button id="btnVoicePause" type="button" class="btn btn-warning btn-sm" onclick="pauseVoiceReading()" style="display: none;" aria-label="Pausar voz">
                    <i class="ti ti-player-pause" aria-hidden="true"></i> Pausar
                </button>
                <button id="btnVoiceStop" type="button" class="btn btn-danger btn-sm" onclick="stopVoiceReading()" style="display: none;" aria-label="Detener voz">
                    <i class="ti ti-player-stop" aria-hidden="true"></i> Detener
                </button>
                <div class="voice-speed-control">
                    <label for="voiceSpeed" class="form-label">Velocidad:</label>
                    <input type="range" id="voiceSpeed" class="form-range" min="0.5" max="1.5" step="0.1" value="0.8" onchange="changeVoiceSpeed(this.value)">
                    <span id="speedValue">0.8x</span>
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
    gap: 1rem;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

.voice-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
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
                pdfjsLibLoaded = true;
                // Configurar PDF.js worker con el mismo índice
                pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrls[currentIndex];
                console.log('PDF.js cargado desde:', cdnUrls[currentIndex]);
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
    
    // Cargar PDF.js dinámicamente y luego extraer texto
    loadPDFJSLibrary().then(function() {
        console.log('PDF.js cargado exitosamente, iniciando extracción de texto...');
        loadPDFForTextExtraction(secureUrl);
    }).catch(function(error) {
        console.error('Error cargando PDF.js:', error);
        // Usar texto de ejemplo si falla la carga de PDF.js
        pdfTextContent = 'No se pudo cargar la librería PDF.js desde ningún CDN. Esto puede deberse a restricciones de red o CORS. La funcionalidad de voz está disponible con texto de ejemplo.';
        isPdfLoaded = true;
        console.log('Usando texto de ejemplo para la funcionalidad de voz');
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

// Detener la voz cuando se cierra el modal
function cerrarModalPDF() {
    stopVoiceReading();
    
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
</script>