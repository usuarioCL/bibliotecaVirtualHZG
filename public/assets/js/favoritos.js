/**
 * favoritos.js
 * Gestión de favoritos del usuario
 */

// Configuración global
const FavoritosConfig = {
    urls: {
        detallesRecurso: '',
        quitarFavorito: '',
        verificarSanciones: '',
        solicitarPrestamo: ''
    },
    elementos: {
        modalBody: null,
        modal: null,
        contadorFavoritos: null,
        favoritosLista: null
    }
};

/**
 * Inicializar configuración con URLs desde PHP
 */
function initFavoritosConfig(urls) {
    FavoritosConfig.urls = { ...FavoritosConfig.urls, ...urls };
}

/**
 * Cachear elementos del DOM
 */
function cacheElements() {
    FavoritosConfig.elementos.modalBody = document.getElementById('libroModalBody');
    FavoritosConfig.elementos.modal = document.getElementById('libroModal');
    FavoritosConfig.elementos.contadorFavoritos = document.getElementById('contadorFavoritos');
    FavoritosConfig.elementos.favoritosLista = document.getElementById('favoritosLista');
}

/**
 * Validar ID de recurso
 */
function validarIdRecurso(idRecurso) {
    const id = parseInt(idRecurso, 10);
    return !isNaN(id) && id > 0 ? id : null;
}

/**
 * Cargar detalles del libro en el modal
 */
async function cargarDetallesLibro(idRecurso) {
    const modalBody = FavoritosConfig.elementos.modalBody;
    
    if (!modalBody) {
        console.error('Modal body no encontrado');
        return;
    }
    
    // Validar ID
    const idValidado = validarIdRecurso(idRecurso);
    if (!idValidado) {
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ID de recurso inválido
            </div>
        `;
        return;
    }
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del recurso...</p>
        </div>
    `;
    
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 segundos timeout
        
        const response = await fetch(`${FavoritosConfig.urls.detallesRecurso}${idValidado}`, {
            signal: controller.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const html = await response.text();
        modalBody.innerHTML = html;
        
    } catch (error) {
        console.error('Error al cargar detalles:', error);
        
        let mensaje = 'Error al cargar los detalles del recurso.';
        if (error.name === 'AbortError') {
            mensaje = 'La solicitud ha tardado demasiado. Por favor, intente nuevamente.';
        }
        
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${mensaje}
            </div>
        `;
    }
}

/**
 * Mostrar confirmación para quitar favorito
 */
function quitarFavorito(idfavorito, idrecurso) {
    // Validar parámetros
    if (!validarIdRecurso(idfavorito)) {
        mostrarError('ID de favorito inválido');
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Quitar de favoritos?',
            text: '¿Estás seguro de que quieres quitar este libro de tus favoritos?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                procesarQuitarFavorito(idfavorito);
            }
        });
    } else {
        if (confirm('¿Estás seguro de que quieres quitar este libro de favoritos?')) {
            procesarQuitarFavorito(idfavorito);
        }
    }
}

/**
 * Procesar la eliminación del favorito
 */
async function procesarQuitarFavorito(idfavorito) {
    try {
        const response = await fetch(FavoritosConfig.urls.quitarFavorito, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ idfavorito: idfavorito })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            mostrarExito(data.message || 'Favorito eliminado correctamente', () => {
                // Eliminar la fila de la tabla dinámicamente
                eliminarFilaFavorito(idfavorito);
            });
        } else {
            mostrarError(data.message || 'Error al quitar de favoritos');
        }
        
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión. Por favor, intente nuevamente.');
    }
}

/**
 * Eliminar fila del favorito del DOM
 */
function eliminarFilaFavorito(idfavorito) {
    const fila = document.querySelector(`tr[data-favorito-id="${idfavorito}"]`);
    
    if (fila) {
        // Animación de salida
        fila.style.transition = 'opacity 0.3s, transform 0.3s';
        fila.style.opacity = '0';
        fila.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            fila.remove();
            actualizarContadorFavoritos(-1);
            verificarSiVacio();
        }, 300);
    } else {
        // Si no se encuentra la fila, recargar la página
        location.reload();
    }
}

/**
 * Actualizar el contador de favoritos
 */
function actualizarContadorFavoritos(cambio) {
    const contador = FavoritosConfig.elementos.contadorFavoritos;
    
    if (contador) {
        const valorActual = parseInt(contador.textContent, 10) || 0;
        const nuevoValor = Math.max(0, valorActual + cambio);
        contador.textContent = nuevoValor;
    }
}

/**
 * Verificar si la lista está vacía y mostrar mensaje
 */
function verificarSiVacio() {
    const lista = FavoritosConfig.elementos.favoritosLista;
    
    if (lista && lista.children.length === 0) {
        // Mostrar estado vacío
        const container = document.querySelector('.container.mt-4');
        if (container) {
            const estadoVacio = `
                <div class="row" id="sinFavoritos">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-heart fa-4x text-danger opacity-50"></i>
                                </div>
                                <h4 class="text-muted mb-3">No tienes libros favoritos</h4>
                                <p class="text-muted mb-4 lead">¡Explora nuestro catálogo y marca tus libros favoritos!</p>
                                
                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                    <a href="${FavoritosConfig.urls.catalogo || '/catalogo'}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-search me-2"></i>Explorar Catálogo
                                    </a>
                                    <a href="${FavoritosConfig.urls.catalogo || '/catalogo'}?categoria=populares" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-star me-2"></i>Libros Populares
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remover la tabla y mostrar estado vacío
            const card = lista.closest('.card');
            if (card) {
                card.remove();
                container.insertAdjacentHTML('beforeend', estadoVacio);
            }
        }
    }
}

/**
 * Solicitar préstamo de un recurso
 */
async function solicitarPrestamo(idrecurso) {
    // Validar ID
    const idValidado = validarIdRecurso(idrecurso);
    if (!idValidado) {
        mostrarError('ID de recurso inválido');
        return;
    }
    
    try {
        // Verificar sanciones antes de solicitar préstamo
        const response = await fetch(FavoritosConfig.urls.verificarSanciones, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.sancionado) {
            // Usuario con sanciones
            mostrarSanciones(data.sanciones);
        } else if (data.success && !data.sancionado) {
            // Sin sanciones, redirigir al formulario de préstamo
            window.location.href = `${FavoritosConfig.urls.solicitarPrestamo}${idValidado}`;
        } else {
            mostrarError(data.message || 'No se pudo verificar su estado');
        }
        
    } catch (error) {
        console.error('Error:', error);
        mostrarError('Error de conexión. Intente nuevamente.');
    }
}

/**
 * Mostrar sanciones del usuario
 */
function mostrarSanciones(sanciones) {
    if (typeof Swal === 'undefined') {
        alert('Tiene sanciones activas y no puede solicitar préstamos.');
        return;
    }
    
    let sancionesHtml = '<div class="alert alert-danger mb-0"><strong>Sanciones activas:</strong><ul class="mb-0 mt-2">';
    
    sanciones.forEach(sancion => {
        sancionesHtml += `<li><strong>${sancion.tipo || 'Sanción'}:</strong> ${sancion.detalle || 'Sin detalles'}`;
        
        if (sancion.fecha_vencimiento) {
            const fechaVenc = new Date(sancion.fecha_vencimiento);
            sancionesHtml += `<br><small>Vence: ${fechaVenc.toLocaleDateString('es-ES')}</small>`;
        }
        
        sancionesHtml += '</li>';
    });
    
    sancionesHtml += '</ul></div>';
    
    Swal.fire({
        title: 'No puede solicitar préstamos',
        html: sancionesHtml + '<p class="mt-3 mb-0">Tiene sanciones activas y no puede solicitar préstamos hasta que se resuelvan.</p>',
        icon: 'warning',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#dc3545'
    });
}

/**
 * Mostrar mensaje de éxito
 */
function mostrarExito(mensaje, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¡Éxito!',
            text: mensaje,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            if (typeof callback === 'function') {
                callback();
            }
        });
    } else {
        alert(mensaje);
        if (typeof callback === 'function') {
            callback();
        }
    }
}

/**
 * Mostrar mensaje de error
 */
function mostrarError(mensaje) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error',
            text: mensaje,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    } else {
        alert('Error: ' + mensaje);
    }
}

/**
 * Inicializar eventos
 */
function initEventos() {
    // Limpiar modal cuando se cierre
    const modal = FavoritosConfig.elementos.modal;
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const modalBody = FavoritosConfig.elementos.modalBody;
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del recurso...</p>
                    </div>
                `;
            }
        });
    }
}

/**
 * Inicialización cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function() {
    cacheElements();
    initEventos();
    console.log('Vista de favoritos cargada correctamente');
});

// Exportar funciones para uso global
window.cargarDetallesLibro = cargarDetallesLibro;
window.quitarFavorito = quitarFavorito;
window.solicitarPrestamo = solicitarPrestamo;
window.initFavoritosConfig = initFavoritosConfig;
