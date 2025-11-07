/**
 * Módulo para visualización de PDFs
 * Gestiona la carga, visualización y extracción de texto de archivos PDF
 */
class PDFViewer {
    constructor(config = {}) {
        this.modalId = config.modalId || 'modalPDF';
        this.viewerId = config.viewerId || 'pdfViewer';
        this.currentUrl = '';
        this.pdfDoc = null;
        this.textContent = '';
        this.isLoaded = false;
        
        this.initModal();
        this.bindEvents();
    }
    
    /**
     * Inicializa las referencias a los elementos del DOM
     */
    initModal() {
        this.modal = document.getElementById(this.modalId);
        this.viewer = document.getElementById(this.viewerId);
        this.loading = document.getElementById('pdfLoading');
        this.error = document.getElementById('pdfError');
        this.modalTitle = document.getElementById('modalPDFLabel');
        this.downloadBtn = document.getElementById('descargarPDF');
    }
    
    /**
     * Vincula eventos del modal
     */
    bindEvents() {
        // Cerrar con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
    }
    
    /**
     * Abre el modal con el PDF especificado
     * @param {string} url URL del PDF
     * @param {string} title Título del PDF
     */
    async open(url, title) {
        this.currentUrl = this.ensureSecureUrl(url);
        
        this.showLoading();
        this.modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        this.modalTitle.textContent = `Visualizar: ${title}`;
        this.downloadBtn.href = this.currentUrl;
        
        // Cargar PDF en iframe
        this.loadInIframe();
        
        // Extraer texto para voz en paralelo
        await this.extractText();
    }
    
    /**
     * Cierra el modal
     */
    close() {
        this.modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        this.viewer.src = '';
        this.viewer.onload = null;
        this.viewer.onerror = null;
        this.reset();
    }
    
    /**
     * Verifica si el modal está abierto
     * @returns {boolean}
     */
    isOpen() {
        return this.modal.style.display === 'block';
    }
    
    /**
     * Muestra el indicador de carga
     */
    showLoading() {
        this.loading.style.display = 'block';
        this.error.style.display = 'none';
        this.viewer.style.display = 'none';
    }
    
    /**
     * Muestra el mensaje de error
     */
    showError() {
        this.loading.style.display = 'none';
        this.error.style.display = 'block';
        this.viewer.style.display = 'none';
    }
    
    /**
     * Carga el PDF en el iframe
     */
    loadInIframe() {
        console.log('Cargando PDF en iframe:', this.currentUrl);
        
        this.viewer.src = this.currentUrl;
        
        this.viewer.onload = () => {
            console.log('Iframe onload disparado');
            this.loading.style.display = 'none';
            this.viewer.style.display = 'block';
            this.verifyLoad();
        };
        
        this.viewer.onerror = () => {
            console.log('Iframe onerror disparado');
            this.showError();
        };
        
        // Timeout de seguridad más generoso - 15 segundos
        setTimeout(() => {
            if (this.loading.style.display !== 'none') {
                console.log('Timeout alcanzado, forzando visualización del PDF');
                // En lugar de mostrar error inmediatamente, intentamos mostrar el PDF
                this.loading.style.display = 'none';
                this.viewer.style.display = 'block';
                
                // Si después de 3 segundos más sigue sin cargar, entonces sí mostramos error
                setTimeout(() => {
                    if (this.viewer.style.display === 'block') {
                        try {
                            // Verificación final más simple
                            if (this.viewer.contentDocument === null && this.viewer.src) {
                                // PDF probablemente cargado pero bloqueado por CORS
                                console.log('PDF asumido como cargado (CORS)');
                                return;
                            }
                        } catch (e) {
                            // Cualquier excepción asumimos que está cargado
                            console.log('PDF asumido como cargado (excepción en verificación)');
                            return;
                        }
                    }
                }, 3000);
            }
        }, 15000);
    }
    
    /**
     * Verifica que el PDF se haya cargado correctamente
     */
    verifyLoad() {
        // Dar más tiempo para que el PDF se cargue completamente
        setTimeout(() => {
            try {
                const iframeDoc = this.viewer.contentDocument || this.viewer.contentWindow.document;
                
                // Si no podemos acceder al documento (CORS), asumimos que se cargó correctamente
                if (!iframeDoc) {
                    console.log('PDF cargado (CORS bloqueado, asumimos éxito)');
                    return;
                }
                
                // Verificación más permisiva: solo mostrar error si realmente hay un problema
                const bodyContent = iframeDoc.body?.innerHTML || '';
                const hasErrorContent = bodyContent.includes('error') || 
                                      bodyContent.includes('404') || 
                                      bodyContent.includes('not found') ||
                                      bodyContent.includes('forbidden');
                
                if (hasErrorContent) {
                    console.log('Detectado error en contenido del PDF, mostrando fallback');
                    this.showError();
                } else {
                    console.log('PDF verificado como cargado correctamente');
                }
            } catch (e) {
                // CORS bloqueado o cualquier otro error de acceso - asumimos éxito
                console.log('PDF cargado (verificación bloqueada por navegador, asumimos éxito)');
            }
        }, 2000); // Aumentamos el tiempo a 2 segundos para dar más margen
    }
    
    /**
     * Extrae el texto del PDF usando PDF.js
     */
    async extractText() {
        try {
            await window.PDFJSLoader.ensureLoaded();
            
            console.log('Iniciando extracción de texto del PDF...');
            const pdf = await pdfjsLib.getDocument(this.currentUrl).promise;
            this.pdfDoc = pdf;
            
            console.log(`PDF cargado: ${pdf.numPages} páginas`);
            
            let allText = '';
            for (let i = 1; i <= pdf.numPages; i++) {
                const page = await pdf.getPage(i);
                const textContent = await page.getTextContent();
                const pageText = textContent.items.map(item => item.str).join(' ');
                allText += pageText + '\n\n';
            }
            
            this.textContent = allText.trim().replace(/\s+/g, ' ');
            this.isLoaded = true;
            
            console.log(`Texto extraído exitosamente: ${this.textContent.length} caracteres`);
        } catch (error) {
            console.error('Error extrayendo texto del PDF:', error);
            
            // Verificar si es un error de CORS
            if (error.name === 'UnknownErrorException' || 
                error.message?.includes('CORS') || 
                error.message?.includes('fetch')) {
                this.textContent = 'No se pudo acceder al PDF debido a restricciones de CORS. La funcionalidad de voz no está disponible para este documento.';
            } else {
                this.textContent = 'No se pudo extraer texto del PDF.';
            }
            
            this.isLoaded = true;
        }
    }
    
    /**
     * Obtiene el texto extraído del PDF
     * @returns {string}
     */
    getText() {
        if (!this.isLoaded) {
            return 'El PDF aún se está cargando...';
        }
        return this.textContent || 'No hay texto disponible.';
    }
    
    /**
     * Asegura que la URL use HTTPS en producción
     * @param {string} url URL original
     * @returns {string} URL segura
     */
    ensureSecureUrl(url) {
        const isLocal = window.location.hostname.includes('localhost') || 
                       window.location.hostname.includes('.test');
        
        if (!isLocal && url.startsWith('http://')) {
            return url.replace('http://', 'https://');
        }
        return url;
    }
    
    /**
     * Abre el PDF en una nueva pestaña
     */
    openInNewTab() {
        if (this.currentUrl) {
            window.open(this.currentUrl, '_blank');
        }
    }
    
    /**
     * Resetea el estado del visor
     */
    reset() {
        this.currentUrl = '';
        this.pdfDoc = null;
        this.textContent = '';
        this.isLoaded = false;
    }
}

// Exportar como global
window.PDFViewer = PDFViewer;
