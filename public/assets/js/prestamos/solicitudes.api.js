/**
 * Módulo API para Solicitudes de Préstamos
 * Centraliza todas las llamadas AJAX al servidor
 */

const SolicitudesAPI = {
    /**
     * Configuración base para peticiones
     */
    config: {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        headersJSON: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    },

    /**
     * Maneja errores de respuesta HTTP
     * @param {Response} response - Respuesta HTTP
     * @returns {Response} Respuesta si es OK
     * @throws {Error} Si hay error
     */
    async manejarRespuesta(response) {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    },

    /**
     * Aprueba una solicitud individual
     * @param {number} solicitudId - ID de la solicitud
     * @returns {Promise} Promesa con respuesta
     */
    async aprobarSolicitud(solicitudId) {
        SolicitudesUtils.log('API: Aprobar solicitud', solicitudId);
        
        try {
            const response = await fetch(BASE_URL + 'prestamos/aprobar', {
                method: 'POST',
                headers: this.config.headers,
                body: 'idsolicitud=' + encodeURIComponent(solicitudId)
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al aprobar solicitud', error);
            throw error;
        }
    },

    /**
     * Rechaza una solicitud individual
     * @param {number} solicitudId - ID de la solicitud
     * @param {string} motivo - Motivo del rechazo
     * @returns {Promise} Promesa con respuesta
     */
    async rechazarSolicitud(solicitudId, motivo = '') {
        SolicitudesUtils.log('API: Rechazar solicitud', { solicitudId, motivo });
        
        try {
            const response = await fetch(BASE_URL + 'prestamos/rechazar', {
                method: 'POST',
                headers: this.config.headers,
                body: `idsolicitud=${encodeURIComponent(solicitudId)}&motivo=${encodeURIComponent(motivo)}`
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al rechazar solicitud', error);
            throw error;
        }
    },

    /**
     * Aprueba múltiples solicitudes
     * @param {Array} solicitudesIds - Array de IDs
     * @returns {Promise} Promesa con respuesta
     */
    async aprobarTodas(solicitudesIds) {
        SolicitudesUtils.log('API: Aprobar múltiples solicitudes', solicitudesIds);
        
        if (!Array.isArray(solicitudesIds) || solicitudesIds.length === 0) {
            throw new Error('No se proporcionaron solicitudes válidas');
        }
        
        try {
            const response = await fetch(BASE_URL + 'prestamos/aprobarTodas', {
                method: 'POST',
                headers: this.config.headers,
                body: 'solicitudes=' + encodeURIComponent(JSON.stringify(solicitudesIds))
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al aprobar todas las solicitudes', error);
            throw error;
        }
    },

    /**
     * Rechaza múltiples solicitudes
     * @param {Array} solicitudesIds - Array de IDs
     * @param {string} motivo - Motivo del rechazo
     * @returns {Promise} Promesa con respuesta
     */
    async rechazarTodas(solicitudesIds, motivo = '') {
        SolicitudesUtils.log('API: Rechazar múltiples solicitudes', { solicitudesIds, motivo });
        
        if (!Array.isArray(solicitudesIds) || solicitudesIds.length === 0) {
            throw new Error('No se proporcionaron solicitudes válidas');
        }
        
        try {
            const response = await fetch(BASE_URL + 'prestamos/rechazarTodas', {
                method: 'POST',
                headers: this.config.headers,
                body: `solicitudes=${encodeURIComponent(JSON.stringify(solicitudesIds))}&motivo=${encodeURIComponent(motivo)}`
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al rechazar todas las solicitudes', error);
            throw error;
        }
    },

    /**
     * Obtiene detalles de una solicitud
     * @param {number} solicitudId - ID de la solicitud
     * @returns {Promise} Promesa con los detalles
     */
    async obtenerDetalle(solicitudId) {
        SolicitudesUtils.log('API: Obtener detalle de solicitud', solicitudId);
        
        try {
            const response = await fetch(BASE_URL + 'prestamos/detalleSolicitud', {
                method: 'POST',
                headers: this.config.headers,
                body: 'idsolicitud=' + encodeURIComponent(solicitudId)
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al obtener detalles', error);
            throw error;
        }
    },

    /**
     * Aprueba una renovación
     * @param {number} solicitudId - ID de la solicitud
     * @param {number} idprestamo - ID del préstamo
     * @returns {Promise} Promesa con respuesta
     */
    async aprobarRenovacion(solicitudId, idprestamo) {
        SolicitudesUtils.log('API: Aprobar renovación', { solicitudId, idprestamo });
        
        try {
            const response = await fetch(BASE_URL + 'prestamo/aprobar-renovacion', {
                method: 'POST',
                headers: this.config.headersJSON,
                body: JSON.stringify({
                    idsolicitud: solicitudId,
                    idprestamo: idprestamo
                })
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al aprobar renovación', error);
            throw error;
        }
    },

    /**
     * Rechaza una renovación
     * @param {number} solicitudId - ID de la solicitud
     * @param {string} motivo - Motivo del rechazo
     * @returns {Promise} Promesa con respuesta
     */
    async rechazarRenovacion(solicitudId, motivo) {
        SolicitudesUtils.log('API: Rechazar renovación', { solicitudId, motivo });
        
        if (!motivo) {
            throw new Error('Debe proporcionar un motivo para rechazar la renovación');
        }
        
        try {
            const response = await fetch(BASE_URL + 'prestamo/rechazar-renovacion', {
                method: 'POST',
                headers: this.config.headersJSON,
                body: JSON.stringify({
                    idsolicitud: solicitudId,
                    motivo_rechazo: motivo
                })
            });
            
            return await this.manejarRespuesta(response);
        } catch (error) {
            SolicitudesUtils.logError('Error al rechazar renovación', error);
            throw error;
        }
    },

    /**
     * Recarga el contenido de solicitudes via AJAX
     * @returns {Promise} Promesa con el HTML de solicitudes
     */
    async cargarSolicitudes() {
        SolicitudesUtils.log('API: Cargar solicitudes');
        
        try {
            const response = await fetch(BASE_URL + 'solicitudes', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            return await response.text();
        } catch (error) {
            SolicitudesUtils.logError('Error al cargar solicitudes', error);
            throw error;
        }
    }
};

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SolicitudesAPI;
}
