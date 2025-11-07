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
        }
        
        return true;
    }
    
    return false;
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
}

// Función para configurar completamente un modal
function setupModalFix(modalId, modalName) {
    // Primero verificar si el modal existe en el DOM
    const modalExists = document.getElementById(modalId);
    
    if (!modalExists) {
        // Si el modal no existe, no hacer nada (sin logs)
        return false;
    }
    
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
            
            console.log(`✅ Modal ${modalName} configurado`);
        }
    }
    
    // Configurar inmediatamente
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', configureModal);
    } else {
        configureModal();
    }
    
    // Observador de mutaciones solo para este modal específico
    const observer = new MutationObserver(function() {
        const modal = document.getElementById(modalId);
        if (modal && modal.classList.contains('show')) {
            fixModalZIndex(modalId, modalName);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class']
    });
    
    return true;
}

// Lista de modales comunes en el sistema
const commonModals = [
    { id: 'modalCrearRecurso', name: 'CrearRecurso' },
    { id: 'modalEditarRecurso', name: 'EditarRecurso' },
    { id: 'modalNuevoUsuario', name: 'NuevoUsuario' },
    { id: 'modalDetalleUsuario', name: 'DetalleUsuario' },
    { id: 'modalEditarUsuario', name: 'EditarUsuario' },
    { id: 'modalEditarUsuarioCompleto', name: 'EditarUsuarioCompleto' },
    { id: 'modalNuevoAutor', name: 'NuevoAutor' },
    { id: 'modalEditarAutor', name: 'EditarAutor' },
    { id: 'modalNuevaSancion', name: 'NuevaSancion' },
    { id: 'modalEditarSancion', name: 'EditarSancion' },
    { id: 'modalEditarEjemplar', name: 'EditarEjemplar' },
    { id: 'modalEditarEjemplarInterno', name: 'EditarEjemplarInterno' }
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
        document.addEventListener('DOMContentLoaded', fixAllCommonModalsEnhanced);
    } else {
        fixAllCommonModalsEnhanced();
    }
}

// Función para asegurar que SweetAlert2 aparezca por encima de los modales
function fixSweetAlertZIndex() {
    // CSS para SweetAlert2
    const sweetAlertCSS = `
        /* Fix para SweetAlert2 - debe aparecer por encima de todos los modales */
        .swal2-container {
            z-index: 999999 !important;
        }
        
        .swal2-popup {
            z-index: 999999 !important;
        }
        
        /* Backdrop de SweetAlert2 también debe estar por encima */
        .swal2-backdrop {
            z-index: 999998 !important;
        }
        
        /* Asegurar que funcione en cualquier contexto */
        body .swal2-container,
        html body .swal2-container {
            z-index: 999999 !important;
        }
        
        /* Fix específico cuando hay modales abiertos */
        .modal-open .swal2-container {
            z-index: 999999 !important;
        }
    `;
    
    const styleId = 'sweetalert2-zindex-fix';
    
    // Verificar si ya existe el estilo
    if (!document.getElementById(styleId)) {
        const style = document.createElement('style');
        style.id = styleId;
        style.textContent = sweetAlertCSS;
        document.head.appendChild(style);
    }
}

// Función para interceptar y configurar SweetAlert2
function setupSweetAlert2() {
    // Aplicar CSS inmediatamente
    fixSweetAlertZIndex();
    
    // Si SweetAlert2 está disponible, configurarlo
    if (typeof Swal !== 'undefined') {
        // Configuración global para SweetAlert2
        const originalFire = Swal.fire;
        
        Swal.fire = function(...args) {
            // Asegurar z-index antes de mostrar
            fixSweetAlertZIndex();
            
            // Llamar al método original
            const result = originalFire.apply(this, args);
            
            // Asegurar z-index después de mostrar también
            setTimeout(fixSweetAlertZIndex, 100);
            
            return result;
        };
    }
}

// Observador para SweetAlert2 que se crea dinámicamente
function observeSweetAlert2() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Verificar si es un contenedor de SweetAlert2
                    if (node.classList && node.classList.contains('swal2-container')) {
                        node.style.zIndex = '999999';
                        
                        // También aplicar a popup interno
                        const popup = node.querySelector('.swal2-popup');
                        if (popup) {
                            popup.style.zIndex = '999999';
                        }
                    }
                    
                    // Buscar dentro del nodo agregado
                    const sweetAlerts = node.querySelectorAll && node.querySelectorAll('.swal2-container');
                    if (sweetAlerts && sweetAlerts.length > 0) {
                        sweetAlerts.forEach(function(sweetAlert) {
                            sweetAlert.style.zIndex = '999999';
                            
                            const popup = sweetAlert.querySelector('.swal2-popup');
                            if (popup) {
                                popup.style.zIndex = '999999';
                            }
                        });
                    }
                }
            });
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Función mejorada para aplicar fix a todos los modales comunes
function fixAllCommonModalsEnhanced() {
    // Configurar SweetAlert2 primero
    setupSweetAlert2();
    
    // Activar observador de SweetAlert2
    observeSweetAlert2();
    
    // Configurar solo los modales que existen en el DOM
    let configuredCount = 0;
    commonModals.forEach(modal => {
        if (setupModalFix(modal.id, modal.name)) {
            configuredCount++;
        }
    });
    
    if (configuredCount > 0) {
        console.log(`✅ ${configuredCount} modal(es) configurado(s) correctamente`);
    }
}

// Exportar funciones para uso manual
window.fixModalZIndex = fixModalZIndex;
window.setupModalFix = setupModalFix;
window.fixAllCommonModals = fixAllCommonModalsEnhanced; // Usar la versión mejorada
window.fixSweetAlertZIndex = fixSweetAlertZIndex;
window.setupSweetAlert2 = setupSweetAlert2;