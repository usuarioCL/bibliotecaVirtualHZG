<!-- Modal para detalles del usuario -->
<div class="modal fade" id="modalDetalleUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenido-detalle-usuario">
                    <!-- Información Personal -->
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-user me-2"></i>Información Personal
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombres:</strong> <span id="detalle-nombres"></span></p>
                                    <p><strong>Apellidos:</strong> <span id="detalle-apellidos"></span></p>
                                    <p><strong>Documento:</strong> <span id="detalle-documento"></span></p>
                                    <p><strong>Género:</strong> <span id="detalle-genero"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Teléfono:</strong> <span id="detalle-telefono"></span></p>
                                    <p><strong>Email:</strong> <span id="detalle-email"></span></p>
                                    <p><strong>Dirección:</strong> <span id="detalle-direccion"></span></p>
                                    <p><strong>Fecha Registro:</strong> <span id="detalle-fecha-registro"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div id="detalle-avatar" class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 100px; height: 100px; font-size: 2rem; font-weight: 600;">
                                --
                            </div>
                            <div class="mb-2">
                                <span id="detalle-nivel-badge" class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="ti ti-user me-1"></i>Usuario
                                </span>
                            </div>
                            <div>
                                <span id="detalle-estado" class="badge bg-success">Activo</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Información de Sistema -->
                    <h6 class="text-primary mb-3">
                        <i class="ti ti-settings me-2"></i>Información del Sistema
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre de Usuario:</strong> <span id="detalle-nomuser"></span></p>
                            <p><strong>Nivel de Acceso:</strong> <span id="detalle-nivelacceso"></span></p>
                            <p><strong>ID Usuario:</strong> <span id="detalle-idusuario"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Última Conexión:</strong> <span id="detalle-ultima-conexion">No registrada</span></p>
                            <p><strong>Estado Cuenta:</strong> <span id="detalle-estado-cuenta">Activa</span></p>
                            <p><strong>Tipo Documento:</strong> <span id="detalle-tipodoc"></span></p>
                        </div>
                    </div>

                    <!-- Información específica por tipo de usuario -->
                    <div id="seccion-matricula" class="d-none">
                        <hr>
                        <h6 class="text-success mb-3">
                            <i class="ti ti-school me-2"></i>Información Académica
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nivel:</strong> <span id="detalle-nivel-academico"></span></p>
                                <p><strong>Grado:</strong> <span id="detalle-grado"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Sección:</strong> <span id="detalle-seccion"></span></p>
                                <p><strong>Año Lectivo:</strong> <span id="detalle-anio-lectivo"></span></p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Estadísticas de Biblioteca -->
                    <h6 class="text-primary mb-3">
                        <i class="ti ti-chart-bar me-2"></i>Actividad en Biblioteca
                    </h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <h4 class="mb-1 text-primary" id="detalle-prestamos-activos">0</h4>
                                <small class="text-muted">Préstamos Activos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <h4 class="mb-1 text-success" id="detalle-total-prestamos">0</h4>
                                <small class="text-muted">Total Préstamos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                <h4 class="mb-1 text-warning" id="detalle-recursos-favoritos">0</h4>
                                <small class="text-muted">Recursos Favoritos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <h4 class="mb-1 text-info" id="detalle-comentarios">0</h4>
                                <small class="text-muted">Comentarios</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Historial Reciente -->
                    <h6 class="text-primary mb-3">
                        <i class="ti ti-history me-2"></i>Historial Reciente
                    </h6>
                    <div id="detalle-historial">
                        <div class="text-center text-muted py-3">
                            <i class="ti ti-clock-hour-3 me-2"></i>
                            Sin actividad reciente registrada
                        </div>
                    </div>
                </div>
                
                <div id="loading-detalle" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información del usuario...</p>
                </div>

                <div id="error-detalle" class="text-center py-5" style="display: none;">
                    <div class="text-danger mb-3">
                        <i class="ti ti-alert-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-danger">Error al cargar información</h5>
                    <p class="text-muted">No se pudo obtener los detalles del usuario</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="recargarDetalleUsuario()">
                        <i class="ti ti-refresh me-1"></i>Reintentar
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="btn-editar-usuario" onclick="editarUsuarioDesdeDetalle()">
                    <i class="ti ti-edit me-2"></i>Editar
                </button>
                <button type="button" class="btn btn-outline-success" id="btn-historial-usuario" onclick="verHistorialCompleto()">
                    <i class="ti ti-history me-2"></i>Ver Historial
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>