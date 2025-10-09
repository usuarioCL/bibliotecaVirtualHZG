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
                        <i class="fas fa-book"></i> Mis Préstamos
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
                        <i class="fas fa-info-circle"></i> Sobre La Plataforma
                    </a>
                </li>
            </ul>
            
            <!-- Área de Usuario - Sección Derecha -->
            <div class="navbar-nav ms-auto">
                <?php if (session()->get('logged_in')): ?>
                <div class="nav-item dropdown">
                    <a class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-inline-block text-truncate" style="max-width: 120px;">
                            <?= esc(session()->get('usuario')); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/perfil">
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
                            <a class="dropdown-item" href="/logout">
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
