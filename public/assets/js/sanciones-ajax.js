/**
 * Script para manejar la carga de sanciones activas vía AJAX
 * Se ejecuta en el dashboard principal
 */

$(document).ready(function() {
    // Verificar si estamos en el dashboard
    if ($('#sanciones-activas-container').length === 0) {
        return;
    }

    // Cargar sanciones activas
    function cargarSancionesActivas() {
        const $container = $('#sanciones-activas-container');
        const $loading = $container.find('.loading-sanciones');
        const $error = $container.find('.error-sanciones');
        const $tablaWrapper = $container.find('.tabla-sanciones-wrapper');
        const $tablaBody = $container.find('tbody');
        const $contador = $container.find('.contador-sanciones');
        const $contadorWrapper = $container.find('.contador-wrapper');
        const $sinSanciones = $container.find('.sin-sanciones');

        // Mostrar indicador de carga
        $loading.removeClass('d-none');
        $error.addClass('d-none');
        $tablaWrapper.addClass('d-none');
        $sinSanciones.addClass('d-none');
        $contadorWrapper.addClass('d-none');

        // Realizar petición AJAX
        $.ajax({
            url: base_url + 'sanciones/activas-ajax',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $loading.addClass('d-none');
                
                if (response.success && response.sanciones && response.sanciones.length > 0) {
                    // Construir filas de la tabla
                    let filas = '';
                    
                    response.sanciones.forEach(function(sancion) {
                        // Formatear fechas
                        const fechaInicio = sancion.fecha_inicio ? new Date(sancion.fecha_inicio).toLocaleDateString('es-ES') : 'N/A';
                        const fechaFin = sancion.fecha_fin ? new Date(sancion.fecha_fin).toLocaleDateString('es-ES') : 'N/A';
                        
                        filas += `
                            <tr>
                                <td>${sancion.nombre_completo || 'N/A'}</td>
                                <td>${sancion.tipo_sancion || 'N/A'}</td>
                                <td>${fechaInicio}</td>
                                <td>${fechaFin}</td>
                                <td>
                                    <span class="badge bg-danger">Activa</span>
                                </td>
                            </tr>
                        `;
                    });
                    
                    // Actualizar la tabla
                    $tablaBody.html(filas);
                    $tablaWrapper.removeClass('d-none');
                    
                    // Actualizar contador
                    if (response.estadisticas) {
                        $contador.text(response.estadisticas.total_sanciones || 0);
                        $contadorWrapper.removeClass('d-none');
                    }
                    
                } else {
                    // No hay sanciones activas
                    $sinSanciones.removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar sanciones:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                $loading.addClass('d-none');
                $error.removeClass('d-none');
                
                let errorMsg = 'Error al cargar las sanciones.';
                if (xhr.responseJSON && xhr.responseJSON.messages && xhr.responseJSON.messages.error) {
                    errorMsg += ' ' + xhr.responseJSON.messages.error;
                }
                
                $error.find('.error-message').text(errorMsg);
            }
        });
    }

    // Cargar sanciones al cargar la página
    cargarSancionesActivas();

    // Botón para recargar sanciones
    $(document).on('click', '.recargar-sanciones', function(e) {
        e.preventDefault();
        cargarSancionesActivas();
    });
});
