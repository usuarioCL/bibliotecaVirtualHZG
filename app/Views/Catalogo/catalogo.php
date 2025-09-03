<?= $header ?>
<?= $navbar ?>
<div class="container mt-4">
    <!-- Botones de categoría -->
    <div class="mb-4">
        <button class="btn btn-secondary btn-categoria" data-id="0">Todos</button>
        <?php foreach ($categorias as $cat): ?>
            <button class="btn btn-outline-primary btn-categoria me-2" data-id="<?= $cat['idcategoria'] ?>">
                <?= $cat['categoria'] ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Contenedor de subcategorías y libros -->
    <div id="contenido">
        <?php foreach ($subcategorias as $sub): ?>
            <h4><?= $sub['subcategoria'] ?></h4>
            <div class="row mb-3">
                <?php if(count($sub['libros']) > 0): ?>
                    <?php foreach($sub['libros'] as $lib): ?>
                        <?= view('partials/libro_card', [
                            'libro' => $lib
                        ]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay libros disponibles en esta subcategoría
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function cargarSubcategorias(idCat) {
    const url = idCat == 0 ? '<?= site_url('catalogo') ?>' : '<?= site_url('catalogo/subcategorias') ?>/' + idCat;
    const contenido = document.getElementById("contenido");
    
    contenido.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        contenido.innerHTML = "";
        
        if (data && data.length > 0) {
            data.forEach(sub => {
                let html = `<h4>${sub.subcategoria}</h4><div class="row mb-3">`;
                
                if (sub.libros && sub.libros.length > 0) {
                    sub.libros.forEach(lib => {
                        // Verificar si generarLibroCard está disponible
                        if (typeof generarLibroCard === 'function') {
                            html += generarLibroCard(lib);
                        } else {
                            // Función fallback simple
                            html += `
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
                                        ${lib.rutaportada ? 
                                            `<img src="${lib.rutaportada}" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="${lib.titulo}">` :
                                            `<div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-book fa-2x mb-2"></i>
                                                    <small>Sin portada</small>
                                                </div>
                                            </div>`
                                        }
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem; line-height: 1.2;">
                                            ${lib.titulo.length > 40 ? lib.titulo.substring(0, 40) + '...' : lib.titulo}
                                        </h6>
                                        <p class="card-text text-muted small mb-2">
                                            <strong>Autor:</strong> ${lib.autores || lib.nomautor || 'Sin autor'}
                                        </p>
                                        <p class="card-text text-muted small">
                                            <strong>Año:</strong> ${lib.anio}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <a href="#" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                                    </div>
                                </div>
                            </div>`;
                        }
                    });
                } else {
                    html += '<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle me-2"></i>No hay libros disponibles en esta subcategoría</div></div>';
                }
                
                html += "</div>";
                contenido.innerHTML += html;
            });
        } else {
            contenido.innerHTML = '<div class="alert alert-info text-center">No se encontraron subcategorías para esta categoría</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contenido.innerHTML = `<div class="alert alert-danger text-center">Error al cargar el contenido: ${error.message}</div>`;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll(".btn-categoria").forEach(btn => {
        btn.addEventListener("click", function() {
            document.querySelectorAll(".btn-categoria").forEach(b => {
                b.classList.remove('btn-primary', 'btn-secondary');
                b.classList.add('btn-outline-primary');
            });
            
            this.classList.remove('btn-outline-primary');
            this.classList.add(this.dataset.id == '0' ? 'btn-secondary' : 'btn-primary');
            
            cargarSubcategorias(this.dataset.id);
        });
    });

    cargarSubcategorias(0);
});
</script>

<?= $footer ?>
