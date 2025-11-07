/**
 * Módulo para gestión de favoritos
 * Maneja agregar/remover recursos de favoritos
 */
class FavoritosHandler {
    constructor() {
        this.favorites = new Set();
        this.loadFavorites();
        this.initEventListeners();
    }
    
    /**
     * Inicializa los event listeners
     */
    initEventListeners() {
        // Event delegation para botones de favoritos
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-toggle-favorito, [onclick*="toggleFavorito"]');
            if (button) {
                e.preventDefault();
                e.stopPropagation();
                
                const recursoId = button.getAttribute('data-recurso-id');
                
                if (recursoId && recursoId !== '0') {
                    this.toggle(parseInt(recursoId));
                } else {
                    console.error('Botón de favorito sin data-recurso-id válido');
                }
            }
        });
    }
    
    /**
     * Carga los favoritos del usuario desde el servidor o localStorage
     */
    loadFavorites() {
        // Por ahora, cargar desde localStorage como backup
        try {
            const stored = localStorage.getItem('userFavorites');
            if (stored) {
                this.favorites = new Set(JSON.parse(stored));
            }
        } catch (error) {
            console.error('Error al cargar favoritos:', error);
        }
    }
    
    /**
     * Alterna el estado de favorito de un recurso
     * @param {number} recursoId ID del recurso
     */
    async toggle(recursoId) {
        // Validar que se reciba el ID
        if (!recursoId) {
            console.error('Error: ID de recurso requerido');
            this.showToast('Error: ID de recurso no válido', 'error');
            return;
        }
        
        const button = this.getButtonByRecursoId(recursoId);
        
        if (!button) {
            console.error('Botón de favorito no encontrado para recurso:', recursoId);
            return;
        }
        
        // Mostrar loading en el botón
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        try {
            const response = await fetch(window.APP_CONFIG.routes.toggleFavorito, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ idrecurso: recursoId })
            });
            
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Actualizar estado (el servidor devuelve 'agregado')
                const esFavorito = result.agregado || result.favorito;
                
                if (esFavorito) {
                    this.add(recursoId);
                } else {
                    this.remove(recursoId);
                }
                
                // Actualizar UI de TODOS los botones con este recurso-id
                this.updateAllButtons(recursoId, esFavorito);
                
                // Mostrar toast de confirmación
                this.showToast(
                    result.message || (esFavorito ? 'Agregado a favoritos' : 'Removido de favoritos'),
                    'success'
                );
            } else {
                throw new Error(result.message || 'Error al actualizar favorito');
            }
            
        } catch (error) {
            console.error('Error al toggle favorito:', error);
            
            // Restaurar botón
            button.innerHTML = originalIcon;
            button.disabled = false;
            
            // Mostrar error
            this.showToast(
                'Error al actualizar favorito. Por favor intenta nuevamente.',
                'error'
            );
        }
    }
    
    /**
     * Agrega un recurso a favoritos
     * @param {number} recursoId ID del recurso
     */
    add(recursoId) {
        this.favorites.add(recursoId);
        this.saveFavorites();
    }
    
    /**
     * Remueve un recurso de favoritos
     * @param {number} recursoId ID del recurso
     */
    remove(recursoId) {
        this.favorites.delete(recursoId);
        this.saveFavorites();
    }
    
    /**
     * Verifica si un recurso está en favoritos
     * @param {number} recursoId ID del recurso
     * @returns {boolean}
     */
    isFavorite(recursoId) {
        return this.favorites.has(recursoId);
    }
    
    /**
     * Guarda favoritos en localStorage
     */
    saveFavorites() {
        try {
            localStorage.setItem('userFavorites', JSON.stringify([...this.favorites]));
        } catch (error) {
            console.error('Error al guardar favoritos:', error);
        }
    }
    
    /**
     * Obtiene el botón de favorito por ID de recurso
     * @param {number} recursoId ID del recurso
     * @returns {HTMLElement|null}
     */
    getButtonByRecursoId(recursoId) {
        // Buscar por atributo data-recurso-id o por onclick
        let button = document.querySelector(`[data-recurso-id="${recursoId}"]`);
        
        if (!button) {
            // Buscar por onclick que contenga el ID
            const buttons = document.querySelectorAll('[onclick*="toggleFavorito"]');
            for (const btn of buttons) {
                if (btn.getAttribute('onclick').includes(`(${recursoId})`)) {
                    button = btn;
                    break;
                }
            }
        }
        
        return button;
    }
    
    /**
     * Actualiza el estado visual del botón
     * @param {HTMLElement} button Botón a actualizar
     * @param {boolean} isFavorite Si es favorito o no
     */
    updateButton(button, isFavorite) {
        button.disabled = false;
        
        if (isFavorite) {
            button.innerHTML = '<i class="fas fa-heart"></i> Quitar de Favoritos';
            button.classList.remove('btn-outline-primary', 'btn-outline-danger');
            button.classList.add('btn-danger');
            button.setAttribute('title', 'Quitar de favoritos');
        } else {
            button.innerHTML = '<i class="far fa-heart"></i> Agregar a Favoritos';
            button.classList.remove('btn-danger', 'btn-outline-danger');
            button.classList.add('btn-outline-primary');
            button.setAttribute('title', 'Agregar a favoritos');
        }
    }
    
    /**
     * Actualiza todos los botones de favorito para un recurso específico
     * Esto asegura que todos los botones en la página se actualicen,
     * incluyendo los que están en modales o duplicados
     * @param {number} recursoId ID del recurso
     * @param {boolean} isFavorite Si es favorito o no
     */
    updateAllButtons(recursoId, isFavorite) {
        // Buscar solo BOTONES con este recurso-id (no otros elementos como imágenes)
        const buttons = document.querySelectorAll(`button[data-recurso-id="${recursoId}"], button.btn-toggle-favorito[data-recurso-id="${recursoId}"]`);
        
        buttons.forEach(button => {
            this.updateButton(button, isFavorite);
        });
    }
    
    /**
     * Muestra un toast de notificación
     * @param {string} message Mensaje a mostrar
     * @param {string} type Tipo de mensaje (success, error, info)
     */
    showToast(message, type = 'info') {
        // Si existe una librería de toasts (ej: Bootstrap Toast, Toastr, etc.)
        // usarla aquí. Por ahora, crear un toast simple
        
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#198754' : type === 'error' ? '#dc3545' : '#0dcaf0'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            max-width: 300px;
        `;
        
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
}

// Agregar animaciones CSS si no existen
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.innerHTML = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// Exportar como global
window.FavoritosHandler = FavoritosHandler;
