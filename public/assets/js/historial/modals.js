/**
 * Módulo de gestión de modales y notificaciones
 * @module HistorialModals
 */

const HistorialModals = {
    /**
     * Muestra un loader (indicador de carga)
     * @param {string} titulo - Título del loader
     * @param {string} texto - Texto descriptivo
     */
    mostrarLoader(titulo = 'Cargando...', texto = 'Por favor espere') {
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
     * Muestra un mensaje de éxito
     * @param {string} titulo - Título del mensaje
     * @param {string} texto - Texto del mensaje
     * @param {number} timer - Tiempo de auto-cierre
     */
    mostrarExito(titulo, texto, timer = HistorialConfig.alertas.tiempoMensajeExito) {
        return Swal.fire({
            title: titulo,
            text: texto,
            icon: 'success',
            timer: timer,
            showConfirmButton: timer === 0
        });
    },

    /**
     * Muestra un mensaje de error
     * @param {string} titulo - Título del error
     * @param {string} texto - Descripción del error
     * @param {string} footer - Texto adicional en el pie
     */
    mostrarError(titulo, texto, footer = null) {
        return Swal.fire({
            title: titulo,
            text: texto,
            icon: 'error',
            footer: footer,
            confirmButtonText: 'Entendido'
        });
    },

    /**
     * Muestra un diálogo de confirmación
     * @param {string} titulo - Título de la confirmación
     * @param {string} html - Contenido HTML
     * @param {string} textoConfirmar - Texto del botón confirmar
     * @returns {Promise<boolean>} true si confirma
     */
    async confirmar(titulo, html, textoConfirmar = 'Sí, continuar') {
        const result = await Swal.fire({
            title: titulo,
            html: html,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: textoConfirmar,
            cancelButtonText: 'Cancelar'
        });
        
        return result.isConfirmed;
    },

    /**
     * Muestra observaciones de devolución
     * @param {string} observaciones - Observaciones
     * @param {string} usuario - Nombre del usuario
     */
    mostrarObservaciones(observaciones, usuario) {
        const observacionesLimpias = HistorialUtils.limpiarDato(observaciones, 'No hay observaciones disponibles');
        const usuarioLimpio = HistorialUtils.limpiarDato(usuario, 'Usuario desconocido');
        
        const observacionesHTML = HistorialUtils.escaparHTML(observacionesLimpias);
        const usuarioHTML = HistorialUtils.escaparHTML(usuarioLimpio);
        
        const debugInfo = HistorialUtils.esModoDebug() ? `
            <div class="mt-2 p-2 bg-light border rounded">
                <small class="text-muted">
                    <strong>Debug:</strong><br>
                    Tipo: ${typeof observaciones}<br>
                    Longitud: ${observaciones ? observaciones.length : 0}<br>
                    Valor crudo: "${observaciones}"
                </small>
            </div>
        ` : '';
        
        Swal.fire({
            title: 'Observaciones de Devolución',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6 class="text-primary mb-2">
                            <i class="ti ti-user me-2"></i>Usuario: ${usuarioHTML}
                        </h6>
                    </div>
                    <div class="alert alert-light border">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-quote text-muted me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <p class="mb-0 fst-italic">${observacionesHTML}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            Observaciones registradas al momento de la devolución
                        </small>
                    </div>
                    ${debugInfo}
                </div>
            `,
            icon: 'info',
            width: HistorialConfig.alertas.anchoModalExtraGrande,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
    },

    /**
     * Muestra detalles de una incidencia
     * @param {Object} incidencia - Datos de la incidencia
     */
    mostrarDetalleIncidencia(incidencia) {
        if (!HistorialUtils.esObjetoValido(incidencia)) {
            this.mostrarError('Error', 'No se pudieron cargar los detalles de la incidencia');
            return;
        }
        
        const tipoHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(incidencia.tipo, 'Incidencia'));
        const detalleHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(incidencia.detalle, 'Sin detalles específicos'));
        const observacionesHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(incidencia.observaciones, 'Sin observaciones adicionales'));
        const usuarioHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(incidencia.usuario, 'Usuario desconocido'));
        const fechaHTML = HistorialUtils.formatearFecha(incidencia.fecha);
        
        Swal.fire({
            title: '⚠️ Detalles de Incidencia',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6 class="text-danger mb-2">
                            <i class="ti ti-user me-2"></i>Usuario: ${usuarioHTML}
                        </h6>
                    </div>
                    
                    <div class="alert alert-danger">
                        <div class="mb-3">
                            <strong><i class="ti ti-alert-triangle me-2"></i>Tipo de Incidencia:</strong>
                            <p class="mb-0 mt-1">${tipoHTML}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="ti ti-file-text me-2"></i>Detalle:</strong>
                            <p class="mb-0 mt-1">${detalleHTML}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="ti ti-message-circle me-2"></i>Observaciones:</strong>
                            <p class="mb-0 mt-1 fst-italic">${observacionesHTML}</p>
                        </div>
                        
                        <div>
                            <strong><i class="ti ti-calendar me-2"></i>Fecha de Registro:</strong>
                            <p class="mb-0 mt-1">${fechaHTML}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            Incidencia registrada al momento de la devolución del material
                        </small>
                    </div>
                </div>
            `,
            icon: 'warning',
            width: HistorialConfig.alertas.anchoModalGrande,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#dc3545',
            customClass: {
                popup: 'swal-incidencia-popup'
            }
        });
    },

    /**
     * Muestra modal para generar sanción
     * @param {number} prestamoId - ID del préstamo
     * @param {string} nombreUsuario - Nombre del usuario
     * @param {number} horasRetraso - Horas de retraso
     * @returns {Promise<Object|null>} Datos de la sanción o null
     */
    async mostrarFormularioSancion(prestamoId, nombreUsuario, horasRetraso) {
        const tipoSancion = HistorialConfig.calcularTipoSancion(horasRetraso);
        const montoSancion = HistorialConfig.calcularMontoSancion(horasRetraso);
        const descripcionSancion = HistorialUtils.generarDescripcionSancion(horasRetraso);
        const textoRetraso = HistorialUtils.textoRetraso(horasRetraso);
        const codigoPrestamo = HistorialUtils.generarCodigoPrestamo(prestamoId);
        
        const result = await Swal.fire({
            title: '⚠️ Generar Sanción',
            html: `
                <div class="text-start">
                    <p><strong>Usuario:</strong> ${HistorialUtils.escaparHTML(nombreUsuario)}</p>
                    <p><strong>Préstamo ID:</strong> ${codigoPrestamo}</p>
                    <p><strong>Retraso:</strong> ${textoRetraso}</p>
                    <hr>
                    <div class="alert alert-warning">
                        <p class="mb-2"><strong>Tipo de Sanción:</strong> <span class="badge bg-warning">${tipoSancion}</span></p>
                        <p class="mb-2"><strong>Monto:</strong> <span class="text-danger fw-bold">${HistorialUtils.formatearMoneda(montoSancion)}</span></p>
                        <p class="mb-0"><strong>Descripción:</strong> ${descripcionSancion}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="observacionesSancion" class="form-label"><strong>Observaciones adicionales:</strong></label>
                    <textarea id="observacionesSancion" class="form-control" rows="3" 
                              placeholder="Ingrese observaciones adicionales sobre esta sanción..."></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Generar Sanción',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            width: HistorialConfig.alertas.anchoModalExtraGrande,
            preConfirm: () => {
                const observaciones = document.getElementById('observacionesSancion').value;
                return {
                    prestamoId: prestamoId,
                    tipoSancion: tipoSancion,
                    monto: montoSancion,
                    descripcion: descripcionSancion,
                    observaciones: observaciones.trim()
                };
            }
        });
        
        return result.isConfirmed ? result.value : null;
    },

    /**
     * Muestra detalles de solicitud rechazada
     * @param {Object} detalle - Datos de la solicitud
     */
    mostrarSolicitudRechazada(detalle) {
        const usuarioHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(detalle.usuario_completo));
        const documentoHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(detalle.documento));
        const recursoHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(detalle.recurso_titulo));
        const codigoHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(detalle.codigo_ejemplar));
        const motivoHTML = HistorialUtils.escaparHTML(HistorialUtils.limpiarDato(detalle.motivo_rechazo, 'No se especificó motivo'));
        
        const fechaSolicitud = HistorialUtils.formatearFecha(detalle.fecha_solicitud, HistorialConfig.formateo.formatoFechaCorta);
        const fechaDevolucion = HistorialUtils.formatearFecha(detalle.fecha_devolucion, HistorialConfig.formateo.formatoFechaCorta);
        
        let infoProcesado = '';
        if (detalle.fecha_procesado) {
            const fechaProcesado = HistorialUtils.formatearFecha(detalle.fecha_procesado, HistorialConfig.formateo.formatoFechaCorta);
            const horaProcesado = HistorialUtils.formatearHora(detalle.fecha_procesado);
            infoProcesado = `
                <p class="text-muted small mb-0">
                    <i class="ti ti-calendar"></i> 
                    Rechazado el: ${fechaProcesado} a las ${horaProcesado}
                </p>
            `;
        }
        
        Swal.fire({
            title: '📋 Solicitud Rechazada',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6 class="fw-bold">Información del Usuario</h6>
                        <p class="mb-1"><strong>Usuario:</strong> ${usuarioHTML}</p>
                        <p class="mb-1"><strong>Documento:</strong> ${documentoHTML}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Información del Recurso</h6>
                        <p class="mb-1"><strong>Título:</strong> ${recursoHTML}</p>
                        <p class="mb-1"><strong>Código:</strong> ${codigoHTML}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Fechas Solicitadas</h6>
                        <p class="mb-1"><strong>Fecha inicio:</strong> ${fechaSolicitud}</p>
                        <p class="mb-1"><strong>Fecha devolución:</strong> ${fechaDevolucion}</p>
                    </div>
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">Motivo de Rechazo</h6>
                        <p class="mb-0">${motivoHTML}</p>
                    </div>
                    ${infoProcesado}
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Cerrar',
            width: HistorialConfig.alertas.anchoModalGrande
        });
    },

    /**
     * Cierra el modal actual de SweetAlert2
     */
    cerrar() {
        Swal.close();
    }
};

// Hacer disponible globalmente
window.HistorialModals = HistorialModals;
