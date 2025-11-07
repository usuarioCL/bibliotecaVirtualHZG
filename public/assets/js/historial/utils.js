/**
 * Utilidades y funciones helper para el módulo de historial
 * @module HistorialUtils
 */

const HistorialUtils = {
    /**
     * Escapa caracteres HTML para prevenir XSS
     * @param {string} texto - Texto a escapar
     * @returns {string} Texto escapado
     */
    escaparHTML(texto) {
        if (!texto) return '';
        return String(texto)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },

    /**
     * Valida que un valor no sea nulo, indefinido o vacío
     * @param {any} valor - Valor a validar
     * @returns {boolean}
     */
    esValorValido(valor) {
        return valor !== null && valor !== undefined && valor !== '';
    },

    /**
     * Valida que un objeto sea válido y no vacío
     * @param {any} obj - Objeto a validar
     * @returns {boolean}
     */
    esObjetoValido(obj) {
        return obj !== null && obj !== undefined && typeof obj === 'object';
    },

    /**
     * Valida un ID de préstamo
     * @param {any} id - ID a validar
     * @returns {boolean}
     */
    esIdValido(id) {
        return id && id !== undefined && id !== null && !isNaN(Number(id));
    },

    /**
     * Formatea una fecha al formato español
     * @param {Date|string} fecha - Fecha a formatear
     * @param {Object} opciones - Opciones de formato
     * @returns {string} Fecha formateada o mensaje de error
     */
    formatearFecha(fecha, opciones = null) {
        if (!fecha) return 'Fecha no disponible';
        
        try {
            const fechaObj = typeof fecha === 'string' ? new Date(fecha) : fecha;
            if (isNaN(fechaObj.getTime())) {
                return 'Fecha inválida';
            }
            
            const opcionesFormato = opciones || HistorialConfig.formateo.formatoFecha;
            return fechaObj.toLocaleDateString(HistorialConfig.formateo.locale, opcionesFormato);
        } catch (e) {
            HistorialConfig.log('Error al formatear fecha:', e);
            return 'Error en fecha';
        }
    },

    /**
     * Formatea una hora al formato español
     * @param {Date|string} fecha - Fecha/hora a formatear
     * @returns {string} Hora formateada
     */
    formatearHora(fecha) {
        if (!fecha) return '00:00';
        
        try {
            const fechaObj = typeof fecha === 'string' ? new Date(fecha) : fecha;
            if (isNaN(fechaObj.getTime())) {
                return '00:00';
            }
            
            return fechaObj.toLocaleTimeString(
                HistorialConfig.formateo.locale, 
                HistorialConfig.formateo.formatoHora
            );
        } catch (e) {
            HistorialConfig.log('Error al formatear hora:', e);
            return '00:00';
        }
    },

    /**
     * Obtiene las iniciales de un nombre completo
     * @param {string} nombreCompleto - Nombre completo del usuario
     * @returns {string} Iniciales (máximo 2 letras)
     */
    obtenerIniciales(nombreCompleto) {
        if (!nombreCompleto) return 'U';
        
        const partes = nombreCompleto.trim().split(' ');
        const primera = partes[0] ? partes[0].charAt(0).toUpperCase() : 'U';
        const segunda = partes[1] ? partes[1].charAt(0).toUpperCase() : '';
        
        return primera + segunda;
    },

    /**
     * Calcula información de retraso basado en horas totales
     * @param {number} horasRetrasoTotal - Total de horas de retraso
     * @returns {Object} Información del retraso
     */
    calcularRetraso(horasRetrasoTotal) {
        const horas = parseInt(horasRetrasoTotal) || 0;
        
        if (horas === 0) {
            return {
                mostrarHoras: false,
                horasRetraso: 0,
                diasRetraso: 0,
                texto: 'A Tiempo',
                textoDetalle: 'Estado'
            };
        }
        
        if (horas > 0 && horas < 24) {
            return {
                mostrarHoras: true,
                horasRetraso: horas,
                diasRetraso: 0,
                texto: `+${horas}h`,
                textoDetalle: 'Horas de Retraso'
            };
        }
        
        const dias = Math.floor(horas / 24);
        return {
            mostrarHoras: false,
            horasRetraso: 0,
            diasRetraso: dias,
            texto: dias > 0 ? `+${dias}d` : `${dias}d`,
            textoDetalle: dias > 0 ? 'Días de Retraso' : 'Días de Anticipación'
        };
    },

    /**
     * Determina el estado y estilo del préstamo
     * @param {number} diasRetraso - Días de retraso
     * @param {number} horasRetraso - Horas de retraso
     * @returns {Object} Información del estado
     */
    determinarEstadoPrestamo(diasRetraso, horasRetraso) {
        if (diasRetraso > 0 || horasRetraso > 0) {
            return {
                badge: 'Con Retraso',
                class: 'bg-danger',
                icon: 'ti-alert-circle'
            };
        }
        
        if (diasRetraso === 0 && horasRetraso === 0) {
            return {
                badge: 'Devuelto a Tiempo',
                class: 'bg-success',
                icon: 'ti-check-circle'
            };
        }
        
        return {
            badge: 'Devuelto Anticipadamente',
            class: 'bg-info',
            icon: 'ti-clock'
        };
    },

    /**
     * Limpia y valida datos de entrada
     * @param {any} valor - Valor a limpiar
     * @param {string} valorPorDefecto - Valor por defecto si no es válido
     * @returns {string} Valor limpio
     */
    limpiarDato(valor, valorPorDefecto = 'N/A') {
        if (!this.esValorValido(valor)) {
            return valorPorDefecto;
        }
        return String(valor).trim();
    },

    /**
     * Formatea un número como moneda
     * @param {number} monto - Monto a formatear
     * @returns {string} Monto formateado
     */
    formatearMoneda(monto) {
        if (!monto || isNaN(monto)) return '$0';
        return `$${parseInt(monto).toLocaleString('es-CO')}`;
    },

    /**
     * Genera un código de préstamo formateado
     * @param {number} id - ID del préstamo
     * @param {string} prefijo - Prefijo del código
     * @returns {string} Código formateado
     */
    generarCodigoPrestamo(id, prefijo = 'PREST-') {
        if (!this.esIdValido(id)) return 'N/A';
        return prefijo + String(id).padStart(6, '0');
    },

    /**
     * Crea un objeto FormData a partir de un objeto
     * @param {Object} datos - Datos a convertir
     * @returns {FormData} FormData creado
     */
    crearFormData(datos) {
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });
        return formData;
    },

    /**
     * Crea URLSearchParams a partir de un objeto
     * @param {Object} datos - Datos a convertir
     * @returns {URLSearchParams} URLSearchParams creado
     */
    crearURLParams(datos) {
        return new URLSearchParams(datos);
    },

    /**
     * Verifica si el parámetro debug está en la URL
     * @returns {boolean}
     */
    esModoDebug() {
        return window.location.search.includes('debug') || HistorialConfig.isDebugMode();
    },

    /**
     * Calcula la descripción de una sanción
     * @param {number} horasRetraso - Horas de retraso
     * @returns {string} Descripción de la sanción
     */
    generarDescripcionSancion(horasRetraso) {
        if (horasRetraso <= 24) {
            const plural = horasRetraso !== 1 ? 's' : '';
            return `Retraso de ${horasRetraso} hora${plural} en devolución`;
        }
        
        const dias = Math.floor(horasRetraso / 24);
        const plural = dias !== 1 ? 's' : '';
        return `Retraso de ${dias} día${plural} en devolución`;
    },

    /**
     * Genera texto de retraso legible
     * @param {number} horasRetraso - Horas de retraso
     * @returns {string} Texto del retraso
     */
    textoRetraso(horasRetraso) {
        if (horasRetraso < 24) {
            return `${horasRetraso} horas`;
        }
        return `${Math.floor(horasRetraso / 24)} días`;
    },

    /**
     * Valida una respuesta de fetch
     * @param {Response} response - Respuesta de fetch
     * @returns {Promise<Response>} Promesa con la respuesta validada
     */
    validarRespuesta(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }
        return response;
    },

    /**
     * Valida que la respuesta sea JSON
     * @param {Response} response - Respuesta de fetch
     * @returns {Promise<any>} Promesa con el JSON parseado o error
     */
    async validarYParsearJSON(response) {
        const contentType = response.headers.get('content-type');
        
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            HistorialConfig.log('Respuesta no es JSON:', text);
            throw new Error('La respuesta del servidor no es JSON válido');
        }
        
        return response.json();
    },

    /**
     * Determina el tipo de registro (préstamo o solicitud)
     * @param {string} estadoFinal - Estado final del registro
     * @returns {string} Tipo de registro
     */
    determinarTipoRegistro(estadoFinal) {
        return estadoFinal === 'Rechazado' ? 'solicitud' : 'prestamo';
    },

    /**
     * Genera un mensaje de error amigable
     * @param {Error} error - Error capturado
     * @returns {string} Mensaje de error amigable
     */
    generarMensajeError(error) {
        if (error.message.includes('HTTP error')) {
            return `Error del servidor: ${error.message}`;
        }
        
        if (error.message.includes('JSON')) {
            return 'Error en el formato de respuesta del servidor';
        }
        
        if (error.name === 'TypeError') {
            return 'Error de red o servidor no disponible';
        }
        
        return 'Ha ocurrido un error de conexión';
    },

    /**
     * Debounce para funciones
     * @param {Function} func - Función a ejecutar
     * @param {number} wait - Tiempo de espera en ms
     * @returns {Function} Función con debounce
     */
    debounce(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Detecta si estamos en un contenedor de administración
     * @returns {boolean}
     */
    esPanelAdministracion() {
        return document.getElementById('contenedor-principal') !== null;
    }
};

// Hacer disponible globalmente
window.HistorialUtils = HistorialUtils;
