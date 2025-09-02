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
                    <a class="nav-link  " href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="/catalogo">Catálogo</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link  " href="#">Sobre La Plataforma
                    </a>
                </li>
                <?php if (session()->get('logged_in') && session()->get('nivel') === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link  " href="<?= base_url('admin'); ?>">
                        Dashboard
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <?php if (session()->get('logged_in')): ?>
            <div class="d-flex align-items-center">
                <span class="nav-link  fs-5 me-3">Hola, <?= session()->get('usuario'); ?></span>
                <a href="/logout" class="nav-link ">Cerrar Sesión</a>
            </div>
            <?php else: ?>
            <form class="d-flex " role="login">
                <a href="/login" class="btn btn-primary me-2 fs-5">Iniciar Sesión</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</nav>
