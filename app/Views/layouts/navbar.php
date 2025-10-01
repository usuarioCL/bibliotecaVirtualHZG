<nav class="navbar navbar-expand-lg py-3">
    <div class="container d-flex align-items-center">
        <!-- Logo/Brand - Sección Izquierda -->
        <a class="navbar-brand fs-4 fw-bold d-flex align-items-center" href="/">Biblioteca Virtual HZG</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Spacer izquierdo -->
            <div class="flex-fill"></div>
            
            <!-- Menú Central -->
            <ul class="navbar-nav mb-0 fs-5 d-flex align-items-center">
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center" href="/"><i class="fas fa-home me-2"></i> Inicio</a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center" href="/catalogo"><i class="fas fa-book-open me-2"></i> Catálogo</a>
                </li>
                <?php if (session()->get('logged_in')): ?>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center" href="<?= site_url('catalogo/mis-prestamos') ?>"><i class="fas fa-book me-2"></i> Mis Préstamos</a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center" href="<?= site_url('catalogo/favoritos') ?>"><i class="fas fa-heart me-2"></i> Favoritos</a>
                </li>
                <?php endif; ?>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link d-flex align-items-center" href="/sobre-plataforma"><i class="fas fa-info-circle me-2"></i> Sobre La Plataforma</a>
                </li>
            </ul>
            
            <!-- Spacer derecho -->
            <div class="flex-fill"></div>
            
            <!-- Área de Usuario - Sección Derecha -->
            <div class="d-flex align-items-center h-100">
                <?php if (session()->get('logged_in')): ?>
                <div class="dropdown">
                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i><?= session()->get('usuario'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/perfil"><i class="fas fa-user-edit"></i> Mi Perfil</a></li>
                        <?php if (session()->get('nivel') === 'admin'): ?>
                        <li><a class="dropdown-item" href="<?= base_url('admin'); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="/login" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
