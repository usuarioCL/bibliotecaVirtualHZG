<?php
  $autores = $autores ?? [];
  $totalAutores = count($autores);
  $nacionalidades = array_unique(array_filter(array_map(static function ($autor) {
      return trim($autor['nacionalidad'] ?? '');
  }, $autores)));
  $totalNacionalidades = count($nacionalidades);
?>

<link rel="stylesheet" href="<?= base_url('assets/css/autores.css'); ?>">

<div class="autores-page">
  <section class="autores-highlight">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
      <div>
        <h2 class="mb-1">Panel de Autores</h2>
        <p class="mb-0">Administra la información de autores destacados de la Biblioteca Virtual HZG.</p>
      </div>
      <a href="<?= base_url('autores/crear'); ?>" class="btn btn-primary ajax-link">
        <i class="ti ti-plus"></i> Nuevo Autor
      </a>
    </div>

    <div class="autores-metrics">
      <div class="autores-metric-card">
        <div class="autores-metric-icon">
          <i class="ti ti-users"></i>
        </div>
        <div class="autores-metric-info">
          <span>Total de autores</span>
          <strong><?= number_format($totalAutores) ?></strong>
        </div>
      </div>
      <div class="autores-metric-card">
        <div class="autores-metric-icon">
          <i class="ti ti-world"></i>
        </div>
        <div class="autores-metric-info">
          <span>Nacionalidades distintas</span>
          <strong><?= number_format($totalNacionalidades) ?></strong>
        </div>
      </div>
      <div class="autores-metric-card">
        <div class="autores-metric-icon">
          <i class="ti ti-clock"></i>
        </div>
        <div class="autores-metric-info">
          <span>Última actualización</span>
          <strong><?= esc(date('d/m/Y')) ?></strong>
        </div>
      </div>
    </div>
  </section>

  <section class="autores-card">
    <div class="autores-actions">
      <form class="autores-search" method="get" action="<?= base_url('autores/buscar'); ?>" id="form-buscar-autores">
        <div class="flex-grow-1 position-relative">
          <i class="ti ti-search position-absolute autores-search-icon"></i>
          <input type="text" name="q" value="<?= esc($q ?? '') ?>" class="form-control ps-5" placeholder="Buscar por nombre, apellido o nacionalidad" />
        </div>
        <button type="submit">
          <i class="ti ti-filter-search me-1"></i> Buscar
        </button>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table autores-table align-middle">
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
          <?php if ($totalAutores > 0): ?>
            <?php foreach ($autores as $a): ?>
              <tr>
                <td class="fw-semibold text-secondary">#<?= (int)$a['idautor'] ?></td>
                <td class="fw-semibold text-dark"><?= esc($a['apeautor']) ?></td>
                <td><?= esc($a['nomautor']) ?></td>
                <td>
                  <?php if (!empty($a['nacionalidad'])): ?>
                    <span class="badge-nacionalidad"><i class="ti ti-flag me-1"></i><?= esc($a['nacionalidad']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">Sin registro</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="<?= base_url('autores/editar/' . $a['idautor']) ?>" class="btn btn-sm btn-warning ajax-link">
                      <i class="ti ti-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-autor" data-id="<?= (int)$a['idautor'] ?>">
                      <i class="ti ti-trash"></i> Eliminar
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="autores-empty">
                <i class="ti ti-users-off d-block fs-2 mb-2"></i>
                No se encontraron autores con los filtros aplicados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
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

  // Eliminar autor vía AJAX POST con SweetAlert
  $(document).on('click', '.btn-eliminar-autor', function(){
    var id = $(this).data('id');

    Swal.fire({
      title: '¿Eliminar autor?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(function(result){
      if (!result.isConfirmed) return;

      Swal.fire({
        title: 'Eliminando autor...',
        allowOutsideClick: false,
        didOpen: function(){
          Swal.showLoading();
        }
      });

      $.post('<?= base_url('autores/eliminar') ?>/' + id)
        .done(function(){
          Swal.fire({
            icon: 'success',
            title: 'Autor eliminado',
            text: 'El autor se eliminó correctamente.'
          }).then(function(){
            $('#contenedor-principal').html('<div class="text-center py-5">Actualizando listado...</div>');
            $.get('<?= base_url('autores') ?>', function(html){ $('#contenedor-principal').html(html); });
          });
        })
        .fail(function(xhr){
          Swal.fire({
            icon: 'error',
            title: 'No se pudo eliminar',
            text: xhr.responseText || 'No se pudo eliminar el autor'
          });
        });
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
