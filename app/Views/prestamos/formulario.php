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
        <button type="submit" class="btn btn-success">Enviar solicitud</button>
    </div>
</form>


<!-- Scripts al final del body -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar el campo "otro motivo" cuando se selecciona "Otro"
    document.getElementById('motivo').addEventListener('change', function() {
        const otroMotivoContainer = document.getElementById('otroMotivoContainer');
        otroMotivoContainer.style.display = this.value === 'Otro' ? 'block' : 'none';
        // Solo requerido si está visible
        const otroMotivo = document.getElementById('otroMotivo');
        otroMotivo.required = this.value === 'Otro';
    });

    // Validar que fecha devolución sea posterior a fecha inicio
    document.getElementById('fechaInicio').addEventListener('change', function() {
        const fechaDevolucion = document.getElementById('fechaDevolucion');
        const fechaInicioValue = new Date(this.value);
        // Sumamos un día para la fecha mínima de devolución
        fechaInicioValue.setDate(fechaInicioValue.getDate() + 1);
        fechaDevolucion.min = fechaInicioValue.toISOString().split('T')[0];
        // Si la fecha actual es menor que la mínima, actualizar
        if (fechaDevolucion.value < fechaDevolucion.min) {
            fechaDevolucion.value = fechaDevolucion.min;
        }
    });

    // Validación de Bootstrap
    const form = document.getElementById('formSolicitudPrestamo');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            enviarSolicitudPrestamo();
        }
        form.classList.add('was-validated');
    }, false);

    // Función para enviar la solicitud
    function enviarSolicitudPrestamo() {
        const formData = new FormData(form);
        // Si el motivo es "Otro", reemplazamos el valor del motivo con el texto proporcionado
        if (document.getElementById('motivo').value === 'Otro') {
            formData.set('motivo', document.getElementById('otroMotivo').value);
        }
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Enviando solicitud',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        // Enviar solicitud mediante AJAX
        fetch('<?= base_url('prestamo/solicitar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Solicitud Enviada!',
                    text: 'Tu solicitud de préstamo ha sido enviada. Te notificaremos cuando sea procesada.',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    showCancelButton: false,
                    confirmButtonText: 'Entendido'
                }).then((result) => {
                    // Cerrar el modal de SweetAlert (formulario)
                    if (result.isConfirmed) {
                        Swal.close();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al procesar tu solicitud',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ha ocurrido un error al enviar tu solicitud',
                icon: 'error'
            });
        });
    }
});
</script>