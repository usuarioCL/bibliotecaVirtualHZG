<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detalles de Editorial - Biblioteca Virtual HZG</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('./assets/images/logos/favicon.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('./assets/css/styles.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/sidebar-hzg.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/modals.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/editoriales.css') ?>">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Overlay para móviles -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    
    <!-- Sidebar Start -->
    <aside class="left-sidebar sidebar-hzg" id="sidebar-hzg">
        <!-- Sidebar scroll-->
          <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin'); ?>" 
              class="text-nowrap logo-img d-flex align-items-center justify-content-center flex-grow-1 dashboard-link"
              aria-label="Regresar al panel principal de administración"
              title="Biblioteca Virtual HZG - Panel Principal"
              data-bs-toggle="tooltip"
              data-bs-placement="bottom">
              <div class="logo-container d-flex align-items-center">
                <img src="<?= base_url('./assets/images/logos/hzg.png') ?>" 
                    alt="Escudo Institucional HZG" 
                    class="logo-image"
                    loading="lazy"
                    onerror="this.src='<?= base_url('./assets/images/logos/default-logo.png') ?>'" />
                <div class="logo-text-container ms-3 d-none d-lg-flex flex-column">
                  <span class="logo-text-primary">Biblioteca Virtual HZG</span>
                  <span class="logo-text-secondary">Sistema de Gestión Integral</span>
                </div>
              </div>
            </a>
          </div>
          <!-- Sidebar navigation-->
          <?= $this->include('Administrador/layouts/sidebar') ?>
          <!-- End Sidebar navigation -->
        <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="ti ti-bell-ringing"></i>
                  <div class="notification bg-primary rounded-circle"></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <p class="mb-0 font-weight-medium d-flex align-items-center justify-content-between py-2">
                    Notificaciones
                  </p>
                  <div class="message-body">
                    <a href="javascript:void(0)" class="py-2 d-flex align-items-center">
                      <div class="me-3">
                        <img class="wd-30 ht-30 rounded-circle" src="<?= base_url('./assets/images/profile/user-1.jpg') ?>" alt="userr">
                      </div>
                      <div class="">
                        <h6 class="mb-0 text-dark">Sistema</h6>
                        <p class="text-muted mb-0">Bienvenido al sistema de gestión</p>
                      </div>
                    </a>
                  </div>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?= base_url('./assets/images/profile/user-1.jpg') ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">Mi Perfil</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">Mi Cuenta</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">Mi Tarea</p>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Cerrar Sesión</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->

      <div class="container-fluid">
        <!-- Encabezado de la página con breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-dark">
                            <i class="ti ti-building-store text-primary me-2"></i>
                            Detalles de Editorial
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('editoriales') ?>">Editoriales</a></li>
                                <li class="breadcrumb-item active"><?= esc($editorial['editorial']) ?></li>
                            </ol>
                        </nav>
                        <p class="text-muted mb-0 mt-1">Información detallada y recursos asociados</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('editoriales') ?>" class="btn btn-light btn-sm">
                            <i class="ti ti-arrow-left me-1"></i>
                            Volver
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="editarEditorial(<?= $editorial['ideditorial'] ?>)">
                            <i class="ti ti-edit me-1"></i>
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la editorial -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="editorial-avatar editorial-avatar-lg me-4">
                                <i class="ti ti-building-store"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-2"><?= esc($editorial['editorial']) ?></h3>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="ti ti-hash me-2"></i>
                                            <span>ID: <?= $editorial['ideditorial'] ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="ti ti-books me-2"></i>
                                            <span><?= count($recursos) ?> recurso(s) asociado(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="ti ti-chart-bar me-2"></i>
                            Estadísticas
                        </h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Total Recursos</span>
                                    <span class="editorial-badge editorial-badge-info"><?= count($recursos) ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Recursos Disponibles</span>
                                    <span class="editorial-badge editorial-badge-success"><?= count(array_filter($recursos, fn($r) => $r['estado'] === 'disponible')) ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Recursos Prestados</span>
                                    <span class="editorial-badge editorial-badge-warning"><?= count(array_filter($recursos, fn($r) => $r['estado'] === 'prestado')) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recursos asociados -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-books me-2"></i>
                            Recursos Asociados
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recursos)): ?>
                            <div class="editorial-empty-state">
                                <i class="ti ti-book-off empty-icon"></i>
                                <h5>No hay recursos asociados</h5>
                                <p>Esta editorial aún no tiene recursos registrados.</p>
                                <a href="<?= base_url('recursos/crear') ?>" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i>
                                    Crear Primer Recurso
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Filtros para recursos -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="editorial-search-container">
                                        <input type="text" 
                                               class="form-control editorial-search-input" 
                                               id="searchRecursos" 
                                               placeholder="Buscar recursos...">
                                        <i class="ti ti-search editorial-search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select editorial-filter-select" id="filterEstado">
                                        <option value="">Todos los estados</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="prestado">Prestado</option>
                                        <option value="perdido">Perdido</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select editorial-filter-select" id="sortRecursos">
                                        <option value="titulo_asc">Título A-Z</option>
                                        <option value="titulo_desc">Título Z-A</option>
                                        <option value="anio_desc">Año (Más reciente)</option>
                                        <option value="anio_asc">Año (Más antiguo)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tabla de recursos -->
                            <div class="table-responsive">
                                <table class="table editorial-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Recurso</th>
                                            <th>Categoría</th>
                                            <th class="text-center">Año</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recursosTableBody">
                                        <?php foreach ($recursos as $recurso): ?>
                                            <tr data-titulo="<?= strtolower(esc($recurso['titulo'])) ?>" 
                                                data-estado="<?= $recurso['estado'] ?>" 
                                                data-anio="<?= $recurso['anio'] ?>">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                                                                <i class="ti ti-book"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?= esc($recurso['titulo']) ?></h6>
                                                            <small class="text-muted">
                                                                ISBN: <?= $recurso['isbn'] ?: 'No disponible' ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span class="editorial-badge editorial-badge-info">
                                                            <?= esc($recurso['categoria'] ?? 'Sin categoría') ?>
                                                        </span>
                                                        <?php if ($recurso['subcategoria']): ?>
                                                            <br>
                                                            <small class="text-muted"><?= esc($recurso['subcategoria']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($recurso['anio']): ?>
                                                        <span class="editorial-badge editorial-badge-info"><?= $recurso['anio'] ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $estadoClass = match($recurso['estado']) {
                                                        'disponible' => 'editorial-badge-success',
                                                        'prestado' => 'editorial-badge-warning',
                                                        'perdido' => 'editorial-badge-danger',
                                                        default => 'editorial-badge-info'
                                                    };
                                                    ?>
                                                    <span class="editorial-badge <?= $estadoClass ?>">
                                                        <?= ucfirst($recurso['estado']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="editorial-badge editorial-badge-info">
                                                        <?= $recurso['stock'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="editorial-btn-group" role="group">
                                                        <a href="<?= base_url('recursos/detalles/' . $recurso['idrecurso']) ?>" 
                                                           class="editorial-btn editorial-btn-info editorial-tooltip" data-tooltip="Ver detalles">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="<?= base_url('recursos/editar/' . $recurso['idrecurso']) ?>" 
                                                           class="editorial-btn editorial-btn-primary editorial-tooltip" data-tooltip="Editar">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Información de paginación -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-0">
                                        Mostrando <span id="showingRecursos"><?= count($recursos) ?></span> de 
                                        <span id="totalRecursos"><?= count($recursos) ?></span> recursos
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal para editar editorial -->
  <div class="modal fade editorial-modal" id="modalEditorial" tabindex="-1" aria-labelledby="modalEditorialLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalEditorialLabel">
                      <i class="ti ti-edit me-2"></i>
                      Editar Editorial
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <form id="formEditorial">
                  <div class="modal-body">
                      <div class="editorial-form-group">
                          <label for="editorial" class="editorial-form-label">
                              Nombre de la Editorial <span class="text-danger">*</span>
                          </label>
                          <input type="text" 
                                 class="editorial-form-control" 
                                 id="editorial" 
                                 name="editorial" 
                                 value="<?= esc($editorial['editorial']) ?>"
                                 placeholder="Ej: Penguin Random House"
                                 required>
                          <div class="invalid-feedback"></div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                          <i class="ti ti-x me-1"></i>
                          Cancelar
                      </button>
                      <button type="submit" class="btn btn-primary" id="submitBtn">
                          <i class="ti ti-check me-1"></i>
                          Actualizar
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <!-- Scripts -->
  <script src="<?= base_url('./assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('./assets/js/sidebarmenu.js') ?>"></script>
  <script src="<?= base_url('./assets/js/app.min.js') ?>"></script>

  <script>
  $(document).ready(function() {
      let recursosData = <?= json_encode($recursos) ?>;
      let recursosFiltrados = [...recursosData];

      // Event listeners para filtros
      $('#searchRecursos').on('input', function() {
          filtrarRecursos();
      });

      $('#filterEstado').on('change', function() {
          filtrarRecursos();
      });

      $('#sortRecursos').on('change', function() {
          ordenarRecursos();
      });

      // Formulario de edición
      $('#formEditorial').on('submit', function(e) {
          e.preventDefault();
          guardarEditorial();
      });

      // Funciones
      function filtrarRecursos() {
          const termino = $('#searchRecursos').val().toLowerCase();
          const estado = $('#filterEstado').val();
          
          recursosFiltrados = recursosData.filter(recurso => {
              const coincideTitulo = recurso.titulo.toLowerCase().includes(termino);
              const coincideEstado = !estado || recurso.estado === estado;
              return coincideTitulo && coincideEstado;
          });

          mostrarRecursos();
      }

      function ordenarRecursos() {
          const orden = $('#sortRecursos').val();
          
          switch(orden) {
              case 'titulo_asc':
                  recursosFiltrados.sort((a, b) => a.titulo.localeCompare(b.titulo));
                  break;
              case 'titulo_desc':
                  recursosFiltrados.sort((a, b) => b.titulo.localeCompare(a.titulo));
                  break;
              case 'anio_desc':
                  recursosFiltrados.sort((a, b) => (b.anio || 0) - (a.anio || 0));
                  break;
              case 'anio_asc':
                  recursosFiltrados.sort((a, b) => (a.anio || 0) - (b.anio || 0));
                  break;
          }

          mostrarRecursos();
      }

      function mostrarRecursos() {
          const tbody = $('#recursosTableBody');
          
          if (recursosFiltrados.length === 0) {
              tbody.html(`
                  <tr>
                      <td colspan="6" class="text-center py-4">
                          <div class="editorial-empty-state">
                              <i class="ti ti-search empty-icon"></i>
                              <h5>No se encontraron recursos</h5>
                              <p>Intente ajustar los filtros de búsqueda.</p>
                          </div>
                      </td>
                  </tr>
              `);
              return;
          }

          let html = '';
          recursosFiltrados.forEach(function(recurso) {
              const estadoClass = {
                  'disponible': 'editorial-badge-success',
                  'prestado': 'editorial-badge-warning',
                  'perdido': 'editorial-badge-danger'
              }[recurso.estado] || 'editorial-badge-info';

              html += `
                  <tr class="editorial-slide-in">
                      <td>
                          <div class="d-flex align-items-center">
                              <div class="avatar-sm me-3">
                                  <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                                      <i class="ti ti-book"></i>
                                  </div>
                              </div>
                              <div>
                                  <h6 class="mb-0">${recurso.titulo}</h6>
                                  <small class="text-muted">
                                      ISBN: ${recurso.isbn || 'No disponible'}
                                  </small>
                              </div>
                          </div>
                      </td>
                      <td>
                          <div>
                              <span class="editorial-badge editorial-badge-info">
                                  ${recurso.categoria || 'Sin categoría'}
                              </span>
                              ${recurso.subcategoria ? `<br><small class="text-muted">${recurso.subcategoria}</small>` : ''}
                          </div>
                      </td>
                      <td class="text-center">
                          ${recurso.anio ? `<span class="editorial-badge editorial-badge-info">${recurso.anio}</span>` : '<span class="text-muted">-</span>'}
                      </td>
                      <td class="text-center">
                          <span class="editorial-badge ${estadoClass}">
                              ${recurso.estado.charAt(0).toUpperCase() + recurso.estado.slice(1)}
                          </span>
                      </td>
                      <td class="text-center">
                          <span class="editorial-badge editorial-badge-info">
                              ${recurso.stock}
                          </span>
                      </td>
                      <td class="text-end">
                          <div class="editorial-btn-group" role="group">
                              <a href="${window.location.origin}/recursos/detalles/${recurso.idrecurso}" 
                                 class="editorial-btn editorial-btn-info editorial-tooltip" data-tooltip="Ver detalles">
                                  <i class="ti ti-eye"></i>
                              </a>
                              <a href="${window.location.origin}/recursos/editar/${recurso.idrecurso}" 
                                 class="editorial-btn editorial-btn-primary editorial-tooltip" data-tooltip="Editar">
                                  <i class="ti ti-edit"></i>
                              </a>
                          </div>
                      </td>
                  </tr>
              `;
          });

          tbody.html(html);
          $('#showingRecursos').text(recursosFiltrados.length);
      }

      function guardarEditorial() {
          const formData = new FormData($('#formEditorial')[0]);

          $.ajax({
              url: '<?= base_url('editoriales/editar') ?>/<?= $editorial['ideditorial'] ?>',
              type: 'POST',
              data: formData,
              dataType: 'json',
              processData: false,
              contentType: false,
              beforeSend: function() {
                  $('#submitBtn').prop('disabled', true).html('<i class="ti ti-loader me-1"></i>Actualizando...');
              },
              success: function(response) {
                  if (response.success) {
                      $('#modalEditorial').modal('hide');
                      mostrarExito(response.message);
                      setTimeout(() => {
                          location.reload();
                      }, 1500);
                  } else {
                      mostrarErrores(response.errors);
                  }
              },
              error: function() {
                  mostrarError('Error de conexión');
              },
              complete: function() {
                  $('#submitBtn').prop('disabled', false).html('<i class="ti ti-check me-1"></i>Actualizar');
              }
          });
      }

      function mostrarErrores(errors) {
          Object.keys(errors).forEach(function(key) {
              $(`#${key}`).addClass('is-invalid');
              $(`#${key}`).siblings('.invalid-feedback').text(errors[key]);
          });
      }

      function mostrarExito(mensaje) {
          Swal.fire({
              icon: 'success',
              title: '¡Éxito!',
              text: mensaje,
              timer: 3000,
              showConfirmButton: false
          });
      }

      function mostrarError(mensaje) {
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: mensaje,
              confirmButtonText: 'Aceptar'
          });
      }

      // Función global para editar
      window.editarEditorial = function(id) {
          $('#modalEditorial').modal('show');
      };
  });
  </script>

</body>

</html>