<?php if (isset($header)): ?>
<?= $header ?>
<?= $navbar ?>
<div class="container mt-4">
<?php else: ?>
<!-- Vista para modal - sin header/footer -->
<div class="container-fluid p-0">
<?php endif; ?>
    <?php if (isset($header)): ?>
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('recursos') ?>">Recursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detalles</li>
                </ol>
            </nav>
        </div>
    </div>
    <?php endif; ?>

    <div class="row <?= isset($header) ? '' : 'p-3' ?>">
        <!-- Portada del libro -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (!empty($recurso['portada'])): ?>
                        <img src="<?= base_url(esc($recurso['portada'])) ?>?v=<?= time() ?>" 
                             alt="Portada de <?= esc($recurso['titulo']) ?>" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 400px; width: auto;"
                             onerror="console.error('Error cargando imagen:', this.src); this.src='<?= base_url('img/portada_default.png') ?>'"
                             onload="console.log('Imagen cargada correctamente:', this.src)">
                    <?php else: ?>
                        <img src="<?= base_url('img/portada_default.png') ?>" 
                             alt="Sin portada" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 400px; width: auto;">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Información del recurso -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 modal-titulo-libro">
                        <i class="fas fa-book me-2"></i>
                        <?= esc($recurso['titulo']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Información básica -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Información Básica</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Autores:</strong></td>
                                    <td><?= esc($recurso['nomautor'] ?? 'No especificado') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Año:</strong></td>
                                    <td><?= esc($recurso['anio'] ?? 'No especificado') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Páginas:</strong></td>
                                    <td><?= esc($recurso['numpaginas'] ?? 'No especificado') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>ISBN:</strong></td>
                                    <td><?= esc($recurso['isbn'] ?? 'No especificado') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Edición:</strong></td>
                                    <td><?= esc($recurso['numedicion'] ?? 'No especificado') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Clasificación</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Categoría:</strong></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= esc($recurso['categoria'] ?? 'No especificada') ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Subcategoría:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= esc($recurso['subcategoria'] ?? 'No especificada') ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Editorial:</strong></td>
                                    <td><?= esc($recurso['editorial'] ?? 'No especificada') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= esc($recurso['tiporecurso'] ?? 'No especificado') ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Nivel:</strong></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?= esc($recurso['nivel'] ?? 'No especificado') ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Estado y disponibilidad -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Estado y Disponibilidad</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-<?= $recurso['estado'] === 'disponible' ? 'success' : 'warning' ?>">
                                                <i class="fas fa-<?= $recurso['estado'] === 'disponible' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                                            </h5>
                                            <p class="card-text">
                                                <strong>Estado:</strong><br>
                                                <span class="badge bg-<?= $recurso['estado'] === 'disponible' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst(esc($recurso['estado'])) ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-info">
                                                <i class="fas fa-boxes"></i>
                                            </h5>
                                            <p class="card-text">
                                                <strong>Stock:</strong><br>
                                                <span class="badge bg-info fs-6">
                                                    <?= esc($recurso['stock'] ?? '0') ?> ejemplares
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">
                                                <i class="fas fa-id-card"></i>
                                            </h5>
                                            <p class="card-text">
                                                <strong>ID:</strong><br>
                                                <span class="badge bg-primary">
                                                    #<?= esc($recurso['idrecurso']) ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Acciones</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php 
                                // Determinar si es un recurso digital
                                $esDigital = false;
                                
                                if (isset($recurso['tiporecurso']) && stripos($recurso['tiporecurso'], 'digital') !== false) {
                                    $esDigital = true;
                                } elseif (isset($recurso['idtiporecurso']) && $recurso['idtiporecurso'] == 2) {
                                    $esDigital = true;
                                } elseif (isset($recurso['rutaarchivo']) && !empty($recurso['rutaarchivo'])) {
                                    $esDigital = true;
                                } elseif (isset($recurso['archivo']) && !empty($recurso['archivo'])) {
                                    $esDigital = true;
                                }
                                ?>
                                
                                <?php if ($esDigital): ?>
                                    <!-- Botón para recursos digitales -->
                                    <button class="btn btn-success" onclick="
                                        // Cerrar modal
                                        const modal = document.getElementById('libroModal');
                                        if (modal) {
                                            const modalInstance = bootstrap.Modal.getInstance(modal);
                                            if (modalInstance) {
                                                modalInstance.hide();
                                            }
                                        }
                                        // Llamar a leerPDFDirecto después de cerrar el modal
                                        setTimeout(() => {
                                            if (typeof leerPDFDirecto === 'function') {
                                                leerPDFDirecto('<?= base_url($recurso['rutaarchivo'] ?? $recurso['archivo'] ?? '') ?>', '<?= esc($recurso['titulo']) ?>');
                                            } else {
                                                window.open('<?= base_url($recurso['rutaarchivo'] ?? $recurso['archivo'] ?? '') ?>', '_blank');
                                            }}, 300);">
                                        <i class="fas fa-book-open"></i> Leer
                                    </button>
                                <?php else: ?>
                                    <!-- Botón para recursos físicos -->
                                    <?php if ($recurso['estado'] === 'disponible' && $recurso['stock'] > 0): ?>
                                        <?php if (session()->get('logged_in')): ?>
                                            <button class="btn btn-success" onclick="solicitarPrestamo(<?= $recurso['idrecurso'] ?>)">
                                                <i class="fas fa-hand-holding"></i> Solicitar Préstamo
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-success" onclick="mostrarAlertaLogin('solicitar préstamo')">
                                                <i class="fas fa-hand-holding"></i> Solicitar Préstamo
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (session()->get('logged_in')): ?>
                                    <?php 
                                    // Verificar si el recurso ya está en favoritos
                                    $favoritoModel = new \App\Models\FavoritoModel();
                                    $usuarioModel = new \App\Models\UsuarioModel();
                                    $nomuser = session()->get('usuario');
                                    $usuario = $usuarioModel->where('nomuser', $nomuser)->first();
                                    $idusuario = $usuario ? $usuario['idusuario'] : null;
                                    $esFavorito = $idusuario ? $favoritoModel->esFavorito($idusuario, $recurso['idrecurso']) : false;
                                    ?>
                                    <button class="btn <?= $esFavorito ? 'btn-danger' : 'btn-outline-primary' ?>" 
                                            id="btnFavorito" 
                                            onclick="toggleFavorito(<?= $recurso['idrecurso'] ?>)">
                                        <i class="fas fa-heart<?= $esFavorito ? '' : '-o' ?>"></i> 
                                        <?= $esFavorito ? 'Quitar de Favoritos' : 'Agregar a Favoritos' ?>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-outline-primary" onclick="mostrarAlertaLogin('agregar a favoritos')">
                                        <i class="fas fa-heart"></i> Agregar a Favoritos
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (session()->get('logged_in')): ?>
                                    <button class="btn btn-outline-secondary" onclick="compartirRecurso(<?= $recurso['idrecurso'] ?>)">
                                        <i class="fas fa-share"></i> Compartir
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-outline-secondary" onclick="mostrarAlertaLogin('compartir')">
                                        <i class="fas fa-share"></i> Compartir
                                    </button>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('recursos') ?>" class="btn btn-outline-dark">
                                    <i class="fas fa-arrow-left"></i> Volver a Recursos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Incluir modal-fix.js para SweetAlert2 z-index -->
<script src="<?= base_url('assets/js/modal-fix.js') ?>"></script>

<script>
// Configurar SweetAlert2 para que aparezca por encima de los modales
document.addEventListener('DOMContentLoaded', function() {
    if (typeof setupSweetAlert2 === 'function') {
        setupSweetAlert2();
    }
    if (typeof observeSweetAlert2 === 'function') {
        observeSweetAlert2();
    }
});

// Nota: La función solicitarPrestamo() se usa desde el módulo global PrestamoForm.js
// No se define aquí para evitar duplicación

// Nota: La función toggleFavorito() se usa desde el módulo global FavoritosHandler.js
// No se define aquí para evitar duplicación

// Función para mostrar alerta de login
function mostrarAlertaLogin(accion) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Iniciar Sesión',
            text: `Debes iniciar sesión para ${accion}.`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Iniciar Sesión',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#007bff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('login') ?>';
            }
        });
    } else {
        alert(`Debes iniciar sesión para ${accion}.`);
        window.location.href = '<?= base_url('login') ?>';
    }
}


</script>
