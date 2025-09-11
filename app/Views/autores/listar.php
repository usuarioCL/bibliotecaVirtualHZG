<div class="card border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Autores</h4>
      <a href="<?= base_url('autores/crear'); ?>" class="btn btn-primary ajax-link">
        <i class="ti ti-plus"></i> Nuevo Autor
      </a>
    </div>

    <form class="row g-2 mb-3" method="get" action="<?= base_url('autores/buscar'); ?>" id="form-buscar-autores">
      <div class="col-md-6">
        <input type="text" name="q" value="<?= esc($q ?? '') ?>" class="form-control" placeholder="Buscar por nombre, apellido o nacionalidad" />
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary" type="submit">
          <i class="ti ti-search"></i> Buscar
        </button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Nacionalidad</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($autores)): ?>
            <?php foreach ($autores as $a): ?>
              <tr>
                <td><?= (int)$a['idautor'] ?></td>
                <td><?= esc($a['apeautor']) ?></td>
                <td><?= esc($a['nomautor']) ?></td>
                <td><?= esc($a['nacionalidad']) ?></td>
                <td class="text-end">
                  <a href="<?= base_url('autores/editar/' . $a['idautor']) ?>" class="btn btn-sm btn-warning ajax-link">
                    <i class="ti ti-edit"></i> Editar
                  </a>
                  <button type="button" class="btn btn-sm btn-danger btn-eliminar-autor" data-id="<?= (int)$a['idautor'] ?>">
                    <i class="ti ti-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No se encontraron autores.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center">
      <?= isset($pager) ? $pager->links('autores', 'paginacion') : '' ?>
    </div>
  </div>
</div>

<script>
(function(){
  // Enviar búsqueda por AJAX para mantener en el contenedor
  $('#form-buscar-autores').on('submit', function(e){
    e.preventDefault();
    var url = $(this).attr('action') + '?' + $(this).serialize();
    $('#contenedor-principal').html('<div class="text-center py-5">Buscando...</div>');
    $.get(url, function(html){ $('#contenedor-principal').html(html); });
  });

  // Eliminar autor vía AJAX POST
  $(document).on('click', '.btn-eliminar-autor', function(){
    var id = $(this).data('id');
    if (!confirm('¿Seguro que deseas eliminar este autor?')) return;
    $.post('<?= base_url('autores/eliminar') ?>/' + id, function(){
      // Recargar listado
      $.get('<?= base_url('autores') ?>', function(html){ $('#contenedor-principal').html(html); });
    }).fail(function(xhr){
      alert(xhr.responseText || 'No se pudo eliminar el autor');
    });
  });

  // Hacer que los enlaces de paginación funcionen con AJAX
  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $('#contenedor-principal').html('<div class="text-center py-5">Cargando...</div>');
    $.get(url, function(html){ $('#contenedor-principal').html(html); });
  });
})();
</script>
