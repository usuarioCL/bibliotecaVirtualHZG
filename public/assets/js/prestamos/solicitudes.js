/**
 * Módulo Principal de Solicitudes de Préstamos
 * Coordina la interacción entre API, UI y lógica de negocio
 */

const SolicitudesController = {
    /**
     * Datos de solicitudes (inyectados desde PHP)
     */
    solicitudes: [],

    /**
     * Inicializa el módulo con datos
     * @param {Array} solicitudesData - Datos de solicitudes desde el servidor
     */
    init(solicitudesData = []) {
        this.solicitudes = solicitudesData;
        this.inicializarEventos();
        SolicitudesUtils.log('Módulo de solicitudes inicializado', {
            total: this.solicitudes.length
        });
    },

    /**
     * Inicializa eventos del DOM
     */
    inicializarEventos() {
        // Inicializar tooltips al cargar
        document.addEventListener('DOMContentLoaded', () => {
            SolicitudesUtils.inicializarTooltips();
        });

        // Reinicializar tooltips después de cargar contenido dinámico
        document.addEventListener('content-loaded', () => {
            SolicitudesUtils.destruirTooltips();
            SolicitudesUtils.inicializarTooltips();
        });
    },

    /**
     * Aprueba una solicitud individual
     * @param {number} solicitudId - ID de la solicitud
     */
    async aprobarSolicitud(solicitudId) {
        SolicitudesUtils.log('Aprobar solicitud', solicitudId);
        
        const confirmacion = await SolicitudesUI.confirmar(
            '¿Aprobar Solicitud?',
            '¿Estás seguro de que deseas aprobar esta solicitud de préstamo?',
            'Sí, aprobar',
            '#28a745'
        );

        if (confirmacion.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Aprobando solicitud de préstamo');
            
            try {
                const response = await SolicitudesAPI.aprobarSolicitud(solicitudId);
                
                if (response.success) {
                    SolicitudesUI.mostrarExito(
                        '¡Solicitud Aprobada!',
                        response.message || 'La solicitud ha sido aprobada exitosamente',
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error al Aprobar',
                        response.message || 'No se pudo aprobar la solicitud'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al aprobar la solicitud'
                );
            }
        }
    },

    /**
     * Rechaza una solicitud individual
     * @param {number} solicitudId - ID de la solicitud
     */
    async rechazarSolicitud(solicitudId) {
        SolicitudesUtils.log('Rechazar solicitud', solicitudId);
        
        const resultado = await SolicitudesUI.confirmarConInput(
            '¿Rechazar Solicitud?',
            'Motivo del rechazo (opcional)',
            'Escribe el motivo por el cual se rechaza la solicitud (opcional)...',
            false,
            'Rechazar',
            '#dc3545'
        );

        if (resultado.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Rechazando solicitud de préstamo');
            
            try {
                const response = await SolicitudesAPI.rechazarSolicitud(solicitudId, resultado.value || '');
                
                if (response.success) {
                    SolicitudesUI.mostrarExito(
                        '¡Solicitud Rechazada!',
                        response.message || 'La solicitud ha sido rechazada',
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error al Rechazar',
                        response.message || 'No se pudo rechazar la solicitud'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al rechazar la solicitud'
                );
            }
        }
    },

    /**
     * Ver detalles de una solicitud
     * @param {number} solicitudId - ID de la solicitud
     */
    async verDetalleSolicitud(solicitudId) {
        SolicitudesUtils.log('Ver detalles de solicitud', solicitudId);
        
        SolicitudesUI.mostrarLoader('Cargando...', 'Obteniendo detalles de la solicitud');
        
        try {
            const response = await SolicitudesAPI.obtenerDetalle(solicitudId);
            
            if (response.success) {
                SolicitudesUI.mostrarModalDetalles(response.data);
            } else {
                SolicitudesUI.mostrarError(
                    'Error',
                    response.message || 'No se pudieron cargar los detalles de la solicitud'
                );
            }
        } catch (error) {
            SolicitudesUI.mostrarError(
                'Error de Conexión',
                'Ha ocurrido un error al obtener los detalles'
            );
        }
    },

    /**
     * Aprueba todas las solicitudes disponibles
     */
    async aprobarTodas() {
        SolicitudesUtils.log('Aprobar todas las solicitudes', this.solicitudes);
        
        // Filtrar solicitudes disponibles
        const disponibles = SolicitudesUtils.filtrarDisponibles(this.solicitudes);
        
        SolicitudesUtils.log('Solicitudes disponibles encontradas', disponibles.length);
        
        if (disponibles.length === 0) {
            SolicitudesUI.mostrarInfo(
                'Sin Solicitudes Disponibles',
                'No hay solicitudes con recursos disponibles para aprobar'
            );
            return;
        }

        const confirmacion = await SolicitudesUI.confirmar(
            `¿Aprobar ${disponibles.length} solicitudes?`,
            'Se aprobarán todas las solicitudes con recursos disponibles',
            `Sí, aprobar ${disponibles.length} solicitudes`,
            '#28a745'
        );

        if (confirmacion.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Aprobando solicitudes disponibles');
            
            // Extraer IDs válidos
            const solicitudesIds = SolicitudesUtils.extraerIds(disponibles);
            
            SolicitudesUtils.log('Solicitudes a aprobar', solicitudesIds);

            if (solicitudesIds.length === 0) {
                SolicitudesUI.mostrarError(
                    'Error',
                    'No se encontraron solicitudes válidas para aprobar'
                );
                return;
            }

            try {
                const response = await SolicitudesAPI.aprobarTodas(solicitudesIds);
                
                if (response.success) {
                    const aprobadas = response.aprobadas || solicitudesIds.length;
                    const fallidas = response.fallidas || 0;
                    
                    let mensaje = `Se aprobaron ${aprobadas} solicitud(es) exitosamente`;
                    if (fallidas > 0) {
                        mensaje += `\n${fallidas} solicitud(es) no pudieron ser aprobadas`;
                    }
                    
                    SolicitudesUI.mostrarExito(
                        '¡Proceso Completado!',
                        mensaje,
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error',
                        response.message || 'No se pudieron aprobar las solicitudes'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al aprobar las solicitudes'
                );
            }
        }
    },

    /**
     * Rechaza todas las solicitudes pendientes
     */
    async rechazarTodas() {
        SolicitudesUtils.log('Rechazar todas las solicitudes', this.solicitudes);
        
        if (this.solicitudes.length === 0) {
            SolicitudesUI.mostrarInfo(
                'Sin Solicitudes',
                'No hay solicitudes para rechazar'
            );
            return;
        }

        const resultado = await SolicitudesUI.confirmarConInput(
            `¿Rechazar todas las ${this.solicitudes.length} solicitudes?`,
            'Motivo del rechazo masivo (opcional)',
            'Escribe el motivo por el cual se rechazan todas las solicitudes (opcional)...',
            false,
            `Sí, rechazar ${this.solicitudes.length} solicitudes`,
            '#dc3545'
        );

        if (resultado.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Rechazando todas las solicitudes');
            
            // Extraer IDs válidos
            const solicitudesIds = SolicitudesUtils.extraerIds(this.solicitudes);
            
            SolicitudesUtils.log('Solicitudes a rechazar', solicitudesIds);

            if (solicitudesIds.length === 0) {
                SolicitudesUI.mostrarError(
                    'Error',
                    'No se encontraron solicitudes válidas para rechazar'
                );
                return;
            }

            try {
                const response = await SolicitudesAPI.rechazarTodas(solicitudesIds, resultado.value || '');
                
                if (response.success) {
                    const rechazadas = response.rechazadas || solicitudesIds.length;
                    
                    SolicitudesUI.mostrarExito(
                        '¡Proceso Completado!',
                        `Se rechazaron ${rechazadas} solicitud(es) exitosamente`,
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error',
                        response.message || 'No se pudieron rechazar las solicitudes'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al rechazar las solicitudes'
                );
            }
        }
    },

    /**
     * Aprueba una renovación
     * @param {number} solicitudId - ID de la solicitud
     * @param {number} idprestamo - ID del préstamo
     */
    async aprobarRenovacion(solicitudId, idprestamo) {
        SolicitudesUtils.log('Aprobar renovación', { solicitudId, idprestamo });
        
        const confirmacion = await SolicitudesUI.confirmar(
            '¿Aprobar Renovación?',
            '¿Estás seguro de que deseas aprobar esta solicitud de renovación?',
            'Sí, aprobar',
            '#28a745'
        );

        if (confirmacion.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Aprobando renovación de préstamo');
            
            try {
                const response = await SolicitudesAPI.aprobarRenovacion(solicitudId, idprestamo);
                
                if (response.success) {
                    SolicitudesUI.mostrarExito(
                        '¡Renovación Aprobada!',
                        response.message || 'La renovación ha sido aprobada exitosamente',
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error al Aprobar',
                        response.message || 'No se pudo aprobar la renovación'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al aprobar la renovación'
                );
            }
        }
    },

    /**
     * Rechaza una renovación
     * @param {number} solicitudId - ID de la solicitud
     */
    async rechazarRenovacion(solicitudId) {
        SolicitudesUtils.log('Rechazar renovación', solicitudId);
        
        const resultado = await SolicitudesUI.confirmarConInput(
            '¿Rechazar Renovación?',
            'Motivo del rechazo',
            'Escribe el motivo por el cual se rechaza la renovación...',
            true,
            'Rechazar',
            '#dc3545'
        );

        if (resultado.isConfirmed) {
            SolicitudesUI.mostrarLoader('Procesando...', 'Rechazando renovación de préstamo');
            
            try {
                const response = await SolicitudesAPI.rechazarRenovacion(solicitudId, resultado.value);
                
                if (response.success) {
                    SolicitudesUI.mostrarExito(
                        '¡Renovación Rechazada!',
                        response.message || 'La renovación ha sido rechazada',
                        () => SolicitudesUI.recargarContenido()
                    );
                } else {
                    SolicitudesUI.mostrarError(
                        'Error al Rechazar',
                        response.message || 'No se pudo rechazar la renovación'
                    );
                }
            } catch (error) {
                SolicitudesUI.mostrarError(
                    'Error de Conexión',
                    'Ha ocurrido un error al rechazar la renovación'
                );
            }
        }
    }
};

// Funciones globales para mantener compatibilidad con onclick en HTML
function aprobarSolicitud(solicitudId) {
    SolicitudesController.aprobarSolicitud(solicitudId);
}

function rechazarSolicitud(solicitudId) {
    SolicitudesController.rechazarSolicitud(solicitudId);
}

function verDetalleSolicitud(solicitudId) {
    SolicitudesController.verDetalleSolicitud(solicitudId);
}

function aprobarTodas() {
    SolicitudesController.aprobarTodas();
}

function rechazarTodas() {
    SolicitudesController.rechazarTodas();
}

function aprobarRenovacion(solicitudId, idprestamo) {
    SolicitudesController.aprobarRenovacion(solicitudId, idprestamo);
}

function rechazarRenovacion(solicitudId) {
    SolicitudesController.rechazarRenovacion(solicitudId);
}

function cerrarModalDetalle() {
    SolicitudesUI.cerrarModalDetalle();
}

function recargarContenidoSolicitudes() {
    SolicitudesUI.recargarContenido();
}
