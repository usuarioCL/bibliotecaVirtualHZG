/**
 * Módulo para lectura de voz de PDFs
 * Gestiona la síntesis de voz con configuración amigable para niños
 */
class VoiceReader {
    constructor(pdfViewer) {
        this.pdfViewer = pdfViewer;
        this.synthesis = window.speechSynthesis;
        this.utterance = null;
        this.isReading = false;
        this.isPaused = false;
        this.speed = 0.8;
        
        this.initControls();
        this.bindEvents();
        this.initVoices();
    }
    
    /**
     * Inicializa las referencias a los controles
     */
    initControls() {
        this.btnPlay = document.getElementById('btnVoicePlay');
        this.btnPause = document.getElementById('btnVoicePause');
        this.btnStop = document.getElementById('btnVoiceStop');
        this.speedSlider = document.getElementById('voiceSpeed');
        this.speedValue = document.getElementById('speedValue');
        this.voiceText = document.getElementById('voiceText');
    }
    
    /**
     * Vincula eventos de los controles
     */
    bindEvents() {
        this.speedSlider?.addEventListener('change', (e) => {
            this.changeSpeed(parseFloat(e.target.value));
        });
        
        this.speedSlider?.addEventListener('input', (e) => {
            this.speedValue.textContent = e.target.value + 'x';
        });
    }
    
    /**
     * Inicializa las voces disponibles
     */
    initVoices() {
        if (VoiceUtils.isSupported()) {
            VoiceUtils.initVoices(() => {
                console.log('Voces disponibles:', this.synthesis.getVoices().length);
            });
        } else {
            console.warn('Síntesis de voz no soportada en este navegador');
        }
    }
    
    /**
     * Alterna entre reproducir y pausar
     */
    toggle() {
        if (this.isReading) {
            this.pause();
        } else {
            this.start();
        }
    }
    
    /**
     * Inicia la lectura de voz
     */
    start() {
        // Detener cualquier lectura anterior
        this.stop();
        
        // Verificar soporte de voz
        if (!VoiceUtils.isSupported()) {
            alert('Tu navegador no soporta síntesis de voz.');
            return;
        }
        
        // Verificar si el PDF está cargado
        if (!this.pdfViewer.isLoaded) {
            alert('El PDF aún se está cargando. Por favor espera un momento e intenta nuevamente.');
            return;
        }
        
        // Obtener el texto del PDF
        const text = this.pdfViewer.getText();
        
        if (!text || text.length < 10) {
            alert('No se pudo extraer texto del PDF para la lectura de voz.');
            return;
        }
        
        // Limpiar el texto para mejor lectura
        const cleanText = VoiceUtils.cleanTextForSpeech(text);
        
        // Crear utterance con configuración amigable para niños
        this.utterance = new SpeechSynthesisUtterance(cleanText);
        
        // Configuración optimizada para niños
        this.utterance.rate = VoiceUtils.getChildFriendlySpeed(this.speed);
        this.utterance.pitch = 1.3;  // Pitch más alto, más amigable
        this.utterance.volume = 0.9; // Volumen alto para mejor audición
        this.utterance.lang = 'es-ES';
        
        // Seleccionar voz amigable para niños
        this.selectChildFriendlyVoice();
        
        // Eventos de la síntesis
        this.utterance.onstart = () => {
            this.isReading = true;
            this.isPaused = false;
            this.updateButtons();
            console.log('Iniciando lectura de voz del PDF...');
        };
        
        this.utterance.onend = () => {
            this.isReading = false;
            this.isPaused = false;
            this.updateButtons();
            console.log('Lectura de voz completada.');
        };
        
        this.utterance.onerror = (event) => {
            console.error('Error en síntesis de voz:', event.error);
            this.isReading = false;
            this.isPaused = false;
            this.updateButtons();
            
            let errorMsg = 'Error al reproducir la voz.';
            if (event.error === 'interrupted') {
                errorMsg = 'La lectura fue interrumpida.';
            } else if (event.error === 'network') {
                errorMsg = 'Error de red al intentar reproducir la voz.';
            }
            
            alert(errorMsg);
        };
        
        // Iniciar lectura
        this.synthesis.speak(this.utterance);
    }
    
    /**
     * Pausa o reanuda la lectura
     */
    pause() {
        if (this.isReading && !this.isPaused) {
            this.synthesis.pause();
            this.isPaused = true;
            this.updateButtons();
        } else if (this.isPaused) {
            this.synthesis.resume();
            this.isPaused = false;
            this.updateButtons();
        }
    }
    
    /**
     * Detiene la lectura
     */
    stop() {
        this.synthesis.cancel();
        this.isReading = false;
        this.isPaused = false;
        this.utterance = null;
        this.updateButtons();
    }
    
    /**
     * Cambia la velocidad de lectura
     * @param {number} speed Nueva velocidad
     */
    changeSpeed(speed) {
        this.speed = parseFloat(speed);
        this.speedValue.textContent = speed + 'x';
        
        // Si hay una lectura activa, necesitamos reiniciarla con la nueva velocidad
        // Por ahora solo actualizamos el valor, el usuario debe reiniciar manualmente
        if (this.utterance) {
            this.utterance.rate = VoiceUtils.getChildFriendlySpeed(this.speed);
        }
    }
    
    /**
     * Selecciona una voz amigable para niños
     */
    selectChildFriendlyVoice() {
        if (!this.utterance) return;
        
        const voice = VoiceUtils.getChildFriendlyVoice();
        
        if (voice) {
            this.utterance.voice = voice;
            console.log('Voz seleccionada para niños:', voice.name);
        } else {
            console.warn('No se encontró una voz amigable para niños, usando voz por defecto');
        }
    }
    
    /**
     * Actualiza el estado visual de los botones
     */
    updateButtons() {
        if (!this.btnPlay || !this.btnPause || !this.btnStop) return;
        
        if (this.isReading) {
            this.btnPlay.style.display = 'none';
            this.btnPause.style.display = 'inline-block';
            this.btnStop.style.display = 'inline-block';
            
            if (this.isPaused) {
                this.btnPause.innerHTML = '<i class="fas fa-play"></i> Continuar';
                if (this.voiceText) this.voiceText.textContent = 'Continuar';
            } else {
                this.btnPause.innerHTML = '<i class="fas fa-pause"></i> Pausar';
                if (this.voiceText) this.voiceText.textContent = 'Pausar';
            }
        } else {
            this.btnPlay.style.display = 'inline-block';
            this.btnPause.style.display = 'none';
            this.btnStop.style.display = 'none';
            if (this.voiceText) this.voiceText.textContent = 'Leer';
        }
    }
}

// Exportar como global
window.VoiceReader = VoiceReader;
