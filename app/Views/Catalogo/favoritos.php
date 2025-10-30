<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center h-100">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-heart me-3"></i>Mis Favoritos
                    </h1>
                    <p class="text-muted mb-0">Tu biblioteca personal de libros favoritos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card bg-danger bg-gradient text-white border-0 shadow-sm">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-heart fa-2x me-3"></i>
                            <div>
                                <small class="text-white-50 d-block">Total Favoritos</small>
                                <h3 class="text-white mb-0 fw-bold" id="contadorFavoritos"><?= $contadorFavoritos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda - Solo mostrar si hay favoritos -->
    <?php if (!empty($favoritos)): ?>
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar en favoritos..." id="buscarFavoritos">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="filtroCategoria">
                <option value="">Todas las categorías</option>
                <option value="literatura">Literatura</option>
                <option value="matemáticas">Matemáticas</option>
                <option value="informática">Informática</option>
                <option value="historia">Historia</option>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="ordenarPor">
                <option value="reciente">Más recientes</option>
                <option value="alfabetico">Orden alfabético</option>
                <option value="autor">Por autor</option>
                <option value="categoria">Por categoría</option>
            </select>
        </div>
    </div>

    <!-- Contador de resultados - Solo mostrar si hay favoritos -->
    <div class="row mb-3">
        <div class="col-12">
            <span class="text-muted">Mostrando <span id="resultadosCount"><?= count($favoritos) ?></span> favoritos</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Contenido de favoritos - Vista Lista -->
    <?php if (!empty($favoritos)): ?>
    <div id="favoritosLista">
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
                                <?php if (!empty($favorito['fecha_agregado'])): ?>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($favorito['fecha_agregado'])) ?>
                                    </small>
                                <?php else: ?>
                                    <small class="text-muted">-</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#libroModal"
                                            onclick="cargarDetallesLibro(<?= $favorito['idrecurso'] ?>)" 
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
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

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
// Función para cargar detalles del libro (debe estar fuera del DOMContentLoaded para ser accesible globalmente)
function cargarDetallesLibro(idRecurso) {
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
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('recurso/detalles/') ?>${idRecurso}`)
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

document.addEventListener('DOMContentLoaded', function() {
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
        
        // Obtener todas las filas de la tabla (lista)
        const filas = Array.from(document.querySelectorAll('#favoritosLista tbody tr'));
        let filasVisibles = [];
        
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
        if (filasVisibles.length > 0) {
            ordenarFilas(filasVisibles, ordenSeleccionado);
        }
        
        // Actualizar contador
        const totalVisibles = filasVisibles.length;
        document.getElementById('resultadosCount').textContent = totalVisibles;
        
        // Mostrar mensaje si no hay resultados
        const sinFavoritos = document.getElementById('sinFavoritos');
        const favoritosLista = document.getElementById('favoritosLista');
        
        if (totalVisibles === 0 && filas.length > 0) {
            // Hay favoritos pero no coinciden con el filtro
            favoritosLista.classList.add('d-none'); // Ocultar tabla
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
        } else if (totalVisibles === 0 && filas.length === 0) {
            // No hay favoritos en absoluto
            favoritosLista.classList.add('d-none'); // Ocultar tabla
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
            favoritosLista.classList.remove('d-none'); // Mostrar tabla
            sinFavoritos.classList.add('d-none');
        }
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

// Funciones globales para favoritos (fuera del DOMContentLoaded para acceso global)
// Función para agregar a favoritos (ahora usa toggleFavorito)
function agregarFavorito(idRecurso) {
    toggleFavorito(idRecurso);
}

// Función para alternar favorito (agregar/quitar)
function toggleFavorito(idRecurso) {
    fetch('<?= base_url('catalogo/toggle-favorito') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({idrecurso: idRecurso})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: data.agregado ? 'Agregado a Favoritos' : 'Quitado de Favoritos',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(data.message);
            }
            
            // Si se quitó de favoritos, recargar la página para actualizar la lista
            if (!data.agregado) {
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al procesar la solicitud',
                    icon: 'error'
                });
            } else {
                alert('Error: ' + (data.message || 'Error desconocido'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión',
                icon: 'error'
            });
        } else {
            alert('Error de conexión');
        }
    });
}

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

function solicitarPrestamo(idrecurso) {
    // Primero verificar si el usuario tiene sanciones activas
    fetch('<?= base_url('prestamo/verificar-sanciones') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.sancionado) {
            // El usuario tiene sanciones activas, mostrar alerta
            let sancionesHtml = '<div class="alert alert-danger"><strong>Sanciones activas:</strong><ul class="mb-0 mt-2">';
            data.sanciones.forEach(sancion => {
                sancionesHtml += `<li><strong>${sancion.tipo}:</strong> ${sancion.detalle}`;
                if (sancion.fecha_vencimiento) {
                    const fechaVenc = new Date(sancion.fecha_vencimiento);
                    sancionesHtml += `<br><small>Vence: ${fechaVenc.toLocaleDateString('es-ES')}</small>`;
                }
                sancionesHtml += '</li>';
            });
            sancionesHtml += '</ul></div>';
            
            Swal.fire({
                title: 'No puede solicitar préstamos',
                html: sancionesHtml + '<p class="mt-3">Usted tiene sanciones activas y no puede solicitar préstamos hasta que se resuelvan.</p>',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc3545'
            });
        } else if (data.success && !data.sancionado) {
            // No tiene sanciones, continuar con el proceso normal
            alert('Solicitar préstamo del libro ID: ' + idrecurso);
        } else {
            // Error al verificar sanciones
            Swal.fire({
                title: 'Error',
                text: data.message || 'No se pudo verificar su estado de sanciones',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error al verificar sanciones:', error);
        Swal.fire({
            title: 'Error',
            text: 'Error al verificar sanciones. Por favor intente nuevamente.',
            icon: 'error'
        });
    });
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