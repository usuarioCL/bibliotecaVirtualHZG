<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-start">
                <h1 class="text-primary mb-2">
                    <i class="fas fa-book-open me-3"></i>Catálogo de Recursos
                </h1>
                <p class="text-muted">Explora nuestra colección de recursos educativos por categorías</p>
            </div>
        </div>
    </div>

    <!-- Filtros de categoría mejorados -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-start">
                <div class="btn-group flex-wrap" role="group" aria-label="Filtros de categoría">
                    <button class="btn btn-secondary btn-categoria active" data-id="0">
                        <i class="fas fa-th-large me-2"></i>Todos
                    </button>
                    <?php foreach ($categorias as $cat): ?>
                        <button class="btn btn-outline-primary btn-categoria" data-id="<?= $cat['idcategoria'] ?>">
                            <i class="fas fa-folder me-2"></i><?= $cat['categoria'] ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de subcategorías y libros -->
    <div id="contenido" class="min-vh-50">
        <!-- Loading state -->
        <div id="loading" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Cargando recursos...</p>
        </div>

        <!-- Contenido inicial -->
        <div id="contenido-inicial">
            <?php foreach ($subcategorias as $sub): ?>
                <div class="subcategoria-section mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="text-primary mb-0 me-3">
                            <i class="fas fa-layer-group me-2"></i><?= $sub['subcategoria'] ?>
                        </h3>
                        <div class="flex-grow-1">
                            <hr class="text-secondary">
                        </div>
                        <span class="badge bg-light text-dark ms-3">
                            <?= count($sub['libros']) ?> recursos
                        </span>
                    </div>
                    
                    <div class="row">
                        <?php if(count($sub['libros']) > 0): ?>
                            <?php foreach($sub['libros'] as $lib): ?>
                                <?= view('partials/libro_card', [
                                    'libro' => $lib,
                                    'colClasses' => 'col-lg-2 col-md-4 col-sm-6'
                                ]) ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center border-0">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <h5 class="text-muted">No hay recursos disponibles</h5>
                                    <p class="text-muted mb-0">Esta subcategoría no tiene recursos disponibles en este momento.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// Función para generar el HTML del libro_card (replica el componente PHP)
function generarLibroCard(libro, colClasses = 'col-lg-2 col-md-4 col-sm-6') {
    const autorTexto = libro.autores || libro.nomautor || 'Sin autor';
    const tituloCorto = libro.titulo.length > 40 ? libro.titulo.substring(0, 40) + '...' : libro.titulo;
    const detalleUrl = libro.detalle_url || '#';
    
    return `
    <div class="${colClasses} mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <!-- Imagen del libro -->
            <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
                ${libro.rutaportada ? 
                    `<img src="${libro.rutaportada}" 
                         class="card-img-top h-100 w-100" 
                         style="object-fit: cover;" 
                         alt="${libro.titulo}">` :
                    `<div class="bg-light h-100 d-flex align-items-center justify-content-center">
                        <div class="text-center text-muted">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <small>Sin portada</small>
                        </div>
                    </div>`
                }
            </div>
            
            <!-- Contenido de la card -->
            <div class="card-body p-3">
                <!-- Título -->
                <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem; line-height: 1.2;">
                    ${tituloCorto}
                </h6>
                
                <!-- Autor -->
                <p class="card-text text-muted small mb-2">
                    <strong>Autor:</strong> ${autorTexto}
                </p>
                
                <!-- Año -->
                <p class="card-text text-muted small">
                    <strong>Año:</strong> ${libro.anio || 'N/A'}
                </p>
            </div>
            
            <!-- Footer de la card -->
            <div class="card-footer bg-transparent border-top-0">
                <a href="${detalleUrl}" class="btn btn-sm btn-outline-primary">
                    Ver detalles
                </a>
            </div>
        </div>
    </div>`;
}

function cargarSubcategorias(idCat) {
    const contenido = document.getElementById("contenido");
    const loading = document.getElementById("loading");
    const contenidoInicial = document.getElementById("contenido-inicial");
    
    // Si es "Todos" (idCat = 0), mostrar contenido inicial
    if (idCat == 0) {
        contenido.innerHTML = '';
        contenido.appendChild(loading);
        
        // Clonar el contenido inicial en lugar de moverlo
        const contenidoInicialClonado = contenidoInicial.cloneNode(true);
        contenidoInicialClonado.classList.remove('d-none');
        contenido.appendChild(contenidoInicialClonado);
        
        loading.classList.add('d-none');
        aplicarAnimacionesCards();
        return Promise.resolve();
    }
    
    const url = '<?= site_url('catalogo/subcategorias') ?>/' + idCat;
    
    // Limpiar contenido previo y mostrar loading
    contenido.innerHTML = '';
    contenido.appendChild(loading);
    contenido.appendChild(contenidoInicial); // Mantener en el DOM pero oculto
    if (contenidoInicial) contenidoInicial.classList.add('d-none');
    loading.classList.remove('d-none');

    return fetch(url, {
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
        loading.classList.add('d-none');
        
        let html = '';
        
        if (data && data.length > 0) {
            data.forEach(sub => {
                const totalLibros = sub.libros ? sub.libros.length : 0;
                
                html += `
                <div class="subcategoria-section mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="text-primary mb-0 me-3">
                            <i class="fas fa-layer-group me-2"></i>${sub.subcategoria}
                        </h3>
                        <div class="flex-grow-1">
                            <hr class="text-secondary">
                        </div>
                        <span class="badge bg-light text-dark ms-3">
                            ${totalLibros} recursos
                        </span>
                    </div>
                    <div class="row">`;
                
                if (sub.libros && sub.libros.length > 0) {
                    sub.libros.forEach(lib => {
                        html += generarLibroCard(lib);
                    });
                } else {
                    html += `
                    <div class="col-12">
                        <div class="alert alert-info text-center border-0">
                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay recursos disponibles</h5>
                            <p class="text-muted mb-0">Esta categoría no tiene recursos disponibles en este momento.</p>
                        </div>
                    </div>`;
                }
                
                html += '</div></div>';
            });
        } else {
            html = `
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron recursos</h4>
                <p class="text-muted">No hay recursos disponibles para esta categoría.</p>
            </div>`;
        }
        
        // Ocultar loading y mostrar contenido
        loading.classList.add('d-none');
        
        // Crear contenedor para el nuevo contenido
        const nuevoContenido = document.createElement('div');
        nuevoContenido.innerHTML = html;
        
        // Limpiar solo el contenido dinámico, mantener elementos base
        contenido.innerHTML = '';
        contenido.appendChild(loading); // Mantener el loading para futuros usos
        contenido.appendChild(contenidoInicial); // Mantener contenido inicial oculto
        contenidoInicial.classList.add('d-none');
        contenido.appendChild(nuevoContenido);
        
        // Aplicar animaciones a las nuevas cards
        setTimeout(() => {
            aplicarAnimacionesCards();
        }, 100);
    })
    .catch(error => {
        console.error('Error:', error);
        loading.classList.add('d-none');
        contenido.innerHTML = `
        <div class="alert alert-danger text-center border-0">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <h5>Error al cargar el contenido</h5>
            <p class="mb-0">${error.message}</p>
        </div>`;
    });
}

// Funciones auxiliares para las acciones de los botones
function verDetalles(idRecurso) {
    // Redirigir a la página de detalles del recurso
    window.location.href = `<?= site_url('catalogo/detalle') ?>/${idRecurso}`;
}

function solicitarPrestamo(idRecurso) {
    // Verificar si el usuario está logueado
    <?php if (session()->get('logged_in')): ?>
        if (confirm('¿Deseas solicitar el préstamo de este recurso?')) {
            // Aquí iría la lógica AJAX para solicitar préstamo
            console.log('Solicitar préstamo del recurso:', idRecurso);
            alert('Solicitud de préstamo enviada exitosamente');
        }
    <?php else: ?>
        alert('Debes iniciar sesión para solicitar un préstamo');
        window.location.href = '<?= site_url('login') ?>';
    <?php endif; ?>
}

// Función para aplicar animaciones a las cards
function aplicarAnimacionesCards() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    // Aplicar animaciones a todas las cards visibles
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.3s ease';
        observer.observe(card);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de botones de categoría
    document.querySelectorAll(".btn-categoria").forEach(btn => {
        btn.addEventListener("click", function() {
            // Prevenir múltiples clicks mientras se carga
            if (this.disabled) return;
            
            // Deshabilitar todos los botones temporalmente
            document.querySelectorAll(".btn-categoria").forEach(b => b.disabled = true);
            
            // Remover clases activas de todos los botones
            document.querySelectorAll(".btn-categoria").forEach(b => {
                b.classList.remove('btn-primary', 'btn-secondary', 'active');
                b.classList.add('btn-outline-primary');
            });
            
            // Activar el botón clickeado
            this.classList.remove('btn-outline-primary');
            this.classList.add(this.dataset.id == '0' ? 'btn-secondary' : 'btn-primary', 'active');
            
            // Cargar contenido
            cargarSubcategorias(this.dataset.id).finally(() => {
                // Rehabilitar botones después de la carga
                document.querySelectorAll(".btn-categoria").forEach(b => b.disabled = false);
            });
        });
    });

    // Aplicar animaciones iniciales
    aplicarAnimacionesCards();

    // Smooth scroll para navegación
    const smoothScroll = (target) => {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    };
});
</script>

<?= $footer ?>
