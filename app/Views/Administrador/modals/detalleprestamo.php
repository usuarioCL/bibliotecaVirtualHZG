<!-- Modal para detalles del préstamo -->
<div class="modal fade" id="modalDetallePrestamo" tabindex="-1">
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
                            <p><strong>Fecha préstamo:</strong> <span id="detalle-fecha-prestamo">-</span></p>
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
                    <p class="mt-2 text-muted">Cargando información del préstamo...</p>
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
</div>