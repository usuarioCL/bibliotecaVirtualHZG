// Función global para mostrar alerta de login requerido
window.mostrarAlertaLogin = function(accion) {
    console.log('Función mostrarAlertaLogin llamada con acción:', accion);
    if (typeof Swal === 'undefined') {
        alert('Para ' + accion + ' necesitas estar registrado e iniciar sesión en el sistema.');
        return;
    }
    if (typeof fixSweetAlertZIndex === 'function') {
        fixSweetAlertZIndex();
    }
    Swal.fire({
        title: 'Inicia Sesión',
        text: 'Para ' + accion + ' necesitas estar registrado e iniciar sesión en el sistema.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Iniciar Sesión',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        customClass: {
            container: 'swal2-top-container'
        },
        didOpen: function() {
            const container = document.querySelector('.swal2-container');
            if (container) container.style.zIndex = '999999';
            const popup = document.querySelector('.swal2-popup');
            if (popup) popup.style.zIndex = '999999';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url_login;
        }
    });
};

// Variable global para la URL de login (debe ser definida en cada vista o layout)
// window.base_url_login = '/login';
