<!-- Ejemplo de integración del sistema de verificación de sanciones -->
<!-- Este archivo muestra cómo integrar la verificación de sanciones en las vistas de préstamos -->

<!-- Incluir el script de verificación de sanciones -->
<script src="<?= base_url('assets/js/sanciones-prestamos.js') ?>"></script>

<script>
// Ejemplo de cómo interceptar el botón de solicitar préstamo
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar todos los botones de solicitar préstamo
    const botonesPrestamo = document.querySelectorAll('[data-action="solicitar-prestamo"]');
    
    botonesPrestamo.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const idRecurso = this.getAttribute('data-recurso-id');
            const tipoRecurso = this.getAttribute('data-tipo-recurso');
            
            // Si es un recurso físico, verificar sanciones primero
            if (tipoRecurso === 'fisico') {
                verificarSancionesAntesDePrestamo(idRecurso, function() {
                    // Si no hay sanciones, proceder con la solicitud original
                    solicitarPrestamoOriginal(idRecurso);
                });
            } else {
                // Si es digital, proceder directamente
                solicitarPrestamoOriginal(idRecurso);
            }
        });
    });
});

// Función original de solicitar préstamo (ejemplo)
function solicitarPrestamoOriginal(idRecurso) {
    // Aquí iría la lógica original de solicitar préstamo
    console.log('Solicitando préstamo para recurso:', idRecurso);
    
    // Ejemplo de llamada AJAX
    fetch('<?= base_url('prestamos/solicitar') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'idRecurso': idRecurso,
            'fechaPrestamo': new Date().toISOString().split('T')[0],
            'horaInicio': '09:00',
            'horaFin': '17:00'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Préstamo solicitado!',
                text: 'Tu solicitud ha sido enviada correctamente',
                confirmButtonColor: '#059669'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#dc2626'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al procesar la solicitud',
            confirmButtonColor: '#dc2626'
        });
    });
}
</script>

<!-- Ejemplo de HTML para botones de préstamo -->
<!-- 
<button class="btn btn-primary" 
        data-action="solicitar-prestamo" 
        data-recurso-id="123" 
        data-tipo-recurso="fisico">
    <i class="ti ti-book"></i> Solicitar Préstamo Físico
</button>

<button class="btn btn-success" 
        data-action="solicitar-prestamo" 
        data-recurso-id="456" 
        data-tipo-recurso="digital">
    <i class="ti ti-device-desktop"></i> Acceder Digital
</button>
-->

