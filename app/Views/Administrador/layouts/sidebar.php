<!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav" class="px-2 py-2">
            <!-- PANEL PRINCIPAL -->
            <li class="sidebar-section">
              <div class="nav-small-cap d-flex align-items-center mb-2">
                <iconify-icon icon="solar:home-linear" class="nav-small-cap-icon fs-4 me-2"></iconify-icon>
                <span class="hide-menu fw-bold">Panel Principal</span>
              </div>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link dashboard-link" href="<?= base_url('admin'); ?>" aria-expanded="false">
                  <i class="ti ti-dashboard me-2"></i>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
            </li>
            
            <!-- GESTIÓN DE BIBLIOTECA -->
            <li><span class="sidebar-divider lg"></span></li>
            <li class="sidebar-section mb-3">
              <div class="nav-small-cap d-flex align-items-center mb-2">
                <iconify-icon icon="solar:book-bookmark-linear" class="nav-small-cap-icon fs-4 me-2"></iconify-icon>
                <span class="hide-menu fw-bold">Gestión de Biblioteca</span>
              </div>
              <!-- Recursos Bibliográficos -->
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-books"></i></span>
                    <span class="hide-menu">Recursos </span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('recursos'); ?>">
                      <i class="ti ti-book fs-5"></i>
                      <span class="hide-menu">Gestionar Recursos</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('autores'); ?>">
                      <i class="ti ti-user fs-5"></i>
                      <span class="hide-menu">Autores</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('categorias'); ?>">
                      <i class="ti ti-category fs-5"></i>
                      <span class="hide-menu">Categorías</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('editoriales'); ?>">
                      <i class="ti ti-building fs-5"></i>
                      <span class="hide-menu">Editoriales</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('recurso-digital'); ?>">
                      <i class="ti ti-device-desktop fs-5"></i>
                      <span class="hide-menu">Recurso Digital</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('recurso-fisico'); ?>">
                      <i class="ti ti-book fs-5"></i>
                      <span class="hide-menu">Recurso Físico</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Gestión de Préstamos -->
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-book-2"></i></span>
                    <span class="hide-menu">Préstamos</span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('prestamos'); ?>">
                      <i class="ti ti-bookmark fs-5"></i>
                      <span class="hide-menu">Préstamos Activos</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('solicitudes'); ?>">
                      <i class="ti ti-clock-hour-3 fs-5"></i>
                      <span class="hide-menu">Solicitudes Pendientes</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('devoluciones'); ?>">
                      <i class="ti ti-book-upload fs-5"></i>
                      <span class="hide-menu">Devoluciones</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('historial-prestamos'); ?>">
                      <i class="ti ti-history fs-5"></i>
                      <span class="hide-menu">Historial Completo</span>
                    </a>
                  </li>
                </ul>
              </li>
            </li>

            <!-- GESTIÓN DE USUARIOS -->
            <li><span class="sidebar-divider lg"></span></li>
            <li class="sidebar-section mb-3">
              <div class="nav-small-cap d-flex align-items-center mb-2">
                <iconify-icon icon="solar:users-group-rounded-linear" class="nav-small-cap-icon fs-4 me-2"></iconify-icon>
                <span class="hide-menu fw-bold">Gestión de Usuarios</span>
              </div>
              <!-- Usuarios y Personas -->
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-users"></i></span>
                    <span class="hide-menu">Usuarios</span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('usuarios'); ?>">
                      <i class="ti ti-user-plus fs-5"></i>
                      <span class="hide-menu">Gestionar Usuarios</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('personas'); ?>">
                      <i class="ti ti-address-book fs-5"></i>
                      <span class="hide-menu">Datos Personales</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/UsuariosRoles'); ?>">
                      <i class="ti ti-shield-check fs-5"></i>
                      <span class="hide-menu">Roles y Permisos</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Gestión Académica -->
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-school"></i></span>
                    <span class="hide-menu">G. Académica</span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('grupos'); ?>">
                      <i class="ti ti-users-group fs-5"></i>
                      <span class="hide-menu">Grupos y Secciones</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('matriculas'); ?>">
                      <i class="ti ti-clipboard-list fs-5"></i>
                      <span class="hide-menu">Matrículas</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Sanciones y Disciplina -->
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-ban"></i></span>
                    <span class="hide-menu">Sanciones </span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('sanciones'); ?>">
                      <i class="ti ti-alert-triangle fs-5"></i>
                      <span class="hide-menu">Gestionar Sanciones</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('tipos-sancion'); ?>">
                      <i class="ti ti-list-details fs-5"></i>
                      <span class="hide-menu">Tipos de Sanción</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/AlumnosSancionados'); ?>">
                      <i class="ti ti-user-x fs-5"></i>
                      <span class="hide-menu">Alumnos Sancionados</span>
                    </a>
                  </li>
                </ul>
              </li>
            </li>

            <!-- REPORTES Y ANÁLISIS -->
            <li><span class="sidebar-divider lg"></span></li>
            <li class="sidebar-section mb-3">
              <div class="nav-small-cap d-flex align-items-center mb-2">
                <iconify-icon icon="solar:chart-linear" class="nav-small-cap-icon fs-4 me-2"></iconify-icon>
                <span class="hide-menu fw-bold">Reportes y Análisis</span>
              </div>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex"><i class="ti ti-chart-bar"></i></span>
                    <span class="hide-menu">Estadísticas</span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level ms-3">
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/PrestamosAlumnos'); ?>">
                      <i class="ti ti-chart-line fs-5"></i>
                      <span class="hide-menu">Préstamos por Periodo</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/RecursosPopulares'); ?>">
                      <i class="ti ti-trending-up fs-5"></i>
                      <span class="hide-menu">Recursos más Populares</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('Administrador/vistas/ReaccionesUsuarios'); ?>">
                      <i class="ti ti-heart fs-5"></i>
                      <span class="hide-menu">Interacción de Usuarios</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center gap-3 ajax-link" href="<?= base_url('reportes/inventario'); ?>">
                      <i class="ti ti-boxes fs-5"></i>
                      <span class="hide-menu">Estado del Inventario</span>
                    </a>
                  </li>
                </ul>
              </li>
            </li>

            <!-- HERRAMIENTAS DE SISTEMA -->
            <li><span class="sidebar-divider lg"></span></li>
            <li class="sidebar-section mb-3">
              <div class="nav-small-cap d-flex align-items-center mb-2">
                <iconify-icon icon="solar:settings-linear" class="nav-small-cap-icon fs-4 me-2"></iconify-icon>
                <span class="hide-menu fw-bold">Herramientas del Sistema</span>
              </div>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link ajax-link" href="<?= base_url('admin/importar-datos'); ?>" aria-expanded="false">
                  <i class="ti ti-file-upload me-2"></i>
                  <span class="hide-menu">Importar Datos</span>
                </a>
              </li>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link ajax-link" href="<?= base_url('admin/exportar-datos'); ?>" aria-expanded="false">
                  <i class="ti ti-file-download me-2"></i>
                  <span class="hide-menu">Exportar Datos</span>
                </a>
              </li>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link ajax-link" href="<?= base_url('admin/backup'); ?>" aria-expanded="false">
                  <i class="ti ti-database-export me-2"></i>
                  <span class="hide-menu">Respaldo de Sistema</span>
                </a>
              </li>
              <li class="sidebar-item mb-2">
                <a class="sidebar-link ajax-link" href="<?= base_url('admin/configuracion'); ?>" aria-expanded="false">
                  <i class="ti ti-settings me-2"></i>
                  <span class="hide-menu">Configuración</span>
                </a>
              </li>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->