<!-- Sidebar navigation-->
<nav class="sidebar-nav scroll-sidebar" data-simplebar="" role="navigation" aria-label="Menú principal de administración">
  <ul id="sidebarnav" class="p-0" role="menubar">
    
    <!-- Dashboard -->
    <li class="sidebar-item mt-2" role="none">
      <a class="sidebar-link dashboard-link" 
         href="<?= base_url('admin'); ?>" 
         role="menuitem"
         aria-label="Ir al panel de control principal"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-dashboard" aria-hidden="true"></i>
          <span class="hide-menu">Panel Control</span>
        </div>
      </a>
    </li>
    
    <!-- Separador visual -->
    <li class="sidebar-divider my-2" role="separator"></li>
    
    <!-- Catálogo Bibliográfico -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de catálogo bibliográfico"
         title="Gestión del catálogo de recursos"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-books" aria-hidden="true"></i>
          <span class="hide-menu">Catálogo Bibliográfico</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('recursos'); ?>"
             title="Ver todos los recursos disponibles">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-books fs-5"></i>
              <span class="hide-menu">Todos los Recursos</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('autores'); ?>"
             title="Gestionar autores">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-user-plus fs-5"></i>
              <span class="hide-menu">Autores</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('admin/categorias'); ?>"
             title="Gestionar categorías">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-category-2 fs-5"></i>
              <span class="hide-menu">Categorías</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('editoriales/ajax'); ?>"
             title="Gestionar editoriales">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-building-store fs-5"></i>
              <span class="hide-menu">Editoriales</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('recurso-digital'); ?>"
             title="Gestionar recursos digitales">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-device-tablet fs-5"></i>
              <span class="hide-menu">Recursos Digitales</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('recurso-fisico'); ?>"
             title="Gestionar recursos físicos">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-book-2 fs-5"></i>
              <span class="hide-menu">Recursos Físicos</span>
            </div>
          </a>
        </li>
      </ul>
    </li>
    
    <!-- Sistema de Préstamos -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de sistema de préstamos"
         title="Gestionar préstamos y devoluciones"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-bookmark" aria-hidden="true"></i>
          <span class="hide-menu">Sistema de Préstamos</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('prestamos'); ?>"
             title="Ver préstamos activos">
            <div class="d-flex align-items-center gap-3">
                <i class="ti ti-bookmark fs-5"></i>
              <span class="hide-menu">Préstamos Activos</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('solicitudes'); ?>"
             title="Gestionar solicitudes pendientes">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-clock-hour-3 fs-5"></i>
              <span class="hide-menu">Solicitudes Pendientes</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('devoluciones'); ?>"
             title="Gestionar devoluciones">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-book-upload fs-5"></i>
              <span class="hide-menu">Devoluciones</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('historial-prestamos'); ?>"
             title="Ver historial completo">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-history fs-5"></i>
              <span class="hide-menu">Historial Completo</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestión de Usuarios -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de gestión de usuarios"
         title="Administrar usuarios del sistema"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-users" aria-hidden="true"></i>
          <span class="hide-menu">Gestión de Usuarios</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('usuarios'); ?>"
             title="Ver todos los usuarios registrados">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-user-circle fs-5"></i>
              <span class="hide-menu">Todos los Usuarios</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('matriculas'); ?>"
             title="Gestionar estudiantes">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-school fs-5"></i>
              <span class="hide-menu">Estudiantes</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('docentes'); ?>"
             title="Gestionar docentes">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-user-check fs-5"></i>
              <span class="hide-menu">Docentes</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('historial-usuarios'); ?>"
             title="Ver historial de acciones de usuarios">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-history fs-5"></i>
              <span class="hide-menu">Historial</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Control y Sanciones -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de sanciones"
         title="Gestionar sanciones disciplinarias"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-alert-triangle" aria-hidden="true"></i>
          <span class="hide-menu">Control y Sanciones</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('sanciones'); ?>"
             title="Ver sanciones activas">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-shield-x fs-5"></i>
              <span class="hide-menu">Sanciones Activas</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('sanciones/historial'); ?>"
             title="Ver historial de sanciones">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-clock-record fs-5"></i>
              <span class="hide-menu">Historial</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Reportes y Estadísticas -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de reportes"
         title="Ver reportes y estadísticas"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-chart-bar" aria-hidden="true"></i>
          <span class="hide-menu">Reportes y Estadísticas</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('Administrador/vistas/PrestamosAlumnos'); ?>"
             title="Reporte de préstamos por estudiante">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-chart-line fs-5"></i>
              <span class="hide-menu">Préstamos por Usuario</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('Administrador/vistas/RecursosPopulares'); ?>"
             title="Recursos más solicitados">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-trending-up fs-5"></i>
              <span class="hide-menu">Recursos Populares</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('reportes/inventario'); ?>"
             title="Estado del inventario">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-clipboard-list fs-5"></i>
              <span class="hide-menu">Inventario</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Administración de Datos -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link has-arrow" 
         href="javascript:void(0)" 
         role="button"
         aria-expanded="false"
         aria-haspopup="true"
         aria-label="Expandir menú de administración de datos"
         title="Gestionar base de datos y respaldos"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-database" aria-hidden="true"></i>
          <span class="hide-menu">Administración de Datos</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level" role="menu" aria-hidden="true">
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('admin/importar-datos'); ?>"
             title="Importar datos desde archivo">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-file-upload fs-5"></i>
              <span class="hide-menu">Importar Datos</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link ajax-link" 
             href="<?= base_url('admin/backup'); ?>"
             title="Gestionar respaldos de la base de datos">
            <div class="d-flex align-items-center gap-3">
              <i class="ti ti-database-export fs-5"></i>
              <span class="hide-menu">Respaldos</span>
            </div>
          </a>
        </li>
      </ul>
    </li>
    
    <!-- Separador visual -->
    <li class="sidebar-divider my-2" role="separator"></li>
    
    <!-- Configuración -->
    <li class="sidebar-item" role="none">
      <a class="sidebar-link ajax-link" 
         href="<?= base_url('admin/configuracion'); ?>" 
         role="menuitem"
         aria-label="Ir a configuración del sistema"
         title="Configuración del sistema"
         tabindex="0">
        <div class="d-flex align-items-center gap-3">
          <i class="ti ti-settings" aria-hidden="true"></i>
          <span class="hide-menu">Configuración</span>
        </div>
      </a>
    </li>
  </ul>
</nav>
<!-- End Sidebar navigation -->