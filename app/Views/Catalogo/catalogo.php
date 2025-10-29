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
                                    'colClasses' => 'col-lg-2 col-md-4 col-sm-6',
                                    'imagenPrefix' => base_url()
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
// Función para generar tarjetas usando el componente PHP como fuente única
async function generarTarjetasDesdeServidor(libros, colClasses = 'col-lg-2 col-md-4 col-sm-6') {
    try {
        // Enviar los datos al endpoint PHP que renderiza usando libro_card.php
        const response = await fetch('<?= base_url("app/Views/Catalogo/render_cards.php") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                libros: libros,
                colClasses: colClasses
            })
        });
        
        if (response.ok) {
            return await response.text();
        } else {
            console.warn('Error en endpoint PHP, usando fallback JavaScript');
            return generarTarjetasFallback(libros, colClasses);
        }
    } catch (error) {
        console.warn('Error conectando con endpoint PHP, usando fallback JavaScript:', error);
        return generarTarjetasFallback(libros, colClasses);
    }
}

// Función fallback que genera múltiples tarjetas
function generarTarjetasFallback(libros, colClasses = 'col-lg-2 col-md-4 col-sm-6') {
    return libros.map(libro => {
        // Mapear datos para que coincidan con el formato del componente PHP
        const libroFormateado = {
            ...libro,
            portada: libro.portada || libro.rutaportada,
            nomautor: libro.autores || libro.nomautor || 'Sin autor'
        };
        
        return generarLibroCardFallback(libroFormateado, colClasses);
    }).join('');
}

// Función fallback que replica exactamente el componente PHP
function generarLibroCardFallback(libro, colClasses = 'col-lg-2 col-md-4 col-sm-6') {
    const autorTexto = libro.autores || libro.nomautor || 'Sin autor';
    
    // Detectar si es recurso digital
    let esDigital = false;
    let debugInfo = [];
    
    if (libro.tiporecurso) {
        esDigital = libro.tiporecurso.toLowerCase().includes('digital');
        debugInfo.push("Tipo: " + libro.tiporecurso);
    }
    
    if (!esDigital && libro.idtiporecurso) {
        esDigital = (libro.idtiporecurso == 2);
        debugInfo.push("ID Tipo: " + libro.idtiporecurso);
    }
    
    if (!esDigital && libro.archivo && libro.archivo.trim() !== '') {
        esDigital = true;
        debugInfo.push("Archivo: " + libro.archivo);
    }
    
    const debugText = debugInfo.join(', ');
    const rutaImagen = libro.portada ? '<?= base_url() ?>' + libro.portada : null;
    
    return `
    <div class="${colClasses}">
        <div class="card h-100 shadow-sm rounded" 
             style="cursor: pointer;" 
             data-bs-toggle="modal" 
             data-bs-target="#libroModal"
             data-libro-id="${libro.idrecurso}"
             onclick="cargarDetallesLibro(${libro.idrecurso})">
            <!-- Icono de tipo de recurso -->
            <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                ${esDigital ? 
                    `<span class="badge bg-info text-white" title="Recurso Digital - ${debugText}">
                        <i class="fas fa-file-pdf me-1"></i>
                        Digital
                    </span>` :
                    `<span class="badge bg-primary text-white" title="Recurso Físico - ${debugText}">
                        <i class="fas fa-book me-1"></i>
                        Físico
                    </span>`
                }
            </div>
            
            <!-- Imagen del libro con texto overlay -->
            <div class="position-relative card" style="height: 300px; overflow: hidden;">
                ${rutaImagen ? 
                    `<img src="${rutaImagen}" 
                         class="card-img-top h-100 w-100" 
                         style="object-fit: cover; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;" 
                         alt="${libro.titulo}"
                         data-recurso-id="${libro.idrecurso}">` :
                    `<div class="bg-light h-100 d-flex align-items-center justify-content-center" style="border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;">
                        <div class="text-center text-muted">
                            ${esDigital ? 
                                `<i class="fas fa-file-pdf fa-2x mb-2 text-info"></i>` :
                                `<i class="fas fa-book fa-2x mb-2"></i>`
                            }
                            <small>Sin portada</small>
                        </div>
                    </div>`
                }
                
                <!-- Overlay con información del libro -->
                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 80%, transparent 100%); text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                    <!-- Título -->
                    <h6 class="text-white fw-bold mb-1 text-truncate" style="font-size: 0.95rem; line-height: 1.3; text-shadow: 2px 2px 4px rgba(0,0,0,0.9);" title="${libro.titulo}">
                        ${libro.titulo}
                    </h6>
                    
                    <!-- Autores -->
                    <p class="text-white small mb-0 text-truncate" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);" title="${autorTexto}">
                        ${autorTexto}
                    </p>
                    
                    <!-- Año -->
                    <p class="text-white small mb-0" style="opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                        ${libro.anio || 'N/A'}
                    </p>
                </div>
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
                    // Usar el sistema fallback que replica el componente PHP
                    const tarjetasHtml = generarTarjetasFallback(sub.libros);
                    html += tarjetasHtml;
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
    // Cargar detalles en modal
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
    fetch(`<?= base_url('recursos/detalles/') ?>${idRecurso}`)
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

function solicitarPrestamo(idRecurso) {
    // Verificar si el usuario está logueado
    <?php if (session()->get('logged_in')): ?>
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
                if (confirm('¿Deseas solicitar el préstamo de este recurso?')) {
                    console.log('Solicitar préstamo del recurso:', idRecurso);
                    alert('Solicitud de préstamo enviada exitosamente');
                }
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

<!-- Modal para ver detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <!-- Contenido se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal personalizado para ver PDF (copiado del administrador) -->
<div id="modalPDF" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="cerrarModalPDF()"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 id="modalPDFLabel">Visualizar PDF</h5>
            <button type="button" class="btn-close" onclick="cerrarModalPDF()" aria-label="Cerrar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <div id="pdfContainer" style="height: 700px; position: relative;">
                <div id="pdfLoading" style="display: none; text-align: center; padding: 50px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando PDF...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando PDF...</p>
                </div>
                <iframe id="pdfViewer" 
                        src="" 
                        width="100%" 
                        height="100%" 
                        style="border: none; display: none;"
                        title="Visor de PDF"
                        allowfullscreen>
                </iframe>
                <div id="pdfError" style="display: none; text-align: center; padding: 50px;">
                    <i class="fas fa-file-pdf fs-1 text-muted mb-3" aria-hidden="true"></i>
                    <h5>No se puede mostrar el PDF en el visor</h5>
                    <p class="text-muted">El archivo se abrirá en una nueva pestaña</p>
                    <button class="btn btn-primary" onclick="abrirPDFEnNuevaPestana()" aria-label="Abrir PDF en nueva pestaña">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i> Abrir PDF
                    </button>
                </div>
                <!-- Canvas oculto para PDF.js -->
                <canvas id="pdfCanvas" style="display: none;"></canvas>
            </div>
        </div>
        <div class="custom-modal-footer">
            <!-- Controles de voz amigables para niños -->
            <div class="voice-controls child-friendly">
                <div class="voice-buttons">
                    <button id="btnVoicePlay" type="button" class="btn btn-success voice-btn" onclick="toggleVoiceReading()" aria-label="Reproducir voz">
                        <i class="fas fa-play" aria-hidden="true"></i> <span id="voiceText">Leer</span>
                    </button>
                    <button id="btnVoicePause" type="button" class="btn btn-warning voice-btn" onclick="pauseVoiceReading()" style="display: none;" aria-label="Pausar voz">
                        <i class="fas fa-pause" aria-hidden="true"></i> Pausar
                    </button>
                    <button id="btnVoiceStop" type="button" class="btn btn-danger voice-btn" onclick="stopVoiceReading()" style="display: none;" aria-label="Detener voz">
                        <i class="fas fa-stop" aria-hidden="true"></i> Detener
                    </button>
                </div>
                <div class="voice-speed-control">
                    <label for="voiceSpeed" class="form-label">Velocidad:</label>
                    <div class="speed-container">
                        <span class="speed-label">Lento</span>
                        <input type="range" id="voiceSpeed" class="form-range speed-slider" min="0.5" max="1.5" step="0.1" value="0.8" onchange="changeVoiceSpeed(this.value)">
                        <span class="speed-label">Rápido</span>
                    </div>
                    <span id="speedValue" class="speed-value">0.8x</span>
                </div>
            </div>
            
            <!-- Botones principales -->
            <div class="main-controls">
                <a id="descargarPDF" href="#" target="_blank" class="btn btn-primary" aria-label="Descargar PDF">
                    <i class="fas fa-download" aria-hidden="true"></i> Descargar PDF
                </a>
                <button type="button" class="btn btn-secondary" onclick="cerrarModalPDF()" aria-label="Cerrar modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
}

.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal-content {
    position: relative;
    background: white;
    margin: 1% auto;
    width: 98%;
    max-width: 1400px;
    max-height: 95vh;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
}

.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.custom-modal-header h5 {
    margin: 0;
    font-size: 1.25rem;
}

.custom-modal-header .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    color: #6c757d;
}

.custom-modal-header .btn-close:hover {
    color: #000;
}

.custom-modal-body {
    padding: 0;
    flex: 1;
    overflow: hidden;
}

.custom-modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 0 0 8px 8px;
}

.voice-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Estilos minimalistas para controles */
.child-friendly {
    background: white;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.voice-buttons {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.voice-btn {
    border-radius: 6px !important;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem !important;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    box-shadow: none;
}

.voice-btn:hover {
    transform: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.voice-btn:active {
    transform: none;
}

/* Botón de reproducir/leer */
.btn-success.voice-btn {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-success.voice-btn:hover {
    background-color: #157347;
    border-color: #146c43;
}

/* Botón de pausa */
.btn-warning.voice-btn {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-warning.voice-btn:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
}

/* Botón de detener */
.btn-danger.voice-btn {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-danger.voice-btn:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}

.speed-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.speed-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6c757d;
}

.speed-slider {
    flex: 1;
    height: 4px;
    border-radius: 2px;
    background: #e9ecef;
    outline: none;
    appearance: none;
}

.speed-slider::-webkit-slider-thumb {
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6c757d;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.speed-slider::-moz-range-thumb {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6c757d;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.speed-value {
    font-weight: 500;
    color: #495057;
    font-size: 0.75rem;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
    min-width: 35px;
    text-align: center;
}

.voice-speed-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: 0.75rem;
}

.voice-speed-control label {
    margin: 0;
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.voice-speed-control input[type="range"] {
    width: 80px;
}

.voice-speed-control span {
    font-size: 0.75rem;
    font-weight: 500;
    color: #495057;
    min-width: 35px;
}

.main-controls {
    display: flex;
    gap: 0.5rem;
}

.main-controls .btn {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.main-controls .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.main-controls .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.main-controls .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.main-controls .btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .custom-modal-content {
        width: 98%;
        margin: 1% auto;
        max-height: 95vh;
    }
    
    .custom-modal-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .voice-controls {
        width: 100%;
        justify-content: center;
    }
    
    .main-controls {
        width: 100%;
        justify-content: center;
    }
    
    .voice-speed-control {
        margin-left: 0;
        margin-top: 0.5rem;
        width: 100%;
        justify-content: center;
    }
    
    #pdfContainer {
        height: 500px !important;
    }
}

/* Mejoras para el visor de PDF */
#pdfContainer {
    position: relative;
    overflow: hidden;
}
</style>

<script>
// Variables globales para el visor de PDF (copiado del administrador)
var currentPDFUrl = '';
var speechSynthesis = window.speechSynthesis;
var currentUtterance = null;
var isVoiceReading = false;
var isVoicePaused = false;
var currentVoiceSpeed = 0.8;

// Variables para PDF.js
var pdfDoc = null;
var pdfTextContent = '';
var isPdfLoaded = false;
var pdfjsLibLoaded = false;

/**
 * Leer PDF directamente - función principal (copiado del administrador)
 */
function leerPDFDirecto(url, titulo) {
    verPDF(url, titulo);
}

function verPDF(url, titulo) {
    currentPDFUrl = url;
    
    // Mostrar loading y ocultar otros elementos
    document.getElementById('pdfLoading').style.display = 'block';
    document.getElementById('pdfError').style.display = 'none';
    document.getElementById('pdfViewer').style.display = 'none';
    
    // Usar la URL tal como viene (no forzar HTTPS en desarrollo local)
    var secureUrl = url;
    // Solo convertir a HTTPS si estamos en producción
    if (url.startsWith('http://') && !window.location.hostname.includes('localhost') && !window.location.hostname.includes('.test')) {
        secureUrl = url.replace('http://', 'https://');
    }
    
    // Configurar el iframe con el PDF directamente
    var iframe = document.getElementById('pdfViewer');
    iframe.src = secureUrl;
    document.getElementById('modalPDFLabel').textContent = 'Visualizar: ' + titulo;
    document.getElementById('descargarPDF').href = secureUrl;
    
    // Mostrar el modal personalizado
    var modal = document.getElementById('modalPDF');
    modal.style.display = 'block';
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    // Enfocar el primer botón
    var firstButton = modal.querySelector('.btn-close');
    if (firstButton) {
        firstButton.focus();
    }
    
    // Cargar PDF.js y extraer texto para el lector de voz
    console.log('Iniciando carga de PDF.js para extracción de texto...');
    loadPDFJSLibrary().then(function() {
        console.log('PDF.js cargado exitosamente, iniciando extracción de texto...');
        loadPDFForTextExtraction(secureUrl);
    }).catch(function(error) {
        console.error('Error cargando PDF.js:', error);
        pdfTextContent = 'No se pudo cargar PDF.js. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        isPdfLoaded = true;
    });
    
    // Manejar la carga del iframe
    iframe.onload = function() {
        // Ocultar loading y mostrar iframe
        document.getElementById('pdfLoading').style.display = 'none';
        document.getElementById('pdfViewer').style.display = 'block';
        
        // Verificar si realmente se cargó el PDF
        setTimeout(function() {
            try {
                // Intentar acceder al contenido del iframe
                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (!iframeDoc || iframeDoc.body === null || iframeDoc.body.innerHTML.trim() === '') {
                    mostrarErrorPDF();
                }
            } catch (e) {
                // Si hay error de CORS, asumir que se cargó correctamente
                console.log('PDF cargado (CORS bloqueado, pero asumimos éxito)');
            }
        }, 1000);
    };
    
    iframe.onerror = function() {
        mostrarErrorPDF();
    };
    
    // Timeout de seguridad después de 10 segundos
    setTimeout(function() {
        if (document.getElementById('pdfLoading').style.display !== 'none') {
            mostrarErrorPDF();
        }
    }, 10000);
}

function cerrarModalPDF() {
    var modal = document.getElementById('modalPDF');
    modal.style.display = 'none';
    
    // Restaurar scroll del body
    document.body.style.overflow = 'auto';
    
    // Detener cualquier lectura de voz activa
    stopVoiceReading();
    
    // Limpiar el iframe y resetear estados
    var iframe = document.getElementById('pdfViewer');
    iframe.src = '';
    iframe.onload = null;
    iframe.onerror = null;
    
    // Ocultar todos los elementos del modal
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfLoading').style.display = 'none';
    document.getElementById('pdfError').style.display = 'none';
    
    // Limpiar variables
    currentPDFUrl = '';
    isPdfLoaded = false;
    pdfTextContent = '';
    pdfDoc = null;
}

function mostrarErrorPDF() {
    document.getElementById('pdfLoading').style.display = 'none';
    document.getElementById('pdfViewer').style.display = 'none';
    document.getElementById('pdfError').style.display = 'block';
}

function abrirPDFEnNuevaPestana() {
    if (currentPDFUrl) {
        window.open(currentPDFUrl, '_blank');
    }
}

// ===== FUNCIONES DE VOZ (copiadas del administrador) =====

function toggleVoiceReading() {
    if (isVoiceReading) {
        pauseVoiceReading();
    } else {
        startVoiceReading();
    }
}

function startVoiceReading() {
    // Detener cualquier lectura anterior
    stopVoiceReading();
    
    // Verificar si el PDF aún se está cargando
    if (!isPdfLoaded) {
        alert('El PDF aún se está cargando. Por favor espera un momento e intenta nuevamente.');
        return;
    }
    
    // Obtener el texto del PDF
    var pdfText = extractTextFromPDF();
    
    if (!pdfText || pdfText.trim() === '') {
        alert('No se pudo extraer texto del PDF para la lectura de voz.');
        return;
    }
    
    // Verificar si el texto es muy corto (posible error)
    if (pdfText.length < 10) {
        alert('El texto extraído del PDF es muy corto. Es posible que el PDF esté protegido o no contenga texto.');
        return;
    }
    
    // Crear utterance con configuración amigable para niños
    currentUtterance = new SpeechSynthesisUtterance(pdfText);
    
    // Configuración optimizada para niños
    currentUtterance.rate = Math.max(0.6, currentVoiceSpeed * 0.8); // Más lento para niños
    currentUtterance.pitch = 1.3; // Pitch más alto, más amigable
    currentUtterance.volume = 0.9; // Volumen más alto para mejor audición
    
    // Configurar idioma (español)
    currentUtterance.lang = 'es-ES';
    
    // Intentar seleccionar una voz más amigable para niños
    selectChildFriendlyVoice();
    
    // Eventos
    currentUtterance.onstart = function() {
        isVoiceReading = true;
        isVoicePaused = false;
        updateVoiceButtons();
        console.log('Iniciando lectura de voz del PDF...');
    };
    
    currentUtterance.onend = function() {
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
        console.log('Lectura de voz completada.');
    };
    
    currentUtterance.onerror = function(event) {
        console.error('Error en speech synthesis:', event.error);
        isVoiceReading = false;
        isVoicePaused = false;
        updateVoiceButtons();
        alert('Error al reproducir la voz: ' + event.error);
    };
    
    // Iniciar lectura
    speechSynthesis.speak(currentUtterance);
}

function pauseVoiceReading() {
    if (isVoiceReading && !isVoicePaused) {
        speechSynthesis.pause();
        isVoicePaused = true;
        updateVoiceButtons();
    } else if (isVoicePaused) {
        speechSynthesis.resume();
        isVoicePaused = false;
        updateVoiceButtons();
    }
}

function stopVoiceReading() {
    speechSynthesis.cancel();
    isVoiceReading = false;
    isVoicePaused = false;
    currentUtterance = null;
    updateVoiceButtons();
}

function changeVoiceSpeed(speed) {
    currentVoiceSpeed = parseFloat(speed);
    document.getElementById('speedValue').textContent = speed + 'x';
    
    if (currentUtterance) {
        // Aplicar velocidad más lenta para niños
        currentUtterance.rate = Math.max(0.6, currentVoiceSpeed * 0.8);
    }
}

// Función para seleccionar una voz amigable para niños
function selectChildFriendlyVoice() {
    if (!currentUtterance) return;
    
    // Obtener todas las voces disponibles
    const voices = speechSynthesis.getVoices();
    
    // Voces preferidas para niños (más amigables)
    const childFriendlyVoices = [
        'Microsoft Sabina Desktop - Spanish (Mexico)',
        'Microsoft Helena Desktop - Spanish (Spain)', 
        'Google español',
        'Microsoft Laura Desktop - Spanish (Spain)',
        'Microsoft Monica Desktop - Spanish (Spain)',
        'Microsoft Paulina Desktop - Spanish (Mexico)',
        'Microsoft Teresa Desktop - Spanish (Spain)'
    ];
    
    // Buscar una voz amigable para niños
    let selectedVoice = null;
    
    // Primero intentar con las voces preferidas
    for (const preferredVoice of childFriendlyVoices) {
        selectedVoice = voices.find(voice => 
            voice.name.includes('Sabina') || 
            voice.name.includes('Helena') ||
            voice.name.includes('Laura') ||
            voice.name.includes('Monica') ||
            voice.name.includes('Paulina') ||
            voice.name.includes('Teresa') ||
            (voice.name.toLowerCase().includes('google') && voice.lang.startsWith('es'))
        );
        if (selectedVoice) break;
    }
    
    // Si no se encuentra una voz preferida, buscar cualquier voz femenina en español
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => 
            voice.lang.startsWith('es') && 
            (voice.name.toLowerCase().includes('female') || 
             voice.name.toLowerCase().includes('woman') ||
             voice.name.toLowerCase().includes('mujer') ||
             voice.name.toLowerCase().includes('femenina'))
        );
    }
    
    // Si aún no se encuentra, usar cualquier voz en español
    if (!selectedVoice) {
        selectedVoice = voices.find(voice => voice.lang.startsWith('es'));
    }
    
    // Aplicar la voz seleccionada
    if (selectedVoice) {
        currentUtterance.voice = selectedVoice;
        console.log('Voz seleccionada para niños:', selectedVoice.name);
    }
}

function updateVoiceButtons() {
    var playBtn = document.getElementById('btnVoicePlay');
    var pauseBtn = document.getElementById('btnVoicePause');
    var stopBtn = document.getElementById('btnVoiceStop');
    var voiceText = document.getElementById('voiceText');
    
    if (isVoiceReading) {
        if (isVoicePaused) {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            stopBtn.style.display = 'inline-block';
            voiceText.textContent = 'Continuar';
            pauseBtn.innerHTML = '<i class="fas fa-play"></i> Continuar';
        } else {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
            stopBtn.style.display = 'inline-block';
            voiceText.textContent = 'Leyendo...';
            pauseBtn.innerHTML = '<i class="fas fa-pause"></i> Pausar';
        }
    } else {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
        stopBtn.style.display = 'none';
        voiceText.textContent = 'Leer';
    }
}

function extractTextFromPDF() {
    // Usar el texto extraído del PDF con PDF.js
    if (pdfTextContent && pdfTextContent.length > 0) {
        return pdfTextContent;
    }
    
    // Texto de ejemplo si no se puede extraer del PDF
    return 'Bienvenido a la lectura de este cuento. El sistema de voz está funcionando correctamente. Este es un texto de ejemplo para demostrar la funcionalidad de lectura de voz.';
}

// ===== FUNCIONES DE PDF.js (copiadas del administrador) =====

// Función para cargar PDF.js dinámicamente con múltiples CDNs
function loadPDFJSLibrary() {
    return new Promise(function(resolve, reject) {
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLibLoaded = true;
            resolve();
            return;
        }
        
        // Lista de CDNs alternativos (incluyendo versiones más estables)
        var cdnUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.4.168/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@4.4.168/build/pdf.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.min.js'
        ];
        
        var workerUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.4.168/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@4.4.168/build/pdf.worker.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.worker.min.js'
        ];
        
        var currentIndex = 0;
        
        function tryLoadScript() {
            if (currentIndex >= cdnUrls.length) {
                reject(new Error('No se pudo cargar PDF.js desde ningún CDN'));
                return;
            }
            
            var script = document.createElement('script');
            script.src = cdnUrls[currentIndex];
            
            script.onload = function() {
                console.log('PDF.js cargado desde:', cdnUrls[currentIndex]);
                
                // Configurar worker
                if (typeof pdfjsLib !== 'undefined' && pdfjsLib.GlobalWorkerOptions) {
                    pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrls[currentIndex];
                    console.log('Worker configurado:', workerUrls[currentIndex]);
                }
                
                pdfjsLibLoaded = true;
                resolve();
            };
            
            script.onerror = function() {
                console.warn('Error cargando PDF.js desde:', cdnUrls[currentIndex]);
                currentIndex++;
                tryLoadScript();
            };
            
            document.head.appendChild(script);
        }
        
        tryLoadScript();
    });
}

function loadPDFForTextExtraction(url) {
    // Verificar que PDF.js esté cargado
    if (!pdfjsLibLoaded || typeof pdfjsLib === 'undefined') {
        console.error('PDF.js no está cargado');
        pdfTextContent = 'PDF.js no está disponible. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        isPdfLoaded = true;
        return;
    }
    
    // Resetear estado
    pdfDoc = null;
    pdfTextContent = '';
    isPdfLoaded = false;
    
    console.log('Iniciando carga de PDF con PDF.js...');
    
    // Cargar PDF con PDF.js
    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        pdfDoc = pdf;
        console.log('PDF cargado con PDF.js:', pdf.numPages, 'páginas');
        
        // Extraer texto de todas las páginas
        extractTextFromAllPages();
        
    }).catch(function(error) {
        console.error('Error cargando PDF con PDF.js:', error);
        
        // Verificar si es un error de CORS
        if (error.name === 'UnknownErrorException' || error.message.includes('CORS') || error.message.includes('fetch')) {
            pdfTextContent = 'No se pudo acceder al PDF debido a restricciones de CORS. Esto es normal en algunos servidores. La funcionalidad de voz está disponible con texto de ejemplo. Para una experiencia completa, el administrador debe configurar los headers CORS en el servidor.';
        } else {
            pdfTextContent = 'No se pudo extraer texto del PDF. Usando texto de ejemplo para demostrar la funcionalidad de voz.';
        }
        
        isPdfLoaded = true;
        console.log('Usando texto de ejemplo debido a error:', error.name || error.message);
    });
}

function extractTextFromAllPages() {
    if (!pdfDoc) {
        console.error('PDF no cargado');
        return;
    }
    
    var totalPages = pdfDoc.numPages;
    var allText = '';
    var pagesProcessed = 0;
    
    console.log('Extrayendo texto de', totalPages, 'páginas...');
    
    // Procesar cada página
    for (var pageNum = 1; pageNum <= totalPages; pageNum++) {
        pdfDoc.getPage(pageNum).then(function(page) {
            return page.getTextContent();
        }).then(function(textContent) {
            // Extraer texto de la página
            var pageText = '';
            for (var i = 0; i < textContent.items.length; i++) {
                pageText += textContent.items[i].str + ' ';
            }
            
            allText += pageText + '\n\n';
            pagesProcessed++;
            
            // Si hemos procesado todas las páginas
            if (pagesProcessed === totalPages) {
                pdfTextContent = allText.trim();
                isPdfLoaded = true;
                console.log('Texto extraído del PDF:', pdfTextContent.length, 'caracteres');
                
                // Limpiar texto (remover espacios excesivos, saltos de línea múltiples)
                pdfTextContent = pdfTextContent.replace(/\s+/g, ' ').trim();
                
                if (pdfTextContent.length === 0) {
                    pdfTextContent = 'Este PDF no contiene texto extraíble o está protegido.';
                }
            }
        }).catch(function(error) {
            console.error('Error extrayendo texto de página:', error);
            pagesProcessed++;
            
            // Si hemos procesado todas las páginas (incluso con errores)
            if (pagesProcessed === totalPages) {
                if (allText.trim().length === 0) {
                    pdfTextContent = 'No se pudo extraer texto del PDF. Puede estar protegido o no contener texto.';
                } else {
                    pdfTextContent = allText.trim();
                    pdfTextContent = pdfTextContent.replace(/\s+/g, ' ').trim();
                }
                isPdfLoaded = true;
                console.log('Extracción completada con algunos errores');
            }
        });
    }
}

// Función para cargar detalles del libro (placeholder)
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
    fetch(`<?= base_url('recursos/detalles/') ?>${idRecurso}`)
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

// Limpiar modal cuando se cierre
document.getElementById('libroModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('libroModalBody').innerHTML = '';
});

// Nota: La función compartirRecurso() y la detección de recurso compartido 
// se manejan en compartir-whatsapp.js
</script>

<?= $footer ?>
