/**
 * Controlador principal de la Página Principal
 * Orquesta todos los módulos y expone funciones globales
 */
class PaginaPrincipalController {
    constructor() {
        this.pdfViewer = null;
        this.voiceReader = null;
        this.prestamoForm = null;
        this.favoritosHandler = null;
        
        this.init();
    }
    
    /**
     * Inicializa todos los módulos
     */
    async init() {
        console.log('Inicializando Página Principal...');
        
        try {
            // Inicializar módulos
            this.pdfViewer = new PDFViewer();
            this.voiceReader = new VoiceReader(this.pdfViewer);
            this.prestamoForm = new PrestamoForm();
            this.favoritosHandler = new FavoritosHandler();
            
            // Exponer funciones globales necesarias para las vistas
            this.exposeGlobalFunctions();
            
            // Inicializar listeners globales
            this.initListeners();
            
            console.log('Página Principal inicializada correctamente');
        } catch (error) {
            console.error('Error al inicializar Página Principal:', error);
        }
    }
    
    /**
     * Expone funciones globales usadas por las vistas
     */
    exposeGlobalFunctions() {
        // Funciones del modal de detalles de libro
        window.cargarDetallesLibro = (id) => this.cargarDetallesLibro(id);
        
        // Funciones del visor de PDF
        window.leerPDFDirecto = (url, title) => this.pdfViewer.open(url, title);
        window.cerrarModalPDF = () => this.pdfViewer.close();
        window.abrirPDFNuevaTab = () => this.pdfViewer.openInNewTab();
        
        // Funciones de voz
        window.toggleVoiceReading = () => this.voiceReader.toggle();
        window.pauseVoiceReading = () => this.voiceReader.pause();
        window.stopVoiceReading = () => this.voiceReader.stop();
        window.changeVoiceSpeed = (speed) => this.voiceReader.changeSpeed(speed);
        
        // Funciones de favoritos
        window.toggleFavorito = (id) => this.favoritosHandler.toggle(id);
        
        // Funciones de préstamo
        window.solicitarPrestamo = (id) => this.prestamoForm.open(id);
    }
    
    /**
     * Inicializa listeners globales
     */
    initListeners() {
        // Limpiar modal de libro al cerrar
        const libroModal = document.getElementById('libroModal');
        if (libroModal) {
            libroModal.addEventListener('hidden.bs.modal', () => {
                const modalBody = document.getElementById('libroModalBody');
                if (modalBody) {
                    modalBody.innerHTML = '';
                }
            });
        }
        
        // Detener voz cuando se cierra el modal de PDF
        const modalPDF = document.getElementById('modalPDF');
        if (modalPDF) {
            // Agregar listener al overlay para cerrar
            const overlay = modalPDF.querySelector('.custom-modal-overlay');
            if (overlay) {
                overlay.addEventListener('click', () => {
                    this.pdfViewer.close();
                    this.voiceReader.stop();
                });
            }
        }
        
        // Listener para cambios de filtros
        this.initFilterListeners();
    }
    
    /**
     * Inicializa listeners para los filtros de nivel y categoría
     */
    initFilterListeners() {
        // Filtros de nivel
        const nivelButtons = document.querySelectorAll('[data-nivel-filter]');
        nivelButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const nivel = btn.getAttribute('data-nivel-filter');
                this.filterByNivel(nivel);
            });
        });
        
        // Filtros de categoría
        const categoriaButtons = document.querySelectorAll('[data-categoria-filter]');
        categoriaButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const categoria = btn.getAttribute('data-categoria-filter');
                this.filterByCategoria(categoria);
            });
        });
    }
    
    /**
     * Carga los detalles de un libro en el modal
     * @param {number} idRecurso ID del recurso
     */
    async cargarDetallesLibro(idRecurso) {
        const modalBody = document.getElementById('libroModalBody');
        
        if (!modalBody) {
            console.error('Modal body no encontrado');
            return;
        }
        
        // Mostrar loading
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando detalles del recurso...</p>
            </div>
        `;
        
        try {
            const response = await fetch(`${APP_CONFIG.routes.detallesRecurso}${idRecurso}`);
            
            if (!response.ok) {
                throw new Error('Error al cargar detalles');
            }
            
            const html = await response.text();
            modalBody.innerHTML = html;
            
        } catch (error) {
            console.error('Error al cargar detalles del libro:', error);
            
            modalBody.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del recurso. Por favor intenta nuevamente.
                </div>
            `;
        }
    }
    
    /**
     * Filtra recursos por nivel educativo
     * @param {string} nivel Nivel educativo
     */
    filterByNivel(nivel) {
        console.log('Filtrando por nivel:', nivel);
        
        // Implementar lógica de filtrado
        // Esto podría recargar la página con parámetros o filtrar en el cliente
        
        const url = new URL(window.location);
        if (nivel === 'todos') {
            url.searchParams.delete('nivel');
        } else {
            url.searchParams.set('nivel', nivel);
        }
        
        window.location.href = url.toString();
    }
    
    /**
     * Filtra recursos por categoría
     * @param {string} categoria Categoría
     */
    filterByCategoria(categoria) {
        console.log('Filtrando por categoría:', categoria);
        
        const url = new URL(window.location);
        if (categoria === 'todos') {
            url.searchParams.delete('categoria');
        } else {
            url.searchParams.set('categoria', categoria);
        }
        
        window.location.href = url.toString();
    }
    
    /**
     * Busca recursos
     * @param {string} query Búsqueda
     */
    search(query) {
        if (!query || query.trim().length < 2) {
            return;
        }
        
        const url = new URL(window.APP_CONFIG.baseUrl + 'catalogo');
        url.searchParams.set('q', query.trim());
        
        window.location.href = url.toString();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.paginaPrincipal = new PaginaPrincipalController();
});

// Manejar navegación con teclas
document.addEventListener('keydown', (e) => {
    // Buscar con Ctrl/Cmd + K
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('busquedaRecurso');
        if (searchInput) {
            searchInput.focus();
        }
    }
});
