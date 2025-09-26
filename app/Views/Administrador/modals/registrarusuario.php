<!-- Modal para nuevo usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nueva Persona y Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoUsuario" autocomplete="off">
                    <!-- Datos de la Persona -->
                    <h6 class="text-primary mb-3">Datos Personales</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required autofocus placeholder="Ej: Pérez Gómez">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required placeholder="Ej: Juan Carlos">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tipodoc" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="tipodoc" name="tipodoc" required>
                                    <option value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numerodoc" class="form-label">Número de Documento</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="numerodoc" name="numerodoc" required maxlength="15" placeholder="Ej: 12345678">
                                    <button type="button" class="btn btn-outline-secondary" onclick="buscarPorDni()" title="Buscar estudiante por DNI">
                                        <i class="icon tabler-search fs-6"></i>
                                    </button>
                                </div>
                                <div id="info-busqueda" class="form-text d-none"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15" placeholder="Ej: 999888777">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" maxlength="100" placeholder="Ej: Av. Siempre Viva 123">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Usuario -->
                    <hr>
                    <h6 class="text-primary mb-3">Datos de Usuario</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nivelacceso" class="form-label">Nivel de Acceso</label>
                                <select class="form-select" id="nivelacceso" name="nivelacceso" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="estudiante">Estudiante</option>
                                    <option value="docente">Docente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="passuser" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="passuser" name="passuser" required minlength="6" placeholder="Mínimo 6 caracteres">
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos generados automáticamente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomuser_preview" class="form-label">Usuario (generado automáticamente)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomuser_preview" readonly placeholder="Se generará automáticamente" title="Usuario generado por el sistema">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generarUsuarioYEmail()" title="Regenerar usuario y email">
                                        <i class="icon tabler-refresh fs-6"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="nomuser" name="nomuser">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email_preview" class="form-label">Email (generado automáticamente)</label>
                                <input type="email" class="form-control" id="email_preview" readonly placeholder="Se generará automáticamente" title="Email institucional generado">
                                <input type="hidden" id="email" name="email">
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacion" class="alert d-none mt-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="registrarPersonaYUsuario()">Registrar</button>
            </div>
        </div>
    </div>
</div>