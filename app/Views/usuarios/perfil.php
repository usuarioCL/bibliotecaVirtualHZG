<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4 mb-5">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-user-circle me-3"></i>Mi Perfil
                    </h1>
                    <p class="text-muted mb-0">Información personal y de cuenta</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Personal -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">NOMBRE COMPLETO</label>
                        <p class="mb-0 fs-5">
                            <?= esc($usuario['nombres']) ?> <?= esc($usuario['apellidos']) ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-bold">TIPO DE DOCUMENTO</label>
                        <p class="mb-0">
                            <i class="fas fa-id-card me-2 text-primary"></i>
                            <?= esc(strtoupper($usuario['tipodoc'])) ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-bold">NÚMERO DE DOCUMENTO</label>
                        <p class="mb-0">
                            <i class="fas fa-hashtag me-2 text-primary"></i>
                            <?= esc($usuario['numerodoc']) ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-bold">CORREO ELECTRÓNICO</label>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <?= esc($usuario['email']) ?>
                        </p>
                    </div>

                    <?php if (!empty($usuario['telefono'])): ?>
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">TELÉFONO</label>
                        <p class="mb-0">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            <?= esc($usuario['telefono']) ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($usuario['direccion'])): ?>
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">DIRECCIÓN</label>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            <?= esc($usuario['direccion']) ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($usuario['genero'])): ?>
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">GÉNERO</label>
                        <p class="mb-0">
                            <i class="fas fa-venus-mars me-2 text-primary"></i>
                            <?= esc(ucfirst($usuario['genero'])) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Información de Cuenta -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Información de Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">NOMBRE DE USUARIO</label>
                        <p class="mb-0">
                            <i class="fas fa-user-tag me-2 text-success"></i>
                            <?= esc($usuario['nomuser']) ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-bold">NIVEL DE ACCESO</label>
                        <p class="mb-0">
                            <?php
                            $badgeClass = 'badge bg-secondary';
                            $icon = 'fas fa-user';
                            
                            if ($usuario['nivelacceso'] === 'admin') {
                                $badgeClass = 'badge bg-danger';
                                $icon = 'fas fa-user-shield';
                            } elseif ($usuario['nivelacceso'] === 'docente') {
                                $badgeClass = 'badge bg-info';
                                $icon = 'fas fa-chalkboard-teacher';
                            } elseif ($usuario['nivelacceso'] === 'estudiante') {
                                $badgeClass = 'badge bg-primary';
                                $icon = 'fas fa-user-graduate';
                            }
                            ?>
                            <span class="<?= $badgeClass ?> fs-6">
                                <i class="<?= $icon ?> me-2"></i>
                                <?= esc(ucfirst($usuario['nivelacceso'])) ?>
                            </span>
                        </p>
                    </div>

                    <?php if ($usuario['nivelacceso'] === 'estudiante' && !empty($usuario['matricula'])): ?>
                    <div class="mt-4">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Información de Matrícula
                        </h6>
                        <?php if (isset($usuario['matricula']['grado'])): ?>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold">GRADO</label>
                            <p class="mb-0">
                                <i class="fas fa-book-reader me-2 text-success"></i>
                                <?= esc($usuario['matricula']['grado']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($usuario['matricula']['seccion'])): ?>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold">SECCIÓN</label>
                            <p class="mb-0">
                                <i class="fas fa-layer-group me-2 text-success"></i>
                                <?= esc($usuario['matricula']['seccion']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($usuario['matricula']['anio'])): ?>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold">AÑO ACADÉMICO</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-alt me-2 text-success"></i>
                                <?= esc($usuario['matricula']['anio']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php elseif ($usuario['nivelacceso'] === 'estudiante'): ?>
                    <div class="mt-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small>No tienes matrícula activa registrada.</small>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Si necesitas actualizar tu información personal o cambiar tu contraseña, contacta con el administrador.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $footer ?>
