<!-- Sección de Sanciones Activas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Sanciones Activas</h4>
                        <p class="card-subtitle mb-0">Lista de usuarios con sanciones vigentes</p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="<?= base_url('sanciones') ?>" class="btn btn-sm btn-outline-primary">
                            Ver todas las sanciones
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary recargar-sanciones">
                            <i class="ti ti-refresh"></i> Actualizar
                        </button>
                    </div>
                </div>

                <!-- Contenedor para el indicador de carga -->
                <div class="text-center py-4 loading-sanciones">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 mb-0">Cargando sanciones activas...</p>
                </div>

                <!-- Mensaje de error -->
                <div class="alert alert-danger d-none error-sanciones" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-circle me-2"></i>
                        <span class="error-message">Error al cargar las sanciones. Por favor, inténtalo de nuevo.</span>
                    </div>
                </div>

                <!-- Mensaje cuando no hay sanciones -->
                <div class="text-center py-4 d-none sin-sanciones">
                    <div class="mb-3">
                        <i class="ti ti-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5>¡No hay sanciones activas!</h5>
                    <p class="text-muted">Todos los usuarios están al día con sus préstamos.</p>
                </div>

                <!-- Tabla de sanciones (se llenará con AJAX) -->
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Tipo de Sanción</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Las filas se cargarán aquí con AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Contador de sanciones -->
                <div class="mt-3 text-muted text-end">
                    <small>
                        <span class="contador-sanciones">0</span> sanciones activas
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para cargar sanciones -->
<script src="<?= base_url('assets/js/sanciones-ajax.js') ?>"></script>
