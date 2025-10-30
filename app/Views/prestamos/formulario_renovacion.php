<!-- Formulario de renovación de préstamo (simplificado) -->
<style>
    .swal-wide {
        max-width: 600px !important;
    }
    
    .swal-html-container-custom {
        padding: 1rem !important;
    }
    
    #formRenovacionPrestamo .form-control:read-only {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>

<form id="formRenovacionPrestamo" novalidate>
    <input type="hidden" name="idprestamo" value="<?= $prestamo['idprestamo'] ?>">
    
    <!-- Información del libro -->
    <div class="mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-book me-2"></i>Libro:
        </label>
        <input type="text" class="form-control form-control-lg" 
               value="<?= esc($prestamo['titulo']) ?>" readonly>
    </div>
    
    <!-- Fechas actuales y nuevas -->
    <div class="row mb-3">
        <div class="col-6">
            <label class="form-label text-muted">Vence actualmente:</label>
            <input type="text" 
                   class="form-control" 
                   value="<?= date('d/m/Y', strtotime($prestamo['fechadevolucion'])) ?>" 
                   readonly>
        </div>
        <div class="col-6">
            <label class="form-label">
                Nueva devolución: <span class="text-danger">*</span>
            </label>
            <?php 
            $hoy = new DateTime();
            $fechaVencimiento = new DateTime($prestamo['fechadevolucion']);
            
            if ($fechaVencimiento < $hoy) {
                $nuevaFechaInicio = (new DateTime())->modify('+1 day')->format('Y-m-d');
            } else {
                $nuevaFechaInicio = (new DateTime($prestamo['fechadevolucion']))->modify('+1 day')->format('Y-m-d');
            }
            
            $nuevaFechaDevolucion = (new DateTime($nuevaFechaInicio))->modify('+7 days')->format('Y-m-d');
            $maxFechaDevolucion = $nuevaFechaDevolucion;
            ?>
            <input type="date" 
                   class="form-control" 
                   id="nuevaFechaDevolucion" 
                   name="nueva_fecha_devolucion" 
                   value="<?= $nuevaFechaDevolucion ?>" 
                   min="<?= $nuevaFechaInicio ?>" 
                   max="<?= $maxFechaDevolucion ?>"
                   onchange="validarFechaDevolucion()"
                   required>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>Máximo <?= date('d/m/Y', strtotime($maxFechaDevolucion)) ?>
            </small>
        </div>
    </div>
    
    <!-- Campo oculto con la fecha de inicio -->
    <input type="hidden" id="nuevaFechaPrestamo" name="nueva_fecha_prestamo" value="<?= $nuevaFechaInicio ?>">
    
    <!-- Motivo (opcional) -->
    <div class="mb-3">
        <label for="motivoRenovacion" class="form-label">
            <i class="fas fa-comment me-2"></i>Motivo (opcional):
        </label>
        <textarea class="form-control" 
                  id="motivoRenovacion" 
                  name="motivo" 
                  rows="2" 
                  placeholder="Razón de la renovación..."></textarea>
    </div>
    
    <!-- Botones de acción -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <button type="button" class="btn btn-secondary" onclick="Swal.close()">
            <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <button type="button" class="btn btn-success" onclick="enviarRenovacionPrestamo()">
            <i class="fas fa-redo me-1"></i>Renovar Préstamo
        </button>
    </div>
</form>
