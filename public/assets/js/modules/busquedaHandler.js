/**
 * Módulo para manejar la búsqueda y filtrado de recursos
 */
class BusquedaHandler {
    constructor() {
        this.resultsContainer = null;
        this.searchForm = null;
        this.filtersForm = null;
        this.resetButton = null;
        this.libroModal = null;
        this.libroModalBody = null;
        this.searchTimeout = null;
        this.requestTimeout = 30000; // 30 segundos
        this.retryAttempts = 3;
        
        this.init();
    }
    
    /**
     * Inicializa el handler
     */
    init() {
        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupElements());
        } else {
            this.setupElements();
        }
    }
    
    /**
     * Configura las referencias a elementos del DOM
     */
    setupElements() {
        this.resultsContainer = document.getElementById('resultados-busqueda');
        this.filtersForm = document.getElementById('filtros-form');
        this.resetButton = document.getElementById('reset-filtros');
        this.libroModal = document.getElementById('libroModal');
        this.libroModalBody = document.getElementById('libroModalBody');
        
        this.initEventListeners();
    }
    
    /**
     * Inicializa todos los event listeners
     */
    initEventListeners() {
        this.setupSearchForm();
        this.setupFilterForm();
        this.setupResetButton();
        this.setupLibroClick();
        this.setupModalCleanup();
        this.setupCategoriaFilter();
    }
    
    /**
     * Configura el formulario de búsqueda principal
     */
    setupSearchForm() {
        const searchForm = document.querySelector('form[action*="buscarRecursos"]');
        if (!searchForm) return;
        
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const query = formData.get('query');
            
            this.loadResults(
                window.APP_CONFIG?.baseUrl + 'recursos/filtrosBusqueda',
                { query: query }
            );
        });
    }
    
    /**
     * Configura el formulario de filtros
     */
    setupFilterForm() {
        if (!this.filtersForm) return;
        
        this.filtersForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const params = Object.fromEntries(formData);
            
            this.loadResults(e.target.action, params);
        });
    }
    
    /**
     * Configura el botón de resetear filtros
     */
    setupResetButton() {
        if (!this.resetButton) return;
        
        this.resetButton.addEventListener('click', () => {
            setTimeout(() => {
                if (!this.filtersForm) return;
                
                this.filtersForm.reset();
                // Resetear subcategorías al valor por defecto (todas)
                this.resetSubcategorias();
                this.loadResults(this.filtersForm.action);
            }, 50);
        });
    }

    /**
     * Configura el filtro en cascada de categoría -> subcategoría
     */
    setupCategoriaFilter() {
        const categoriaSelect = document.getElementById('categoria');
        const subcategoriaSelect = document.getElementById('subcategoria');
        
        if (!categoriaSelect || !subcategoriaSelect) return;
        
        // Guardar las subcategorías originales
        this.todasSubcategorias = Array.from(subcategoriaSelect.options).map(option => ({
            value: option.value,
            text: option.text,
            categoria: option.getAttribute('data-categoria')
        }));
        
        categoriaSelect.addEventListener('change', async (e) => {
            const idcategoria = e.target.value;
            
            if (!idcategoria) {
                // Si no hay categoría seleccionada, mostrar todas las subcategorías
                this.resetSubcategorias();
                return;
            }
            
            // Cargar subcategorías de la categoría seleccionada
            await this.loadSubcategorias(idcategoria);
        });
    }

    /**
     * Carga las subcategorías de una categoría específica
     */
    async loadSubcategorias(idcategoria) {
        const subcategoriaSelect = document.getElementById('subcategoria');
        if (!subcategoriaSelect) return;
        
        try {
            // Mostrar loading
            subcategoriaSelect.disabled = true;
            subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
            
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const url = `${baseUrl}recursos/subcategorias-por-categoria?idcategoria=${idcategoria}`;
            
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!response.ok) {
                throw new Error('Error al cargar subcategorías');
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Limpiar el select
                subcategoriaSelect.innerHTML = '<option value="">Todas</option>';
                
                // Agregar las subcategorías de la categoría seleccionada
                data.subcategorias.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.idsubcategoria;
                    option.text = sub.subcategoria;
                    subcategoriaSelect.appendChild(option);
                });
            } else {
                this.resetSubcategorias();
                console.error('Error:', data.message);
            }
            
        } catch (error) {
            console.error('Error al cargar subcategorías:', error);
            this.resetSubcategorias();
        } finally {
            subcategoriaSelect.disabled = false;
        }
    }

    /**
     * Resetea el select de subcategorías a su estado original
     */
    resetSubcategorias() {
        const subcategoriaSelect = document.getElementById('subcategoria');
        if (!subcategoriaSelect) return;
        
        // Si no tenemos las subcategorías guardadas, solo reseteamos
        if (!this.todasSubcategorias || this.todasSubcategorias.length === 0) {
            subcategoriaSelect.selectedIndex = 0;
            return;
        }
        
        // Restaurar todas las subcategorías
        subcategoriaSelect.innerHTML = '';
        this.todasSubcategorias.forEach(sub => {
            const option = document.createElement('option');
            option.value = sub.value;
            option.text = sub.text;
            if (sub.categoria) {
                option.setAttribute('data-categoria', sub.categoria);
            }
            subcategoriaSelect.appendChild(option);
        });
    }
    
    /**
     * Configura el clic en libros para mostrar detalles
     */
    setupLibroClick() {
        document.addEventListener('click', (e) => {
            const libroItem = e.target.closest('.libro-item');
            if (!libroItem) return;
            
            const libroId = libroItem.getAttribute('data-libro-id');
            this.loadLibroDetails(libroId);
        });
    }
    
    /**
     * Configura la limpieza del modal al cerrar
     */
    setupModalCleanup() {
        if (!this.libroModal) return;
        
        this.libroModal.addEventListener('hidden.bs.modal', () => {
            if (this.libroModalBody) {
                this.libroModalBody.innerHTML = '';
            }
        });
    }
    
    /**
     * Carga resultados de búsqueda/filtros
     */
    async loadResults(url, params = {}, retryCount = 0) {
        if (!this.resultsContainer) return;
        
        try {
            this.showLoading();
            this.disableFormInputs(true);
            
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = queryString ? `${url}?${queryString}` : url;
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), this.requestTimeout);
            
            const response = await fetch(fullUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const html = await response.text();
            this.resultsContainer.innerHTML = html;
            
        } catch (error) {
            console.error('Error:', error);
            
            // Retry automático en caso de error de red
            if (error.name === 'AbortError' || error.name === 'TypeError') {
                if (retryCount < this.retryAttempts) {
                    console.log(`Reintentando... (${retryCount + 1}/${this.retryAttempts})`);
                    await new Promise(resolve => setTimeout(resolve, 1000 * (retryCount + 1)));
                    return this.loadResults(url, params, retryCount + 1);
                }
            }
            
            this.showError(
                error.name === 'AbortError' 
                    ? 'La solicitud tardó demasiado. Por favor, intenta nuevamente.'
                    : 'Error al cargar los resultados. Por favor, intenta nuevamente.'
            );
        } finally {
            this.disableFormInputs(false);
        }
    }
    
    /**
     * Carga detalles de un libro en el modal
     */
    async loadLibroDetails(libroId) {
        if (!this.libroModalBody) return;
        
        try {
            this.showModalLoading();
            
            const url = (window.APP_CONFIG?.routes?.detallesRecurso || '/recursos/detalles/') + libroId;
            
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!response.ok) {
                throw new Error('Error al cargar los detalles');
            }
            
            const html = await response.text();
            this.libroModalBody.innerHTML = html;
            
        } catch (error) {
            console.error('Error:', error);
            this.showModalError();
        }
    }
    
    /**
     * Muestra estado de carga en el contenedor de resultados
     */
    showLoading() {
        if (!this.resultsContainer) return;
        
        this.resultsContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-3">Cargando resultados...</p>
            </div>
        `;
    }
    
    /**
     * Muestra estado de carga en el modal
     */
    showModalLoading() {
        if (!this.libroModalBody) return;
        
        this.libroModalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-2">Cargando detalles del libro...</p>
            </div>
        `;
    }
    
    /**
     * Muestra error en el contenedor de resultados
     */
    showError(message) {
        if (!this.resultsContainer) return;
        
        this.resultsContainer.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }
    
    /**
     * Muestra error en el modal
     */
    showModalError() {
        if (!this.libroModalBody) return;
        
        this.libroModalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error al cargar los detalles del libro.
            </div>
        `;
    }
    
    /**
     * Deshabilita/habilita inputs de formularios durante carga
     */
    disableFormInputs(disable) {
        const forms = [this.searchForm, this.filtersForm].filter(f => f);
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, button');
            inputs.forEach(input => {
                input.disabled = disable;
                if (disable) {
                    input.classList.add('disabled');
                } else {
                    input.classList.remove('disabled');
                }
            });
        });
    }
    
    /**
     * Debounce para evitar llamadas excesivas
     */
    debounce(func, wait) {
        return (...args) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
}

// Inicializar automáticamente
if (typeof window !== 'undefined') {
    window.BusquedaHandler = BusquedaHandler;
    
    // Auto-inicializar si estamos en la página de búsqueda
    if (document.getElementById('resultados-busqueda')) {
        window.busquedaHandler = new BusquedaHandler();
    }
}

// Exportar para uso como módulo
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BusquedaHandler;
}
