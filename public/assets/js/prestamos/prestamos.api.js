/**
 * Módulo API para Préstamos
 * Centraliza todas las llamadas AJAX relacionadas con préstamos
 * Maneja errores de forma consistente
 */

window.PrestamosAPI = window.PrestamosAPI || {
    /**
     * Configuración base
     */
    config: {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    },

    /**
     * Obtiene la URL base del sistema
     * @private
     */
    _getBaseUrl() {
        // La base_url es inyectada desde PHP en el archivo index.php
        return window.BIBLIOTECA_BASE_URL || '';
    },

    /**
     * Construye la URL completa para un endpoint
     * @private
     */
    _buildUrl(endpoint) {
        const baseUrl = this._getBaseUrl();
        return `${baseUrl}${endpoint}`;
    },

    /**
     * Convierte un objeto a formato URL-encoded
     * @private
     */
    _encodeFormData(data) {
        return Object.keys(data)
            .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(data[key])}`)
            .join('&');
    },

    /**
     * Maneja errores de fetch de forma consistente
     * @private
     */
    _handleFetchError(error, contexto = 'operación') {
        console.error(`Error en ${contexto}:`, error);
        return {
            success: false,
            message: `Error al realizar la ${contexto}`,
            error: error.message
        };
    },

    /**
     * Obtiene los detalles de un préstamo
     * @param {number} idprestamo - ID del préstamo
     * @returns {Promise} Promesa con los detalles
     */
    async obtenerDetallePrestamo(idprestamo) {
        try {
            const response = await fetch(this._buildUrl('prestamos/detalle'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({ idprestamo })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'No se pudieron obtener los detalles');
            }

            return data;
        } catch (error) {
            return this._handleFetchError(error, 'obtener detalles del préstamo');
        }
    },

    /**
     * Renueva un préstamo
     * @param {object} datos - Datos de la renovación
     * @returns {Promise} Promesa con el resultado
     */
    async renovarPrestamo(datos) {
        try {
            const { idprestamo, nueva_fecha_prestamo, nueva_fecha_devolucion, motivo } = datos;

            const response = await fetch(this._buildUrl('prestamos/renovar'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({
                    idprestamo,
                    nueva_fecha_prestamo,
                    nueva_fecha_devolucion,
                    motivo: motivo || ''
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'No se pudo renovar el préstamo');
            }

            return data;
        } catch (error) {
            return this._handleFetchError(error, 'renovar préstamo');
        }
    },

    /**
     * Procesa la devolución de un préstamo
     * @param {object} datos - Datos de la devolución
     * @returns {Promise} Promesa con el resultado
     */
    async procesarDevolucion(datos) {
        try {
            const { 
                idprestamo, 
                estado_devolucion, 
                idtiposancion, 
                detalle_incidencia, 
                observaciones 
            } = datos;

            const response = await fetch(this._buildUrl('prestamos/procesar-devolucion'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({
                    idprestamo,
                    estado_devolucion,
                    idtiposancion: idtiposancion || '',
                    detalle_incidencia: detalle_incidencia || '',
                    observaciones: observaciones || ''
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'No se pudo procesar la devolución');
            }

            return data;
        } catch (error) {
            return this._handleFetchError(error, 'procesar devolución');
        }
    },

    /**
     * Cancela un préstamo
     * @param {number} idprestamo - ID del préstamo
     * @param {string} motivo - Motivo de la cancelación
     * @returns {Promise} Promesa con el resultado
     */
    async cancelarPrestamo(idprestamo, motivo = '') {
        try {
            const response = await fetch(this._buildUrl('prestamos/cancelar'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({
                    idprestamo,
                    motivo
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'No se pudo cancelar el préstamo');
            }

            return data;
        } catch (error) {
            return this._handleFetchError(error, 'cancelar préstamo');
        }
    },

    /**
     * Crea un nuevo préstamo
     * @param {object} datos - Datos del nuevo préstamo
     * @returns {Promise} Promesa con el resultado
     */
    async crearPrestamo(datos) {
        try {
            const { 
                idusuario, 
                idejemplar, 
                fechaPrestamo, 
                horaInicio, 
                horaFin, 
                observaciones 
            } = datos;

            const response = await fetch(this._buildUrl('prestamos/crear'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({
                    idusuario,
                    idejemplar,
                    fechaPrestamo,
                    horaInicio,
                    horaFin,
                    observaciones: observaciones || ''
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'No se pudo crear el préstamo');
            }

            return data;
        } catch (error) {
            return this._handleFetchError(error, 'crear préstamo');
        }
    },

    /**
     * Obtiene los tipos de sanción disponibles
     * @returns {Promise} Promesa con los tipos de sanción
     */
    async obtenerTiposSancion() {
        try {
            const response = await fetch(this._buildUrl('prestamos/obtener-tipos-sancion'), {
                method: 'GET',
                headers: this.config.headers
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error al obtener tipos de sanción:', error);
            // Retornar array vacío en caso de error
            return { success: true, data: [] };
        }
    },

    /**
     * Busca usuarios por término de búsqueda
     * @param {string} termino - Término a buscar
     * @returns {Promise} Promesa con los resultados
     */
    async buscarUsuarios(termino) {
        try {
            const response = await fetch(this._buildUrl('usuarios/buscar-ajax'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({ termino })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            return this._handleFetchError(error, 'buscar usuarios');
        }
    },

    /**
     * Busca recursos disponibles por término de búsqueda
     * @param {string} termino - Término a buscar
     * @returns {Promise} Promesa con los resultados
     */
    async buscarRecursosDisponibles(termino) {
        try {
            const response = await fetch(this._buildUrl('recursos/buscar-disponibles-ajax'), {
                method: 'POST',
                headers: this.config.headers,
                body: this._encodeFormData({ termino })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            return this._handleFetchError(error, 'buscar recursos');
        }
    },

    /**
     * Recarga el contenido de préstamos vía AJAX
     * @returns {Promise} Promesa con el HTML del contenido
     */
    async recargarContenidoPrestamos() {
        try {
            const response = await fetch(this._buildUrl('prestamos'), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const html = await response.text();
            return { success: true, html };
        } catch (error) {
            return this._handleFetchError(error, 'recargar contenido');
        }
    }
};

// Hacer disponible globalmente
if (typeof window !== 'undefined') {
    window.PrestamosAPI = PrestamosAPI;
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrestamosAPI;
}
