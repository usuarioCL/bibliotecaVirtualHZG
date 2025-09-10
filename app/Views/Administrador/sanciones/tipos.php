<?= $header ?>
<?= $navbar ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-settings me-2"></i>Tipos de Sanción
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('sanciones') ?>" class="btn btn-outline-secondary ajax-link">
                            <i class="ti ti-arrow-left me-1"></i>Volver a Sanciones
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoTipo">
                            <i class="ti ti-plus me-1"></i>Nuevo Tipo
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="70%">Tipo de Sanción</th>
                                    <th width="20%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tipos)): ?>
                                    <?php foreach ($tipos as $index => $tipo): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <strong><?= esc($tipo['tiposancion']) ?></strong>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="eliminarTipo(<?= $tipo['idtiposancion'] ?>)" 
                                                        title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-settings fs-1 d-block mb-2"></i>
                                                No hay tipos de sanción registrados
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo tipo -->
<div class="modal fade" id="modalNuevoTipo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-plus me-2"></i>Nuevo Tipo de Sanción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoTipo" action="<?= base_url('sanciones/crear-tipo') ?>" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="tiposancion" class="form-label">
                            Nombre del Tipo de Sanción <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="tiposancion" name="tiposancion" 
                               maxlength="80" required placeholder="Ej: Amonestación verbal, Suspensión...">
                        <div class="form-text">Máximo 80 caracteres</div>
                        <div class="invalid-feedback" id="error-tiposancion"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formNuevoTipo');
    const modal = new bootstrap.Modal(document.getElementById('modalNuevoTipo'));
    
    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpiar errores anteriores
        document.getElementById('tiposancion').classList.remove('is-invalid');
        document.getElementById('error-tiposancion').textContent = '';
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Deshabilitar botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti ti-loader-2 me-1 spin"></i>Guardando...';
        
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
                alert(data.message);
                modal.hide();
                form.reset();
                location.reload();
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
                } else {
                    alert('Error al guardar el tipo de sanción');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Intente nuevamente.');
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Limpiar errores al escribir
    document.getElementById('tiposancion').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('error-tiposancion').textContent = '';
    });
});

function eliminarTipo(idtiposancion) {
    if (confirm('¿Está seguro de que desea eliminar este tipo de sanción?\n\nNota: No se puede eliminar si está siendo utilizado en alguna sanción.')) {
        fetch(`<?= base_url('sanciones/eliminar-tipo/') ?>${idtiposancion}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el tipo de sanción');
        });
    }
}

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
