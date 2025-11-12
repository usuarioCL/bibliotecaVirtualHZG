<?php
  $autores = $autores ?? [];
  $totalAutores = count($autores);
  $nacionalidades = array_unique(array_filter(array_map(static function ($autor) {
      return trim($autor['nacionalidad'] ?? '');
  }, $autores)));
  $totalNacionalidades = count($nacionalidades);
?>

<style>
  .autores-page {
    display: flex;
    flex-direction: column;
    gap: 1.75rem;
  }

  .autores-highlight {
    background: linear-gradient(135deg, #d92732 0%, #f96b11 100%);
    border-radius: 24px;
    color: #fff;
    padding: 1.75rem 2rem;
    box-shadow: 0 18px 35px rgba(217, 39, 50, 0.35);
    position: relative;
    overflow: hidden;
  }

  .autores-highlight::after {
    content: '';
    position: absolute;
    right: -60px;
    top: -60px;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
    filter: blur(0.5px);
  }

  .autores-highlight h2 {
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #ffffff;
  }

  .autores-highlight p {
    margin-bottom: 0;
    opacity: 0.9;
  }

  .autores-metrics {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .autores-metric-card {
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(4px);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    min-width: 180px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease, background 0.2s ease;
  }

  .autores-metric-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.24);
  }

  .autores-metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.22);
    display: grid;
    place-items: center;
    font-size: 1.5rem;
  }

  .autores-metric-info span {
    display: block;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.75;
  }

  .autores-metric-info strong {
    font-size: 1.5rem;
    font-weight: 700;
  }

  .autores-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 18px 35px rgba(15, 27, 77, 0.08);
    padding: 1.5rem 1.75rem;
    border: 1px solid rgba(233, 236, 241, 0.8);
  }

  .autores-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .autores-search {
    flex: 1;
    min-width: 260px;
    display: flex;
    gap: 0.75rem;
    align-items: center;
  }

  .autores-search .form-control {
    border-radius: 14px;
    border: 1px solid #e1e5ee;
    box-shadow: none;
    padding: 0.65rem 1rem;
  }

  .autores-search .form-control:focus {
    border-color: #d92732;
    box-shadow: 0 0 0 0.2rem rgba(217, 39, 50, 0.1);
  }

  .autores-search button {
    border-radius: 14px;
    padding-inline: 1.25rem;
    border: none;
    background: linear-gradient(135deg, #f5aa0a, #f96b11);
    color: #fff;
    font-weight: 600;
    box-shadow: 0 10px 18px rgba(249, 107, 17, 0.25);
  }

  .autores-search button:hover {
    filter: brightness(0.95);
  }

  .autores-actions .btn-primary {
    border-radius: 14px;
    padding-inline: 1.25rem;
    font-weight: 600;
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    border: none;
    box-shadow: 0 14px 26px rgba(255, 65, 108, 0.25);
  }

  .autores-table {
    margin-bottom: 0;
  }

  .autores-table thead th {
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #f1f3f7;
    color: #5c6378;
  }

  .autores-table tbody tr {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .autores-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(30, 41, 59, 0.08);
  }

  .autores-table td {
    border-color: #f5f6fa;
    vertical-align: middle;
  }

  .autores-table .badge-nacionalidad {
    border-radius: 50px;
    background: rgba(241, 245, 249, 0.75);
    border: 1px solid rgba(226, 232, 240, 0.8);
    color: #27364b;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    font-size: 0.78rem;
  }

  .autores-table .btn {
    border-radius: 10px;
    font-weight: 600;
    border: none;
  }

  .autores-table .btn-warning {
    background: linear-gradient(135deg, #febb06, #f28c03);
    color: #fff;
    box-shadow: 0 12px 20px rgba(242, 140, 3, 0.2);
  }

  .autores-table .btn-danger {
    background: linear-gradient(135deg, #ff5f6d, #d92732);
    box-shadow: 0 12px 22px rgba(217, 39, 50, 0.22);
  }

  .autores-empty {
    padding: 2.75rem 0;
    text-align: center;
    color: #9aa3b7;
    font-weight: 500;
    font-size: 1rem;
  }

  @media (max-width: 768px) {
    .autores-highlight {
      padding: 1.5rem;
    }

    .autores-actions {
      flex-direction: column;
      align-items: stretch;
    }

    .autores-actions .btn-primary {
      width: 100%;
      justify-content: center;
    }

    .autores-search {
      flex-direction: column;
      align-items: stretch;
    }

    .autores-search button {
      width: 100%;
    }

    .autores-table tbody tr {
      box-shadow: none;
    }
  }
</style>

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
          <i class="ti ti-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #97a0b9;"></i>
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
