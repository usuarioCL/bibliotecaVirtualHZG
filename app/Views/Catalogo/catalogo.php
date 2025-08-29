<?= $header ?>
<?= $navbar ?>
<div class="container mt-4">
    <h2 class="text-center mb-4">Catálogo de Libros</h2>

    <!-- Botones de categoría -->
    <div class="mb-4">
        <?php foreach ($categorias as $cat): ?>
            <button class="btn btn-primary btn-categoria me-2" data-id="<?= $cat['idcategoria'] ?>">
                <?= $cat['categoria'] ?>
            </button>
        <?php endforeach; ?>
        <button class="btn btn-secondary btn-categoria" data-id="0">Todos</button>
    </div>

    <!-- Contenedor de subcategorías y libros -->
    <div id="contenido">
        <?php foreach ($subcategorias as $sub): ?>
            <h4><?= $sub['subcategoria'] ?></h4>
            <div class="row mb-3">
                <?php if(count($sub['libros']) > 0): ?>
                    <?php foreach($sub['libros'] as $lib): ?>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="<?= $lib['rutaportada'] ?>" class="card-img-top" alt="Portada" style="height:200px; object-fit:cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $lib['titulo'] ?></h5>
                                    <p class="card-text">
                                        <strong>Autores:</strong> <?= isset($lib['autores']) ? $lib['autores'] : 'Sin autores' ?>
                                    </p>
                                    <p class="card-text"><strong>Año:</strong> <?= $lib['anio'] ?> | <strong>ISBN:</strong> <?= $lib['isbn'] ?></p>
                                    <p class="card-text"><strong>Edición:</strong> <?= $lib['numedicion'] ?> | <strong>Estado:</strong> <?= $lib['estado'] ?></p>
                                    <p class="card-text"><strong>Stock:</strong> <?= $lib['stock'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted ms-3">No hay libros</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function cargarSubcategorias(idCat){
    let url = idCat == 0 ? '/catalogo' : `/catalogo/subcategorias/${idCat}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            let cont = document.getElementById("contenido");
            cont.innerHTML = "";

            data.forEach(sub => {
                let html = `<h4>${sub.subcategoria}</h4><div class="row mb-3">`;

                if(sub.libros.length > 0){
                    sub.libros.forEach(lib => {
                        html += `
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="${lib.rutaportada}" class="card-img-top" style="height:200px; object-fit:cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${lib.titulo}</h5>
                                    <p class="card-text"><strong>Autores:</strong> ${lib.autores}</p>
                                    <p class="card-text"><strong>Año:</strong> ${lib.anio} | <strong>ISBN:</strong> ${lib.isbn}</p>
                                    <p class="card-text"><strong>Edición:</strong> ${lib.numedicion} | <strong>Estado:</strong> ${lib.estado}</p>
                                    <p class="card-text"><strong>Stock:</strong> ${lib.stock}</p>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html += `<p class="text-muted ms-3">No hay libros</p>`;
                }

                html += "</div>";
                cont.innerHTML += html;
            });
        });
}

// Botones
document.querySelectorAll(".btn-categoria").forEach(btn => {
    btn.addEventListener("click", function() {
        cargarSubcategorias(this.dataset.id);
    });
});

// Cargar todas al inicio
cargarSubcategorias(0);
</script>



<?= $footer ?>
