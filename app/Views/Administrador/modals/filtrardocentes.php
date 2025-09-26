<!-- Modal para filtrar docentes -->
<div class="modal fade" id="modalFiltrarDocentes" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar Docentes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltrarDocentes">
                    <div class="mb-3">
                        <label for="filtro-nivel" class="form-label">Nivel Educativo</label>
                        <select class="form-select" id="filtro-nivel" name="nivel">
                            <option value="">Todos los niveles</option>
                            <option value="Inicial">Inicial</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria</option>
                            <option value="Todos">Todos los niveles</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filtro-especialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="filtro-especialidad" name="especialidad">
                            <option value="">Todas las especialidades</option>
                            <option value="Matemáticas">Matemáticas</option>
                            <option value="Comunicación">Comunicación</option>
                            <option value="Ciencias">Ciencias</option>
                            <option value="Inglés">Inglés</option>
                            <option value="Educación Física">Educación Física</option>
                            <option value="Arte">Arte</option>
                            <option value="Historia">Historia</option>
                            <option value="Geografía">Geografía</option>
                            <option value="Religión">Religión</option>
                            <option value="Informática">Informática</option>
                            <option value="Psicología">Psicología</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filtro-estado" class="form-label">Estado</label>
                        <select class="form-select" id="filtro-estado" name="estado">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filtro-fecha-desde" class="form-label">Fecha de ingreso desde</label>
                        <input type="date" class="form-control" id="filtro-fecha-desde" name="fecha_desde">
                    </div>

                    <div class="mb-3">
                        <label for="filtro-fecha-hasta" class="form-label">Fecha de ingreso hasta</label>
                        <input type="date" class="form-control" id="filtro-fecha-hasta" name="fecha_hasta">
                    </div>

                    <div class="mb-3">
                        <label for="filtro-buscar" class="form-label">Buscar por nombre o documento</label>
                        <input type="text" class="form-control" id="filtro-buscar" name="buscar" placeholder="Nombre, apellido o número de documento">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</div>

<script>
function aplicarFiltros() {
    const form = document.getElementById('formFiltrarDocentes');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Mostrar indicador de carga
    const boton = event.target;
    const textoOriginal = boton.textContent;
    boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Aplicando...';
    boton.disabled = true;
    
    fetch(`<?= base_url('docentes/filtrar') ?>?${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Actualizar la tabla con los resultados filtrados
            actualizarTablaDocentes(data.docentes);
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalFiltrarDocentes')).hide();
            
            // Mostrar mensaje de éxito
            mostrarMensaje(`Se encontraron ${data.docentes.length} docente(s) con los filtros aplicados`, 'success');
        } else {
            mostrarMensaje('Error al aplicar filtros: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('Error de conexión al aplicar filtros', 'danger');
    })
    .finally(() => {
        // Restaurar botón
        boton.textContent = textoOriginal;
        boton.disabled = false;
    });
}

function limpiarFiltros() {
    document.getElementById('formFiltrarDocentes').reset();
    // Recargar página para mostrar todos los docentes
    location.reload();
}

function actualizarTablaDocentes(docentes) {
    // Implementar actualización de tabla
    console.log('Actualizando tabla con:', docentes);
    // Por ahora, recargamos la página
    location.reload();
}

function mostrarMensaje(mensaje, tipo) {
    // Crear y mostrar alerta temporal
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alerta);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        alerta.remove();
    }, 5000);
}
</script>