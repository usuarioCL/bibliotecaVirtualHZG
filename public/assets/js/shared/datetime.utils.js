/**
 * Utilidades de Fecha y Hora para el Sistema de Biblioteca
 * Funciones reutilizables para formateo, validación y cálculos de fechas/horas
 */

window.DateTimeUtils = window.DateTimeUtils || {
    /**
     * Formatea una fecha en formato dd/mm/yyyy
     * @param {string|Date} fecha - Fecha a formatear
     * @returns {string} Fecha formateada
     */
    formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        
        const date = new Date(fecha);
        if (isNaN(date.getTime())) return 'Fecha inválida';
        
        const dia = String(date.getDate()).padStart(2, '0');
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const anio = date.getFullYear();
        
        return `${dia}/${mes}/${anio}`;
    },

    /**
     * Formatea una fecha con hora en formato dd/mm/yyyy HH:mm
     * @param {string|Date} fecha - Fecha a formatear
     * @returns {string} Fecha y hora formateadas
     */
    formatearFechaHora(fecha) {
        if (!fecha) return 'N/A';
        
        const date = new Date(fecha);
        if (isNaN(date.getTime())) return 'Fecha inválida';
        
        const dia = String(date.getDate()).padStart(2, '0');
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const anio = date.getFullYear();
        const horas = String(date.getHours()).padStart(2, '0');
        const minutos = String(date.getMinutes()).padStart(2, '0');
        
        return `${dia}/${mes}/${anio} ${horas}:${minutos}`;
    },

    /**
     * Obtiene la fecha actual en formato ISO (YYYY-MM-DD)
     * @returns {string} Fecha actual en formato ISO
     */
    obtenerFechaActual() {
        return new Date().toISOString().split('T')[0];
    },

    /**
     * Obtiene la hora actual en formato HH:mm
     * @returns {string} Hora actual en formato HH:mm
     */
    obtenerHoraActual() {
        const ahora = new Date();
        const horas = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');
        return `${horas}:${minutos}`;
    },

    /**
     * Valida si una fecha es un día laboral (lunes a viernes)
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @returns {boolean} True si es día laboral
     */
    esDiaLaboral(fecha) {
        if (!fecha) return false;
        
        const fechaPartes = fecha.split('-');
        const fechaObj = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        const dia = fechaObj.getDay();
        
        return dia >= 1 && dia <= 5; // Lunes = 1, Viernes = 5
    },

    /**
     * Obtiene el próximo día laboral a partir de una fecha
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @returns {string} Próximo día laboral en formato YYYY-MM-DD
     */
    obtenerProximoDiaLaboral(fecha) {
        const fechaPartes = fecha.split('-');
        const fechaObj = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        const dia = fechaObj.getDay();
        
        let diasASumar = 0;
        
        if (dia === 0) { // Domingo
            diasASumar = 1;
        } else if (dia === 6) { // Sábado
            diasASumar = 2;
        } else {
            return fecha; // Ya es día laboral
        }
        
        fechaObj.setDate(fechaObj.getDate() + diasASumar);
        return fechaObj.toISOString().split('T')[0];
    },

    /**
     * Valida si una fecha es hoy o futura
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @returns {boolean} True si es hoy o futura
     */
    esFechaValidaParaPrestamo(fecha) {
        if (!fecha) return false;
        
        const fechaPartes = fecha.split('-');
        const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        const hoy = new Date();
        
        // Resetear horas para comparar solo fechas
        fechaSeleccionada.setHours(0, 0, 0, 0);
        hoy.setHours(0, 0, 0, 0);
        
        return fechaSeleccionada >= hoy;
    },

    /**
     * Calcula los días transcurridos entre dos fechas
     * @param {string|Date} fechaInicio - Fecha inicial
     * @param {string|Date} fechaFin - Fecha final
     * @returns {number} Número de días (puede incluir decimales)
     */
    calcularDiasTranscurridos(fechaInicio, fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        if (isNaN(inicio.getTime()) || isNaN(fin.getTime())) return 0;
        
        const diferencia = fin - inicio;
        return diferencia / (1000 * 60 * 60 * 24);
    },

    /**
     * Calcula los días restantes considerando horas
     * @param {string|Date} fechaVencimiento - Fecha de vencimiento
     * @returns {number} Días restantes (puede ser negativo si está vencido)
     */
    calcularDiasRestantes(fechaVencimiento) {
        const ahora = new Date();
        const vencimiento = new Date(fechaVencimiento);
        
        if (isNaN(vencimiento.getTime())) return 0;
        
        const diferencia = vencimiento - ahora;
        return diferencia / (1000 * 60 * 60 * 24);
    },

    /**
     * Formatea días y horas restantes de manera legible
     * @param {number} diasDecimal - Días en formato decimal
     * @returns {string} Texto formateado (ej: "2 días" o "5 horas")
     */
    formatearDiasRestantes(diasDecimal) {
        const diasAbs = Math.abs(diasDecimal);
        const dias = Math.floor(diasAbs);
        const horas = Math.round((diasAbs - dias) * 24);
        
        if (diasAbs >= 1) {
            return `${dias} día${dias !== 1 ? 's' : ''}`;
        } else {
            return `${horas} hora${horas !== 1 ? 's' : ''}`;
        }
    },

    /**
     * Formatea días y horas restantes con contexto de retraso
     * @param {number} diasDecimal - Días en formato decimal
     * @returns {string} Texto formateado con contexto
     */
    formatearTiempoConContexto(diasDecimal) {
        const texto = this.formatearDiasRestantes(diasDecimal);
        
        if (diasDecimal >= 0) {
            return `${texto} restantes`;
        } else {
            return `${texto} de retraso`;
        }
    },

    /**
     * Convierte hora en formato HH:mm a minutos desde medianoche
     * @param {string} hora - Hora en formato HH:mm
     * @returns {number} Minutos desde medianoche
     */
    horaAMinutos(hora) {
        if (!hora) return 0;
        
        const [horas, minutos] = hora.split(':').map(Number);
        return horas * 60 + minutos;
    },

    /**
     * Convierte minutos desde medianoche a formato HH:mm
     * @param {number} minutos - Minutos desde medianoche
     * @returns {string} Hora en formato HH:mm
     */
    minutosAHora(minutos) {
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        return `${String(horas).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
    },

    /**
     * Calcula la duración entre dos horas
     * @param {string} horaInicio - Hora inicio en formato HH:mm
     * @param {string} horaFin - Hora fin en formato HH:mm
     * @returns {number} Duración en minutos
     */
    calcularDuracionMinutos(horaInicio, horaFin) {
        if (!horaInicio || !horaFin) return 0;
        
        const inicioMinutos = this.horaAMinutos(horaInicio);
        const finMinutos = this.horaAMinutos(horaFin);
        
        return finMinutos - inicioMinutos;
    },

    /**
     * Formatea duración en minutos a texto legible
     * @param {number} minutos - Duración en minutos
     * @returns {string} Texto formateado (ej: "2 horas y 30 minutos")
     */
    formatearDuracion(minutos) {
        if (minutos <= 0) return '0 minutos';
        
        const horas = Math.floor(minutos / 60);
        const mins = minutos % 60;
        
        if (horas === 0) {
            return `${mins} minuto${mins !== 1 ? 's' : ''}`;
        } else if (mins === 0) {
            return `${horas} hora${horas !== 1 ? 's' : ''}`;
        } else {
            return `${horas} hora${horas !== 1 ? 's' : ''} y ${mins} minuto${mins !== 1 ? 's' : ''}`;
        }
    },

    /**
     * Valida si una hora está dentro del rango permitido
     * @param {string} hora - Hora en formato HH:mm
     * @param {number} minMinutos - Mínimo en minutos desde medianoche
     * @param {number} maxMinutos - Máximo en minutos desde medianoche
     * @returns {boolean} True si está en el rango
     */
    horaEnRango(hora, minMinutos, maxMinutos) {
        const horaMinutos = this.horaAMinutos(hora);
        return horaMinutos >= minMinutos && horaMinutos <= maxMinutos;
    },

    /**
     * Ajusta una hora al rango permitido más cercano
     * @param {string} hora - Hora en formato HH:mm
     * @param {number} minMinutos - Mínimo en minutos
     * @param {number} maxMinutos - Máximo en minutos
     * @returns {string} Hora ajustada en formato HH:mm
     */
    ajustarHoraARango(hora, minMinutos, maxMinutos) {
        let horaMinutos = this.horaAMinutos(hora);
        
        if (horaMinutos < minMinutos) {
            horaMinutos = minMinutos;
        } else if (horaMinutos > maxMinutos) {
            horaMinutos = maxMinutos;
        }
        
        return this.minutosAHora(horaMinutos);
    },

    /**
     * Combina fecha y hora en formato YYYY-MM-DD HH:mm:ss
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @param {string} hora - Hora en formato HH:mm
     * @returns {string} Fecha y hora combinadas
     */
    combinarFechaHora(fecha, hora) {
        if (!fecha || !hora) return null;
        return `${fecha} ${hora}:00`;
    },

    /**
     * Obtiene el nombre del día de la semana
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @returns {string} Nombre del día
     */
    obtenerNombreDia(fecha) {
        const nombres = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const fechaPartes = fecha.split('-');
        const fechaObj = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        return nombres[fechaObj.getDay()];
    },

    /**
     * Valida si dos horarios se superponen
     * @param {string} inicio1 - Hora inicio del primer intervalo
     * @param {string} fin1 - Hora fin del primer intervalo
     * @param {string} inicio2 - Hora inicio del segundo intervalo
     * @param {string} fin2 - Hora fin del segundo intervalo
     * @returns {boolean} True si se superponen
     */
    horariosSeSuperponen(inicio1, fin1, inicio2, fin2) {
        const inicio1Min = this.horaAMinutos(inicio1);
        const fin1Min = this.horaAMinutos(fin1);
        const inicio2Min = this.horaAMinutos(inicio2);
        const fin2Min = this.horaAMinutos(fin2);
        
        return !(fin1Min <= inicio2Min || fin2Min <= inicio1Min);
    },

    /**
     * Logger con timestamp
     * @param {string} mensaje - Mensaje a loggear
     * @param {*} data - Datos adicionales
     */
    log(mensaje, data = null) {
        const timestamp = new Date().toISOString();
        console.log(`[${timestamp}] DateTimeUtils: ${mensaje}`, data || '');
    }
};

// Hacer disponible globalmente
if (typeof window !== 'undefined') {
    window.DateTimeUtils = DateTimeUtils;
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DateTimeUtils;
}
