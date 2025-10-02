/**
 * SOLUCIÓN UNIVERSAL PARA MODALES QUE APARECEN DEBAJO DEL SIDEBAR
 * Biblioteca Virtual HZG - Modal Fix
 * 
 * USO:
 * 1. Incluir este archivo en la página
 * 2. Llamar: fixModalZIndex('idDelModal', 'nombreDelModal')
 * 
 * EJEMPLO:
 * fixModalZIndex('modalCrearRecurso', 'CrearRecurso');
 * fixModalZIndex('modalNuevoUsuario', 'NuevoUsuario');
 */

// Función universal para aplicar fix de z-index a cualquier modal
function fixModalZIndex(modalId, modalName) {
    console.log(`🔧 Aplicando fix de z-index para ${modalName}`);
    
    const modal = document.getElementById(modalId);
    
    if (modal) {
        // Aplicar estilos directamente con JavaScript
        modal.style.zIndex = '99999';
        modal.style.position = 'fixed';
        
        // Aplicar a elementos internos también
        const modalDialog = modal.querySelector('.modal-dialog');
        const modalContent = modal.querySelector('.modal-content');
        
        if (modalDialog) {
            modalDialog.style.zIndex = '100000';
            modalDialog.style.position = 'relative';
        }
        
        if (modalContent) {
            modalContent.style.zIndex = '100001';
            modalContent.style.position = 'relative';
        }
        
        // Mover al body si no está ahí
        if (modal.parentElement.id !== 'body' && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
            console.log(`Modal ${modalName} movido al body`);
        }
        
        console.log(`Z-index del modal ${modalName} forzado a 99999`);
        return true;
    } else {
        console.warn(`⚠️ Modal ${modalName} (${modalId}) no encontrado`);
        return false;
    }
}

// Función para crear CSS dinámico para un modal específico
function createModalCSS(modalId) {
    const styleId = `style-fix-${modalId}`;
    
    // Verificar si ya existe el estilo
    if (document.getElementById(styleId)) {
        return;
    }
    
    const css = `
        /* SOLUCIÓN DEFINITIVA: Z-index extremadamente alto para ${modalId} */
        #${modalId},
        #${modalId}.modal,
        #${modalId}.modal.fade,
        #${modalId}.modal.show {
            z-index: 99999 !important;
            position: fixed !important;
        }

        #${modalId} .modal-dialog {
            z-index: 100000 !important;
            position: relative !important;
        }

        #${modalId} .modal-content {
            z-index: 100001 !important;
            position: relative !important;
        }

        #${modalId} .modal-header,
        #${modalId} .modal-body,
        #${modalId} .modal-footer {
            z-index: 100002 !important;
            position: relative !important;
        }

        /* Reglas específicas con máxima especificidad */
        body .modal#${modalId} {
            z-index: 99999 !important;
        }

        body .modal#${modalId}.show {
            z-index: 99999 !important;
            display: block !important;
        }

        html body .modal#${modalId} {
            z-index: 99999 !important;
        }

        /* Fix específico para el contenedor principal */
        #contenedor-principal .modal#${modalId} {
            z-index: 99999 !important;
        }

        /* Asegurar que funcione en el contexto del dashboard */
        .page-wrapper .modal#${modalId},
        .body-wrapper .modal#${modalId} {
            z-index: 99999 !important;
        }
    `;
    
    const style = document.createElement('style');
    style.id = styleId;
    style.textContent = css;
    document.head.appendChild(style);
    
    console.log(`✅ CSS creado para modal ${modalId}`);
}

// Función para configurar completamente un modal
function setupModalFix(modalId, modalName) {
    const functionName = `reinicializarModal${modalName}`;
    
    // Crear función global de reinicialización
    window[functionName] = function() {
        setTimeout(() => fixModalZIndex(modalId, modalName), 50);
    };
    
    // Crear CSS dinámico
    createModalCSS(modalId);
    
    // Configurar eventos
    function configureModal() {
        setTimeout(() => fixModalZIndex(modalId, modalName), 100);
        
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            // Eventos de Bootstrap
            modalElement.addEventListener('show.bs.modal', function() {
                fixModalZIndex(modalId, modalName);
            });
            
            modalElement.addEventListener('shown.bs.modal', function() {
                fixModalZIndex(modalId, modalName);
            });
            
            console.log(`✅ Eventos configurados para modal ${modalName}`);
        } else {
            console.warn(`⚠️ Modal ${modalName} no encontrado para configurar eventos`);
        }
    }
    
    // Configurar inmediatamente y observar cambios
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', configureModal);
    } else {
        configureModal();
    }
    
    // Observador de mutaciones
    const observer = new MutationObserver(function() {
        const modal = document.getElementById(modalId);
        if (modal) {
            fixModalZIndex(modalId, modalName);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    console.log(`🚀 Modal ${modalName} configurado completamente`);
}

// Lista de modales comunes en el sistema
const commonModals = [
    { id: 'modalCrearRecurso', name: 'CrearRecurso' },
    { id: 'modalEditarRecurso', name: 'EditarRecurso' },
    { id: 'modalNuevoUsuario', name: 'NuevoUsuario' },
    { id: 'modalDetalleUsuario', name: 'DetalleUsuario' },
    { id: 'modalEditarUsuario', name: 'EditarUsuario' },
    { id: 'modalNuevoAutor', name: 'NuevoAutor' },
    { id: 'modalEditarAutor', name: 'EditarAutor' },
    { id: 'modalNuevaSancion', name: 'NuevaSancion' },
    { id: 'modalEditarSancion', name: 'EditarSancion' }
];

// Función para aplicar fix a todos los modales comunes
function fixAllCommonModals() {
    console.log('🔧 Aplicando fix a todos los modales comunes...');
    
    commonModals.forEach(modal => {
        setupModalFix(modal.id, modal.name);
    });
    
    console.log('✅ Fix aplicado a todos los modales comunes');
}

// Auto-inicialización si el script se carga
if (typeof window !== 'undefined') {
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fixAllCommonModals);
    } else {
        fixAllCommonModals();
    }
}

// Exportar funciones para uso manual
window.fixModalZIndex = fixModalZIndex;
window.setupModalFix = setupModalFix;
window.fixAllCommonModals = fixAllCommonModals;