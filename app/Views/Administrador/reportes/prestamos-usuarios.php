<?php
/**
 * Vista: Reportes - Préstamos por Usuario
 * Descripción: Estadísticas detalladas de préstamos agrupados por usuario
 * Ubicación: app/Views/Administrador/reportes/prestamos-usuarios.php
 */

// Verificar si hay datos disponibles
$estadisticas = $estadisticas ?? [
    'total_usuarios' => 0,
    'total_prestamos' => 0,
    'prestamos_pendientes' => 0,
    'prestamos_vencidos' => 0,
    'promedio_mensual' => 0,
    'crecimiento_mensual' => '0%'
];

$top_usuarios = $top_usuarios ?? [];
$usuarios_prestamos = $usuarios_prestamos ?? [];
$tendencias_mensuales = $tendencias_mensuales ?? [];
?>

<?php if (isset($error)): ?>
<div class="container-fluid py-4">
    <div class="alert alert-warning">
        <i class="ti ti-alert-triangle me-2"></i>
        <?= esc($error) ?>
    </div>
</div>
<?php endif; ?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-chart-line me-2 text-primary"></i>
                Préstamos por Usuario
            </h1>
            <p class="text-muted small mb-0">Estadísticas y análisis de préstamos por estudiante</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success" onclick="exportarReportePrestamos()">
                <i class="ti ti-file-export me-1"></i>
                Exportar Datos
            </button>
            <button type="button" class="btn btn-primary" onclick="generarGrafico()">
                <i class="ti ti-chart-bar me-1"></i>
                Ver Gráfico
            </button>
        </div>
    </div>

    <!-- Filtros de reporte -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-filter me-1"></i>
                Filtros de Análisis
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtroFechaDesde" class="form-label">Fecha Desde</label>
                    <input type="date" id="filtroFechaDesde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filtroFechaHasta" class="form-label">Fecha Hasta</label>
                    <input type="date" id="filtroFechaHasta" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="filtroNivelUsuarios" class="form-label">Nivel</label>
                    <select id="filtroNivelUsuarios" class="form-select">
                        <option value="">Todos</option>
                        <option value="Inicial">Inicial</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroGrado" class="form-label">Grado</label>
                    <select id="filtroGrado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1">1°</option>
                        <option value="2">2°</option>
                        <option value="3">3°</option>
                        <option value="4">4°</option>
                        <option value="5">5°</option>
                        <option value="6">6°</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltrosUsuarios()">
                        <i class="ti ti-search me-1"></i>
                        Aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-users fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalUsuarios"><?= isset($estadisticas['total_usuarios']) ? $estadisticas['total_usuarios'] : 0 ?></h4>
                    <p class="mb-0 small">Usuarios Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-book fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalPrestamos"><?= number_format($estadisticas['total_prestamos'] ?? 0) ?></h4>
                    <p class="mb-0 small">Total Préstamos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-clock fs-1 mb-2"></i>
                    <h4 class="mb-1" id="prestamosPendientes"><?= $estadisticas['prestamos_pendientes'] ?? 0 ?></h4>
                    <p class="mb-0 small">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-alert-circle fs-1 mb-2"></i>
                    <h4 class="mb-1" id="prestamosVencidos"><?= $estadisticas['prestamos_vencidos'] ?? 0 ?></h4>
                    <p class="mb-0 small">Vencidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-calendar fs-1 mb-2"></i>
                    <h4 class="mb-1" id="promedioMensual"><?= $estadisticas['promedio_mensual'] ?? 0 ?></h4>
                    <p class="mb-0 small">Promedio/Usuario</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-dark text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-trending-up fs-1 mb-2"></i>
                    <h4 class="mb-1" id="crecimientoMensual"><?= $estadisticas['crecimiento_mensual'] ?? '0%' ?></h4>
                    <p class="mb-0 small">Crecimiento</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de tendencias -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-chart-line me-1"></i>
                        Tendencia de Préstamos Mensuales
                    </h6>
                </div>
                <div class="card-body">
                    <div id="chartPrestamos" style="height: 300px;">
                        <!-- Aquí se renderizará el gráfico con Chart.js -->
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="ti ti-chart-line fs-1 mb-3"></i>
                                <p>Gráfico de tendencias</p>
                                <button class="btn btn-primary btn-sm" onclick="cargarGrafico()">Cargar Gráfico</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-medal me-1"></i>
                        Top 5 Usuarios Más Activos
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (!empty($top_usuarios)): ?>
                            <?php 
                            $badges = ['bg-warning text-dark', 'bg-secondary', 'bg-success', 'bg-info', 'bg-primary'];
                            foreach ($top_usuarios as $index => $usuario): 
                                $badgeClass = $badges[$index] ?? 'bg-secondary';
                                $posicion = $index + 1;
                            ?>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="badge <?= $badgeClass ?> me-3"><?= $posicion ?>°</div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium"><?= esc($usuario['nombre']) ?></div>
                                    <small class="text-muted"><?= esc($usuario['grado']) ?></small>
                                </div>
                                <div class="text-primary fw-bold"><?= $usuario['total_prestamos'] ?> libros</div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="list-group-item text-center text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                No hay datos de usuarios disponibles
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla detallada de usuarios -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detalle de Préstamos por Usuario</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="cambiarVistaTabla(this.value)">
                        <option value="detallado">Vista Detallada</option>
                        <option value="resumen">Vista Resumen</option>
                        <option value="grafico">Vista Gráfica</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" onclick="exportarTablaUsuarios()">
                        <i class="ti ti-download me-1"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Usuario</th>
                            <th>Nivel/Grado</th>
                            <th class="text-center">Total Préstamos</th>
                            <th class="text-center">Activos</th>
                            <th class="text-center">Completados</th>
                            <th class="text-center">Vencidos</th>
                            <th class="text-center">Último Préstamo</th>
                            <th class="text-center">Promedio/Mes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaUsuarios">
                        <?php if (!empty($usuarios_prestamos)): ?>
                            <?php foreach ($usuarios_prestamos as $index => $usuario): 
                                $iniciales = '';
                                $nombres = explode(' ', $usuario['nombre_completo']);
                                foreach ($nombres as $nombre) {
                                    $iniciales .= strtoupper(substr($nombre, 0, 1));
                                }
                                $iniciales = substr($iniciales, 0, 2);
                                
                                $avatarColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                                $avatarColor = $avatarColors[$index % count($avatarColors)];
                                
                                $vencidosBadge = $usuario['prestamos_vencidos'] > 0 ? 'bg-danger' : 'bg-success';
                            ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm <?= $avatarColor ?> text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <span class="small fw-bold"><?= $iniciales ?></span>
                                        </div>
                                        <div>
                                            <div class="fw-medium"><?= esc($usuario['nombre_completo']) ?></div>
                                            <small class="text-muted"><?= esc($usuario['email'] ?? 'Sin email') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= esc($usuario['nivel_grado']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6"><?= $usuario['total_prestamos'] ?></span>
                                </td>
                                <td class="text-center"><?= $usuario['prestamos_activos'] ?></td>
                                <td class="text-center"><?= $usuario['prestamos_completados'] ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $vencidosBadge ?>"><?= $usuario['prestamos_vencidos'] ?></span>
                                </td>
                                <td class="text-center">
                                    <?= $usuario['ultimo_prestamo'] ? date('d/m/Y', strtotime($usuario['ultimo_prestamo'])) : 'N/A' ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?= $usuario['promedio_mensual'] ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="verDetalleUsuario(<?= $usuario['idpersona'] ?>)" title="Ver detalle completo">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-info" 
                                                onclick="verHistorialCompleto(<?= $usuario['idpersona'] ?>)" title="Ver historial">
                                            <i class="ti ti-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="ti ti-info-circle fs-1 mb-2"></i>
                                    <p>No hay datos de usuarios disponibles con los filtros aplicados</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Mostrando <?= count($usuarios_prestamos) ?> de <?= count($usuarios_prestamos) ?> usuarios</small>
                </div>
                <nav aria-label="Navegación de usuarios">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Usuario -->
<div class="modal fade" id="modalDetalleUsuario" tabindex="-1" aria-labelledby="modalDetalleUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetalleUsuarioLabel">
                    <i class="ti ti-user me-2"></i>
                    Detalle Completo del Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleUsuario">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="exportarDetalleUsuario()">
                    <i class="ti ti-download me-1"></i>
                    Exportar Detalle
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para el reporte de préstamos por usuario
function aplicarFiltrosUsuarios() {
    const fechaDesde = document.getElementById('filtroFechaDesde').value;
    const fechaHasta = document.getElementById('filtroFechaHasta').value;
    const nivel = document.getElementById('filtroNivelUsuarios').value;
    const grado = document.getElementById('filtroGrado').value;
    
    // Mostrar loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i>Aplicando...';
    btn.disabled = true;
    
    // Construir URL con parámetros
    const params = new URLSearchParams();
    if (fechaDesde) params.append('fecha_desde', fechaDesde);
    if (fechaHasta) params.append('fecha_hasta', fechaHasta);
    if (nivel) params.append('nivel', nivel);
    if (grado) params.append('grado', grado);
    
    // Recargar página con filtros
    window.location.href = window.location.pathname + '?' + params.toString();
}

function exportarReportePrestamos() {
    // Implementar exportación
    fetch('<?= base_url('reportes/exportar/prestamos-usuarios/excel') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Descargar archivo
                window.open(data.url, '_blank');
            } else {
                alert('Error al exportar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al exportar el reporte');
        });
}

function generarGrafico() {
    cargarGrafico();
}

function cargarGrafico() {
    fetch('<?= base_url('reportes/grafico/tendencias-mensuales') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderizarGraficoTendencias(data.data);
            } else {
                document.getElementById('chartPrestamos').innerHTML = 
                    '<div class="alert alert-warning">Error al cargar gráfico: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('chartPrestamos').innerHTML = 
                '<div class="alert alert-danger">Error al cargar el gráfico</div>';
        });
}

function renderizarGraficoTendencias(datos) {
    const chartContainer = document.getElementById('chartPrestamos');
    
    if (!datos || datos.length === 0) {
        chartContainer.innerHTML = '<div class="alert alert-info">No hay datos suficientes para mostrar el gráfico</div>';
        return;
    }
    
    // Crear gráfico simple con los datos
    let html = '<div class="chart-simple">';
    html += '<h6 class="text-center mb-3">Préstamos por Mes</h6>';
    html += '<div class="row">';
    
    datos.forEach(item => {
        const altura = Math.max(20, (item.total_prestamos / Math.max(...datos.map(d => d.total_prestamos))) * 200);
        html += `
            <div class="col text-center">
                <div class="chart-bar bg-primary mb-2" style="height: ${altura}px; width: 30px; margin: 0 auto;"></div>
                <small class="text-muted">${item.mes_nombre}</small>
                <div class="fw-bold">${item.total_prestamos}</div>
            </div>
        `;
    });
    
    html += '</div></div>';
    chartContainer.innerHTML = html;
}

function cambiarVistaTabla(vista) {
    console.log('Cambiando vista de tabla a:', vista);
    // Implementar cambio de vista según necesidades
}

function exportarTablaUsuarios() {
    // Implementar exportación de tabla
    const tabla = document.getElementById('tablaUsuarios');
    const csv = tableToCSV(tabla);
    downloadCSV(csv, 'usuarios_prestamos.csv');
}

function tableToCSV(table) {
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) { // Excluir columna de acciones
            row.push(cols[j].innerText.replace(/,/g, ';'));
        }
        csv.push(row.join(','));
    }
    
    return csv.join('\n');
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

function verDetalleUsuario(idpersona) {
    fetch(`<?= base_url('reportes/detalle-usuario/') ?>${idpersona}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarDetalleUsuario(data.data);
                $('#modalDetalleUsuario').modal('show');
            } else {
                alert('Error al cargar detalle: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el detalle del usuario');
        });
}

function mostrarDetalleUsuario(datos) {
    const contenido = document.getElementById('contenidoDetalleUsuario');
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información Personal</h6>
                <p><strong>Nombre:</strong> ${datos.info_usuario.nombre_completo}</p>
                <p><strong>Email:</strong> ${datos.info_usuario.email || 'No disponible'}</p>
                <p><strong>Teléfono:</strong> ${datos.info_usuario.telefono || 'No disponible'}</p>
                <p><strong>Documento:</strong> ${datos.info_usuario.numerodoc}</p>
                <p><strong>Nivel/Grado:</strong> ${datos.info_usuario.nivel_grado}</p>
            </div>
            <div class="col-md-6">
                <h6>Estadísticas de Préstamos</h6>
                <p><strong>Total Préstamos:</strong> ${datos.estadisticas.total_prestamos}</p>
                <p><strong>Activos:</strong> ${datos.estadisticas.activos}</p>
                <p><strong>Completados:</strong> ${datos.estadisticas.completados}</p>
                <p><strong>Vencidos:</strong> ${datos.estadisticas.vencidos}</p>
                <p><strong>Primer Préstamo:</strong> ${datos.estadisticas.primer_prestamo ? new Date(datos.estadisticas.primer_prestamo).toLocaleDateString() : 'N/A'}</p>
            </div>
        </div>
    `;
    
    if (datos.historial && datos.historial.length > 0) {
        html += `
            <hr>
            <h6>Historial Reciente</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Recurso</th>
                            <th>Fecha Préstamo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        datos.historial.slice(0, 10).forEach(prestamo => {
            html += `
                <tr>
                    <td>${prestamo.titulo}</td>
                    <td>${new Date(prestamo.fechaprestamo).toLocaleDateString()}</td>
                    <td>${prestamo.fechahoraretorno ? 'Devuelto' : 'Activo'}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    }
    
    contenido.innerHTML = html;
}

function verHistorialCompleto(idpersona) {
    // Redirigir a una página de historial completo o abrir modal con más datos
    window.open(`<?= base_url('admin/usuarios/historial/') ?>${idpersona}`, '_blank');
}

function exportarDetalleUsuario() {
    // Implementar exportación del detalle mostrado en el modal
    const contenido = document.getElementById('contenidoDetalleUsuario').innerText;
    const blob = new Blob([contenido], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'detalle_usuario.txt';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Inicializar fechas por defecto (último mes)
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    document.getElementById('filtroFechaDesde').valueAsDate = lastMonth;
    document.getElementById('filtroFechaHasta').valueAsDate = today;
    
    // Cargar gráfico automáticamente si hay datos
    <?php if (!empty($tendencias_mensuales)): ?>
    setTimeout(() => {
        renderizarGraficoTendencias(<?= json_encode($tendencias_mensuales) ?>);
    }, 1000);
    <?php endif; ?>
});
</script>