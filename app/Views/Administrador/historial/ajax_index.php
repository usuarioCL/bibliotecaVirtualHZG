<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/historial.css') ?>">

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-history text-primary me-2"></i>
                        Historial de Usuarios
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="cargarContenidoDefault()">Dashboard</a></li>
                            <li class="breadcrumb-item active">Historial</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Registro de todas las acciones realizadas en el sistema</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                        <i class="ti ti-refresh"></i> Actualizar
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="exportBtn">
                        <i class="ti ti-download"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card historial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="historial-stats-icon bg-primary text-white">
                                <i class="ti ti-user-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Usuarios Activos</p>
                            <h4 class="mb-0" id="usuariosActivos">-</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card historial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="historial-stats-icon bg-warning text-white">
                                <i class="ti ti-user-x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Usuarios Suspendidos</p>
                            <h4 class="mb-0" id="usuariosSuspendidos">-</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card historial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="historial-stats-icon bg-warning text-white">
                                <i class="ti ti-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Última Actividad</p>
                            <h6 class="mb-0" id="ultimaActividad">-</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Buscar</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ti ti-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por usuario, acción...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="tipoAccionSelect" class="form-label">Tipo de Acción</label>
                            <select class="form-select" id="tipoAccionSelect">
                                <option value="">Todas las acciones</option>
                                <option value="creacion">Creación</option>
                                <option value="eliminacion">Eliminación</option>
                                <option value="actualizacion">Actualización</option>
                                <option value="suspension">Suspensión</option>
                                <option value="reactivacion">Reactivación</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tipoUsuarioSelect" class="form-label">Tipo de Usuario</label>
                            <select class="form-select" id="tipoUsuarioSelect">
                                <option value="">Todos los tipos</option>
                                <option value="admin">Administrador</option>
                                <option value="docente">Docente</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="fechaSelect" class="form-label">Fecha</label>
                            <select class="form-select" id="fechaSelect">
                                <option value="">Todas las fechas</option>
                                <option value="hoy">Hoy</option>
                                <option value="ayer">Ayer</option>
                                <option value="semana">Esta semana</option>
                                <option value="mes">Este mes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-list me-2"></i>
                            Registro de Actividades
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">
                                Mostrando <span id="showingCount">0</span> de <span id="totalCount">0</span> registros
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Acción</th>
                                    <th class="border-0">Usuario Actor</th>
                                    <th class="border-0">Usuario Afectado</th>
                                    <th class="border-0">Tipo</th>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0 text-center">Detalles</th>
                                </tr>
                            </thead>
                            <tbody id="historialTableBody">
                                <!-- Los datos se cargarán aquí via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let historialData = [];

    // Cargar datos iniciales
    cargarHistorial();
    cargarEstadisticas();

    // Event listeners
    $('#searchInput').on('input', function() {
        filtrarHistorial();
    });

    $('#tipoAccionSelect, #tipoUsuarioSelect, #fechaSelect').on('change', function() {
        filtrarHistorial();
    });

    $('#refreshBtn').on('click', function() {
        cargarHistorial();
        cargarEstadisticas();
    });
    
    // Función para actualizar estadísticas cuando se realice una nueva acción
    function actualizarEstadisticasEnTiempoReal() {
        cargarEstadisticas();
    }

    $('#exportBtn').on('click', function() {
        exportarHistorial();
    });

    function cargarHistorial() {
        const busqueda = $('#searchInput').val();
        const tipoAccion = $('#tipoAccionSelect').val();
        const tipoUsuario = $('#tipoUsuarioSelect').val();
        const fecha = $('#fechaSelect').val();

        $.ajax({
            url: '<?= base_url('historial-usuarios/getHistorialAjax') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                busqueda: busqueda,
                tipo_accion: tipoAccion,
                tipo_usuario: tipoUsuario,
                fecha: fecha
            },
            success: function(response) {
                if (response.success) {
                    historialData = response.data;
                    mostrarHistorial(historialData);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function() {
                console.error('Error al cargar historial');
            }
        });
    }

    function cargarEstadisticas() {
        $.ajax({
            url: '<?= base_url('historial-usuarios/estadisticas') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    $('#usuariosActivos').text(stats.usuarios_activos || 0);
                    $('#usuariosSuspendidos').text(stats.usuarios_suspendidos || 0);
                    
                    // Calcular tiempo transcurrido en tiempo real
                    if (stats.ultima_actividad_fecha) {
                        actualizarTiempoTranscurrido(stats.ultima_actividad_fecha);
                    } else {
                        $('#ultimaActividad').text('N/A');
                    }
                } else {
                    console.error('Error en estadísticas:', response.message);
                }
            },
            error: function() {
                console.error('Error al cargar estadísticas');
            }
        });
    }

    function mostrarHistorial(historial) {
        if (historial.length === 0) {
            $('#historialTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="historial-empty-state">
                            <i class="ti ti-history empty-icon"></i>
                            <h5>No hay registros de historial</h5>
                            <p>No se encontraron actividades registradas.</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        historial.forEach(registro => {
            const fecha = new Date(registro.fecha);
            const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });

            const tipoBadge = getTipoBadge(registro.tipo_usuario);
            const accionBadge = getAccionBadge(registro.accion);

            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            ${accionBadge}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="historial-user-avatar">
                                <i class="ti ti-user"></i>
                            </div>
                            <div class="ms-2">
                                <div class="fw-medium">${registro.usuario_actor}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="historial-user-avatar">
                                <i class="ti ti-user"></i>
                            </div>
                            <div class="ms-2">
                                <div class="fw-medium">${registro.usuario_afectado}</div>
                            </div>
                        </div>
                    </td>
                    <td>${tipoBadge}</td>
                    <td>
                        <div class="historial-fecha">
                            <div class="fw-medium">${fechaFormateada}</div>
                            <small class="text-muted">${registro.fecha}</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                onclick="verDetalles(${registro.id})" 
                                title="Ver detalles">
                            <i class="ti ti-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#historialTableBody').html(html);
        $('#showingCount').text(historial.length);
        $('#totalCount').text(historial.length);
    }

    function getTipoBadge(tipo) {
        // Normalizar el tipo para evitar problemas de mayúsculas/minúsculas
        const tipoNormalizado = (tipo || '').toLowerCase().trim();
        
        const badges = {
            'admin': '<span class="badge bg-danger">Admin</span>',
            'docente': '<span class="badge bg-warning text-dark">Docente</span>',
            'estudiante': '<span class="badge bg-success">Estudiante</span>'
        };

        return badges[tipoNormalizado] || '<span class="badge bg-secondary">Desconocido (' + tipo + ')</span>';
    }

    function getAccionBadge(accion) {
        const badges = {
            'Usuario creado': '<span class="badge bg-success">Creación</span>',
            'Usuario eliminado': '<span class="badge bg-danger">Eliminación</span>',
            'Perfil actualizado': '<span class="badge bg-info">Actualización</span>',
            'Contraseña cambiada': '<span class="badge bg-warning">Cambio</span>',
            'Usuario suspendido': '<span class="badge bg-danger">Suspensión</span>',
            'Usuario reactivado': '<span class="badge bg-success">Reactivación</span>'
        };
        return badges[accion] || '<span class="badge bg-secondary">Otro</span>';
    }

    function filtrarHistorial() {
        const termino = $('#searchInput').val().toLowerCase();
        const tipoAccion = $('#tipoAccionSelect').val();
        const tipoUsuario = $('#tipoUsuarioSelect').val();
        const fecha = $('#fechaSelect').val();

        let historialFiltrado = historialData.filter(registro => {
            const coincideBusqueda = !termino || 
                registro.usuario_actor.toLowerCase().includes(termino) ||
                registro.usuario_afectado.toLowerCase().includes(termino) ||
                registro.accion.toLowerCase().includes(termino) ||
                registro.detalles.toLowerCase().includes(termino);

            const coincideTipoAccion = !tipoAccion || 
                registro.accion.toLowerCase().includes(tipoAccion);

            const coincideTipoUsuario = !tipoUsuario || 
                registro.tipo_usuario === tipoUsuario;

            return coincideBusqueda && coincideTipoAccion && coincideTipoUsuario;
        });

        mostrarHistorial(historialFiltrado);
    }

    function exportarHistorial() {
        Swal.fire({
            title: 'Exportar Historial',
            text: 'La exportación estará disponible próximamente.',
            icon: 'info',
            confirmButtonText: 'Entendido'
        });
    }

    // Función para calcular tiempo transcurrido
    function calcularTiempoTranscurrido(fechaString) {
        const ahora = new Date();
        const fecha = new Date(fechaString);
        const diferencia = ahora - fecha;
        
        const segundos = Math.floor(diferencia / 1000);
        const minutos = Math.floor(segundos / 60);
        const horas = Math.floor(minutos / 60);
        const dias = Math.floor(horas / 24);
        
        if (dias > 0) {
            return `${dias} día${dias > 1 ? 's' : ''}`;
        } else if (horas > 0) {
            return `${horas} hora${horas > 1 ? 's' : ''}`;
        } else if (minutos > 0) {
            return `${minutos} minuto${minutos > 1 ? 's' : ''}`;
        } else {
            return 'Hace un momento';
        }
    }
    
    // Función para actualizar tiempo transcurrido
    function actualizarTiempoTranscurrido(fechaString) {
        const tiempoTranscurrido = calcularTiempoTranscurrido(fechaString);
        $('#ultimaActividad').text(tiempoTranscurrido);
        // Guardar la fecha para actualizaciones automáticas
        $('#ultimaActividad').data('fecha', fechaString);
    }
    
    // Función para actualizar tiempo cada minuto
    function iniciarActualizacionTiempo() {
        setInterval(function() {
            // Solo actualizar si hay una fecha válida
            const fechaElement = $('#ultimaActividad').data('fecha');
            if (fechaElement) {
                actualizarTiempoTranscurrido(fechaElement);
            }
        }, 60000); // Actualizar cada minuto
    }
    
    // Función global para ver detalles
    window.verDetalles = function(id) {
        const registro = historialData.find(r => r.id === id);
        if (registro) {
            Swal.fire({
                title: 'Detalles de la Acción',
                html: `
                    <div class="text-start">
                        <p><strong>Acción:</strong> ${registro.accion}</p>
                        <p><strong>Usuario Actor:</strong> ${registro.usuario_actor}</p>
                        <p><strong>Usuario Afectado:</strong> ${registro.usuario_afectado}</p>
                        <p><strong>Tipo de Usuario:</strong> ${registro.tipo_usuario}</p>
                        <p><strong>Fecha:</strong> ${registro.fecha}</p>
                        <p><strong>Detalles:</strong> ${registro.detalles}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        }
    };
    
    // Iniciar actualización automática del tiempo
    iniciarActualizacionTiempo();
    
    // Actualizar estadísticas cada 30 segundos para reflejar cambios en tiempo real
    setInterval(function() {
        actualizarEstadisticasEnTiempoReal();
    }, 30000); // Actualizar cada 30 segundos
});
</script>
