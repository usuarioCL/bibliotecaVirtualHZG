<!-- Modal para Ayuda -->
<div class="modal fade" id="modalAyuda" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-help text-primary me-2"></i>
                    Centro de Ayuda - Sistema Biblioteca Virtual HZG
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de búsqueda -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control" id="buscarAyuda" placeholder="Buscar en la ayuda..." onkeyup="buscarEnAyuda()">
                            <button class="btn btn-outline-secondary" type="button" onclick="limpiarBusqueda()">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Accesos rápidos -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="mb-3">
                            <i class="ti ti-bolt text-warning me-2"></i>
                            Accesos Rápidos
                        </h6>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 text-center border-primary quick-help-card" onclick="mostrarSeccion('primeros-pasos')">
                                    <div class="card-body">
                                        <i class="ti ti-rocket text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="card-title">Primeros Pasos</h6>
                                        <p class="card-text text-muted small">Guía para comenzar</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 text-center border-success quick-help-card" onclick="mostrarSeccion('prestamos')">
                                    <div class="card-body">
                                        <i class="ti ti-book text-success mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="card-title">Préstamos</h6>
                                        <p class="card-text text-muted small">Gestión de préstamos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 text-center border-info quick-help-card" onclick="mostrarSeccion('usuarios')">
                                    <div class="card-body">
                                        <i class="ti ti-users text-info mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="card-title">Usuarios</h6>
                                        <p class="card-text text-muted small">Administrar usuarios</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 text-center border-warning quick-help-card" onclick="mostrarSeccion('reportes')">
                                    <div class="card-body">
                                        <i class="ti ti-chart-bar text-warning mb-2" style="font-size: 2rem;"></i>
                                        <h6 class="card-title">Reportes</h6>
                                        <p class="card-text text-muted small">Generar reportes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs de navegación -->
                <ul class="nav nav-tabs" id="ayudaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button" role="tab">
                            <i class="ti ti-help-circle me-2"></i>Preguntas Frecuentes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tutoriales-tab" data-bs-toggle="tab" data-bs-target="#tutoriales" type="button" role="tab">
                            <i class="ti ti-video me-2"></i>Tutoriales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="manuales-tab" data-bs-toggle="tab" data-bs-target="#manuales" type="button" role="tab">
                            <i class="ti ti-file-text me-2"></i>Manuales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#contacto" type="button" role="tab">
                            <i class="ti ti-message-circle me-2"></i>Contacto
                        </button>
                    </li>
                </ul>

                <!-- Contenido de los tabs -->
                <div class="tab-content pt-4" id="ayudaTabContent">
                    <!-- Tab Preguntas Frecuentes -->
                    <div class="tab-pane fade show active" id="faq" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="accordion" id="accordionFAQ">
                                    <!-- Categoría: General -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general">
                                                <i class="ti ti-settings text-primary me-2"></i>
                                                <strong>General (8)</strong>
                                            </button>
                                        </h2>
                                        <div id="general" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-primary mb-2">¿Cómo accedo al sistema por primera vez?</h6>
                                                    <p class="text-muted">Para acceder por primera vez, utiliza las credenciales proporcionadas por el administrador. Una vez dentro, se recomienda cambiar la contraseña desde el perfil de usuario.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-primary mb-2">¿Cómo recupero mi contraseña?</h6>
                                                    <p class="text-muted">En la pantalla de login, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo electrónico. Recibirás un enlace para restablecer tu contraseña.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-primary mb-2">¿El sistema funciona en dispositivos móviles?</h6>
                                                    <p class="text-muted">Sí, el sistema está diseñado para ser completamente responsivo y funciona correctamente en tablets y smartphones.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-primary mb-2">¿Puedo personalizar la interfaz?</h6>
                                                    <p class="text-muted">Desde tu perfil puedes cambiar el tema (claro/oscuro), idioma, y otras preferencias de visualización.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Categoría: Préstamos -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#prestamos-faq">
                                                <i class="ti ti-book text-success me-2"></i>
                                                <strong>Préstamos (12)</strong>
                                            </button>
                                        </h2>
                                        <div id="prestamos-faq" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-success mb-2">¿Cómo registro un nuevo préstamo?</h6>
                                                    <p class="text-muted">Ve a la sección "Préstamos" → "Solicitudes" → "Nuevo Préstamo". Busca el usuario y selecciona los recursos a prestar.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-success mb-2">¿Cómo proceso una devolución?</h6>
                                                    <p class="text-muted">En "Préstamos" → "Devoluciones", busca el préstamo y haz clic en "Procesar Devolución". Verifica el estado del material.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-success mb-2">¿Qué hago si un libro se devuelve dañado?</h6>
                                                    <p class="text-muted">Registra el daño en el sistema durante la devolución y genera una sanción si es necesario según las políticas de la biblioteca.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-success mb-2">¿Cómo extiendo un préstamo?</h6>
                                                    <p class="text-muted">En la lista de préstamos activos, selecciona el préstamo y haz clic en "Renovar" si cumple con las condiciones.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Categoría: Usuarios -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#usuarios-faq">
                                                <i class="ti ti-users text-info me-2"></i>
                                                <strong>Usuarios (10)</strong>
                                            </button>
                                        </h2>
                                        <div id="usuarios-faq" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-info mb-2">¿Cómo registro un nuevo usuario?</h6>
                                                    <p class="text-muted">En "Usuarios" haz clic en "Nuevo Usuario", completa los datos personales y del usuario. El sistema generará automáticamente las credenciales.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-info mb-2">¿Cómo desactivo un usuario?</h6>
                                                    <p class="text-muted">En la lista de usuarios, selecciona el usuario y cambia su estado a "Inactivo". Esto impedirá que pueda acceder al sistema.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-info mb-2">¿Puedo importar usuarios masivamente?</h6>
                                                    <p class="text-muted">Sí, en "Administración" → "Importar Datos" puedes cargar un archivo Excel con los datos de múltiples usuarios.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Categoría: Reportes -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#reportes-faq">
                                                <i class="ti ti-chart-bar text-warning me-2"></i>
                                                <strong>Reportes (6)</strong>
                                            </button>
                                        </h2>
                                        <div id="reportes-faq" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-warning mb-2">¿Cómo genero un reporte de préstamos?</h6>
                                                    <p class="text-muted">Ve a "Reportes" → "Préstamos", selecciona el período y tipo de reporte. Puedes exportarlo en PDF o Excel.</p>
                                                </div>
                                                <div class="faq-item mb-3">
                                                    <h6 class="text-warning mb-2">¿Los reportes se pueden programar?</h6>
                                                    <p class="text-muted">Sí, puedes configurar reportes automáticos que se envíen por email semanalmente o mensualmente desde Configuración.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Tutoriales -->
                    <div class="tab-pane fade" id="tutoriales" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-play text-primary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Introducción al Sistema</h6>
                                                <small class="text-muted">15 minutos • Básico</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Aprende los conceptos básicos y navegación del sistema de biblioteca virtual.</p>
                                        <button class="btn btn-outline-primary btn-sm" onclick="reproducirTutorial('intro')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-book text-success" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Gestión de Préstamos</h6>
                                                <small class="text-muted">25 minutos • Intermedio</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Tutorial completo sobre cómo gestionar préstamos, devoluciones y renovaciones.</p>
                                        <button class="btn btn-outline-success btn-sm" onclick="reproducirTutorial('prestamos')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-users text-info" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Administración de Usuarios</h6>
                                                <small class="text-muted">20 minutos • Intermedio</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Cómo crear, editar y gestionar usuarios del sistema bibliotecario.</p>
                                        <button class="btn btn-outline-info btn-sm" onclick="reproducirTutorial('usuarios')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-chart-bar text-warning" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Reportes y Estadísticas</h6>
                                                <small class="text-muted">18 minutos • Avanzado</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Genera reportes detallados y comprende las estadísticas del sistema.</p>
                                        <button class="btn btn-outline-warning btn-sm" onclick="reproducirTutorial('reportes')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-secondary bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-database text-secondary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Backup y Seguridad</h6>
                                                <small class="text-muted">12 minutos • Avanzado</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Aprende a realizar backups y mantener la seguridad del sistema.</p>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="reproducirTutorial('backup')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100 border-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                                <i class="ti ti-star text-success" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Tips y Trucos Avanzados</h6>
                                                <small class="text-success">¡Nuevo! • 22 minutos</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">Descubre funciones avanzadas y optimiza tu flujo de trabajo.</p>
                                        <button class="btn btn-success btn-sm" onclick="reproducirTutorial('avanzado')">
                                            <i class="ti ti-play me-1"></i>Ver Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Manuales -->
                    <div class="tab-pane fade" id="manuales" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Nota:</strong> Puedes descargar todos los manuales en formato PDF para consulta offline.
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-file-text text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="card-title">Manual de Usuario</h6>
                                        <p class="card-text text-muted small">Guía completa para usuarios del sistema</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary btn-sm" onclick="descargarManual('usuario')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" onclick="verManualOnline('usuario')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 45 páginas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-settings text-success" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="card-title">Manual de Administrador</h6>
                                        <p class="card-text text-muted small">Configuración y gestión avanzada del sistema</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="descargarManual('admin')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="verManualOnline('admin')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 78 páginas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-code text-info" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="card-title">Manual Técnico</h6>
                                        <p class="card-text text-muted small">Documentación técnica y API del sistema</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-info btn-sm" onclick="descargarManual('tecnico')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="verManualOnline('tecnico')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 124 páginas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-rocket text-warning" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="card-title">Guía de Inicio Rápido</h6>
                                        <p class="card-text text-muted small">Configuración inicial y primeros pasos</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-warning btn-sm" onclick="descargarManual('inicio')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-warning btn-sm" onclick="verManualOnline('inicio')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 12 páginas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-bug text-danger" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="card-title">Solución de Problemas</h6>
                                        <p class="card-text text-muted small">Diagnóstico y resolución de errores comunes</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-danger btn-sm" onclick="descargarManual('problemas')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="verManualOnline('problemas')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 32 páginas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="ti ti-sparkles text-success" style="font-size: 3rem;"></i>
                                            <span class="badge bg-success position-absolute top-0 start-100 translate-middle">Nuevo</span>
                                        </div>
                                        <h6 class="card-title">Novedades v2.1</h6>
                                        <p class="card-text text-muted small">Nuevas funcionalidades y mejoras</p>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-sm" onclick="descargarManual('novedades')">
                                                <i class="ti ti-download me-1"></i>Descargar PDF
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="verManualOnline('novedades')">
                                                <i class="ti ti-eye me-1"></i>Ver Online
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Versión 2.1 • 18 páginas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Contacto -->
                    <div class="tab-pane fade" id="contacto" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ti ti-message-circle text-primary me-2"></i>
                                            Enviar Consulta o Reporte de Problema
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="formContacto">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="tipo_consulta" class="form-label">Tipo de Consulta</label>
                                                        <select class="form-select" id="tipo_consulta" name="tipo" required>
                                                            <option value="">Seleccionar...</option>
                                                            <option value="consulta">Consulta General</option>
                                                            <option value="problema">Reporte de Problema</option>
                                                            <option value="mejora">Sugerencia de Mejora</option>
                                                            <option value="capacitacion">Solicitud de Capacitación</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="prioridad_consulta" class="form-label">Prioridad</label>
                                                        <select class="form-select" id="prioridad_consulta" name="prioridad" required>
                                                            <option value="baja">Baja</option>
                                                            <option value="media" selected>Media</option>
                                                            <option value="alta">Alta</option>
                                                            <option value="critica">Crítica</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="asunto_consulta" class="form-label">Asunto</label>
                                                <input type="text" class="form-control" id="asunto_consulta" name="asunto" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="mensaje_consulta" class="form-label">Descripción Detallada</label>
                                                <textarea class="form-control" id="mensaje_consulta" name="mensaje" rows="5" required placeholder="Describe tu consulta o problema de la manera más detallada posible..."></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="archivo_adjunto" class="form-label">Archivo Adjunto (opcional)</label>
                                                <input type="file" class="form-control" id="archivo_adjunto" name="archivo" accept=".jpg,.png,.pdf,.docx">
                                                <div class="form-text">Formatos permitidos: JPG, PNG, PDF, DOCX. Tamaño máximo: 5MB</div>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary" onclick="enviarConsulta(event)">
                                                    <i class="ti ti-send me-2"></i>Enviar Consulta
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ti ti-phone text-success me-2"></i>
                                            Información de Contacto
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ti ti-mail text-primary me-2"></i>
                                                <strong>Email Soporte:</strong>
                                            </div>
                                            <p class="text-muted ms-4 mb-0">soporte@bibliotecahzg.edu.pe</p>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ti ti-phone text-success me-2"></i>
                                                <strong>Teléfono:</strong>
                                            </div>
                                            <p class="text-muted ms-4 mb-0">+51 (01) 234-5678</p>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ti ti-clock text-warning me-2"></i>
                                                <strong>Horarios:</strong>
                                            </div>
                                            <p class="text-muted ms-4 mb-1">Lun - Vie: 8:00 AM - 6:00 PM</p>
                                            <p class="text-muted ms-4 mb-0">Sáb: 9:00 AM - 2:00 PM</p>
                                        </div>
                                        <div class="mb-0">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ti ti-map-pin text-danger me-2"></i>
                                                <strong>Ubicación:</strong>
                                            </div>
                                            <p class="text-muted ms-4 mb-0">Biblioteca Central HZG<br>Av. Universitaria 123<br>Lima, Perú</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ti ti-clock text-info me-2"></i>
                                            Tiempo de Respuesta
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <span class="badge bg-success me-2">Baja</span>
                                            <small>2-3 días hábiles</small>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge bg-warning me-2">Media</span>
                                            <small>1-2 días hábiles</small>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge bg-danger me-2">Alta</span>
                                            <small>4-6 horas</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-dark me-2">Crítica</span>
                                            <small>1-2 horas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-info" onclick="imprimirAyuda()">
                    <i class="ti ti-printer me-2"></i>Imprimir
                </button>
                <button type="button" class="btn btn-primary" onclick="descargarAyuda()">
                    <i class="ti ti-download me-2"></i>Descargar PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para buscar en la ayuda
function buscarEnAyuda() {
    const termino = document.getElementById('buscarAyuda').value.toLowerCase();
    const elementos = document.querySelectorAll('.faq-item, .card-title, .card-text');
    
    elementos.forEach(elemento => {
        const texto = elemento.textContent.toLowerCase();
        const contenedor = elemento.closest('.faq-item, .card');
        
        if (texto.includes(termino) || termino === '') {
            if (contenedor) contenedor.style.display = '';
        } else {
            if (contenedor) contenedor.style.display = 'none';
        }
    });
}

// Función para limpiar búsqueda
function limpiarBusqueda() {
    document.getElementById('buscarAyuda').value = '';
    buscarEnAyuda();
}

// Función para mostrar sección específica
function mostrarSeccion(seccion) {
    // Activar tab correspondiente
    const tabs = {
        'primeros-pasos': 'faq-tab',
        'prestamos': 'faq-tab',
        'usuarios': 'faq-tab',
        'reportes': 'faq-tab'
    };
    
    if (tabs[seccion]) {
        const tab = document.getElementById(tabs[seccion]);
        const tabInstance = new bootstrap.Tab(tab);
        tabInstance.show();
        
        // Expandir accordion correspondiente
        setTimeout(() => {
            const accordion = document.querySelector(`#${seccion}-faq, #general`);
            if (accordion && !accordion.classList.contains('show')) {
                accordion.classList.add('show');
            }
        }, 100);
    }
}

// Función para reproducir tutorial
function reproducirTutorial(tutorial) {
    Swal.fire({
        title: 'Reproducir Tutorial',
        html: `
            <div class="text-center">
                <i class="ti ti-video text-primary mb-3" style="font-size: 4rem;"></i>
                <p>El tutorial "${tutorial}" se reproducirá en una nueva ventana.</p>
                <div class="alert alert-info mt-3">
                    <small><i class="ti ti-info-circle me-1"></i>Los tutoriales están disponibles en formato interactivo con navegación paso a paso.</small>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Reproducir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Simular apertura de tutorial
            Swal.fire({
                title: 'Cargando Tutorial...',
                text: 'Preparando contenido interactivo',
                timer: 2000,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                Swal.fire({
                    title: 'Tutorial Iniciado',
                    text: 'El tutorial se ha abierto en una nueva pestaña',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    });
}

// Función para descargar manual
function descargarManual(tipo) {
    Swal.fire({
        title: 'Descargando Manual...',
        text: `Preparando el manual de ${tipo} para descarga`,
        timer: 2000,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            title: 'Descarga Iniciada',
            text: 'El manual se descargará automáticamente',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });
}

// Función para ver manual online
function verManualOnline(tipo) {
    Swal.fire({
        title: 'Ver Manual Online',
        html: `
            <div class="text-center">
                <i class="ti ti-file-text text-primary mb-3" style="font-size: 4rem;"></i>
                <p>El manual de ${tipo} se abrirá en el visor integrado.</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Abrir Visor',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Cargando...', 'Abriendo manual en el visor', 'info');
        }
    });
}

// Función para enviar consulta
function enviarConsulta(event) {
    event.preventDefault();
    const form = document.getElementById('formContacto');
    
    if (form.checkValidity()) {
        Swal.fire({
            title: 'Enviando Consulta...',
            text: 'Por favor espera mientras procesamos tu solicitud',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simular envío
        setTimeout(() => {
            Swal.fire({
                title: 'Consulta Enviada',
                html: `
                    <div class="text-success">
                        <i class="ti ti-check-circle mb-3" style="font-size: 3rem;"></i>
                        <p>Tu consulta ha sido enviada exitosamente.</p>
                        <p><strong>Ticket #:</strong> HZG-2025-001234</p>
                        <div class="alert alert-info mt-3">
                            <small>Recibirás una confirmación por email y te contactaremos según la prioridad seleccionada.</small>
                        </div>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Entendido'
            });
            
            // Limpiar formulario
            form.reset();
        }, 2000);
    } else {
        form.reportValidity();
    }
}

// Función para imprimir ayuda
function imprimirAyuda() {
    window.print();
}

// Función para descargar ayuda en PDF
function descargarAyuda() {
    Swal.fire({
        title: 'Descargar Ayuda Completa',
        text: 'Se generará un PDF con toda la información de ayuda',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Descargar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando PDF...', 'Tu archivo se descargará automáticamente', 'success');
        }
    });
}
</script>

<style>
/* Estilos específicos para el modal de ayuda */
#modalAyuda .nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

#modalAyuda .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 12px 20px;
    font-weight: 500;
}

#modalAyuda .nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    background: none;
}

#modalAyuda .quick-help-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

#modalAyuda .quick-help-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#modalAyuda .accordion-button {
    font-weight: 600;
}

#modalAyuda .accordion-button:not(.collapsed) {
    color: #0d6efd;
    background-color: #f8f9fa;
}

#modalAyuda .faq-item {
    padding: 15px;
    border-left: 3px solid #e9ecef;
    margin-bottom: 10px;
}

#modalAyuda .faq-item:hover {
    border-left-color: #0d6efd;
    background-color: #f8f9fa;
}

#modalAyuda .card {
    transition: all 0.3s ease;
}

#modalAyuda .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Z-index fixes */
#modalAyuda {
    z-index: 99999 !important;
}

#modalAyuda .modal-backdrop {
    z-index: 99998 !important;
}

#modalAyuda .modal-content {
    z-index: 100001 !important;
    position: relative !important;
}

#modalAyuda .modal-header,
#modalAyuda .modal-body,
#modalAyuda .modal-footer {
    z-index: 100002 !important;
    position: relative !important;
}

/* Reglas específicas con máxima especificidad */
body .modal#modalAyuda {
    z-index: 99999 !important;
}

body .modal#modalAyuda.show {
    z-index: 99999 !important;
    display: block !important;
}

html body .modal#modalAyuda {
    z-index: 99999 !important;
}

/* Fix específico para el contenedor principal */
#contenedor-principal .modal#modalAyuda {
    z-index: 99999 !important;
}

/* Asegurar que funcione en el contexto del dashboard */
.page-wrapper .modal#modalAyuda,
.body-wrapper .modal#modalAyuda {
    z-index: 99999 !important;
}
</style>