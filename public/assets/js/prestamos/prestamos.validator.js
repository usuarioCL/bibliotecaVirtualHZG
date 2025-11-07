/**
 * Módulo de Validaciones para Préstamos
 * Centraliza todas las validaciones de formularios de préstamos
 * Depende de: DateTimeUtils, PrestamosConstants
 */

window.PrestamosValidator = window.PrestamosValidator || {
    /**
     * Valida una fecha de préstamo
     * @param {HTMLElement} input - Input de fecha
     * @param {boolean} autoCorregir - Si debe auto-corregir errores
     * @returns {boolean} True si es válida
     */
    validarFecha(input, autoCorregir = true) {
        const fechaValor = input.value;
        const feedback = this._obtenerFeedback(input);
        
        // Validar campo requerido
        if (!fechaValor) {
            return this._mostrarError(
                input, 
                feedback, 
                PrestamosConstants.MENSAJES.VALIDACION.FECHA_REQUERIDA
            );
        }
        
        // Validar día laboral
        if (!DateTimeUtils.esDiaLaboral(fechaValor)) {
            this._mostrarError(
                input,
                feedback,
                PrestamosConstants.MENSAJES.VALIDACION.DIA_LABORAL
            );
            
            // Auto-corrección
            if (autoCorregir) {
                setTimeout(() => {
                    const proximoDiaLaboral = DateTimeUtils.obtenerProximoDiaLaboral(fechaValor);
                    input.value = proximoDiaLaboral;
                    this._limpiarError(input, feedback);
                }, PrestamosConstants.AUTO_CORRECCION.DELAY);
            }
            
            return false;
        }
        
        // Validar que no sea fecha pasada
        if (!DateTimeUtils.esFechaValidaParaPrestamo(fechaValor)) {
            return this._mostrarError(
                input,
                feedback,
                PrestamosConstants.MENSAJES.VALIDACION.FECHA_PASADA
            );
        }
        
        // Todo correcto
        this._limpiarError(input, feedback);
        return true;
    },

    /**
     * Valida una hora de préstamo (inicio o fin)
     * @param {HTMLElement} input - Input de hora
     * @param {string} tipo - 'inicio' o 'fin'
     * @param {boolean} autoCorregir - Si debe auto-corregir errores
     * @returns {boolean} True si es válida
     */
    validarHora(input, tipo, autoCorregir = true) {
        const horaValor = input.value;
        const feedback = this._obtenerFeedback(input);
        const CONST = PrestamosConstants.HORARIOS;
        
        // Validar campo requerido
        if (!horaValor) {
            return this._mostrarError(
                input,
                feedback,
                PrestamosConstants.MENSAJES.VALIDACION.HORA_REQUERIDA
            );
        }
        
        const horaMinutos = DateTimeUtils.horaAMinutos(horaValor);
        
        if (tipo === 'inicio') {
            // Validar rango de hora de inicio (8:00 AM - 12:59 PM)
            if (horaMinutos < CONST.HORA_MIN_MINUTOS || horaMinutos >= CONST.HORA_MAX_MINUTOS) {
                this._mostrarError(
                    input,
                    feedback,
                    PrestamosConstants.MENSAJES.VALIDACION.HORA_INICIO_RANGO
                );
                
                // Auto-corrección
                if (autoCorregir) {
                    setTimeout(() => {
                        input.value = CONST.HORA_MIN_FORMATO;
                        this._limpiarError(input, feedback);
                    }, PrestamosConstants.AUTO_CORRECCION.DELAY);
                }
                
                return false;
            }
        } else if (tipo === 'fin') {
            // Validar rango de hora de fin (8:01 AM - 1:00 PM)
            if (horaMinutos <= CONST.HORA_MIN_MINUTOS || horaMinutos > CONST.HORA_MAX_MINUTOS) {
                this._mostrarError(
                    input,
                    feedback,
                    PrestamosConstants.MENSAJES.VALIDACION.HORA_FIN_RANGO
                );
                
                // Auto-corrección
                if (autoCorregir) {
                    setTimeout(() => {
                        input.value = CONST.HORA_MAX_FORMATO;
                        this._limpiarError(input, feedback);
                    }, PrestamosConstants.AUTO_CORRECCION.DELAY);
                }
                
                return false;
            }
            
            // Validar que hora fin sea posterior a hora inicio
            const horaInicioInput = document.getElementById('hora_inicio_prestamo') || 
                                   document.getElementById('nueva_hora_inicio');
            
            if (horaInicioInput && horaInicioInput.value) {
                const inicioMinutos = DateTimeUtils.horaAMinutos(horaInicioInput.value);
                
                if (horaMinutos <= inicioMinutos) {
                    this._mostrarError(
                        input,
                        feedback,
                        PrestamosConstants.MENSAJES.VALIDACION.HORA_FIN_POSTERIOR
                    );
                    
                    // Auto-corrección
                    if (autoCorregir) {
                        setTimeout(() => {
                            const nuevaHoraMinutos = Math.min(
                                inicioMinutos + PrestamosConstants.AUTO_CORRECCION.EXTENSION_DEFAULT,
                                CONST.HORA_MAX_MINUTOS
                            );
                            input.value = DateTimeUtils.minutosAHora(nuevaHoraMinutos);
                            this._limpiarError(input, feedback);
                            
                            // Actualizar duración si existe
                            this._actualizarDuracion();
                        }, PrestamosConstants.AUTO_CORRECCION.DELAY);
                    }
                    
                    return false;
                }
            }
        }
        
        // Todo correcto
        this._limpiarError(input, feedback);
        return true;
    },

    /**
     * Valida el formulario completo de préstamo
     * @param {object} opciones - Opciones de validación
     * @returns {boolean} True si todo es válido
     */
    validarFormularioPrestamo(opciones = {}) {
        const {
            esValidacionFinal = false,
            autoCorregir = !esValidacionFinal
        } = opciones;
        
        let hasErrors = false;
        
        // Obtener elementos del formulario
        const fechaInput = document.getElementById('fecha_prestamo');
        const horaInicioInput = document.getElementById('hora_inicio_prestamo');
        const horaFinInput = document.getElementById('hora_fin_prestamo');
        
        // Limpiar errores anteriores si es validación final
        if (esValidacionFinal) {
            [fechaInput, horaInicioInput, horaFinInput].forEach(el => {
                if (el) {
                    el.classList.remove('is-invalid');
                    const feedback = this._obtenerFeedback(el);
                    if (feedback) feedback.style.display = 'none';
                }
            });
        }
        
        // Validar fecha
        if (fechaInput && !this.validarFecha(fechaInput, autoCorregir)) {
            hasErrors = true;
        }
        
        // Validar hora de inicio
        if (horaInicioInput && !this.validarHora(horaInicioInput, 'inicio', autoCorregir)) {
            hasErrors = true;
        }
        
        // Validar hora de fin
        if (horaFinInput && !this.validarHora(horaFinInput, 'fin', autoCorregir)) {
            hasErrors = true;
        }
        
        return !hasErrors;
    },

    /**
     * Valida el formulario de renovación
     * @param {object} opciones - Opciones de validación
     * @returns {boolean} True si todo es válido
     */
    validarFormularioRenovacion(opciones = {}) {
        const {
            esValidacionFinal = false,
            autoCorregir = !esValidacionFinal
        } = opciones;
        
        let hasErrors = false;
        
        // Obtener elementos del formulario
        const fechaInput = document.getElementById('nueva_fecha_devolucion');
        const horaInicioInput = document.getElementById('nueva_hora_inicio');
        const horaFinInput = document.getElementById('nueva_hora_devolucion');
        
        // Limpiar errores anteriores
        if (esValidacionFinal) {
            [fechaInput, horaInicioInput, horaFinInput].forEach(el => {
                if (el) {
                    el.classList.remove('is-invalid');
                    const feedback = this._obtenerFeedback(el);
                    if (feedback) feedback.style.display = 'none';
                }
            });
        }
        
        // Validar fecha
        if (fechaInput && !this.validarFecha(fechaInput, autoCorregir)) {
            hasErrors = true;
        }
        
        // Validar hora de inicio
        if (horaInicioInput && !this.validarHora(horaInicioInput, 'inicio', autoCorregir)) {
            hasErrors = true;
        }
        
        // Validar hora de fin
        if (horaFinInput && !this.validarHora(horaFinInput, 'fin', autoCorregir)) {
            hasErrors = true;
        }
        
        return !hasErrors;
    },

    /**
     * Valida selección de usuario
     * @param {string} idusuario - ID del usuario seleccionado
     * @returns {boolean} True si es válido
     */
    validarUsuarioSeleccionado(idusuario) {
        return idusuario && idusuario.trim() !== '';
    },

    /**
     * Valida selección de recurso
     * @param {string} idejemplar - ID del ejemplar seleccionado
     * @returns {boolean} True si es válido
     */
    validarRecursoSeleccionado(idejemplar) {
        return idejemplar && idejemplar.trim() !== '';
    },

    /**
     * Valida campos de devolución con incidencia
     * @param {string} tipoSancion - ID del tipo de sanción
     * @param {string} detalleIncidencia - Detalle específico
     * @param {boolean} detalleVisible - Si el campo detalle está visible
     * @returns {object} Objeto con resultado y mensaje
     */
    validarDevolucionConIncidencia(tipoSancion, detalleIncidencia, detalleVisible) {
        if (!tipoSancion) {
            return {
                valido: false,
                mensaje: PrestamosConstants.MENSAJES.VALIDACION.TIPO_SANCION_REQUERIDO
            };
        }
        
        if (detalleVisible && !detalleIncidencia) {
            return {
                valido: false,
                mensaje: PrestamosConstants.MENSAJES.VALIDACION.DETALLE_INCIDENCIA_REQUERIDO
            };
        }
        
        return { valido: true };
    },

    /**
     * MÉTODOS PRIVADOS
     */

    /**
     * Obtiene el elemento de feedback de un input
     * @private
     */
    _obtenerFeedback(input) {
        if (!input) return null;
        
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = input.parentElement?.querySelector('.invalid-feedback');
        }
        return feedback;
    },

    /**
     * Muestra un mensaje de error en un input
     * @private
     */
    _mostrarError(input, feedback, mensaje) {
        if (input) {
            input.classList.add('is-invalid');
        }
        
        if (feedback) {
            feedback.textContent = mensaje;
            feedback.style.display = 'block';
        }
        
        return false;
    },

    /**
     * Limpia el error de un input
     * @private
     */
    _limpiarError(input, feedback) {
        if (input) {
            input.classList.remove('is-invalid');
        }
        
        if (feedback) {
            feedback.style.display = 'none';
        }
    },

    /**
     * Actualiza la duración mostrada
     * @private
     */
    _actualizarDuracion() {
        const duracionElement = document.getElementById('duracion_prestamo') || 
                               document.getElementById('duracion_renovacion');
        
        if (!duracionElement) return;
        
        const horaInicioInput = document.getElementById('hora_inicio_prestamo') || 
                               document.getElementById('nueva_hora_inicio');
        const horaFinInput = document.getElementById('hora_fin_prestamo') || 
                            document.getElementById('nueva_hora_devolucion');
        
        if (horaInicioInput && horaFinInput && horaInicioInput.value && horaFinInput.value) {
            const duracionMinutos = DateTimeUtils.calcularDuracionMinutos(
                horaInicioInput.value,
                horaFinInput.value
            );
            duracionElement.textContent = DateTimeUtils.formatearDuracion(duracionMinutos);
        }
    },

    /**
     * Configura listeners de validación en tiempo real
     * @param {object} elementos - Objeto con referencias a elementos del DOM
     */
    configurarValidacionTiempoReal(elementos) {
        const { fechaInput, horaInicioInput, horaFinInput, duracionElement } = elementos;
        
        // Validar fecha al cambiar
        if (fechaInput) {
            fechaInput.addEventListener('change', () => {
                this.validarFecha(fechaInput, true);
            });
            
            fechaInput.addEventListener('input', () => {
                if (fechaInput.value) {
                    this.validarFecha(fechaInput, true);
                }
            });
        }
        
        // Validar hora de inicio
        if (horaInicioInput) {
            horaInicioInput.addEventListener('change', () => {
                this.validarHora(horaInicioInput, 'inicio', true);
                this._actualizarDuracion();
            });
            
            horaInicioInput.addEventListener('input', () => {
                if (horaInicioInput.value) {
                    this.validarHora(horaInicioInput, 'inicio', true);
                    this._actualizarDuracion();
                }
            });
        }
        
        // Validar hora de fin
        if (horaFinInput) {
            horaFinInput.addEventListener('change', () => {
                this.validarHora(horaFinInput, 'fin', true);
                this._actualizarDuracion();
            });
            
            horaFinInput.addEventListener('input', () => {
                if (horaFinInput.value) {
                    this.validarHora(horaFinInput, 'fin', true);
                    this._actualizarDuracion();
                }
            });
        }
        
        // Calcular duración inicial
        if (duracionElement) {
            setTimeout(() => {
                this._actualizarDuracion();
            }, 100);
        }
    }
};

// Hacer disponible globalmente
if (typeof window !== 'undefined') {
    window.PrestamosValidator = PrestamosValidator;
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PrestamosValidator;
}
