<!-- Cards de estadísticas rápidas -->
<div class="row">
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="bg-primary-subtle rounded-circle p-3">
            <i class="ti ti-books text-primary fs-6"></i>
          </div>
          <div class="ms-3">
            <h6 class="mb-0">Total Recursos</h6>
            <h3 class="mb-0"><?= $estadisticas['recursos']['total'] ?? 0 ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="bg-success-subtle rounded-circle p-3">
            <i class="ti ti-bookmark text-success fs-6"></i>
          </div>
          <div class="ms-3">
            <h6 class="mb-0">Préstamos Activos</h6>
            <h3 class="mb-0"><?= $estadisticas['prestamos']['activos'] ?? 0 ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="bg-warning-subtle rounded-circle p-3">
            <i class="ti ti-clock-hour-3 text-warning fs-6"></i>
          </div>
          <div class="ms-3">
            <h6 class="mb-0">Solicitudes Pendientes</h6>
            <h3 class="mb-0"><?= $estadisticas['prestamos']['solicitudes_pendientes'] ?? 0 ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="bg-danger-subtle rounded-circle p-3">
            <i class="ti ti-alert-triangle text-danger fs-6"></i>
          </div>
          <div class="ms-3">
            <h6 class="mb-0">Sanciones Activas</h6>
            <h3 class="mb-0"><?= $estadisticas['sanciones']['activas'] ?? 0 ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Primera fila: Cards de estadísticas principales -->
<div class="row mt-4">
  <div class="col-lg-8">
      <div class="card w-100">
      <div class="card-body">
          <div class="d-md-flex align-items-center">
          <div>
              <h4 class="card-title">Préstamos del Mes</h4>
              <p class="card-subtitle">
              Comparación de préstamos vs devoluciones
              </p>
          </div>
          <div class="ms-auto">
              <ul class="list-unstyled mb-0">
              <li class="list-inline-item text-primary">
                  <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                  Préstamos
              </li>
              <li class="list-inline-item text-info">
                  <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                  Devoluciones
              </li>
              </ul>
          </div>
          </div>
          <div class="mt-4 mx-n6" style="height: 300px;">
              <canvas id="prestamos-overview"></canvas>
          </div>
      </div>
      </div>
  </div>
  <div class="col-lg-4">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-start">
          <div>
              <h4 class="card-title">Estadísticas Semanales</h4>
              <p class="card-subtitle">Actividad de la biblioteca</p>
          </div>
          <div class="ms-auto">
              <div class="dropdown">
              <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="ti ti-dots fs-7"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Esta semana</a>
                  </li>
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Semana pasada</a>
                  </li>
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Este mes</a>
                  </li>
              </ul>
              </div>
          </div>
          </div>
          <div class="mt-3 pb-2 d-flex align-items-center">
          <span class="btn btn-primary rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-books fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Más Solicitado</h6>
              <span class="text-muted fs-4"><?= !empty($recursos_populares[0]['titulo']) ? $recursos_populares[0]['titulo'] : 'N/A' ?></span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted">+<?= !empty($recursos_populares[0]['total_prestamos']) ? $recursos_populares[0]['total_prestamos'] : '0' ?></span>
          </div>
          </div>
          <div class="py-2 d-flex align-items-center">
          <span class="btn btn-warning rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-star fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Mejor Valorado</h6>
              <span class="text-muted fs-4"><?= !empty($recursos_populares[1]['titulo']) ? $recursos_populares[1]['titulo'] : 'N/A' ?></span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted"><?= !empty($recursos_populares[1]['total_prestamos']) ? $recursos_populares[1]['total_prestamos'] : '0' ?>★</span>
          </div>
          </div>
          <div class="py-2 d-flex align-items-center">
          <span class="btn btn-success rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-users fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Total Usuarios</h6>
              <span class="text-muted fs-4"><?= $estadisticas['usuarios']['total'] ?? 0 ?> registrados</span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted"><?= $estadisticas['usuarios']['estudiantes'] ?? 0 ?></span>
          </div>
          </div>
      </div>
      </div>
  </div>
</div>

<!-- Segunda fila: Tabla de recursos -->
<div class="row mt-4">
  <div class="col-12">
      <div class="card">
      <div class="card-body">
          <div class="d-md-flex align-items-center">
          <div>
              <h4 class="card-title">Recursos Más Solicitados</h4>
              <p class="card-subtitle">
              Top 5 de recursos con mayor número de préstamos
              </p>
          </div>
          </div>
          <div class="table-responsive mt-4">
          <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
              <thead>
              <tr>
                  <th scope="col" class="px-0 text-muted">
                  Recurso
                  </th>
                  <th scope="col" class="px-0 text-muted">Autor</th>
                  <th scope="col" class="px-0 text-muted">
                  Estado
                  </th>
                  <th scope="col" class="px-0 text-muted text-end">
                  Préstamos
                  </th>
              </tr>
              </thead>
              <tbody>
              <?php if (!empty($recursos_populares)): ?>
                  <?php 
                  $colores = ['primary', 'warning', 'info', 'success', 'danger'];
                  $index = 0;
                  foreach ($recursos_populares as $recurso): 
                      $color = $colores[$index % count($colores)];
                      $badgeClass = '';
                      $estadoTexto = '';
                      
                      switch($recurso['estado']) {
                          case 'disponible':
                              $badgeClass = 'bg-success';
                              $estadoTexto = 'Disponible';
                              break;
                          case 'prestado':
                              $badgeClass = 'text-bg-primary';
                              $estadoTexto = 'Prestado';
                              break;
                          case 'perdido':
                              $badgeClass = 'bg-danger';
                              $estadoTexto = 'Perdido';
                              break;
                          default:
                              $badgeClass = 'bg-secondary';
                              $estadoTexto = ucfirst($recurso['estado']);
                      }
                      
                      // Determinar si el stock es bajo
                      if ($recurso['stock'] <= 2 && $recurso['estado'] == 'disponible') {
                          $badgeClass = 'bg-warning';
                          $estadoTexto = 'Stock Bajo';
                      }
                      
                      $index++;
                  ?>
                  <tr>
                      <td class="px-0">
                      <div class="d-flex align-items-center">
                          <div class="bg-<?= $color ?> rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                          <i class="ti ti-book-2 text-white"></i>
                          </div>
                          <div class="ms-3">
                          <h6 class="mb-0 fw-bolder"><?= esc($recurso['titulo']) ?></h6>
                          <span class="text-muted"><?= esc($recurso['categoria'] ?? 'Sin categoría') ?></span>
                          </div>
                      </div>
                      </td>
                      <td class="px-0"><?= esc($recurso['autor'] ?? 'Desconocido') ?></td>
                      <td class="px-0">
                      <span class="badge <?= $badgeClass ?>"><?= $estadoTexto ?></span>
                      </td>
                      <td class="px-0 text-dark fw-medium text-end">
                      <?= $recurso['total_prestamos'] ?? 0 ?> veces
                      </td>
                  </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="4" class="text-center py-4">
                          <i class="ti ti-inbox fs-4 text-muted"></i>
                          <p class="text-muted mb-0">No hay recursos disponibles</p>
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

<!-- Tercera fila: Usuarios activos y categorías -->
<div class="row mt-4">
  <div class="col-lg-6">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-center">
          <h4 class="card-title mb-0">Usuarios Activos</h4>
          </div>
          <div class="d-flex align-items-center flex-row mt-4">
          <div class="p-2 display-6 text-primary">
              <i class="ti ti-users"></i>
              <span><?= $estadisticas['usuarios']['total'] ?? 0 ?></span>
          </div>
          <div class="p-2">
              <h4 class="mb-0">Registrados</h4>
              <small>Biblioteca Virtual HZG</small>
          </div>
          </div>
          <table class="table table-borderless">
          <tbody>
              <tr>
              <td>Estudiantes</td>
              <td class="fw-medium"><?= $estadisticas['usuarios']['estudiantes'] ?? 0 ?> usuarios</td>
              </tr>
              <tr>
              <td>Docentes</td>
              <td class="fw-medium"><?= $estadisticas['usuarios']['docentes'] ?? 0 ?> usuarios</td>
              </tr>
              <tr>
              <td>Sanciones Activas</td>
              <td class="fw-medium"><?= $estadisticas['sanciones']['activas'] ?? 0 ?> sanciones</td>
              </tr>
              <tr>
              <td>Préstamos Activos</td>
              <td class="fw-medium"><?= $estadisticas['prestamos']['activos'] ?? 0 ?> recursos</td>
              </tr>
          </tbody>
          </table>
      </div>
      </div>
  </div>
  <div class="col-lg-6">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-center">
          <h4 class="card-title mb-0">Categorías Populares</h4>
          </div>
          <div class="mt-4">
          <?php if (!empty($categorias_populares)): ?>
              <?php 
              $colores = ['primary', 'success', 'warning', 'info'];
              $iconos = ['ti-book-2', 'ti-atom', 'ti-history', 'ti-palette'];
              $index = 0;
              foreach ($categorias_populares as $categoria): 
                  $color = $colores[$index % count($colores)];
                  $icono = $iconos[$index % count($iconos)];
                  $porcentaje = round($categoria['porcentaje'], 0);
                  $isLast = ($index === count($categorias_populares) - 1);
                  $index++;
              ?>
              <div class="d-flex align-items-center <?= !$isLast ? 'mb-3' : '' ?>">
                  <div class="me-3">
                  <div class="bg-<?= $color ?> rounded-circle p-2">
                      <i class="ti <?= $icono ?> text-white fs-6"></i>
                  </div>
                  </div>
                  <div class="flex-grow-1">
                  <h6 class="mb-0"><?= esc($categoria['categoria']) ?></h6>
                  <small class="text-muted"><?= $categoria['total_recursos'] ?> recursos</small>
                  </div>
                  <div class="text-end">
                  <h6 class="mb-0"><?= $porcentaje ?>%</h6>
                  <div class="progress" style="height: 4px; width: 60px;">
                      <div class="progress-bar bg-<?= $color ?>" style="width: <?= $porcentaje ?>%"></div>
                  </div>
                  </div>
              </div>
              <?php endforeach; ?>
          <?php else: ?>
              <div class="text-center py-4">
                  <i class="ti ti-inbox fs-4 text-muted"></i>
                  <p class="text-muted mb-0">No hay categorías disponibles</p>
              </div>
          <?php endif; ?>
          </div>
      </div>
      </div>
  </div>
</div>