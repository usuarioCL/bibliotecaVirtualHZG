/**
 * Utilidades para el manejo de voz
 */
class VoiceUtils {
    /**
     * Obtiene una lista de voces en español disponibles
     * @returns {Array} Lista de voces en español
     */
    static getSpanishVoices() {
        const voices = window.speechSynthesis.getVoices();
        return voices.filter(voice => voice.lang.startsWith('es'));
    }
    
    /**
     * Obtiene una voz amigable para niños
     * @returns {SpeechSynthesisVoice|null}
     */
    static getChildFriendlyVoice() {
        const voices = window.speechSynthesis.getVoices();
        
        // Voces preferidas para niños (más amigables)
        const preferredNames = [
            'Sabina',    // Microsoft Sabina Desktop - Spanish (Mexico)
            'Helena',    // Microsoft Helena Desktop - Spanish (Spain)
            'Laura',     // Microsoft Laura Desktop - Spanish (Spain)
            'Monica',    // Microsoft Monica Desktop - Spanish (Spain)
            'Paulina',   // Microsoft Paulina Desktop - Spanish (Mexico)
            'Teresa'     // Microsoft Teresa Desktop - Spanish (Spain)
        ];
        
        // Buscar una voz amigable para niños
        let selectedVoice = null;
        
        // Primero intentar con las voces preferidas
        for (const preferredName of preferredNames) {
            selectedVoice = voices.find(voice => 
                voice.name.includes(preferredName) ||
                (voice.name.toLowerCase().includes('google') && voice.lang.startsWith('es'))
            );
            if (selectedVoice) break;
        }
        
        // Si no se encuentra una voz preferida, buscar cualquier voz femenina en español
        if (!selectedVoice) {
            selectedVoice = voices.find(voice => 
                voice.lang.startsWith('es') && 
                (voice.name.toLowerCase().includes('female') || 
                 voice.name.toLowerCase().includes('femenina'))
            );
        }
        
        // Si aún no se encuentra, usar cualquier voz en español
        if (!selectedVoice) {
            selectedVoice = voices.find(voice => voice.lang.startsWith('es'));
        }
        
        return selectedVoice;
    }
    
    /**
     * Limpia y prepara texto para lectura de voz
     * @param {string} text Texto a limpiar
     * @returns {string} Texto limpio
     */
    static cleanTextForSpeech(text) {
        if (!text) return '';
        
        return text
            .trim()
            .replace(/\s+/g, ' ')  // Múltiples espacios a uno
            .replace(/\n+/g, '. ') // Saltos de línea a puntos
            .replace(/[•·]/g, '') // Remover viñetas
            .replace(/\d{1,2}\/\d{1,2}\/\d{2,4}/g, ''); // Remover fechas
    }
    
    /**
     * Inicializa las voces (necesario en algunos navegadores)
     * @param {Function} callback Función a ejecutar cuando las voces estén listas
     */
    static initVoices(callback) {
        const voices = window.speechSynthesis.getVoices();
        
        if (voices.length > 0) {
            callback();
        } else {
            window.speechSynthesis.onvoiceschanged = () => {
                callback();
            };
        }
    }
    
    /**
     * Verifica si el navegador soporta síntesis de voz
     * @returns {boolean}
     */
    static isSupported() {
        return 'speechSynthesis' in window;
    }
    
    /**
     * Calcula la velocidad óptima para niños basada en la velocidad seleccionada
     * @param {number} speed Velocidad base
     * @returns {number} Velocidad ajustada
     */
    static getChildFriendlySpeed(speed) {
        // Hacer la lectura más lenta para mejor comprensión
        return Math.max(0.6, speed * 0.8);
    }
}

// Exportar como global
window.VoiceUtils = VoiceUtils;
