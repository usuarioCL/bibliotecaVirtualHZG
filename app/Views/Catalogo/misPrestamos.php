<?php
helper('prestamo');
?>
<?= $header ?>
<?= $navbar ?>

<!-- Estilos específicos para componentes de préstamos -->
<link rel="stylesheet" href="<?= base_url('assets/css/components/prestamos-components.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center h-100">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-book me-3"></i>Mis Préstamos
                    </h1>
                    <p class="text-muted mb-0">Gestiona tus libros prestados y consulta el historial</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card prestamos-counter-card text-white border-0 shadow-sm">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-bookmark fa-2x me-3"></i>
                            <div>
                                <small class="text-white-50 d-block">Préstamos Activos</small>
                                <h3 class="text-white mb-0 prestamos-counter-value" id="contadorActivos">
                                    <?= $contadorActivos ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de navegación -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill border-0 mb-0" id="prestamosTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-0 py-3" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab" aria-controls="activos" aria-selected="true">
                        <i class="fas fa-book-open me-2"></i>
                        <span class="d-none d-sm-inline">Préstamos </span>Activos
                        <span class="badge bg-primary ms-2"><?= $contadorActivos ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0 py-3" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">
                        <i class="fas fa-history me-2"></i>
                        <span class="d-none d-sm-inline">Historial</span>
                        <span class="d-sm-none">Hist.</span>
                        <span class="badge bg-secondary ms-2"><?= count($historialPrestamos) ?></span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Contenido de pestañas -->
    <div class="tab-content" id="prestamosTabContent">
        <!-- Préstamos Activos -->
        <div class="tab-pane fade show active" id="activos" role="tabpanel">
            <?php if (!empty($prestamosActivos)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-prestamos mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 fw-semibold">Libro</th>
                                        <th class="border-0 fw-semibold">Autor</th>
                                        <th class="border-0 fw-semibold">Fecha Préstamo</th>
                                        <th class="border-0 fw-semibold">Fecha Vencimiento</th>
                                        <th class="border-0 fw-semibold">Estado</th>
                                        <th class="border-0 fw-semibold text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="prestamosActivosLista">
                                    <?php foreach ($prestamosActivos as $prestamo): ?>
                                        <?php 
                                        $mostrarRenovar = true;
                                        $mostrarDevolver = false;
                                        echo view('partials/_prestamo_row', compact('prestamo', 'mostrarRenovar', 'mostrarDevolver'));
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row" id="sinPrestamosActivos">
                    <div class="col-12">
                        <?php
                        $titulo = 'No tienes préstamos activos';
                        $mensaje = '¡Explora nuestro catálogo y encuentra tu próximo libro!';
                        $icono = 'book-open';
                        $botones = [
                            [
                                'texto' => 'Explorar Catálogo',
                                'url' => site_url('catalogo'),
                                'clase' => 'btn-primary',
                                'icono' => 'search'
                            ],
                            [
                                'texto' => 'Libros Populares',
                                'url' => site_url('catalogo') . '?categoria=populares',
                                'clase' => 'btn-outline-primary',
                                'icono' => 'star'
                            ]
                        ];
                        $alertaAdmin = session()->get('nivel') === 'admin' 
                            ? '<strong>Modo Admin:</strong> Para probar la funcionalidad, puedes <a href="' . site_url('catalogo/insertar-datos-prueba') . '" class="alert-link text-decoration-none">insertar datos de prueba</a>'
                            : null;

                        echo view('partials/_empty_state_con_acciones', compact('titulo', 'mensaje', 'icono', 'botones', 'alertaAdmin'));
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Historial -->
        <div class="tab-pane fade" id="historial" role="tabpanel">
            <?php if (!empty($historialPrestamos)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-prestamos mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 fw-semibold">Libro</th>
                                        <th class="border-0 fw-semibold">Autor</th>
                                        <th class="border-0 fw-semibold">Fecha Préstamo</th>
                                        <th class="border-0 fw-semibold">Fecha Devolución</th>
                                        <th class="border-0 fw-semibold">Estado</th>
                                        <th class="border-0 fw-semibold text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="historialPrestamos">
                                    <?php foreach ($historialPrestamos as $prestamo): ?>
                                        <?php 
                                        $mostrarRenovar = false;
                                        $mostrarDevolver = false;
                                        echo view('partials/_prestamo_row', compact('prestamo', 'mostrarRenovar', 'mostrarDevolver'));
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Paginación del historial -->
                <?php if (count($historialPrestamos) > 10): ?>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Paginación del historial">
                        <ul class="pagination shadow-sm">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                    <i class="fas fa-chevron-left"></i>
                                    <span class="d-none d-sm-inline ms-1">Anterior</span>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <span class="d-none d-sm-inline me-1">Siguiente</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <?php
                    $titulo = 'No hay historial de préstamos';
                    $mensaje = 'Cuando realices préstamos aparecerán aquí';
                    $icono = 'history';
                    $botones = [
                        [
                            'texto' => 'Explorar Catálogo',
                            'url' => site_url('catalogo'),
                            'clase' => 'btn-primary',
                            'icono' => 'search'
                        ],
                        [
                            'texto' => 'Ver Novedades',
                            'url' => site_url('catalogo') . '?categoria=novedades',
                            'clase' => 'btn-outline-primary',
                            'icono' => 'sparkles'
                        ]
                    ];

                    echo view('partials/_empty_state_con_acciones', compact('titulo', 'mensaje', 'icono', 'botones'));
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para detalles del préstamo -->
<div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="prestamoModalLabel">
                    <i class="fas fa-book-open me-2"></i>Detalles del Préstamo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="prestamoModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando detalles del préstamo...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript externo para gestión de préstamos -->
<script src="<?= base_url('assets/js/mis-prestamos.js') ?>"></script>

<?= $footer ?>
