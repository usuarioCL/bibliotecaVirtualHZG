/**
 * Sistema de Notificaciones
 * Maneja la campanita de notificaciones en el navbar
 */

let intervaloNotificaciones = null;

/**
 * Inicializar el sistema de notificaciones
 */
function inicializarNotificaciones() {
    // Cargar notificaciones al iniciar
    cargarNotificaciones();
    
    // Actualizar contador cada 30 segundos
    intervaloNotificaciones = setInterval(actualizarContador, 30000);
    
    // Event listeners
    document.getElementById('btn-marcar-todas-leidas')?.addEventListener('click', marcarTodasComoLeidas);
    
    // Recargar notificaciones al abrir el dropdown
    document.getElementById('notificacionesDropdown')?.addEventListener('click', function(e) {
        if (!this.classList.contains('show')) {
            cargarNotificaciones();
        }
    });
    
    console.log('✅ Sistema de notificaciones inicializado');
}

/**
 * Actualizar solo el contador de notificaciones
 */
function actualizarContador() {
    fetch(base_url + '/notificaciones/contar', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            actualizarBadge(data.contador);
        }
    })
    .catch(error => {
        console.error('Error al actualizar contador:', error);
    });
}

/**
 * Cargar notificaciones completas
 */
function cargarNotificaciones() {
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    
    // Mostrar loading
    listaNotificaciones.innerHTML = `
        <div class="text-center text-muted py-4">
            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
            <p class="mb-0">Cargando notificaciones...</p>
        </div>
    `;
    
    fetch(base_url + '/notificaciones/obtener', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificaciones(data.notificaciones);
            actualizarBadge(data.contador);
        } else {
            listaNotificaciones.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <p class="mb-0">Error al cargar notificaciones</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error al cargar notificaciones:', error);
        listaNotificaciones.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-times-circle fa-2x mb-2"></i>
                <p class="mb-0">Error de conexión</p>
            </div>
        `;
    });
}

/**
 * Mostrar lista de notificaciones
 */
function mostrarNotificaciones(notificaciones) {
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    
    if (!notificaciones || notificaciones.length === 0) {
        listaNotificaciones.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-3" style="color: #28a745;"></i>
                <p class="mb-0">No tienes notificaciones</p>
                <small class="text-muted">¡Estás al día!</small>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    notificaciones.forEach(notif => {
        const iconoTipo = obtenerIconoTipo(notif.tipo);
        const colorTipo = obtenerColorTipo(notif.tipo);
        const noLeida = notif.leida == 0 ? 'bg-light' : '';
        const fechaFormato = formatearFechaRelativa(notif.created_at);
        
        html += `
            <div class="dropdown-item notificacion-item ${noLeida}" 
                 data-id="${notif.idnotificacion}" 
                 data-leida="${notif.leida}"
                 style="cursor: pointer; border-bottom: 1px solid #eee; padding: 0.75rem 1rem;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="${iconoTipo}" style="color: ${colorTipo}; font-size: 1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600;">
                            ${notif.titulo}
                            ${notif.leida == 0 ? '<span class="badge bg-primary ms-2" style="font-size: 0.65rem;">NUEVA</span>' : ''}
                        </h6>
                        <p class="mb-1 text-muted" style="font-size: 0.85rem;">
                            ${notif.mensaje}
                        </p>
                        ${notif.recurso_titulo ? `<small class="text-info"><i class="fas fa-book me-1"></i>${notif.recurso_titulo}</small><br>` : ''}
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>${fechaFormato}
                        </small>
                    </div>
                </div>
            </div>
        `;
    });
    
    listaNotificaciones.innerHTML = html;
    
    // Agregar event listeners a cada notificación
    document.querySelectorAll('.notificacion-item').forEach(item => {
        item.addEventListener('click', function() {
            const idNotificacion = this.dataset.id;
            const leida = this.dataset.leida;
            
            if (leida == 0) {
                marcarComoLeida(idNotificacion);
            }
        });
    });
}

/**
 * Obtener icono según tipo de notificación
 */
function obtenerIconoTipo(tipo) {
    const iconos = {
        'aprobacion': 'fas fa-check-circle',
        'rechazo': 'fas fa-times-circle',
        'vencimiento': 'fas fa-exclamation-triangle',
        'renovacion': 'fas fa-sync-alt',
        'devolucion': 'fas fa-undo',
        'sancion': 'fas fa-shield-alt' // CAMBIO 2025-10-28: Agregado icono para notificaciones de sanciones
    };
    return iconos[tipo] || 'fas fa-bell';
}

/**
 * Obtener color según tipo de notificación
 */
function obtenerColorTipo(tipo) {
    const colores = {
        'aprobacion': '#28a745',
        'rechazo': '#dc3545',
        'vencimiento': '#ffc107',
        'renovacion': '#17a2b8',
        'devolucion': '#6c757d',
        'sancion': '#dc3545' // CAMBIO 2025-10-28: Agregado color rojo para notificaciones de sanciones
    };
    return colores[tipo] || '#007bff';
}

/**
 * Formatear fecha de forma relativa
 */
function formatearFechaRelativa(fecha) {
    const ahora = new Date();
    const fechaNotif = new Date(fecha);
    const diferencia = Math.floor((ahora - fechaNotif) / 1000); // segundos
    
    if (diferencia < 60) {
        return 'Hace unos segundos';
    } else if (diferencia < 3600) {
        const minutos = Math.floor(diferencia / 60);
        return `Hace ${minutos} minuto${minutos > 1 ? 's' : ''}`;
    } else if (diferencia < 86400) {
        const horas = Math.floor(diferencia / 3600);
        return `Hace ${horas} hora${horas > 1 ? 's' : ''}`;
    } else if (diferencia < 604800) {
        const dias = Math.floor(diferencia / 86400);
        return `Hace ${dias} día${dias > 1 ? 's' : ''}`;
    } else {
        return fechaNotif.toLocaleDateString('es-ES', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }
}

/**
 * Actualizar badge de notificaciones
 */
function actualizarBadge(contador) {
    const badge = document.getElementById('badge-notificaciones');
    
    if (badge) {
        if (contador > 0) {
            badge.textContent = contador > 99 ? '99+' : contador;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

/**
 * Marcar una notificación como leída
 */
function marcarComoLeida(idNotificacion) {
    fetch(base_url + '/notificaciones/marcar-leida', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `idnotificacion=${idNotificacion}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            actualizarBadge(data.contador);
            // Actualizar visualmente la notificación
            const notifElement = document.querySelector(`[data-id="${idNotificacion}"]`);
            if (notifElement) {
                notifElement.classList.remove('bg-light');
                notifElement.dataset.leida = '1';
                const badgeNueva = notifElement.querySelector('.badge.bg-primary');
                if (badgeNueva) {
                    badgeNueva.remove();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error al marcar como leída:', error);
    });
}

/**
 * Marcar todas las notificaciones como leídas
 */
function marcarTodasComoLeidas() {
    fetch(base_url + '/notificaciones/marcar-todas-leidas', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            actualizarBadge(0);
            cargarNotificaciones(); // Recargar lista
            
            // Mostrar mensaje de éxito
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Todas las notificaciones marcadas como leídas'
            });
        }
    })
    .catch(error => {
        console.error('Error al marcar todas como leídas:', error);
    });
}

/**
 * Detener el intervalo de actualización (llamar al salir de la página)
 */
function detenerNotificaciones() {
    if (intervaloNotificaciones) {
        clearInterval(intervaloNotificaciones);
        console.log('⏹️ Sistema de notificaciones detenido');
    }
}

// Inicializar automáticamente cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarNotificaciones);
} else {
    inicializarNotificaciones();
}

// Detener intervalo al salir de la página
window.addEventListener('beforeunload', detenerNotificaciones);

