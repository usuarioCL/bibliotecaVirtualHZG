<div class="card border-0">
  <div class="card-body">
    <h4 class="mb-3">Crear Autor</h4>
    <form id="form-crear-autor" method="post" action="<?= base_url('autores/guardar'); ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Apellidos</label>
          <input type="text" name="apeautor" class="form-control" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Nombres</label>
          <input type="text" name="nomautor" class="form-control" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Nacionalidad</label>
          <input type="text" name="nacionalidad" class="form-control" />
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-device-floppy"></i> Guardar
        </button>
        <a href="<?= base_url('autores'); ?>" class="btn btn-secondary ajax-link">
          <i class="ti ti-arrow-left"></i> Volver
        </a>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  // Enviar por AJAX para mantener en el contenedor del dashboard
  $('#form-crear-autor').on('submit', function(e){
    e.preventDefault();
    var $form = $(this);
    $.post($form.attr('action'), $form.serialize())
      .done(function(){
        $.get('<?= base_url('autores') ?>', function(html){ $('#contenedor-principal').html(html); });
      })
      .fail(function(xhr){
        alert(xhr.responseText || 'No se pudo guardar');
      });
  });
})();
</script>
