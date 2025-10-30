<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center h-100">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-history me-3"></i>Historial de Notificaciones
                    </h1>
                    <p class="text-muted mb-0">Todas tus notificaciones en un solo lugar</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card bg-info bg-gradient text-white border-0 shadow-sm">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-bell fa-2x me-3"></i>
                            <div>
                                <small class="text-white-50 d-block">Sin Leer</small>
                                <h3 class="text-white mb-0 fw-bold"><?= $contador ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <?php if (!empty($notificaciones)): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">Mostrando <?= count($notificaciones) ?> notificaciones</span>
                </div>
                <div>
                    <button class="btn btn-outline-success btn-sm" onclick="eliminarTodas()">
                        <i class="fas fa-check-double me-1"></i>Marcar todas como leídas
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista de notificaciones -->
    <?php if (!empty($notificaciones)): ?>
        <div class="row">
            <?php foreach ($notificaciones as $notif): ?>
                <?php
                    $iconoTipo = [
                        'aprobacion' => 'fas fa-check-circle',
                        'rechazo' => 'fas fa-times-circle',
                        'vencimiento' => 'fas fa-exclamation-triangle',
                        'renovacion' => 'fas fa-sync-alt',
                        'devolucion' => 'fas fa-undo',
                        'sancion' => 'fas fa-shield-alt'
                    ];
                    $colorTipo = [
                        'aprobacion' => '#28a745',
                        'rechazo' => '#dc3545',
                        'vencimiento' => '#ffc107',
                        'renovacion' => '#17a2b8',
                        'devolucion' => '#6c757d',
                        'sancion' => '#dc3545'
                    ];
                    $icono = $iconoTipo[$notif['tipo']] ?? 'fas fa-bell';
                    $color = $colorTipo[$notif['tipo']] ?? '#007bff';
                    $leida = $notif['leida'] == 1;
                ?>
                <div class="col-12 mb-3">
                    <div class="card <?= !$leida ? 'border-primary' : '' ?> shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="<?= $icono ?>" style="color: <?= $color ?>; font-size: 2rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">
                                            <?= esc($notif['titulo']) ?>
                                            <?php if (!$leida): ?>
                                                <span class="badge bg-primary ms-2">NUEVA</span>
                                            <?php endif; ?>
                                        </h5>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-2"><?= esc($notif['mensaje']) ?></p>
                                    <?php if (!empty($notif['recurso_titulo'])): ?>
                                        <p class="mb-0">
                                            <small class="text-info">
                                                <i class="fas fa-book me-1"></i><?= esc($notif['recurso_titulo']) ?>
                                            </small>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="ms-3">
                                    <button class="btn btn-sm btn-outline-success" 
                                            onclick="eliminarNotificacionHistorial(<?= $notif['idnotificacion'] ?>)"
                                            title="Marcar como leída">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes notificaciones</h4>
                    <p class="text-muted">Cuando recibas notificaciones, aparecerán aquí</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// CAMBIO 2025-10-30: Scripts para gestión de notificaciones en el historial

// Elimina una notificación individual y recarga la página
function eliminarNotificacionHistorial(idNotificacion) {
    fetch('<?= base_url('notificaciones/eliminar') ?>', {
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
            }).then(() => {
                location.reload();
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
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}

// Elimina TODAS las notificaciones del usuario
// Valida que existan notificaciones antes de mostrar confirmación
function eliminarTodas() {
    const totalNotificaciones = <?= count($notificaciones) ?>;
    
    // Si no hay notificaciones, mostrar mensaje informativo
    if (totalNotificaciones === 0) {
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
            fetch('<?= base_url('notificaciones/eliminar-todas') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: 'Todas las notificaciones han sido leídas',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
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
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor'
                });
            });
        }
    });
}
</script>

<?= $footer ?>
