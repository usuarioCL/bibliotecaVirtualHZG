/**
 * Sistema de Compartir Recursos por WhatsApp
 * Biblioteca Virtual HZG
 */

/**
 * Compartir recurso por WhatsApp
 * @param {number} idRecurso - ID del recurso a compartir
 */
function compartirRecurso(idRecurso) {
    // Obtener información del recurso del modal
    const modalBody = document.getElementById('libroModalBody');
    let tituloRecurso = 'este recurso';
    
    // Intentar obtener el título del modal
    const tituloElement = modalBody?.querySelector('.modal-titulo-libro');
    if (tituloElement) {
        tituloRecurso = tituloElement.textContent.trim();
        // Limpiar el icono del título si existe
        tituloRecurso = tituloRecurso.replace(/\s*\n\s*/g, ' ').trim();
    }
    
    // Construir URL del recurso con parámetro
    const baseUrl = window.location.origin + window.location.pathname;
    const urlRecurso = `${baseUrl}?ver_recurso=${idRecurso}`;
    
    // Mensaje personalizado para WhatsApp con emojis
    const mensaje = `📚 *Biblioteca Virtual HZG*

¡Mira este recurso que encontré! 📖

📌 *${tituloRecurso}*

👉 Ver detalles aquí:
${urlRecurso}

¡Espero que te sea útil! 🎓`;
    
    // Codificar mensaje para URL
    const mensajeCodificado = encodeURIComponent(mensaje);
    
    // URL de WhatsApp
    const urlWhatsApp = `https://wa.me/?text=${mensajeCodificado}`;
    
    // Abrir WhatsApp en nueva pestaña
    window.open(urlWhatsApp, '_blank');
    
    // Log para debugging
    console.log('📤 Compartiendo recurso:', {
        id: idRecurso,
        titulo: tituloRecurso,
        url: urlRecurso
    });
}

/**
 * Detectar parámetro ver_recurso en la URL y abrir modal automáticamente
 * Debe llamarse cuando el DOM esté listo
 */
function detectarRecursoCompartido() {
    const urlParams = new URLSearchParams(window.location.search);
    const verRecurso = urlParams.get('ver_recurso');
    
    if (verRecurso) {
        console.log('🔗 Detectado recurso compartido:', verRecurso);
        
        // Esperar a que la página cargue completamente
        setTimeout(() => {
            // Intentar diferentes funciones según la vista
            if (typeof verDetalles === 'function') {
                // Vista de catálogo
                verDetalles(verRecurso);
            } else if (typeof cargarDetallesLibro === 'function') {
                // Vista principal
                cargarDetallesLibro(verRecurso);
                const modal = new bootstrap.Modal(document.getElementById('libroModal'));
                modal.show();
            } else {
                console.error('❌ No se encontró función para mostrar detalles del recurso');
            }
            
            // Limpiar la URL sin recargar la página
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }, 500);
    }
}

// Auto-inicializar al cargar el DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', detectarRecursoCompartido);
} else {
    detectarRecursoCompartido();
}

console.log('✅ Sistema de compartir WhatsApp cargado');

