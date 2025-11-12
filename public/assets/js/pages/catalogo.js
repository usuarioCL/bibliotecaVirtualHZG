/**
 * Módulo de gestión del catálogo de recursos
 * @author Sistema Biblioteca Virtual HZG
 * @version 1.0.0
 */

// Configuración global del módulo
const CatalogoConfig = {
    urls: {
        subcategorias: null, // Se inicializa desde PHP
        nivel: null, // Se inicializa desde PHP
        detallesRecurso: null // Se inicializa desde PHP
    },
    classes: {
        cardCol: 'col-lg-2 col-md-4 col-sm-6',
        cardDefault: 'card h-100 shadow-sm rounded libro-card'
    },
    selectors: {
        contenido: '#contenido',
        loading: '#loading',
        contenidoInicial: '#contenido-inicial',
        btnCategoria: '.btn-categoria',
        btnNivel: '.btn-nivel',
        modalBody: '#libroModalBody',
        modal: '#libroModal'
    },
    animations: {
        duration: 300,
        delay: 100
    }
};

/**
 * Gestor de errores centralizado
 */
const ErrorHandler = {
    /**
     * Muestra un mensaje de error formateado
     */
    mostrar(mensaje, tipo = 'danger') {
        const iconos = {
            danger: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        
        return `
            <div class="alert alert-${tipo} text-center border-0">
                <i class="fas ${iconos[tipo]} fa-2x mb-3"></i>
                <h5>${mensaje}</h5>
            </div>`;
    },
    
    /**
     * Registra error en consola con contexto
     */
    registrar(error, contexto = 'General') {
        console.error(`[Catálogo - ${contexto}]:`, error);
    },
    
    /**
     * Muestra error de carga
     */
    errorCarga(mensaje) {
        return this.mostrar(mensaje || 'Error al cargar el contenido', 'danger');
    },
    
    /**
     * Muestra estado vacío
     */
    estadoVacio(mensaje) {
        return `
            <div class="text-center py-5">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">${mensaje}</h4>
            </div>`;
    }
};

/**
 * Generador de tarjetas de libros
 */
const CardGenerator = {
    /**
     * Detecta si un recurso es digital
     */
    detectarTipoRecurso(libro) {
        if (libro.tiporecurso && libro.tiporecurso.toLowerCase().includes('digital')) {
            return true;
        }
        if (libro.idtiporecurso == 2) {
            return true;
        }
        if (libro.archivo && libro.archivo.trim() !== '') {
            return true;
        }
        return false;
    },
    
    /**
     * Genera el badge de tipo de recurso
     */
    generarBadgeTipo(esDigital) {
        if (esDigital) {
            return `
                <span class="badge bg-info text-white">
                    <i class="fas fa-file-pdf me-1"></i>Digital
                </span>`;
        } else {
            return `
                <span class="badge bg-primary text-white">
                    <i class="fas fa-book me-1"></i>Físico
                </span>`;
        }
    },
    
    /**
     * Genera la imagen o placeholder
     */
    generarImagen(libro, rutaImagen, esDigital) {
        if (rutaImagen) {
            return `
                <img src="${rutaImagen}" 
                     class="card-img-top h-100 w-100 libro-card__image" 
                     alt="${libro.titulo}"
                     data-recurso-id="${libro.idrecurso}">`;
        } else {
            const icono = esDigital 
                ? '<i class="fas fa-file-pdf fa-2x mb-2 text-info"></i>'
                : '<i class="fas fa-book fa-2x mb-2"></i>';
            
            return `
                <div class="libro-card__placeholder bg-light">
                    <div class="text-center text-muted">
                        ${icono}
                        <small>Sin portada</small>
                    </div>
                </div>`;
        }
    },
    
    /**
     * Genera el overlay con información del libro
     */
    generarOverlay(libro, autorTexto) {
        return `
            <div class="libro-card__overlay">
                <h6 class="libro-card__titulo" title="${libro.titulo}">
                    ${libro.titulo}
                </h6>
                <p class="libro-card__autor" title="${autorTexto}">
                    ${autorTexto}
                </p>
                <p class="libro-card__anio">
                    ${libro.anio || 'N/A'}
                </p>
            </div>`;
    },
    
    /**
     * Genera una tarjeta individual de libro
     */
    generarCard(libro, colClasses = CatalogoConfig.classes.cardCol) {
        const autorTexto = libro.autores || libro.nomautor || 'Sin autor';
        const esDigital = this.detectarTipoRecurso(libro);
        const rutaImagen = libro.portada ? window.base_url + libro.portada : null;
        
        return `
            <div class="${colClasses}">
                <div class="${CatalogoConfig.classes.cardDefault}" 
                     data-bs-toggle="modal" 
                     data-bs-target="${CatalogoConfig.selectors.modal}"
                     data-libro-id="${libro.idrecurso}"
                     onclick="CatalogoManager.cargarDetallesLibro(${libro.idrecurso})">
                    
                    <div class="libro-card__badge">
                        ${this.generarBadgeTipo(esDigital)}
                    </div>
                    
                    <div class="libro-card__image-container">
                        ${this.generarImagen(libro, rutaImagen, esDigital)}
                        ${this.generarOverlay(libro, autorTexto)}
                    </div>
                </div>
            </div>`;
    },
    
    /**
     * Genera múltiples tarjetas
     */
    generarMultiples(libros, colClasses = CatalogoConfig.classes.cardCol) {
        return libros.map(libro => {
            const libroFormateado = {
                ...libro,
                portada: libro.portada || libro.rutaportada,
                nomautor: libro.autores || libro.nomautor || 'Sin autor'
            };
            return this.generarCard(libroFormateado, colClasses);
        }).join('');
    }
};

/**
 * Gestor de animaciones
 */
const AnimationManager = {
    /**
     * Aplica animaciones de entrada a las cards
     */
    aplicarAnimaciones() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.card').forEach(card => {
            card.classList.add('animate-ready');
            observer.observe(card);
        });
    }
};

/**
 * Gestor principal del catálogo
 */
const CatalogoManager = {
    /**
     * Inicializa el módulo
     */
    init(config = {}) {
        // Configurar URLs desde PHP
        if (config.urls) {
            CatalogoConfig.urls = { ...CatalogoConfig.urls, ...config.urls };
        }
        
        this.bindEvents();
        AnimationManager.aplicarAnimaciones();
    },
    
    /**
     * Vincula eventos
     */
    bindEvents() {
        // Eventos de botones de categoría
        document.querySelectorAll(CatalogoConfig.selectors.btnCategoria).forEach(btn => {
            btn.addEventListener("click", (e) => this.handleCategoriaClick(e));
        });
        
        // Eventos de botones de nivel educativo
        document.querySelectorAll(CatalogoConfig.selectors.btnNivel).forEach(btn => {
            btn.addEventListener("click", (e) => this.handleNivelClick(e));
        });
        
        // Limpiar modal al cerrar
        const modal = document.querySelector(CatalogoConfig.selectors.modal);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                document.querySelector(CatalogoConfig.selectors.modalBody).innerHTML = '';
            });
        }
    },
    
    /**
     * Maneja el click en botón de categoría
     */
    handleCategoriaClick(event) {
        const btn = event.currentTarget;
        
        if (btn.disabled) return;
        
        // Deshabilitar todos los botones
        this.toggleBotones(false);
        
        // Actualizar estado visual
        this.actualizarEstadoBotones(btn, 'categoria');
        
        // Cargar subcategorías
        const idCategoria = btn.dataset.id;
        this.cargarSubcategorias(idCategoria).finally(() => {
            this.toggleBotones(true);
        });
    },

    /**
     * Maneja el click en botón de nivel educativo
     */
    handleNivelClick(event) {
        const btn = event.currentTarget;
        
        if (btn.disabled) return;
        
        // Deshabilitar todos los botones
        this.toggleBotones(false);
        
        // Actualizar estado visual
        this.actualizarEstadoBotones(btn, 'nivel');
        
        // Cargar por nivel
        const nivel = btn.dataset.nivel;
        this.cargarPorNivel(nivel).finally(() => {
            this.toggleBotones(true);
        });
    },
    
    /**
     * Habilita/deshabilita botones
     */
    toggleBotones(habilitar) {
        document.querySelectorAll(CatalogoConfig.selectors.btnCategoria).forEach(b => {
            b.disabled = !habilitar;
        });
        document.querySelectorAll(CatalogoConfig.selectors.btnNivel).forEach(b => {
            b.disabled = !habilitar;
        });
    },
    
    /**
     * Actualiza estado visual de botones
     */
    actualizarEstadoBotones(btnActivo, tipo) {
        // Desactivar todos los botones de categoría
        document.querySelectorAll(CatalogoConfig.selectors.btnCategoria).forEach(b => {
            b.classList.remove('btn-primary', 'btn-secondary', 'active');
            b.classList.add('btn-outline-primary');
        });
        
        // Desactivar todos los botones de nivel
        document.querySelectorAll(CatalogoConfig.selectors.btnNivel).forEach(b => {
            b.classList.remove('btn-primary', 'btn-secondary', 'active');
            b.classList.add('btn-outline-primary');
        });
        
        // Activar el botón clickeado
        btnActivo.classList.remove('btn-outline-primary');
        const esInicio = btnActivo.dataset.id == '0';
        btnActivo.classList.add(esInicio ? 'btn-secondary' : 'btn-primary', 'active');
    },
    
    /**
     * Carga subcategorías por categoría
     */
    cargarSubcategorias(idCat) {
        const contenido = document.querySelector(CatalogoConfig.selectors.contenido);
        const loading = document.querySelector(CatalogoConfig.selectors.loading);
        const contenidoInicial = document.querySelector(CatalogoConfig.selectors.contenidoInicial);
        
        // Si es "Todos" (id=0), mostrar contenido inicial
        if (idCat == 0) {
            return this.mostrarContenidoInicial(contenido, loading, contenidoInicial);
        }
        
        // Mostrar loading
        this.mostrarLoading(contenido, loading, contenidoInicial);
        
        const url = `${CatalogoConfig.urls.subcategorias}/${idCat}`;
        
        return fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const html = this.generarHTMLSubcategorias(data);
            this.actualizarContenido(contenido, loading, contenidoInicial, html);
        })
        .catch(error => {
            ErrorHandler.registrar(error, 'cargarSubcategorias');
            this.mostrarError(contenido, loading, error);
        });
    },

    /**
     * Carga subcategorías por nivel educativo
     */
    cargarPorNivel(nivel) {
        const contenido = document.querySelector(CatalogoConfig.selectors.contenido);
        const loading = document.querySelector(CatalogoConfig.selectors.loading);
        const contenidoInicial = document.querySelector(CatalogoConfig.selectors.contenidoInicial);
        
        // Mostrar loading
        this.mostrarLoading(contenido, loading, contenidoInicial);
        
        const url = `${CatalogoConfig.urls.nivel}/${nivel}`;
        
        return fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const html = this.generarHTMLSubcategorias(data, nivel);
            this.actualizarContenido(contenido, loading, contenidoInicial, html);
        })
        .catch(error => {
            ErrorHandler.registrar(error, 'cargarPorNivel');
            this.mostrarError(contenido, loading, error);
        });
    },
    
    /**
     * Muestra el contenido inicial
     */
    mostrarContenidoInicial(contenido, loading, contenidoInicial) {
        contenido.innerHTML = '';
        contenido.appendChild(loading);
        
        const contenidoClonado = contenidoInicial.cloneNode(true);
        contenidoClonado.classList.remove('d-none');
        contenido.appendChild(contenidoClonado);
        
        loading.classList.add('d-none');
        
        setTimeout(() => AnimationManager.aplicarAnimaciones(), CatalogoConfig.animations.delay);
        
        return Promise.resolve();
    },
    
    /**
     * Muestra estado de carga
     */
    mostrarLoading(contenido, loading, contenidoInicial) {
        contenido.innerHTML = '';
        contenido.appendChild(loading);
        contenido.appendChild(contenidoInicial);
        if (contenidoInicial) contenidoInicial.classList.add('d-none');
        loading.classList.remove('d-none');
    },
    
    /**
     * Genera HTML de subcategorías
     */
    generarHTMLSubcategorias(data, nivelFiltro = null) {
        if (!data || data.length === 0) {
            const mensajeFiltro = nivelFiltro ? ` para el nivel ${nivelFiltro}` : '';
            return ErrorHandler.estadoVacio(`No se encontraron recursos${mensajeFiltro}`);
        }
        
        let html = '';
        
        data.forEach(sub => {
            const totalLibros = sub.libros ? sub.libros.length : 0;
            
            html += `
                <div class="subcategoria-section mb-5">
                    <div class="subcategoria-header">
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
                html += CardGenerator.generarMultiples(sub.libros);
            } else {
                html += `
                    <div class="col-12">
                        ${ErrorHandler.estadoVacio('No hay recursos disponibles en esta subcategoría')}
                    </div>`;
            }
            
            html += '</div></div>';
        });
        
        return html;
    },
    
    /**
     * Actualiza el contenido del catálogo
     */
    actualizarContenido(contenido, loading, contenidoInicial, html) {
        loading.classList.add('d-none');
        
        const nuevoContenido = document.createElement('div');
        nuevoContenido.innerHTML = html;
        
        contenido.innerHTML = '';
        contenido.appendChild(loading);
        contenido.appendChild(contenidoInicial);
        contenidoInicial.classList.add('d-none');
        contenido.appendChild(nuevoContenido);
        
        setTimeout(() => AnimationManager.aplicarAnimaciones(), CatalogoConfig.animations.delay);
    },
    
    /**
     * Muestra error de carga
     */
    mostrarError(contenido, loading, error) {
        loading.classList.add('d-none');
        contenido.innerHTML = ErrorHandler.errorCarga(
            `Error al cargar el contenido: ${error.message}`
        );
    },
    
    /**
     * Carga detalles de un libro en el modal
     */
    cargarDetallesLibro(idRecurso) {
        const modalBody = document.querySelector(CatalogoConfig.selectors.modalBody);
        
        // Mostrar loading
        modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando detalles del recurso...</p>
            </div>`;
        
        const url = `${CatalogoConfig.urls.detallesRecurso}${idRecurso}`;
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(error => {
                ErrorHandler.registrar(error, 'cargarDetallesLibro');
                modalBody.innerHTML = ErrorHandler.mostrar(
                    'Error al cargar los detalles del recurso. Por favor intenta nuevamente.'
                );
            });
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // La configuración de URLs se pasa desde PHP
    if (typeof window.catalogoConfig !== 'undefined') {
        CatalogoManager.init(window.catalogoConfig);
        
        // Verificar si hay un filtro en el hash de la URL
        if (window.location.hash) {
            const hash = window.location.hash.substring(1); // Remover el #
            
            if (hash.startsWith('nivel=')) {
                const nivel = decodeURIComponent(hash.split('=')[1]);
                const btnNivel = document.querySelector(`[data-nivel="${nivel}"]`);
                if (btnNivel) {
                    setTimeout(() => btnNivel.click(), 100);
                }
            } else if (hash.startsWith('categoria=')) {
                const idCategoria = hash.split('=')[1];
                const btnCategoria = document.querySelector(`[data-id="${idCategoria}"][data-tipo="categoria"]`);
                if (btnCategoria) {
                    setTimeout(() => btnCategoria.click(), 100);
                }
            }
        }
    } else {
        console.warn('No se encontró configuración del catálogo');
    }
    
    // Inicializar PDFViewer si existe
    if (typeof PDFViewer !== 'undefined') {
        window.pdfViewer = new PDFViewer();
    }
    
    // Inicializar VoiceReader si existe
    if (typeof VoiceReader !== 'undefined' && window.pdfViewer) {
        window.voiceReader = new VoiceReader(window.pdfViewer);
    }
});

// Exponer al scope global para uso desde HTML onclick (migrar a event listeners)
window.CatalogoManager = CatalogoManager;
window.CardGenerator = CardGenerator;

/**
 * Función global para abrir PDF (compatibilidad con botones onclick)
 * Se conecta con el PDFViewer del footer
 */
window.verPDF = function(url, titulo) {
    if (window.pdfViewer && typeof window.pdfViewer.open === 'function') {
        window.pdfViewer.open(url, titulo);
    } else {
        console.error('PDFViewer no está inicializado');
        // Fallback: abrir en nueva pestaña
        window.open(url, '_blank');
    }
};

/**
 * Función global para cerrar modal PDF (compatibilidad con botones onclick)
 */
window.cerrarModalPDF = function() {
    if (window.pdfViewer && typeof window.pdfViewer.close === 'function') {
        window.pdfViewer.close();
    }
};

/**
 * Función global para abrir PDF en nueva pestaña
 */
window.abrirPDFEnNuevaPestana = function() {
    if (window.pdfViewer && typeof window.pdfViewer.openInNewTab === 'function') {
        window.pdfViewer.openInNewTab();
    }
};

/**
 * Funciones de control de voz (compatibilidad con botones onclick)
 */
window.toggleVoiceReading = function() {
    if (window.voiceReader && typeof window.voiceReader.toggle === 'function') {
        window.voiceReader.toggle();
    }
};

window.pauseVoiceReading = function() {
    if (window.voiceReader && typeof window.voiceReader.pause === 'function') {
        window.voiceReader.pause();
    }
};

window.stopVoiceReading = function() {
    if (window.voiceReader && typeof window.voiceReader.stop === 'function') {
        window.voiceReader.stop();
    }
};

window.changeVoiceSpeed = function(speed) {
    if (window.voiceReader && typeof window.voiceReader.changeSpeed === 'function') {
        window.voiceReader.changeSpeed(speed);
    }
};
