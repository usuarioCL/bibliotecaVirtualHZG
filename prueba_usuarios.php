<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Funcionalidad - Usuarios</title>
    <link rel="stylesheet" href="<?= base_url('./assets/css/styles.min.css') ?>">
    <style>
        body { padding: 20px; }
        .test-section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Prueba de Funcionalidad - Sistema de Usuarios</h1>
    
    <div class="test-section">
        <h2>1. Enlace AJAX del Sidebar</h2>
        <p>El enlace <code>href="<?= base_url('usuarios'); ?>"</code> debe cargar la vista de usuarios.</p>
        <button onclick="probarEnlaceAjax()" class="btn btn-primary">Probar Enlace AJAX</button>
        <div id="resultado-ajax" class="mt-3"></div>
    </div>

    <div class="test-section">
        <h2>2. Prueba de Vista Directa</h2>
        <p>Acceder directamente a la vista de usuarios:</p>
        <a href="<?= base_url('usuarios'); ?>" target="_blank" class="btn btn-success">Abrir Vista de Usuarios</a>
    </div>

    <div class="test-section">
        <h2>3. Verificar Rutas Configuradas</h2>
        <ul>
            <li><strong>GET /usuarios</strong> → UsuarioController::index</li>
            <li><strong>GET /usuarios/crear</strong> → UsuarioController::crear</li>
            <li><strong>GET /usuarios/listar</strong> → UsuarioController::listar (JSON)</li>
            <li><strong>GET /usuarios/verificar-elegibilidad</strong> → UsuarioController::verificarElegibilidad</li>
        </ul>
        <button onclick="probarRutas()" class="btn btn-info">Probar Todas las Rutas</button>
        <div id="resultado-rutas" class="mt-3"></div>
    </div>

    <div class="test-section">
        <h2>4. Datos de Prueba</h2>
        <p>El sistema debería mostrar estos usuarios de ejemplo:</p>
        <ul>
            <li><strong>admin</strong> - Ana García (Administrador)</li>
            <li><strong>docente</strong> - Luis Pérez (Docente)</li>
            <li><strong>estudiante</strong> - Elena Torres (Estudiante)</li>
        </ul>
    </div>

    <script src="<?= base_url('./assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <script>
        function probarEnlaceAjax() {
            const resultado = document.getElementById('resultado-ajax');
            resultado.innerHTML = '<div class="text-info">Probando enlace AJAX...</div>';
            
            fetch('<?= base_url('usuarios'); ?>')
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Error en la respuesta: ' + response.status);
                })
                .then(html => {
                    if (html.includes('Gestión de Usuarios')) {
                        resultado.innerHTML = '<div class="success">✅ ÉXITO: El enlace AJAX funciona correctamente</div>';
                    } else {
                        resultado.innerHTML = '<div class="error">❌ ERROR: La respuesta no contiene el contenido esperado</div>';
                    }
                })
                .catch(error => {
                    resultado.innerHTML = '<div class="error">❌ ERROR: ' + error.message + '</div>';
                });
        }

        function probarRutas() {
            const resultado = document.getElementById('resultado-rutas');
            resultado.innerHTML = '<div class="text-info">Probando rutas...</div>';
            
            const rutas = [
                { url: '<?= base_url('usuarios'); ?>', nombre: 'Vista principal' },
                { url: '<?= base_url('usuarios/listar'); ?>', nombre: 'Listar (JSON)' },
                { url: '<?= base_url('usuarios/verificar-elegibilidad'); ?>?idpersona=1&nivelacceso=estudiante', nombre: 'Verificar elegibilidad' }
            ];
            
            let resultados = [];
            let completed = 0;
            
            rutas.forEach(ruta => {
                fetch(ruta.url)
                    .then(response => {
                        if (response.ok) {
                            resultados.push(`✅ ${ruta.nombre}: OK`);
                        } else {
                            resultados.push(`❌ ${ruta.nombre}: Error ${response.status}`);
                        }
                    })
                    .catch(error => {
                        resultados.push(`❌ ${ruta.nombre}: ${error.message}`);
                    })
                    .finally(() => {
                        completed++;
                        if (completed === rutas.length) {
                            resultado.innerHTML = resultados.map(r => '<div>' + r + '</div>').join('');
                        }
                    });
            });
        }

        // Simular el comportamiento del dashboard
        function simularDashboard() {
            console.log('Simulando comportamiento del dashboard...');
            
            // Simular clic en enlace del sidebar
            $('#contenedor-principal').html('<div class="text-center py-5">Cargando...</div>');
            
            $.get('<?= base_url('usuarios'); ?>', function(data) {
                $('#contenedor-principal').html(data);
                console.log('✅ Contenido cargado exitosamente via AJAX');
            }).fail(function(xhr, status, error) {
                $('#contenedor-principal').html('<div class="text-danger">Error al cargar el contenido: ' + error + '</div>');
                console.log('❌ Error al cargar contenido:', error);
            });
        }
    </script>

    <div class="test-section">
        <h2>5. Simular Dashboard</h2>
        <p>Crear un contenedor como en el dashboard para probar el comportamiento AJAX:</p>
        <button onclick="simularDashboard()" class="btn btn-warning">Simular Carga en Dashboard</button>
        <div id="contenedor-principal" class="mt-3 border p-3" style="min-height: 200px;">
            <div class="text-muted">Contenido se cargará aquí...</div>
        </div>
    </div>

    <div class="test-section">
        <h2>6. Instrucciones de Integración</h2>
        <div class="alert alert-info">
            <h5>Para que funcione en el dashboard:</h5>
            <ol>
                <li>El enlace del sidebar ya está configurado: <code>href="<?= base_url('usuarios'); ?>"</code></li>
                <li>Tiene la clase <code>ajax-link</code> para el manejo AJAX</li>
                <li>El JavaScript del dashboard detectará el clic y cargará el contenido en <code>#contenedor-principal</code></li>
                <li>La ruta está configurada para servir la vista HTML completa</li>
            </ol>
        </div>
    </div>
</body>
</html>
