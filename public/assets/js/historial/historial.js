/**
 * Módulo principal del historial de préstamos
 * Orquesta todas las funcionalidades del sistema
 * @module Historial
 */

const Historial = {
    /**
     * Inicializa el módulo
     */
    init() {
        this.setupEventListeners();
        this.showDebugInfo();
    },

    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Event listener para búsqueda con Enter
        const busquedaInput = document.getElementById('busquedaRapida');
        if (busquedaInput) {
            busquedaInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.aplicarFiltros();
                }
            });
        }
    },

    /**
     * Muestra información de debug si estamos en desarrollo
     */
    showDebugInfo() {
        if (HistorialConfig.isDebugMode()) {
            console.log('🔧 Modo de desarrollo detectado - funciones de diagnóstico disponibles');
            console.log('💡 Use Historial.diagnosticarConexion() para verificar conectividad');
            console.log('💡 Use Historial.recargarPagina() para recargar si hay problemas');
        }
    },

    /**
     * Aplica filtros de búsqueda
     */
    aplicarFiltros() {
        const periodo = document.getElementById('periodoFiltro')?.value || '';
        const estado = document.getElementById('estadoFiltro')?.value || '';
        const busqueda = document.getElementById('busquedaRapida')?.value || '';
        
        HistorialConfig.log('Aplicando filtros:', { periodo, estado, busqueda });
        
        // TODO: Implementar filtrado en tiempo real
        if (busqueda || periodo || estado) {
            HistorialModals.mostrarExito(
                'Filtros Aplicados',
                'Se han aplicado los filtros seleccionados',
                HistorialConfig.alertas.tiempoAutoCierre
            );
        }
    },

    /**
     * Muestra observaciones de devolución
     * @param {string} observaciones - Observaciones
     * @param {string} usuario - Nombre del usuario
     */
    mostrarObservaciones(observaciones, usuario) {
        HistorialModals.mostrarObservaciones(observaciones, usuario);
    },

    /**
     * Muestra detalles de una incidencia
     * @param {Object} incidencia - Datos de la incidencia
     */
    mostrarDetalleIncidencia(incidencia) {
        HistorialModals.mostrarDetalleIncidencia(incidencia);
    },

    /**
     * Ver detalles completos del historial de un préstamo
     * @param {number} registroId - ID del préstamo
     */
    async verDetalleHistorial(registroId) {
        try {
            // Validar ID
            if (!HistorialUtils.esIdValido(registroId)) {
                HistorialModals.mostrarError('Error', 'ID de préstamo no válido');
                return;
            }
            
            // Mostrar loader
            HistorialModals.mostrarLoader('Cargando...', 'Obteniendo detalles del préstamo');
            
            // Obtener datos
            const detalle = await HistorialAPI.obtenerDetallePrestamo(registroId);
            
            // Mostrar modal con detalles
            this.mostrarModalDetallesHistorial(detalle, registroId);
            
        } catch (error) {
            HistorialConfig.log('Error al obtener detalles:', error);
            
            const mensajeError = HistorialUtils.generarMensajeError(error);
            
            HistorialModals.mostrarError(
                'Error de Conexión',
                mensajeError,
                'Consulte con el administrador si el problema persiste'
            );
        }
    },

    /**
     * Muestra el modal Bootstrap con los detalles completos del préstamo
     * @param {Object} detalle - Datos del préstamo
     * @param {number} registroId - ID del registro
     */
    mostrarModalDetallesHistorial(detalle, registroId) {
        if (!HistorialUtils.esObjetoValido(detalle)) {
            HistorialModals.mostrarError('Error', 'No se pudieron obtener los datos del préstamo');
            return;
        }

        // Obtener ID del préstamo
        const idPrestamo = detalle.id || detalle.idprestamo || registroId;
        
        HistorialConfig.log('Mostrando modal para préstamo ID:', idPrestamo);

        // Remover modal existente si hay
        const modalExistente = document.getElementById('modalDetalleHistorial');
        if (modalExistente) {
            modalExistente.remove();
        }

        // Procesar fechas
        const fechaPrestamo = new Date(detalle.fechaprestamo || Date.now());
        const fechaLimite = new Date(detalle.fecha_limite || Date.now());
        const fechaDevolucionReal = new Date(detalle.fecha_devolucion_real || Date.now());
        
        // Calcular retraso
        const infoRetraso = HistorialUtils.calcularRetraso(detalle.horas_retraso_total || 0);
        const estadoPrestamo = HistorialUtils.determinarEstadoPrestamo(
            infoRetraso.diasRetraso, 
            infoRetraso.horasRetraso
        );
        
        // Generar HTML del modal
        const modalHtml = this.generarHTMLModalDetalle(
            detalle, 
            idPrestamo, 
            fechaPrestamo, 
            fechaLimite, 
            fechaDevolucionReal,
            infoRetraso,
            estadoPrestamo
        );

        // Agregar al DOM y mostrar
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleHistorial'));
        modal.show();
        
        HistorialModals.cerrar();
    },

    /**
     * Genera el HTML del modal de detalles
     * @returns {string} HTML del modal
     */
    generarHTMLModalDetalle(detalle, idPrestamo, fechaPrestamo, fechaLimite, fechaDevolucionReal, infoRetraso, estadoPrestamo) {
        const inicialesUsuario = HistorialUtils.obtenerIniciales(detalle.usuario);
        
        return `
            <div class="modal fade" id="modalDetalleHistorial" tabindex="-1" style="z-index: 99999;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-history me-2"></i>Detalles del Préstamo - ${detalle.codigo_prestamo}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${this.generarSeccionUsuario(detalle, idPrestamo, fechaPrestamo, inicialesUsuario, estadoPrestamo, infoRetraso)}
                            <hr>
                            ${this.generarSeccionRecurso(detalle)}
                            <hr>
                            ${this.generarSeccionTimeline(fechaPrestamo, fechaLimite, fechaDevolucionReal, detalle, infoRetraso)}
                            ${this.generarSeccionObservaciones(detalle, infoRetraso)}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info" onclick="Historial.generarReporte(${idPrestamo})">
                                <i class="ti ti-file-download me-2"></i>Generar Reporte
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Genera la sección de información del usuario
     */
    generarSeccionUsuario(detalle, idPrestamo, fechaPrestamo, inicialesUsuario, estadoPrestamo, infoRetraso) {
        const badgeRetraso = (infoRetraso.diasRetraso > 0 || infoRetraso.horasRetraso > 0) ? `
            <div>
                <span class="badge bg-warning fs-6 px-3 py-2">
                    <i class="ti ti-clock-exclamation me-1"></i>
                    ${infoRetraso.mostrarHoras ? 
                        `${infoRetraso.horasRetraso} hora${infoRetraso.horasRetraso !== 1 ? 's' : ''} de retraso` : 
                        `${infoRetraso.diasRetraso} día${infoRetraso.diasRetraso !== 1 ? 's' : ''} de retraso`
                    }
                </span>
            </div>
        ` : '';

        return `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-primary mb-3">
                        <i class="ti ti-user me-2"></i>Información del Usuario
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre Completo:</strong> <span>${detalle.usuario}</span></p>
                            <p><strong>Documento:</strong> <span>${detalle.documento}</span></p>
                            ${detalle.telefono ? `<p><strong>Teléfono:</strong> <span>${detalle.telefono}</span></p>` : ''}
                            ${detalle.email ? `<p><strong>Email:</strong> <span>${detalle.email}</span></p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <p><strong>Código Préstamo:</strong> <span>${detalle.codigo_prestamo}</span></p>
                            <p><strong>ID Préstamo:</strong> <span>#${idPrestamo}</span></p>
                            <p><strong>Fecha de Registro:</strong> <span>${HistorialUtils.formatearFecha(fechaPrestamo, HistorialConfig.formateo.formatoFechaCorta)}</span></p>
                            <p><strong>Hora de Inicio:</strong> <span>${HistorialUtils.formatearHora(fechaPrestamo)}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 100px; height: 100px; font-size: 2rem; font-weight: 600;">
                        ${inicialesUsuario}
                    </div>
                    <div class="mb-2">
                        <span class="badge ${estadoPrestamo.class} fs-6 px-3 py-2">
                            <i class="ti ${estadoPrestamo.icon} me-1"></i>${estadoPrestamo.badge}
                        </span>
                    </div>
                    ${badgeRetraso}
                </div>
            </div>
        `;
    },

    /**
     * Genera la sección de información del recurso
     */
    generarSeccionRecurso(detalle) {
        return `
            <h6 class="text-primary mb-3">
                <i class="ti ti-book me-2"></i>Recurso Prestado
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Título:</strong> <span>${detalle.recurso}</span></p>
                    ${detalle.autor ? `<p><strong>Autor(es):</strong> <span>${detalle.autor}</span></p>` : ''}
                    ${detalle.codigo_ejemplar ? `<p><strong>Código:</strong> <span>${detalle.codigo_ejemplar}</span></p>` : ''}
                    ${detalle.editorial ? `<p><strong>Editorial:</strong> <span>${detalle.editorial}</span></p>` : ''}
                </div>
                <div class="col-md-6">
                    ${detalle.anio_publicacion ? `<p><strong>Año Publicación:</strong> <span>${detalle.anio_publicacion}</span></p>` : ''}
                    ${detalle.categoria ? `<p><strong>Categoría:</strong> <span>${detalle.categoria}</span></p>` : ''}
                    ${detalle.estado_ejemplar ? `<p><strong>Estado del Ejemplar:</strong> <span>${detalle.estado_ejemplar}</span></p>` : ''}
                    ${detalle.ubicacion ? `<p><strong>Ubicación:</strong> <span>${detalle.ubicacion}</span></p>` : ''}
                </div>
            </div>
        `;
    },

    /**
     * Genera la sección de timeline
     */
    generarSeccionTimeline(fechaPrestamo, fechaLimite, fechaDevolucionReal, detalle, infoRetraso) {
        const claseDuracion = (infoRetraso.diasRetraso > 0 || infoRetraso.horasRetraso > 0) ? 'bg-danger' : 
                             (infoRetraso.diasRetraso === 0 && infoRetraso.horasRetraso === 0) ? 'bg-success' : 'bg-info';
        const colorTexto = (infoRetraso.diasRetraso > 0 || infoRetraso.horasRetraso > 0) ? 'text-danger' : 
                          (infoRetraso.diasRetraso === 0 && infoRetraso.horasRetraso === 0) ? 'text-success' : 'text-info';

        return `
            <h6 class="text-primary mb-3">
                <i class="ti ti-clock-hour-3 me-2"></i>Timeline del Préstamo
            </h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                        <h5 class="mb-1 text-primary">${HistorialUtils.formatearFecha(fechaPrestamo, HistorialConfig.formateo.formatoFechaCorta)}</h5>
                        <small class="text-muted">Fecha de Préstamo</small>
                        <p class="mb-0 mt-1 small">${HistorialUtils.formatearHora(fechaPrestamo)}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                        <h5 class="mb-1 text-warning">${HistorialUtils.formatearFecha(fechaLimite, HistorialConfig.formateo.formatoFechaCorta)}</h5>
                        <small class="text-muted">Fecha Límite</small>
                        <p class="mb-0 mt-1 small">${HistorialUtils.formatearHora(fechaLimite)}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 ${infoRetraso.diasRetraso > 0 ? 'bg-danger' : 'bg-success'} bg-opacity-10 rounded">
                        <h5 class="mb-1 ${infoRetraso.diasRetraso > 0 ? 'text-danger' : 'text-success'}">${HistorialUtils.formatearFecha(fechaDevolucionReal, HistorialConfig.formateo.formatoFechaCorta)}</h5>
                        <small class="text-muted">Fecha de Devolución</small>
                        <p class="mb-0 mt-1 small">${HistorialUtils.formatearHora(fechaDevolucionReal)}</p>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                        <h4 class="mb-1 text-info">${Math.abs(parseInt(detalle.dias_prestamo) || 0)}</h4>
                        <small class="text-muted">Días de Duración</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-3 ${claseDuracion} bg-opacity-10 rounded">
                        <h4 class="mb-1 ${colorTexto}">
                            ${infoRetraso.texto}
                        </h4>
                        <small class="text-muted">
                            ${infoRetraso.textoDetalle}
                        </small>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Genera la sección de observaciones y sanciones
     */
    generarSeccionObservaciones(detalle, infoRetraso) {
        const tieneObservaciones = (infoRetraso.diasRetraso > 0 || infoRetraso.horasRetraso > 0) || 
                                   detalle.sanciones || detalle.observaciones || detalle.observaciones_devolucion;
        
        if (!tieneObservaciones) return '';

        let html = `
            <hr>
            <h6 class="text-primary mb-3">
                <i class="ti ti-alert-triangle me-2"></i>Observaciones y Sanciones
            </h6>
        `;

        // Alerta de retraso
        if (infoRetraso.diasRetraso > 0 || infoRetraso.horasRetraso > 0) {
            const multaInfo = detalle.multa && parseInt(detalle.multa) > 0 ? 
                `<br><small class="text-danger"><strong>Multa aplicada:</strong> ${HistorialUtils.formatearMoneda(detalle.multa)}</small>` : '';
            
            html += `
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Retraso Detectado</strong><br>
                            <small>Se registró un retraso de ${infoRetraso.mostrarHoras ? 
                                `${infoRetraso.horasRetraso} hora${infoRetraso.horasRetraso !== 1 ? 's' : ''}` : 
                                `${infoRetraso.diasRetraso} día${infoRetraso.diasRetraso !== 1 ? 's' : ''}`
                            } en la devolución del recurso.</small>
                            ${multaInfo}
                        </div>
                    </div>
                </div>
            `;
        }

        // Sanciones del usuario
        if (detalle.sanciones && detalle.sanciones.trim()) {
            html += `
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-ban me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Sanciones del Usuario</strong>
                            ${detalle.total_sanciones ? ` (${detalle.total_sanciones} registrada(s))` : ''}<br>
                            <small>${detalle.sanciones}</small>
                            <br><small class="text-muted"><em>Se muestran las sanciones más recientes del usuario</em></small>
                        </div>
                    </div>
                </div>
            `;
        }

        // Observaciones del préstamo
        if (detalle.observaciones && detalle.observaciones.trim()) {
            html += `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-note me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Observaciones del Préstamo</strong><br>
                            <small>${detalle.observaciones}</small>
                        </div>
                    </div>
                </div>
            `;
        }

        // Observaciones de devolución
        if (detalle.observaciones_devolucion && detalle.observaciones_devolucion.trim()) {
            const fechaObs = detalle.fecha_observaciones_devolucion ? 
                `<br><em class="text-muted">Registrado: ${HistorialUtils.formatearFecha(detalle.fecha_observaciones_devolucion)}</em>` : '';
            
            html += `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-clipboard-check me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Observaciones de Devolución</strong><br>
                            <small>${detalle.observaciones_devolucion}</small>
                            ${fechaObs}
                        </div>
                    </div>
                </div>
            `;
        }

        return html;
    },

    /**
     * Confirma y elimina un registro individual
     * @param {number} registroId - ID del registro
     * @param {string} estadoFinal - Estado final del registro
     */
    async confirmarEliminacion(registroId, estadoFinal) {
        const esRechazado = estadoFinal === 'Rechazado';
        const tipoRegistro = esRechazado ? 'solicitud rechazada' : 'registro de préstamo';
        
        const confirmar = await HistorialModals.confirmar(
            '¿Estás seguro?',
            `
                <p>Se eliminará esta ${tipoRegistro} del historial.</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer</strong></p>
                ${!esRechazado ? '<p class="text-warning"><small><i class="ti ti-alert-circle"></i> Nota: Esto NO eliminará el préstamo original, solo lo ocultará del historial.</small></p>' : ''}
            `,
            'Sí, eliminar'
        );

        if (confirmar) {
            await this.eliminarRegistroHistorial(registroId, estadoFinal);
        }
    },

    /**
     * Elimina un registro del historial
     * @param {number} registroId - ID del registro
     * @param {string} estadoFinal - Estado final
     */
    async eliminarRegistroHistorial(registroId, estadoFinal) {
        try {
            HistorialModals.mostrarLoader('Eliminando...', 'Por favor espere');
            
            const tipo = HistorialUtils.determinarTipoRegistro(estadoFinal);
            const data = await HistorialAPI.eliminarRegistro(registroId, tipo);
            
            await HistorialModals.mostrarExito(
                '¡Eliminado!',
                data.message || 'El registro ha sido eliminado del historial'
            );
            
            this.recargarContenidoHistorial();
            
        } catch (error) {
            HistorialConfig.log('Error al eliminar:', error);
            HistorialModals.mostrarError(
                'Error de Conexión',
                error.message || 'No se pudo conectar con el servidor'
            );
        }
    },

    /**
     * Confirma y elimina todo el historial
     */
    async confirmarEliminarTodoHistorial() {
        const result = await Swal.fire({
            title: '⚠️ ¿Eliminar TODO el Historial?',
            html: `
                <div class="text-start">
                    <p class="text-danger fw-bold mb-3">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Esta es una acción EXTREMADAMENTE PELIGROSA
                    </p>
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">Se eliminarán:</h6>
                        <ul class="mb-0">
                            <li>Todos los préstamos devueltos del historial</li>
                            <li>Todas las solicitudes rechazadas</li>
                            <li>Todos los registros de renovaciones</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <h6 class="fw-bold mb-2">Se CONSERVARÁN:</h6>
                        <ul class="mb-0">
                            <li>Todas las sanciones de los usuarios</li>
                            <li>Los préstamos activos actuales</li>
                            <li>Las solicitudes pendientes</li>
                        </ul>
                    </div>
                    <p class="text-danger fw-bold mb-2">
                        <i class="ti ti-lock me-2"></i>
                        Esta acción NO se puede deshacer
                    </p>
                    <div class="form-group mt-3">
                        <label class="form-label fw-bold">
                            Para confirmar, escriba: <span class="text-danger">${HistorialConfig.textos.CONFIRMAR_ELIMINACION_TOTAL}</span>
                        </label>
                        <input type="text" id="confirmacionTexto" class="form-control" 
                               placeholder="Escriba aquí para confirmar">
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-2"></i>Sí, eliminar TODO',
            cancelButtonText: 'Cancelar',
            width: HistorialConfig.alertas.anchoModalGrande,
            preConfirm: () => {
                const confirmacion = document.getElementById('confirmacionTexto').value;
                if (confirmacion !== HistorialConfig.textos.CONFIRMAR_ELIMINACION_TOTAL) {
                    Swal.showValidationMessage(`Debe escribir exactamente: ${HistorialConfig.textos.CONFIRMAR_ELIMINACION_TOTAL}`);
                    return false;
                }
                return true;
            }
        });

        if (result.isConfirmed) {
            await this.eliminarTodoHistorial();
        }
    },

    /**
     * Elimina todo el historial
     */
    async eliminarTodoHistorial() {
        try {
            Swal.fire({
                title: 'Eliminando Historial Completo...',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-danger mb-3" role="status">
                            <span class="visually-hidden">Eliminando...</span>
                        </div>
                        <p class="text-muted">Por favor espere, esto puede tardar unos momentos...</p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false
            });

            const data = await HistorialAPI.eliminarTodoHistorial();
            
            let detallesHTML = '';
            if (data.detalles) {
                detallesHTML = `
                    <div class="alert alert-info mt-3">
                        <h6 class="fw-bold mb-2">Detalles:</h6>
                        <ul class="mb-0">
                            <li>Préstamos eliminados: ${data.detalles.prestamos || 0}</li>
                            <li>Solicitudes eliminadas: ${data.detalles.solicitudes || 0}</li>
                            <li>Renovaciones eliminadas: ${data.detalles.renovaciones || 0}</li>
                        </ul>
                    </div>
                `;
            }

            await Swal.fire({
                title: '✅ Historial Eliminado',
                html: `
                    <div class="text-start">
                        <p class="mb-2">${data.message}</p>
                        ${detallesHTML}
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Entendido'
            });
            
            this.recargarContenidoHistorial();
            
        } catch (error) {
            HistorialConfig.log('Error al eliminar todo:', error);
            HistorialModals.mostrarError(
                'Error de Conexión',
                error.message || 'No se pudo conectar con el servidor'
            );
        }
    },

    /**
     * Genera una sanción por retraso
     * @param {number} prestamoId - ID del préstamo
     * @param {string} nombreUsuario - Nombre del usuario
     * @param {number} horasRetraso - Horas de retraso
     */
    async generarSancion(prestamoId, nombreUsuario, horasRetraso) {
        try {
            const datosSancion = await HistorialModals.mostrarFormularioSancion(
                prestamoId, 
                nombreUsuario, 
                horasRetraso
            );

            if (datosSancion) {
                await this.procesarSancion(datosSancion);
            }
        } catch (error) {
            HistorialConfig.log('Error al generar sanción:', error);
            HistorialModals.mostrarError(
                'Error',
                'No se pudo generar la sanción'
            );
        }
    },

    /**
     * Procesa y guarda una sanción
     * @param {Object} datosSancion - Datos de la sanción
     */
    async procesarSancion(datosSancion) {
        try {
            HistorialModals.mostrarLoader('Procesando Sanción...', 'Registrando la sanción en el sistema');
            
            // TODO: Descomentar cuando el endpoint esté listo
            // const data = await HistorialAPI.crearSancion(datosSancion);
            
            // Simulación temporal
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            await Swal.fire({
                title: '✅ Sanción Registrada',
                html: `
                    <div class="text-start">
                        <p>La sanción ha sido registrada exitosamente:</p>
                        <hr>
                        <p><strong>Tipo:</strong> ${datosSancion.tipoSancion}</p>
                        <p><strong>Monto:</strong> ${HistorialUtils.formatearMoneda(datosSancion.monto)}</p>
                        <p><strong>Descripción:</strong> ${datosSancion.descripcion}</p>
                        ${datosSancion.observaciones ? `<p><strong>Observaciones:</strong> ${datosSancion.observaciones}</p>` : ''}
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Entendido'
            });
            
        } catch (error) {
            HistorialConfig.log('Error al procesar sanción:', error);
            HistorialModals.mostrarError(
                'Error de Conexión',
                error.message || 'No se pudo conectar con el servidor'
            );
        }
    },

    /**
     * Ver detalles de solicitud rechazada
     * @param {number} solicitudId - ID de la solicitud
     */
    async verDetalleRechazado(solicitudId) {
        try {
            HistorialModals.mostrarLoader('Cargando información...', 'Obteniendo detalles de la solicitud rechazada');
            
            const detalle = await HistorialAPI.obtenerDetalleSolicitud(solicitudId);
            
            HistorialModals.mostrarSolicitudRechazada(detalle);
            
        } catch (error) {
            HistorialConfig.log('Error al obtener solicitud:', error);
            HistorialModals.mostrarError(
                'Error de Conexión',
                error.message || 'No se pudo obtener la información de la solicitud'
            );
        }
    },

    /**
     * Genera un reporte individual
     * @param {number} registroId - ID del registro
     */
    generarReporte(registroId) {
        HistorialConfig.log('Generar reporte:', registroId);
        HistorialModals.mostrarExito(
            'Generando Reporte',
            'Se está generando el reporte del préstamo...',
            HistorialConfig.alertas.tiempoMensajeExito
        );
        // TODO: Implementar generación de reporte
    },

    /**
     * Ver línea de tiempo
     * @param {number} registroId - ID del registro
     */
    verLineaTiempo(registroId) {
        HistorialConfig.log('Ver línea de tiempo:', registroId);
        // TODO: Implementar vista de línea de tiempo
        Swal.fire({
            title: 'Línea de Tiempo del Préstamo',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    },

    /**
     * Recarga el contenido del historial de forma inteligente
     */
    async recargarContenidoHistorial() {
        if (HistorialUtils.esPanelAdministracion()) {
            // Estamos en el panel - Recargar via AJAX
            try {
                HistorialConfig.log('🔄 Recargando contenido del historial via AJAX...');
                
                HistorialModals.mostrarLoader('Actualizando...', 'Recargando el historial');
                
                const html = await HistorialAPI.recargarContenidoHistorial();
                
                const contenedor = document.getElementById('contenedor-principal');
                if (contenedor) {
                    contenedor.innerHTML = html;
                    HistorialModals.cerrar();
                    HistorialConfig.log('✅ Contenido del historial actualizado correctamente');
                }
                
            } catch (error) {
                HistorialConfig.log('❌ Error al recargar el contenido:', error);
                await HistorialModals.mostrarError(
                    'Error al actualizar',
                    'Se recargará la página completa',
                    null
                );
                location.reload();
            }
        } else {
            // No estamos en el panel - Recarga normal
            HistorialConfig.log('🔄 Recargando página completa...');
            location.reload();
        }
    },

    /**
     * Diagnóstico de conexión
     */
    async diagnosticarConexion() {
        HistorialConfig.log('Iniciando diagnóstico de conexión...');
        
        try {
            const conectado = await HistorialAPI.verificarConexion();
            
            if (conectado) {
                HistorialModals.mostrarExito(
                    'Diagnóstico de Conexión',
                    'La conexión al servidor está funcionando correctamente',
                    0
                );
            }
        } catch (error) {
            Swal.fire({
                title: 'Problema de Conectividad',
                html: `
                    <p>Se detectaron problemas de conexión:</p>
                    <ul class="text-start">
                        <li>Verifique su conexión a internet</li>
                        <li>Asegúrese de que el servidor esté funcionando</li>
                        <li>Revise la configuración de red</li>
                    </ul>
                    <hr>
                    <small class="text-muted">Error técnico: ${error.message}</small>
                `,
                icon: 'error'
            });
        }
    },

    /**
     * Recarga la página con confirmación
     */
    async recargarPagina() {
        const confirmar = await HistorialModals.confirmar(
            '¿Recargar página?',
            'Esto puede solucionar problemas temporales de conexión',
            'Sí, recargar'
        );

        if (confirmar) {
            location.reload();
        }
    }
};

// Hacer disponible globalmente
window.Historial = Historial;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    Historial.init();
});
