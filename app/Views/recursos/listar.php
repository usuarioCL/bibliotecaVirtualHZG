<?= $header; ?>

<div class="container mt-4">
  <!-- Título -->
  <div class="mb-3">
    <h3 class="fw-bold text-primary text-center mb-4 border-bottom pb-2">
      Lista de Recursos
    </h3>
  </div>

  <!-- Tabla -->
  <div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover align-middle">
      <colgroup>
        <col width="5%"><!--Id-->
        <col width="13%"><!--Titulo-->
        <col width="10%"><!--Año-->
        <col width="5%"><!--Paginas-->
        <col width="10%"><!--Encuadernación-->
        <col width="10%"><!--ISBN-->
        <col width="7%"><!--Edición-->
        <col width="10%"><!--Estado-->
        <col width="10%"><!--Stock-->
        <col width="15%"><!--Acciones-->
      </colgroup>
      <thead class="table-primary text-center">
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Año</th>
          <th>Páginas</th>
          <th>Encuadernación</th>
          <th>ISBN</th>
          <th>Edición</th>
          <th>Estado</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php foreach($recursos as $recurso): ?>
        <tr>
          <td><?= $recurso['idrecurso'] ?></td>
          <td><?= $recurso['titulo'] ?></td>
          <td><?= $recurso['anio'] ?></td>
          <td><?= $recurso['numpaginas'] ?></td>
          <td><?= $recurso['encuadernacion'] ?></td>
          <td><?= $recurso['isbn'] ?></td>
          <td><?= $recurso['numedicion'] ?></td>
          <td>
            <span class="badge 
              <?php if($recurso['estado'] === 'disponible') echo 'bg-success';
                    elseif($recurso['estado'] === 'prestado') echo 'bg-warning text-dark';
                    else echo 'bg-danger'; ?>">
              <?= ucfirst($recurso['estado']) ?>
           </span>
           </td>
          <td><?= $recurso['stock'] ?></td>
          
          <td>
            <a href="<?= base_url('recursos/editar/') ?><?= $recurso['idrecurso'] ?>" 
               class="btn btn-sm btn-warning me-1">
              Editar
            </a>
            <!-- Cambio en el nombre en la url -->
            <a href="<?= base_url('recursos/eliminar/') ?><?= $recurso['idrecurso'] ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('¿Seguro que deseas eliminar este recurso?');">
              Eliminar
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

    <!-- Paginación -->
  <div class="d-flex justify-content-center mt-4">
    <?= $pager->links('recursos', 'paginacion') ?>
  </div>



  <!-- Botón registrar -->
  <div class="mt-3 text-center">
    <a href="<?= base_url("recursos/crear"); ?>" class="btn btn-success">
      Registrar recurso
    </a>
  </div>
</div>

<?= $footer; ?>
