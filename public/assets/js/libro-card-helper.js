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
    const imagenHtml = libro.rutaportada ? 
        `<img src="${imagenPrefix}${libro.rutaportada}" 
              class="card-img-top h-100 w-100" 
              style="object-fit: cover;" 
              alt="${escapeHtml(libro.titulo)}">` :
        `<div class="bg-light h-100 d-flex align-items-center justify-content-center">
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
    <div class="${colClasses} mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
                ${imagenHtml}
            </div>
            <div class="card-body p-3">
                <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem; line-height: 1.2;">
                    ${escapeHtml(titulo)}
                </h6>
                <p class="card-text text-muted small mb-2">
                    <strong>${autorTexto}</strong> ${escapeHtml(autorValor)}
                </p>
                <p class="card-text text-muted small ${mostrarDetalles.length > 0 ? 'mb-1' : ''}">
                    <strong>Año:</strong> ${escapeHtml(libro.anio ? libro.anio.toString() : '')}
                </p>
                ${detallesHtml}
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="${libro.detalle_url || '#'}" class="btn btn-sm btn-outline-primary">
                    Ver detalles
                </a>
            </div>
            
        </div>
    </div>`;
}

// Exportar para uso global
window.generarLibroCard = generarLibroCard;
