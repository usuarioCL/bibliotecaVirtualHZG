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
    
    <!-- Fecha y horarios -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-3">
            <label for="fechaPrestamo" class="form-label fw-semibold">
                <i class="fas fa-calendar-alt text-primary me-1"></i>Fecha de uso:
            </label>
            <input type="date" class="form-control" id="fechaPrestamo" name="fechaPrestamo" 
                   value="<?= date('Y-m-d') ?>">
            <div class="invalid-feedback">
                Seleccione una fecha válida (no puede ser anterior a hoy).
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <label for="horaInicio" class="form-label fw-semibold">
                <i class="fas fa-clock text-success me-1"></i>Hora de inicio:
            </label>
            <input type="time" class="form-control" id="horaInicio" name="horaInicio" 
                   min="08:00" max="12:59" value="08:00" required>
            <div class="invalid-feedback">
                La hora debe estar entre 8:00 AM y 12:59 PM.
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 mb-3">
            <label for="horaFin" class="form-label fw-semibold">
                <i class="fas fa-clock text-danger me-1"></i>Hora de fin:
            </label>
            <input type="time" class="form-control" id="horaFin" name="horaFin" 
                   min="08:01" max="13:00" value="09:00" required>
            <div class="invalid-feedback">
                La hora debe estar entre 8:01 AM y 1:00 PM.
            </div>
        </div>
    </div>
    
    <!-- Resumen de la solicitud -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card-title mb-1 text-primary">
                                <i class="fas fa-clock me-2"></i>Resumen del préstamo
                            </h6>
                            <p class="card-text mb-0">
                                <strong>Duración:</strong> <span id="duracionPrestamo" class="text-success fw-bold">1 hora</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-school me-1"></i>Horario escolar<br>
                                <strong>8:00 AM - 1:00 PM</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Información importante -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-start">

                    <div class="flex-grow-1">
                        <h6 class="alert-heading fw-bold mb-2">Condiciones del préstamo:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        Solo dentro de la institución educativa
                                    </li>
                                    <li class="mb-1">
                                        <i class="fas fa-calendar-week text-success me-2"></i>
                                        Lunes a Viernes únicamente
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-1">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        Horario: 8:00 AM - 1:00 PM
                                    </li>
                                    <li class="mb-1">
                                        <i class="fas fa-graduation-cap text-info me-2"></i>
                                        Durante horas de clase
                                    </li>
                                </ul>
                            </div>
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


