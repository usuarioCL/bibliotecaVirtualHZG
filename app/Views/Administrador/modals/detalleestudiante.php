<!-- Modal para ver detalles del estudiante -->
<div class="modal fade" id="modalDetalleEstudiante" tabindex="-1" aria-labelledby="modalDetalleEstudianteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleEstudianteLabel">
                    <i class="ti ti-user me-2"></i>Detalles del Estudiante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido dinámico generado por JavaScript -->
                <div id="detalleEstudianteContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando información del estudiante...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>