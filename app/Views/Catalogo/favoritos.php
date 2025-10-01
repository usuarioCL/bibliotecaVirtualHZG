<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-heart me-3"></i>Mis Favoritos
                    </h1>
                    <p class="text-muted">Tu biblioteca personal de libros favoritos</p>
                </div>
                <div class="d-none d-md-block">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center py-2">
                            <small class="text-muted">Total Favoritos</small>
                            <h4 class="text-primary mb-0" id="contadorFavoritos">5</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar en favoritos..." id="buscarFavoritos">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filtroCategoria">
                <option value="">Todas las categorías</option>
                <option value="matematicas">Matemáticas</option>
                <option value="ciencias">Ciencias</option>
                <option value="historia">Historia</option>
                <option value="literatura">Literatura</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="ordenarPor">
                <option value="reciente">Más recientes</option>
                <option value="alfabetico">Orden alfabético</option>
                <option value="autor">Por autor</option>
                <option value="categoria">Por categoría</option>
            </select>
        </div>
    </div>

    <!-- Vista de grilla/lista -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Mostrando <span id="resultadosCount">5</span> favoritos</span>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="vistaGrilla">
                        <i class="fas fa-th"></i> Grilla
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="vistaLista">
                        <i class="fas fa-list"></i> Lista
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido de favoritos - Vista Grilla -->
    <div class="row" id="favoritosGrilla">
        <!-- Ejemplo de libro favorito 1 -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 position-relative">
                <div class="position-absolute top-0 end-0 p-2">
                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Quitar de favoritos">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </div>
                <div class="card-img-top-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= base_url('img/portada_1.png') ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Matemáticas Básicas">
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-2">Matemáticas Básicas</h6>
                    <p class="card-text text-muted small mb-2">
                        <strong>Autor:</strong> Juan Pérez
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <strong>Categoría:</strong> Matemáticas
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar-plus me-1"></i>Agregado: 25/Sep/2025
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-book me-1"></i>Prestar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de libro favorito 2 -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 position-relative">
                <div class="position-absolute top-0 end-0 p-2">
                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Quitar de favoritos">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </div>
                <div class="card-img-top-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= base_url('img/portada_2.png') ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Historia Universal">
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-2">Historia Universal</h6>
                    <p class="card-text text-muted small mb-2">
                        <strong>Autor:</strong> María García
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <strong>Categoría:</strong> Historia
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar-plus me-1"></i>Agregado: 20/Sep/2025
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-book me-1"></i>Prestar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de libro favorito 3 -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 position-relative">
                <div class="position-absolute top-0 end-0 p-2">
                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Quitar de favoritos">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </div>
                <div class="card-img-top-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= base_url('img/inicial.jpg') ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Ciencias Naturales">
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-2">Ciencias Naturales</h6>
                    <p class="card-text text-muted small mb-2">
                        <strong>Autor:</strong> Carlos López
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <strong>Categoría:</strong> Ciencias
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar-plus me-1"></i>Agregado: 15/Sep/2025
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-book me-1"></i>Prestar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de libro favorito 4 -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 position-relative">
                <div class="position-absolute top-0 end-0 p-2">
                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Quitar de favoritos">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </div>
                <div class="card-img-top-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= base_url('img/primaria.jpg') ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Álgebra Avanzada">
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-2">Álgebra Avanzada</h6>
                    <p class="card-text text-muted small mb-2">
                        <strong>Autor:</strong> Ana Martínez
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <strong>Categoría:</strong> Matemáticas
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar-plus me-1"></i>Agregado: 10/Sep/2025
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-book me-1"></i>Prestar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de libro favorito 5 -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 position-relative">
                <div class="position-absolute top-0 end-0 p-2">
                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Quitar de favoritos">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </div>
                <div class="card-img-top-container" style="height: 220px; overflow: hidden;">
                    <img src="<?= base_url('img/secundaria.jpg') ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Física Moderna">
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-2">Física Moderna</h6>
                    <p class="card-text text-muted small mb-2">
                        <strong>Autor:</strong> Roberto Silva
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <strong>Categoría:</strong> Ciencias
                    </p>
                    <p class="card-text text-muted small">
                        <i class="fas fa-calendar-plus me-1"></i>Agregado: 05/Sep/2025
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>Ver
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-book me-1"></i>Prestar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido de favoritos - Vista Lista (oculta por defecto) -->
    <div class="d-none" id="favoritosLista">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Libro</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>Fecha Agregado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= base_url('img/portada_1.png') ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                <div>
                                    <h6 class="mb-0">Matemáticas Básicas</h6>
                                    <small class="text-muted">ISBN: 978-123456789</small>
                                </div>
                            </div>
                        </td>
                        <td>Juan Pérez</td>
                        <td><span class="badge bg-primary">Matemáticas</span></td>
                        <td>25/Sep/2025</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary" title="Prestar">
                                    <i class="fas fa-book"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Quitar de favoritos">
                                    <i class="fas fa-heart-broken"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Más filas de ejemplo aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mensaje cuando no hay favoritos -->
    <div class="row d-none" id="sinFavoritos">
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No tienes libros favoritos</h4>
                <p class="text-muted mb-4">¡Explora nuestro catálogo y marca tus libros favoritos!</p>
                <a href="<?= site_url('catalogo') ?>" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Explorar Catálogo
                </a>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación de favoritos" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Anterior</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Siguiente</a>
            </li>
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de cambio de vista
    const vistaGrilla = document.getElementById('vistaGrilla');
    const vistaLista = document.getElementById('vistaLista');
    const favoritosGrilla = document.getElementById('favoritosGrilla');
    const favoritosLista = document.getElementById('favoritosLista');

    vistaGrilla.addEventListener('click', function() {
        this.classList.add('active');
        vistaLista.classList.remove('active');
        favoritosGrilla.classList.remove('d-none');
        favoritosLista.classList.add('d-none');
    });

    vistaLista.addEventListener('click', function() {
        this.classList.add('active');
        vistaGrilla.classList.remove('active');
        favoritosLista.classList.remove('d-none');
        favoritosGrilla.classList.add('d-none');
    });

    // Funcionalidad de búsqueda y filtros
    const buscarInput = document.getElementById('buscarFavoritos');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const ordenarPor = document.getElementById('ordenarPor');

    buscarInput.addEventListener('input', filtrarFavoritos);
    filtroCategoria.addEventListener('change', filtrarFavoritos);
    ordenarPor.addEventListener('change', filtrarFavoritos);

    function filtrarFavoritos() {
        // Aquí iría la lógica de filtrado
        console.log('Filtrando favoritos...');
    }

    // Funcionalidad de quitar de favoritos
    document.querySelectorAll('.btn-outline-danger').forEach(btn => {
        if (btn.title && btn.title.includes('Quitar')) {
            btn.addEventListener('click', function() {
                if (confirm('¿Estás seguro de que deseas quitar este libro de favoritos?')) {
                    // Lógica para quitar de favoritos
                    this.closest('.col-lg-3, tr').remove();
                    actualizarContador();
                    alert('Libro quitado de favoritos');
                }
            });
        }
    });

    // Funcionalidad de prestar libro
    document.querySelectorAll('.btn-primary').forEach(btn => {
        if (btn.textContent.includes('Prestar') || btn.title === 'Prestar') {
            btn.addEventListener('click', function() {
                if (confirm('¿Deseas solicitar el préstamo de este libro?')) {
                    // Lógica de préstamo
                    alert('Solicitud de préstamo enviada');
                }
            });
        }
    });

    // Actualizar contador
    function actualizarContador() {
        const totalFavoritos = document.querySelectorAll('#favoritosGrilla .col-lg-3').length;
        document.getElementById('contadorFavoritos').textContent = totalFavoritos;
        document.getElementById('resultadosCount').textContent = totalFavoritos;
        
        // Mostrar mensaje si no hay favoritos
        if (totalFavoritos === 0) {
            document.getElementById('sinFavoritos').classList.remove('d-none');
            favoritosGrilla.classList.add('d-none');
        }
    }

    actualizarContador();
});
</script>

<?= $footer ?>