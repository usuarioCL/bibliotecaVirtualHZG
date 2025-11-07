/**
 * Módulo UI para Solicitudes de Préstamos
 * Maneja la interacción con el usuario (modales, alertas, UI updates)
 */

var SolicitudesUI = SolicitudesUI || {
    /**
     * Muestra un loader con SweetAlert2
     * @param {string} titulo - Título del loader
     * @param {string} texto - Texto descriptivo
     */
    mostrarLoader(titulo = 'Procesando...', texto = 'Por favor espera') {
        Swal.fire({
            title: titulo,
            text: texto,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },

    /**
     * Muestra mensaje de éxito
     * @param {string} titulo - Título
     * @param {string} texto - Texto descriptivo
     * @param {Function} callback - Función a ejecutar al confirmar
     */
    mostrarExito(titulo, texto, callback = null) {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    },

    /**
     * Muestra mensaje de error
     * @param {string} titulo - Título
     * @param {string} texto - Texto descriptivo
     */
    mostrarError(titulo = 'Error', texto = 'Ha ocurrido un error') {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    },

    /**
     * Muestra confirmación simple
     * @param {string} titulo - Título
     * @param {string} texto - Texto descriptivo
     * @param {string} textoConfirmar - Texto del botón confirmar
     * @param {string} color - Color del botón
     * @returns {Promise} Promesa con el resultado
     */
    async confirmar(titulo, texto, textoConfirmar = 'Confirmar', color = '#28a745') {
        return await Swal.fire({
            title: titulo,
            text: texto,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: textoConfirmar,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: color
        });
    },

    /**
     * Muestra confirmación con input de texto
     * @param {string} titulo - Título
     * @param {string} texto - Texto descriptivo
     * @param {string} placeholder - Placeholder del input
     * @param {boolean} requerido - Si el input es requerido
     * @param {string} textoConfirmar - Texto del botón
     * @param {string} color - Color del botón
     * @returns {Promise} Promesa con el resultado
     */
    async confirmarConInput(titulo, texto, placeholder = '', requerido = false, textoConfirmar = 'Confirmar', color = '#28a745') {
        return await Swal.fire({
            title: titulo,
            input: 'textarea',
            inputLabel: texto,
            inputPlaceholder: placeholder,
            inputAttributes: {
                'aria-label': texto
            },
            showCancelButton: true,
            confirmButtonText: textoConfirmar,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: color,
            inputValidator: (value) => {
                if (requerido && !value) {
                    return 'Este campo es requerido';
                }
                return null;
            }
        });
    },

    /**
     * Muestra información
     * @param {string} titulo - Título
     * @param {string} texto - Texto descriptivo
     */
    mostrarInfo(titulo, texto) {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'info',
            confirmButtonText: 'Aceptar'
        });
    },

    /**
     * Cierra cualquier SweetAlert activo
     */
    cerrarAlerta() {
        Swal.close();
    },

    /**
     * Muestra el modal de detalles de solicitud
     * @param {Object} detalle - Datos de la solicitud
     */
    mostrarModalDetalles(detalle) {
        // Limpiar modal existente si hay
        let modalExistente = document.getElementById('modalDetalleSolicitud');
        if (modalExistente) {
            modalExistente.remove();
        }

        // Formatear datos
        const prioridadConfig = SolicitudesUtils.getPrioridadConfig(detalle.prioridad);
        const disponibilidadBadge = SolicitudesUtils.generarBadgeDisponibilidad(detalle.disponible);
        const autoresLista = SolicitudesUtils.generarListaAutores(detalle.autores);
        
        // Crear el HTML del modal
        const modalHtml = `
            <div class="modal fade" id="modalDetalleSolicitud" tabindex="-1" style="z-index: 99999;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-gradient text-white">
                            <h5 class="modal-title">
                                <i class="ti ti-file-info me-2"></i>
                                Detalles de la Solicitud #${detalle.id}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-4">
                                <!-- Información del Usuario -->
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="ti ti-user text-primary me-2"></i>
                                                Información del Usuario
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="text-muted fw-medium" width="40%">Nombre:</td>
                                                    <td>${detalle.nombre_usuario}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Email:</td>
                                                    <td>${detalle.email_usuario}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Teléfono:</td>
                                                    <td>${detalle.telefono || 'No especificado'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Tipo:</td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">
                                                            ${detalle.tipo_usuario}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del Recurso -->
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="ti ti-book text-primary me-2"></i>
                                                Información del Recurso
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="text-muted fw-medium" width="40%">Título:</td>
                                                    <td class="fw-medium">${detalle.titulo}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Autor(es):</td>
                                                    <td>${autoresLista}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Editorial:</td>
                                                    <td>${detalle.editorial || 'No especificada'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Tipo:</td>
                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            ${detalle.tipo_recurso}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-medium">Disponibilidad:</td>
                                                    <td>${disponibilidadBadge}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalles de la Solicitud -->
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="ti ti-calendar text-primary me-2"></i>
                                                Detalles de la Solicitud
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Fecha de Solicitud</small>
                                                    <strong>${SolicitudesUtils.formatearFechaHora(detalle.fecha_creacion || detalle.fecha_solicitud)}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Fecha Inicio Préstamo</small>
                                                    <strong>${SolicitudesUtils.formatearFecha(detalle.fecha_solicitud)}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Fecha de Devolución</small>
                                                    <strong>${SolicitudesUtils.formatearFecha(detalle.fecha_devolucion)}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Prioridad</small>
                                                    <span class="badge ${prioridadConfig.clase}">
                                                        <i class="ti ${prioridadConfig.icono} me-1"></i>
                                                        ${detalle.prioridad}
                                                    </span>
                                                </div>
                                            </div>
                                            ${detalle.motivo ? `
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <small class="text-muted d-block">Motivo de la Solicitud</small>
                                                        <p class="mb-0 mt-1">${detalle.motivo}</p>
                                                    </div>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Agregar el modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleSolicitud'));
        modal.show();
        
        // Cerrar SweetAlert2
        this.cerrarAlerta();
    },

    /**
     * Cierra el modal de detalles
     */
    cerrarModalDetalle() {
        const modal = document.getElementById('modalDetalleSolicitud');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    },

    /**
     * Recarga el contenido de solicitudes
     * @param {string} contenedorId - ID del contenedor principal
     */
    async recargarContenido(contenedorId = 'contenedor-principal') {
        const contenedor = document.getElementById(contenedorId);
        
        if (contenedor) {
            SolicitudesUtils.log('Recargando solicitudes via AJAX');
            
            // Mostrar indicador de carga
            contenedor.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Actualizando solicitudes...</p>
                </div>
            `;
            
            try {
                const html = await SolicitudesAPI.cargarSolicitudes();
                contenedor.innerHTML = html;
                
                // Disparar evento de contenido cargado
                document.dispatchEvent(new CustomEvent('content-loaded'));
                
                SolicitudesUtils.log('Solicitudes recargadas exitosamente');
            } catch (error) {
                SolicitudesUtils.logError('Error al recargar solicitudes', error);
                contenedor.innerHTML = `
                    <div class="text-danger text-center py-5">
                        <i class="ti ti-alert-circle" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Error al cargar solicitudes</h5>
                        <p class="text-muted">${error.message}</p>
                        <button class="btn btn-primary" onclick="location.reload()">
                            <i class="ti ti-refresh me-1"></i> Recargar Página
                        </button>
                    </div>
                `;
            }
        } else {
            // No estamos en el panel de administración, recargar página completa
            SolicitudesUtils.log('Recargando página completa');
            location.reload();
        }
    }
};

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SolicitudesUI;
}
