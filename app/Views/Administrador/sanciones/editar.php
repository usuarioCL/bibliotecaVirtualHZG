<?= $header ?>
<?= $navbar ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-edit me-2"></i>Editar Sanción
                    </h4>
                </div>
                
                <div class="card-body">
                    <form id="formEditarSancion" action="<?= base_url('sanciones/actualizar/' . $sancion['idsancion']) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idpersona" class="form-label">
                                        <i class="ti ti-user me-1"></i>Persona <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="idpersona" name="idpersona" required>
                                        <option value="">Seleccione una persona</option>
                                        <?php foreach ($personas as $persona): ?>
                                            <option value="<?= $persona['idpersona'] ?>" 
                                                    <?= ($sancion['idpersona'] == $persona['idpersona']) ? 'selected' : '' ?>>
                                                <?= esc($persona['apellidos']) ?>, <?= esc($persona['nombres']) ?> 
                                                (<?= esc($persona['numerodoc']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback" id="error-idpersona"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idtiposancion" class="form-label">
                                        <i class="ti ti-ban me-1"></i>Tipo de Sanción <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="idtiposancion" name="idtiposancion" required>
                                        <option value="">Seleccione un tipo</option>
                                        <?php foreach ($tiposSancion as $tipo): ?>
                                            <option value="<?= $tipo['idtiposancion'] ?>" 
                                                    <?= ($sancion['idtiposancion'] == $tipo['idtiposancion']) ? 'selected' : '' ?>>
                                                <?= esc($tipo['tiposancion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback" id="error-idtiposancion"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="detallesancion" class="form-label">
                                        <i class="ti ti-file-text me-1"></i>Detalles de la Sanción
                                    </label>
                                    <textarea class="form-control" id="detallesancion" name="detallesancion" 
                                              rows="4" maxlength="200" 
                                              placeholder="Describa los motivos y detalles de la sanción..."><?= esc($sancion['detallesancion']) ?></textarea>
                                    <div class="form-text">
                                        <span id="contador-caracteres">0</span>/200 caracteres
                                    </div>
                                    <div class="invalid-feedback" id="error-detallesancion"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mostrar errores de validación -->
                        <?php if (session()->getFlashdata('errores')): ?>
                            <div class="alert alert-danger">
                                <h6><i class="ti ti-alert-circle me-1"></i>Errores de validación:</h6>
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errores') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('sanciones') ?>" class="btn btn-secondary ajax-link">
                                <i class="ti ti-arrow-left me-1"></i>Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-device-floppy me-1"></i>Actualizar Sanción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarSancion');
    const textareaDetalle = document.getElementById('detallesancion');
    const contadorCaracteres = document.getElementById('contador-caracteres');
    
    // Contador de caracteres
    function actualizarContador() {
        const longitud = textareaDetalle.value.length;
        contadorCaracteres.textContent = longitud;
        
        if (longitud > 180) {
            contadorCaracteres.classList.add('text-warning');
        } else {
            contadorCaracteres.classList.remove('text-warning');
        }
        
        if (longitud >= 200) {
            contadorCaracteres.classList.add('text-danger');
            contadorCaracteres.classList.remove('text-warning');
        } else {
            contadorCaracteres.classList.remove('text-danger');
        }
    }
    
    textareaDetalle.addEventListener('input', actualizarContador);
    actualizarContador(); // Inicializar contador
    
    // Mejorar selects
    const selectPersona = document.getElementById('idpersona');
    selectPersona.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            document.getElementById('error-idpersona').textContent = '';
        }
    });
    
    const selectTipo = document.getElementById('idtiposancion');
    selectTipo.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            document.getElementById('error-idtiposancion').textContent = '';
        }
    });
    
    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpiar errores anteriores
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Deshabilitar botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti ti-loader-2 me-1 spin"></i>Actualizando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="ti ti-check me-1"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);
                
                // Opcional: redirigir después de un momento
                setTimeout(() => {
                    window.location.href = '<?= base_url('sanciones') ?>';
                }, 2000);
                
            } else {
                // Mostrar errores de validación
                if (data.errors) {
                    for (const [field, message] of Object.entries(data.errors)) {
                        const input = document.getElementById(field);
                        const errorDiv = document.getElementById(`error-${field}`);
                        
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = message;
                        }
                    }
                }
                
                // Mostrar mensaje de error general
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="ti ti-alert-circle me-1"></i>Error al actualizar la sanción
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="ti ti-alert-circle me-1"></i>Error de conexión. Intente nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            form.insertBefore(alertDiv, form.firstChild);
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});

// Estilo para animación de carga
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

<?= $footer ?>
