<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Autor</h4>
        </div>
        <div class="card-body">
            <!-- Sección de edición en página completa -->
            <div id="seccionEditarAutor">
                <div class="pb-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Editar Autor</h5>
                    <a href="<?= base_url('autores') ?>" class="btn btn-sm btn-outline-secondary ajax-link">Volver</a>
                </div>
                
                <form id="form-editar-autor" method="post" action="<?= base_url('autores/actualizar/' . $autor['idautor']); ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apeautor" class="form-control" value="<?= esc($autor['apeautor']) ?>" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nomautor" class="form-control" value="<?= esc($autor['nomautor']) ?>" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nacionalidad</label>
                            <input type="text" name="nacionalidad" class="form-control" value="<?= esc($autor['nacionalidad']) ?>" />
                        </div>
                    </div>
                    
                    <div id="alertaValidacionAutor" class="alert d-none mt-3"></div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('autores') ?>" class="btn btn-secondary ajax-link">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Actualizar Autor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    // Enviar por AJAX para mantener en el contenedor del dashboard
    $('#form-editar-autor').on('submit', function(e){
        e.preventDefault();
        var $form = $(this);
        var alerta = document.getElementById('alertaValidacionAutor');
        
        // Limpiar alertas previas
        alerta.classList.add('d-none');
        
        // Validar campos requeridos
        var apellidos = $form.find('[name="apeautor"]').val().trim();
        var nombres = $form.find('[name="nomautor"]').val().trim();
        
        if (!apellidos || !nombres) {
            alerta.className = 'alert alert-danger mt-3';
            alerta.textContent = 'Por favor complete los campos requeridos (Apellidos y Nombres)';
            alerta.classList.remove('d-none');
            return;
        }
        
        $.post($form.attr('action'), $form.serialize())
            .done(function(){
                alerta.className = 'alert alert-success mt-3';
                alerta.innerHTML = '<strong>¡Actualización exitosa!</strong><br>Autor actualizado correctamente';
                alerta.classList.remove('d-none');
                
                // Redirigir a la lista después de 1 segundo
                setTimeout(() => {
                    $.get('<?= base_url('autores') ?>', function(html){ 
                        $('#contenedor-principal').html(html); 
                    });
                }, 1000);
            })
            .fail(function(xhr){
                alerta.className = 'alert alert-danger mt-3';
                alerta.textContent = xhr.responseText || 'No se pudo actualizar el autor';
                alerta.classList.remove('d-none');
            });
    });
})();
</script>
