<!-- Modal para filtrar estudiantes -->
<div class="modal fade" id="modalFiltrarEstudiantes" tabindex="-1" aria-labelledby="modalFiltrarEstudiantesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFiltrarEstudiantesLabel">
                    <i class="ti ti-filter me-2"></i>Filtrar Estudiantes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formFiltros">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="filtro_nivel" class="form-label">Nivel</label>
                                <select class="form-select" id="filtro_nivel" name="nivel">
                                    <option value="">Todos los niveles</option>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="filtro_grado" class="form-label">Grado</label>
                                <select class="form-select" id="filtro_grado" name="grado">
                                    <option value="">Todos los grados</option>
                                    <option value="1">1°</option>
                                    <option value="2">2°</option>
                                    <option value="3">3°</option>
                                    <option value="4">4°</option>
                                    <option value="5">5°</option>
                                    <option value="6">6°</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="filtro_seccion" class="form-label">Sección</label>
                                <select class="form-select" id="filtro_seccion" name="seccion">
                                    <option value="">Todas las secciones</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="filtro_anio" class="form-label">Año Lectivo</label>
                                <select class="form-select" id="filtro_anio" name="aniolectivo">
                                    <option value="">Todos los años</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filtro_estado" class="form-label">Estado de Matrícula</label>
                        <select class="form-select" id="filtro_estado" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                        <i class="ti ti-refresh"></i> Limpiar Filtros
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">
                        <i class="ti ti-check"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>