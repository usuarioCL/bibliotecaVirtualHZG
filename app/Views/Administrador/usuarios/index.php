<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-users text-primary me-2"></i>
                        Gestión de Usuarios
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Usuarios</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra los usuarios registrados en el sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiltrarUsuarios">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                        <i class="ti ti-plus"></i> Nuevo Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card primary h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="ti ti-users text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($totalUsuarios) ? number_format($totalUsuarios) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Usuarios</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-shield-lock text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($administradores) ? number_format($administradores) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Administradores</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-user-check text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($docentes) ? number_format($docentes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Docentes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-school text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estudiantes) ? number_format($estudiantes) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Estudiantes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Usuarios
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona todos los usuarios del sistema</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="ti ti-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Información Personal</th>
                            <th class="border-0 px-3 py-3">Email</th>
                            <th class="border-0 text-center px-3 py-3">Nivel de Acceso</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr class="border-bottom">
                                    
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar rounded-3 text-black d-flex align-items-center justify-content-center me-3 shadow-lg" style="width: 42px; height: 42px; font-weight: 600;">
                                                <?= strtoupper(substr($usuario['nomuser'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark"><?= $usuario['nomuser'] ?></h6>
                                                <small class="text-muted d-flex align-items-center">
                                                    <i class="ti ti-at me-1"></i><?= $usuario['nomuser'] ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <?php if (isset($usuario['nombres']) && isset($usuario['apellidos'])): ?>
                                                <div class="fw-semibold text-dark mb-1"><?= $usuario['nombres'] . ' ' . $usuario['apellidos'] ?></div>
                                            <?php endif; ?>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="ti ti-id-badge me-1 text-secondary"></i>
                                                <span><?= $usuario['numerodoc'] ?? 'Sin documento' ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if (!empty($usuario['email'])): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-mail me-2 text-primary"></i>
                                                <span class="text-primary fw-medium"><?= $usuario['email'] ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="ti ti-mail-off me-2"></i>
                                                <span class="small">Sin email registrado</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <?php 
                                        $badgeClass = match($usuario['nivelacceso']) {
                                            'admin' => 'bg-warning text-dark',
                                            'docente' => 'bg-info text-white',
                                            'estudiante' => 'bg-success text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        $icon = match($usuario['nivelacceso']) {
                                            'admin' => 'ti-shield-lock',
                                            'docente' => 'ti-user-check',
                                            'estudiante' => 'ti-school',
                                            default => 'ti-user'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> px-3 py-2 fw-medium rounded-pill" title="<?= ucfirst($usuario['nivelacceso']) ?>">
                                            <i class="ti <?= $icon ?> me-2"></i>
                                            <?= ucfirst($usuario['nivelacceso']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center px-3 py-3">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-outline-info btn-sm" type="button" onclick="verPerfilUsuario(<?= $usuario['idusuario'] ?>)" title="Ver perfil">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="editarUsuario(<?= $usuario['idusuario'] ?>)" title="Editar usuario">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" type="button" onclick="eliminarUsuario(<?= $usuario['idusuario'] ?>)" title="Eliminar usuario">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state p-5">
                                        <div class="rounded-circle bg-light mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                            <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="text-dark mb-2">No hay usuarios registrados</h4>
                                        <p class="text-muted mb-4 lead">Comienza creando tu primer usuario del sistema bibliotecario</p>
                                        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                                            <i class="ti ti-plus me-2"></i> Crear Primer Usuario
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer de la tarjeta con información adicional -->
        <?php if (!empty($usuarios)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <div>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($usuarios) ?> usuario(s) de <?= isset($totalUsuarios) ? $totalUsuarios : count($usuarios) ?>
                </div>
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="ti ti-clock me-1"></i>
                        Actualizado hace pocos minutos
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modales para gestión de usuarios -->
<?php echo view('Administrador/modals/registrarusuario'); ?>
<?php echo view('Administrador/modals/detalleusuario'); ?>
<?php echo view('Administrador/modals/editarusuario'); ?>

<script>
    // Función para ver perfil de usuario
    function verPerfilUsuario(userId) {
        console.log('Ver perfil usuario:', userId);
        
        // Verificar si el modal existe
        const modalElement = document.getElementById('modalDetalleUsuario');
        if (!modalElement) {
            console.error('Modal no encontrado: modalDetalleUsuario');
            alert('Error: Modal de detalles no encontrado');
            return;
        }
        
        console.log('Modal encontrado, procediendo...');
        
        // Guardar ID del usuario en el modal para uso posterior
        modalElement.setAttribute('data-user-id', userId);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        console.log('Modal mostrado');
        
        // Mostrar estado de carga
        document.getElementById('loading-detalle').style.display = 'block';
        document.getElementById('contenido-detalle-usuario').style.display = 'none';
        document.getElementById('error-detalle').style.display = 'none';
        
        // Realizar petición AJAX para obtener datos del usuario
        const url = `<?= base_url('usuarios/obtener') ?>/${userId}`;
        console.log('Realizando petición a:', url);
        
        fetch(url)
            .then(response => {
                console.log('Respuesta recibida:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                document.getElementById('loading-detalle').style.display = 'none';
                
                if (data.success) {
                    // Llenar los datos en el modal
                    const usuario = data.usuario;
                    
                    // Datos personales
                    document.getElementById('detalle-nombres').textContent = usuario.nombres || 'No disponible';
                    document.getElementById('detalle-apellidos').textContent = usuario.apellidos || 'No disponible';
                    document.getElementById('detalle-documento').textContent = usuario.dni || 'No disponible';
                    document.getElementById('detalle-telefono').textContent = usuario.telefono || 'No disponible';
                    document.getElementById('detalle-direccion').textContent = usuario.direccion || 'No disponible';
                    document.getElementById('detalle-email').textContent = usuario.email || 'No disponible';
                    document.getElementById('detalle-genero').textContent = usuario.genero || 'No disponible';
                    
                    // Datos del sistema
                    document.getElementById('detalle-nomuser').textContent = usuario.nomuser || 'No disponible';
                    document.getElementById('detalle-nivelacceso').textContent = usuario.nivelacceso || 'No disponible';
                    document.getElementById('detalle-idusuario').textContent = usuario.idusuario || 'No disponible';
                    document.getElementById('detalle-tipodoc').textContent = usuario.tipodoc || 'No disponible';
                    
                    // Estado del usuario
                    const estadoElement = document.getElementById('detalle-estado');
                    if (usuario.estado == 1) {
                        estadoElement.textContent = 'Activo';
                        estadoElement.className = 'badge bg-success';
                    } else {
                        estadoElement.textContent = 'Inactivo';
                        estadoElement.className = 'badge bg-danger';
                    }
                    
                    // Avatar con iniciales
                    const avatar = document.getElementById('detalle-avatar');
                    if (usuario.nombres && usuario.apellidos) {
                        const iniciales = (usuario.nombres.charAt(0) + usuario.apellidos.charAt(0)).toUpperCase();
                        avatar.textContent = iniciales;
                    } else {
                        avatar.textContent = '--';
                    }
                    
                    // Badge de nivel
                    const nivelBadge = document.getElementById('detalle-nivel-badge');
                    let badgeClass = 'badge fs-6 px-3 py-2';
                    let icon = 'ti-user';
                    
                    switch(usuario.nivelacceso) {
                        case 'admin':
                            badgeClass += ' bg-warning text-dark';
                            icon = 'ti-shield-lock';
                            break;
                        case 'docente':
                            badgeClass += ' bg-info';
                            icon = 'ti-user-check';
                            break;
                        case 'estudiante':
                            badgeClass += ' bg-success';
                            icon = 'ti-school';
                            break;
                        default:
                            badgeClass += ' bg-secondary';
                    }
                    
                    nivelBadge.className = badgeClass;
                    nivelBadge.innerHTML = `<i class="ti ${icon} me-1"></i>${usuario.nivelacceso || 'Usuario'}`;
                    
                    // Fechas
                    const fechaCreacion = usuario.fecha_creacion ? new Date(usuario.fecha_creacion).toLocaleDateString('es-ES') : 'No disponible';
                    document.getElementById('detalle-fecha-registro').textContent = fechaCreacion;
                    
                    // Información académica (solo para estudiantes)
                    if (usuario.nivelacceso === 'estudiante' && usuario.nivel) {
                        document.getElementById('detalle-nivel-academico').textContent = usuario.nivel || 'No disponible';
                        document.getElementById('detalle-grado').textContent = usuario.grado || 'No disponible';
                        document.getElementById('detalle-seccion').textContent = usuario.seccion || 'No disponible';
                        document.getElementById('detalle-anio-lectivo').textContent = usuario.anio_lectivo || 'No disponible';
                        document.getElementById('seccion-matricula').classList.remove('d-none');
                    } else {
                        document.getElementById('seccion-matricula').classList.add('d-none');
                    }
                    
                    document.getElementById('contenido-detalle-usuario').style.display = 'block';
                } else {
                    // Mostrar error
                    console.error('Error en respuesta:', data.message);
                    document.getElementById('error-detalle').style.display = 'block';
                    // Mostrar el mensaje de error específico
                    const errorMsg = document.querySelector('#error-detalle h5');
                    if (errorMsg && data.message) {
                        errorMsg.textContent = data.message;
                    }
                }
            })
            .catch(error => {
                console.error('Error de red o parsing:', error);
                document.getElementById('loading-detalle').style.display = 'none';
                document.getElementById('error-detalle').style.display = 'block';
                
                // Mostrar el error específico
                const errorMsg = document.querySelector('#error-detalle h5');
                if (errorMsg) {
                    errorMsg.textContent = 'Error de conexión: ' + error.message;
                }
            });
    }

    // Función para editar usuario desde el modal de detalles
    function editarUsuarioDesdeDetalle() {
        // Obtener ID del usuario desde el modal de detalles
        const modalDetalleElement = document.getElementById('modalDetalleUsuario');
        const userId = modalDetalleElement.getAttribute('data-user-id');
        
        if (!userId) {
            console.error('No se encontró ID del usuario en modal de detalles');
            return;
        }
        
        // Cerrar modal de detalles
        const modalDetalle = bootstrap.Modal.getInstance(modalDetalleElement);
        modalDetalle.hide();
        
        // Esperar a que se cierre completamente y luego abrir modal de edición
        modalDetalleElement.addEventListener('hidden.bs.modal', function() {
            editarUsuario(userId);
        }, { once: true });
    }

    // Función para ver historial completo
    function verHistorialCompleto() {
        console.log('Ver historial completo');
        // TODO: Implementar vista de historial
    }

    // Función para recargar detalles en caso de error
    function recargarDetalleUsuario() {
        const modalElement = document.getElementById('modalDetalleUsuario');
        const userId = modalElement.getAttribute('data-user-id');
        
        if (userId) {
            // Ocultar error y mostrar loading
            document.getElementById('error-detalle').style.display = 'none';
            document.getElementById('loading-detalle').style.display = 'block';
            
            // Volver a cargar
            verPerfilUsuario(userId);
        }
    }

    // Función para editar usuario (botón en tabla)
    function editarUsuario(userId) {
        console.log('Editar usuario:', userId);
        
        // Verificar si el modal existe
        const modalElement = document.getElementById('modalEditarUsuario');
        if (!modalElement) {
            console.error('Modal de edición no encontrado');
            Swal.fire({
                icon: 'error',
                title: 'Error del sistema',
                text: 'Modal de edición no encontrado',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        // Mostrar modal con loading
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Mostrar estado de carga
        document.getElementById('loading-editar').style.display = 'block';
        const form = document.getElementById('formEditarUsuario');
        if (form) {
            form.style.display = 'none';
        }
        
        // Cargar datos del usuario
        const url = `<?= base_url('usuarios/obtener') ?>/${userId}`;
        console.log('🔍 Cargando datos para edición desde:', url);
        
        fetch(url)
            .then(response => {
                console.log('📥 Respuesta recibida para edición:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('📊 Datos recibidos para edición:', data);
                document.getElementById('loading-editar').style.display = 'none';
                if (form) {
                    form.style.display = 'block';
                }
                
                if (data.success) {
                    const usuario = data.usuario;
                    
                    // Llenar datos ocultos
                    document.getElementById('editar-idusuario').value = usuario.idusuario || '';
                    document.getElementById('editar-idpersona').value = usuario.idpersona || '';
                    
                    // Datos personales
                    document.getElementById('editar-apellidos').value = usuario.apellidos || '';
                    document.getElementById('editar-nombres').value = usuario.nombres || '';
                    document.getElementById('editar-tipodoc').value = usuario.tipodoc || '';
                    document.getElementById('editar-numerodoc').value = usuario.dni || '';
                    document.getElementById('editar-genero').value = usuario.genero || '';
                    document.getElementById('editar-telefono').value = usuario.telefono || '';
                    document.getElementById('editar-direccion').value = usuario.direccion || '';
                    
                    // Datos de usuario
                    document.getElementById('editar-nomuser').value = usuario.nomuser || '';
                    document.getElementById('editar-email').value = usuario.email || '';
                    document.getElementById('editar-nivelacceso').value = usuario.nivelacceso || '';
                    
                    // Limpiar contraseña (no se debe mostrar)
                    document.getElementById('editar-passuser').value = '';
                    
                    // Mostrar/ocultar sección académica si es estudiante
                    const seccionAcademica = document.getElementById('seccion-academica-editar');
                    if (seccionAcademica) {
                        if (usuario.nivelacceso === 'estudiante') {
                            seccionAcademica.classList.remove('d-none');
                            
                            // Llenar datos académicos si existen
                            if (usuario.nivel) {
                                const editarNivel = document.getElementById('editar-nivel');
                                const editarGrado = document.getElementById('editar-grado');
                                const editarSeccion = document.getElementById('editar-seccion');
                                const editarAnioLectivo = document.getElementById('editar-anio-lectivo');
                                
                                if (editarNivel) editarNivel.value = usuario.nivel || '';
                                if (editarGrado) editarGrado.value = usuario.grado || '';
                                if (editarSeccion) editarSeccion.value = usuario.seccion || '';
                                if (editarAnioLectivo) editarAnioLectivo.value = usuario.anio_lectivo || new Date().getFullYear();
                            }
                        } else {
                            seccionAcademica.classList.add('d-none');
                        }
                    } else {
                        console.warn('⚠️ Elemento seccion-academica-editar no encontrado');
                    }
                    
                    console.log('✅ Datos del usuario cargados para edición');
                } else {
                    console.error('❌ Error en respuesta del servidor:', data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar datos',
                        text: data.message || 'No se pudieron cargar los datos del usuario',
                        confirmButtonColor: '#dc3545',
                        footer: `<small>Código de error: ${data.message || 'Desconocido'}</small>`
                    }).then(() => {
                        modal.hide();
                    });
                }
            })
            .catch(error => {
                console.error('❌ Error de red o parsing:', error);
                document.getElementById('loading-editar').style.display = 'none';
                if (form) {
                    form.style.display = 'block';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    html: `
                        <p>No se pudo cargar la información del usuario</p>
                        <small class="text-muted">Detalles técnicos: ${error.message}</small>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ejecutar Diagnóstico',
                    cancelButtonText: 'Cerrar',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        diagnosticarConexion(userId);
                    }
                    modal.hide();
                });
            });
    }

    // Función para eliminar usuario (botón en tabla)
    function eliminarUsuario(userId) {
        console.log('Eliminar usuario:', userId);
        
        // Validar ID de usuario
        if (!userId || userId <= 0) {
            console.error('ID de usuario inválido:', userId);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'ID de usuario inválido',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        // Mostrar confirmación con SweetAlert2
        Swal.fire({
            title: '¿Eliminar usuario?',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p class="mb-2">Esta acción <strong>eliminará permanentemente</strong> el usuario del sistema.</p>
                    <p class="text-muted small mb-0">Esta operación no se puede deshacer.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i>Sí, eliminar',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            focusCancel: true,
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Realizar petición AJAX para eliminar usuario
                const url = `<?= base_url('usuarios/eliminar') ?>/${userId}`;
                console.log('🗑️ Eliminando usuario desde:', url);
                
                return fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('📥 Respuesta de eliminación:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Datos de eliminación:', data);
                    if (!data.success) {
                        throw new Error(data.message || 'Error al eliminar usuario');
                    }
                    return data;
                })
                .catch(error => {
                    console.error('❌ Error al eliminar usuario:', error);
                    Swal.showValidationMessage(`Error: ${error.message}`);
                    throw error;
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Eliminación exitosa
                console.log('✅ Usuario eliminado exitosamente');
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario eliminado!',
                    html: `
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="ti ti-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                            <p class="mb-2">El usuario ha sido eliminado correctamente del sistema.</p>
                            <p class="text-muted small mb-0">La página se actualizará automáticamente.</p>
                        </div>
                    `,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    // Recargar el contenido AJAX de usuarios
                    console.log('🔄 Recargando contenido de usuarios...');
                    
                    // Buscar el enlace de usuarios en el sidebar y simular click
                    const usuariosLink = document.querySelector('a[href="<?= base_url('usuarios') ?>"].ajax-link');
                    if (usuariosLink) {
                        usuariosLink.click();
                    } else {
                        // Fallback: recargar la página completa si no se encuentra el enlace AJAX
                        console.log('⚠️ Enlace AJAX no encontrado, recargando página completa...');
                        location.reload();
                    }
                });
                
            } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                // Cancelación
                console.log('❌ Eliminación cancelada por el usuario');
                
                Swal.fire({
                    icon: 'info',
                    title: 'Operación cancelada',
                    text: 'El usuario no ha sido eliminado',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        }).catch(error => {
            // Error en la eliminación
            console.error('❌ Error final en eliminación:', error);
            
            Swal.fire({
                icon: 'error',
                title: 'Error al eliminar',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="ti ti-x-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <p class="mb-2">No se pudo eliminar el usuario</p>
                        <p class="text-muted small mb-0">Detalles: ${error.message || 'Error desconocido'}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Reintentar',
                cancelButtonText: 'Cerrar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((retryResult) => {
                if (retryResult.isConfirmed) {
                    // Reintentar eliminación
                    setTimeout(() => {
                        eliminarUsuario(userId);
                    }, 500);
                }
            });
        });
    }

    // Función de diagnóstico temporal
    function diagnosticarConexion(userId) {
        console.log('🔧 Diagnóstico de conexión para usuario:', userId);
        
        // Probar endpoint de test
        const testUrl = `<?= base_url('usuarios/test') ?>/${userId}`;
        console.log('🧪 Probando endpoint:', testUrl);
        
        fetch(testUrl)
            .then(response => {
                console.log('🧪 Test response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('🧪 Test data:', data);
                
                if (data.status === 'success') {
                    console.log('✅ Conectividad OK, probando endpoint real...');
                    
                    // Ahora probar el endpoint real
                    const realUrl = `<?= base_url('usuarios/obtener') ?>/${userId}`;
                    console.log('🔍 Probando endpoint real:', realUrl);
                    
                    return fetch(realUrl);
                } else {
                    throw new Error('Test endpoint falló');
                }
            })
            .then(response => {
                console.log('🔍 Real response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('🔍 Real data:', data);
                
                Swal.fire({
                    icon: data.success ? 'success' : 'warning',
                    title: 'Diagnóstico completado',
                    html: `
                        <div class="text-start">
                            <p><strong>Conectividad:</strong> ✅ OK</p>
                            <p><strong>Endpoint:</strong> ${data.success ? '✅ OK' : '❌ Error'}</p>
                            <p><strong>Mensaje:</strong> ${data.message || 'Sin mensaje'}</p>
                            ${data.success ? '<p><strong>Usuario encontrado:</strong> ✅</p>' : ''}
                        </div>
                    `,
                    confirmButtonColor: '#0d6efd'
                });
            })
            .catch(error => {
                console.error('🔧 Error en diagnóstico:', error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error en diagnóstico',
                    html: `
                        <div class="text-start">
                            <p><strong>Error:</strong> ${error.message}</p>
                            <p><strong>Recomendación:</strong> Verificar logs del servidor</p>
                        </div>
                    `,
                    confirmButtonColor: '#dc3545'
                });
            });
    }
</script>
