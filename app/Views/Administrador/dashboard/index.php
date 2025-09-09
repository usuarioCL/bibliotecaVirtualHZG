<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel de Administración</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('./assets/images/logos/favicon.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('./assets/css/styles.min.css') ?>">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.php" class="text-nowrap logo-img">
            <img src="<?= base_url('./assets/images/logos/logo-wrappixel.svg') ?>" alt="./assets/images/logos/logo-wrappixel.svg" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link dashboard-link" href="<?= base_url('admin'); ?>" aria-expanded="false">
                <i class="ti ti-atom"></i>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <!-- ---------------------------------- -->
            <!-- Dashboard -->
            <!-- ---------------------------------- -->
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Accesos</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <i class="ti ti-database"></i>
                  </span>
                  <span class="hide-menu">Gestión Interna</span>
                </div>
              </a>
              <ul aria-expanded="false" class="collapse first-level">
                
              <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('recursos'); ?>">
                    <i class="ti ti-books fs-5"></i>
                    <span class="hide-menu">Recursos</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('usuarios'); ?>">
                    <i class="ti ti-users fs-5"></i>
                    <span class="hide-menu">Usuarios</span>
                  </a>
                </li>

              <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('autores'); ?>">
                    <i class="ti ti-pencil fs-5"></i>
                    <span class="hide-menu">Autores</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <i class="ti ti-eye"></i>
                  </span>
                  <span class="hide-menu">Vistas</span>
                </div>
                
              </a>
              <ul aria-expanded="false" class="collapse first-level">

                <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/UsuariosRoles'); ?>">
                    <i class="ti ti-checks fs-5"></i>
                    <span class="hide-menu">Roles de usuarios</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/PrestamosAlumnos'); ?>">
                    <i class="ti ti-book-2 fs-5"></i>
                    <span class="hide-menu">Prestamos realizados</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/ReaccionesUsuarios'); ?>">
                    <i class="ti ti-hearts fs-5"></i>
                    <span class="hide-menu">Reaccion de los usuarios</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/AlumnosSancionados'); ?>">
                    <i class="ti ti-ban fs-5"></i>
                    <span class="hide-menu">Alumnos Sancionados</span>
                  </a>
                </li>

              </ul>
            </li>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Herramientas</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link ajax-link" href="<?= base_url('admin/importar-datos'); ?>" aria-expanded="false">
                <i class="ti ti-file-upload"></i>
                <span class="hide-menu">Importar Datos</span>
              </a>
            </li>
          </ul>
            
        </nav>
        <!-- End Sidebar navigation -->
      </div>
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
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link " href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-bell"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="message-body">
                  <a href="javascript:void(0)" class="dropdown-item">
                    Item 1
                  </a>
                  <a href="javascript:void(0)" class="dropdown-item">
                    Item 2
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
               
              <li class="nav-item dropdown">
                <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?= base_url('./assets/images/profile/user-1.jpg') ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">My Account</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <!--  Main content wrapper -->
      <div class="body-wrapper-inner">
        <div class="container-fluid" >
          <!--  Row 1 -->
          <div class="row" >
            <div id="contenedor-principal">
              <?php include __DIR__ . '/default.php'; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="py-6 px-6 text-center mt-auto">
        <p class="mb-0 fs-4">Design and Developed by <a href="#"
            class="pe-1 text-primary text-decoration-underline">Wrappixel.com</a> Distributed by <a href="https://themewagon.com" target="_blank" >ThemeWagon</a></p>
      </footer>
    </div>
  </div>
  <script src="<?= base_url('./assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script>
  // Cargar contenido por defecto al inicializar
  $(document).ready(function() {
    cargarContenidoDefault();
  });

  // Función para cargar contenido por defecto
  function cargarContenidoDefault() {
    $('#contenedor-principal').html('<div class="text-center py-5">Cargando dashboard...</div>');
    $.get('<?= base_url("admin/dashboard-default") ?>', function(data) {
      $('#contenedor-principal').html(data);
    }).fail(function() {
      $('#contenedor-principal').html('<div class="text-danger text-center py-5">Error al cargar el dashboard.</div>');
    });
  }

  $(document).on('click', '.ajax-link', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $('#contenedor-principal').html('<div class="text-center py-5">Cargando...</div>');
    $.get(url, function(data) {
      $('#contenedor-principal').html(data);
      if (typeof window.initSidebarMenu === 'function') {
        window.initSidebarMenu();
      }
    }).fail(function() {
      $('#contenedor-principal').html('<div class="text-danger">Error al cargar el contenido.</div>');
    });
  });

  // Hacer que el enlace del Dashboard también cargue el contenido por defecto
  $(document).on('click', '.dashboard-link', function(e) {
    e.preventDefault();
    cargarContenidoDefault();
  });
  </script>
  <script src="<?= base_url('./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('./assets/js/sidebarmenu.js') ?>"></script>
  <script src="<?= base_url('./assets/js/app.min.js') ?>"></script>
  <script src="<?= base_url('./assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
  <script src="<?= base_url('./assets/libs/simplebar/dist/simplebar.js') ?>"></script>
  <script src="<?= base_url('./assets/js/dashboard.js') ?>"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>