<?php helper('form'); ?>
<?= $header; ?>
<?= $navbar; ?>

<div class="container">
    <!-- Hero section con buscador -->
    <div class="py-4 border-bottom">
        <form action="<?= base_url('recursos/buscarRecursos') ?>" method="get" class="d-flex justify-content-end align-items-center">
            <div class="input-group input-group-lg w-50 ">
                <input 
                    type="search" 
                    name="query" 
                    class="form-control rounded-start-pill border-primary" 
                    placeholder="Buscar libros, autores o temas..." 
                    aria-label="Buscar" 
                    required>
                <button type="submit" class="btn btn-primary rounded-end-pill px-4">Buscar
                </button>
            </div>
        </form>
    </div>
    <!-- Fin de sección de búsqueda -->
    <div class="row mt-4">
        <!-- Filtros de búsqueda -->
        <div class="col-3">
            <h4 accesskey="f" class="fw-bold text-primary">Filtros de búsqueda</h4>
            <form method="get" action="<?= base_url('recursos/filtrosBusqueda') ?>" id="filtros-form">
                <div class="mb-3">
                    <?= form_label('Autor', 'autor', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'autor',
                        ['' => 'Todos'] + array_column($autores, 'nomautor', 'idautor'),
                        $filtros['autor'] ?? '',
                        ['class' => 'form-select', 'id' => 'autor']
                    ) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Categoría', 'categoria', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'categoria',
                        ['' => 'Todas'] + array_column($categorias, 'categoria', 'idcategoria'),
                        $filtros['categoria'] ?? '',
                        ['class' => 'form-select', 'id' => 'categoria']
                    ) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Subcategoría', 'subcategoria', ['class' => 'form-label']) ?>
                    <select name="subcategoria" id="subcategoria" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($subcategorias as $sub): ?>
                            <option value="<?= $sub['idsubcategoria'] ?>" 
                                    data-categoria="<?= $sub['idcategoria'] ?>"
                                    <?= (isset($filtros['subcategoria']) && $filtros['subcategoria'] == $sub['idsubcategoria']) ? 'selected' : '' ?>>
                                <?= esc($sub['subcategoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <?= form_label('Editorial', 'editorial', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'editorial',
                        ['' => 'Todas'] + array_column($editoriales, 'editorial', 'ideditorial'),
                        $filtros['editorial'] ?? '',
                        ['class' => 'form-select', 'id' => 'editorial']
                    ) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Año', 'anio', ['class' => 'form-label']) ?>
                    <?= form_input([
                        'type' => 'number',
                        'name' => 'anio',
                        'id' => 'anio',
                        'class' => 'form-control',
                        'min' => 1900,
                        'max' => date('Y'),
                        'value' => $filtros['anio'] ?? ''
                    ]) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Tipo de Recurso', 'tiporecurso', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'tiporecurso',
                        ['' => 'Todos'] + array_column($tiposrecurso, 'tiporecurso', 'idtiporecurso'),
                        $filtros['tiporecurso'] ?? '',
                        ['class' => 'form-select', 'id' => 'tiporecurso']
                    ) ?>
                </div>
                <?= form_submit('filtrar', 'Filtrar', ['class' => 'btn btn-primary w-100']) ?>
                <?= form_reset('reset', 'Borrar filtros', ['class' => 'btn btn-secondary w-100 mt-2', 'id' => 'reset-filtros']) ?>
            </form>
        </div>
        <!-- Fin de filtros de búsqueda -->
        <!-- Resultados de la búsqueda -->
        <div class="col-9">
            <div class="row">
                <h4 class="fw-bold text-primary text-center mb-4  pb-2">
                    Resultados de la búsqueda
                </h4>
            </div>
            <div class="row" id="resultados-busqueda">
                <?php include(APPPATH . 'Views/recursos/resultadosBusqueda.php'); ?>
            </div>
        </div>
        <!-- Fin de sección de búsqueda -->
    </div>
</div>

<!-- Cargar módulo de búsqueda -->
<script src="<?= base_url('assets/js/modules/busquedaHandler.js') ?>"></script>

<?= $footer; ?>