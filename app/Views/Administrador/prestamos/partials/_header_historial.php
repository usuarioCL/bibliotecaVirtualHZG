<!-- Encabezado de la página con breadcrumb -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">
                    <i class="ti ti-history text-primary me-2"></i>
                    Historial Completo de Préstamos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                        <li class="breadcrumb-item active">Historial Completo</li>
                    </ol>
                </nav>
                <p class="text-muted mb-0 mt-1">Consulta el historial completo de todos los préstamos del sistema</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-success btn-sm">
                    <i class="ti ti-file-excel"></i> Exportar Excel
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="Historial.confirmarEliminarTodoHistorial()">
                    <i class="ti ti-trash"></i> Limpiar Historial
                </button>
            </div>
        </div>
    </div>
</div>
