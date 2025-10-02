<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel de Administración</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('./assets/images/logos/favicon.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('./assets/css/styles.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/sidebar-hzg.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/modals.css') ?>">
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
        <nav class="navbar navbar-expand-lg">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)"
                aria-label="Alternar menú lateral"
                title="Mostrar/Ocultar menú lateral">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" 
                aria-expanded="false"
                aria-label="Notificaciones"
                title="Ver notificaciones">
                <i class="ti ti-bell"></i>
                <div class="notification rounded-circle"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="message-body">
                  <a href="javascript:void(0)" class="dropdown-item">
                    <i class="ti ti-info-circle"></i>
                    <p class="mb-0">Nueva notificación disponible</p>
                  </a>
                  <a href="javascript:void(0)" class="dropdown-item">
                    <i class="ti ti-alert-circle"></i>
                    <p class="mb-0">Actualización del sistema</p>
                  </a>
                  <a href="javascript:void(0)" class="dropdown-item">
                    <i class="ti ti-check-circle"></i>
                    <p class="mb-0">Todas las notificaciones</p>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false"
                  aria-label="Menú de usuario"
                  title="Opciones de usuario">
                  <img src="<?= base_url('./assets/images/profile/user-1.jpg') ?>" 
                      alt="Foto de perfil del usuario" 
                      width="35" 
                      height="35" 
                      class="rounded-circle"
                      loading="lazy"
                      onerror="this.src='<?= base_url('./assets/images/profile/default-avatar.png') ?>'">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="dropdown-item">
                      <i class="ti ti-user"></i>
                      <p class="mb-0">Mi Perfil</p>
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item">
                      <i class="ti ti-settings"></i>
                      <p class="mb-0">Configuración</p>
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item">
                      <i class="ti ti-list-check"></i>
                      <p class="mb-0">Mis Tareas</p>
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item">
                      <i class="ti ti-help"></i>
                      <p class="mb-0">Ayuda</p>
                    </a>
                    <a href="<?= base_url('/') ?>" class="dropdown-item">
                      <i class="ti ti-book"></i>
                      <p class="mb-0">Biblioteca Virtual</p>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-primary d-block">
                      <i class="ti ti-logout me-2"></i>Cerrar Sesión
                    </a>
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
    </div>
  </div>
  <script src="<?= base_url('./assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('./assets/js/modal-fix.js') ?>"></script>
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
  
  // Manejar clics en enlaces AJAX
  $(document).on('click', '.ajax-link', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var clickedUrl = url; // Guardar la URL del enlace clickeado
    $('#contenedor-principal').html('<div class="text-center py-5">Cargando...</div>');
    $.get(url, function(data) {
      $('#contenedor-principal').html(data);
      if (typeof window.initSidebarMenu === 'function') {
        window.initSidebarMenu(clickedUrl);
      }
      // Reinicializar modales después de carga AJAX
      setTimeout(function() {
        if (url.includes('recursos') && typeof window.reinicializarModalRecurso === 'function') {
          window.reinicializarModalRecurso();
        }
        if (url.includes('usuarios') && typeof window.reinicializarModalUsuario === 'function') {
          window.reinicializarModalUsuario();
        }
        if (url.includes('usuarios') && typeof window.reinicializarModalDetalleUsuario === 'function') {
          window.reinicializarModalDetalleUsuario();
        }
      }, 100);
    }).fail(function() {
      $('#contenedor-principal').html('<div class="text-danger">Error al cargar el contenido.</div>');
    });
  });

  // Hacer que el enlace del Dashboard también cargue el contenido por defecto
  $(document).on('click', '.dashboard-link', function(e) {
    e.preventDefault();
    var dashboardUrl = $(this).attr('href');
    cargarContenidoDefault();
    // Actualizar el estado del sidebar para el dashboard
    if (typeof window.initSidebarMenu === 'function') {
      window.initSidebarMenu(dashboardUrl);
    }
  });

  // Funcionalidad del sidebar para móviles
  function initSidebarToggle() {
    const sidebarToggler = document.getElementById('headerCollapse');
    const sidebar = document.getElementById('sidebar-hzg');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;
    const pageWrapper = document.getElementById('main-wrapper');

    if (sidebarToggler && sidebar && overlay) {
      // Toggle sidebar
      sidebarToggler.addEventListener('click', function(e) {
        e.preventDefault();
        toggleSidebar();
      });

      // Cerrar sidebar al hacer click en overlay
      overlay.addEventListener('click', function() {
        closeSidebar();
      });

      // Cerrar sidebar con tecla ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
          closeSidebar();
        }
      });

      // Funciones helper
      function toggleSidebar() {
        if (sidebar.classList.contains('show')) {
          closeSidebar();
        } else {
          openSidebar();
        }
      }

      function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        body.classList.add('sidebar-open');
        pageWrapper.classList.add('sidebar-open');
      }

      function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        body.classList.remove('sidebar-open');
        pageWrapper.classList.remove('sidebar-open');
      }

      // Cerrar sidebar automáticamente en desktop
      function handleResize() {
        if (window.innerWidth >= 992) {
          closeSidebar();
        }
      }

      window.addEventListener('resize', handleResize);
    }
  }

  // Inicializar funcionalidad del sidebar
  $(document).ready(function() {
    initSidebarToggle();
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