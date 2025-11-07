/**
 * Módulo para gestión del formulario de préstamos
 * Maneja la solicitud de préstamos de recursos físicos usando SweetAlert2
 */
class PrestamoForm {
    constructor() {
        this.currentRecursoId = null;
        this.setupGlobalFunctions();
    }
    
    /**
     * Configura funciones globales para el formulario
     */
    setupGlobalFunctions() {
        window.cambiarCantidad = (delta) => this.cambiarCantidad(delta);
        window.enviarSolicitudPrestamo = () => this.enviarSolicitud();
    }
    
    /**
     * Abre el modal del formulario de préstamo
     * @param {number} recursoId ID del recurso a solicitar
     */
    async open(recursoId) {
        this.currentRecursoId = recursoId;
        
        // Cerrar el modal de detalles del libro si está abierto
        const libroModal = document.getElementById('libroModal');
        if (libroModal) {
            const modalInstance = bootstrap.Modal.getInstance(libroModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
        
        try {
            // Verificar sanciones del usuario
            const sancionData = await this.verificarSanciones();
            
            if (sancionData.sancionado) {
                this.showSancionMessage(sancionData.sanciones);
            } else {
                // Cargar formulario
                await this.loadForm();
            }
        } catch (error) {
            console.error('Error al abrir formulario de préstamo:', error);
            this.showError('Error al cargar el formulario. Por favor intenta nuevamente.');
        }
    }
    
    /**
     * Verifica si el usuario tiene sanciones activas
     * @returns {Promise<object>}
     */
    async verificarSanciones() {
        try {
            const response = await fetch(window.APP_CONFIG.routes.verificarSanciones, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            return {
                sancionado: data.success && data.sancionado,
                sanciones: data.sanciones || []
            };
        } catch (error) {
            console.error('Error al verificar sanciones:', error);
            return { sancionado: false, sanciones: [] };
        }
    }
    
    /**
     * Carga el formulario del servidor usando SweetAlert2
     */
    async loadForm() {
        try {
            const response = await fetch(
                `${window.APP_CONFIG.routes.formularioPrestamo}${this.currentRecursoId}`
            );
            
            if (!response.ok) {
                throw new Error('Error al cargar el formulario');
            }
            
            const html = await response.text();
            
            // Mostrar el formulario en SweetAlert2
            Swal.fire({
                title: 'Solicitud de Préstamo',
                html: html,
                width: '600px',
                showConfirmButton: false,
                showCloseButton: true,
                didOpen: () => {
                    // Inicializar el formulario después de que el SweetAlert2 se abre
                    this.initFormEvents();
                }
            });
            
        } catch (error) {
            console.error('Error al cargar formulario:', error);
            throw error;
        }
    }
    
    /**
     * Inicializa los eventos del formulario
     */
    initFormEvents() {
        setTimeout(() => {
            const fechaInicio = document.getElementById('fechaInicio');
            const fechaEntrega = document.getElementById('fechaEntrega');
            const cantidadInput = document.getElementById('cantidadLibros');
            
            if (fechaInicio) {
                fechaInicio.addEventListener('change', () => {
                    this.actualizarFechaEntrega();
                    this.validarFechas();
                });
            }
            
            if (fechaEntrega) {
                fechaEntrega.addEventListener('change', () => {
                    this.actualizarDuracion();
                    this.validarFechas();
                });
            }
            
            if (cantidadInput) {
                cantidadInput.addEventListener('input', () => this.actualizarResumenCantidad());
            }
            
            // Inicializar fechas y valores al cargar
            if (fechaInicio && fechaInicio.value) {
                this.actualizarFechaEntrega();
            }
            this.actualizarDuracion();
            this.actualizarResumenCantidad();
        }, 100);
    }
    
    /**
     * Cambia la cantidad de libros
     */
    cambiarCantidad(delta) {
        const input = document.getElementById('cantidadLibros');
        if (!input) return;
        
        const valorActual = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 1;
        const nuevoValor = Math.max(1, Math.min(max, valorActual + delta));
        
        input.value = nuevoValor;
        this.actualizarResumenCantidad();
    }
    
    /**
     * Actualiza el resumen de cantidad
     */
    actualizarResumenCantidad() {
        const cantidadInput = document.getElementById('cantidadLibros');
        const resumenCantidad = document.getElementById('resumenCantidad');
        
        if (cantidadInput && resumenCantidad) {
            const cantidad = parseInt(cantidadInput.value) || 1;
            resumenCantidad.textContent = `${cantidad} libro${cantidad !== 1 ? 's' : ''}`;
        }
    }
    
    /**
     * Actualiza la fecha de entrega automáticamente
     */
    actualizarFechaEntrega() {
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaEntrega = document.getElementById('fechaEntrega');
        
        if (fechaInicio && fechaEntrega && fechaInicio.value) {
            const inicio = new Date(fechaInicio.value);
            inicio.setDate(inicio.getDate() + 7);
            
            const year = inicio.getFullYear();
            const month = String(inicio.getMonth() + 1).padStart(2, '0');
            const day = String(inicio.getDate()).padStart(2, '0');
            
            fechaEntrega.value = `${year}-${month}-${day}`;
            this.actualizarDuracion();
        }
    }
    
    /**
     * Actualiza la duración del préstamo
     */
    actualizarDuracion() {
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaEntrega = document.getElementById('fechaEntrega');
        const duracionSpan = document.getElementById('duracionPrestamo');
        
        if (fechaInicio && fechaEntrega && fechaInicio.value && fechaEntrega.value) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(fechaEntrega.value);
            
            const diffTime = Math.abs(fin - inicio);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (duracionSpan) {
                duracionSpan.textContent = `${diffDays} día${diffDays !== 1 ? 's' : ''}`;
            }
        }
    }
    
    /**
     * Valida las fechas del préstamo
     */
    validarFechas() {
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaEntrega = document.getElementById('fechaEntrega');
        
        if (!fechaInicio || !fechaEntrega) return true;
        
        // Si no hay valores, no validar aún
        if (!fechaInicio.value || !fechaEntrega.value) return true;
        
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        // Agregar 'T00:00:00' para evitar problemas de zona horaria
        const inicio = new Date(fechaInicio.value + 'T00:00:00');
        const fin = new Date(fechaEntrega.value + 'T00:00:00');
        
        // Validar que la fecha de inicio no sea anterior a hoy
        if (inicio < hoy) {
            fechaInicio.setCustomValidity('La fecha de inicio no puede ser anterior a hoy');
            return false;
        } else {
            fechaInicio.setCustomValidity('');
        }
        
        // Validar que la fecha de entrega sea posterior a la fecha de inicio
        if (fin <= inicio) {
            fechaEntrega.setCustomValidity('La fecha de entrega debe ser posterior a la fecha de inicio');
            return false;
        } else {
            fechaEntrega.setCustomValidity('');
        }
        
        // Calcular diferencia de días
        const diffTime = fin - inicio;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // Validar que no exceda 7 días
        if (diffDays > 7) {
            fechaEntrega.setCustomValidity('El préstamo no puede exceder 7 días');
            return false;
        } else {
            fechaEntrega.setCustomValidity('');
        }
        
        return true;
    }
    
    /**
     * Envía la solicitud de préstamo
     */
    enviarSolicitud() {
        const form = document.getElementById('formSolicitudPrestamo');
        
        if (!form) {
            console.error('Formulario no encontrado');
            return;
        }
        
        if (!this.validarFechas()) {
            form.classList.add('was-validated');
            Swal.fire({
                title: 'Error de validación',
                text: 'Por favor, verifique las fechas del préstamo.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            Swal.fire({
                title: 'Formulario incompleto',
                text: 'Por favor, complete todos los campos requeridos.',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        Swal.fire({
            title: '¿Confirmar solicitud?',
            text: 'Se enviará su solicitud de préstamo.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, solicitar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const formData = new FormData(form);
                
                return fetch(window.APP_CONFIG.routes.solicitarPrestamo, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                if (result.value.success) {
                    Swal.fire({
                        title: '¡Solicitud enviada!',
                        text: result.value.message || 'Su solicitud de préstamo ha sido registrada exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        if (result.value.reload) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: result.value.message || 'No se pudo procesar la solicitud.',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    });
                }
            }
        });
    }
    
    /**
     * Muestra mensaje de sanción usando SweetAlert2
     */
    showSancionMessage(sanciones) {
        let sancionesHtml = '<div class="alert alert-danger"><strong>Sanciones activas:</strong><ul class="mb-0 mt-2">';
        sanciones.forEach(sancion => {
            sancionesHtml += `<li><strong>${sancion.tipo}:</strong> ${sancion.detalle}`;
            if (sancion.fecha_vencimiento) {
                const fechaVenc = new Date(sancion.fecha_vencimiento);
                sancionesHtml += `<br><small>Vence: ${fechaVenc.toLocaleDateString('es-ES')}</small>`;
            }
            sancionesHtml += '</li>';
        });
        sancionesHtml += '</ul></div>';
        
        Swal.fire({
            title: 'No puede solicitar préstamos',
            html: sancionesHtml + '<p class="mt-3">Usted tiene sanciones activas y no puede solicitar préstamos hasta que se resuelvan.</p>',
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
        });
    }
    
    /**
     * Muestra un mensaje de error usando SweetAlert2
     * @param {string} message Mensaje de error
     */
    showError(message) {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonText: 'Cerrar'
        });
    }
    
    /**
     * Muestra mensaje de éxito usando SweetAlert2
     * @param {string} message Mensaje de éxito
     */
    showSuccess(message) {
        Swal.fire({
            title: '¡Éxito!',
            text: message,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    }
}

// Exportar como global
window.PrestamoForm = PrestamoForm;
