/**
 * Módulo de Modales y UI para Préstamos
 * Gestiona todos los modales y componentes de interfaz
 * Depende de: PrestamosAPI, PrestamosValidator, DateTimeUtils, PrestamosConstants
 */

window.PrestamosModal = window.PrestamosModal || {
    /**
     * Inicializa los tooltips de Bootstrap
     */
    inicializarTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },

    /**
     * Crea o inicializa un modal de Bootstrap
     * @param {string} modalId - ID del modal
     * @param {string} contenidoHTML - HTML del contenido
     * @returns {bootstrap.Modal} Instancia del modal
     */
    crearModal(modalId, contenidoHTML) {
        let modalElement = document.getElementById(modalId);
        
        if (!modalElement) {
            modalElement = document.createElement('div');
            modalElement.id = modalId;
            modalElement.className = 'modal fade';
            modalElement.tabIndex = -1;
            modalElement.innerHTML = contenidoHTML;
            document.body.appendChild(modalElement);
        } else {
            modalElement.innerHTML = contenidoHTML;
        }
        
        return new bootstrap.Modal(modalElement);
    },

    /**
     * Genera HTML para el modal de detalle de préstamo
     * @returns {string} HTML del modal
     */
    generarHTMLModalDetalle() {
        return `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ti ti-bookmark text-primary me-2"></i>
                            Detalles del Préstamo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="contenido-detalle-prestamo">
                            <!-- Estado del préstamo -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div id="alert-estado" class="alert d-flex align-items-center">
                                        <i id="icono-estado" class="me-2"></i>
                                        <strong>Estado: <span id="detalle-estado-prestamo">-</span></strong>
                                        <span id="detalle-tiempo-restante" class="ms-auto">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del recurso -->
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="text-primary mb-3">Información del Recurso</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Título:</strong> <span id="detalle-titulo">-</span></p>
                                            <p><strong>Autor(es):</strong> <span id="detalle-autores">-</span></p>
                                            <p><strong>Editorial:</strong> <span id="detalle-editorial">-</span></p>
                                            <p><strong>ISBN:</strong> <span id="detalle-isbn">-</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Año:</strong> <span id="detalle-anio">-</span></p>
                                            <p><strong>Categoría:</strong> <span id="detalle-categoria">-</span></p>
                                            <p><strong>Tipo:</strong> <span id="detalle-tipo-recurso" class="badge bg-secondary">-</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div id="detalle-portada-container">
                                        <div id="detalle-portada-placeholder" class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-2" 
                                             style="width: 120px; height: 120px;">
                                            <i class="ti ti-book-off text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                        <img id="detalle-portada" src="" alt="Portada" class="img-fluid rounded mx-auto mb-2" 
                                             style="max-width: 120px; max-height: 120px; display: none;">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Información del usuario -->
                            <h6 class="text-primary mb-3">Información del Usuario</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombre:</strong> <span id="detalle-usuario-nombre">-</span></p>
                                    <p><strong>Documento:</strong> <span id="detalle-documento">-</span></p>
                                    <p><strong>Teléfono:</strong> <span id="detalle-telefono">-</span></p>
                                    <p><strong>Email:</strong> <span id="detalle-email">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Usuario:</strong> <span id="detalle-nombre-usuario">-</span></p>
                                    <p><strong>Nivel:</strong> <span id="detalle-nivel-acceso" class="badge">-</span></p>
                                    <p id="detalle-matricula-container" style="display: none;"><strong>ID Matrícula:</strong> <span id="detalle-matricula">-</span></p>
                                    <p id="detalle-grado-container" style="display: none;"><strong>Grado:</strong> <span id="detalle-grado">-</span></p>
                                </div>
                            </div>

                            <hr>

                            <!-- Información del préstamo -->
                            <h6 class="text-primary mb-3">Información del Préstamo</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Código préstamo:</strong> <span id="detalle-codigo-prestamo">-</span></p>
                                    <p><strong>Fecha préstamo:</strong> <span id="detalle-fecha-prestamo-solo">-</span></p>
                                    <p><strong>Hora inicio:</strong> <span id="detalle-hora-inicio">-</span></p>
                                    <p><strong>Hora fin:</strong> <span id="detalle-hora-fin">-</span></p>
                                    <p><strong>Fecha vencimiento:</strong> <span id="detalle-fecha-vencimiento">-</span></p>
                                    <p id="detalle-fecha-aprobacion-container" style="display: none;"><strong>Fecha aprobación:</strong> <span id="detalle-fecha-aprobacion">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Días transcurridos:</strong> <span id="detalle-dias-transcurridos">-</span> días</p>
                                    <p><strong>Días restantes:</strong> <span id="detalle-dias-restantes" class="badge">-</span></p>
                                    <p><strong>Total renovaciones:</strong> <span id="detalle-total-renovaciones" class="badge bg-info">-</span></p>
                                </div>
                            </div>

                            <hr>

                            <!-- Historial de renovaciones -->
                            <div id="detalle-renovaciones-section" style="display: none;">
                                <h6 class="text-primary mb-3">
                                    Historial de Renovaciones 
                                    <span id="detalle-cantidad-renovaciones" class="badge bg-success">0</span>
                                </h6>
                                <div id="detalle-renovaciones-tabla">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Nueva fecha devolución</th>
                                                    <th>Extensión</th>
                                                    <th>Motivo</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detalle-renovaciones-body">
                                                <!-- Se llenará dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="loading-detalle-prestamo" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted">${PrestamosConstants.MENSAJES.LOADING.DETALLE}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-renovar-prestamo" class="btn btn-outline-warning" style="display: none;">
                            <i class="ti ti-refresh me-2"></i>Renovar
                        </button>
                        <button type="button" id="btn-procesar-devolucion" class="btn btn-outline-success" style="display: none;">
                            <i class="ti ti-check me-2"></i>Procesar Devolución
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Actualiza el contenido del modal de detalle con los datos del préstamo
     * @param {object} detalle - Datos del préstamo
     */
    actualizarModalDetalle(detalle) {
        // Actualizar estado
        this._actualizarEstadoPrestamo(detalle);
        
        // Actualizar info del recurso
        this._actualizarInfoRecurso(detalle);
        
        // Actualizar info del usuario
        this._actualizarInfoUsuario(detalle);
        
        // Actualizar info del préstamo
        this._actualizarInfoPrestamo(detalle);
        
        // Actualizar historial de renovaciones
        this._actualizarHistorialRenovaciones(detalle);
    },

    /**
     * Actualiza la sección de estado del préstamo
     * @private
     */
    _actualizarEstadoPrestamo(detalle) {
        const alertEstado = document.getElementById('alert-estado');
        const estadoConfig = PrestamosConstants.ESTADO_CONFIG[detalle.estado_prestamo] || 
                            PrestamosConstants.ESTADO_CONFIG['Activo'];
        
        alertEstado.className = `alert alert-${estadoConfig.color} d-flex align-items-center`;
        document.getElementById('icono-estado').className = `ti ${estadoConfig.icono} me-2`;
        document.getElementById('detalle-estado-prestamo').textContent = detalle.estado_prestamo;
        
        const textoTiempo = DateTimeUtils.formatearTiempoConContexto(parseFloat(detalle.dias_restantes) || 0);
        document.getElementById('detalle-tiempo-restante').textContent = textoTiempo;
    },

    /**
     * Actualiza la sección de información del recurso
     * @private
     */
    _actualizarInfoRecurso(detalle) {
        document.getElementById('detalle-titulo').textContent = detalle.recurso_titulo || '-';
        
        const autoresText = detalle.autores?.length > 0 
            ? detalle.autores.map(a => a.autor_completo || 'Autor desconocido').join(', ')
            : 'No especificado';
        document.getElementById('detalle-autores').innerHTML = autoresText;
        
        document.getElementById('detalle-editorial').textContent = detalle.editorial || 'No especificada';
        document.getElementById('detalle-isbn').textContent = detalle.isbn || 'No disponible';
        document.getElementById('detalle-anio').textContent = detalle.anio_publicacion || 'No especificado';
        
        const categoriaCompleta = (detalle.categoria || 'Sin categoría') + 
                                 (detalle.subcategoria ? ` / ${detalle.subcategoria}` : '');
        document.getElementById('detalle-categoria').textContent = categoriaCompleta;
        
        const tipoRecursoElement = document.getElementById('detalle-tipo-recurso');
        tipoRecursoElement.textContent = detalle.tipo_recurso || '-';
        tipoRecursoElement.className = 'badge bg-secondary';

        // Portada
        const portadaImg = document.getElementById('detalle-portada');
        const portadaPlaceholder = document.getElementById('detalle-portada-placeholder');
        
        if (detalle.portada) {
            portadaImg.src = detalle.portada;
            portadaImg.style.display = 'block';
            portadaPlaceholder.style.display = 'none';
        } else {
            portadaImg.style.display = 'none';
            portadaPlaceholder.style.display = 'flex';
        }
    },

    /**
     * Actualiza la sección de información del usuario
     * @private
     */
    _actualizarInfoUsuario(detalle) {
        document.getElementById('detalle-usuario-nombre').textContent = detalle.usuario_completo || '-';
        document.getElementById('detalle-documento').textContent = `${detalle.tipo_documento || ''} ${detalle.documento || ''}`.trim() || '-';
        document.getElementById('detalle-telefono').textContent = detalle.telefono || 'No registrado';
        document.getElementById('detalle-email').textContent = detalle.email || 'No registrado';
        document.getElementById('detalle-nombre-usuario').textContent = detalle.nombre_usuario || 'N/A';
        
        const nivelElement = document.getElementById('detalle-nivel-acceso');
        nivelElement.textContent = detalle.nivel_acceso || 'N/A';
        
        const nivelClasses = {
            'admin': 'badge bg-danger',
            'docente': 'badge bg-warning',
            'default': 'badge bg-success'
        };
        nivelElement.className = nivelClasses[detalle.nivel_acceso] || nivelClasses.default;

        // Información adicional
        const matriculaContainer = document.getElementById('detalle-matricula-container');
        const gradoContainer = document.getElementById('detalle-grado-container');
        
        if (detalle.idmatricula) {
            document.getElementById('detalle-matricula').textContent = detalle.idmatricula;
            matriculaContainer.style.display = 'block';
        } else {
            matriculaContainer.style.display = 'none';
        }
        
        if (detalle.grado && detalle.seccion) {
            document.getElementById('detalle-grado').textContent = `${detalle.grado} - ${detalle.seccion}`;
            gradoContainer.style.display = 'block';
        } else {
            gradoContainer.style.display = 'none';
        }
    },

    /**
     * Actualiza la sección de información del préstamo
     * @private
     */
    _actualizarInfoPrestamo(detalle) {
        document.getElementById('detalle-codigo-prestamo').textContent = detalle.idprestamo || '-';
        document.getElementById('detalle-fecha-prestamo-solo').textContent = detalle.fecha_prestamo_solo || '-';
        document.getElementById('detalle-hora-inicio').textContent = detalle.hora_inicio || '-';
        document.getElementById('detalle-hora-fin').textContent = detalle.hora_fin || 'No especificada';
        document.getElementById('detalle-fecha-vencimiento').textContent = detalle.fecha_vencimiento_formatted || '-';
        
        const fechaAprobacionContainer = document.getElementById('detalle-fecha-aprobacion-container');
        if (detalle.fecha_aprobacion_formatted) {
            document.getElementById('detalle-fecha-aprobacion').textContent = detalle.fecha_aprobacion_formatted;
            fechaAprobacionContainer.style.display = 'block';
        } else {
            fechaAprobacionContainer.style.display = 'none';
        }
        
        document.getElementById('detalle-dias-transcurridos').textContent = Math.floor(detalle.dias_transcurridos) || 0;
        
        const diasRestantesElement = document.getElementById('detalle-dias-restantes');
        const diasRestantes = parseFloat(detalle.dias_restantes) || 0;
        const textoRestantes = DateTimeUtils.formatearDiasRestantes(diasRestantes);
        
        diasRestantesElement.textContent = textoRestantes;
        diasRestantesElement.className = `badge bg-${diasRestantes >= 0 ? 'success' : 'danger'}`;
        
        document.getElementById('detalle-total-renovaciones').textContent = detalle.total_renovaciones || 0;
    },

    /**
     * Actualiza la sección del historial de renovaciones
     * @private
     */
    _actualizarHistorialRenovaciones(detalle) {
        const renovacionesSection = document.getElementById('detalle-renovaciones-section');
        const cantidadRenovaciones = document.getElementById('detalle-cantidad-renovaciones');
        const renovacionesBody = document.getElementById('detalle-renovaciones-body');
        
        if (detalle.renovaciones?.length > 0) {
            cantidadRenovaciones.textContent = detalle.renovaciones.length;
            renovacionesBody.innerHTML = '';
            
            detalle.renovaciones.forEach(ren => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><small>${ren.fecha_renovacion_formatted}</small></td>
                    <td><small>${ren.fecha_vencimiento_nueva_formatted}</small></td>
                    <td><span class="badge bg-info">${ren.dias_extension || 0} días</span></td>
                    <td><small>${ren.motivo || 'Sin motivo especificado'}</small></td>
                `;
                renovacionesBody.appendChild(row);
            });
            
            renovacionesSection.style.display = 'block';
        } else {
            renovacionesSection.style.display = 'none';
        }
    },

    /**
     * Configura los botones de acción del modal de detalle
     * @param {bootstrap.Modal} modal - Instancia del modal
     * @param {object} detalle - Datos del préstamo
     * @param {number} prestamoId - ID del préstamo
     */
    configurarBotonesModalDetalle(modal, detalle, prestamoId) {
        const btnRenovar = document.getElementById('btn-renovar-prestamo');
        const btnDevolucion = document.getElementById('btn-procesar-devolucion');
        
        if (detalle.estado_prestamo === 'Activo' || detalle.estado_prestamo === 'Vencido') {
            btnRenovar.style.display = 'inline-block';
            btnDevolucion.style.display = 'inline-block';
            
            btnRenovar.onclick = () => {
                modal.hide();
                // Esta función será proporcionada por prestamos.main.js
                if (window.PrestamoController) {
                    window.PrestamoController.renovarPrestamo(prestamoId);
                }
            };
            
            btnDevolucion.onclick = () => {
                modal.hide();
                // Esta función será proporcionada por prestamos.main.js
                if (window.PrestamoController) {
                    window.PrestamoController.procesarDevolucion(prestamoId);
                }
            };
        } else {
            btnRenovar.style.display = 'none';
            btnDevolucion.style.display = 'none';
        }
    }
};

// Hacer disponible globalmente
if (typeof window !== 'undefined') {
    window.PrestamosModal = PrestamosModal;
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrestamosModal;
}
