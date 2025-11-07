<!-- Modal personalizado para ver PDF -->
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
