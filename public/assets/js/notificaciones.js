// Sistema de Notificaciones - Maneja la campanita del navbar
// Actualización automática cada 30 segundos

let intervaloNotificaciones = null;

function inicializarNotificaciones() {
    // Cargar notificaciones al iniciar
    cargarNotificaciones();
    
    // Actualizar contador cada 30 segundos
    intervaloNotificaciones = setInterval(actualizarContador, 30000);
    
    // Recargar notificaciones al abrir el dropdown
    document.getElementById('notificacionesDropdown')?.addEventListener('click', function(e) {
        if (!this.classList.contains('show')) {
            cargarNotificaciones();
        }
    });
    
    console.log('✅ Sistema de notificaciones inicializado');
}

// Actualiza solo el número del badge sin recargar toda la lista
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

// Carga la lista completa de notificaciones desde el servidor
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

// Renderiza el HTML de las notificaciones en el dropdown
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
                 style="border-bottom: 1px solid #eee; padding: 0.75rem 1rem; position: relative;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="${iconoTipo}" style="color: ${colorTipo}; font-size: 1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1" style="cursor: pointer;">
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
                    <div class="ms-2">
                        <button class="btn btn-sm btn-outline-success" 
                                onclick="event.stopPropagation(); eliminarNotificacion(${notif.idnotificacion})"
                                title="Marcar como leída"
                                style="padding: 0.25rem 0.5rem;">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    listaNotificaciones.innerHTML = html;
}

// Retorna el ícono de FontAwesome según el tipo de notificación
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

// Retorna el color hexadecimal según el tipo de notificación
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

// Convierte timestamp a formato relativo ("Hace 5 minutos")
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

// Actualiza el badge rojo con el número de notificaciones no leídas
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

// CAMBIO 2025-10-30: Elimina una notificación individual (sin confirmación)

function eliminarNotificacion(idNotificacion) {
    fetch(base_url + '/notificaciones/eliminar', {
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
            cargarNotificaciones(); // Recargar lista (automáticamente mostrará "No tienes notificaciones" si está vacío)
            
            // Mostrar mensaje de éxito
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Notificación leída'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar la notificación'
            });
        }
    })
    .catch(error => {
        console.error('Error al eliminar notificación:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}

// CAMBIO 2025-10-30: Elimina TODAS las notificaciones del usuario
// Valida que haya notificaciones antes de mostrar confirmación
function eliminarTodas() {
    // Verificar si hay notificaciones en la lista
    const listaNotificaciones = document.getElementById('lista-notificaciones');
    const notificacionesItems = listaNotificaciones.querySelectorAll('.notificacion-item');
    
    if (notificacionesItems.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Sin notificaciones',
            text: 'No hay notificaciones pendientes',
            confirmButtonColor: '#17a2b8'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Marcar todas como leídas?',
        text: "Todas las notificaciones se eliminarán del buzón",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, marcar todas',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(base_url + '/notificaciones/eliminar-todas', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar badge a 0
                    actualizarBadge(0);
                    
                    // Mostrar mensaje de "No tienes notificaciones"
                    listaNotificaciones.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-3" style="color: #28a745;"></i>
                            <p class="mb-0">No tienes notificaciones</p>
                            <small class="text-muted">¡Estás al día!</small>
                        </div>
                    `;
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: 'Todas las notificaciones han sido leídas',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron marcar las notificaciones'
                    });
                }
            })
            .catch(error => {
                console.error('Error al eliminar notificaciones:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor'
                });
            });
        }
    });
}

// Detiene el intervalo de actualización cuando se cierra la página
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

