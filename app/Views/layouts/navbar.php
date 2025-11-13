<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Logo/Brand - Sección Izquierda -->
        <a class="navbar-brand" href="/">
            Biblioteca Virtual HZG
        </a>
        
        <!-- Botón de menú móvil -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú Principal -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/catalogo">
                        <i class="fas fa-book-open"></i> Catálogo
                    </a>
                </li>
                <?php if (session()->get('logged_in')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('catalogo/mis-prestamos') ?>">
                        <i class="fas fa-book"></i> Préstamos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('catalogo/favoritos') ?>">
                        <i class="fas fa-heart"></i> Favoritos
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/sobre-plataforma">
                        <i class="fas fa-info-circle"></i> Acerca de
                    </a>
                </li>
            </ul>
            
            <!-- Área de Usuario - Sección Derecha -->
            <div class="navbar-nav ms-auto">
                <?php if (session()->get('logged_in')): ?>
                <!-- Campanita de Notificaciones -->
                <div class="nav-item dropdown me-2">
                    <a class="nav-link position-relative" href="#" role="button" id="notificacionesDropdown" 
                       data-bs-toggle="dropdown" aria-expanded="false" 
                       title="Notificaciones">
                        <i class="fas fa-bell"></i>
                        <span id="badge-notificaciones" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notificaciones-dropdown" aria-labelledby="notificacionesDropdown">
                        <div class="dropdown-header d-flex justify-content-between align-items-center border-bottom pb-2">
                            <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Notificaciones</h6>
                            <div>
                                <button class="btn btn-sm btn-link text-decoration-none p-0" onclick="eliminarTodas()" title="Marcar todas como leídas">
                                    <i class="fas fa-check-double text-success"></i>
                                </button>
                            </div>
                        </div>
                        <div id="lista-notificaciones" class="py-2">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p class="mb-0">Cargando notificaciones...</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?= site_url('notificaciones/historial') ?>" class="dropdown-item text-center text-primary">
                            <i class="fas fa-history me-2"></i>Ver historial completo
                        </a>
                    </div>
                </div>
                
                <div class="nav-item dropdown">
                    <a class="nav-link"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= esc(session()->get('usuario')); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= site_url('perfil') ?>">
                                <i class="fas fa-user-edit"></i> Mi Perfil
                            </a>
                        </li>
                        <?php if (session()->get('nivel') === 'admin'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('admin'); ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= site_url('logout') ?>">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="nav-item">
                    <a href="/login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
