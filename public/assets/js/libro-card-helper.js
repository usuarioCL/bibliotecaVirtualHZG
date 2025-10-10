/**
 * Función helper de JavaScript para generar cards de libros
 * Mantiene consistencia con el partial de PHP
 * 
 * @param {Object} libro - Objeto con información del libro
 * @param {Object} opciones - Opciones de configuración
 * @param {string} opciones.colClasses - Clases CSS para columnas
 * @param {Array} opciones.mostrarDetalles - Array de detalles adicionales ['isbn', 'edicion', 'estado', 'stock']
 * @param {string} opciones.imagenPrefix - Prefijo para la ruta de imagen
 * @returns {string} HTML de la card
 */
function generarLibroCard(libro, opciones = {}) {
    const {
        colClasses = 'col-lg-2 col-md-4 col-sm-6',
        mostrarDetalles = [],
        imagenPrefix = ''
    } = opciones;

    // Función para escapar HTML
    const escapeHtml = (text) => {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    };

    // Truncar título
    const titulo = libro.titulo.length > 40 ? 
        libro.titulo.substring(0, 40) + '...' : 
        libro.titulo;

    // Generar imagen o placeholder
    const imagenHtml = libro.rutaportada || libro.portada ? 
        `<img src="${imagenPrefix}${libro.rutaportada || libro.portada}" 
              class="card-img-top h-100 w-100" 
              style="object-fit: cover; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;" 
              alt="${escapeHtml(libro.titulo)}">` :
        `<div class="bg-light h-100 d-flex align-items-center justify-content-center" style="border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;">
            <div class="text-center text-muted">
                <i class="fas fa-book fa-2x mb-2"></i>
                <small>Sin portada</small>
            </div>
        </div>`;

    // Generar detalles opcionales
    let detallesHtml = '';
    
    if (mostrarDetalles.includes('isbn') && libro.isbn) {
        detallesHtml += `<p class="card-text text-muted small mb-1">
            <strong>ISBN:</strong> ${escapeHtml(libro.isbn)}
        </p>`;
    }
    
    if (mostrarDetalles.includes('edicion') && libro.numedicion) {
        detallesHtml += `<p class="card-text text-muted small mb-1">
            <strong>Edición:</strong> ${escapeHtml(libro.numedicion)}
        </p>`;
    }
    
    if (mostrarDetalles.includes('estado') && libro.estado) {
        detallesHtml += `<p class="card-text text-muted small mb-1">
            <strong>Estado:</strong> ${escapeHtml(libro.estado)}
        </p>`;
    }
    
    if (mostrarDetalles.includes('stock') && libro.stock !== undefined) {
        detallesHtml += `<p class="card-text text-muted small">
            <strong>Stock:</strong> ${escapeHtml(libro.stock.toString())}
        </p>`;
    }

    // Determinar texto del autor - adaptarse a ambos formatos
    const autorTexto = libro.autores ? 'Autor:' : 'Autor:';
    const autorValor = libro.autores || libro.nomautor || 'Sin autor';

    return `
    <div class="${colClasses}">
        <div class="card h-100 shadow-sm rounded" 
             style="cursor: pointer;" 
             data-bs-toggle="modal" 
             data-bs-target="#libroModal"
             data-libro-id="${libro.idrecurso || libro.id}"
             onclick="cargarDetallesLibro(${libro.idrecurso || libro.id})">
            
            <!-- Imagen del libro con texto overlay -->
            <div class="position-relative card" style="height: 300px; overflow: hidden;">
                ${imagenHtml}
                
                <!-- Overlay con información del libro -->
                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 80%, transparent 100%); text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                    <!-- Título -->
                    <h6 class="text-white fw-bold mb-1 text-truncate" style="font-size: 0.95rem; line-height: 1.3; text-shadow: 2px 2px 4px rgba(0,0,0,0.9);" title="${escapeHtml(libro.titulo)}">
                        ${escapeHtml(titulo)}
                    </h6>
                    
                    <!-- Autores -->
                    <p class="text-white small mb-0 text-truncate" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);" title="${escapeHtml(autorValor)}">
                        ${escapeHtml(autorValor)}
                    </p>
                    
                    <!-- Año -->
                    <p class="text-white small mb-0" style="opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                        ${escapeHtml(libro.anio ? libro.anio.toString() : 'N/A')}
                    </p>
                </div>
            </div>
        </div>
    </div>`;
}

// Exportar para uso global
window.generarLibroCard = generarLibroCard;
