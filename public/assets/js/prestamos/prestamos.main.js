/**
 * Controlador Principal de Préstamos
 * Orquesta todos los módulos y expone funciones globales
 * Depende de: PrestamosAPI, PrestamosValidator, PrestamosModal, DateTimeUtils, PrestamosConstants
 */

if (typeof PrestamoController === 'undefined') {
    window.PrestamoController = class PrestamoController {
    constructor() {
        this.init();
    }

    /**
     * Inicializa el controlador
     */
    init() {
        // Inicializar tooltips
        if (typeof PrestamosModal !== 'undefined' && PrestamosModal.inicializarTooltips) {
            PrestamosModal.inicializarTooltips();
        }
    }

    /**
     * Muestra los detalles de un préstamo
     * @param {number} prestamoId - ID del préstamo
     */
    async verDetallePrestamo(prestamoId) {
        // Crear modal
        const modalHTML = PrestamosModal.generarHTMLModalDetalle();
        const modal = PrestamosModal.crearModal('modalDetallePrestamo', modalHTML);
        
        // Mostrar loading
        document.getElementById('loading-detalle-prestamo').style.display = 'block';
        document.getElementById('contenido-detalle-prestamo').style.display = 'none';
        modal.show();

        // Obtener datos
        const response = await PrestamosAPI.obtenerDetallePrestamo(prestamoId);
        
        if (response.success && response.data) {
            const detalle = response.data;
            
            // Actualizar modal con datos
            PrestamosModal.actualizarModalDetalle(detalle);
            PrestamosModal.configurarBotonesModalDetalle(modal, detalle, prestamoId);

            // Mostrar contenido
            document.getElementById('loading-detalle-prestamo').style.display = 'none';
            document.getElementById('contenido-detalle-prestamo').style.display = 'block';
        } else {
            modal.hide();
            this._mostrarError('Error', response.message || PrestamosConstants.MENSAJES.ERROR.DETALLE);
        }
    }

    /**
     * Renueva un préstamo
     * @param {number} prestamoId - ID del préstamo
     */
    async renovarPrestamo(prestamoId) {
        // Mostrar loading mientras obtenemos los datos
        this._mostrarLoading(PrestamosConstants.MENSAJES.LOADING.GENERAL, 'Obteniendo información del préstamo');
        
        // Obtener detalles del préstamo para prellenar el formulario
        const response = await PrestamosAPI.obtenerDetallePrestamo(prestamoId);
        
        if (!response.success || !response.data) {
            this._mostrarError('Error', 'No se pudo obtener la información del préstamo');
            return;
        }
        
        const detalle = response.data;
        
        // Preparar datos para el modal
        const fechaDefecto = detalle.fecha_vencimiento ? 
                            new Date(detalle.fecha_vencimiento).toISOString().split('T')[0] : 
                            DateTimeUtils.obtenerFechaActual();
        
        const horaInicioDefecto = detalle.fecha_prestamo ? 
                                 new Date(detalle.fecha_prestamo).toTimeString().slice(0, 5) : 
                                 PrestamosConstants.HORARIOS.HORA_MIN_FORMATO;
        
        const horaFinDefecto = detalle.fecha_vencimiento ? 
                              new Date(detalle.fecha_vencimiento).toTimeString().slice(0, 5) : 
                              PrestamosConstants.HORARIOS.HORA_MAX_FORMATO;
        
        // Mostrar modal de renovación
        this._mostrarModalRenovacion(prestamoId, fechaDefecto, horaInicioDefecto, horaFinDefecto);
    }

    /**
     * Muestra el modal de renovación
     * @private
     */
    _mostrarModalRenovacion(prestamoId, fechaDefecto, horaInicioDefecto, horaFinDefecto) {
        const fechaHoy = DateTimeUtils.obtenerFechaActual();
        
        Swal.fire({
            title: '¿Renovar Préstamo?',
            html: `
                <p class="mb-3 text-start">Selecciona la nueva fecha y horarios del préstamo renovado:</p>
                
                <div class="mb-3 text-start">
                    <label for="nueva_fecha_devolucion" class="form-label fw-bold">
                        <i class="ti ti-calendar me-1"></i>Fecha:
                    </label>
                    <input type="date" 
                           id="nueva_fecha_devolucion" 
                           class="form-control" 
                           min="${fechaHoy}"
                           value="${fechaDefecto}">
                    <div class="invalid-feedback" style="display: none;"></div>
                    <small class="text-muted">Solo días de lunes a viernes</small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nueva_hora_inicio" class="form-label fw-bold">
                            <i class="ti ti-clock me-1"></i>Hora de inicio:
                        </label>
                        <input type="time" 
                               id="nueva_hora_inicio" 
                               class="form-control" 
                               min="08:00"
                               max="12:59"
                               value="${horaInicioDefecto}">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <small class="text-muted">8:00 AM - 12:59 PM</small>
                    </div>
                    <div class="col-md-6">
                        <label for="nueva_hora_devolucion" class="form-label fw-bold">
                            <i class="ti ti-clock-off me-1"></i>Hora de fin:
                        </label>
                        <input type="time" 
                               id="nueva_hora_devolucion" 
                               class="form-control" 
                               min="08:01"
                               max="13:00"
                               value="${horaFinDefecto}">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <small class="text-muted">8:01 AM - 1:00 PM</small>
                    </div>
                </div>
                
                <div class="mb-3 text-start">
                    <div class="alert alert-light border">
                        <strong><i class="ti ti-hourglass me-1"></i>Duración del préstamo:</strong>
                        <span id="duracion_renovacion" class="text-primary fw-bold">-</span>
                    </div>
                </div>
                
                <div class="mb-3 text-start">
                    <label for="motivo_renovacion" class="form-label fw-bold">
                        <i class="ti ti-message me-1"></i>Motivo (opcional):
                    </label>
                    <textarea id="motivo_renovacion" 
                              class="form-control" 
                              placeholder="Describe el motivo de la renovación..." 
                              rows="2"></textarea>
                </div>
                
                <div class="alert alert-info text-start mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    <small>Los préstamos se pueden renovar múltiples veces según sea necesario.</small>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-refresh me-1"></i>Renovar préstamo',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.warning,
            cancelButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.cancel,
            width: '550px',
            didOpen: () => {
                // Configurar validación en tiempo real
                const elementos = {
                    fechaInput: document.getElementById('nueva_fecha_devolucion'),
                    horaInicioInput: document.getElementById('nueva_hora_inicio'),
                    horaFinInput: document.getElementById('nueva_hora_devolucion'),
                    duracionElement: document.getElementById('duracion_renovacion')
                };
                
                PrestamosValidator.configurarValidacionTiempoReal(elementos);
            },
            preConfirm: () => {
                const nuevaFechaDevolucion = document.getElementById('nueva_fecha_devolucion').value;
                const nuevaHoraInicio = document.getElementById('nueva_hora_inicio').value;
                const nuevaHoraFin = document.getElementById('nueva_hora_devolucion').value;
                const motivo = document.getElementById('motivo_renovacion').value;
                
                // Validar usando el módulo de validación
                if (!PrestamosValidator.validarFormularioRenovacion({ esValidacionFinal: true })) {
                    Swal.showValidationMessage(PrestamosConstants.MENSAJES.ERROR.VALIDACION);
                    return false;
                }
                
                return {
                    nueva_fecha_prestamo: DateTimeUtils.combinarFechaHora(nuevaFechaDevolucion, nuevaHoraInicio),
                    nueva_fecha_devolucion: DateTimeUtils.combinarFechaHora(nuevaFechaDevolucion, nuevaHoraFin),
                    motivo: motivo
                };
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                this._mostrarLoading(
                    PrestamosConstants.MENSAJES.LOADING.GENERAL,
                    PrestamosConstants.MENSAJES.LOADING.RENOVAR
                );

                // Enviar datos
                const datos = {
                    idprestamo: prestamoId,
                    nueva_fecha_prestamo: result.value.nueva_fecha_prestamo,
                    nueva_fecha_devolucion: result.value.nueva_fecha_devolucion,
                    motivo: result.value.motivo || ''
                };
                
                const response = await PrestamosAPI.renovarPrestamo(datos);
                
                if (response.success) {
                    Swal.fire({
                        title: 'Préstamo Renovado',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>✅ ${response.message}</strong></p>
                                <hr>
                                <p class="mb-1"><i class="ti ti-calendar-event me-2"></i><strong>Nueva fecha de devolución:</strong> ${response.nueva_fecha_devolucion}</p>
                                <p class="mb-1"><i class="ti ti-refresh me-2"></i><strong>Total de renovaciones:</strong> ${response.renovaciones_totales}</p>
                                ${response.dias_extension ? `<p class="mb-0"><i class="ti ti-calendar-plus me-2"></i><strong>Extensión:</strong> ${response.dias_extension} días adicionales</p>` : ''}
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Entendido',
                        timer: PrestamosConstants.SWAL_CONFIG.TIMER.LONG
                    }).then(() => {
                        this.recargarContenidoPrestamos();
                    });
                } else {
                    this._mostrarError('Error al Renovar', response.message);
                }
            }
        });
    }

    /**
     * Procesa la devolución de un préstamo
     * @param {number} prestamoId - ID del préstamo
     */
    async procesarDevolucion(prestamoId) {
        // Mostrar loading mientras cargamos tipos de sanción
        this._mostrarLoading(
            PrestamosConstants.MENSAJES.LOADING.GENERAL,
            PrestamosConstants.MENSAJES.LOADING.TIPOS_SANCION
        );
        
        // Cargar tipos de sanción
        const tiposSancionResponse = await PrestamosAPI.obtenerTiposSancion();
        const tiposSancion = tiposSancionResponse.data || [];
        
        this._mostrarModalDevolucion(prestamoId, tiposSancion);
    }

    /**
     * Muestra el modal de devolución
     * @private
     */
    _mostrarModalDevolucion(prestamoId, tiposSancion) {
        // Construir opciones de tipo de sanción
        let opcionesTipoSancion = '<option value="">Seleccionar tipo de incidencia...</option>';
        
        if (tiposSancion && tiposSancion.length > 0) {
            tiposSancion.forEach(tipo => {
                opcionesTipoSancion += `<option value="${tipo.idtiposancion}">${tipo.tiposancion}</option>`;
            });
        }
        
        Swal.fire({
            title: 'Procesar Devolución',
            html: `
                <p class="mb-3 text-start">Selecciona el estado del material devuelto:</p>
                
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">
                        <i class="ti ti-clipboard-check me-1"></i>Estado del Material:
                    </label>
                    <select id="estado_devolucion" class="form-select form-select-lg">
                        <option value="bueno" selected>✅ Devuelto en Buen Estado</option>
                        <option value="con_incidencia">⚠️ Devuelto con Incidencia (Daño/Pérdida)</option>
                    </select>
                    <small class="text-muted d-block mt-1">
                        <i class="ti ti-info-circle me-1"></i>Haz clic para ver las opciones disponibles
                    </small>
                </div>
                
                <div id="seccion_incidencia" class="mb-3 text-start" style="display: none;">
                    <label for="tipo_sancion" class="form-label fw-bold">
                        <i class="ti ti-alert-triangle me-1"></i>Tipo de Incidencia<span class="text-danger">*</span>:
                    </label>
                    <select id="tipo_sancion" class="form-select mb-2">
                        ${opcionesTipoSancion}
                    </select>
                    
                    <div id="detalle_incidencia_container" class="mt-2" style="display: none;">
                        <label for="detalle_incidencia" class="form-label fw-bold">
                            <i class="ti ti-file-description me-1"></i>Detalle Específico<span class="text-danger">*</span>:
                        </label>
                        <select id="detalle_incidencia" class="form-select mb-2">
                            <option value="">Seleccionar detalle...</option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="observaciones_devolucion" class="form-label fw-bold">
                            <i class="ti ti-message me-1"></i>Observaciones (opcional):
                        </label>
                        <textarea id="observaciones_devolucion" 
                                  class="form-control" 
                                  placeholder="Puedes agregar detalles adicionales sobre la incidencia, si lo consideras necesario..." 
                                  rows="4"></textarea>
                        <small class="text-muted">Este campo es opcional</small>
                    </div>
                    
                    <div class="alert alert-warning mb-0 mt-3">
                        <small><i class="ti ti-info-circle me-1"></i><strong>Importante:</strong> Se aplicará una sanción según el tipo de incidencia registrada</small>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i>Procesar Devolución',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.success,
            cancelButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.cancel,
            width: '600px',
            didOpen: () => {
                this._configurarModalDevolucion();
            },
            preConfirm: () => {
                const estadoDevolucion = document.getElementById('estado_devolucion').value;
                const tipoSancion = document.getElementById('tipo_sancion')?.value || '';
                const detalleIncidencia = document.getElementById('detalle_incidencia')?.value || '';
                const observaciones = document.getElementById('observaciones_devolucion').value.trim();
                
                // Validar con el módulo de validación
                if (estadoDevolucion === 'con_incidencia') {
                    const detalleContainer = document.getElementById('detalle_incidencia_container');
                    const detalleVisible = detalleContainer.style.display !== 'none';
                    
                    const validacion = PrestamosValidator.validarDevolucionConIncidencia(
                        tipoSancion,
                        detalleIncidencia,
                        detalleVisible
                    );
                    
                    if (!validacion.valido) {
                        Swal.showValidationMessage(validacion.mensaje);
                        return false;
                    }
                }
                
                return {
                    estado_devolucion: estadoDevolucion,
                    idtiposancion: tipoSancion,
                    detalle_incidencia: detalleIncidencia,
                    observaciones: observaciones || ''
                };
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                const { estado_devolucion, idtiposancion, detalle_incidencia, observaciones } = result.value;
                
                // Mostrar loading
                this._mostrarLoading(
                    PrestamosConstants.MENSAJES.LOADING.GENERAL,
                    estado_devolucion === 'con_incidencia' ? 
                        'Registrando devolución con incidencia' : 
                        PrestamosConstants.MENSAJES.LOADING.DEVOLUCION
                );

                // Procesar devolución
                const datos = {
                    idprestamo: prestamoId,
                    estado_devolucion,
                    idtiposancion,
                    detalle_incidencia,
                    observaciones
                };
                
                const response = await PrestamosAPI.procesarDevolucion(datos);
                
                if (response.success) {
                    let icon = 'success';
                    let title = 'Devolución Procesada';
                    let htmlContent = response.message;
                    
                    // Personalizar mensaje según el estado
                    if (estado_devolucion === 'con_incidencia') {
                        icon = 'warning';
                        title = 'Devolución con Incidencia Registrada';
                        if (response.sancion_aplicada) {
                            htmlContent += '<br><br><div class="alert alert-warning mt-2 mb-0"><i class="ti ti-alert-triangle me-2"></i>Se ha generado una sanción: <strong>' + 
                                          (response.tipo_sancion || 'Sanción aplicada') + '</strong></div>';
                        }
                    } else if (response.con_retraso && response.sancion_aplicada) {
                        icon = 'warning';
                        title = 'Devolución con Retraso';
                        htmlContent += '<br><br><div class="alert alert-warning mt-2 mb-0"><i class="ti ti-clock me-2"></i>Se ha generado una sanción por retraso en la devolución</div>';
                    }
                    
                    Swal.fire({
                        title: title,
                        html: htmlContent,
                        icon: icon,
                        timer: (estado_devolucion === 'bueno' && !response.con_retraso) ? 
                               PrestamosConstants.SWAL_CONFIG.TIMER.MEDIUM : null,
                        showConfirmButton: true,
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        this.recargarContenidoPrestamos();
                    });
                } else {
                    this._mostrarError('Error', response.message);
                }
            }
        });
    }

    /**
     * Configura el comportamiento del modal de devolución
     * @private
     */
    _configurarModalDevolucion() {
        const estadoSelect = document.getElementById('estado_devolucion');
        const seccionIncidencia = document.getElementById('seccion_incidencia');
        const tipoSancionSelect = document.getElementById('tipo_sancion');
        const detalleIncidenciaContainer = document.getElementById('detalle_incidencia_container');
        const detalleIncidenciaSelect = document.getElementById('detalle_incidencia');
        
        // Manejar cambio de estado del material
        estadoSelect.addEventListener('change', function() {
            seccionIncidencia.style.display = this.value === 'con_incidencia' ? 'block' : 'none';
            if (this.value !== 'con_incidencia') {
                detalleIncidenciaContainer.style.display = 'none';
            }
        });
        
        // Manejar cambio de tipo de sanción
        tipoSancionSelect.addEventListener('change', function() {
            const tipoTexto = this.options[this.selectedIndex]?.text.toLowerCase() || '';
            
            // Limpiar opciones anteriores
            detalleIncidenciaSelect.innerHTML = '<option value="">Seleccionar detalle...</option>';
            
            // Determinar qué detalles mostrar
            let detalles = [];
            const DETALLES = PrestamosConstants.DETALLES_INCIDENCIAS;
            
            if (tipoTexto.includes('daño')) {
                detalles = DETALLES.daño;
            } else if (tipoTexto.includes('pérdida') || tipoTexto.includes('perdida')) {
                detalles = DETALLES.pérdida;
            } else if (tipoTexto.includes('retraso')) {
                detalles = DETALLES.retraso;
            } else if (tipoTexto.includes('incumplimiento') || tipoTexto.includes('norma')) {
                detalles = DETALLES.incumplimiento;
            } else if (tipoTexto.includes('comportamiento')) {
                detalles = DETALLES.comportamiento;
            }
            
            // Agregar opciones
            if (detalles.length > 0) {
                detalles.forEach(detalle => {
                    const option = document.createElement('option');
                    option.value = detalle.value;
                    option.textContent = detalle.text;
                    detalleIncidenciaSelect.appendChild(option);
                });
                detalleIncidenciaContainer.style.display = 'block';
            } else {
                detalleIncidenciaContainer.style.display = 'none';
            }
        });
    }

    /**
     * Recarga el contenido de préstamos
     */
    async recargarContenidoPrestamos() {
        const contenedorPrincipal = document.getElementById('contenedor-principal');
        
        if (contenedorPrincipal) {
            // Estamos en el panel de administración (contexto AJAX)
            
            // Mostrar indicador de carga
            contenedorPrincipal.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Actualizando préstamos...</p>
                    </div>
                </div>
            `;
            
            // Recargar el contenido via AJAX
            const response = await PrestamosAPI.recargarContenidoPrestamos();
            
            if (response.success) {
                contenedorPrincipal.innerHTML = response.html;
            } else {
                contenedorPrincipal.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        Error al recargar el contenido. Por favor, intenta nuevamente.
                    </div>
                `;
            }
        } else {
            // Estamos fuera del panel (página independiente)
            window.location.reload();
        }
    }

    /**
     * Busca usuarios
     */
    async buscarUsuario() {
        const termino = document.getElementById('buscar_usuario').value.trim();
        const resultadoDiv = document.getElementById('resultado_busqueda_usuario');
        
        if (!termino) {
            Swal.showValidationMessage('Ingresa un término de búsqueda');
            return;
        }
        
        resultadoDiv.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm" role="status"></div> Buscando...</div>';
        resultadoDiv.style.display = 'block';
        
        const response = await PrestamosAPI.buscarUsuarios(termino);
        
        if (response.success && response.usuarios && response.usuarios.length > 0) {
            let html = '<div class="list-group">';
            response.usuarios.forEach(usuario => {
                html += `
                    <a href="#" class="list-group-item list-group-item-action" onclick="seleccionarUsuario(${usuario.idusuario}, '${usuario.nombre_completo}', '${usuario.documento}'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${usuario.nombre_completo}</h6>
                            <small class="badge bg-primary">${usuario.nivel_acceso || 'Usuario'}</small>
                        </div>
                        <small class="text-muted">${usuario.tipo_documento || 'Doc'}: ${usuario.documento}</small>
                    </a>
                `;
            });
            html += '</div>';
            resultadoDiv.innerHTML = html;
        } else {
            resultadoDiv.innerHTML = '<div class="alert alert-warning mb-0"><i class="ti ti-alert-circle me-2"></i>No se encontraron usuarios</div>';
        }
    }

    /**
     * Busca recursos disponibles
     */
    async buscarRecurso() {
        const termino = document.getElementById('buscar_recurso').value.trim();
        const resultadoDiv = document.getElementById('resultado_busqueda_recurso');
        
        if (!termino) {
            Swal.showValidationMessage('Ingresa un término de búsqueda');
            return;
        }
        
        resultadoDiv.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm" role="status"></div> Buscando...</div>';
        resultadoDiv.style.display = 'block';
        
        const response = await PrestamosAPI.buscarRecursosDisponibles(termino);
        
        if (response.success && response.recursos && response.recursos.length > 0) {
            let html = '<div class="list-group">';
            response.recursos.forEach(recurso => {
                html += `
                    <a href="#" class="list-group-item list-group-item-action" onclick="seleccionarRecurso(${recurso.idejemplar}, '${recurso.titulo}', '${recurso.codigo_ejemplar}'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${recurso.titulo}</h6>
                            <small class="badge bg-success">Disponible</small>
                        </div>
                        <small class="text-muted">Código: ${recurso.codigo_ejemplar} | ${recurso.tipo_recurso || 'Físico'}</small>
                    </a>
                `;
            });
            html += '</div>';
            resultadoDiv.innerHTML = html;
        } else {
            resultadoDiv.innerHTML = '<div class="alert alert-warning mb-0"><i class="ti ti-alert-circle me-2"></i>No se encontraron recursos disponibles</div>';
        }
    }

    /**
     * Selecciona un usuario de la búsqueda
     */
    seleccionarUsuario(idusuario, nombre, documento) {
        document.getElementById('idusuario_prestamo').value = idusuario;
        document.getElementById('nombre_usuario_sel').textContent = nombre;
        document.getElementById('doc_usuario_sel').textContent = 'Documento: ' + documento;
        document.getElementById('usuario_seleccionado').style.display = 'block';
        document.getElementById('resultado_busqueda_usuario').style.display = 'none';
        document.getElementById('buscar_usuario').value = '';
    }

    /**
     * Selecciona un recurso de la búsqueda
     */
    seleccionarRecurso(idejemplar, titulo, codigo) {
        document.getElementById('idejemplar_prestamo').value = idejemplar;
        document.getElementById('nombre_recurso_sel').textContent = titulo;
        document.getElementById('codigo_recurso_sel').textContent = 'Código: ' + codigo;
        document.getElementById('recurso_seleccionado').style.display = 'block';
        document.getElementById('resultado_busqueda_recurso').style.display = 'none';
        document.getElementById('buscar_recurso').value = '';
    }

    /**
     * Limpia la selección de usuario
     */
    limpiarUsuarioSeleccionado() {
        document.getElementById('idusuario_prestamo').value = '';
        document.getElementById('usuario_seleccionado').style.display = 'none';
    }

    /**
     * Limpia la selección de recurso
     */
    limpiarRecursoSeleccionado() {
        document.getElementById('idejemplar_prestamo').value = '';
        document.getElementById('recurso_seleccionado').style.display = 'none';
    }

    /**
     * Muestra el modal para crear un nuevo préstamo
     */
    mostrarModalNuevoPrestamo() {
        const fechaHoy = DateTimeUtils.obtenerFechaActual();
        
        Swal.fire({
            title: '<i class="ti ti-bookmark-plus me-2"></i>Nuevo Préstamo',
            html: `
                <div class="text-start">
                    <p class="text-muted mb-4">Completa la información para registrar un nuevo préstamo</p>
                    
                    <!-- Búsqueda de Usuario -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-user-search me-2"></i>Buscar Usuario
                        </h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" 
                                   id="buscar_usuario" 
                                   class="form-control" 
                                   placeholder="Buscar por nombre, documento o usuario...">
                            <button class="btn btn-outline-primary" type="button" onclick="buscarUsuario()">
                                <i class="ti ti-search me-1"></i>Buscar
                            </button>
                        </div>
                        <div id="resultado_busqueda_usuario" class="mt-2" style="display: none;">
                            <!-- Resultados de búsqueda -->
                        </div>
                        <div id="usuario_seleccionado" class="alert alert-info mt-2" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Usuario seleccionado:</strong><br>
                                    <span id="nombre_usuario_sel"></span><br>
                                    <small id="doc_usuario_sel" class="text-muted"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarUsuarioSeleccionado()">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="idusuario_prestamo" value="">
                    </div>

                    <hr>

                    <!-- Búsqueda de Recurso -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-book-2 me-2"></i>Buscar Recurso
                        </h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" 
                                   id="buscar_recurso" 
                                   class="form-control" 
                                   placeholder="Buscar por título, ISBN, código...">
                            <button class="btn btn-outline-primary" type="button" onclick="buscarRecurso()">
                                <i class="ti ti-search me-1"></i>Buscar
                            </button>
                        </div>
                        <div id="resultado_busqueda_recurso" class="mt-2" style="display: none;">
                            <!-- Resultados de búsqueda -->
                        </div>
                        <div id="recurso_seleccionado" class="alert alert-success mt-2" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Recurso seleccionado:</strong><br>
                                    <span id="nombre_recurso_sel"></span><br>
                                    <small id="codigo_recurso_sel" class="text-muted"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarRecursoSeleccionado()">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="idejemplar_prestamo" value="">
                    </div>

                    <hr>

                    <!-- Fecha y Horarios de Préstamo -->
                    <div class="mb-3">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-calendar-time me-2"></i>Fecha y Horarios de Préstamo
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-calendar me-1"></i>Fecha de uso:
                                </label>
                                <input type="date" 
                                       id="fecha_prestamo" 
                                       class="form-control"
                                       min="${fechaHoy}"
                                       value="${fechaHoy}">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Solo días de lunes a viernes</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_inicio_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-clock me-1"></i>Hora de inicio:
                                </label>
                                <input type="time" 
                                       id="hora_inicio_prestamo" 
                                       class="form-control"
                                       min="08:00"
                                       max="12:59"
                                       value="08:00">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Entre 8:00 AM y 12:59 PM</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_fin_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-clock me-1"></i>Hora de fin:
                                </label>
                                <input type="time" 
                                       id="hora_fin_prestamo" 
                                       class="form-control"
                                       min="08:01"
                                       max="13:00"
                                       value="13:00">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Entre 8:01 AM y 1:00 PM</small>
                            </div>
                        </div>
                        
                        <!-- Duración del préstamo -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-light border mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clock text-primary me-2"></i>
                                        <div>
                                            <strong>Duración del préstamo:</strong> 
                                            <span id="duracion_prestamo" class="text-success fw-bold">5 horas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label for="observaciones_prestamo" class="form-label fw-bold">
                            <i class="ti ti-message me-1"></i>Observaciones (opcional):
                        </label>
                        <textarea id="observaciones_prestamo" 
                                  class="form-control" 
                                  placeholder="Escribe cualquier observación sobre el préstamo..." 
                                  rows="2"></textarea>
                    </div>

                </div>
            `,
            width: '700px',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i>Crear Préstamo',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.confirm,
            cancelButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.cancel,
            showLoaderOnConfirm: true,
            didOpen: () => {
                // Configurar validación en tiempo real
                const elementos = {
                    fechaInput: document.getElementById('fecha_prestamo'),
                    horaInicioInput: document.getElementById('hora_inicio_prestamo'),
                    horaFinInput: document.getElementById('hora_fin_prestamo'),
                    duracionElement: document.getElementById('duracion_prestamo')
                };
                
                PrestamosValidator.configurarValidacionTiempoReal(elementos);
            },
            preConfirm: async () => {
                const idusuario = document.getElementById('idusuario_prestamo').value;
                const idejemplar = document.getElementById('idejemplar_prestamo').value;
                const fechaPrestamo = document.getElementById('fecha_prestamo').value;
                const horaInicio = document.getElementById('hora_inicio_prestamo').value;
                const horaFin = document.getElementById('hora_fin_prestamo').value;
                const observaciones = document.getElementById('observaciones_prestamo').value;
                
                // Validar usuario
                if (!PrestamosValidator.validarUsuarioSeleccionado(idusuario)) {
                    Swal.showValidationMessage(PrestamosConstants.MENSAJES.VALIDACION.USUARIO_REQUERIDO);
                    return false;
                }
                
                // Validar recurso
                if (!PrestamosValidator.validarRecursoSeleccionado(idejemplar)) {
                    Swal.showValidationMessage(PrestamosConstants.MENSAJES.VALIDACION.RECURSO_REQUERIDO);
                    return false;
                }
                
                // Validar formulario
                if (!PrestamosValidator.validarFormularioPrestamo({ esValidacionFinal: true })) {
                    Swal.showValidationMessage(PrestamosConstants.MENSAJES.ERROR.VALIDACION);
                    return false;
                }
                
                // Crear el préstamo
                const datos = {
                    idusuario,
                    idejemplar,
                    fechaPrestamo,
                    horaInicio,
                    horaFin,
                    observaciones: observaciones || ''
                };
                
                const response = await PrestamosAPI.crearPrestamo(datos);
                
                if (!response.success) {
                    throw new Error(response.message || PrestamosConstants.MENSAJES.ERROR.CREAR);
                }
                
                return response;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    title: '¡Préstamo Creado!',
                    html: `
                        <div class="text-start">
                            <p class="mb-2"><strong>✅ ${result.value.message}</strong></p>
                            <hr>
                            <p class="mb-1"><i class="ti ti-bookmark me-2"></i><strong>Código:</strong> ${result.value.codigo_prestamo || 'N/A'}</p>
                            <p class="mb-1"><i class="ti ti-calendar-event me-2"></i><strong>Fecha de devolución:</strong> ${result.value.fecha_devolucion || 'N/A'}</p>
                            <p class="mb-0"><i class="ti ti-user me-2"></i><strong>Usuario:</strong> ${result.value.usuario || 'N/A'}</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Entendido',
                    timer: PrestamosConstants.SWAL_CONFIG.TIMER.LONG
                }).then(() => {
                    this.recargarContenidoPrestamos();
                });
            }
        });
    }

    /**
     * Cancela un préstamo
     * @param {number} prestamoId - ID del préstamo a cancelar
     */
    async cancelarPrestamo(prestamoId) {
        Swal.fire({
            title: '¿Cancelar Préstamo?',
            html: `
                <p class="mb-3 text-start">Esta acción no se puede deshacer. El recurso volverá a estar disponible.</p>
                <div class="mb-3 text-start">
                    <label for="motivo_cancelacion" class="form-label fw-bold">
                        <i class="ti ti-message me-1"></i>Motivo de cancelación (opcional):
                    </label>
                    <textarea id="motivo_cancelacion" 
                              class="form-control" 
                              placeholder="Escribe el motivo por el cual se cancela el préstamo..." 
                              rows="3"></textarea>
                    <small class="text-muted">Puedes dejar este campo vacío si no deseas especificar un motivo.</small>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <small><strong>Advertencia:</strong> Esta acción es irreversible y el préstamo quedará marcado como cancelado.</small>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-x me-1"></i>Sí, cancelar préstamo',
            cancelButtonText: '<i class="ti ti-arrow-back me-1"></i>No cancelar',
            confirmButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.danger,
            cancelButtonColor: PrestamosConstants.SWAL_CONFIG.COLORS.cancel,
            width: '550px',
            preConfirm: () => {
                const motivo = document.getElementById('motivo_cancelacion').value.trim();
                return {
                    motivo: motivo || ''
                };
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                this._mostrarLoading(
                    PrestamosConstants.MENSAJES.LOADING.GENERAL,
                    'Cancelando préstamo'
                );

                // Enviar solicitud
                const response = await PrestamosAPI.cancelarPrestamo(prestamoId, result.value.motivo);
                
                if (response.success) {
                    Swal.fire({
                        title: 'Préstamo Cancelado',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>✅ ${response.message || 'El préstamo ha sido cancelado correctamente'}</strong></p>
                                ${result.value.motivo ? `
                                    <hr>
                                    <p class="mb-1"><i class="ti ti-message-circle me-2"></i><strong>Motivo registrado:</strong></p>
                                    <p class="text-muted mb-0 small">${result.value.motivo}</p>
                                ` : ''}
                            </div>
                        `,
                        icon: 'success',
                        timer: PrestamosConstants.SWAL_CONFIG.TIMER.MEDIUM,
                        showConfirmButton: true,
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        this.recargarContenidoPrestamos();
                    });
                } else {
                    this._mostrarError('Error al Cancelar', response.message);
                }
            }
        });
    }

    /**
     * MÉTODOS AUXILIARES PRIVADOS
     */

    /**
     * Muestra un mensaje de loading con SweetAlert2
     * @private
     */
    _mostrarLoading(titulo, texto) {
        Swal.fire({
            title: titulo,
            text: texto,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Muestra un mensaje de error con SweetAlert2
     * @private
     */
    _mostrarError(titulo, texto) {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }
    };
}

// ====================================
// INICIALIZACIÓN INMEDIATA
// ====================================

// Inicializar el controlador inmediatamente cuando se carga el script
if (!window.prestamoController && typeof PrestamoController !== 'undefined') {
    window.prestamoController = new PrestamoController();
}

// Asegurar inicialización cuando el DOM esté listo (por si acaso)
document.addEventListener('DOMContentLoaded', () => {
    if (!window.prestamoController && typeof PrestamoController !== 'undefined') {
        window.prestamoController = new PrestamoController();
    }
});

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrestamoController;
}

// ====================================
// FUNCIONES GLOBALES PARA ONCLICK
// ====================================

/**
 * Funciones globales que delegan al controlador
 * Estas funciones permiten que los botones onclick funcionen correctamente
 * Solo se definen si no existen para evitar redeclaraciones
 */
if (!window.verDetallePrestamo) {
    window.verDetallePrestamo = function(prestamoId) {
        if (window.prestamoController) {
            window.prestamoController.verDetallePrestamo(prestamoId);
        }
    };
}

if (!window.renovarPrestamo) {
    window.renovarPrestamo = function(prestamoId) {
        if (window.prestamoController) {
            window.prestamoController.renovarPrestamo(prestamoId);
        }
    };
}

if (!window.procesarDevolucion) {
    window.procesarDevolucion = function(prestamoId) {
        if (window.prestamoController) {
            window.prestamoController.procesarDevolucion(prestamoId);
        }
    };
}

if (!window.cancelarPrestamo) {
    window.cancelarPrestamo = function(prestamoId) {
        if (window.prestamoController) {
            window.prestamoController.cancelarPrestamo(prestamoId);
        }
    };
}

if (!window.mostrarModalNuevoPrestamo) {
    window.mostrarModalNuevoPrestamo = function() {
        if (window.prestamoController) {
            window.prestamoController.mostrarModalNuevoPrestamo();
        }
    };
}

if (!window.buscarUsuario) {
    window.buscarUsuario = function() {
        if (window.prestamoController) {
            window.prestamoController.buscarUsuario();
        }
    };
}

if (!window.buscarRecurso) {
    window.buscarRecurso = function() {
        if (window.prestamoController) {
            window.prestamoController.buscarRecurso();
        }
    };
}

if (!window.seleccionarUsuario) {
    window.seleccionarUsuario = function(idusuario, nombre, documento) {
        if (window.prestamoController) {
            window.prestamoController.seleccionarUsuario(idusuario, nombre, documento);
        }
    };
}

if (!window.seleccionarRecurso) {
    window.seleccionarRecurso = function(idejemplar, titulo, codigo) {
        if (window.prestamoController) {
            window.prestamoController.seleccionarRecurso(idejemplar, titulo, codigo);
        }
    };
}

if (!window.limpiarUsuarioSeleccionado) {
    window.limpiarUsuarioSeleccionado = function() {
        if (window.prestamoController) {
            window.prestamoController.limpiarUsuarioSeleccionado();
        }
    };
}

if (!window.limpiarRecursoSeleccionado) {
    window.limpiarRecursoSeleccionado = function() {
        if (window.prestamoController) {
            window.prestamoController.limpiarRecursoSeleccionado();
        }
    };
}

if (!window.recargarContenidoPrestamos) {
    window.recargarContenidoPrestamos = function() {
        if (window.prestamoController) {
            window.prestamoController.recargarContenidoPrestamos();
        }
    };
}
