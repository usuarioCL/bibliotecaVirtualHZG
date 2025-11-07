# Arquitectura de la Página Principal Refactorizada

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         PÁGINA PRINCIPAL (56 líneas)                         │
│                        paginaPrincipal.php                                   │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                 ┌────────────────────┼────────────────────┐
                 │                    │                    │
                 ▼                    ▼                    ▼
        ┌────────────────┐   ┌────────────────┐  ┌────────────────┐
        │  CSS IMPORTS   │   │  PARTIALS PHP  │  │  JS MODULES    │
        └────────────────┘   └────────────────┘  └────────────────┘
                 │                    │                    │
    ┌────────────┴────────┐          │          ┌─────────┴─────────┐
    │                     │          │          │                   │
    ▼                     ▼          │          ▼                   ▼
┌─────────────┐   ┌─────────────┐   │    ┌──────────┐       ┌──────────┐
│pdf-viewer-  │   │voice-       │   │    │ shared/  │       │ modules/ │
│modal.css    │   │controls.css │   │    └──────────┘       └──────────┘
└─────────────┘   └─────────────┘   │          │                   │
                                     │          ▼                   ▼
                                     │    ┌──────────┐       ┌──────────┐
                                     │    │pdfjs-    │       │pdfViewer │
                                     │    │loader.js │       │.js       │
                                     │    └──────────┘       └──────────┘
                                     │    ┌──────────┐       ┌──────────┐
                                     │    │voice-    │       │voice     │
                                     │    │utils.js  │       │Reader.js │
                                     │    └──────────┘       └──────────┘
                                     │                       ┌──────────┐
                                     │                       │prestamo  │
                                     │                       │Form.js   │
                                     │                       └──────────┘
                                     │                       ┌──────────┐
                                     │                       │favoritos │
                                     │                       │Handler.js│
                                     │                       └──────────┘
                                     │
                        ┌────────────┴────────────┐
                        │                         │
                        ▼                         ▼
              ┌──────────────────┐      ┌──────────────────┐
              │ hero_section.php │      │ modals/          │
              └──────────────────┘      └──────────────────┘
              ┌──────────────────┐                │
              │ niveles_         │      ┌─────────┴─────────┐
              │ categorias_      │      │                   │
              │ tabs.php         │      ▼                   ▼
              └──────────────────┘  ┌────────────┐  ┌────────────┐
              ┌──────────────────┐  │libro_modal │  │pdf_viewer_ │
              │ recursos_grid.php│  │.php        │  │modal.php   │
              └──────────────────┘  └────────────┘  └────────────┘


═══════════════════════════════════════════════════════════════════════════════

                           FLUJO DE DATOS Y CONTROL

┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                              │
│  1. Usuario carga página → paginaPrincipal.php                              │
│                                                                              │
│  2. Se cargan CSS de componentes (pdf-viewer-modal, voice-controls)         │
│                                                                              │
│  3. Se renderizan partials PHP:                                             │
│     ├── hero_section.php (búsqueda)                                         │
│     ├── niveles_categorias_tabs.php (navegación)                            │
│     ├── recursos_grid.php (grid de libros)                                  │
│     └── modals/ (libro_modal, pdf_viewer_modal)                             │
│                                                                              │
│  4. Se cargan scripts en orden:                                             │
│     ├── shared/pdfjs-loader.js (carga PDF.js)                               │
│     ├── shared/voice-utils.js (utilidades de voz)                           │
│     ├── modules/pdfViewer.js (clase PDFViewer)                              │
│     ├── modules/voiceReader.js (clase VoiceReader)                          │
│     ├── modules/prestamoForm.js (clase PrestamoForm)                        │
│     ├── modules/favoritosHandler.js (clase FavoritosHandler)                │
│     └── paginaPrincipal.js (orquestador)                                    │
│                                                                              │
│  5. PaginaPrincipalController inicializa:                                   │
│     ├── new PDFViewer()                                                     │
│     ├── new VoiceReader(pdfViewer)                                          │
│     ├── new PrestamoForm()                                                  │
│     └── new FavoritosHandler()                                              │
│                                                                              │
│  6. Se exponen funciones globales:                                          │
│     ├── cargarDetallesLibro(id)                                             │
│     ├── leerPDFDirecto(url, title)                                          │
│     ├── toggleFavorito(id)                                                  │
│     └── solicitarPrestamo(id)                                               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════════════

                         INTERACCIÓN DE MÓDULOS

         ┌──────────────────────────────────────────────┐
         │    PaginaPrincipalController (Orquestador)   │
         └──────────────────────────────────────────────┘
                           │
         ┌─────────────────┼─────────────────┐
         │                 │                 │
         ▼                 ▼                 ▼
    ┌─────────┐      ┌──────────┐     ┌──────────┐
    │PDFViewer│◄─────│VoiceReader│     │Prestamo  │
    │         │      │          │     │Form      │
    └─────────┘      └──────────┘     └──────────┘
         │                                   │
         │                                   │
         ▼                                   ▼
    [Extrae Texto]                    [Verifica Sanciones]
         │                                   │
         └───────────────┬───────────────────┘
                         │
                         ▼
                  ┌──────────────┐
                  │ Favoritos    │
                  │ Handler      │
                  └──────────────┘
                         │
                         ▼
                  [Toggle Favorito]


═══════════════════════════════════════════════════════════════════════════════

                    DEPENDENCIAS ENTRE MÓDULOS

PDFViewer (independiente)
    └── Utiliza: PDFJSLoader (shared)

VoiceReader
    ├── Depende de: PDFViewer (recibe instancia)
    └── Utiliza: VoiceUtils (shared)

PrestamoForm (independiente)
    └── Utiliza: APP_CONFIG (global)

FavoritosHandler (independiente)
    └── Utiliza: APP_CONFIG (global)

PaginaPrincipalController
    ├── Instancia: PDFViewer
    ├── Instancia: VoiceReader (inyecta PDFViewer)
    ├── Instancia: PrestamoForm
    └── Instancia: FavoritosHandler


═══════════════════════════════════════════════════════════════════════════════

                     EVENTOS Y COMUNICACIÓN

┌───────────────┐         ┌───────────────┐         ┌───────────────┐
│   Usuario     │         │   Módulo      │         │   Servidor    │
└───────┬───────┘         └───────┬───────┘         └───────┬───────┘
        │                         │                         │
        │ Click "Ver PDF"         │                         │
        ├────────────────────────►│                         │
        │                         │                         │
        │                         │ PDFViewer.open()        │
        │                         ├────────┐                │
        │                         │        │                │
        │                         │◄───────┘                │
        │                         │                         │
        │                         │ Fetch PDF               │
        │                         ├────────────────────────►│
        │                         │                         │
        │                         │◄────────────────────────┤
        │                         │ PDF Data                │
        │                         │                         │
        │ Click "Leer Voz"        │                         │
        ├────────────────────────►│                         │
        │                         │                         │
        │                         │ VoiceReader.start()     │
        │                         ├────────┐                │
        │                         │        │                │
        │                         │◄───────┘                │
        │                         │                         │
        │◄────────────────────────┤                         │
        │ Audio Output            │                         │
        │                         │                         │


═══════════════════════════════════════════════════════════════════════════════

                        ESTRUCTURA DE CLASES

class PDFViewer {
    - modalId: string
    - viewerId: string
    - currentUrl: string
    - pdfDoc: object
    - textContent: string
    - isLoaded: boolean
    
    + constructor(config)
    + open(url, title): Promise
    + close(): void
    + getText(): string
    + extractText(): Promise
    + isOpen(): boolean
}

class VoiceReader {
    - pdfViewer: PDFViewer
    - synthesis: SpeechSynthesis
    - utterance: SpeechSynthesisUtterance
    - isReading: boolean
    - isPaused: boolean
    - speed: number
    
    + constructor(pdfViewer)
    + start(): void
    + pause(): void
    + stop(): void
    + changeSpeed(speed): void
}

class PrestamoForm {
    - modalId: string
    - formId: string
    - currentRecursoId: number
    
    + constructor()
    + open(recursoId): Promise
    + verificarSanciones(): Promise<boolean>
    + submitForm(): Promise
}

class FavoritosHandler {
    - favorites: Set<number>
    
    + constructor()
    + toggle(recursoId): Promise
    + isFavorite(recursoId): boolean
    + showToast(message, type): void
}

class PaginaPrincipalController {
    - pdfViewer: PDFViewer
    - voiceReader: VoiceReader
    - prestamoForm: PrestamoForm
    - favoritosHandler: FavoritosHandler
    
    + constructor()
    + init(): Promise
    + cargarDetallesLibro(id): Promise
}


═══════════════════════════════════════════════════════════════════════════════

                    REDUCCIÓN DE COMPLEJIDAD

┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                              │
│  ANTES: paginaPrincipal.php (1,690 líneas)                                  │
│  ┌───────────────────────────────────────────────────────────────────┐     │
│  │ HTML + PHP + JavaScript + CSS + Lógica + Estilos + Eventos      │     │
│  │                                                                   │     │
│  │ • Todo mezclado en un archivo                                    │     │
│  │ • Difícil de mantener                                            │     │
│  │ • Imposible de testear                                           │     │
│  │ • No reutilizable                                                │     │
│  │ • Alta complejidad ciclomática                                   │     │
│  └───────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  DESPUÉS: Arquitectura Modular (13 archivos, 56 líneas principales)        │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐              │
│  │   Vistas PHP   │  │   CSS          │  │   JavaScript   │              │
│  │   (Partials)   │  │   (Componentes)│  │   (Módulos)    │              │
│  │                │  │                │  │                │              │
│  │ • Separados    │  │ • Extraído     │  │ • Modular      │              │
│  │ • Reutilizables│  │ • Semántico    │  │ • Testeable    │              │
│  │ • Semánticos   │  │ • Mantenible   │  │ • Reutilizable │              │
│  └────────────────┘  └────────────────┘  └────────────────┘              │
│                                                                              │
│  Complejidad Ciclomática:  Alto → Bajo                                     │
│  Acoplamiento:             Alto → Bajo                                     │
│  Cohesión:                 Baja → Alta                                     │
│  Mantenibilidad:           Difícil → Fácil                                 │
│  Testabilidad:             Imposible → Posible                             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```
