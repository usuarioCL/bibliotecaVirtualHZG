 <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">Biblioteca Virtual HZG</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-4 mb-lg-0 me-3 fs-4">
                <li class="nav-item" >
                    <a class="nav-link  " href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="/catalogo">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="#">Autores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link      " href="#">Temas</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link  " href="#">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="<?= base_url('admin'); ?>">
                        Dashboard
                    </a>
                </li>
            </ul>
            <form class="d-flex " role="login">
                <a href="/login" class="btn btn-primary me-2 fs-5">Iniciar Sesión</a>
            </form>
        </div>
    </div>
</nav>
