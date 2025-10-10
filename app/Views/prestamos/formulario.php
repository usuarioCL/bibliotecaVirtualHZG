<!-- Formulario de solicitud de préstamo -->
<form id="formSolicitudPrestamo" class="needs-validation" novalidate>
    <input type="hidden" name="idRecurso" value="<?= $idRecurso ?>">
    <input type="hidden" name="idUsuario" value="<?= session()->get('id') ?>">
    
    <div class="mb-3">
        <label for="fechaSolicitud" class="form-label">Fecha de solicitud:</label>
        <input type="text" class="form-control" id="fechaSolicitud" value="<?= date('d/m/Y') ?>" readonly>
    </div>
    
    <div class="mb-3">
        <label for="recursoTitulo" class="form-label">Recurso solicitado:</label>
        <input type="text" class="form-control" id="recursoTitulo" value="<?= esc($recurso['titulo']) ?>" readonly>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="fechaInicio" class="form-label">Fecha de préstamo:</label>
            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" 
                   value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
            <div class="invalid-feedback">
                Por favor seleccione una fecha válida.
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="fechaDevolucion" class="form-label">Fecha de devolución:</label>
            <input type="date" class="form-control" id="fechaDevolucion" name="fechaDevolucion"
                   value="<?= date('Y-m-d', strtotime('+7 days')) ?>" 
                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            <div class="invalid-feedback">
                La fecha debe ser posterior a la fecha de préstamo.
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="motivo" class="form-label">Motivo del préstamo:</label>
        <select class="form-select" id="motivo" name="motivo" required>
            <option value="" selected disabled>Seleccione un motivo</option>
            <option value="Estudio">Estudio</option>
            <option value="Investigación">Investigación</option>
            <option value="Lectura recreativa">Lectura recreativa</option>
            <option value="Proyecto escolar">Proyecto escolar</option>
            <option value="Otro">Otro</option>
        </select>
        <div class="invalid-feedback">
            Por favor seleccione un motivo.
        </div>
    </div>
    
    <div class="mb-3" id="otroMotivoContainer" style="display:none;">
        <label for="otroMotivo" class="form-label">Especifique el motivo:</label>
        <input type="text" class="form-control" id="otroMotivo" name="otroMotivo">
    </div>
    
    <div class="mb-3">
        <label for="observaciones" class="form-label">Observaciones (opcional):</label>
        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
    </div>
    
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="aceptaTerminos" name="aceptaTerminos" required>
        <label class="form-check-label" for="aceptaTerminos">
            Acepto los términos y condiciones para el préstamo de recursos
        </label>
        <div class="invalid-feedback">
            Debe aceptar los términos y condiciones.
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" onclick="Swal.close()">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="enviarSolicitudPrestamo()">Enviar solicitud</button>
    </div>
</form>



<!-- contenido adicional -->