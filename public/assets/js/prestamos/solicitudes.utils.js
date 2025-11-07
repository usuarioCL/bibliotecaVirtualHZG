/**
 * Módulo de Utilidades para Solicitudes de Préstamos
 * Contiene funciones helper para formateo y manipulación de datos
 */

var SolicitudesUtils = SolicitudesUtils || {
    /**
     * Formatea una fecha en formato dd/mm/yyyy
     * @param {string|Date} fecha - Fecha a formatear
     * @returns {string} Fecha formateada
     */
    formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        
        const date = new Date(fecha);
        if (isNaN(date.getTime())) return 'Fecha inválida';
        
        const dia = String(date.getDate()).padStart(2, '0');
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const anio = date.getFullYear();
        
        return `${dia}/${mes}/${anio}`;
    },

    /**
     * Formatea una fecha con hora en formato dd/mm/yyyy HH:mm
     * @param {string|Date} fecha - Fecha a formatear
     * @returns {string} Fecha y hora formateadas
     */
    formatearFechaHora(fecha) {
        if (!fecha) return 'N/A';
        
        const date = new Date(fecha);
        if (isNaN(date.getTime())) return 'Fecha inválida';
        
        const dia = String(date.getDate()).padStart(2, '0');
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const anio = date.getFullYear();
        const horas = String(date.getHours()).padStart(2, '0');
        const minutos = String(date.getMinutes()).padStart(2, '0');
        
        return `${dia}/${mes}/${anio} ${horas}:${minutos}`;
    },

    /**
     * Calcula los días entre dos fechas
     * @param {string|Date} fechaInicio - Fecha inicial
     * @param {string|Date} fechaFin - Fecha final
     * @returns {number} Número de días
     */
    calcularDias(fechaInicio, fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        if (isNaN(inicio.getTime()) || isNaN(fin.getTime())) return 0;
        
        const diferencia = fin - inicio;
        return Math.ceil(diferencia / (1000 * 60 * 60 * 24));
    },

    /**
     * Obtiene la clase CSS para el badge de prioridad
     * @param {string} prioridad - Nivel de prioridad (Alta, Media, Baja)
     * @returns {object} Objeto con clase e icono
     */
    getPrioridadConfig(prioridad) {
        const configs = {
            'Alta': {
                clase: 'bg-danger',
                icono: 'ti-alert-circle'
            },
            'Media': {
                clase: 'bg-warning',
                icono: 'ti-alert-triangle'
            },
            'Baja': {
                clase: 'bg-info',
                icono: 'ti-info-circle'
            }
        };
        
        return configs[prioridad] || configs['Baja'];
    },

    /**
     * Valida si un ID es válido
     * @param {*} id - ID a validar
     * @returns {boolean} True si es válido
     */
    esIdValido(id) {
        const idNum = parseInt(id);
        return !isNaN(idNum) && idNum > 0;
    },

    /**
     * Filtra solicitudes disponibles
     * @param {Array} solicitudes - Array de solicitudes
     * @returns {Array} Solicitudes disponibles
     */
    filtrarDisponibles(solicitudes) {
        if (!Array.isArray(solicitudes)) {
            return [];
        }
        
        return solicitudes.filter(s => {
            return s.disponible == 1 || 
                   s.disponible === true || 
                   s.disponible === 'true' ||
                   s.disponible === '1';
        });
    },

    /**
     * Extrae IDs válidos de un array de solicitudes
     * @param {Array} solicitudes - Array de solicitudes
     * @returns {Array} Array de IDs válidos
     */
    extraerIds(solicitudes) {
        if (!Array.isArray(solicitudes)) return [];
        
        return solicitudes
            .map(s => parseInt(s.id))
            .filter(id => !isNaN(id) && id > 0);
    },

    /**
     * Genera HTML para badge de disponibilidad
     * @param {boolean} disponible - Estado de disponibilidad
     * @returns {string} HTML del badge
     */
    generarBadgeDisponibilidad(disponible) {
        if (disponible) {
            return '<span class="badge bg-success"><i class="ti ti-check-circle me-1"></i>Disponible</span>';
        } else {
            return '<span class="badge bg-secondary"><i class="ti ti-x-circle me-1"></i>No Disponible</span>';
        }
    },

    /**
     * Genera lista de autores formateada
     * @param {Array} autores - Array de autores
     * @returns {string} Texto con autores
     */
    generarListaAutores(autores) {
        if (!Array.isArray(autores) || autores.length === 0) {
            return 'No especificado';
        }
        
        return autores.map(autor => {
            let autorTexto = autor.nombre_completo.trim();
            if (autor.nacionalidad) {
                autorTexto += ` (${autor.nacionalidad})`;
            }
            return autorTexto;
        }).join(', ');
    },

    /**
     * Inicializa tooltips de Bootstrap
     */
    inicializarTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        
        return tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },

    /**
     * Destruye tooltips existentes
     */
    destruirTooltips() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(element => {
            const tooltip = bootstrap.Tooltip.getInstance(element);
            if (tooltip) {
                tooltip.dispose();
            }
        });
    },

    /**
     * Logger mejorado con timestamps
     * @param {string} mensaje - Mensaje a loggear
     * @param {*} data - Datos adicionales
     */
    log(mensaje, data = null) {
        const timestamp = new Date().toISOString();
        console.log(`[${timestamp}] ${mensaje}`, data || '');
    },

    /**
     * Logger de errores
     * @param {string} mensaje - Mensaje de error
     * @param {*} error - Objeto de error
     */
    logError(mensaje, error = null) {
        const timestamp = new Date().toISOString();
        console.error(`[${timestamp}] ERROR: ${mensaje}`, error || '');
    }
};

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SolicitudesUtils;
}
