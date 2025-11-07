/**
 * Módulo de API para gestionar las llamadas al servidor
 * @module HistorialAPI
 */

const HistorialAPI = {
    /**
     * Realiza una petición fetch genérica
     * @param {string} url - URL del endpoint
     * @param {Object} opciones - Opciones de fetch
     * @returns {Promise<any>} Respuesta parseada
     */
    async realizarPeticion(url, opciones = {}) {
        try {
            HistorialConfig.log('📤 Enviando solicitud a:', url);
            
            const response = await fetch(url, {
                ...opciones,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...opciones.headers
                }
            });

            HistorialConfig.log('📥 Respuesta recibida:', {
                status: response.status,
                statusText: response.statusText,
                url: url
            });

            HistorialUtils.validarRespuesta(response);
            const data = await HistorialUtils.validarYParsearJSON(response);
            
            HistorialConfig.log('✅ Datos parseados:', data);
            return data;

        } catch (error) {
            HistorialConfig.log('❌ Error en petición:', error);
            throw error;
        }
    },

    /**
     * Obtiene los detalles de un préstamo devuelto
     * @param {number} idPrestamo - ID del préstamo
     * @returns {Promise<Object>} Datos del préstamo
     */
    async obtenerDetallePrestamo(idPrestamo) {
        if (!HistorialUtils.esIdValido(idPrestamo)) {
            throw new Error('ID de préstamo no válido');
        }

        const url = HistorialConfig.getUrl(HistorialConfig.urls.prestamos.obtenerDetalle);
        const formData = HistorialUtils.crearFormData({ idprestamo: idPrestamo });

        const data = await this.realizarPeticion(url, {
            method: 'POST',
            body: formData
        });

        if (!data.success) {
            throw new Error(data.message || 'No se pudieron cargar los detalles del préstamo');
        }

        return data.data;
    },

    /**
     * Obtiene los detalles de una solicitud rechazada
     * @param {number} idSolicitud - ID de la solicitud
     * @returns {Promise<Object>} Datos de la solicitud
     */
    async obtenerDetalleSolicitud(idSolicitud) {
        if (!HistorialUtils.esIdValido(idSolicitud)) {
            throw new Error('ID de solicitud no válido');
        }

        const url = HistorialConfig.getUrl(HistorialConfig.urls.prestamos.detalleSolicitud);
        
        const data = await this.realizarPeticion(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: HistorialUtils.crearURLParams({ idsolicitud: idSolicitud })
        });

        if (!data.success) {
            throw new Error(data.message || 'No se pudo obtener la información');
        }

        return data.data;
    },

    /**
     * Elimina un registro individual del historial
     * @param {number} registroId - ID del registro
     * @param {string} tipo - Tipo de registro ('prestamo' o 'solicitud')
     * @returns {Promise<Object>} Respuesta del servidor
     */
    async eliminarRegistro(registroId, tipo) {
        if (!HistorialUtils.esIdValido(registroId)) {
            throw new Error('ID de registro no válido');
        }

        const url = HistorialConfig.getUrl(HistorialConfig.urls.prestamos.eliminarHistorial);
        
        const data = await this.realizarPeticion(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: HistorialUtils.crearURLParams({
                id: registroId,
                tipo: tipo
            })
        });

        if (!data.success) {
            throw new Error(data.message || 'No se pudo eliminar el registro');
        }

        return data;
    },

    /**
     * Elimina todo el historial
     * @returns {Promise<Object>} Respuesta del servidor con detalles
     */
    async eliminarTodoHistorial() {
        const url = HistorialConfig.getUrl(HistorialConfig.urls.prestamos.eliminarTodoHistorial);
        
        const data = await this.realizarPeticion(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        if (!data.success) {
            throw new Error(data.message || 'No se pudo eliminar el historial');
        }

        return data;
    },

    /**
     * Crea una nueva sanción
     * @param {Object} datosSancion - Datos de la sanción
     * @returns {Promise<Object>} Respuesta del servidor
     */
    async crearSancion(datosSancion) {
        if (!HistorialUtils.esObjetoValido(datosSancion)) {
            throw new Error('Datos de sanción inválidos');
        }

        const url = HistorialConfig.getUrl(HistorialConfig.urls.sanciones.crear);
        
        const data = await this.realizarPeticion(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosSancion)
        });

        if (!data.success) {
            throw new Error(data.message || 'No se pudo registrar la sanción');
        }

        return data;
    },

    /**
     * Recarga el contenido del historial via AJAX
     * @returns {Promise<string>} HTML del historial
     */
    async recargarContenidoHistorial() {
        const url = HistorialConfig.getUrl(HistorialConfig.urls.historialPrestamos);
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Error al recargar el contenido');
        }

        return response.text();
    },

    /**
     * Verifica la conectividad con el servidor
     * @returns {Promise<boolean>} true si hay conexión
     */
    async verificarConexion() {
        try {
            // Verificar conectividad básica
            const responseBase = await fetch(HistorialConfig.urls.base, {
                method: 'HEAD',
                cache: 'no-cache'
            });

            HistorialConfig.log('✅ Conectividad básica OK:', responseBase.status);

            // Verificar endpoint específico
            const responsePrestamos = await fetch(
                HistorialConfig.getUrl(HistorialConfig.urls.prestamos.index),
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            HistorialConfig.log('✅ Endpoint de préstamos OK:', responsePrestamos.status);
            
            return true;
        } catch (error) {
            HistorialConfig.log('❌ Error en diagnóstico:', error);
            return false;
        }
    }
};

// Hacer disponible globalmente
window.HistorialAPI = HistorialAPI;
