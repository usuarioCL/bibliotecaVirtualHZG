<!-- Modal para detalles del docente -->
<div class="modal fade" id="modalDetalleDocente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenido-detalle-docente">
                    <!-- Información Personal -->
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-primary mb-3">Información Personal</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombres:</strong> <span id="detalle-nombres"></span></p>
                                    <p><strong>Apellidos:</strong> <span id="detalle-apellidos"></span></p>
                                    <p><strong>Documento:</strong> <span id="detalle-documento"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Teléfono:</strong> <span id="detalle-telefono"></span></p>
                                    <p><strong>Email:</strong> <span id="detalle-email"></span></p>
                                    <p><strong>Género:</strong> <span id="detalle-genero"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div id="detalle-avatar" class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                 style="width: 80px; height: 80px; font-size: 1.5rem; font-weight: 600;">
                                --
                            </div>
                            <span id="detalle-estado" class="badge bg-success">Activo</span>
                        </div>
                    </div>

                    <hr>

                    <!-- Información Profesional -->
                    <h6 class="text-primary mb-3">Información Profesional</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Especialidad:</strong> <span id="detalle-especialidad"></span></p>
                            <p><strong>Nivel Asignado:</strong> <span id="detalle-nivel"></span></p>
                            <p><strong>Código Docente:</strong> <span id="detalle-codigo"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha de Ingreso:</strong> <span id="detalle-fecha-ingreso"></span></p>
                            <p><strong>Usuario Sistema:</strong> <span id="detalle-usuario"></span></p>
                            <p><strong>Años de Servicio:</strong> <span id="detalle-anos-servicio"></span></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Estadísticas de Biblioteca -->
                    <h6 class="text-primary mb-3">Actividad en Biblioteca</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1 text-primary" id="detalle-prestamos-activos">0</h5>
                                <small class="text-muted">Préstamos Activos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1 text-success" id="detalle-total-prestamos">0</h5>
                                <small class="text-muted">Total Préstamos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1 text-warning" id="detalle-recursos-favoritos">0</h5>
                                <small class="text-muted">Recursos Favoritos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1 text-info" id="detalle-comentarios">0</h5>
                                <small class="text-muted">Comentarios</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Historial Reciente -->
                    <h6 class="text-primary mb-3">Historial Reciente</h6>
                    <div id="detalle-historial">
                        <p class="text-muted">Cargando historial...</p>
                    </div>
                </div>
                
                <div id="loading-detalle" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" onclick="editarDocente()">
                    <i class="ti ti-edit me-2"></i>Editar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>