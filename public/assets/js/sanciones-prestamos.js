/**
 * Sistema de Verificación de Sanciones para Préstamos
 * Maneja las alertas cuando un usuario con sanciones activas intenta solicitar un préstamo físico
 */

// Función para verificar sanciones antes de solicitar préstamo
function verificarSancionesAntesDePrestamo(idRecurso, callback) {
    // Obtener ID del usuario desde la sesión o localStorage
    const idUsuario = obtenerIdUsuario();
    
    if (!idUsuario) {
        console.error('No se pudo obtener el ID del usuario');
        return;
    }
    
    // Verificar sanciones activas
    fetch(`<?= base_url('sanciones/verificar') ?>/${idUsuario}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.tiene_sanciones) {
            // Mostrar alerta de sanciones
            mostrarAlertaSanciones(data.sanciones, idRecurso);
        } else {
            // No hay sanciones, continuar con el préstamo
            if (callback) callback();
        }
    })
    .catch(error => {
        console.error('Error al verificar sanciones:', error);
        // En caso de error, permitir el préstamo
        if (callback) callback();
    });
}

// Función para mostrar alerta de sanciones con SweetAlert2
function mostrarAlertaSanciones(sanciones, idRecurso) {
    let mensaje = "⚠️ <strong>PRÉSTAMO BLOQUEADO</strong><br><br>";
    mensaje += "Tienes <strong>" + sanciones.length + " sanción(es) activa(s)</strong> que impiden préstamos físicos.<br><br>";
    
    sanciones.forEach(function(sancion) {
        const fechaVencimiento = sancion.fecha_vencimiento ? 
            new Date(sancion.fecha_vencimiento).toLocaleDateString('es-ES') : 
            'Sin fecha límite';
        
        mensaje += "• <strong>" + sancion.tiposancion + "</strong><br>";
        mensaje += "&nbsp;&nbsp;&nbsp;Detalles: " + sancion.detallesancion + "<br>";
        mensaje += "&nbsp;&nbsp;&nbsp;Vence: " + fechaVencimiento + "<br><br>";
    });
    
    mensaje += "📚 <strong>Puedes seguir usando recursos digitales</strong><br>";
    mensaje += "Para levantar las sanciones, contacta con el administrador.";
    
    Swal.fire({
        title: 'Sanciones Activas',
        html: mensaje,
        icon: 'warning',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#d97706',
        showCancelButton: true,
        cancelButtonText: 'Ver Mis Sanciones',
        cancelButtonColor: '#6b7280',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            popup: 'swal-sanciones-popup',
            title: 'swal-sanciones-title',
            content: 'swal-sanciones-content'
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            // Mostrar página de sanciones del usuario
            mostrarSancionesUsuario();
        }
    });
}

// Función para mostrar las sanciones del usuario actual
function mostrarSancionesUsuario() {
    const idUsuario = obtenerIdUsuario();
    
    if (!idUsuario) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo obtener la información del usuario',
            confirmButtonColor: '#dc2626'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Cargando sanciones...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Obtener sanciones del usuario
    fetch(`<?= base_url('sanciones/persona') ?>/${idUsuario}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success && data.sanciones.length > 0) {
            let html = '<div class="text-start">';
            html += '<h6 class="mb-3">📋 Mis Sanciones</h6>';
            
            data.sanciones.forEach(function(sancion) {
                const estadoClass = sancion.estado_sancion === 'activa' ? 'text-danger' : 
                                  sancion.estado_sancion === 'cumplida' ? 'text-success' : 'text-secondary';
                
                html += '<div class="border rounded p-3 mb-2">';
                html += '<div class="d-flex justify-content-between align-items-start">';
                html += '<div>';
                html += '<strong>' + sancion.tiposancion + '</strong>';
                html += '<span class="badge bg-' + (sancion.estado_sancion === 'activa' ? 'danger' : 'success') + ' ms-2">' + sancion.estado_sancion + '</span>';
                html += '</div>';
                html += '</div>';
                html += '<p class="mb-1 mt-2"><strong>Detalles:</strong> ' + sancion.detallesancion + '</p>';
                html += '<p class="mb-1"><strong>Fecha:</strong> ' + new Date(sancion.fecha_sancion).toLocaleDateString('es-ES') + '</p>';
                if (sancion.fecha_vencimiento) {
                    html += '<p class="mb-0"><strong>Vence:</strong> ' + new Date(sancion.fecha_vencimiento).toLocaleDateString('es-ES') + '</p>';
                }
                html += '</div>';
            });
            
            html += '</div>';
            
            Swal.fire({
                title: 'Mis Sanciones',
                html: html,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#6b7280',
                width: '600px'
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Sin sanciones',
                text: 'No tienes sanciones registradas',
                confirmButtonColor: '#6b7280'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error al cargar sanciones:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las sanciones',
            confirmButtonColor: '#dc2626'
        });
    });
}

// Función para obtener el ID del usuario
function obtenerIdUsuario() {
    // Intentar obtener desde diferentes fuentes
    let idUsuario = null;
    
    // Desde localStorage
    if (localStorage.getItem('idUsuario')) {
        idUsuario = localStorage.getItem('idUsuario');
    }
    
    // Desde variables globales (si están definidas)
    if (typeof window.idUsuario !== 'undefined') {
        idUsuario = window.idUsuario;
    }
    
    // Desde el DOM (si hay un elemento con el ID)
    const elementoUsuario = document.querySelector('[data-usuario-id]');
    if (elementoUsuario) {
        idUsuario = elementoUsuario.getAttribute('data-usuario-id');
    }
    
    return idUsuario;
}

// Función para interceptar solicitudes de préstamo
function interceptarSolicitudPrestamo(idRecurso, callbackOriginal) {
    verificarSancionesAntesDePrestamo(idRecurso, callbackOriginal);
}

// Estilos CSS para las alertas de sanciones
const estilosSanciones = `
<style>
.swal-sanciones-popup {
    border-radius: 20px !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

.swal-sanciones-title {
    color: #d97706 !important;
    font-weight: 700 !important;
}

.swal-sanciones-content {
    font-size: 14px !important;
    line-height: 1.6 !important;
}

.swal-sanciones-content strong {
    color: #1f2937 !important;
}

.swal-sanciones-content .badge {
    font-size: 0.75rem !important;
}
</style>
`;

// Agregar estilos al documento
document.head.insertAdjacentHTML('beforeend', estilosSanciones);

// Exportar funciones para uso global
window.verificarSancionesAntesDePrestamo = verificarSancionesAntesDePrestamo;
window.interceptarSolicitudPrestamo = interceptarSolicitudPrestamo;
window.mostrarAlertaSanciones = mostrarAlertaSanciones;

