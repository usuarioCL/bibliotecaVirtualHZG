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
                            <h4 class="text-primary mb-0" id="contadorFavoritos"><?= $contadorFavoritos ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda - Solo mostrar si hay favoritos -->
    <?php if (!empty($favoritos)): ?>
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
                <option value="literatura">Literatura</option>
                <option value="matemáticas">Matemáticas</option>
                <option value="informática">Informática</option>
                <option value="historia">Historia</option>
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

    <!-- Vista de grilla/lista - Solo mostrar si hay favoritos -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Mostrando <span id="resultadosCount"><?= count($favoritos) ?></span> favoritos</span>
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
    <?php endif; ?>

    <!-- Contenido de favoritos - Vista Grilla -->
    <div class="row" id="favoritosGrilla">
        <?php if (!empty($favoritos)): ?>
            <?php foreach ($favoritos as $favorito): ?>
                <?= view('partials/favorito_card', ['favorito' => $favorito, 'colClasses' => 'col-lg-3 col-md-4 col-sm-6 mb-4']) ?>
            <?php endforeach; ?>
        <?php endif; ?>
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
                    <?php if (!empty($favoritos)): ?>
                        <?php foreach ($favoritos as $favorito): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($favorito['portada'])): ?>
                                            <img src="<?= base_url($favorito['portada']) ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                        <?php else: ?>
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 50px;">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?= esc($favorito['titulo']) ?></h6>
                                            <?php if (!empty($favorito['isbn'])): ?>
                                                <small class="text-muted">ISBN: <?= esc($favorito['isbn']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($favorito['nomautor'] ?: 'Sin autor') ?></td>
                                <td>
                                    <?php if (!empty($favorito['categoria'])): ?>
                                        <span class="badge bg-primary"><?= esc($favorito['categoria']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Sin categoría</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-heart text-danger me-1"></i>
                                        Favorito
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="verDetalles(<?= $favorito['idrecurso'] ?>)" 
                                                title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($favorito['estado'] === 'disponible'): ?>
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="solicitarPrestamo(<?= $favorito['idrecurso'] ?>)" 
                                                    title="Prestar">
                                                <i class="fas fa-book"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled title="No disponible">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="quitarFavorito(<?= $favorito['idfavorito'] ?>, <?= $favorito['idrecurso'] ?>)" 
                                                title="Quitar de favoritos">
                                            <i class="fas fa-heart-broken"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-heart fa-2x mb-2"></i>
                                <br>No tienes libros favoritos
                                <br>
                                <small>¡Explora nuestro catálogo y marca tus libros favoritos!</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mensaje cuando no hay favoritos -->
    <div class="row <?= empty($favoritos) ? '' : 'd-none' ?>" id="sinFavoritos">
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de cambio de vista - Solo si existen los elementos
    const vistaGrilla = document.getElementById('vistaGrilla');
    const vistaLista = document.getElementById('vistaLista');
    const favoritosGrilla = document.getElementById('favoritosGrilla');
    const favoritosLista = document.getElementById('favoritosLista');

    if (vistaGrilla && vistaLista && favoritosGrilla && favoritosLista) {
        vistaGrilla.addEventListener('click', function() {
            this.classList.add('active');
            vistaLista.classList.remove('active');
            favoritosGrilla.classList.remove('d-none');
            favoritosLista.classList.add('d-none');
            // Reaplicar filtros después del cambio de vista
            filtrarFavoritos();
        });

        vistaLista.addEventListener('click', function() {
            this.classList.add('active');
            vistaGrilla.classList.remove('active');
            favoritosLista.classList.remove('d-none');
            favoritosGrilla.classList.add('d-none');
            // Reaplicar filtros después del cambio de vista
            filtrarFavoritos();
        });
    }

    // Funcionalidad de búsqueda y filtros - Solo si existen los elementos
    const buscarInput = document.getElementById('buscarFavoritos');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const ordenarPor = document.getElementById('ordenarPor');

    if (buscarInput && filtroCategoria && ordenarPor) {
        buscarInput.addEventListener('input', filtrarFavoritos);
        filtroCategoria.addEventListener('change', filtrarFavoritos);
        ordenarPor.addEventListener('change', filtrarFavoritos);
    }
    function filtrarFavoritos() {
        // Verificar que los elementos existan antes de usarlos
        if (!buscarInput || !filtroCategoria || !ordenarPor) {
            return;
        }
        
        const busqueda = buscarInput.value.toLowerCase().trim();
        const categoriaSeleccionada = filtroCategoria.value.toLowerCase();
        const ordenSeleccionado = ordenarPor.value;
        
        console.log('Filtros aplicados:', { busqueda, categoriaSeleccionada, ordenSeleccionado });
        
        // Obtener todas las cards de favoritos (grilla)
        const cards = Array.from(document.querySelectorAll('#favoritosGrilla .col-lg-3'));
        let cardsVisibles = [];
        
        // Obtener todas las filas de la tabla (lista)
        const filas = Array.from(document.querySelectorAll('#favoritosLista tbody tr'));
        let filasVisibles = [];
        
        // Filtrar cards de grilla
        cards.forEach(card => {
            const titulo = card.querySelector('h6.card-title')?.textContent.toLowerCase() || '';
            
            // Buscar el texto del autor (después de "Autores:")
            const autorElement = card.querySelector('p.card-text');
            const autorTexto = autorElement?.textContent || '';
            const autor = autorTexto.replace('autores:', '').trim().toLowerCase();
            
            // Buscar el texto de la categoría (después de "Categoría:")
            const categoriaElements = card.querySelectorAll('p.card-text');
            let categoria = '';
            categoriaElements.forEach(el => {
                const texto = el.textContent.toLowerCase();
                if (texto.includes('categoría:')) {
                    categoria = texto.replace('categoría:', '').trim();
                }
            });
            
            // Verificar si coincide con la búsqueda
            const coincideBusqueda = !busqueda || 
                titulo.includes(busqueda) || 
                autor.includes(busqueda);
            
            // Verificar si coincide con la categoría
            const coincidenCategoria = !categoriaSeleccionada || 
                categoria.includes(categoriaSeleccionada);
            
            // Mostrar/ocultar card
            if (coincideBusqueda && coincidenCategoria) {
                card.style.display = 'block';
                cardsVisibles.push(card);
            } else {
                card.style.display = 'none';
            }
        });
        
        // Filtrar filas de la tabla
        filas.forEach(fila => {
            // Evitar filtrar la fila de "no hay favoritos"
            if (fila.querySelector('td[colspan]')) {
                return;
            }
            
            const titulo = fila.querySelector('h6')?.textContent.toLowerCase() || '';
            const autor = fila.cells[1]?.textContent.toLowerCase() || '';
            const categoriaElement = fila.querySelector('.badge');
            const categoria = categoriaElement?.textContent.toLowerCase() || '';
            
            // Verificar si coincide con la búsqueda
            const coincideBusqueda = !busqueda || 
                titulo.includes(busqueda) || 
                autor.includes(busqueda);
            
            // Verificar si coincide con la categoría
            const coincidenCategoria = !categoriaSeleccionada || 
                categoria.includes(categoriaSeleccionada);
            
            // Mostrar/ocultar fila
            if (coincideBusqueda && coincidenCategoria) {
                fila.style.display = 'table-row';
                filasVisibles.push(fila);
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Ordenar elementos visibles
        if (cardsVisibles.length > 0) {
            ordenarCards(cardsVisibles, ordenSeleccionado);
        }
        if (filasVisibles.length > 0) {
            ordenarFilas(filasVisibles, ordenSeleccionado);
        }
        
        // Actualizar contador (usar el mayor entre cards y filas)
        const totalVisibles = Math.max(cardsVisibles.length, filasVisibles.length);
        document.getElementById('resultadosCount').textContent = totalVisibles;
        
        // Mostrar mensaje si no hay resultados
        const sinFavoritos = document.getElementById('sinFavoritos');
        const favoritosGrilla = document.getElementById('favoritosGrilla');
        
        if (totalVisibles === 0 && (cards.length > 0 || filas.length > 0)) {
            // Hay favoritos pero no coinciden con el filtro
            sinFavoritos.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No se encontraron resultados</h4>
                        <p class="text-muted mb-4">Intenta con otros términos de búsqueda o filtros</p>
                        <button class="btn btn-outline-primary" onclick="limpiarFiltros()">
                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                        </button>
                    </div>
                </div>
            `;
            sinFavoritos.classList.remove('d-none');
        } else if (totalVisibles === 0 && cards.length === 0 && filas.length === 0) {
            // No hay favoritos en absoluto
            sinFavoritos.innerHTML = `
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
            `;
            sinFavoritos.classList.remove('d-none');
        } else {
            sinFavoritos.classList.add('d-none');
        }
    }
    
    function ordenarCards(cards, criterio) {
        const container = document.getElementById('favoritosGrilla');
        
        cards.sort((a, b) => {
            switch (criterio) {
                case 'alfabetico':
                    const tituloA = a.querySelector('h6.card-title')?.textContent || '';
                    const tituloB = b.querySelector('h6.card-title')?.textContent || '';
                    return tituloA.localeCompare(tituloB);
                    
                case 'autor':
                    const autorElementA = a.querySelector('p.card-text');
                    const autorElementB = b.querySelector('p.card-text');
                    const autorA = autorElementA?.textContent.replace('Autores:', '').trim() || '';
                    const autorB = autorElementB?.textContent.replace('Autores:', '').trim() || '';
                    return autorA.localeCompare(autorB);
                    
                case 'categoria':
                    let catA = '', catB = '';
                    
                    // Buscar categoría en card A
                    a.querySelectorAll('p.card-text').forEach(el => {
                        if (el.textContent.includes('Categoría:')) {
                            catA = el.textContent.replace('Categoría:', '').trim();
                        }
                    });
                    
                    // Buscar categoría en card B
                    b.querySelectorAll('p.card-text').forEach(el => {
                        if (el.textContent.includes('Categoría:')) {
                            catB = el.textContent.replace('Categoría:', '').trim();
                        }
                    });
                    
                    return catA.localeCompare(catB);
                    
                case 'reciente':
                default:
                    // Mantener orden original (más recientes primero)
                    return 0;
            }
        });
        
        // Reordenar en el DOM
        cards.forEach(card => {
            container.appendChild(card);
        });
    }
    
    function ordenarFilas(filas, criterio) {
        const tbody = document.querySelector('#favoritosLista tbody');
        
        filas.sort((a, b) => {
            switch (criterio) {
                case 'alfabetico':
                    const tituloA = a.querySelector('h6')?.textContent || '';
                    const tituloB = b.querySelector('h6')?.textContent || '';
                    return tituloA.localeCompare(tituloB);
                    
                case 'autor':
                    const autorA = a.cells[1]?.textContent || '';
                    const autorB = b.cells[1]?.textContent || '';
                    return autorA.localeCompare(autorB);
                    
                case 'categoria':
                    const catA = a.querySelector('.badge')?.textContent || '';
                    const catB = b.querySelector('.badge')?.textContent || '';
                    return catA.localeCompare(catB);
                    
                case 'reciente':
                default:
                    // Mantener orden original (más recientes primero)
                    return 0;
            }
        });
        
        // Reordenar en el DOM
        filas.forEach(fila => {
            tbody.appendChild(fila);
        });
    }

});

// Funciones globales para favoritos
function quitarFavorito(idfavorito, idrecurso) {
    if (confirm('¿Estás seguro de que quieres quitar este libro de favoritos?')) {
        fetch('<?= base_url('catalogo/quitar-favorito') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({idfavorito: idfavorito})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}

function verDetalles(idrecurso) {
    const modalBody = document.getElementById('libroModalBody');
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del recurso...</p>
        </div>
    `;
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('libroModal'));
    modal.show();
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('recursos/detalles/') ?>${idrecurso}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del recurso.
                </div>
            `;
        });
}

function solicitarPrestamo(idrecurso) {
    alert('Solicitar préstamo del libro ID: ' + idrecurso);
}

function limpiarFiltros() {
    document.getElementById('buscarFavoritos').value = '';
    document.getElementById('filtroCategoria').value = '';
    document.getElementById('ordenarPor').value = 'reciente';
    
    // Disparar evento para aplicar filtros
    document.getElementById('buscarFavoritos').dispatchEvent(new Event('input'));
}
</script>

<!-- Modal para detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles del recurso...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Limpiar modal cuando se cierre -->
<script>
document.getElementById('libroModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('libroModalBody').innerHTML = '';
});
</script>

<?= $footer ?>