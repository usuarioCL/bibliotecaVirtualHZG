<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página -->
    <?= view('Administrador/prestamos/partials/_header') ?>

    <!-- Estadísticas rápidas -->
    <?= view('Administrador/prestamos/partials/_stats_cards', ['estadisticas' => $estadisticas]) ?>

    <!-- Tabla de préstamos -->
    <?= view('Administrador/prestamos/partials/_tabla_prestamos', ['prestamos' => $prestamos]) ?>
</div>

<!-- Scripts JavaScript -->
<?= view('Administrador/prestamos/partials/_scripts') ?>
