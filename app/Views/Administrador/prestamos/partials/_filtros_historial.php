<!-- Filtros rápidos -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Período:</label>
                        <select class="form-select form-select-sm" id="periodoFiltro">
                            <option value="">Todos los períodos</option>
                            <option value="hoy">Hoy</option>
                            <option value="semana">Esta semana</option>
                            <option value="mes" selected>Este mes</option>
                            <option value="trimestre">Este trimestre</option>
                            <option value="ano">Este año</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Estado:</label>
                        <select class="form-select form-select-sm" id="estadoFiltro">
                            <option value="">Todos los estados</option>
                            <option value="devuelto">Devuelto</option>
                            <option value="devuelto_retraso">Devuelto con retraso</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Buscar:</label>
                        <input type="text" class="form-control form-control-sm" id="busquedaRapida" placeholder="Usuario, documento, recurso...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary btn-sm w-100" onclick="Historial.aplicarFiltros()">
                            <i class="ti ti-search me-1"></i>Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
