<!-- Modal para matricular estudiante -->
<div class="modal fade" id="modalNuevoEstudiante" tabindex="-1" aria-labelledby="modalNuevoEstudianteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoEstudianteLabel">
                    <i class="ti ti-user-plus me-2"></i>Matricular Nuevo Estudiante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNuevoEstudiante">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        Complete la información del estudiante y seleccione el grupo al que pertenecerá.
                    </div>
                    <div class="alert alert-success">
                        <i class="ti ti-key me-2"></i>
                        <strong>Credenciales de acceso:</strong> Se creará automáticamente un usuario con contraseña predeterminada <code class="bg-white px-2 py-1 rounded">123456</code>
                    </div>

                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Información Personal</h6>
                            
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="numerodoc" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numerodoc" name="numerodoc" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>

                        <!-- Información Académica -->
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Información Académica</h6>
                            
                            <div class="mb-3">
                                <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                                <select class="form-select" id="nivel" name="nivel" required onchange="cargarGrados()">
                                    <option value="">Seleccionar nivel</option>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grado" class="form-label">Grado <span class="text-danger">*</span></label>
                                        <select class="form-select" id="grado" name="grado" required disabled>
                                            <option value="">Seleccionar grado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="seccion" class="form-label">Sección <span class="text-danger">*</span></label>
                                        <select class="form-select" id="seccion" name="seccion" required>
                                            <option value="">Seleccionar</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="aniolectivo" class="form-label">Año Lectivo <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="aniolectivo" name="aniolectivo" 
                                       value="<?= date('Y') ?>" min="2020" max="2030" required>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta para mensajes -->
                    <div id="alertaMatricula" class="alert d-none" role="alert"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="matricularEstudiante()">
                        <i class="ti ti-check"></i> Registrar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para cargar grados según el nivel seleccionado
function cargarGrados() {
    const nivel = document.getElementById('nivel').value;
    const gradoSelect = document.getElementById('grado');
    
    // Limpiar opciones existentes
    gradoSelect.innerHTML = '<option value="">Seleccionar grado</option>';
    
    if (nivel) {
        gradoSelect.disabled = false;
        
        let grados = [];
        if (nivel === 'Inicial') {
            grados = ['3', '4', '5'];
        } else if (nivel === 'Primaria') {
            grados = ['1', '2', '3', '4', '5', '6'];
        } else if (nivel === 'Secundaria') {
            grados = ['1', '2', '3', '4', '5'];
        }
        
        grados.forEach(grado => {
            const option = document.createElement('option');
            option.value = grado;
            option.textContent = grado + '°';
            gradoSelect.appendChild(option);
        });
    } else {
        gradoSelect.disabled = true;
    }
}

// Limpiar formulario cuando se cierra el modal
document.getElementById('modalNuevoEstudiante').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formNuevoEstudiante').reset();
    document.getElementById('grado').disabled = true;
    document.getElementById('grado').innerHTML = '<option value="">Seleccionar grado</option>';
    document.getElementById('alertaMatricula').classList.add('d-none');
});
</script>