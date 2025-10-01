 <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="#">Biblioteca Virtual HZG</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-4 mb-lg-0 me-3 fs-5">
                <li class="nav-item" >
                    <a class="nav-link  " href="/"><i class="fas fa-home"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="/catalogo"><i class="fas fa-book-open"></i> Catálogo</a>
                </li>
                <?php if (session()->get('logged_in')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-book"></i> Mis Préstamos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-heart"></i> Favoritos</a>
                </li>
                <?php endif; ?>
                <li class="nav-item me-2">
                    <a class="nav-link  " href="/sobre-plataforma"><i class="fas fa-info-circle"></i> Sobre La Plataforma
                    </a>
                </li>
            </ul>
            <?php if (session()->get('logged_in')): ?>
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <?= session()->get('usuario'); ?>
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
            <a href="/login" class="btn btn-primary">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
