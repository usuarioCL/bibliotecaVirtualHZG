<!-- Modal para nuevo docente -->
<div class="modal fade" id="modalNuevoDocente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoDocente" autocomplete="off">
                    <!-- Datos de la Persona -->
                    <h6 class="text-primary mb-3">Datos Personales</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required autofocus placeholder="Ej: García López">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required placeholder="Ej: María Elena">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tipodoc" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="tipodoc" name="tipodoc" required>
                                    <option value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numerodoc" class="form-label">Número de Documento</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="numerodoc" name="numerodoc" required maxlength="15" placeholder="Ej: 12345678">
                                    <button type="button" class="btn btn-outline-secondary" onclick="buscarPorDni()" title="Buscar persona por DNI">
                                        <i class="icon tabler-search fs-6"></i>
                                    </button>
                                </div>
                                <div id="info-busqueda" class="form-text d-none"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15" placeholder="Ej: 999888777">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" maxlength="100" placeholder="Ej: Av. Siempre Viva 123">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Ej: maria.garcia@colegio.edu.pe">
                                <div class="form-text">Email institucional del docente</div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Usuario -->
                    <hr>
                    <h6 class="text-success mb-3">Información del Sistema</h6>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        Se creará automáticamente un usuario con nivel de acceso "docente" y contraseña temporal "123456".
                        El nombre de usuario se generará automáticamente basado en el nombre y apellido.
                    </div>

                    <div id="alertaValidacion" class="alert d-none mt-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="registrarDocente()">Registrar Docente</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para buscar persona por DNI
function buscarPorDni() {
    const numerodoc = document.getElementById('numerodoc').value.trim();
    const infoBusqueda = document.getElementById('info-busqueda');
    
    if (numerodoc.length < 8) {
        mostrarAlerta('Ingrese un número de documento válido (mínimo 8 caracteres)', 'warning');
        return;
    }

    // Mostrar loading
    const botonBuscar = event.target.closest('button');
    const iconoOriginal = botonBuscar.innerHTML;
    botonBuscar.innerHTML = '<i class="icon tabler-loader-2 spin fs-6"></i>';
    botonBuscar.disabled = true;

    fetch(`/docentes/buscar-por-dni?numerodoc=${numerodoc}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.encontrado) {
                const persona = data.datos;
                
                if (persona.es_docente) {
                    mostrarAlerta('Esta persona ya está registrada como docente', 'warning');
                } else {
                    // Llenar el formulario con los datos encontrados
                    document.getElementById('apellidos').value = persona.apellidos || '';
                    document.getElementById('nombres').value = persona.nombres || '';
                    document.getElementById('tipodoc').value = persona.tipodoc || 'DNI';
                    document.getElementById('telefono').value = persona.telefono || '';
                    document.getElementById('direccion').value = persona.direccion || '';
                    document.getElementById('email').value = persona.email || '';
                    document.getElementById('genero').value = persona.genero || '';
                    
                    infoBusqueda.className = 'form-text text-success';
                    infoBusqueda.innerHTML = '<i class="ti ti-check"></i> Persona encontrada - datos cargados automáticamente';
                    infoBusqueda.classList.remove('d-none');
                    
                    mostrarAlerta('Persona encontrada. Se han cargado los datos automáticamente.', 'success');
                }
            } else {
                infoBusqueda.className = 'form-text text-info';
                infoBusqueda.innerHTML = '<i class="ti ti-info-circle"></i> No se encontró ninguna persona con ese documento. Se registrará como nueva persona.';
                infoBusqueda.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al buscar la persona', 'danger');
        })
        .finally(() => {
            // Restaurar botón
            botonBuscar.innerHTML = iconoOriginal;
            botonBuscar.disabled = false;
        });
}

// Función para registrar docente
function registrarDocente() {
    const form = document.getElementById('formNuevoDocente');
    const formData = new FormData(form);
    
    // Validar campos requeridos
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const botonRegistrar = event.target;
    botonRegistrar.disabled = true;
    botonRegistrar.innerHTML = '<i class="ti ti-loader-2 spin"></i> Registrando...';

    fetch('/docentes/guardar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            mostrarAlerta('Docente registrado exitosamente', 'success');
            document.getElementById('modalNuevoDocente').querySelector('[data-bs-dismiss="modal"]').click();
            form.reset();
            location.reload(); // Recargar para mostrar el nuevo docente
        } else {
            mostrarAlerta(data.message || 'Error al registrar docente', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error al registrar docente', 'danger');
    })
    .finally(() => {
        botonRegistrar.disabled = false;
        botonRegistrar.innerHTML = 'Registrar Docente';
    });
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alerta = document.getElementById('alertaValidacion');
    alerta.className = `alert alert-${tipo}`;
    alerta.innerHTML = mensaje;
    alerta.classList.remove('d-none');
    
    setTimeout(() => {
        alerta.classList.add('d-none');
    }, 5000);
}

// Limpiar información de búsqueda al cambiar el número de documento
document.getElementById('numerodoc').addEventListener('input', function() {
    const infoBusqueda = document.getElementById('info-busqueda');
    infoBusqueda.classList.add('d-none');
});
</script>