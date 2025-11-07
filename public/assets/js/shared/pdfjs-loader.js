/**
 * Cargador de la biblioteca PDF.js
 * Gestiona la carga dinámica de PDF.js desde múltiples CDNs
 */
class PDFJSLoader {
    constructor() {
        this.isLoaded = false;
        this.loadPromise = null;
        
        // Lista de CDNs alternativos (incluyendo versiones más estables)
        this.cdnUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js'
        ];
        
        this.workerUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js',
            'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js',
            'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.worker.min.js'
        ];
    }
    
    /**
     * Asegura que PDF.js esté cargado
     * @returns {Promise}
     */
    ensureLoaded() {
        if (this.isLoaded && typeof pdfjsLib !== 'undefined') {
            return Promise.resolve();
        }
        
        if (this.loadPromise) {
            return this.loadPromise;
        }
        
        this.loadPromise = this.loadLibrary();
        return this.loadPromise;
    }
    
    /**
     * Carga la biblioteca PDF.js
     * @returns {Promise}
     */
    loadLibrary() {
        return new Promise((resolve, reject) => {
            if (typeof pdfjsLib !== 'undefined') {
                console.log('PDF.js ya está cargado');
                this.isLoaded = true;
                resolve();
                return;
            }
            
            let currentIndex = 0;
            
            const tryLoadScript = () => {
                if (currentIndex >= this.cdnUrls.length) {
                    console.error('No se pudo cargar PDF.js desde ningún CDN');
                    reject(new Error('No se pudo cargar PDF.js'));
                    return;
                }
                
                const scriptUrl = this.cdnUrls[currentIndex];
                const workerUrl = this.workerUrls[currentIndex];
                
                console.log(`Intentando cargar PDF.js desde: ${scriptUrl}`);
                
                const script = document.createElement('script');
                script.src = scriptUrl;
                script.async = true;
                
                script.onload = () => {
                    console.log(`PDF.js cargado exitosamente desde: ${scriptUrl}`);
                    
                    if (typeof pdfjsLib !== 'undefined') {
                        pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;
                        this.isLoaded = true;
                        resolve();
                    } else {
                        console.warn('Script cargado pero pdfjsLib no está definido');
                        currentIndex++;
                        tryLoadScript();
                    }
                };
                
                script.onerror = () => {
                    console.warn(`Error cargando desde ${scriptUrl}, intentando siguiente CDN...`);
                    currentIndex++;
                    tryLoadScript();
                };
                
                document.head.appendChild(script);
            };
            
            tryLoadScript();
        });
    }
}

// Crear instancia global
window.PDFJSLoader = new PDFJSLoader();
