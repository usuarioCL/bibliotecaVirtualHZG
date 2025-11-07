<!-- Modal para ver ejemplares -->
<div class="modal fade" id="modalEjemplares" tabindex="-1" aria-labelledby="modalEjemplaresLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEjemplaresLabel">Ejemplares Físicos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoEjemplares">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando ejemplares...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Ejemplar -->
<div class="modal fade" id="modalEditarEjemplar" tabindex="-1" aria-labelledby="modalEditarEjemplarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarEjemplarLabel">Editar Ejemplar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarEjemplar">
                <div class="modal-body">
                    <input type="hidden" name="idejemplar" id="editIdejemplar">
                    <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado Operativo</label>
                        <select class="form-select" id="editEstado" name="estado" required>
                            <option value="disponible">Disponible</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editEstadoFisico" class="form-label">Estado Físico</label>
                        <select class="form-select" id="editEstadoFisico" name="estado_fisico" required>
                            <option value="excelente">Excelente</option>
                            <option value="bueno">Bueno</option>
                            <option value="regular">Regular</option>
                            <option value="malo">Malo</option>
                            <option value="muy_malo">Muy Malo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editUbicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="editUbicacion" name="ubicacion" maxlength="100" placeholder="Ej: Estante A-1, Sección Literatura">
                    </div>
                    <div class="mb-3">
                        <label for="editObservaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="editObservaciones" name="observaciones" rows="3" maxlength="500" placeholder="Detalles sobre el estado del ejemplar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar Ejemplar</button>
                </div>
            </form>
        </div>
    </div>
</div>
