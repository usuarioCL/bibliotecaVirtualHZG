<?php if (isset($header)): ?>
<?= $header ?>
<?php endif; ?>

<!-- Estilos específicos para recursos digitales -->
<link rel="stylesheet" href="<?= base_url('assets/css/recursos-digitales-styles.css') ?>">

<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Recursos Digitales</h4>
            <p class="text-muted mb-0">Lista de recursos digitales disponibles en la biblioteca</p>
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
        <?php
            $itemsPorPagina = max(1, $per_page ?? 8);
            $paginaActual = max(1, $pagina_actual ?? 1);
            $totalRegistros = $total_recursos ?? 0;
            $inicio = $totalRegistros > 0 ? ($paginaActual - 1) * $itemsPorPagina + 1 : 0;
            $fin = $totalRegistros > 0 ? min($totalRegistros, $paginaActual * $itemsPorPagina) : 0;
            $totalPaginas = (int) ceil($totalRegistros / $itemsPorPagina);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
            <div class="text-muted small">
                <?php if ($totalRegistros > 0): ?>
                    Mostrando <?= $inicio ?>-<?= $fin ?> de <?= $totalRegistros ?> recursos digitales
                <?php else: ?>
                    No hay registros para paginar
                <?php endif; ?>
            </div>
            <?php if ($totalPaginas > 1): ?>
                <?php
                    $request = service('request');
                    $basePath = service('uri')->getPath();
                    $baseUrl = base_url($basePath);
                    $queryParams = $request->getGet();
                    unset($queryParams['page']);
                    $buildUrl = static function (string $base, array $params): string {
                        return $params ? $base . '?' . http_build_query($params) : $base;
                    };
                ?>
                <nav aria-label="Paginación de recursos digitales" class="pagination-wrapper recursos-digitales-pagination-container">
                    <ul class="pagination recursos-digitales-pagination mb-0">
                        <?php
                            $prevDisabled = $paginaActual <= 1;
                            $prevParams = $queryParams;
                            if (!$prevDisabled) {
                                $prevParams['page'] = $paginaActual - 1;
                            }
                            $prevUrl = $prevDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $prevParams);
                        ?>
                        <li class="page-item <?= $prevDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $prevUrl ?>" data-page="<?= max(1, $paginaActual - 1) ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($page = 1; $page <= $totalPaginas; $page++): ?>
                            <?php
                                $pageParams = $queryParams;
                                $pageParams['page'] = $page;
                                $pageUrl = $buildUrl($baseUrl, $pageParams);
                            ?>
                            <li class="page-item <?= $page === $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $pageUrl ?>" data-page="<?= $page ?>" aria-current="<?= $page === $paginaActual ? 'page' : 'false' ?>">
                                    <?= $page ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php
                            $nextDisabled = $paginaActual >= $totalPaginas;
                            $nextParams = $queryParams;
                            if (!$nextDisabled) {
                                $nextParams['page'] = $paginaActual + 1;
                            }
                            $nextUrl = $nextDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $nextParams);
                        ?>
                        <li class="page-item <?= $nextDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $nextUrl ?>" data-page="<?= min($totalPaginas, $paginaActual + 1) ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
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
            <div id="pdfContainer">
                <div id="pdfLoading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando PDF...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando PDF...</p>
                </div>
                <iframe id="pdfViewer" src="" width="100%" height="100%" style="border: none;" title="Visor de PDF" allowfullscreen>
                </iframe>
                <div id="pdfError" style="display: none;">
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
                // Configurar PDF.js worker con el mismo índice
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
    
    // Cargar PDF.js dinámicamente y luego extraer texto
    loadPDFJSLibrary().then(function() {
        loadPDFForTextExtraction(secureUrl);
    }).catch(function(error) {
        pdfTextContent = 'No se pudo cargar la librería PDF.js desde ningún CDN.';
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
                // PDF cargado (CORS bloqueado)
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

// =====================================
// Paginación AJAX dentro del dashboard
// =====================================
(function(){
    if (window.__recursosDigitalesPaginationBound) {
        return;
    }
    window.__recursosDigitalesPaginationBound = true;

    document.addEventListener('click', function(event) {
        var target = event.target;

        // Navegar hasta el enlace dentro de la paginación
        while (target && target !== document && !(target instanceof HTMLAnchorElement)) {
            target = target.parentElement;
        }

        if (!target || !(target instanceof HTMLAnchorElement)) {
            return;
        }

        if (!target.closest('.pagination')) {
            return;
        }

        var contenedor = document.getElementById('contenedor-principal');
        if (!contenedor) {
            // No estamos dentro del dashboard, dejar comportamiento normal
            return;
        }

        event.preventDefault();
        var url = target.getAttribute('href');
        if (!url) {
            return;
        }

        contenedor.innerHTML = '<div class="text-center py-5">Cargando recursos...</div>';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Error al cargar la página');
            }
            return response.text();
        })
        .then(function(html) {
            contenedor.innerHTML = html;
        })
        .catch(function() {
            contenedor.innerHTML = '<div class="text-danger text-center py-5">No se pudo cargar la página solicitada.</div>';
        });
    });
})();
</script>

</div>

<?php if (isset($footer)): ?>
<?= $footer ?>
<?php endif; ?>