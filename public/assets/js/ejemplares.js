// Variables globales para mantener el contexto del modal
let currentIdRecurso = null;
let currentTitulo = null;

function verEjemplares(idrecurso, titulo) {
    currentIdRecurso = idrecurso;
    currentTitulo = titulo;
    
    document.getElementById('modalEjemplaresLabel').textContent = 'Ejemplares de: ' + titulo;
    
    var modal = new bootstrap.Modal(document.getElementById('modalEjemplares'));
    modal.show();
    
    fetch(baseUrl + 'ejemplares-fisicos/modal/' + idrecurso)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenidoEjemplares').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('contenidoEjemplares').innerHTML = 
                '<div class="alert alert-danger">Error al cargar los ejemplares.</div>';
        });
}

function recargarModalEjemplares() {
    if (currentIdRecurso) {
        document.getElementById('contenidoEjemplares').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Actualizando ejemplares...</p>
            </div>
        `;
        
        fetch(baseUrl + 'ejemplares-fisicos/modal/' + currentIdRecurso)
            .then(response => response.text())
            .then(html => {
                document.getElementById('contenidoEjemplares').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('contenidoEjemplares').innerHTML = `
                    <div class="alert alert-danger">Error al cargar los ejemplares.</div>
                `;
            });
    }
}

function editarRecurso(id) {
    alert('Editar recurso #' + id);
}

function eliminarRecurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este recurso físico?')) {
        alert('Eliminar recurso #' + id);
    }
}

window.abrirModalEditar = function(idejemplar, estado, estadoFisico, ubicacion, observaciones) {
    const modalEditar = document.getElementById('modalEditarEjemplarInterno');
    const editIdejemplar = document.getElementById('editIdejemplarInterno');
    const editEstado = document.getElementById('editEstadoInterno');
    const editEstadoFisico = document.getElementById('editEstadoFisicoInterno');
    const editUbicacion = document.getElementById('editUbicacionInterno');
    const editObservaciones = document.getElementById('editObservacionesInterno');
    
    if (modalEditar && editIdejemplar && editEstado && editEstadoFisico && editUbicacion && editObservaciones) {
        editIdejemplar.value = idejemplar;
        editEstado.value = estado;
        editEstadoFisico.value = estadoFisico;
        editUbicacion.value = ubicacion || '';
        editObservaciones.value = observaciones || '';
        modalEditar.style.zIndex = '1060';
        
        const modal = new bootstrap.Modal(modalEditar);
        modal.show();
    }
}

window.guardarEdicionEjemplar = function() {
    const idejemplar = document.getElementById('editIdejemplarInterno').value;
    const estado = document.getElementById('editEstadoInterno').value;
    const estadoFisico = document.getElementById('editEstadoFisicoInterno').value;
    const ubicacion = document.getElementById('editUbicacionInterno').value;
    const observaciones = document.getElementById('editObservacionesInterno').value;
    
    if (!idejemplar) {
        Swal.fire('Error', 'ID del ejemplar no encontrado', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('idejemplar', idejemplar);
    formData.append('estado', estado);
    formData.append('estado_fisico', estadoFisico);
    formData.append('ubicacion', ubicacion);
    formData.append('observaciones', observaciones);
    
    fetch(baseUrl + 'ejemplares-fisicos/actualizar-estado', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 3000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarEjemplarInterno'));
                if (modalEditar) modalEditar.hide();
                
                setTimeout(() => recargarModalEjemplares(), 300);
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Hubo un problema al actualizar el ejemplar.', 'error');
    });
};

document.addEventListener('abrirModalEditarEjemplar', function(e) {
    const data = e.detail;
    const modalEjemplares = bootstrap.Modal.getInstance(document.getElementById('modalEjemplares'));
    if (modalEjemplares) modalEjemplares.hide();
    
    setTimeout(function() {
        document.getElementById('editIdejemplar').value = data.idejemplar;
        document.getElementById('editEstado').value = data.estado;
        document.getElementById('editEstadoFisico').value = data.estadoFisico;
        document.getElementById('editUbicacion').value = data.ubicacion;
        document.getElementById('editObservaciones').value = data.observaciones;
        
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarEjemplar'));
        modalEditar.show();
    }, 300);
});

document.getElementById('modalEditarEjemplar')?.addEventListener('hidden.bs.modal', function() {
    const backdrops = document.querySelectorAll('.modal-backdrop');
    if (backdrops.length > 1) {
        for (let i = 1; i < backdrops.length; i++) backdrops[i].remove();
    }
    
    const modalEjemplares = document.getElementById('modalEjemplares');
    if (modalEjemplares && !modalEjemplares.classList.contains('show')) {
        new bootstrap.Modal(modalEjemplares).show();
    }
    
    if (!document.body.classList.contains('modal-open')) {
        document.body.classList.add('modal-open');
    }
});

document.addEventListener('submit', function(e) {
    if (e.target.id === 'formEditarEjemplar') {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch(baseUrl + 'ejemplares-fisicos/actualizar-estado', {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarEjemplar'));
                if (modalEditar) modalEditar.hide();
                
                setTimeout(() => {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => recargarModalEjemplares());
                }, 100);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => Swal.fire('Error', 'Hubo un problema al actualizar el ejemplar.', 'error'));
    }
});
