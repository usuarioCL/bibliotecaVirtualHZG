<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca Virtual HZG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">
    <style>
        body {
            background: linear-gradient(135deg, var(--cream-bg) 0%, #f8f9fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            margin: 20px;
        }
        .login-header {
            background: linear-gradient(135deg, var(--institutional-red) 0%, #d73527 100%);
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--institutional-red);
            box-shadow: 0 0 0 0.2rem rgba(185, 28, 28, 0.15);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--institutional-red) 0%, #d73527 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(185, 28, 28, 0.3);
        }
        .btn-back-home {
            background: white;
            border: 2px solid var(--institutional-red);
            color: var(--institutional-red);
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-back-home:hover {
            background: var(--institutional-red);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(185, 28, 28, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header minimalista -->
        <div class="login-header">
            <i class="fas fa-book-open fa-3x text-white mb-3" style="position: relative; z-index: 1;"></i>
            <h1 class="text-white fw-bold mb-2" style="position: relative; z-index: 1;">Biblioteca Virtual</h1>
            <p class="text-white opacity-75 mb-0" style="position: relative; z-index: 1;">Centro Educativo HZG</p>
        </div>

        <!-- Formulario -->
        <div class="p-4">
            <?php if (session('error')): ?>
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i><?= session('error') ?>
            </div>
            <?php endif; ?>

            <form action="/login" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold mb-2">
                        <i class="fas fa-user me-2" style="color: var(--institutional-red);"></i>Usuario
                    </label>
                    <input type="text" 
                           id="username" 
                           name="nomuser" 
                           class="form-control" 
                           placeholder="Ingresa tu usuario"
                           required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold mb-2">
                        <i class="fas fa-lock me-2" style="color: var(--institutional-red);"></i>Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="passuser" 
                           class="form-control" 
                           placeholder="Ingresa tu contraseña"
                           required>
                </div>

                <button type="submit" class="btn btn-login text-white w-100 fw-semibold">
                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                </button>
            </form>

            <a href="<?= base_url('/') ?>" class="btn btn-back-home w-100 fw-semibold mt-3 text-decoration-none">
                <i class="fas fa-home me-2"></i>Volver
            </a>

            <!-- Footer minimalista -->
            <div class="text-center mt-4 pt-3" style="border-top: 1px solid #e9ecef;">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>Acceso seguro al sistema
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Limpiar espacios en blanco al enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            
            // Limpiar espacios al inicio y final
            usernameInput.value = usernameInput.value.trim();
            passwordInput.value = passwordInput.value.trim();
            
            // Validar que no estén vacíos después del trim
            if (!usernameInput.value || !passwordInput.value) {
                e.preventDefault();
                alert('Por favor complete todos los campos');
                return false;
            }
        });
    </script>
</body>
</html>
