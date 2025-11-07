/**
 * Configuración y constantes para el módulo de historial de préstamos
 * @module HistorialConfig
 */

const HistorialConfig = {
    // URLs de la aplicación
    urls: {
        base: window.baseURL || '',
        prestamos: {
            obtenerDetalle: '/prestamos/obtenerDetalleDevolucion',
            detalleSolicitud: '/prestamos/detalleSolicitud',
            eliminarHistorial: '/prestamos/eliminarHistorial',
            eliminarTodoHistorial: '/prestamos/eliminarTodoHistorial',
            index: '/prestamos'
        },
        sanciones: {
            crear: '/sanciones/crear'
        },
        admin: '/admin',
        historialPrestamos: '/historial-prestamos'
    },

    // Constantes de multas y sanciones
    multas: {
        MONTO_POR_HORA: 2500,
        MONTO_POR_DIA: 5000,
        HORAS_PARA_DIA: 24,
        DIAS_SANCION_LEVE: 1,
        DIAS_SANCION_MODERADA: 3
    },

    // Tipos de sanción
    tiposSancion: {
        LEVE: 'Leve',
        MODERADA: 'Moderada',
        GRAVE: 'Grave'
    },

    // Estados de préstamo
    estados: {
        DEVUELTO: 'Devuelto',
        DEVUELTO_RETRASO: 'Devuelto con retraso',
        RECHAZADO: 'Rechazado',
        CANCELADO: 'Cancelado',
        ACTIVO: 'Activo',
        PENDIENTE: 'Pendiente'
    },

    // Configuración de alertas
    alertas: {
        tiempoAutoCierre: 1500,
        tiempoMensajeExito: 2000,
        anchoModalGrande: '600px',
        anchoModalExtraGrande: '500px'
    },

    // Textos de confirmación
    textos: {
        CONFIRMAR_ELIMINACION_TOTAL: 'ELIMINAR HISTORIAL',
        mensajeEliminacionTotal: 'Esta es una acción EXTREMADAMENTE PELIGROSA'
    },

    // Configuración de formateo
    formateo: {
        locale: 'es-ES',
        localeAlt: 'es-CO',
        formatoFecha: {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        },
        formatoFechaCorta: {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        },
        formatoHora: {
            hour: '2-digit',
            minute: '2-digit'
        }
    },

    // Configuración de debug
    debug: {
        habilitado: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1',
        mostrarConsoleLogs: true
    },

    /**
     * Obtiene una URL completa concatenando base + path
     * @param {string} path - Ruta relativa
     * @returns {string} URL completa
     */
    getUrl(path) {
        return this.urls.base + path;
    },

    /**
     * Verifica si estamos en modo desarrollo
     * @returns {boolean}
     */
    isDebugMode() {
        return this.debug.habilitado;
    },

    /**
     * Log condicional según modo debug
     * @param {...any} args - Argumentos para console.log
     */
    log(...args) {
        if (this.debug.habilitado && this.debug.mostrarConsoleLogs) {
            console.log(...args);
        }
    },

    /**
     * Calcula el tipo de sanción basado en horas de retraso
     * @param {number} horasRetraso - Horas de retraso
     * @returns {string} Tipo de sanción
     */
    calcularTipoSancion(horasRetraso) {
        if (horasRetraso <= this.multas.HORAS_PARA_DIA) {
            return this.tiposSancion.LEVE;
        }
        const dias = Math.floor(horasRetraso / this.multas.HORAS_PARA_DIA);
        return dias <= this.multas.DIAS_SANCION_MODERADA 
            ? this.tiposSancion.MODERADA 
            : this.tiposSancion.GRAVE;
    },

    /**
     * Calcula el monto de la sanción
     * @param {number} horasRetraso - Horas de retraso
     * @returns {number} Monto de la sanción
     */
    calcularMontoSancion(horasRetraso) {
        if (horasRetraso <= this.multas.HORAS_PARA_DIA) {
            return horasRetraso * this.multas.MONTO_POR_HORA;
        }
        const dias = Math.floor(horasRetraso / this.multas.HORAS_PARA_DIA);
        return dias * this.multas.MONTO_POR_DIA;
    }
};

// Hacer disponible globalmente
window.HistorialConfig = HistorialConfig;
