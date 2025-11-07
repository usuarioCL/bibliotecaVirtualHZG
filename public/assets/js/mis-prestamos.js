/**
 * Módulo de Gestión de Préstamos
 * Maneja todas las interacciones de la vista "Mis Préstamos"
 */
class PrestamoManager {
    constructor() {
        this.baseUrl = window.location.origin;
        this.init();
    }

    /**
     * Inicializar el módulo
     */
    init() {
        console.log('Vista de préstamos cargada correctamente');
        this.setupModalCleanup();
    }

    /**
     * Configurar limpieza del modal al cerrarse
     */
    setupModalCleanup() {
        const modal = document.getElementById('prestamoModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                this.resetModalContent();
            });
        }
    }

    /**
     * Resetear contenido del modal
     */
    resetModalContent() {
        const modalBody = document.getElementById('prestamoModalBody');
        if (modalBody) {
            modalBody.innerHTML = this.getLoadingHTML();
        }
    }

    /**
     * Obtener HTML de loading
     */
    getLoadingHTML() {
        return `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando detalles del préstamo...</p>
            </div>
        `;
    }

    /**
     * Cargar detalles del préstamo
     */
    async cargarDetalles(idPrestamo) {
        const modalBody = document.getElementById('prestamoModalBody');
        if (!modalBody) return;

        modalBody.innerHTML = this.getLoadingHTML();

        try {
            const response = await fetch(`${this.baseUrl}/prestamo/detalles/${idPrestamo}`);
            
            if (!response.ok) {
                throw new Error('Error al cargar detalles');
            }

            const html = await response.text();
            modalBody.innerHTML = html;
        } catch (error) {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del préstamo.
                </div>
            `;
        }
    }

    /**
     * Renovar préstamo
     */
    async renovar(idPrestamo) {
        try {
            const response = await fetch(`${this.baseUrl}/prestamo/formulario-renovacion/${idPrestamo}`);
            
            if (!response.ok) {
                throw new Error('Error al cargar el formulario');
            }

            const html = await response.text();
            
            Swal.fire({
                title: '<i class="fas fa-redo me-2"></i>Renovar Préstamo',
                html: html,
                width: '800px',
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'swal-wide',
                    htmlContainer: 'swal-html-container-custom'
                }
            });
        } catch (error) {
            console.error('Error al cargar formulario de renovación:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el formulario de renovación. Por favor, intente nuevamente.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    /**
     * Devolver préstamo
     */
    async devolver(idPrestamo) {
        const result = await Swal.fire({
            title: '¿Devolver libro?',
            text: 'Confirma que vas a devolver este libro.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        });

        if (result.isConfirmed) {
            await this.procesarDevolucion(idPrestamo);
        }
    }

    /**
     * Procesar devolución del préstamo
     */
    async procesarDevolucion(idPrestamo) {
        try {
            const response = await fetch(`${this.baseUrl}/catalogo/devolver-prestamo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ idprestamo: idPrestamo })
            });

            const data = await response.json();

            if (data.success) {
                await Swal.fire({
                    title: 'Libro Devuelto',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                location.reload();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al devolver el libro',
                    icon: 'error'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión',
                icon: 'error'
            });
        }
    }

    /**
     * Validar fecha de devolución
     */
    validarFechaDevolucion() {
        const fechaInicio = document.getElementById('nuevaFechaPrestamo');
        const fechaDevolucion = document.getElementById('nuevaFechaDevolucion');

        if (!fechaInicio || !fechaDevolucion || !fechaInicio.value || !fechaDevolucion.value) {
            return;
        }

        const inicio = new Date(fechaInicio.value + 'T00:00:00');
        const devolucion = new Date(fechaDevolucion.value + 'T00:00:00');
        const diffTime = devolucion - inicio;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays < 0) {
            fechaDevolucion.setCustomValidity('La fecha de devolución no puede ser anterior a la fecha de inicio');
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha de devolución no puede ser anterior a la fecha de inicio',
                confirmButtonColor: '#f39c12'
            });
            this.resetFechaMaxima(inicio, fechaDevolucion);
        } else if (diffDays > 7) {
            fechaDevolucion.setCustomValidity('No puede extender el préstamo por más de 7 días');
            Swal.fire({
                icon: 'warning',
                title: 'Período máximo excedido',
                text: 'La renovación no puede extender el préstamo por más de 7 días',
                confirmButtonColor: '#f39c12'
            });
            this.resetFechaMaxima(inicio, fechaDevolucion);
        } else {
            fechaDevolucion.setCustomValidity('');
        }
    }

    /**
     * Resetear fecha a la máxima permitida (7 días)
     */
    resetFechaMaxima(fechaInicio, inputFechaDevolucion) {
        const maxDate = new Date(fechaInicio);
        maxDate.setDate(maxDate.getDate() + 7);
        inputFechaDevolucion.value = maxDate.toISOString().split('T')[0];
    }

    /**
     * Enviar renovación del préstamo
     */
    async enviarRenovacion() {
        const form = document.getElementById('formRenovacionPrestamo');
        if (!form) {
            console.error('No se encontró el formulario de renovación');
            return;
        }

        const data = {
            idprestamo: form.querySelector('input[name="idprestamo"]')?.value,
            motivo: form.querySelector('textarea[name="motivo"]')?.value || '',
            nueva_fecha_devolucion: form.querySelector('input[name="nueva_fecha_devolucion"]')?.value,
            nueva_fecha_prestamo: form.querySelector('input[name="nueva_fecha_prestamo"]')?.value
        };

        console.log('Datos del formulario:', data);

        if (!data.idprestamo) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró el ID del préstamo',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        const { diasExtension, mensajeExtension } = this.calcularExtension(
            data.nueva_fecha_prestamo,
            data.nueva_fecha_devolucion
        );

        await this.confirmarYEnviarRenovacion(data, mensajeExtension);
    }

    /**
     * Calcular extensión de días
     */
    calcularExtension(fechaInicio, fechaDevolucion) {
        let diasExtension = 7;
        let mensajeExtension = 'El préstamo se extenderá por 7 días más';

        if (fechaInicio && fechaDevolucion) {
            const inicio = new Date(fechaInicio + 'T00:00:00');
            const fin = new Date(fechaDevolucion + 'T00:00:00');
            const diffTime = fin - inicio;
            diasExtension = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            mensajeExtension = diasExtension === 1
                ? 'El préstamo se extenderá por 1 día más'
                : `El préstamo se extenderá por ${diasExtension} días más`;
        }

        return { diasExtension, mensajeExtension };
    }

    /**
     * Confirmar y enviar renovación
     */
    async confirmarYEnviarRenovacion(data, mensajeExtension) {
        const urlRenovacion = `${this.baseUrl}/prestamo/solicitar-renovacion`;

        const result = await Swal.fire({
            title: '¿Enviar solicitud de renovación?',
            text: mensajeExtension + '. Tu solicitud será revisada por un administrador.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar solicitud',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const response = await fetch(urlRenovacion, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (!result.success) {
                        throw new Error(result.message || 'Error desconocido');
                    }

                    return result;
                } catch (error) {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed && result.value) {
            await Swal.fire({
                icon: 'info',
                title: '¡Solicitud Enviada!',
                html: `
                    <div class="text-start">
                        <p class="mb-2"><strong>Tu solicitud de renovación ha sido enviada exitosamente.</strong></p>
                        <p class="mb-2">Un administrador o docente revisará tu solicitud pronto.</p>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Recibirás una notificación cuando tu solicitud sea procesada.</small>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#28a745'
            });
            window.location.reload();
        }
    }
}

// Crear instancia global
const prestamoManager = new PrestamoManager();

// Funciones globales para mantener compatibilidad con HTML inline
function cargarDetallesPrestamo(idPrestamo) {
    prestamoManager.cargarDetalles(idPrestamo);
}

function renovarPrestamo(idPrestamo) {
    prestamoManager.renovar(idPrestamo);
}

function devolverPrestamo(idPrestamo) {
    prestamoManager.devolver(idPrestamo);
}

function validarFechaDevolucion() {
    prestamoManager.validarFechaDevolucion();
}

function enviarRenovacionPrestamo() {
    prestamoManager.enviarRenovacion();
}
