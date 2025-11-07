/**
 * Constantes de configuración para el módulo de Préstamos
 * Centraliza todos los valores de configuración del sistema
 */

window.PrestamosConstants = window.PrestamosConstants || {
    /**
     * Configuración de horarios de biblioteca
     */
    HORARIOS: {
        HORA_MIN_MINUTOS: 8 * 60,     // 8:00 AM en minutos
        HORA_MAX_MINUTOS: 13 * 60,    // 1:00 PM en minutos
        HORA_MIN_FORMATO: '08:00',
        HORA_MAX_FORMATO: '13:00',
        HORA_MAX_INICIO: '12:59',
        HORA_MIN_FIN: '08:01'
    },

    /**
     * Días laborales (lunes = 1, viernes = 5)
     */
    DIAS_LABORALES: {
        MIN: 1,  // Lunes
        MAX: 5,  // Viernes
        NOMBRES: {
            0: 'Domingo',
            1: 'Lunes',
            2: 'Martes',
            3: 'Miércoles',
            4: 'Jueves',
            5: 'Viernes',
            6: 'Sábado'
        }
    },

    /**
     * Estados de préstamos
     */
    ESTADOS: {
        ACTIVO: 'Activo',
        VENCIDO: 'Vencido',
        DEVUELTO: 'Devuelto',
        CANCELADO: 'Cancelado',
        PENDIENTE: 'Pendiente'
    },

    /**
     * Configuración de estados (colores e iconos)
     */
    ESTADO_CONFIG: {
        'Activo': {
            color: 'success',
            icono: 'ti-check-circle',
            badge: 'bg-success-subtle text-success'
        },
        'Vencido': {
            color: 'danger',
            icono: 'ti-x-circle',
            badge: 'bg-danger-subtle text-danger'
        },
        'Por Vencer': {
            color: 'warning',
            icono: 'ti-alert-triangle',
            badge: 'bg-warning-subtle text-warning'
        },
        'Devuelto': {
            color: 'info',
            icono: 'ti-book-upload',
            badge: 'bg-info-subtle text-info'
        },
        'Cancelado': {
            color: 'secondary',
            icono: 'ti-ban',
            badge: 'bg-secondary-subtle text-secondary'
        }
    },

    /**
     * Estados de devolución
     */
    ESTADOS_DEVOLUCION: {
        BUENO: 'bueno',
        CON_INCIDENCIA: 'con_incidencia'
    },

    /**
     * Detalles de incidencias por tipo
     */
    DETALLES_INCIDENCIAS: {
        'daño': [
            { value: 'paginas_rasgadas', text: 'Páginas rasgadas' },
            { value: 'paginas_faltantes', text: 'Páginas faltantes' },
            { value: 'portada_danada', text: 'Portada dañada' },
            { value: 'manchas_humedad', text: 'Manchas o humedad' },
            { value: 'lomo_roto', text: 'Lomo roto o despegado' },
            { value: 'rayones_escritura', text: 'Rayones o escritura' },
            { value: 'encuadernacion_dañada', text: 'Encuadernación dañada' },
            { value: 'otro_daño', text: 'Otro tipo de daño' }
        ],
        'pérdida': [
            { value: 'extraviado', text: 'Material extraviado' },
            { value: 'no_devuelto', text: 'No devuelto en plazo' },
            { value: 'robado', text: 'Reportado como robado' },
            { value: 'otro_perdida', text: 'Otra causa de pérdida' }
        ],
        'retraso': [
            { value: 'olvido', text: 'Olvido de fecha de devolución' },
            { value: 'imposibilidad', text: 'Imposibilidad de asistir' },
            { value: 'enfermedad', text: 'Enfermedad o emergencia' },
            { value: 'otro_retraso', text: 'Otro motivo de retraso' }
        ],
        'incumplimiento': [
            { value: 'no_respetar_horarios', text: 'No respetar horarios' },
            { value: 'uso_inadecuado', text: 'Uso inadecuado del material' },
            { value: 'prestamo_terceros', text: 'Préstamo a terceros sin autorización' },
            { value: 'otro_incumplimiento', text: 'Otro incumplimiento' }
        ],
        'comportamiento': [
            { value: 'desorden', text: 'Generar desorden en biblioteca' },
            { value: 'ruido_excesivo', text: 'Ruido excesivo' },
            { value: 'falta_respeto', text: 'Falta de respeto al personal' },
            { value: 'otro_comportamiento', text: 'Otro comportamiento inadecuado' }
        ]
    },

    /**
     * Configuración de SweetAlert2
     */
    SWAL_CONFIG: {
        COLORS: {
            confirm: '#0d6efd',
            cancel: '#6c757d',
            warning: '#ffc107',
            success: '#28a745',
            danger: '#dc3545'
        },
        TIMER: {
            SHORT: 2000,
            MEDIUM: 3000,
            LONG: 5000
        }
    },

    /**
     * Mensajes del sistema
     */
    MENSAJES: {
        LOADING: {
            GENERAL: 'Procesando...',
            DETALLE: 'Cargando información del préstamo...',
            RENOVAR: 'Renovando préstamo',
            DEVOLUCION: 'Registrando devolución',
            CREAR: 'Creando préstamo',
            TIPOS_SANCION: 'Obteniendo tipos de sanción'
        },
        ERROR: {
            CONEXION: 'Ha ocurrido un error de conexión',
            DETALLE: 'No se pudieron obtener los detalles del préstamo',
            RENOVAR: 'No se pudo renovar el préstamo',
            DEVOLUCION: 'No se pudo procesar la devolución',
            CREAR: 'No se pudo crear el préstamo',
            VALIDACION: 'Por favor, corrige los errores en el formulario'
        },
        VALIDACION: {
            FECHA_REQUERIDA: 'La fecha es obligatoria.',
            HORA_REQUERIDA: 'La hora es obligatoria.',
            DIA_LABORAL: 'Solo se pueden programar préstamos de lunes a viernes.',
            FECHA_PASADA: 'No se puede seleccionar una fecha pasada.',
            HORA_INICIO_RANGO: 'La hora de inicio debe estar entre 8:00 AM y 12:59 PM.',
            HORA_FIN_RANGO: 'La hora de fin debe estar entre 8:01 AM y 1:00 PM.',
            HORA_FIN_POSTERIOR: 'La hora de fin debe ser posterior a la hora de inicio.',
            USUARIO_REQUERIDO: 'Debes seleccionar un usuario',
            RECURSO_REQUERIDO: 'Debes seleccionar un recurso disponible',
            TIPO_SANCION_REQUERIDO: 'Debes seleccionar el tipo de incidencia',
            DETALLE_INCIDENCIA_REQUERIDO: 'Debes seleccionar el detalle específico de la incidencia'
        }
    },

    /**
     * Configuración de auto-corrección
     */
    AUTO_CORRECCION: {
        DELAY: 2000,  // 2 segundos de delay antes de auto-corregir
        EXTENSION_DEFAULT: 60  // 60 minutos de extensión por defecto
    },

    /**
     * Umbrales de tiempo
     */
    UMBRALES: {
        DIAS_PROXIMOS_VENCER: 3,  // Días para considerar "próximo a vencer"
        MINIMO_DURACION_MINUTOS: 1  // Mínimo de duración de un préstamo
    },

    /**
     * Configuración de paginación y límites
     */
    LIMITES: {
        BUSQUEDA_MAX_RESULTADOS: 10,
        RENOVACIONES_MAX: 5  // Máximo de renovaciones permitidas
    }
};

// Hacer disponible globalmente
if (typeof window !== 'undefined') {
    window.PrestamosConstants = PrestamosConstants;
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrestamosConstants;
}
