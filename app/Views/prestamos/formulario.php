<!-- Formulario de solicitud de préstamo -->
<form id="formSolicitudPrestamo" novalidate>
    <input type="hidden" name="idRecurso" value="<?= $idRecurso ?>">
    <input type="hidden" name="idUsuario" value="<?= session()->get('id') ?>">
    
    <!-- Información del recurso -->
    <div class="row mb-3">
        <div class="col-12">
            <label for="recursoTitulo" class="form-label fw-bold">Recurso solicitado:</label>
            <input type="text" class="form-control form-control-lg" id="recursoTitulo" 
                   value="<?= esc($recurso['titulo']) ?>" readonly>
        </div>
    </div>
    
    <!-- Fechas de préstamo -->
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-3">
            <label for="fechaInicio" class="form-label fw-semibold">
                <i class="fas fa-calendar-plus text-primary me-1"></i>Fecha de inicio:
            </label>
            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" 
                   value="<?= date('Y-m-d') ?>" required>
            <div class="invalid-feedback">
                Seleccione una fecha válida (no puede ser anterior a hoy).
            </div>
        </div>
        
        <div class="col-lg-6 col-md-6 mb-3">
            <label for="fechaEntrega" class="form-label fw-semibold">
                <i class="fas fa-calendar-check text-success me-1"></i>Fecha de entrega:
            </label>
            <input type="date" class="form-control" id="fechaEntrega" name="fechaEntrega" 
                   value="" required>
            <div class="invalid-feedback">
                Seleccione una fecha válida (máximo 7 días después de la fecha de inicio).
            </div>
        </div>
    </div>
    
    <!-- Campo de cantidad (para docentes y administradores) -->
    <?php if (in_array(session()->get('nivelacceso'), ['docente', 'admin'])): ?>
    <div class="row mb-3">
        <div class="col-lg-6 col-md-6 mb-3">
            <label for="cantidadLibros" class="form-label fw-semibold">
                <i class="fas fa-list-ol text-warning me-1"></i>Cantidad de libros:
            </label>
            <div class="input-group">
                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(-1)">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" class="form-control text-center" id="cantidadLibros" name="cantidadLibros" 
                       value="1" min="1" max="<?= isset($recurso['stock']) ? $recurso['stock'] : 1 ?>" required>
                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(1)">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="invalid-feedback">
                Ingrese una cantidad válida (1 a <?= isset($recurso['stock']) ? $recurso['stock'] : 1 ?> libros).
            </div>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Disponibles: <span id="stockDisponible"><?= isset($recurso['stock']) ? $recurso['stock'] : 1 ?></span> ejemplares
            </small>
        </div>
        <div class="col-lg-6 col-md-6 mb-3">
            <div class="alert alert-info border-0 h-100 d-flex align-items-center">
                <div>
                    <h6 class="alert-heading mb-1">
                        <i class="fas fa-user-shield me-2"></i>Privilegio Especial
                    </h6>
                    <p class="mb-0 small">
                        <?php if (session()->get('nivelacceso') === 'docente'): ?>
                            Como docente, puede solicitar múltiples ejemplares del mismo recurso para actividades académicas.
                        <?php else: ?>
                            Como administrador, puede solicitar múltiples ejemplares del mismo recurso para gestión bibliotecaria.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Resumen de la solicitud -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card-title mb-1 text-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Resumen del préstamo
                            </h6>
                            <p class="card-text mb-0">
                                <strong>Duración:</strong> <span id="duracionPrestamo" class="text-success fw-bold">7 días</span>
                                <?php if (in_array(session()->get('nivelacceso'), ['docente', 'admin'])): ?>
                                <br><strong>Cantidad:</strong> <span id="resumenCantidad" class="text-primary fw-bold">1 libro</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i>Préstamo físico<br>
                                <strong>Máximo 1 semana</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Botones de acción -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="Swal.close()">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success btn-lg px-4" onclick="enviarSolicitudPrestamo()">
                    <i class="fas fa-paper-plane me-2"></i>Enviar solicitud
                </button>
            </div>
        </div>
    </div>
</form>


