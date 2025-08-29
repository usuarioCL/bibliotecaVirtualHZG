<?php helper('form'); ?>
<?= $header; ?>
<div class="container mt-4">
<?= $navbar; ?>
    <!-- Hero section con buscador -->
    <div class="mt-4 p-4">
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
    <hr>
    <div class="row">
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
                    <?= form_dropdown(
                        'subcategoria',
                        ['' => 'Todas'] + array_column($subcategorias, 'subcategoria', 'idsubcategoria'),
                        $filtros['subcategoria'] ?? '',
                        ['class' => 'form-select', 'id' => 'subcategoria']
                    ) ?>
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
                <div class="mb-3">
                    <?= form_label('Estado', 'estado', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'estado',
                        [
                            '' => 'Todos',
                            'disponible' => 'Disponible',
                            'prestado' => 'Prestado',
                            'perdido' => 'Perdido'
                        ],
                        $filtros['estado'] ?? '',
                        ['class' => 'form-select', 'id' => 'estado']
                    ) ?>
                </div>
                <?= form_submit('filtrar', 'Filtrar', ['class' => 'btn btn-primary w-100']) ?>
                <?= form_reset('reset', 'Borrar filtros', ['class' => 'btn btn-secondary w-100 mt-2', 'id' => 'reset-filtros']) ?>
            </form>
        </div>
        <div class="col-9">
            <div class="row">
                <h4 class="fw-bold text-primary text-center mb-4  pb-2">
                    Resultados de la búsqueda
                </h4>
            </div>
            <!-- Resultados de la búsqueda -->
            <div class="row" id="resultados-busqueda">
                <?php include(APPPATH . 'Views/recursos/resultadosBusqueda.php'); ?>
            </div>
        </div>  
    </div>
</div>
<script>
    // AJAX para el buscador principal (solo actualiza resultados)
    document.querySelector('form[action*="buscarRecursos"]').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        fetch('<?= base_url('recursos/filtrosBusqueda') ?>?query=' + encodeURIComponent(formData.get('query')), {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados-busqueda').innerHTML = html;
            // Opcional: limpiar los filtros si quieres que el usuario vea todos los resultados del query
            // document.getElementById('filtros-form').reset();
        });
    });

    // AJAX para filtros
    document.getElementById('filtros-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        fetch(form.action + '?' + params, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados-busqueda').innerHTML = html;
        });
    });

    // Borrar filtros y recargar resultados sin filtros
    document.getElementById('reset-filtros').addEventListener('click', function(e) {
        setTimeout(function() {
            const form = document.getElementById('filtros-form');
            // Limpiar todos los campos manualmente por si el reset no lo hace
            form.reset();
            // Recargar resultados sin filtros
            fetch(form.action, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('resultados-busqueda').innerHTML = html;
            });
        }, 50);
    });
</script>
<?= $footer; ?>